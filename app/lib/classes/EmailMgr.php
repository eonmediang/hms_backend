<?php 

global $CFG;
require_once $CFG->paths->class_dir.'/PHPMailer/PHPMailerAutoload.php';

class EmailMgr 
{
	public $errorMsg;
	public $bcc_array = [];
	public $bcc_address;
	public $bcc_name;

	public function addBCC( $address, $name )
	{
		array_push( $this->bcc_array, [ 'address' => $address, 'name' => $name ] );
	}

	public function sendMail( $fullname, array $company_data, array $recipient, array $email )
	{
		$emailBody =  $email['html'] ?? '';
		$plainBody =  $email['plain'] ?? '';
		$subject =  $email['subject'] ?? '';
		
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
		$mail->Host = "mail.ufranklimited.com";
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
		$mail->Username = "notifications@ufranklimited.com";
		//Password to use for SMTP authentication
		$mail->Password = "1-qm0K1!h^A?1";
		//Set who the message is to be sent from
		$mail->setFrom('notifications@ufranklimited.com', $fullname.' from '.$company_data['name']);
		//Set an alternative reply-to address
		$mail->addReplyTo($company_data['email'], $company_data['name']);
		//Set who the message is to be sent to
		$mail->addAddress( $recipient['email'], $recipient['name'] );
		// Add BCC
		if ( ! empty( $this->bcc_array ) ){

			foreach ($this->bcc_array as $bcc) {
				$mail->addBCC( $bcc['address'], $bcc['name'] );
			}

		}
		//Set the subject line
		$mail->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($emailBody);
		//Replace the plain text body with one created manually
		$mail->AltBody = $plainBody;
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		if (!$mail->send()) {
			$this->errorMsg = $mail->ErrorInfo;
		    return false; //echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		    return true; //echo "Message sent!";
		}
	}

}