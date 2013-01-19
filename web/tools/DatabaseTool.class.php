<?php

	require_once("sqlcredentials.php");

	class DatabaseTool
	{
	
		function SanitizeInput($input)
		{
			// first ensure there are escape chars
			$retVal = mysql_real_escape_string($input);
			
			// return the sanitized string
			return $retVal;
		}
		
		function Connect()
		{
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			// select the correct DB within the mysql instance
			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this
		}
		
		function Query($query)
		{
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			return $result;
		}
	
	}

?>