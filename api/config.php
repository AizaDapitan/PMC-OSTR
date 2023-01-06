<?php 
		
	$serverName = "LAPTOP-7B29UNBF\SQLEXPRESS";	
	$connectionInfo = array( "Database"=>"PMC-WFS", "UID"=>"sa", "PWD"=>"sa" );
	$conn = sqlsrv_connect($serverName, $connectionInfo);	
?>