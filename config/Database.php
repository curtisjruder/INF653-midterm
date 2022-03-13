<?php
    class Database{
        private $hostname;
        private $database;
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