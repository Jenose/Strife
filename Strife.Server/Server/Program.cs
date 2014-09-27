using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Server.Network;
using System.Diagnostics;

namespace Server
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.Title = "Strife Server";

            NetOpcode.Init();
            new NetFactory().Listen(7031);

            Process.GetCurrentProcess().WaitForExit();
        }
    }
}
