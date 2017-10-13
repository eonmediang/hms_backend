<?php

use \Core\Requests;
use \Models\Profile;

class ProfileController extends \Core\Controller
{
    public function __construct()
    {
        if (Requests::method() == 'post'){
            return sendJsonResponse(
                (new Profile())->update()
            );
        }
        $uid = Requests::queryStrings()->uid;
        if ( $uid )
            return sendJsonResponse(
                (new Profile())->get( $uid )
            );
    }
}