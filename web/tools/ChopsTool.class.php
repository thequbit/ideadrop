<?php

	require_once("Chops.class.php");

	class ChopsTool
	{
		function AddChops($chops, $description)
		{
			// we will represent our success in adding the user
			$success = False;
	
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// create the chops in the database
				$result = $query = 'INSERT INTO chops(chopsname,chopsdescription) VALUES("' . $chops . ',"' . $description . '")';
				$db->query($query);
				
				// success!
				$success = True;
			}
			catch (Exception $e)
			{
				$success = False;
			}
			
			// return our success
			return $success;
		}
		
		function GetChopsIDFromChopsName($chops)
		{
			// we will represent our success in adding the user
			$chopsid = -1;
		
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// get the userid of the username
				$query = 'SELECT chopsid FROM chops WHERE chopsname="' . $chops . '"';
				$result = $db->query($query);
				
				// test to see if user exists
				if( mysql_num_rows($result) > 0)
				{
					// get the first responce from the DB
					$r = mysql_fetch_assoc($result);
				
					// pull the userid from the returned data
					$chopsid = $r['chopsid'];
				}
				else
				{
					// user does not exist, we will return -1 for their ID
					$chopsid = -1;
				}
				
			}
			catch (Exception $e)
			{
				$chopsid = -1;
			}
			
			// return our success
			return $chopsid;
		}
	}

?>