<?php 

use Core\Requests as Request;
use Core\Registry;

class Auth 
{
    private $db = null;

    public function __construct()
    {
        $this->db = Registry::getInstance()->get('Core\Database');
    }

    public function login()
    {
        $form = Request::form();
        $username = $form->username;
        $password = $form->password;

        $sql = "SELECT * 
                FROM `admin`
                WHERE `admin`.`username` = :username";

        $result = $this->db->fetchOne( $sql, ['username' => $username]);

        if ( ! $result )
            return ['msg' => 'This user does not exist. Please confirm your details', 'success' => 0];

        if ( ! verifyPassword( $password, $result->password) )
            return ['msg' => 'Your password is incorrect. Please try again', 'success' => 0];

        return ['uid' => genUid( $result->id ), 'success' => 1];
    }
}