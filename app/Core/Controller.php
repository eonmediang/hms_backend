<?php 

namespace Core;

use \Core\Datastore as store;

class Controller {

	private $cfg;
	private $paths;

	public function model($model)
	{
		$file = __DIR__.'/../models/'.$model.'.php';
		if ( file_exists($file) ){
			require_once $file;
			$model = explode('/', ltrim( $model, '/' ));
			$model = $model[ count( $model ) - 1];
			return new $model();
		}

		return new stdClass;
		
	}

	public function view($view, $data = '', $templates = [])
	{
		global $CFG;
		$spa = $CFG->spa ?? false;
		$header = '';
		$footer = '';
		if ( ! $spa ){
			$header = $templates['header'] ?? $CFG->paths->templates.'/header.php';
			$footer = $templates['footer'] ?? $CFG->paths->templates.'/footer.php';
		}
		$prettyDate = Registry::getInstance()->get('PrettyDate');
    	$this->cfg = $CFG;
    	$file = __DIR__.'/../views/'.$view.'.php';
    	if ( !file_exists($file) ){
    		throw new Exception("The file 'views/{$view}.php' does not exist", 1);   		
    	}
		if ( ! $spa ){
			require_once $header;
		}
		require_once $file;
		if ( ! $spa ){
			get_scripts( 'footer', $footer );
		}
		die();
	}

	public function getModel()
	{
		$paths = store::get('url_paths');		
		if ( ! isset( $paths[1] ) ) return false;
		return $paths[1];
	}


	public function __get( $name )
	{
		switch ( $name ) {
			case 'paths':
				return store::get('url_paths');
				break;

			case 'controller_args':
				return array_slice(
					store::get('url_paths'), 1
				);

			case 'controller_args_url':
				return implode(
					'/', array_slice(
						store::get('url_paths'), 1
					)
				);

			case 'method_args':
				return array_slice(
					store::get('url_paths'), 2
				);

			case 'method_args_url':
				return implode(
					'/', array_slice(
						store::get('url_paths'), 2
					)
				);

			case 'cfg':			
				if ( ! isset( $this->cfg ) ) {

					global $CFG;
					$this->cfg = $CFG;
				}
								
				return $this->cfg;

				break;
			
			default:
				# code...
				break;
		}
	}



}

