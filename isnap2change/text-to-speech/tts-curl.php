<?php
function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

$ch = curl_init();
$text = "Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below.";
$encodedText = encodeURIComponent($text);

$source = "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?accept=audio/wav&text=$encodedText&voice=en-US_AllisonVoice";

//$source = "https://text-to-speech-demo.mybluemix.net/api/synthesize?voice=en-US_AllisonVoice&text=I%20have%20been%20assigned%20to%20handle%20your%20order%20status%20request.%20I%20am%20sorry%20to%20inform%20you%20that%20the%20items%20you%20requested%20are%20back-ordered.%20We%20apologize%20for%20the%20inconvenience.%20We%20don%27t%20know%20when%20those%20items%20will%20become%20available.%20Maybe%20next%20week%20but%20we%20are%20not%20sure%20at%20this%20time.%20Because%20we%20want%20you%20to%20be%20a%20happy%20customer%2C%20management%20has%20decided%20to%20give%20you%20a%2050%25%20discount!&X-WDC-PL-OPT-OUT=1";
$username = "6678c2a8-0261-44f8-a9d3-9a2fb43d65b6";
echo $source;
$password = "40byApeYUV7f";
curl_setopt($ch, CURLOPT_URL, $source);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);
$destination = dirname(__FILE__)."/test.wav";
$file = fopen($destination, "w+");
curl_setopt($ch, CURLOPT_FILE, $file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

curl_exec($ch);

curl_close($ch);
fclose($file);
?>
<video controls autoplay><source src="https://text-to-speech-demo.mybluemix.net/api/synthesize?voice=en-US_AllisonVoice&amp;text=Eating%20a%20balanced%20diet%20is%20vital%20for%20your%20health%20and%20wellbeing.%20The%20food%20we%20eat%20is%20responsible%20for%20providing%20us%20with%20the%20energy%20to%20do%20all%20the%20tasks%20of%20daily%20life.%20For%20optimum%20performance%20and%20growth%20a%20balance%20of%20protein%2C%20essential%20fats%2C%20vitamins%20and%20minerals%20are%20required.%20We%20need%20a%20wide%20variety%20of%20different%20foods%20to%20provide%20the%20right%20amounts%20of%20nutrients%20for%20good%20health.%20The%20different%20types%20of%20food%20and%20how%20much%20of%20it%20you%20should%20be%20aiming%20to%20eat%20is%20demonstrated%20on%20the%20pyramid%20below&amp;X-WDC-PL-OPT-OUT=1" type="audio/ogg"></video>
