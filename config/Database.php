<?php
    class Database{
        private $host = 'm7az7525jg6ygibs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
        private $db_name = 'p5vpahtl3pwjhxaq';
        private $username = 'dxlwbgjn3608z8ld';
        private $password = getenv();
        private $conn;

        public function connect(){
            $this->conn = null;

            try {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }

            return $this->conn;
        }
    }