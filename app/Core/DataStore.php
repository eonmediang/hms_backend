<?php

namespace Core;

class DataStore
{
	static $bucket = [];

	public static function get( $name )
	{
		if ( array_key_exists($name, self::$bucket))
			return self::$bucket[ $name ];

		//	Else...
		throw new \Exception("The requested resource does not exist.", 1);

	}

	public static function set( array $array )
	{

		if ( count( $array ) < 1 ) return false;
		foreach ($array as $key => $value) {
			
			self::$bucket[ $key ] = $value;
		}

		return true;		
	}
}