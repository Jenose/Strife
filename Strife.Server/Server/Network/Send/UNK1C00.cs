using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Server.Utilities;

namespace Server.Network.Send
{
    /// <summary>
    /// 2200
    /// 001c
    /// b5f3a0d25657f13f7bc2671dd162d07429ad253a75dff6d7f7bfbbb64cdb9731
    /// </summary>
    public class UNK1C00 : NetSendPacket
    {
        public UNK1C00()
        {

        }

        protected internal override void Write()
        {
            WriteB("b5f3a0d25657f13f7bc2671dd162d07429ad253a75dff6d7f7bfbbb64cdb9731".ToBytes());
        }
    }
}
