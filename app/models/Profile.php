<?php 

use Core\Requests;
use Core\Registry;

class Profile 
{
    private $db = null;

    public function __construct()
    {
        $this->db = Registry::getInstance()->get('Core\Database');
    }

    public function get( $uid )
    {
        $result = $this->getUserDetails($uid);

        if ( ! $result )
            return ['msg' => 'This user does not exist. Please confirm your details', 'success' => 0];

        $result->password = $result->id = '';

        return $result;
    }

    public function getUserDetails( $uid )
    {
        $sql = "SELECT * 
                FROM `admin`
                WHERE `admin`.`uid` = :uid";

        return $this->db->fetchOne( $sql, ['uid' => $uid]);
    }

    public function update()
    {
        $form = Requests::form();
        $uid = $form->uid;
        $user = $this->getUserDetails( $uid );

        if ( ! $user )
            return ['msg' => 'This user does not exist. Please confirm your details', 'success' => 0];

        $fname = $form->fname;
        $lname = $form->lname;
        $username = $form->username;
        $email = $form->email;
        $phone = $form->phone;
        $opassword = $form->opassword;
        $npassword = $form->npassword;

        // Check if 'new' email has already been taken
        if ( strtolower( $email ) !== strtolower( $user->email ) ){
        
            $sql = "SELECT * 
                    FROM `admin`
                    WHERE `admin`.`email` = :email";

            $result = $this->db->fetchOne($sql, [
                'email' => $email
            ]);

            if ( $result )
                return ['msg' => 'This email address is already taken.', 'success' => 0];

        }

        $sql = "UPDATE `admin`
                SET `admin`.`fname` = :fname,
                `admin`.`lname` = :lname,
                `admin`.`email` = :email,
                `admin`.`username` = :username,
                    `admin`.`phone` = :phone";

        $params = [
            'id' => $user->id,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
        ];

        // If a new password was entered, check that the old one entered is correct
        if ( $npassword ){
            if ( $opassword == "" )
                return ['msg' => 'Please enter your old password.', 'success' => 0];
            if ( ! verifyPassword( $opassword, $user->password ) )
                return ['msg' => 'The old password you entered is incorrect.', 'success' => 0];
            $opassword = $npassword;

            // Update SQL query
            $sql .= ", `admin`.`password` = :password";

            // Update params
            $params['password'] = genNewPassword($npassword);
        }

        // Continue SQL query.
        $sql .= " WHERE `admin`.`id` = :id";

        // Update
        $update = $this->db->updateOne( $sql, $params );

        if ( $update )
            return ['msg' => 'Update successful', 'success' => 1];

        if ( $this->db->errorCode == '23000')
            return ['msg' => 'This username has already been taken.', 'success' => 0];

        return $this->db->errorCode;
    }
}