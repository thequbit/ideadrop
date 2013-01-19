<?

	require_once("../tools/Idea.class.php");
	require_once("../tools/IdeaTool.class.php");
	require_once("../tools/ChopsTool.class.php");

	//
	// This file will return a json of all ideas a few at a time
	//

	// get passed in variables
	$page = $_GET['page'];
	$keyword= $_GET['keyword'];
	$chops = $_GET['chops'];
	
	// defualts for errors
	$errorcode = 0;
	$errortext = "No errors reported";
	$searchtype = "None";

	// our results are an empty array since no search was performed
	$results = array();
	
	// create an instance of our IdeaTool so we can perform our search
	$ideatool = new IdeaTool();

	// we will alway default to a keyword search, so check that first
	if( $keyword != "" )
	{
		// set our searchtype to keyword so the calling entity knows what they called
		$searchtype = "Keyword";
	}
	// no keyword, see if there is a chops specified
	elseif( $chops != "" )
	{
		// set our searchtype to chops so the calling entity knows what they called
		$searchtype = "Chops";
	
		// we need to get the chops id from the passed in chops
		$chopstool = new ChopsTool();
		$chopsid = $chopstool->GetChopsIDFromChopsName($chops);
	
		// test to make sure the chops specified actually exist
		if( $chopsid == -1 )
		{
			// the chops specified was not in the database
			$errorcode = 2;
			$errortext = "Chops specified was not found";
		}
		else
		{
			// perform the search
			$results = $ideatool->GetIdeasByChops($chopsid);	
		}
		
	}
	else
	{
		// the calling entity did not specify enough information to perform a search
		$errorcode = 1;
		$errortext = "No keyword or chops reported";
		$searchtype = "None";
	}
	
	// encode our result array into a json object
	$jsonresults = json_encode($results);
	
	// get the number of ides returned
	$resultcount = count($results);
	
	// return our json object with error information to the calling entity
	echo '{"errorcode":"' . $errorcode . '","errortext":"' . $errortext . '","searchtype":"' . $searchtype . '", "resultcount":"' . $resultcount . '", "results":' . $jsonresults . '}';

?>