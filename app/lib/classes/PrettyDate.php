<?php 

/**
 * Simple class for formatting
 * DateTime objects
 *
 * @package default
 * @author Abeeb Ola
 **/


class PrettyDate {

	private $date;
	private $time;
	private $format;
	private $error;
	/**
	 * Returns time difference
	 *
	 * This function calculates the time difference 
	 * between a specified timestamp and the current
	 * timestamp. It uses the time() function
	 *
	 * @param string|int timestamp A string or integer representing
	 * a UNIX timestamp.
	 * @return string time elapsed relative to the received timestamp.
	 **/
	public function calcDate($timestamp)
  	{
	    //  Check if argument supplied is a valid timestamp
	    $tArr = str_split($timestamp);
	    $tlen = count($tArr);

	    for ($i = 0; $i < $tlen; $i++){
	      $tArr[$i] = (int) $tArr[$i];
	    }

	    $tStr = (int) implode('', $tArr);

	    //  If not throw an error.
	    if ( $tStr != $timestamp ) {
	      throw new Exception("The argument supplied is not a valid timestamp", 1);      
	    }

	    $curr_time = time();

	    //  Calculate difference between timestamps.
	    $diff = $curr_time - $timestamp;

	    if ($diff > 60*60*24*365) return $this->timeEnding( floor($diff / (60*60*24*365)), 'year');
	    if ($diff > 60*60*24*30) return $this->timeEnding( floor($diff / (60*60*24*30)), 'mo');
	    if ($diff > 60*60*24*7) return $this->timeEnding( floor($diff / (60*60*24*7)), 'week');
	    if ($diff > 60*60*24) return $this->timeEnding( floor($diff / (60*60*24)), 'day');
	    if ($diff > 60*60) return $this->timeEnding( floor($diff / (60*60)), ' hr');
	    if ($diff > 60) return $this->timeEnding( floor($diff / (60)), 'min');
	    if ($diff < 60) return 'just now';

	}

	public function processDate($date_input)
	{
		//	Check if supplied date is a timestamp.
		//	This method of checking is being used in the event
		//	that a timestamp is passed in string value type instead of the expected
		//	integer type.
		
		$isTimestamp = true;
		$tArr = str_split($date_input);
		$tlen = count($tArr);

		for ($i = 0; $i < $tlen; $i++){

			$tArr[$i] = (int) $tArr[$i];

		}

		$tStr = implode('', $tArr);

		if ( $tStr != $date_input ) $isTimestamp = false;

		$this->date = ($isTimestamp) ? $date_input : date_create($date_input);
        
        try {
         	
         	return date( $this->format, $this->date );

         } catch (Exception $e) {
         	
         	$this->error = "<strong>Simple error: </strong>The format '{$this->format}' is invalid. Please check again.<br />";
         	$this->error .= "<strong>Technical error: </strong>{$e->getMessage()}<br />";
         	return false;
         } 
	}

	public function getDate($date_input, $date_format = ''){

		$this->format = ( ! empty($date_format) ) ? $date_format : "F j, Y";
		return $this->processDate($date_input);

	}

	public function getTime($date_input, $time_format = ''){

		$this->format = ( ! empty($date_format) ) ? $date_format : "g:i:s a";
		return $this->processDate($date_input);
		
	}

	public function getFullTime($date_input, $datetime_format = ''){

		$this->format = ( ! empty($datetime_format) ) ? $datetime_format : "F j, Y g:i:s a";
		return $this->processDate($date_input);
		
	}

	public function getError()
	{
		return $this->error;
	}

	public function timeEnding($time, $end){
      $end = $time.' '.$end;
      if ( $end === ' day' && $time < 2 ) return 'yesterday'; 
      return ($time > 1) ? $end.'s ago' : $end.' ago';
    }


}