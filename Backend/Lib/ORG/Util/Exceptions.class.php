<?php
class Exceptions extends Exception  {
	public function __construct($msg) {
		throw_exception($msg);
	}
}
?>