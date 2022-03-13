<?php
    class Database{
        private $host;
        private $db_name;
        private $username;
        private $password;
        private $conn;

        public function connect(){
            
            $url = getenv('JAWSDB_URL');
            $dbparts = parse_url($url);
        
            $this->hostname = $dbparts['host'];
            $this->username = $dbparts['user'];
            $this->password = $dbparts['pass'];
            $this->database = ltrim($dbparts['path'],'/');
            //You cannot do the above for your local dev environment, just Heroku
        
            // Create your new PDO connection here
            // This is also from the Heroku docs showing the PDO connection: 
            try {
                $this->$conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
              // set the PDO error mode to exception
              $this->$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              echo "Connected successfully";
            }
            catch(PDOException $e)
            {
              echo "Connection failed: " . $e->getMessage();
            }

            return $this->conn;
        }
    }