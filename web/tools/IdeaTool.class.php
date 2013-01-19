<?php

	require_once("DatabaseTool.class.php");
	require_once("UserTool.class.php");
	require_once("Idea.class.php");
	
	class IdeaTool
	{
	
		function AddIdea($username, $name, $description)
		{
			// we will represent our success in adding the user
			$success = False;
	
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				$usertool = new UserTool();
				$userid = $usertool->GetUserIDByUserName($username);
				
				//
				// TODO: make sure the idea name doesn't already exist
				//
				
				// create the idea in the database
				$query = 'INSERT INTO ideas(creationdatetime,owerid,name,description) VALUES("' . date( 'Y-m-d H:i:s' ) . '",' . $userid . ',"' . $name . '","' . $description . '")';
				$result = $db->query($query);
				
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
		
		function AddKeywordToIdea($ideaid, $keyword)
		{
			// we will represent our success in adding the chops to the idea
			$success = False;
	
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// add the keyword to the list of chops the idea needs
				$query = 'INSERT INTO ideakeywords(keyword, ideaid) VALUES(' . $keyword . ', ' . $ideaid . ')';
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

		function RemoveKeywordToIdea($ideaid, $keyword)
		{
			// we will represent our success in adding the chops to the idea
			$success = False;
	
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// remove the keyword from the list of keywords for the idea
				$query = 'DELETE FROM ideakeywords WHERE ideaid=' . $ideaid . ' AND keyword="' . $keyword . '"';
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
		
		function AddChopsToIdea($ideaid, $chopsid)
		{
			// we will represent our success in adding the chops to the idea
			$success = False;
	
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// make sure the chops exist
				$result = $query = 'SELECT chopsid FROM chops WHERE chopsid="' . $chopsid . '"';
				$db->query($query);
				
				// test to see if the chops exist
				if( mysql_num_rows($result) > 0)
				{
				
					// add the chops to the list of chops the idea needs
					$query = 'INSERT INTO ideachops(ideaid,chopsid) VALUES(' . $ideaid . ', ' . $chopsid . ')';
					
					// success!
					$success = True;
				}
				else
				{
					// the chops doesn't exist yet, can't add it
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
	
		function RemoveChopsFromIdea($ideaid,$chopsid)
		{
			// we will represent our success in removing the chops from the idea
			$success = False;
	
			try
			{
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
				
				// delete the row that has the chops/idea association
				$query = 'DELETE FROM ideachops WHERE ideaid=' . $ideaid . ' AND chopsid=' . $chopsid;
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
	
		function GetIdeasByChops($chopsid)
		{
			// our returned array of ideas, if there were none it will have a count of 0
			$ideas = array();
		
			// try and add the user to the database
			//try
			//{
			
				//echo "chopsid: " . $chopsid . "<br>";
			
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
			
				// get all of the ideaid's that have that chops associated with them
				$query = 'SELECT ideaid FROM ideachops WHERE chopsid="' . $chopsid . '"';
				$result = $db->query($query);
				
				// get all of the ideaid's that have that keyword
				while($r = mysql_fetch_assoc($result))
				{
				
					// get the id of the idea that was returned with having that keyword
					$ideaid = $r['ideaid'];
				
					//echo $ideaid;
				
					// get all of the ideaid's that have that keyword associated with them
					$query = 'SELECT creationdatetime,ownerid,name,description FROM ideas WHERE ideaid="' . $ideaid . '"';
					$result = $db->query($query);
				
					// get the idea from the row
					$r = mysql_fetch_assoc($result);
					
					// create and populate our idea object
					$idea = new Idea();
					$idea->creationdatetime = $r['creationdatetime'];
					$idea->ownerid = $r['ownerid'];
					$idea->name = $r['name'];
					$idea->description = $r['description'];
					
					// populate the username and display name of the owner from the database
					$user = new UserTool();
					
					//echo "ownerid: " . $idea->ownerid . "<br>";
					
					$idea->ownerdisplayname = $user->GetDisplayNameByUserID($idea->ownerid);
					$idea->ownerusername = $user->GetUsernameByUserID($idea->ownerid);
					
					// add the idea object to our array of ideas
					$ideas[] = $idea;
				}

			//}
			//catch (Exception $e)
			//{
				// reset our array to a count of 0
			//	$ideas = array();
			//}
			
			// return our array of ideas
			return $ideas;
		}
	
		function GetIdeasByKeyword($keyword)
		{
			// our returned array of ideas, if there were none it will have a count of 0
			$ideas = array();
		
			// try and add the user to the database
			try
			{
			
				// create an instance of our DB tool, and connect to the database
				$db = new DatabaseTool();
				$db->Connect();
			
				// get all of the ideaid's that have that keyword associated with them
				$query = 'SELECT ideaid FROM ideakeywords WHERE keyword="' . $keyword . '"';
				$result = $db->query($query);
				
				// get all of the ideaid's that have that keyword
				while($r = mysql_fetch_assoc($result))
				{
				
					// get the id of the idea that was returned with having that keyword
					$ideaid = $r['ideaid'];
				
					// get all of the ideaid's that have that keyword associated with them
					$result = $query = 'SELECT creationdatetime,ownerid,name,description FROM ideas WHERE ideaid="' . $ideaid . '"';
					$db->query($query);
				
					// get the idea from the row
					$r = mysql_fetch_assoc($result);
					
					// create and populate our idea object
					$idea = new Idea();
					$idea->creationdatetime = $r['creationdatetime'];
					$idea->ownerid = $r['ownerid'];
					$idea->name = $r['name'];
					$idea->description = $r['description'];
					
					// populate the username and display name of the owner from the database
					$user = new UserTool();
					$idea->owneruname = $user->GetDisplayNameByUserID($idea->ownerid);
					$idea->ownerusername = $user->GetUsernameByUserID($idea->ownerid);
					
					// add the idea object to our array of ideas
					$ideas[] = $idea;
				}
			}
			catch (Exception $e)
			{
				// reset our array to a count of 0
				$ideas = array();
			}
			
			// return our array of ideas
			return $ideas;
		}
	}

?>