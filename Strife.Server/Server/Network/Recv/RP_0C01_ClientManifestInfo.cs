using Server.Network.Send;

namespace Server.Network.Recv
{
    /// <summary>
    /// 5e00
    /// 010c
    /// 6acccd26b5b49f6bc13a24eac2038ad86c9df781ac67971a93ba2dc591d920814a116ddd52f651e8c286cde45c178954b1b2141bb55f69bd1252ecceea582323010074680077696e646f77730078383600000401027669645f643900
    /// </summary>
    public class RP_0C01_ClientManifestInfo : NetRecvPacket
    {
        protected byte[] UNKHash;
        protected short UNK2;
        protected string REGION;
        protected string OS;
        protected string ARCH;
        protected byte MAJOR;
        protected byte MINOR;
        protected byte MICRO;
        protected byte HOTFIX;
        protected string VIDDRV;

        protected internal override void Read()
        {
            UNKHash = ReadB(64);
            UNK2 = ReadH();
            REGION = ReadS();
            OS = ReadS();
            ARCH = ReadS();
            MAJOR = ReadC();
            MINOR = ReadC();
            MICRO = ReadC();
            HOTFIX = ReadC();
            VIDDRV = ReadS();
        }

        protected internal override void Run()
        {
            this._Client._Manifest = new Database.Model.AccountManifest()
            {
                Hash = UNKHash,
                Unk = UNK2,
                Region = REGION,
                Os = OS,
                Architecture = ARCH,
                Major = MAJOR,
                Minor = MINOR,
                Micro = MICRO,
                Hotfix = HOTFIX,
                VideoDriver = VIDDRV
            };

            this._Client.SendPacket(new SP_1C01_Unknown());
        }
    }
}
