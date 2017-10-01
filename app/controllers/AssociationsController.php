<?php

use \Core\Requests as Requests;

class AssociationsController extends \Core\Controller
{

    public function __construct()
    {
    }

    public function add()
    {
        $res = $this->model('Associations')
                    ->new();

        return sendJsonResponse($res);
    }

    public function all()
    {
        $res = $this->model('Associations')
                    ->all();

        return sendJsonResponse($res);
    }
}