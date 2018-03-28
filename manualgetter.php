<?php

	//Include the database connection
	include_once('database_connection.php');

	//This is today's date
	$date = '2018-03-21';
	
	//This is the source URL
	$url = "http://dilbert.com/strip/" . $date;

	//Get the page's plain html
	$raw = shell_exec("curl '". $url ."'");

	//Break up the HTML into a useable array
	xml_parse_into_struct(xml_parser_create(), $raw, $html, $index);

	//This is an array for the comic data
	$comic = array();

	//Get the two elements that we need out of the pages html
	array_push($comic, $html[52]["attributes"]["CONTENT"]);
	array_push($comic, $html[71]["attributes"]["CONTENT"]);

	//We will now need to push that into the database
	$query = "INSERT INTO comics (`date`,`url`,`transcript`) VALUES ('". $date ."','". $comic[0] ."','". addslashes($comic[1]) ."')";
	$execute = mysqli_query($conn, $query);

	//Release the mysql connection
	mysqli_close($conn);
?>
