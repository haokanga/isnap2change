<?php

	// The text to be read, url-encoded
	//$text_to_read = urlencode($_GET[text]);


	$text = 'Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below.';
    $text_to_read = urlencode($text);
	// Your API key here
	$apikey = '19961681ac25133e660005b9dbb48660';

	// The language to use
	$language = 'en_us';

	// The voice to use
	$voice= 'Male01';

	// API URL of text-to-speech enabler
	$api_url = 'http://tts.readspeaker.com/a/speak';

	// Compose API call url
	$url = $api_url . '?key='.$apikey.'&lang='.$language.'&voice='.$voice.'&text='.$text_to_read;

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	$audio_data = curl_exec($ch);

	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if ($status == 200 && ! curl_errno($ch)) {
		// Everything is fine and data is returned
		curl_close($ch);
		header('HTTP/1.1 200 OK');
		header('Content-Type: audio/mpeg');
		echo $audio_data;
	} else {
		// Cannot translate text to speech because of text-to-speech API error
		error_log(__FILE__ . ': API error while text-to-speech. error code=' . $status);
		curl_close($ch);
	}

?>