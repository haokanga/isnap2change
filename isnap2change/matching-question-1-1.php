<!doctype html>
<meta charset='utf-8'>
<link rel="shortcut icon" href="favicon.ico">
<!--dragula plugin css-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link href='css/dragula.css' rel='stylesheet' type='text/css' />
<link href='css/example.css' rel='stylesheet' type='text/css' />

<script type="text/javascript" src="js/jquery-1.12.3.js"></script>
<title>1:1 matching</title>

<style>
.parent { display: -ms-flex; display: -webkit-flex; display: flex; }
.parent>div { flex:1; }
.choices { display: -ms-flex; display: -webkit-flex; display: flex; flex-direction:column; }
.choices>div { flex:1; text-align: center; vertical-align: middle; }
</style>

<div class='examples'>  
    <label>Match the diseases to the causes. You may have to do some research on other websites to find out the answers.</label>
    <div class='wrapper'>
        <div class="row parent">
            <div class='container choices'>
                <div class="choices">
                    <div>1 Kwashiorkor </div>
                    <div>2 Marasmus </div>
                    <div>3 Scurvy </div>
                    <div>4 Rickets </div>
                    <div>5 Beriberi</div>
                </div>
            </div> 
            <div id='sortable' class='container choices'>
                <div>A This condition is brought on by a lack of vitamin D</div>
                <div>B  A disease that occurs if your body doesn’t get enough proteins</div>
                <div>C  Occurs in young children who don’t get enough calories every day</div>
                <div>D  Caused by the deficiency of vitamin B1 (thiamine) </div>
                <div>E  Caused by a lack of vitamin C</div>
            </div>       
        </div>
  </div>
</div>
<!--dragula plugin js-->
<script src='js/dragula.js'></script>
<script src='js/example.min.js'></script>
