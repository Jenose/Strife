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
        public short Unk { get; set; }
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

    public class AccountManifest
    {
        public byte[] Hash { get; set; }
        public short Unk { get; set; }
        public string Region { get; set; }
        public string Os { get; set; }
        public string Architecture { get; set; }
        public byte Major { get; set; }
        public byte Minor { get; set; }
        public byte Micro { get; set; }
        public byte Hotfix { get; set; }
        public string VideoDriver { get; set; }
    }

    public enum EStatus
    {
        disabled = 0,
        enabled = 1
    }
}
