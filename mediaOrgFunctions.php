<?php
	function getNPRStories()
	{
		global $nprAPIKey;

		// CURL FOR SOURCE FILES / NPR
		$nprUrl = 'http://api.npr.org/query?apiKey=' . $nprAPIKey . '&id=100&fields=title,teaser,text&output=NPRML&numResults=10';
		$nprFieldsNumber = 5;

		// CURL NPR API
		$nprCurlResult = curlSomething($nprUrl, '', '');

		// POPULATE SimpleXML OBJECT
		$nprml = new SimpleXMLElement($nprCurlResult);

		// DEFINE VARIABLES
		$totalText = "";
		$allLocCounter = 0;

		// ITERATE THROUGH EACH XML STORY
		foreach ($nprml->list->story as $story)
		{
			// CAST VARIABLES AS STRINGS
			$storyLink = '' . $story->link;
			$storyTitle = '' . $story->title;
			$storyTeaser = '' . $story->teaser;

			// ACCUMULATE ALL OF THE TEXT INTO ONE VARIABLE
			$totalText = "";
			$totalText = $totalText . $storyTitle;
			$totalText = $totalText . $storyTeaser;

			// ITERATE THROUGH EACH PARAGRAPH OF TEXT TO ADD TO THE ACCUMULATED TEXT VARIABLE
			foreach ($story->text->paragraph as $text)
			{
				$totalText = $totalText . " " . $text;
			}

			// PASS THE ACCUMULATED TEXT VARIABLE THROUGH THE YAHOO! PLACEMAKER API
			$locations = getLocations($totalText);

			// PARSE THE XML THAT IS RETURNED BY THE YAHOO! PLACEMAKER API
			$parsedLocations = parseLocations($locations);

			if (!empty($parsedLocations))
			{
				// ITERATE THROUGH EACH LOCATION
				for ($i=0;$i<count($parsedLocations);$i++)
				{
					// POPULATE THE VARIABLE CONTAINING ALL STORIES AND LOCATIONS (FOR THE MAP)
					$allNPRLocations[$allLocCounter] = Array($storyLink, $storyTitle, $storyTeaser, $parsedLocations[$i][0], $parsedLocations[$i][1], $parsedLocations[$i][2]);
					$allLocCounter++;
				}
			}
		}

		return $allNPRLocations;
	}


	function getNYTStories()
	{
		// CURL FOR SOURCE FILES / NYT
		//NYT key = 10b8ad6674b2723c2d0ed6bd5fc61b5a:5:57036131
		$nytUrl = 'http://www.nytimes.com/services/xml/rss/nyt/pop_top.xml';

		// CURL NYT FEED
		$nytCurlResult = curlSomething($nytUrl, '', '');

		// POPULATE SimpleXML OBJECT
		$nytxml = new SimpleXMLElement($nytCurlResult);

		// DEFINE VARIABLES
		$totalText = "";
		$allLocCounter = 0;

		// ITERATE THROUGH EACH XML STORY
		foreach ($nytxml->channel->item as $item)
		{
			// CAST VARIABLES AS STRINGS
			$storyTitle = '' . $item->title;
			$storyLink = '' . $item->link;
			$storyTeaser = '' . $item->description;

			// ACCUMULATE ALL OF THE TEXT INTO ONE VARIABLE
			$totalText = "";
			$totalText = $totalText . $storyTitle;
			$totalText = $totalText . $storyTeaser;

			// PASS THE ACCUMULATED TEXT VARIABLE THROUGH THE YAHOO! PLACEMAKER API
			$locations = getLocations($totalText);

			// PARSE THE XML THAT IS RETURNED BY THE YAHOO! PLACEMAKER API
			$parsedLocations = parseLocations($locations);

			if (!empty($parsedLocations))
			{
				for ($i=0;$i<count($parsedLocations);$i++)
				{
					// POPULATE THE VARIABLE CONTAINING ALL STORIES AND LOCATIONS (FOR THE MAP)
					$allNYTLocations[$allLocCounter] = Array($storyLink, $storyTitle, $storyTeaser, $parsedLocations[$i][0], $parsedLocations[$i][1], $parsedLocations[$i][2]);
					$allLocCounter++;
				}
			}
		}

		return $allNYTLocations;
	}
?>