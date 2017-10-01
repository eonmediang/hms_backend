<?php

class User extends Database
{

  protected $lastAddedUserId;
  protected $dbConn;
  protected $dbObj;
  public $table = 'users';


  function __construct()
  {
    $this->dbConn = $this->getConnection();
  }

  public function allUsers()
  {
    // $db_name = ($this->db === 'users') ? 'users' : 'admins';
    // $sql = 'SELECT * FROM '.$db_name;
    return $this->queryAll();
  }

  public function addPendingUser($userData)
  {

    return $this->insertDb('pending_activation', $userData);

  }

  public function removePendingUser($userData)
  {

    return $this->deleteById('pending_activation', $userData);

  }

  public function addUser($userData)
  {
    if ( $this->db !== 'users' ) {
      $this->queryError = 'Wrong DB selected. This operation can only be carried out on the main users database.';
      return false;
    }
    return $this->insertDb('users', $userData);

  }

  public function addNewAdmin($userData)
  {
    if ( $this->db !== 'admins' ) {
      $this->queryError = 'Wrong DB selected. This operation can only be carried out on the admin users database.';
      return false;
    }
    return $this->insertDb('admins', $userData);

  }

  public function deleteUser($userId)
  {
    return $this->deleteById('users', $userId);
  }

  public function editUser($userData, $condition="")
  {

    $this->update('users', $condition, $userData);

  }

  public function getFollowers($id)
  {
    $sql = "SELECT * FROM follows WHERE uid = :id";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;

  }

  public function getFollowing($id)
  {
    $sql = "SELECT * FROM follows WHERE fid = :id";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;

  }

  public function getFollowersCount($id)
  {
    $sql = "SELECT COUNT(fid) FROM follows WHERE uid = :id";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_COLUMN);
    return $result;

  }

  public function getFollowingCount($id)
  {
    $sql = "SELECT COUNT(fid) FROM follows WHERE fid = :id";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_COLUMN);
    return $result;

  }

  public function getUserDetails($id)
  {
    return $this->queryByField('users', 'id', $id);
  }

  public function getProfileImg($id)
  {
    $result = $this->queryByField('users', 'id', $id);
    return $result->profile_pic;
  }

  public function isFollowing($userId, $followedUserId)
  {
    $sql = "SELECT COUNT(*) FROM follows WHERE fid = :userId AND uid = :followedUserId";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':followedUserId', $followedUserId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_COLUMN);
    return ($result == 0) ? false : true;

  }

  public function userExists($id)
  {
    return $this->queryByField('users', 'id', $id);
  }

  public function confirmUsername($username)
  {
    return $this->queryByField('users', 'username', $username);
  }

  public function confirmActivation($actCode, $udc)
  {

      try {

        $sql = 'SELECT * FROM pending_activation WHERE activation_code = :activation_code';
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':activation_code', $actCode, PDO::PARAM_STR);

        $stmt->execute();
        $numRows = $stmt->rowCount();

        if ($numRows >= 1){

          $row = $stmt->fetchObject();
          $userData = array(

            'id'        =>  $row->id,
            'activation_code' =>  $row->activation_code,
            'timestamp'     =>  $row->timestamp,
            'user_details'    =>  $row->user_details

            );

          if ($udc == substr(md5($row->timestamp), 0, 8)){

            return $userData;

          } else return false;

        } else {

          return false;
        }
        
       } catch (PDOException $e) {

        echo $this->connError = $e->getMessage();
        
      }

  }

  public function validateLogin($loginData)
  {
    if ( ! isset($loginData['login']) || !isset($loginData['password']) ){

      //  Exit early
      return false;
    }
  
    //  Set username and email to the same value for comparison
    $username = $email = $loginData['login'];

    //  Password
    $password = $loginData['password'];

    $sql = 'SELECT id, username, user_pass, profile_pic FROM users WHERE ';
    $sql .= 'username = :username OR user_email = :user_email LIMIT 1';
    try {
      $stmt = $this->dbConn->prepare($sql);
      $stmt->bindValue(':username', $username, PDO::PARAM_STR);
      $stmt->bindValue(':user_email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);

      if ($result){
        return ( verifyPassword($password, $result->user_pass) ) ? $result : false;
      }


        
    } catch (PDOException $e) {
      $this->queryError = $e->getMessage();
      return false;
    }
  }

  public function followUser($id, $loggedInUserId)
  {
    $loggedInUser = $loggedInUser;
    $userToFollow = $username;
    $followData = array(

      'fid'      =>  $loggedInUser,
      'uid'      =>  $userToFollow,
      'time_followed' => time()

      );

    return $this->insertDb('follows', $followData);
  }

  public function unfollowUser($id, $loggedInUserId)
  {
    $loggedInUser   = $loggedInUser;
    $userToUnfollow = htmlentities($username);
    
    try {

      $sql            = "DELETE FROM follows WHERE fid = :follower AND uid = :userToUnfollow";
      $stmt           = $this->dbConn->prepare($sql);
      $stmt->bindValue(':userToUnfollow', $userToUnfollow, PDO::PARAM_STR);
      $stmt->bindValue(':follower', $loggedInUser, PDO::PARAM_STR);
      $stmt->execute();
      return true;

    } catch(PDOException $e){
      $this->queryError = $e->getMessage();
      return false;
    }

  }

}