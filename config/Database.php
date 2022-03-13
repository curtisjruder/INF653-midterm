<?php
    class Database{
        private $conn;
    
        private $hostname;
        private $username;
        private $password;
        private $database;


        public function __construct(){
            echo "Constructor";
            $url = getenv('JAWSDB_URL');
            $dbparts = parse_url($url);

            $this->hostname = $dbparts['host'];
            $this->username = $dbparts['user'];
            $this->password = $dbparts['pass'];
            $this->database = ltrim($dbparts['path'],'/');
            echo "\nConstructorEND\n";
        }

        public function connect(){          
            try {
                $this->$conn = new PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->database, $this->username, getenv('JAWSPASS'));
                $this->$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            }
            catch(PDOException $e)
            {
                echo "Connection failed: " . $e->getMessage();
            }            
        }
    }