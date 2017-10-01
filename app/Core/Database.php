<?php namespace Core;

use \PDO as PDO;

class Database extends PDO
{
    // Hold DB connection
    private $conn = null;

    // Check if connection is open.
    public $connected = false;

    // Keeps count of last query result
    protected $resultCount;

    // Last query error generated
    public $queryError = '';

    // Error code
    public $errorCode;

    // ID of last inserted row
    public $lastInsertedId;

    // Hold current PDO statement
    protected $stmt;

    // Is a transaction in progress?
    protected $transaction = false;

    public function __construct($db_config = []){

        $this->connect( $db_config );

    }

    public function close()
    {
        $this->conn = null;
    }

    public function connect($db_config = [])
    {
        $dbhost = $db_config['host'] ?? $_ENV['DB_SERVER'];
        $dbport = $db_config['port'] ?? 3306;
        $dbname = $db_config['db'] ?? $_ENV['CENTRAL_DB'];
        $dbuser = $db_config['user'] ?? $_ENV['DB_USER'];
        $dbpass = $db_config['password'] ?? $_ENV['DB_PASS'];

        $opt = [

            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,

        ];

        $this->conn = new PDO('mysql:host='.$dbhost.';port='.$dbport.';dbname='.$dbname, $dbuser, $dbpass, $opt);

        $this->connected = true;

        // Log this connection with the DBMonitor.
        $caller = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        \Core\Registry::getInstance()->get('DBMonitor')->log( $caller );

    }

    public function delete($sql, $vals = [])
    {
        try {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->execute($vals);
            return $this->conn->lastInsertId();
        } catch(PDOException $e){
            $this->error($e);
            return false;
        }
    }

    public function error($e)
    {
        $q = $this->queryError = $e->getMessage();
        error_log( $q, 0);
        $this->errorCode = $this->stmt->errorCode();

    }

    public function fetchOne($sql, $values = [])
    {
        try {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->execute($values);
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        } catch(PDOException $e){
            $this->error($e);
            return false;
        }
    }

    public function fetchAll($sql, $values = [])
    {
        try {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->execute($values);
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        } catch(PDOException $e){
            $this->error($e);
            return false;
        }
    }

    public function insertOne($sql, $vals = [])
    {
        try {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->execute($vals);
            if ( ! $this->transaction )
                return $this->conn->lastInsertId();
        } catch(PDOException $e){
            $this->error($e);
            return false;
        }
    }

    public function insertMany($sql, $vals=[])
    {
        $i_transaction = false;

        if ( ! $this->transaction ){
            $i_transaction = true;
            $this->start();
        }

        try {
            $this->stmt = $this->conn->prepare($query);
            foreach ($vals as $val) {
                $this->stmt->execute( $val );
            }
            return true;
        } catch( PDOException $e){
            $this->error($e);
            if ( $i_transaction )
                $this->revert();
            return false;
        }
        
    }

    public function query($query, $values = [], $update = false)
    {
        try {
            $this->stmt = $this->conn->prepare($query);
            $this->stmt->execute( $values);
            return true;

        } catch( PDOException $e){
            $this->error($e);
        }

        // Return result if this is not part of a transaction.
        if ( ! $this->transaction ){
                if ( $update ) return $this->stmt->rowCount();
                return $this->stmt->fetchAll( PDO::FETCH_OBJ );
            }
    }

    public function revert()
    {
        $this->conn->rollBack();
    }

    public function save()
    {
        $this->conn->commit();
        $this->transaction = false;
    }

    public function start()
    {
        $this->conn->beginTransaction();
        $this->transaction = true;
    }

    public function updateOne($query, $values = [])
    {
        try {
            $this->stmt = $this->conn->prepare($query);
            $this->stmt->execute( $values);
            return true;

        } catch( PDOException $e){
            $this->error($e);
            return false;
        }
    }

    public function updateMany($query, $values = [])
    {
        $i_transaction = false;

        try {
            $this->stmt = $this->conn->prepare($query);
            if ( ! $this->transaction ){
                $i_transaction = true;
                $this->start();
            }
            foreach ($values as $val) {

                $this->stmt->execute( $val );
            }

            if ( $i_transaction )
                $this->save();
            return true;
            
        } catch( PDOException $e){
            $this->error($e);
            if ( $i_transaction )
                $this->revert();
            return false;
        }
    }

}