using System;
using System.Collections.Generic;
using Server.Network.Recv;
using Server.Network.Send;

namespace Server.Network
{
    public class NetOpcode
    {
        public static Dictionary<short, Type> Recv = new Dictionary<short, Type>();
        public static Dictionary<Type, short> Send = new Dictionary<Type, short>();

        public static void Init()
        {
            #region Recv

            Recv.Add(unchecked((short)0x0C00), typeof(RP_0C00_AuthRequest));
            Recv.Add(unchecked((short)0x0C01), typeof(RP_0C01_ClientManifestInfo));

            #endregion

            #region Send

            Send.Add(typeof(SP_1C00_AuthResponse), unchecked((short)0x1C00));
            Send.Add(typeof(SP_1C01_Unknown), unchecked((short)0x1C01));

            #endregion
        }
    }
}
