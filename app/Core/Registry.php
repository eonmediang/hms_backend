<?php

namespace Core;

class Registry
{
	static $bucket = [];
	private static $instance = NULL;

	public static function getInstance(){
		if (is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct(){
		return $this;
	}

	public function get( $name )
	{
		if ( array_key_exists($name, self::$bucket))
			return self::$bucket[ $name ];
		//	Else...
		throw new \Exception("Class or object does not exist", 1);		

	}

	public function set( $object )
	{
		if ( is_object( $object ) ){

			$class_name = get_class( $object );
			self::$bucket[ $class_name ] = $object;
			return true;
		}

		throw new \Exception("$$object is not a valid object", 1);
		
	}
}