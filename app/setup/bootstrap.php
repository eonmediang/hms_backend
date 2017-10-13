<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $errors = [];

    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $db_host = ! empty($_POST['db_host']) ? $_POST['db_host'] : 'localhost';
    $db_name = ! empty($_POST['db_name']) ? $_POST['db_name'] : 'laa_db';
    $db_user = ! empty($_POST['db_user']) ? $_POST['db_user'] : 'root';
    $db_pass = ! empty($_POST['db_pass']) ? $_POST['db_pass'] : '';

    if ( strlen($password) < 5 )
        $errors[] = "Your password is too short. It can't be less than 5 characters.";

    if ( $password !== $cpassword )
        $errors[] = "Your passwords don't match. Please try again.";

    // proceed only if there are no errors
    if (empty($errors)){
        // Import UUID class
        require_once __DIR__.'/../lib/classes/UUID.php';
        // Import functions
        require_once __DIR__.'/../lib/functions.php';
        // Hash admin password
        $hashedPassword = genNewPassword($password);

        // Generate UID. Since this is always going to be the first admin user
        // it is safe to assume that the ID will always be `1`.
        $uid = genUid(1);

        // Set up database tables
        try {
            $conn = new PDO("mysql:host=$db_host", $db_user, $db_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DROP DATABASE IF EXISTS `$db_name`";
            $conn->exec($sql);
            $sql = "CREATE DATABASE `$db_name`";
            $conn->exec($sql);
            $sql = "USE `$db_name`";
            $conn->exec($sql);
            $sql = "DROP TABLE IF EXISTS `admin`";
            $conn->exec($sql);
            $sql = "CREATE TABLE `admin` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uid` int(11) NOT NULL,
                `fname` varchar(30) NOT NULL,
                `lname` varchar(30) NOT NULL,
                `username` varchar(30) NOT NULL,
                `phone` varchar(15) NOT NULL,
                `email` varchar(30) NOT NULL,
                `password` varchar(150) NOT NULL,
                `dt` datetime NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $conn->exec($sql);
            $sql = "DROP TABLE IF EXISTS `associations`;";
            $conn->exec($sql);
            $sql = "CREATE TABLE `associations` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $conn->exec($sql);
            $sql = "INSERT INTO `associations` (`id`, `name`) VALUES
                (1,	'Amalgamated Traders Association'),
                (2,	'United Butchers Association'),
                (3,	'Jigawa Butchers Association'),
                (4,	'Lagos State Butchers Association'),
                (5,	'Irepodun Association');";
            $conn->exec($sql);
            $sql = "DROP TABLE IF EXISTS `members`;";
            $conn->exec($sql);
            $sql = "CREATE TABLE `members` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uid` bigint(20) NOT NULL,
                `fname` varchar(30) NOT NULL,
                `mname` varchar(30) NOT NULL,
                `lname` varchar(30) NOT NULL,
                `phone` varchar(20) NOT NULL,
                `address` varchar(500) NOT NULL,
                `sex` char(1) NOT NULL,
                `dob` datetime NOT NULL,
                `m_status` char(1) NOT NULL,
                `img` varchar(500) NOT NULL,
                `assoc_id` int(11) NOT NULL,
                `dt` datetime NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
              $conn->exec($sql);

              // Add admin data
              $sql = "INSERT INTO `admin` (`id`, `uid`, `fname`, `lname`, username, phone, email, `password`) VALUES
                (1,	$uid, '', '', '$username', '', '', '$hashedPassword');";
                $conn->exec($sql);
                $conn = null;

            // Save database details into `.env` file
            $env = file_get_contents(__DIR__.'/../.env');
            $env = str_replace('[DATABASE_NAME]', $db_name, $env);
            $env = str_replace('[DATABASE_HOST]', $db_host, $env);
            $env = str_replace('[DATABASE_USER]', $db_user, $env);
            $env = str_replace('[DATABASE_PASS]', $db_pass, $env);

            file_put_contents(__DIR__.'/../.env', $env);

            // Generate index file from stub
            $stub = file_get_contents(__DIR__.'/stubs/index.php');
            file_put_contents(__DIR__.'/../../public/index.php', $stub);
            header('Location: /');
        }
        catch(PDOException $e)
        {
            echo $sql;
            $errors[] = $e->getMessage();
        }
    }
}