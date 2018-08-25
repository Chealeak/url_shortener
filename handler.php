<?php

require_once 'db.php';

$url = $_POST['url'];

if ( !preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $url, $matches) ) {

	echo 'Введите правильный url';

} else {

	if ( $instance = DB::getInstance() ) {

		$sql = "SELECT * FROM urls WHERE url = ?";
		$statement = $instance->connection->prepare($sql);

		if ( $statement->execute( [$url] ) ) {

			if ( $statement->rowCount() === 0 ) {
				echo 'Your link: ' . createRow($instance, $url);
			} else {
				echo 'Your link: ' . getUrl($statement);
			}

		}

	} else {

		echo 'Соединиться с базой данных не удалось...';

	}

}

function createRow($instance, $url) {

	$config = require 'config.php';

	$shortcode = substr( md5( $url.mt_rand() ), 0, 5);

	if ( !checkShortcode($instance, $shortcode) ) {
		$shortcode = substr( md5( $url.mt_rand() ), 0, 5);
		checkShortcode($instance, $shortcode);
	}

	$sql = "INSERT INTO urls (url, shortcode) VALUES (?, ?)";
	$statement = $instance->connection->prepare($sql);
	$statement->execute([$url, $shortcode]);
	return "<a href=\"{$config['domain']}{$shortcode}\">{$config['domain']}{$shortcode}</a>";

}

function getUrl($statement) {

	$config = require 'config.php';

	while ( $row = $statement->fetch(PDO::FETCH_ASSOC) ) {
		return "<a href=\"{$config['domain']}{$row['shortcode']}\">{$config['domain']}{$row['shortcode']}</a>";
	}

}

function checkShortcode($instance, $shortcode) {

	$sql = "SELECT * FROM urls WHERE shortcode = ?";
	$statement = $instance->connection->prepare($sql);
	$statement->execute([$shortcode]);

	if ( $statement->rowCount() > 0 ) {
		return false;
	}

	return true;

}

?>