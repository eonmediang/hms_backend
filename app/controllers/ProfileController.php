<?php

use \Core\Requests;
use \Models\Profile;

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
        $uid = Requests::queryStrings()->uid;
        if ( $uid )
            return sendJsonResponse(
                $this->model('Profile')
                    ->get($uid)
            );
    }
}