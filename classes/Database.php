<?php
    class Database{
        private $host            = "localhost";
        private $dbname          = "store";
        private $uname           = "root";
        private $password        = "";
        private static $instance = null;
        private $con;

        private function __construct(){}

        public static function getInstance(){
            if(self::$instance == null){
                self::$instance = new Database();
            }
                return self::$instance;
        }

        public function getConnection(){
            if($this->con == null){
                try{
                    $this->con = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->uname, $this->password);
                    $this->con->exec("set names utf8");
                    $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }catch(PDOException $exception){
                    error_log("Connection error: " .$exception->getMessage());
                    die("Connection error : please connect support"); //avoid display error details for users
                }
            }
            return $this->con;
        }

    }
?>