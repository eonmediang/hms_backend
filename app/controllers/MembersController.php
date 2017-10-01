<?php

use \Core\Requests as Requests;

class MembersController extends \Core\Controller
{
    public function __construct()
    {
        $q = Requests::queryStrings();
        if ( isset( $q['uid'] ))
            return sendJsonResponse(
                $this->model('Members')
                    ->single($q['uid'])
        );
        // return sendJsonResponse($q);
        // var_dump($q);
    }

    public function add()
    {
        $res = $this->model('Members')
                    ->new();

        return sendJsonResponse($res);
    }

    public function all()
    {
        $res = $this->model('Members')
                    ->all();

        return sendJsonResponse($res);
    }
}