using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net.Sockets;
using System.Net;
using Server.Utilities;

namespace Server.Network
{
    public class NetFactory
    {
        private TcpListener _Listener;

        private ushort Port;

        public static List<NetClient> ConnectedClients = new List<NetClient>();

        public void Listen(ushort port)
        {
            try
            {
                Port = port;
                _Listener = new TcpListener(IPAddress.Any, Port);
                _Listener.Start();
                _Listener.BeginAcceptTcpClient(new AsyncCallback(BeginAcceptTcpClient), (object)null);

                Log.Info("Server Listening on Port: {0}", port);
            }
            catch (Exception ex)
            {
                Log.ErrorException("NetFactory.Listen", ex);
            }
        }

        private void BeginAcceptTcpClient(IAsyncResult ar)
        {
            var tcpclient = _Listener.EndAcceptTcpClient(ar);
            new NetClient(tcpclient);
            Log.Info("Client {0} Connected", tcpclient.Client.AddressFamily);

            _Listener.BeginAcceptTcpClient(new AsyncCallback(BeginAcceptTcpClient), (object)null);
        }
    }
}
