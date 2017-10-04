<?php 

/* 	This file contains core functions
**	needed for basic functionality
*/

$CFG = Config::newInstance();

function addScript( $type, $src, $position = 'header')
{
	global $CFG;
	$link = '';
	switch ( strtolower( $type ) ) {
		case 'css':
			$link = "<link href='{$src}?{$CFG->version}' rel='stylesheet'>";
			break;

		case 'js':
			$link = "<script src='{$src}?{$CFG->version}'></script>";
		
		default:
			# code...
			break;
	}

	switch ($position) {
		case 'footer':
			array_push( $CFG->footerScripts, $link);
			break;
		
		default:
			array_push( $CFG->headerScripts, $link);
			break;
	}

	
}

function appendScripts( $position )
{
	global $CFG;
	$script_group = array();

	switch ($position) {

		case 'header':			
			$script_group = $CFG->headerScripts;
			break;

		case 'footer':			
			$script_group = $CFG->footerScripts;
			break;
		
		default:
			$script_group = $CFG->headerScripts;
			break;
	}
	$links = $script_group;
	$scripts = '';
	foreach ($links as $l) {
		$scripts .= "{$l}\n\t";
	}

	return $scripts;
}

function appendModals( $filesArray, $tempTagArray = [] )
{
	global $CFG;

	if ( ! empty( $tempTagArray ) ) $CFG->modalTags = array_merge( $tempTagArray );

	foreach ($filesArray as $f) {

		$file = $CFG->templates."/modals/{$f}.php";
		if ( ! file_exists( $file ) ) throw new Exception("The modal ".$f." does not exist.", 1);
		array_push( $CFG->footerModals, $file );
		
	}
	
}

function autoloader($className)
{
	global $CFG;
	$pathToFile = $CFG->class_dir.'/'.ucfirst($className).'.php';
	if (file_exists($pathToFile)){
		require $pathToFile;
	}
}

function autoloadCoreNamespace($classname)
{

	$file = str_replace( '\\', '//',  ltrim($classname, '\\') );
	$pathToFile = __DIR__.'/'.$file.'.php';
	if ( file_exists( $pathToFile ))
		require_once $pathToFile;
}

spl_autoload_register('autoloader');
spl_autoload_register('autoloadCoreNamespace');

/**
 * Simple Function that returns its
 * arguments in an array
 *
 * @return array
 * 
 **/

function __()
{
	return func_get_args();
}

function getModals()
{
	global $CFG;
	$fileArray = $CFG->footerModals;

	if ( count( $fileArray ) < 1 ) return;

	$content = '';

	foreach ($fileArray as $file) {

		$content .= renderTemplate( $CFG->modalTags, $file )."\n";

	}

	return $content;
}

function getParamsFromUrl()
{
	//	Return empty array if GET data does not exist.
	if ( !isset( $_GET['req_url'] ) ) return [];
	$url = resolveUrl($_GET['req_url']);
	unset( $url[0] );
	return array_values( $url );
}

/**
 * Get default header styles and scripts
 * by appending them to the template tags
 * array
 *
 * @return void
 * 
 **/
function get_scripts( $pos = 'header', $file = '')
{
	global $CFG;
	if ( $file == '')
		$file = $CFG->templates.'/header-tags.php';
	$tempTags = array(

		'css'  =>	$CFG->css,
		'js'	=>	$CFG->js,
		'img'	=>	$CFG->img,
		'extrascripts' => appendScripts( $pos ),
		'version'	=>	'?ver='.$CFG->version,
		'modals'	=>	getModals()

		);

	$CFG->templateTags = array_merge($CFG->templateTags, $tempTags);
	$currTempTags = $CFG->templateTags;

	if ( ! isset( $currTempTags['title'] ) || empty( $currTempTags['title'] ) ) {
		$currTempTags['title'] = $CFG->pageTitle;
		$CFG->templateTags = array_merge( $CFG->templateTags, $currTempTags );
	}

	$content = renderTemplate($CFG->templateTags, $file);
	echo <<<EOT
	$content
EOT;

}

