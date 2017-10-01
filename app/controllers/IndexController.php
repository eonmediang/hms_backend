<?php 

class IndexController extends \Core\Controller {

	public function __construct()
	{
		$this->home();
	}

	public function home()
	{
		$this->view('index', '', [], true);
	}


}