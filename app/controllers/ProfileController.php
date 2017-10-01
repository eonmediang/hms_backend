<?php

use \Core\Requests;

class ProfileController extends \Core\Controller
{
    public function __construct()
    {

        if (Requests::method() == 'post'){
            return sendJsonResponse(
                $this->model('Profile')
                    ->update()
            );
        }
        $q = Requests::queryStrings();
        if ( isset( $q['uid'] ))
            return sendJsonResponse(
                $this->model('Profile')
                    ->get($q['uid'])
            );
    }
}