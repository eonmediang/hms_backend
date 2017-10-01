<?php 

namespace Core;

class BaseModel {

	public static function config()
	{
		return \Config::newInstance();
	}

}