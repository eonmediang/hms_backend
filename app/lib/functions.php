<?php

function createImageFromString($imgString, $ext="jpg", $savePath)
{
	$filename = fileNameGen();
	$filename .= ".{$ext}";
	$destPath = $savePath.'/'.$filename;

	$img = imagecreatefromstring($imgString);

	switch ($ext) {
		case 'jpg':
			return imagejpeg($img, $destPath) ? $filename : false;
			break;
		case 'png':
			return imagepng($img, $destPath) ? $filename : false;
			break;
		case 'gif':
			return imagegif($img, $destPath) ? $filename : false;
			break;
		case 'bmp':
			return imagewbmp($img, $destPath) ? $filename : false;
			break;
		
		default:
			return false;
			break;
	}

}

function fileNameGen()
{
	$string = idGen();
	$array = str_split($string);
	$len = strlen($string);
	$index1 = mt_rand(1, $len-3);
	$index2 = mt_rand(3, $len-3);
	$array[$index1] = '.';
	$array[$index2] = '_';

	$name = implode('', $array);

	return $name;

}

function formatPhoneNumbers( $numbers )
{
	$count = count( $numbers );
	for ($i=0; $i < $count; $i++) { 

		$num = $numbers[ $i ];
		$len = strlen( $num );
		$first = substr($num, 0, 1);
		$remainder = substr($num, 1, ( $len - 1 ) );

		if ( $first == '0' ) $numbers[ $i ] = '234'.$remainder;
	}
	
	return implode( ',', $numbers );
}

function genNewPassword($pass)
{
	$options = [
	    'cost' => 12,
	];

	return password_hash($pass, PASSWORD_BCRYPT, $options);

}

