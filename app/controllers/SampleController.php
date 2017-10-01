<?php 

class SampleController extends \Core\Controller
{
    protected $s; // Holds the sampleModel object. See below.

    /*The 'controller_args' method in the base controller returns
    an array of arguments based on the remaining paths after the
    controller name in the url while the 'controller_args_url'
    returns a string with the paths.
    
    You can uncomment the lines below to see
    the effects of each action.*/

    public function __construct()
    {
        // echo $this->controller_args_url;
        // var_dump( $this->controller_args );

        // Since all the functions below use the same model in this sample,
        // it makes more sense to instantiate the model in one place so that
        // it can be shared by all the methods. 
        $this->s = $this->model('sampleModel');
    }
    
    public function get($id_or_username='')
    {
        // echo $this->method_args_url;
        // var_dump( $this->method_args );

        $result = $this->s->getRecord($id_or_username);
        if ( ! $result ){

            return sendJsonResponse(
                ['statusCode'   =>  0,
                 'response'     => 'User does not exist.']
            );

        }

        sendJsonResponse(
            [
                'statusCode'    => 1,
                'response'      => $result
            ]
        );
    }

    public function add()
    {
        $r = $this->s->addSampleUser();
        if ( $r )
            return sendJsonResponse(
                [
                    'statusCode'    =>  1,
                    'response'      => 'User added successfully.'
                ]
            );

        return sendJsonResponse(
                [
                    'statusCode'    =>  0,
                    'response'      => 'An error occurred.'
                ]
            );
    }

    public function remove($val)
    {
        $r = $this->s->deleteUser( $val );
        if ( $r )
            return sendJsonResponse(
                [
                    'statusCode'    =>  1,
                    'response'      => 'User deleted successfully.'
                ]
            );

        return sendJsonResponse(
                [
                    'statusCode'    =>  0,
                    'response'      => 'An error occurred.'
                ]
            );
    }
}