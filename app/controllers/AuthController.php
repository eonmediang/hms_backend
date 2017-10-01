<?php

use \Core\Requests as Requests;

class AuthController extends \Core\Controller
{

    public function __construct()
    {
        
    }

    public function login()
    {
        $res = $this->model('Auth')
                    ->login();

        sendJsonResponse($res);
    }
}