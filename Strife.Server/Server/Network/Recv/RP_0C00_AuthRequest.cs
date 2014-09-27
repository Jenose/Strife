using Server.Database;
using Server.Network.Send;
using Server.Utilities;

namespace Server.Network.Recv
{
    public class RP_0C00_AuthRequest : NetRecvPacket
    {
        protected short UNK;
        protected int AccountID;
        protected string UNK2;

        protected internal override void Read()
        {
            UNK = ReadH(); // always EC 00 // 236
            AccountID = ReadD();
            UNK2 = ReadS(); // USE
        }

        protected internal override void Run()
        {
            var account = DBAccount.GetInstance().GetAccount(AccountID);

            if (account != null)
            {
                this._Client._Account = account;
                this._Client._Account.Unk = UNK;
                this._Client._Identity = DBAccount.GetInstance().GetAccountIdentity(AccountID);
                NetFactory.ConnectedClients.Add(this._Client);

                // response
                this._Client.SendPacket(new SP_1C00_AuthResponse());
            }
            //this._Client.Disconnect();
        }
    }
}
