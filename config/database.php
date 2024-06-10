<?php

class Database
{

    private string $servername = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $dbname = "Authentication";

    public function connect(): ?PDO
    {
        try {

            $bdd = new PDO("mysql:host=$this->servername;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $bdd;
        } catch (PDOException $e) {

            error_log("DB connection failed: " . $e->getMessage());
            return null;
        }
    }
}