function get_q_string()
{
	//	Get request URI
	$URI = $_SERVER['REQUEST_URI'];

	//	Return early if no '?' symbol in URI
	if (!(stripos($URI, '?'))) return false;
	$queryString = substr($URI, (stripos($URI, '?') + 1));

	//	Return early if no query string in URI
	if (!$queryString) return false;
	
	$queryString = explode('&', $queryString);
	$result = new stdClass;
	if (count($queryString) > 0) {
	    	foreach ($queryString as $q) {
				$e = explode('=', $q);
				$result->$e[0] = (isset($e[1])) ? $e[1] : '';
			}
	
	}

	return $result;
}

function isLoggedIn()
{
	global $CFG;

	// Function to run after successful cookie check
	$cookie_func = $CFG->cookie_func ?? '';
	$loginCookie = Core\Registry::get('CookieMgr');
	$id = $_SESSION['id'] ?? null;

	if ( ! is_null( $id ) ){

		$user_data = getUserData( $id );
		// Core\DataStore::set( ['user_data' => $user_data] );

		return $id;

	} 

	if ( $loginCookie->getCookie($CFG->Login_cookie_name) ){

			if ( is_callable( $cookie_func) ){
				$cookie_func();
				return true;
			} else throw new Exception("Invalid function passed to login function", 1);
			
		}

	return false;

}

function loadEncryptionKey( $keyAscii )
{
	return \Defuse\Crypto\Key::loadFromAsciiSafeString( $keyAscii );
}

function logOut()
{
	global $CFG;
	$cookie = new CookieMgr();
	$cookie->deleteCookie($CFG->Login_cookie_name);
	SessionMgr::_endSession();
	header('Location: '.$CFG->home_url);
}

function processUri()
{

	//	Get request URI
	$URI = $_SERVER['REQUEST_URI'];
	$URI = parse_url($URI);
	$path = trim( $URI['path'], '/');
	$q = (isset($URI['query'])) ? $URI['query'] : [];

	$path = explode('/', $path);
	$result = new stdClass;
	$result = array();

	for ($i=0; $i < count($path); $i++) { 
		
		if ( ! empty( $path[$i] ) )	$result[] = $path[$i];
	}

	//	Check if query string exists
	$qr = array();
	if ( ! empty( $q ) ){

		$queryString = explode('&', $q);

    	foreach ($queryString as $q) {
			$e = explode('=', $q);
			$qr[ $e[0] ] = (isset($e[1])) ? $e[1] : '';
		}
		
		$result->query = $qr;

	} 

	return $result;

}

function requestUri()
{
	return filter_var( rtrim( $_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL);
}

if (!function_exists('replace_str')){

	function replace_str($search, $replace, $subject)
	{
		//	Function by Bas on stackoverflow
		$pos = strpos($subject, $search);
		if ($pos === false) return $subject;
		return substr_replace($subject, $replace, $pos, strlen($search));
	}

}

function resolveUrl($url)
{
	// Trim slashes
	$url = trim($url, '/');

	// Explode url into array
	$url = explode('/', filter_var($url, FILTER_SANITIZE_URL));

	return $url;
}

/**
 * Render Template
 *
 * @return void
 * @author Abeeb Ola
 *
 **/

function renderTemplate($file, array $tempArray = [])
{
	$tags = $tempArray;
	$file = file_get_contents($file);
	$content = '';

	foreach ($tags as $key => $value) {

		if ( is_callable( $value ) ){
			$content = $value();
		} else $content = $value;
		
		$file = str_replace("{{{{$key}}}}", $content, $file);

	}

	return $file;

}

/**
 * Send object as a JSON-Encoded response.
 * The response is sent with a content-type
 * of `application/json`
 *
 * @param type `array`|string|object` object Description
 **/
function sendJsonResponse($response, $code = 200)
{
	header('Content-Type: application/json');
	http_response_code( $code );
	echo json_encode($response);
	die();
}

if (!function_exists('temp_admin_pass')){

	function temp_admin_pass()
	{
		return randStrGen(10);
	}
}