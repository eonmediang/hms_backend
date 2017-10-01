<?php

use \Core\Requests as Requests;
use \Core\DataStore as Store;

class UuidController extends \Core\Controller
{

    public function __construct()
    {
        // var_dump($this->method_args[0]);
        // var_dump($this->parseUid($this->method_args[0]));
        // // $this->getSid();   
        // $this->genUid();
    }

    public function login()
    {
        sleep(1);
        sendJsonResponse( Requests::form());
    }

}