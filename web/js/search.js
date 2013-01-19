
//
// This file handles all of the JQuery search functions
//

	function performChopsSearch()
	{
		var chops = document.getElementById('chopssearchstring').value;
		if( chops == "" )
		{
			alert("Enter a chops first to perform a search");
			return;
		}
		
		// generate our post data
		var postData = {};
		postData.chops = chops;
		
		// get json from api call
		$.getJSON("./api/searchapi.php",
			postData,
			function(data)
			{
			
				// init our html contents variable
				var resultsHtml = "";
			
				// clear out any html that may have been put there already from a previous search
				$("#searchresults").html("");
				
				// check to see if there was an error
				if( data.errorcode != "0" )
				{
					// TODO: switch on error to see what we want to display to the user
					
					resultsHtml += "<br><p>There was an error processing your request: " + data.errortext + "</p><br>";
				}
				else
				{
					// test to see if there were any results returned
					if( data.results.length == 0 )
					{
						// no results, tell the user
						resultsHtml += "<br><p>Your search criteria returned zero results.  Please refine your search and try again.</p><br>";
					}
					else
					{
						// itterate through the returned json array and list the ideas that were returned
						$.each(data.results, 
							function(i,item)
							{
							
								resultsHtml += '<div class="searchresult">\n';
								resultsHtml += '<p><b>Name:</b> <a href="' + item.name + '">' + item.name + '</a></p>\n';
								resultsHtml += "<p><b>Owner:</b> " + item.ownerdisplayname + "</p>\n";
								resultsHtml += "<p><b>Creation Date:</b> " + item.creationdatetime.substring(0,9) + "</p>\n";
								
								resultsHtml += '</div><br>\n';
							}
						);
					}
					
				}
				
				$("#searchresults").html(resultsHtml);
				$('#searchresults').show("slow");
			}
		);
	}

	function displaySubOrg(orgname)
	{
		
		var postData = {};
		postData.orgname = orgname;
		postData.orgtype = "suborg";
		
		// get json from api call
		$.getJSON("./api/orgapi.php",
			postData,
			function(data) {
			
				// init our html contents variable
				var resultsHtml = "";
				
				// clear out any html that may have been put there already.
				$("#orginfo").html("");
			
				resultsHtml += '<h3>' + data.name + '</h3>';
				resultsHtml += 'Belongs to Organization: ' + data.organiztionname + '<br>';
				resultsHtml += 'Website: <a href="' + data.websiteurl + '">' + data.websiteurl + '</a><br>';
				resultsHtml += 'Documents Website: <a href="' + data.documentsurl + '">' + data.documentsurl + '</a><br>';
				resultsHtml += 'Number of Indexed Documents: ' + data.indexeddocs + '<br>';
				//resultsHtml += '----<br>';
				//resultsHtml += 'Script Name: ' + data.scriptname + '<br>';
				//resultsHtml += 'DB Populated: ' + data.dbpopulated + '<br>';
				
				$("#orginfo").html(resultsHtml);
			
				// show our results to the user
				$('#orginfo').hide();
				$('#orginfo').show("slow");
			
			}
		);	

	}