<?php


$files = scandir('./img/meal');

for ($i = 3; $i < count($files); $i++) {
    $key = substr($files[$i], 10, strpos($files[$i], '.')-10);

    print('.fruit-item-'.$key.' .fruit-item-icon { <br>');
    print('&nbsp background-image: url("./img/meal/'.$files[$i].'") <br>');
    print('} <br>');
}


//print_r($files1);

?>

