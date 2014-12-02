<?php
class HandlersocketBase{
	
	protected $hs = NULL;
	protected $index = 1;

	protected $db_name = 'test';
	protected $db_table;
	protected $db_host;
	protected $db_port;

	protected $primary_key = null;
	protected $columns = array();

	function __construct($db_host, $db_port, $db_name, $db_table)
	{
		$this->db_host = $db_host;
		$this->db_port = $db_port;
		$this->db_name = $db_name;
		$this->db_table = $db_table;
		$this->hs = new HandlerSocket($this->db_host, $this->db_port);
	}

	function init($array = array(), $index = 1, $primary_key = NULL)
	{
		$this->index = $index;
		$this->columns = implode(',', $array);
		if( ! isset($this->primary_key)) $this->primary_key = HandlerSocket::PRIMARY;
		$status = $this->hs->openIndex($this->index, $this->db_name, $this->db_table, $this->primary_key, $this->columns);
		
		if($status == FALSE)
			$this->error_log(__METHOD__, __LINE__, $this->hs->getError());
			
		return $status; 
	}
	
	function get($key, $op = '=')
	{
		if( ! is_array($key))
			return $this->hs->executeSingle(1, $op, array($key), 1, 0);

		$array = array();
		foreach($key as $row)
		{
			$query = array($this->index, $op, array($row), 1, 0);
			array_push($array, $query);
		}
		return $this->hs->executeMulti($array);
	}
	
	function add($array = array())
	{
		$status = $this->hs->executeInsert($this->index, $array);
		
		if($status = FALSE)	
			$this->error_log(__METHOD__, __LINE__, $this->hs->getError());

		return $status;
	}

	function update($key, $value = array(), $op = '=')
	{
		$update_array = array_merge(array($key), $value);
		$status = $this->hs->executeUpdate($this->index, $op, array('104'), $update_array, 1, 0);
		
		if($status = FALSE)
			$this->error_log(__METHOD__, __LINE__, $this->hs->getError());
			
		return $status;
	}
	
	function del($key, $op = '=')
	{
		$status = $this->hs->executeDelete($this->index, $op, array($key));
        
		if($status = FALSE)
			$this->error_log(__METHOD__, __LINE__, $this->hs->getError());
			
		return $status;
	}
	
	private function error_log($method, $line, $log)
	{
		echo "[ERROR]\n$method\nLine: $line\nMsg: $log\n";
	}
}
?>