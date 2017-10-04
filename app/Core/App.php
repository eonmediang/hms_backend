<?php

namespace Core;
use \Core\Requests;

class App extends \Config
{

	public $version = '1.0.2';
	protected $params = array(); 
	protected $controller = 'index';
	protected $cfg;

	public function __construct()
	{
		//	Process URI
		$paths = Requests::processUri()->paths;
		$this->cfg = self::newInstance();

		if ( ! empty( $paths )){

			$filename = array_shift( $paths );
			$this->params = array_values($paths);
			
			$this->resolveController( $filename );

		} else $this->resolveController( $this->controller );

	}

	public function defaultAction( $arg='')
	{
		// die();
		$ctrl = new Controller;
		$ctrl->view('index', '', []);
	}

	public function resolveController($filename)
	{
		$this->controller = $filename;
		$controller_file = __DIR__.'/../controllers/'.ucfirst( $this->controller ).'Controller.php';

		// Check if there is a matching controller for the path after the main domain
		// e.g http://domain/{controller}
		if ( file_exists( $controller_file )){
			require_once $controller_file;
			$class = $this->controller.'Controller';
			$this->controller = new $class();
			if ( isset( $this->params[0] ) ){
				$method = array_shift( $this->params );

				//	Check if method in Param exists in the current controller class
				if (method_exists($this->controller, $method)){

					//	Call method and pass param as an array argument
					// call_user_func_array([$this->controller, $method], [$this->params]);
					call_user_func_array([$this->controller, $method], $this->params);
					die();

				// If not, check if this is an SPA
				} else {
					if ($this->spa)
							$this->loadSpaFile();

					$this->defaultAction();
				}
			// If there is no path after the controller, then the controller should have
			// handled the requet before the code gets to this point.
			// If not, check if this is an SPA
			} else {
				if ($this->spa)
					$this->loadSpaFile();
				// Otherwise, throw an exception.
				throw new \Exception('This route does not return any data');
			}

		// If no controller found, display the index.html page
		// if this is a single page application
		} else {

			if ( $this->spa ){
				$this->loadSpaFile();
			// Otherwise, specify default action to take.
			} else	$this->defaultAction();
		}

	}

	protected function loadSpaFile()
	{
		require_once $this->cfg->public_dir.'/index.html';
		die();
	}

}