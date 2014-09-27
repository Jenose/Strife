using System;
using System.Net;
using System.Net.Sockets;
using System.Threading;
using Server.Database.Model;
using Server.Utilities;

namespace Server.Network
{
    public class NetClient
    {
        public EndPoint _address;
        public TcpClient _client;
        public NetworkStream _stream;
        private byte[] _buffer;

        public Account _Account;
        public AccountIdentity _Identity;
        public AccountManifest _Manifest;
        //public account_info _Info;

        public NetClient(TcpClient tcpClient)
        {
            _client = tcpClient;
            _stream = tcpClient.GetStream();
            _address = tcpClient.Client.RemoteEndPoint;

            new Thread(new ThreadStart(BeginRead)).Start();
        }

        private void BeginRead()
        {
            try
            {
                if (this._stream == null || !this._stream.CanRead)
                    return;

                this._buffer = new byte[2];
                this._stream.BeginRead(_buffer, 0, 2, new AsyncCallback(OnReceiveCallbackStatic), (object)null);
            }
            catch (Exception ex)
            {
                Log.ErrorException("BeginRead", ex);
            }
        }

        private void OnReceiveCallbackStatic(IAsyncResult ar)
        {
            try
            {
                if (this._stream.EndRead(ar) <= 0)
                    return;

                short length = BitConverter.ToInt16(this._buffer, 0);
                if (this._stream.DataAvailable)
                {
                    this._buffer = new byte[(int)length];
                    this._stream.BeginRead(this._buffer, 0, (int)length, new AsyncCallback(OnReceiveCallback), ar.AsyncState);
                }
            }
            catch (Exception ex)
            {
                Log.ErrorException("OnReceiveCallbackStatic", ex);
            }
        }

        private void OnReceiveCallback(IAsyncResult ar)
        {
            this._stream.EndRead(ar);
            byte[] data = new byte[this._buffer.Length];
            this._buffer.CopyTo((Array)data, 0);

            if (data.Length >= 2)
                handlePacket(data);

            new Thread(new ThreadStart(BeginRead)).Start();
        }

        private void handlePacket(byte[] Data)
        {
            short opcode = BitConverter.ToInt16(new byte[2] { Data[0], Data[1] }, 0);

            if (NetOpcode.Recv.ContainsKey(opcode))
            {
                ((NetRecvPacket)Activator.CreateInstance(NetOpcode.Recv[opcode])).execute(this, Data);
            }
            else
            {
                string opCodeLittleEndianHex = BitConverter.GetBytes(opcode).ToHex();
                Log.Debug("Unknown Opcode: 0x{0}{1} [{2}]",
                                 opCodeLittleEndianHex.Substring(2),
                                 opCodeLittleEndianHex.Substring(0, 2),
                                 Data.Length);

                Log.Debug("Data:\n{0}", Data.FormatHex());
            }
        }

        internal void Disconnect()
        {
            this._buffer = null;
            this._client.Close();
            this._client = null;
            this._stream.Dispose();
            this._stream = null;
        }

        public void SendPacket(NetSendPacket packet)
        {
            packet._Client = this;

            if (!NetOpcode.Send.ContainsKey(packet.GetType()))
            {
                Log.Warn("UNKNOWN packet opcode: {0}", packet.GetType().Name);
                return;
            }

            try
            {
                packet.WriteH(0); // packet len
                packet.WriteH(NetOpcode.Send[packet.GetType()]); // opcode
                packet.Write();

                byte[] Data = packet.ToByteArray();
                BitConverter.GetBytes((short)(Data.Length - 2)).CopyTo(Data, 0);

                Log.Debug("Send: {0}", Data.FormatHex());
                this._stream.BeginWrite(Data, 0, Data.Length, new AsyncCallback(EndSendCallBackStatic), (object)null);
            }
            catch (Exception ex)
            {
                Log.Warn("Can't send packet: {0}", GetType().Name);
                Log.WarnException("ASendPacket", ex);
                return;
            }
        }

        public void Send(byte[] buff)
        {
            try
            {
                Log.Debug("Send: {0}", buff.FormatHex());
                this._stream.BeginWrite(buff, 0, buff.Length, new AsyncCallback(EndSendCallBackStatic), (object)null);
            }
            catch (Exception ex)
            {
                Log.Warn("Can't send packet: {0}", GetType().Name);
                Log.WarnException("ASendPacket", ex);
                return;
            }
        }

        private void EndSendCallBackStatic(IAsyncResult ar)
        {
            try
            {
                this._stream.EndWrite(ar);
                this._stream.Flush();
            }
            catch (Exception ex)
            {
                Log.WarnException("EndSendCallBackStatic", ex);
            }
        }
    }
}
