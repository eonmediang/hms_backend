<?php

use \Core\Requests as Requests;
use \Models\Members;

class MembersController extends \Core\Controller
{
    public function __construct()
    {
        $q = Requests::queryStrings();
        if ( $q->uid )
            return sendJsonResponse(
                (new Members())->single( $q->uid )
        );
    }

    public function add()
    {
        $res = (new Members())->new();

        return sendJsonResponse($res);
    }

    public function all()
    {
        $res = (new Members())->all();
        return sendJsonResponse($res);
    }

    public function search()
    {
        $id = Requests::queryStrings()->id;
        if ( ! $id )
            return sendJsonResponse( [
                'msg' => 'Valid ID code missing.',
                'success' => 0
            ] );
        $result = (new Members())->search( $id );
        return sendJsonResponse( $result );
    }
}