using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Server.Database.Model
{
    public class Account
    {
        public int AccountId { get; set; }
        public string Display { get; set; }
        public string Email { get; set; }
        public string Password { get; set; }
        public EStatus Status { get; set; }
        public string AccountType { get; set; }
        public int Provider { get; set; }
    }

    public class AccountIdentity
    {
        public int UniqId { get; set; }
        public int AccountId { get; set; }
        public string Nickname { get; set; }
        public EStatus Status { get; set; }
        public string PlayerType { get; set; }
        public int Level { get; set; }
        public int Experience { get; set; }
        public string Description { get; set; }
        public int TutorialProgress { get; set; }
        public byte CanCraft { get; set; }
        public byte CanPlayRanked { get; set; }
        public string IdentId { get; set; }
    }

    public enum EStatus
    {
        disabled = 0,
        enabled = 1
    }
}
