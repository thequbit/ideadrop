<?php

	require_once("DatabaseTool.class.php");
	
	class UserTool
	{
	
		function GetDisplayNameByUserID($userid)
		{
			// we will be the display name we return from the userid
			$displayname = "";
		
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// get the displayname of the userid
				$query = 'SELECT displayname FROM users WHERE userid="' . $userid . '"';
				$result = $db->query($query);
				
				// get the first responce from the DB
				$r = mysql_fetch_assoc($result);
			
				// pull the displayname from the returned data
				$displayname = $r['displayname'];
			}
			catch (Exception $e)
			{
				$displayname = "";
			}
			
			// return our success
			return $displayname;
		}
	
		function GetUsernameByUserID($userid)
		{
			// we will be the username we return from the userid
			$username = "";
		
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// get the username of the userid
				$query = 'SELECT username FROM users WHERE userid=' . $userid;
				$result = $db->query($query);
				
				// get the first responce from the DB
				$r = mysql_fetch_assoc($result);
			
				// pull the displayname from the returned data
				$username = $r['username'];
			}
			catch (Exception $e)
			{
				$username = "";
			}
			
			// return our success
			return $username;
		}
	
		///
		/// GetUserIDByUserName
		///			This function will return the userid of the passed in username
		///
		/// $username - the username of the userid to return
		///
		/// returns:
		///			Int - userid if user exists, else -1
		///
		function GetUserIDByUserName($username)
		{
			// we will represent our success in adding the user
			$userid = -1;
		
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// get the userid of the username
				$query = 'SELECT userid FROM users WHERE username="' . $username . '"';
				$result = $db->query($query);
				
				// test to see if user exists
				if( mysql_num_rows($result) > 0)
				{
					// get the first responce from the DB
					$r = mysql_fetch_assoc($result);
				
					// pull the userid from the returned data
					$userid = $r['userid'];
				}
				else
				{
					// user does not exist, we will return -1 for their ID
					$userid = -1;
				}
				
			}
			catch (Exception $e)
			{
				$userid = -1;
			}
			
			// return our success
			return $userid;
		}
	
		///
		/// CreateUser
		///			Creates a new user in the system with the specified username and password
		///
		/// $username - the new username for the user
		/// $password - the password to use for the user
		///
		/// returns:
		///			Bool - True if successful, else False
		///
		function CreateUser($username, $password)
		{
			// we will represent our success in adding the user
			$success = False;
		
			try
			{
		
				// get the md5 hash from the password
				$passwordhash = md5($password);
			
				// generate validation code by hashing the $username concatinated with the password
				$validationcode = md5($username . $password);
			
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// first, check to make sure the username is available
				$userid = GetUserIDByUserName($username);
				
				if( $userid == -1 )
				{
				
					// we need to create a permissions entry for the user
					$query = 'INSERT INTO permissions (enabled, isadmin, banned) VALUES(1,0,0)';
					$db->query($query);
					$permissionid = mysql_insert_id();
					
					// we need to create a profile entry for the user, although there won't be anything in it
					$query = 'INSERT INTO profiles (creationdate) VALUES(' . date('Y-m-d') . ')';
					$db->query($query);
					$profileid = mysql_insert_id();
					
					// finally, we need to create our user with the permissionid and profile id.
					// note: we are also putting in the validation code and setting validated to FALSE.
					//       this will be changed once the user is validated via email
					$query = 'INSERT INTO users(username,passwordhash,permissionid,validationcode,validated,profileid) VALUES("' . $username . '", "' . $passwordhash . '", "' . $permissionid . '", "' . $validationcode . '", 0, ' . $profileid . '")';
					$db->query($query);
				
					$success = True;
				}
				else
				{
					// the username already exists!
					$success = False;
				}
			}
			catch (Exception $e)
			{
				$success = False;
			}
			
			// return our success
			return $success;
		}
		
		///
		/// ValidateUser
		///			This function will set the flag for a user to "validated"
		///
		/// $validationcode - the validation code of the user to validate
		///
		/// returns:
		///			Bool - True if successful, else False
		///
		function ValidateUser($validationcode)
		{
			// this is the value we will return to tell the calling function if we were successful
			$success = False;
		
			try
			{
		
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// get the userid of the validationcode from the database
				$result = $query = 'SELECT userid FROM users WHERE validationcode="' . $validationcode . '"';
				$db->query($query);
				
				if( mysql_num_rows($result) > 0)
				{
					// update the validated field for the user
					$query = 'UPDATE users SET validated=1 WHERE validationcode="' . $validationcode . '"';
					$db->query($query);
					
					// success!
					$success = True;
				}
				else
				{
					// there was no user with that validation code, we were not successful
					$success = False;
				}
			}
			catch (Exception $e)
			{
				$success = False;
			}
			
			// return our success
			return $success;
		}
		
		///
		/// ChangePassword
		///			This function changes the password for the user
		///
		/// $username - the username of the user to change the password for
		/// $oldpassowrd - the previous password used by the user
		/// $newpassword - the new password to be set for the user
		///
		/// returns:
		///			Bool - True if successful, else False
		///
		function ChangePassword($username, $oldpassowrd, $newpassword)
		{
			// this is the value we will return to tell the calling function if we were successful
			$success = False;
	
			try
			{
		
				// first see if the user has entered the correct previous password
				$loginok = $this->CheckCredentials($username, $oldpassword);
		
				// check if the login is good
				if( loginok == True)
				{
				
					// get the md5 hash from the password
					$newpasswordhash = md5($newpassword);
			
					// create an instance of our DB tool, and connect to the database
					$db = new DatabaseTool();
					$db->Connect();
					
					// update the users password hash
					$query = 'UPDATE users SET passwordhash="' . $newpasswordhash . '" WHERE username="' . $username . '"';
					$result = $db->query($query);
					
					// success!
					$success = True;
				}
				else
				{
					// the user did not enter the correct old password
					$success = False;
				}
				
			}
			catch (Exception $e)
			{
				$success = False;
			}
			
			// return our success
			return $success;
		
		}
		
		///
		/// ChangePassword
		///			This function changes the password for the user
		///
		/// $username - the username of the user to check the credentials for
		/// $password - the password for the user
		///
		/// returns:
		///			Bool - True if successful, else False
		///
		function CheckCredentials($username, $password)
		{
			
			// this is the value we will return to tell the calling function if we were successful
			$success = False;
		
			try
			{
		
				// get the md5 hash from the password
				$passwordhash = md5($password);
		
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// get the passwordhash for the username
				$query = 'SELECT passwordhash FROM users WHERE username="' . $username . '"';
				$result = $db->query($query);
				
				// get the first responce from the DB
				$r = mysql_fetch_assoc($result);
				
				if( $r['passwordhash'] == $passwordhash )
				{
					// the credentials were correct
					$success = True;
				}
				else
				{
					// the credentials were incorrect
					$success = False;
				}
			}
			catch (Exception $e)
			{
				$success = False;
			}
			
			// return our success
			return $success;
		}
	}
	
	

?>