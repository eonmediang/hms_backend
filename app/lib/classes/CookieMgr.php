<?php 

/**
 * Cookie Manager class handles setting, getting and deleting cookies
 *
 * @author Abeeb Ola for showy.ng
 *
 **/


class CookieMgr {

	private $cookie;
	private $cookieName;
	private $cookieContent;
	private $enc_key;
	private $encrypter;
	private $error;
	private $path;
	private $domain;
	private $secure;
	private $httponly;
	private $duration;

	function __construct()
	{
		global $CFG;
		$this->cfg = $CFG;
		$this->enc_key = $_ENV['COOKIE_ENC_KEY'];
		$this->cookieName = $_ENV['COOKIE_NAME'];
		$this->duration = 24;
		$this->path='/';
		$this->domain = '';
		$this->cookieContent = 'Nothing here';
		$this->secure = false;
		$this->httponly = true;
		$this->error = "No error";


		
	}

	public function storeCookie()
	{
		$cookieName	= $this->cookieName;
		$content 	= $this->cookieContent;
		$duration 	= time() + ($this->duration * 3600);
		$path	 	= $this->path;
		$domain		= $this->domain;
		$secure		= $this->secure;
		$httponly	= $this->httponly;

		try {

		    $encryptedContent = \Defuse\Crypto\Crypto::Encrypt($content, loadEncryptionKey( $this->enc_key ) );

		  } catch (CryptoTestFailedException $ex) {

		      return $this->error = "Could not perform encryption";

		  } catch (CannotPerformOperationException $ex) {

		      return $this->error = "Could not perform decryption";

		  }

		setcookie($cookieName, $encryptedContent, $duration, $path, $domain, $secure, $httponly);
	}

	public function getError()
	{
		return $this->error;
	}

	public function isSecure($bool)
	{
		$this->secure = $bool;
	}

	public function setCookieDomain($domain)
	{
		$this->domain = $domain;
	}

	public function setCookiePath($cookiePath)
	{
		$this->path = $cookiePath;
	}

	public function setCookieName($name)
	{
		$this->cookieName = $name;
	}

	public function isHttpOnly($bool)
	{
		$this->httponly = $bool;
	}

	public function setDuration($time)
	{
		$this->duration = $time;
	}

	public function setCookieContent($content)
	{
		$this->cookieContent = $content;
	}

	public function getCookie($cookieName)
	{
		if (isset($_COOKIE[$cookieName])){

			$value = $_COOKIE[$cookieName];

			try {
			      $decrypted = \Defuse\Crypto\Crypto::Decrypt($value, loadEncryptionKey( $this->enc_key ) );
			      return $decrypted;

			  } catch (\Defuse\Crypto\Exception\InvalidCiphertextException $ex) { // VERY IMPORTANT
			      // Either:
			      //   1. The ciphertext was modified by the attacker,
			      //   2. The key is wrong, or
			      //   3. $ciphertext is not a valid ciphertext or was corrupted.
			      // Assume the worst.
			  		$this->error = 'DANGER! DANGER! The ciphertext has been tampered with!';
			      	return false;

			  } catch ( \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex){

			  		$this->error = 'DANGER! DANGER! The ciphertext has been tampered with!';
			  		return false;
			  		
			  } catch (\Defuse\Crypto\Exception\CryptoTestFailedException $ex) {

			      $this->error = 'Cannot safely perform encryption';

			  } catch (\Defuse\Crypto\Exception\CannotPerformOperationException $ex) {

			      $this->error = 'Cannot safely perform decryption';

				}

			} else {

				$this->error = 'Cookie does not exist';
				return false;
			}
	}

	public function setLoginCookie($id)
	{
		$this->setCookieName($this->cookieName);
		$this->isHttpOnly(true);
		$this->setDuration(24*365*2); 	// Cookie expires in 2 years
		$this->setCookieContent($id);
		$this->storeCookie();
	}

	public function deleteCookie($cookieName)
	{
		$this->setCookieName($cookieName);
		$this->setDuration(1-(24*365*2)); 	// Cookie expires due to negative time
		$this->setCookieContent('');
		$this->storeCookie();
	}

}