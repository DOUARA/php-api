<?php 

class DB {
    private $dbname = 'rest_api';
    private $host = 'db';
    private $port = '3306';
    private $user = 'user';
    private $password = 'password';

    public function connect() {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname;port=$this->port";
        $conn = new PDO($conn_str, $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }

}