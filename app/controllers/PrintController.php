<?php

use \Core\Requests;
use \Core\DataStore;

class PrintController extends \Core\Controller{

    public function __construct()
    {
        // $headers = Requests::headers();
        // $customHeader = $headers->x_customheader;
        // return sendJsonResponse(Requests::queryStrings($customHeader, true));
        // $r = parse_str($headers->x_customheader);
        // var_dump( Requests::headers());
        // $this->view('print', $form);
    }
    
    public function table($table='')
    {


    }

    public function id( $uid )
    {
        $details = $this->model('Members')
                    ->single($uid);

        $this->model('Printer')
                ->printId($details);

    }
}