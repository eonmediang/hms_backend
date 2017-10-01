<?php 

use Core\Database as db;

class uids
{
    private $db;

    public function run()
    {
        $users = $this->getAllUsers();
        return $this->genUuids( $users );
    }

    public function getAllUsers()
    {
        $this->db = new db(
            [
                'db'        => 'sdb00001',
                'user'      => 'test_user',
                'password'  => 'test_pass'
            ] 
        );
        $sql = "SELECT * FROM users";
        return $this->db->fetchAll($sql);
    }

    public function genUuids( $data )
    {
        $uuids = [];
        foreach ($data as $d) {
            $id = $d->id;
            $uuid = Uuid::genUid(1, $id);
            // echo $uuid, ' | ';
            array_push( $uuids, 
                [
                    ':uuid' => $uuid,
                    'id'    => $id
                ]
            );
        }

        // var_dump($uuids);

        $sql = "UPDATE users SET uuid = :uuid WHERE id = :id";
        return $this->db->UpdateMany( $sql, $uuids);
    }
}