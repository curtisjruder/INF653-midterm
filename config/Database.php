<?php
    class Database{
        private $conn;
    
        private $hostname;
        private $username;
        private $password;
        private $database;

        public function connect(){            
            $url = getenv('JAWSDB_URL');
            $dbparts = parse_url($url);

            $this->hostname = $dbparts['host'];
            $this->username = $dbparts['user'];
            $this->password = $dbparts['pass'];
            $this->database = ltrim($dbparts['path'],'/');

            try {
                $this->$conn = new PDO("mysql:host=$this->hostname;dbname=$this->database", $this->username, $this->password);
                $this->$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            }
            catch(PDOException $e)
            {
                echo "Connection failed: " . $e->getMessage();
            }            
        }
    }