<?php 

/**
 * The Session Manager class handles the initialization and
 *	management of user sessions
 *
 * @author Abeeb Ola (for Showy.NG)
 *
 **/

class SessionMgr
{
	/**
	 *
	 * Initialize session manager 
	 *
	 * @return void
	 *
	 **/
	
	function __construct()
	{
		session_name('jht7');
		session_start();

		if ( session_id() ) {
			$this->newSessionId();
		}
		
	}

	/**
	 * Starts a new session
	 *
	 * @return 	bool True if session was created successfully
	 *			false if otherwise
	 * @author 	Abeeb Ola for Showy.NG
	 **/

	public static function _newSession(array $sessionArray)
	{
		if ( ! empty($sessionArray) ) {

			session_start();
			$_SESSION = array();

	    	foreach ($sessionArray as $key  => $value) {
				$_SESSION[$key] = $value;			
			}

			return true;

		} else {

			return false;
		}
	}

	/**
	 * Ends current session
	 *
	 * @return void
	 **/

	public static function _endSession()
	{
		$_SESSION = array();
		session_destroy();
	}

	public function newSessionId()
	{
		$currentSession = $_SESSION;
		session_destroy();
		session_id(bin2hex(openssl_random_pseudo_bytes(32)));
		SessionMgr::_newSession($currentSession);
	}

	public static function get( $index )
	{
		if ( isset( $_SESSION[$index] ) ){
			return $_SESSION[$index];
		}

		// throw new Exception("There is no '{$index}' data in the session", 1);
		return false;
		
	}

	public static function set( array $data )
	{
		if ( !empty( $data )){

			foreach ($data as $key => $value) {
				$_SESSION[$key] = $value;
			}
		}
	}
}

// $sessionHandler = new SessionMgr;