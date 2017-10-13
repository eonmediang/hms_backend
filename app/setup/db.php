<?php

class DB {

    private $db_conn;
    private $db_name;

    public function __construct( $host, $db_name, $user, $password)
    {
        $this->db_name = $db_name;
        $this->user = $user;
        $this->password = $password;
        // $opt = [
            
        //             PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        //             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //             PDO::ATTR_EMULATE_PREPARES   => false,
        
        //         ];
    
        // try {
        //     $this->db_conn = new PDO("mysql:host={$host};", $user, $password, $opts);
        //     // $this->db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     $sql = "CREATE DATABASE `{$this->db_name}`";
        //     $this->db_conn->query($sql);
        // } catch (PDOException $e) {
        //     die("DB ERROR: ". $e->getMessage());
        // }

        // Create connection
        $conn = new mysqli($host, $user, $password);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        // Create database
        $sql = "CREATE DATABASE `{$this->db_name}`";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $conn->error;
        }

        $conn->query("USE $db_name");

        $conn->close();
    }

    public function createAdmin($username, $password)
    {
        $sql = file_get_contents(__DIR__.'/db/admin.sql');
        $sql = str_replace('[DATABASE_NAME]', $this->db_name, $sql);

        $opt = [
            
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
        
                ];
    
        try {
            $this->db_conn = new PDO("mysql:host={$host};dbname={$this->db_name}", $this->user, $this->password, $opts);
            $this->db_conn->exec($sql);
        } catch (PDOException $e) {
            die("DB ERROR: ". $e->getMessage());
        }

        // try {
        //     $this->db_conn->exec($sql) or die(print_r($this->db_conn->errorInfo(), true));    
        // } catch (PDOException $e) {
        //     die("DB ERROR: ". $e->getMessage());
        // }
    }
    
    public function createDatabase()
    {
        $sql = "CREATE DATABASE {$this->db_name}";
    
        try {
            $this->db_conn->exec($sql);
            echo 'Done';
            // $this->db_conn->execute($sql) or die(print_r($this->db_conn->errorInfo(), true));
    
        } catch (PDOException $e) {
            die("DB ERROR: ". $e->getMessage());
        }

        // Create connection
        $conn = new mysqli($host, $user, $password);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        // Create database
        $sql = "CREATE DATABASE `{$this->db_name}`";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $conn->error;
        }

        $conn->close();
    }

}