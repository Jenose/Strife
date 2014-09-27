using System;
using MySql.Data.MySqlClient;
using Server.Database.Model;

namespace Server.Database
{
    /// <summary>
    /// DB Account, Account Identity, Account Info
    /// </summary>
    public class DBAccount
    {
        private static DBAccount _Instance;

        private MySqlConnection connection;
        private string server;
        private string database;
        private string uid;
        private string password;

        public DBAccount()
        {
            Initialize();
        }

        private void Initialize()
        {
            server = "localhost";
            database = "strife";
            uid = "root";
            password = "";
            string connectionString = "SERVER=" + server + ";" + "DATABASE=" +
            database + ";" + "UID=" + uid + ";" + "PASSWORD=" + password + ";";

            connection = new MySqlConnection(connectionString);
            connection.Open();
        }

        public Account GetAccount(int accountId)
        {
            MySqlCommand cmd = connection.CreateCommand();
            cmd.CommandText = string.Format("select * from account where account_id = {0}", accountId);
            MySqlDataReader reader = cmd.ExecuteReader();

            Account acc = null;

            while (reader.Read())
            {
                acc = new Account();
                acc.AccountId = Convert.ToInt32(reader["account_id"]);
                acc.Display = reader["display"].ToString();
                acc.Email = reader["email"].ToString();
                acc.Password = reader["password"].ToString();
                acc.Status = (EStatus)Enum.Parse(typeof(EStatus), reader["status"].ToString());
                acc.Provider = Convert.ToInt32(reader["provider"]);
            }
            reader.Close();

            return (acc != null) ? acc : null;
        }

        public AccountIdentity GetAccountIdentity(int accountId)
        {
            MySqlCommand cmd = connection.CreateCommand();
            cmd.CommandText = string.Format("select * from account_identity where account_id = {0}", accountId);
            MySqlDataReader reader = cmd.ExecuteReader();

            AccountIdentity ident = null;

            while (reader.Read())
            {
                ident = new AccountIdentity();
                ident.UniqId = Convert.ToInt32(reader["uniqid"]);
                ident.AccountId = Convert.ToInt32(reader["account_id"]);
                ident.Nickname = reader["nickname"].ToString();
                ident.Status = (EStatus)Enum.Parse(typeof(EStatus), reader["status"].ToString());
                ident.PlayerType = reader["playerType"].ToString();
                ident.Level = Convert.ToInt32(reader["level"]);
                ident.Experience = Convert.ToInt32(reader["experience"]);
                ident.Description = reader["description"].ToString();
                ident.TutorialProgress = Convert.ToInt32(reader["tutorialProgress"]);
                ident.CanCraft = Convert.ToByte(reader["canCraft"]);
                ident.CanPlayRanked = Convert.ToByte(reader["canPlayRanked"]);
                ident.IdentId = reader["ident_id"].ToString();
            }
            reader.Close();

            return (ident != null) ? ident : null;
        }

        public static DBAccount GetInstance()
        {
            return (_Instance != null) ? _Instance : _Instance = new DBAccount();
        }
    }
}
