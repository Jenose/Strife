using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Server.Network.Send
{
    /// <summary>
    /// 2000
    /// 011c
    /// 32333600ec0076292a004a656e6f73650037373931007800000000000000
    /// </summary>
    public class SP_1C01_Unknown : NetSendPacket
    {
        protected internal override void Write()
        {
            WriteS(this._Client._Account.Unk.ToString());
            WriteH(this._Client._Account.Unk);
            WriteD(this._Client._Account.AccountId);
            WriteS(this._Client._Identity.Nickname);
            WriteS(this._Client._Identity.UniqId.ToString());
            WriteQ(120);
        }
    }
}
