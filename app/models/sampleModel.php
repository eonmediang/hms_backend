<?php 

class SampleModel {

    public function __construct()
    {
        // Get global database connection
        $this->db = \Core\Registry::get('Core\Database');
    }

    public function addSampleUser()
    {
        $int = random_int(1, 2000);
        $username = 'user'.$int;

        $sql = "INSERT INTO test (username) 
                VALUES (:user)";

        return $this->db->insert( $sql, [ 'user' => $username ] );
    }

    public function deleteUser( $val )
    {
        $int = random_int(1, 2000);
        $username = 'user'.$int;

        $sql = "DELETE FROM test 
                WHERE id = :id 
                OR username = :username";

        return $this->db->query( $sql, [
            'id' => $val,
            'username' => $val
             ] );
    }

    public function getRecord($val)
    {
        $sql = "SELECT * FROM test 
                WHERE id = :id 
                OR username = :username";

        return $this->db->fetchOne( $sql, [
            'id' => $val,
            'username' => $val
        ] );
    }
}