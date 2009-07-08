<?php

require "apiKeys.php";
require "mediaOrgFunctions.php";
require "mapFunctions.php";
require "locationFunctions.php";
require "curlFunctions.php";

$allNPRLocations = getNPRStories();
$allNYTLocations = getNYTStories();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>NewsMap - Mapping News Stories from NPR and NYTimes</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $googleMapsAPIKey ?>&sensor=false" type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[

    var map = null;
    var geocoder = null;
    var point = null;

	function createMarker(marker, point, text)
		{
  			GEvent.addListener
  				(marker, "click", function()
					{
						marker.openInfoWindowHtml(text);
					}
  				);
			return marker;
		}


	function showAddress(address, text)
		{
			if (geocoder)
				{
					geocoder.getLatLng(address,function(point)
						{
							if (!point)
								{
									alert(address + " not found");
								}
							else
								{
									//map.setCenter(point, 13);
									var marker = new GMarker(point);
									map.addOverlay(marker);
									//marker.openInfoWindowHtml(address);
									createMarker(marker, point, text);
								}
						}
					);
				}
		}

	function getNPRIcon()
		{
			<?php
				$url = 'http://www.danieljacobson.com/oscon/logo_npr_32.gif';
				$width = 32;
				$height = 12;
				$modifier = 'NPR';
				createIcon($url, $width, $height, $modifier);
			?>
		}

	function getNYTIcon()
		{
			<?php
				$url = 'http://www.danieljacobson.com/oscon/nytlogo15.gif';
				$width = 15;
				$height = 22;
				$modifier = 'NYT';
				createIcon($url, $width, $height, $modifier);
			?>
		}

	function loadNPRStories()
		{
			// SET NPR MARKERS
			<?php
				$logo = 'http://media.npr.org/images/logo_npr_125.gif';
				$url = 'http://www.npr.org/';
				createMarkers($allNPRLocations, "NPR", $url, $logo);
			?>
		}

	function loadNYTStories()
		{
			// SET NYT MARKERS
			<?php
				$logo = 'http://danieljacobson.com/oscon/nytlogo200.gif';
				$url = 'http://www.nytimes.com/';
				createMarkers($allNYTLocations, "NYT", $url, $logo);
			?>
		}

    function loadMap()
    	{
      		if (GBrowserIsCompatible())
			{
				// GET MAP
				map = new GMap2(document.getElementById("map"));

				// SET CONTROLS
				map.addControl(new GLargeMapControl());
				map.addControl(new GMapTypeControl());

				// SET MAP CENTER
				map.setCenter(new GLatLng(37.1234, -88.1234), 2);

				// CREATE GEOCODER OBJECT
				geocoder = new GClientGeocoder();

				loadNPRStories();

				loadNYTStories();
			}
	    }
    //]]>
    </script>
  </head>
  <body onload="loadMap()" onunload="GUnload()">
    <div id="map" style="width: 1000px; height: 600px"></div>
  </body>
</html>