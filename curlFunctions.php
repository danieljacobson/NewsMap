<?php

	// CURL FUNCTION
	function curlSomething($url, $numFields, $fieldsString)
	{
		//OPEN CONNECTION
		$curled = curl_init($url);

		if ($numFields=='')
		{
			curl_setopt($curled,CURLOPT_URL,$url);
			curl_setopt($curled, CURLOPT_HEADER,0);
			curl_setopt ($curled, CURLOPT_RETURNTRANSFER, true);
		}
		else
		{
			curl_setopt($curled, CURLOPT_POST, $numFields);
			curl_setopt($curled, CURLOPT_POSTFIELDS, $fieldsString);
			curl_setopt ($curled, CURLOPT_RETURNTRANSFER, true);
		}

		//EXECUTE CURL POST
		$result = curl_exec($curled);

		//CLOSE CONNECTION
		curl_close($curled);

		return $result;
	}

?>