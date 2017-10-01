<?php 

class DBMonitor
{
	public $instance = 0;
	public $record = array();

	public function count()
	{
		return $this->instance;
	}

	public function increment()
	{
		$this->instance ++; 
	}

	public function log( $caller )
	{
		$p = new PrettyDate;
		$this->record[] = "Connection was opened from file '".$caller['file']." on line ".$caller['line']." around {$p->getFullTime( time() )}.";
		$this->instance ++; 
	}

	public function getLogs()
	{
		if ( empty( $this->record ) ) return 'No logs to show.';
		$logs = '';
		foreach ($this->record as $rec) {
			
			$logs .= $rec.'<br />';
		}

		return $logs;
	}
}