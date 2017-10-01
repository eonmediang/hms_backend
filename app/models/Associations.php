<?php 

use Core\Requests as Request;
use Core\Registry;

class Associations 
{
    private $db;

    public function __construct()
    {
        $this->db = Registry::getInstance()->get('Core\Database');
    }

    public function all()
    {
        $sql = "SELECT DISTINCT `associations`.`id`,`associations`.`name`,
                 (SELECT COUNT(*) FROM `members`
                  WHERE `members`.`assoc_id` = `associations`.`id`)
                   AS member_count
                FROM `associations`";

        $all = $this->db->fetchAll($sql);

        return $all;
        foreach ($all as $single) {
            $single->dob = date_format(new DateTime($single->dob), 'j/m/Y');
        }
        return $all;
        $all->dob = date('j/m/Y', $all->dob);
        return $all;
    }

    public function new()
    {
        $form = Request::form();
        $name = $form->name;

        $sql = "INSERT INTO 
                 `associations` (name)
                  VALUES (:name)";

        $insert = $this->db->insertOne( $sql, 
                    [   'name' => $name  ]);

        if ($insert)
            return ['msg' => 'successful', 'success' => 1, 'id' => $insert];
        return ['msg' => 'An error occurred. Please try again.', 'success' => 0];
    }
}