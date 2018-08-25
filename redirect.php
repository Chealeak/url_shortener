<?php

require_once 'db.php';

if ( $shortcode = $_GET['shortcode'] ) {

	if ( $instance = DB::getInstance() ) {

		$sql = "SELECT * FROM urls WHERE shortcode = ?";
		$statement = $instance->connection->prepare($sql);
		$statement->execute([$shortcode]);
		$url = '';

		while ( $row = $statement->fetch(PDO::FETCH_ASSOC) ) {
			$url = $row['url'];
		}

		header("Location: {$url}");
		die();
	}

}

?>