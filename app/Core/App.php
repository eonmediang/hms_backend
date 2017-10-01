<?php

namespace Core;
use \Core\Requests;

class App extends \Config
{

	public $version = '1.0.2';
	protected $params = array(); 
	protected $controller = 'index';

	public function __construct()
	{
		//	Process URI
		$paths = Requests::processUri()->paths;

		if ( ! empty( $paths )){

			$filename = array_shift( $paths );
			$this->params = array_values($paths);
			
			$this->resolveController( $filename );

		} else $this->resolveController( $this->controller );

	}

	public function defaultAction( $arg='')
	{
		$ctrl = new Controller;
		$ctrl->view('index', '', []);
	}

	public function resolveController($filename)
	{
		$this->controller = $filename;
		$controller_file = __DIR__.'/../controllers/'.ucfirst( $this->controller ).'Controller.php';
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
				}
			}

		} else {
			
			$this->defaultAction();
		}

	}

}