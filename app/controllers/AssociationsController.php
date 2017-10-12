<?php

use \Core\Requests as Requests;
use \Models\Associations;

class AssociationsController extends \Core\Controller
{

    public function __construct()
    {
    }

    public function add()
    {
        $res = (new Associations)->new();
        return sendJsonResponse($res);
    }

    public function all()
    {
        return sendJsonResponse(
            (new Associations)->all()
        );
    }
}