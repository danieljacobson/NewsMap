<?php

	// FUNCTION TO CALL YAHOOS PLACEMAKER API
	function getLocations($textToLocate)
	{
		// SET API KEY
		global $placeMakerAPIKey;

		// URL TO API
		$apiUrl = 'http://wherein.yahooapis.com/v1/document';

		// INPUT TYPE DEFINITION
		$inputType = 'text/html';

		// OUTPUT TYPE DEFINITION
		$outputType = 'rss';

		// POST STRING
		$post = 'appid=' . $placeMakerAPIKey . '&documentContent=' . $textToLocate . '&documentType=' . $inputType . '&outputType=' . $outputType;

		// EXECUTE CURL POST
		$curled = curl_init($apiUrl);
		curl_setopt($curled, CURLOPT_POST, 1);
		curl_setopt($curled, CURLOPT_POSTFIELDS, $post);
		curl_setopt($curled, CURLOPT_RETURNTRANSFER, 1);
		$locationResults = curl_exec($curled);

		return $locationResults;
	}

	// PARSE THE RESULTS FROM YAHOOS PLACEMAKER API
	function parseLocations($locationsToParse)
	{
		// REPLACE ALL : CHARACTERS IN EXTENDED NAMESPACE
		$loc = str_replace("cl:", "cl_", $locationsToParse);

		// POPULATE SimpleXML OBJECT
		$xmlLoc = new SimpleXMLElement($loc);

		// DEFINE VARIABLES
		$locName = '';
		$lat = '';
		$lon = '';
		$locCounter = 0;
		$arrLoc = Array();

		// ITERATE THROUGH THE XML ITEMS
		foreach ($xmlLoc->channel->item as $item)
		{
			// FOR EACH ITEM, ITERATE THROUGH EACH LOCATION
			foreach ($item->cl_contentlocation->cl_place as $place)
			{
				// CAST EACH VARIABLE AS STRINGS
				$locName = '' . $place->cl_name;
				$lat = '' . $place->cl_latitude;
				$lon = '' . $place->cl_longitude;

				// ELIMINATE ALL INSTANCES OF UNITED STATES (THERE ARE TOO MANY AND THEY DONT PROVIDE MUCH CONTEXT)
				if ($locName != 'United States')
				{
					// POPULATE ARRAY WITH LOCATION INFORMATION
					$arrLoc[$locCounter] = Array($locName, $lon, $lat);
					$locCounter++;
				}
			}
		}

		return $arrLoc;
	}


?>