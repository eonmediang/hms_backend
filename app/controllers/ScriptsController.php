<?php 

class ScriptsController extends \Core\Controller
{
    public function fixuids()
    {
        $t1 = microtime(true);
        $data = $this->model('uids')
                    ->run();

        if ( $data ){
            $t2 = microtime(true);
            echo "Opration completed in ", ($t2 - $t1), " seconds.";
        } else {
            echo "An error occurred.";
        }

                    // var_dump($data);

        // sendJsonResponse( $data );
    }
}