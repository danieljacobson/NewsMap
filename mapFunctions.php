<?php
	// PRODUCES MARKERS FOR THE MAP
	function createMarkers($arrLocationsForMarkers, $varModifier, $sourceUrl, $sourceLogo)
	{
		$markerCounter = 0;
		for ($i=0;$i<count($arrLocationsForMarkers);$i++)
		{
			echo "var point = new GPoint (" . $arrLocationsForMarkers[$i][4] . ", " . $arrLocationsForMarkers[$i][5] . ");\n";
			echo "var " . $varModifier . "icon = get" . $varModifier . "Icon();\n";
			echo "var " . $varModifier . "marker" . $i . " = new GMarker(point, {icon:" . $varModifier . "icon});\n";
			$bubbleText = '<div style="padding:0 0 20px 0;"><a href="' . $sourceUrl . '" target="_blank"><img src="' . $sourceLogo . '" style=\"border:none;\" /></a><br />';
			$bubbleText = $bubbleText . "<a href=\"" . $arrLocationsForMarkers[$i][0] . "\" target=\"_blank\">" . str_replace("'", "\'", $arrLocationsForMarkers[$i][1]) . "</a><br />";
			$bubbleText = $bubbleText . "<em>" . $arrLocationsForMarkers[$i][3] . "</em><br />";
			$bubbleText = $bubbleText . str_replace("'", "\'", $arrLocationsForMarkers[$i][2]);
			echo "var textFor" . $varModifier . "Marker" . $i . " = '" . $bubbleText . "</div>';";
			echo 'GEvent.addListener(' . $varModifier . 'marker' . $i . ', "click", function() {' . $varModifier . 'marker' . $i . '.openInfoWindowHtml(textFor' . $varModifier . 'Marker' . $i . ', "maxWidth:100px");});';
			echo "map.addOverlay(" . $varModifier . "marker" . $i . ");\n\n";

			$markerCounter++;
		}
	}

	// PRODUCES ICONS FOR THE MAP
	function createIcon($iconUrl, $iconWidth, $iconHeight, $varModifier)
	{
		echo $varModifier . "icon = new GIcon(G_DEFAULT_ICON);";
		echo $varModifier . "icon.image = '" . $iconUrl . "';";
		echo $varModifier . "icon.iconSize = new GSize(" . $iconWidth . ", " . $iconHeight . ");";
		echo $varModifier . "icon.shadowSize = new GSize(0, 0);";
		echo $varModifier . "icon.iconAnchor = new GPoint(0, " . $iconHeight . ");";
		echo "return " . $varModifier . "icon;";
	}
?>