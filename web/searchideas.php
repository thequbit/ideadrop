<html>
<head>
	<title>IdeaDrop - Share Great Ideas</title>
	
	<meta name="description" content="Meeting Minute Agrigator and Search Engine for Monroe County, NY">
	<meta name="keywords" content="Monroe,Minutes,MonroeMinutes,Rochester,Meetings">

	<link href="css/main.css" rel="stylesheet" type="text/css">
	
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/search.js"></script>
	
</head>
<body>

	<div class="top">
	
		<div class="sitewrapper">
		
			<div class="content">
			
				<div class="title">
					<br>
					<h1>Idea Drop</h1>
					<h4> - Share Great Ideas - Find Awesome People - </h4>
					<br>
			
				</div>
			
				<div class="sidebar">
				
					<!--
					<div class="sidebarheader">
				
						Welcome to Idea Drop.
						
					</div>
					-->
					
					<div class="navlinks">
					
						<div class="navlink">
							<a href="createuser.php">Create Login</a>
						</div>
					
						<div class="navlink">
							<a href="login.php">User Login</a>
						</div>
						
						<div class="navlink">
							<a href="manageuser.php">Manager User Profile</a>
						</div>
						
						<div class="navlink">
							<a href="createidea.php">Create an Idea</a>
						</div>
						
						<div class="navlink">
							<a href="manageidea.php">Manage Your Ideas</a>
						</div>
						
						<div class="navlink">
							<a href="searchideas.php">Search For Ideas</a>
						</div>
					
						<div class="navlink">
							<a href="about.php">About Idea Drop</a>
						</div>
					
					</div>
				
				</div>
				
				<div class="meat">
				
					<div class="index">
				
						<div id="search" class="search">

							<div id="searcharea" class="searcharea">

								<div id="keywordsearch" class="keywordsearch">
									Keyword Search: 
										<input type="text" id="keywordsearchstring" name="searchstring" size="20"> 
										<button id="searchbutton" onclick="performKeywordSearch()">Search</button>
								</div>

								<div id="chopssearch" class="chopssearch">
									Chops Search: 
										<input type="text" id="chopssearchstring" name="searchstring" size="20"> 
										<button id="searchbutton" onclick="performChopsSearch()">Search</button>
								</div>
								
							</div>
						
						</div>
						
						<div id="searchresults" class="searchresults">
						
							
						
						</div>
					
					</div>
				
				</div>
			
			</div>
		
			<div class="footerwrapper">
				
				<div class="footer">
				
					Idea Drop - Share Great Ideas | Created by Tim Duffy | 2013 | <a href="https://github.com/thequbit/ideadrop">https://github.com/thequbit/ideadrop</a>
			
				</div>
			
			</div>
		
		</div>
	
	</div>

</body>
</html>