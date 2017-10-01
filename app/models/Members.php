<?php 

use Core\Requests as Request;
use Core\Registry;

class Members
{
    private $db;

    public function __construct()
    {
        $this->db = Registry::getInstance()->get('Core\Database');
    }

    public function all()
    {
        $sql = "SELECT `members`.*, `associations`.`name` AS assoc_name, `associations`.`id` AS assoc_id
                 FROM `members`
                  INNER JOIN `associations`
                   ON `members`.`assoc_id` = `associations`.`id`";

        $all = $this->db->fetchAll($sql);
        foreach ($all as $single) {
            $single->dob = date_format(new DateTime($single->dob), 'j/m/Y');
            $single->uid = genUid($single->id);
            $single->pid = parseUid($single->uid)->lid;
        }
        return $all;
    }

    private function createImage($imgString, $ext ="jpg"){
        global $CFG;
        $md = md5(time());
		$dir1 = substr($md, 0, 2);
		$dir2 = substr($md, 2, 2);
		$dir3 = substr($md, 4, 2);
		$dir4 = substr($md, 6, 2);
		$photosDir = $CFG->profile_pictures_dir;
        $photosUrl = $CFG->profile_pictures_uri;
        $directories = "$dir1/$dir2/$dir3/$dir4";

		// Set up image paths
		$imgDir = "$photosDir/_s/$directories";
		$imgUrl = "$photosUrl/_s/$directories";
		$thumbImgDir = "$photosDir/_t/$directories";
        $thumbImgUrl = "$photosUrl/_t/$directories";

		// Check if destination directories exist. If not, create them.
		if (!file_exists($imgDir) || !is_dir($imgDir)){
			mkdir($imgDir, 0755, true);
		}

		// Do same for thumb image.
		if (!file_exists($thumbImgDir) || !is_dir($thumbImgDir)){
			mkdir($thumbImgDir, 0755, true);
        }

        $filename = createImageFromString($imgString, $ext, $imgDir);
        if ($filename)
            return "{$directories}/{$filename}";

    }

    public function new()
    {
        $form = Request::form();
        $fname = $form->fname;
        $mname = $form->mname;
        $lname = $form->lname;
        $phone = $form->phone;
        $address = $form->address;
        $sex = $form->sex;
        $dob = $form->dob;
        $m_status = $form->status;
        $assoc = $form->association;
        $img = $form->img_url;

        // return $dob;

        // Format dob
        $dob = str_replace('/', '-', $dob);
        try {
            $dob_formatted = new DateTime($dob);
            $dob_formatted = $dob_formatted->format(DATE_W3C);
        } catch (Exception $e){
            return ['data' => 'Invalid date.', 'success' => 0];
        }

        // Process image
        $img_text = str_replace('data:image/png;base64,', '', $img);
        $img_b = base64_decode($img_text);

        $img_url = $this->createImage($img_b, 'png');
        error_log($img_url);
        if (! $img_url)
            return ['data' => 'The image couldn\'t be uploaded. Please try again.', 'success' => 0];

        $sql = "INSERT INTO 
            `members` 
                (fname, mname, lname, phone, address, sex, dob, m_status, img, assoc_id)
                    VALUES (:fname, :mname, :lname, :phone, :address, :sex, :dob, :m_status, :img, :assoc_id)";

        $insert = $this->db->insertOne( $sql, 
                    [   'fname' => $fname,
                        'mname' => $mname,
                        'lname' => $lname,
                        'phone' => $phone,
                        'address' => $address,
                        'sex' => $sex,
                        'dob' => $dob_formatted,
                        'm_status' => $m_status,
                        'img' => $img_url,
                        'assoc_id' => $assoc,
                    ]);

        if ($insert)
            return ['data' => 'successful', 'success' => 1];
        return ['data' => 'An error occurred. Please try again.', 'success' => 0];
    }

    public function single($uid)
    {
        $uid = (int) $uid;
        $id = parseUid( $uid )->lid;

        $sql = "SELECT `members`.*, `associations`.`name` AS assoc_name, `associations`.`id` AS assoc_id
                 FROM `members`
                  INNER JOIN `associations`
                   ON `members`.`assoc_id` = `associations`.`id`
                    WHERE `members`.`id` = :id";

        $person = $this->db->fetchOne($sql, [
            'id' => $id
        ]);

        if (empty($person))
        return [];

        $person->uid = $uid;
        $person->dob = date_format(new DateTime($person->dob), 'j/m/Y');
        return $person;
    }
}