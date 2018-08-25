<?php

class DB {

	private static $instance = null;
	public $connection = null;
	
	private function __construct() {
		$config = require 'config.php';
		$dsn = "{$config['driver']}:host={$config['host']};dbname={$config['dbname']}";
	    $this->connection = new PDO($dsn, $config['username'], $config['password']);
	}

	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __clone() {}
	private function __wakeup() {}

}

?>