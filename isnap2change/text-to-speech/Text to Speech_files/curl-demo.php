<?php
function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

$ch = curl_init();
$text = "Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below.";
$encodedText = encodeURIComponent($text);

$source = "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?accept=audio/wav&text=$encodedText&voice=en-US_AllisonVoice";
$username = "6678c2a8-0261-44f8-a9d3-9a2fb43d65b6";
echo $source;
$password = "40byApeYUV7f";

curl_setopt($ch, CURLOPT_URL, $source);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);

$destination = dirname(__FILE__)."/test.wav";
$file = fopen($destination, "w+");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FILE, $file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

curl_exec($ch);

curl_close($ch);
fclose($file);
?>