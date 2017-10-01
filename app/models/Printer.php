<?php 

class Printer extends \Core\BaseModel
{

    public function printId( $details )
    {
        $template = self::config()->templates.'/print/id.html';
        echo renderTemplate($template, (array) $details);
        // return sendJsonResponse($template);
    }

}