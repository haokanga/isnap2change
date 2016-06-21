<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

<script>
    speak('Dopamine (contracted from 3,4-dihydroxyphenethylamine) is an organic chemical of the catecholamine and phenethylamine families that plays several important roles in the brain and body. It is an amine synthesized by removing a carboxyl group from a molecule of its precursor chemical L-DOPA, which is synthesized in the brain and kidneys. Dopamine is also synthesized in plants and most multicellular animals.');
 
// say a message
function speak(text, callback) {
    var u = new SpeechSynthesisUtterance();
    u.text = text;
    u.lang = 'en-AU';
 
    u.onend = function () {
        if (callback) {
            callback();
        }
    };
 
    u.onerror = function (e) {
        if (callback) {
            callback(e);
        }
    };
 
    speechSynthesis.speak(u);
}
</script>
</body>
