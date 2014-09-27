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

            Recv.Add(unchecked((short)0x0C00), typeof(UNK0C00));

            #endregion

            #region Send

            Send.Add(typeof(UNK1C00), unchecked((short)0x1C00));

            #endregion
        }
    }
}
