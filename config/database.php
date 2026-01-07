<?php

class Database
{
    private $host = "localhost";
    private $db_name = "unity_care_clinic_v2";
    private $username = "root";
    private $password = "";

    public function connect()
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;

        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
}