function genSDImage($path, $savePath, $thumbWidth = 500, $thumbHeight = 400)
{
	$imgInfo = getimagesize($path);
	$width = $imgInfo[0];
	$height = $imgInfo[1];
	$mime = $imgInfo['mime'];

	switch ($mime) {
		case 'image/jpeg':
			$src = imagecreatefromjpeg($path);
			$ext = 'jpg';
			break;
		case 'image/png':
			$src = imagecreatefrompng($path);
			$ext = 'png';
			break;
		case 'image/gif':
			$src = imagecreatefromgif($path);
			$ext = 'gif';
			break;
		case 'image/bmp':
			$src = imagecreatefromwbmp($path);
			$ext = 'bmp';
			break;
		default:
			return false;
			break;
	}

	$ratio = $width / $thumbWidth;

	$newWidth = $width;
	$newHeight = $height;

	if ($width > $thumbWidth){

		$newWidth = floor($width / $ratio);
		$newHeight = floor($height/$ratio);

	}

	if ($newHeight > $thumbHeight){

		$ratio = $height/$thumbHeight;
		$newWidth = floor($width /$ratio);
		$newHeight = floor($height/$ratio);

	}

	$sdImg = imagecreatetruecolor($newWidth, $newHeight);
	//imagecopyresampled(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h);
	imagecopyresampled($sdImg, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

	switch ($ext) {
		case 'jpg':
			return imagejpeg($sdImg, $savePath);
			break;
		case 'png':
			return imagepng($sdImg, $savePath);
			break;
		case 'gif':
			return imagegif($sdImg, $savePath);
			break;
		case 'bmp':
			return imagewbmp($sdImg, $savePath);
			break;
		
		default:
			return false;
			break;
	}

}

function genStaffCode( $id, $threshold = 10000 ){

	function multiples($value, $threshold, $floor = false)
	{
		$greater = $value / $threshold;
		if ($floor)
			return floor( $greater );
		return ceil( $greater );
	}

	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$alphas = str_split($chars);
	// $limit = 26 * $threshold;
	$multiples = multiples( $id, $threshold );

	$prefix = $alphas[ $multiples - 1 ];

	return $prefix.$id;
}

function genUid($id)
{
	$opts = [
		'sid_len' => 4,
		'lid_len' => 20,
		// 'lid_len' => 
	];
	return Uuid::genUid($id, $opts);
}

function getMatches($pattern, $string)
{
 $string = preg_match_all($pattern, $string, $matches);
  return $matches[0];
}

function getMediaBaseUrl( $type )
{
	global $CFG;

	switch ( $type ) {
		case 'audio':
			return $CFG->audio;
			break;

		case 'video':
			return $CFG->videos;
			break;

		case 'photo':
			return $CFG->photos;
			break;
		
		default:
			return $CFG->photos;
			break;
	}
}

function idGen()
{
	$time = time();
    $time = str_split($time);
    $len = count($time);

    for ($i=0; $i < 4; $i++) { 

    	$randomIndex = mt_rand(0, $len);
    	
    	unset($time[$randomIndex]);
    }

    $newTime = implode('', array_values($time));
    $r1 = mt_rand(pow(10, 8), pow(10, 9)-1);
    $r2 = mt_rand(pow(10, 7), pow(10, 8)-1);

    $prefix = '1';

    for ($i=0; $i < 3; $i++){
    	$prefix .= mt_rand(1,9);
    }

    $uid = $prefix.$r1.$newTime.$r2;

    return $uid;

}

function is_admin( $access_level )
{
	$admin_arr = ['admin', 'manager', 'owner'];
	if ( in_array( strtolower( $access_level ), $admin_arr ) ) return true;
	return false;
}

function leadingZeroes( $num, $length = 5 )
{
	$len = strlen( $num );
	$length;
	$leading_zeroes = '';

	if ( (int) $num == 0 || $len >= $length ) return $num;

	$diff = $length - $len;
	for ($i=0; $i < $diff; $i++) { 
		$leading_zeroes .= '0';
	}

	return $leading_zeroes.($num);

}

function parseUid($uid)
{
	$opts = [
		'sid_len' => 4,
		'lid_len' => 20,
		// 'lid_len' => 
	];
	return Uuid::parseUid($uid, $opts);
}

function redirect( $location = '/' )
{
	header( 'Location: '.$location );
	exit();
}

function sendActivationMessage($fname, $lname, $email, $code, $udc)
{
	require_once $SitePaths->class_dir.'/PHPMailer/PHPMailerAutoload.php';

	$emailBody = file_get_contents($SitePaths->templates.'/signup_email.php');
	$emailBody = str_replace('***act_code***', $code, $emailBody);
	$emailBody = str_replace('***udc***', $udc, $emailBody);

	$plainBody = file_get_contents($SitePaths->templates.'/signup_plain.php');
	$plainBody = str_replace('***act_code***', $code, $plainBody);
	$plainBody = str_replace('***udc***', $udc, $plainBody);
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
	$mail->Host = "mail.showy.ng";
	//Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Port = 26;
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	//Username to use for SMTP authentication

	$mail->SMTPOptions = array(
	    'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
	    )
	);
	$mail->Username = "welcome@showy.ng";
	//Password to use for SMTP authentication
	$mail->Password = "O0F<e(|\"-q\"f";
	//Set who the message is to be sent from
	$mail->setFrom('welcome@showy.ng', 'Showy.NG');
	//Set an alternative reply-to address
	$mail->addReplyTo('welcome@showy.ng', 'Showy.NG');
	//Set who the message is to be sent to
	$mail->addAddress($email, $fname.' '.$lname);
	//Set the subject line
	$mail->Subject = 'Welcome to showy.ng';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($emailBody, dirname(__FILE__));
	//Replace the plain text body with one created manually
	$mail->AltBody = $plainBody;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
	    return false; //echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    return true; //echo "Message sent!";
	}
	
}

if ( ! function_exists('randStrGen') ){

	function randStrGen($length)
	{

		$result = "";
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/.0123456789";
		$charArray = str_split($chars);

		for($i=0; $i < $length; $i++){

			$randItem = array_rand($charArray);
			$result .= $charArray[$randItem];
		}

		return $result;
	}

}

function slug($string)
{
	if ( $string == '' ) return '';

	$chars = "@/()|{}[]:*&^\$%$#!~`'\"\,.?_+=";

	//$new_slug = str_replace(" ", "-", $string);
	$new_slug = str_replace(str_split($chars), "", trim($string));
	$new_slug = preg_replace('/\s+/', '_', $new_slug);
    $new_slug = strtolower($new_slug);

    return $new_slug;
}

function verifyPassword($plainText, $hashedPassword)
{
	return password_verify($plainText, $hashedPassword);
}