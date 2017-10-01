<?php 

//declare(strict_types = 1);

/**
* Validator class for input fields
* @author:	Abeeb Ola
*
**/

class Validator 
{
	private $errors = [];


	/**
	 * Class constructor
	 * Determines the Request Method
	 * Or sets it as a POST request
	 * if not specified
	 *
	 * @param String $requestType
	 *
	 * @return void
	 * 
	 **/
	
	function __construct(){}

	public function field($input, $conditions=[]){
		foreach ($conditions as $condition) {
			$this->isValid($input, $condition);
		}
	}

	private function isValid($input, $condition){
		switch ($condition) {
			case 'name':
				
				break;
			
			default:
				# code...
				break;
		}
	}

	function isValidType( $input_val, $type_array )
	{
		// If input is empty, return true. If it was a required field, 
		// the 'validate' method would have returned false before getting to this stage.
		if ($input_val == '')
			return true;
		foreach ($type_array as $tar) {

			if ( $tar == '' )
				$tar = 'novalidation';
			
			switch ($tar) {
				case 'username':
					if ( $this->validateUsername( $input_val ) ) return true;
					break;

				case 'fullname':
					if (  $this->validateFullName( $input_val ) ) return true;
					break;

				case 'name':
					if (  $this->validateName( $input_val ) ) return true;
					break;

				case 'password':
					if ( $this->validatePassword( $input_val ) ) return true;
					break;

				case 'email':
					if ( $this->validateEmail( $input_val ) ) return true;
					break;

				case 'phone':
					if ( $this->validatePhoneNumber( $input_val ) ) return true;
					break;

				case 'url':
					if ( $this->validateUrl( $input_val ) ) return true;
					break;

				case 'novalidation':
					return true;
					break;
				
				default:
				$this->errorMsg = "Validation type does not exist";
					throw new Exception("Validation type does not exist", 1);					
					break;
			}

		}

		return false;
	}

	 /**
     * Validate array of form field values
     *
     * @param array $arr
     *
     * @return array | bool
     *
     */


	public function validate(array $arr)
	{
		$returnArr = array(); //	Data to be returned on successful validation
		$dataArr = $arr;
		$valid = true;

		foreach ($dataArr as $key => $value) {
			
			//	Check the data from the array of values gotten from each $data key
			$arr_val = $value;

			//	Check if each array has the minimum required number of elements (3)
			//	If not, pad it.

			if (count($arr_val) < 3 ){
				array_push($arr_val, '', '', '');

				//	Give the 3rd element a value of 'false'
				//	This 3rd element is normally checked to determine if the key can 
				//	contain an empty value or if it's a required field

				$arr_val[2] = false;
			}

			//	The input key is the first element
			$inputKey = $arr_val[0];

			//	Check if key exists in Request data

			if ( ! isset($this->reqType[$inputKey]) ){
				$this->errorMsg = 'The '.$this->requestType.' data does not contain the index \''.$inputKey.'\'.';
				return false;
			}

			//	Check if the current key is a required field. If so, exit if it's empty
			if ($arr_val[2]){

				if (empty($this->reqType[$inputKey])){
					$this->errorMsg = 'The '.$this->requestType.' data index \''.$inputKey.'\' can\'t contain empty data. It is a required field';
					return false;
				}
			}

			//	Determine the type of validation to carry out
			//	based on the value of the 2nd element of the key

			$input_type = explode(':', $arr_val[1] );

			//	Get input value
			$inputValue = $this->reqType[$arr_val[0]];

			if ( ! $this->isValidType( $inputValue, $input_type ) ) return false;

			//	Get value of the data from the REQUEST 

			$returnArr[$key] = $inputValue;


		}

		return $returnArr;


	}

	/**
	 * Self-explanatory class methods
	 * 
	 **/

	private function validateFullName($input)
	{
		$names = explode(' ', $input);
		$allowedString = '^([A-Za-z]+([-|\']{1})?[A-Za-z]+)$';

		foreach ($names as $name) {
			if (strlen($name) >= 50){

				$this->errorMsg = 'Your first name is too long. It can\'t be longer than 50 characters';
				return false;
			
			} 

			if (!filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>"/$allowedString/")))){

				$this->errorMsg = 'This name contains unsupported characters.';
				return false;

			}
		}

		return true;

	}

	private function validateName($input, $errorMsg = '')
	{
		$fname = $input;
		$allowedString = '^([A-Za-z]+([-|\']{1})?[A-Za-z]+)$';

		if (!filter_var($fname, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>"/$allowedString/")))){

			$this->errorMsg = 'This name contains unsupported characters.';
			return false;

		}

		return true;

	}


	public function validateUsername($input)
	{
		$username = $input;
		$allowedString = '^([A-Za-z_]+([\d]+)?)+$';

		if (strlen($username) >= 25){

			$this->errorMsg = 'Your username is too long. It can\'t be longer than 25 characters';
			return false;
		
		} 

		if (!filter_var($username, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/'.$allowedString.'/')))){

			$this->errorMsg = 'Your username can only contain letters, numbers and underscores';
			return false;

		} 

		return true;
	}

	public function validatePassword($input)
	{
		$user_pass = $input;

		if (strlen($user_pass) < 6){

			$this->errorMsg = 'Your password is too short. It must contain at least 6 characters.';
			return false;		
		}

		if (strlen($user_pass) > 50){

			$this->errorMsg = 'Your password is too long. It can\'t be more than 50 characters long.';
			return false;		
		}

		return true;

	}

	public function validateEmail($input)
	{
		$user_email = $input;

		if (strlen($user_email) >= 50){

			$this->errorMsg = 'Your email address is too long. It can\'t be longer than 50 characters';
			return false;
		
		}

		if ( ! filter_var($user_email, FILTER_VALIDATE_EMAIL)){

			$this->errorMsg = 'This is not a valid email address';
			return false;		

		}

		return true;
	}

	public function validatePhoneNumber($input){
		return true;
	}

	public function validateUrl($input){

		if ( $parts = parse_url($input) ) {

			if ( ! isset($parts["scheme"]) ) $input = "http://$input";
			
		}

		if ( ! filter_var( $input, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED ) ) {
			$this->errorMsg = 'Please enter a valid website address';
			return false;
		}

		return true;
	}

	public function errorMsg()
	{
		return $this->errorMsg;
	}

}



?>