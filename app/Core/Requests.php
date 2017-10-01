<?php namespace Core;

class Requests {


	public static function form($value = null)
	{
		$post = new \Core\RequestForm( $_POST );
		if ( is_null($value ) )
			return $post;

		return $post->$value;

	}

	public static function headers( $value = null )
	{
		$headers = new \Core\RequestHeaders( $_SERVER );
		if ( is_null($value ) )
			return $headers;

		return $headers->$value;
	}

	public static function method()
	{
		return strtolower( $_SERVER['REQUEST_METHOD'] );
	}

	public static function fetch( $url )
	{
		return "Fetched!";
	}

	public static function processUri( $uri = null )
	{
	
		//	Get request URI
		$URI = $uri ?? Requests::requestUri();
		$URI = parse_url($URI);
		$path = trim( $URI['path'], '/');
		$q = (isset($URI['query'])) ? $URI['query'] : [];
	
		$path = explode('/', $path);
		$result = new \stdClass;
		$result->paths = array();
	
		for ($i=0; $i < count($path); $i++) { 
			
			if ( ! empty( $path[$i] ) )	$result->paths[] = $path[$i];
		}
	
		//	Check if query string exists
		$qr = array();
		if ( ! empty( $q ) ){
	
			$queryString = explode('&', $q);
	
			foreach ($queryString as $q) {
				$e = explode('=', $q);
				$qr[ $e[0] ] = (isset($e[1])) ? $e[1] : '';
			}	
		} 
		$result->query = new \Core\RequestQs( $qr );
	
		return $result;
	
	}

	public static function queryStrings( $string = null, $decode = false )
	{
		$qs = ( ! \is_null($string) ) ? "http://dummy-site.com/?$string" : null;
		$result = Requests::processUri( $qs )->query;
		if ( $decode ){
			foreach ($result as $key => $value) {
				$result[ $key ] = \urldecode($value);
			}
		}
		return $result;
	}

	public static function requestUri()
	{
		return filter_var( rtrim( $_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL);
	}
}

 class RequestForm
{
	private $post;

	public function __call( $func, $args = [])
	{
		if ($func == 'values')
			return (object) $_POST;
		return null;
	}

	public function __construct( $post )
	{
		$this->post = $post;
		// $this->post = ['fname' => 'Tunde'];
	}

	public function __get( $val )
	{
		return $this->post[ $val ] ?? null;
	}
}

class RequestHeaders
{
	private $headers;

	public function __call( $func, $args = [])
	{
		if ($func == 'values')
			return (object) $_headers;
		return null;
	}

	public function __construct( $headers )
	{
		$_headers = [];
		foreach ($headers as $key => $value) {
			if (\substr($key, 0, 5) == 'HTTP_'){
				$_key = \strtolower(\substr($key, 5, \strlen($key)));
				$_headers[ $_key ] = $value;
			}
		}
		$this->headers = $_headers;
	}

	public function __get( $val )
	{
		return $this->headers[ $val ] ?? null;
	}
}

class RequestQs
{
	// Query string array
	private $qs;

	public function __construct( $results )
	{
		$this->qs = $results;
	}

	public function __get( $value )
	{
		return $this->qs[ $value ] ?? null;
	}
}