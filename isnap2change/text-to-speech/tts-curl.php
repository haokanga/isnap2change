<?php
function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

$ch = curl_init();
$text = "Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below.";
$encodedText = encodeURIComponent($text);

//$source = "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?accept=audio/wav&text=$encodedText&voice=en-US_AllisonVoice";

$source = "https://text-to-speech-demo.mybluemix.net/api/synthesize?voice=en-US_AllisonVoice&text=I%20have%20been%20assigned%20to%20handle%20your%20order%20status%20request.%20I%20am%20sorry%20to%20inform%20you%20that%20the%20items%20you%20requested%20are%20back-ordered.%20We%20apologize%20for%20the%20inconvenience.%20We%20don%27t%20know%20when%20those%20items%20will%20become%20available.%20Maybe%20next%20week%20but%20we%20are%20not%20sure%20at%20this%20time.%20Because%20we%20want%20you%20to%20be%20a%20happy%20customer%2C%20management%20has%20decided%20to%20give%20you%20a%2050%25%20discount!&X-WDC-PL-OPT-OUT=1";
$username = "6678c2a8-0261-44f8-a9d3-9a2fb43d65b6";
echo $source;
$password = "40byApeYUV7f";
curl_setopt($ch, CURLOPT_URL, $source);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);
$destination = dirname(__FILE__)."/test.wav";
$file = fopen($destination, "w+");
curl_setopt($ch, CURLOPT_FILE, $file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

curl_exec($ch);

curl_close($ch);
fclose($file);
?>