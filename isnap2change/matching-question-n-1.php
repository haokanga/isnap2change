<!doctype html>
<meta charset='utf-8'>
<link rel="shortcut icon" href="favicon.ico">
<link href='css/dragula.css' rel='stylesheet' type='text/css' />
<link href='css/example.css' rel='stylesheet' type='text/css' />
<title>dragula</title>

<div class='examples'>
  <div class='parent'>
    <label for='hy'>Move stuff between these two containers. Note how the stuff gets inserted near the mouse pointer? Great stuff.</label>    
    <div class='wrapper'>
      <!--Multiple Buckets-->      
      <div id='bucket-defaults0' class='container'>
        <div>There's also the possibility of moving elements around in the same container, changing their position</div>
        <div>This is the default use case. You only need to specify the containers you want to use</div>
        <div>More interactive use cases lie ahead</div>
        <div>Moving <code>&lt;input/&gt;</code> elements works just fine. You can still focus them, too. <input placeholder='See?' /></div>
        <div>Make sure to check out the <a href='https://github.com/bevacqua/dragula#readme'>documentation on GitHub!</a></div>
      </div>
      <div id='bucket-defaults1' class='container'>
        <div>There's also the possibility of moving elements around in the same container, changing their position</div>
        <div>This is the default use case. You only need to specify the containers you want to use</div>
        <div>More interactive use cases lie ahead</div>
        <div>Moving <code>&lt;input/&gt;</code> elements works just fine. You can still focus them, too. <input placeholder='See?' /></div>
        <div>Make sure to check out the <a href='https://github.com/bevacqua/dragula#readme'>documentation on GitHub!</a></div>
      </div>
      <div id='bucket-defaults2' class='container'>
        <div>There's also the possibility of moving elements around in the same container, changing their position</div>
        <div>This is the default use case. You only need to specify the containers you want to use</div>
        <div>More interactive use cases lie ahead</div>
        <div>Moving <code>&lt;input/&gt;</code> elements works just fine. You can still focus them, too. <input placeholder='See?' /></div>
        <div>Make sure to check out the <a href='https://github.com/bevacqua/dragula#readme'>documentation on GitHub!</a></div>
      </div>      
    </div>
    <div id='option-defaults' class='container'>
        <div>You can move these elements between these two containers</div>
        <div>Moving them anywhere else isn't quite possible</div>
        <div>Anything can be moved around. That includes images, <a href='https://github.com/bevacqua/dragula'>links</a>, or any other nested elements.
        <div class='image-thing'></div><sub>(You can still click on links, as usual!)</sub>
        </div>
    </div>
  </div>
 
</div>

<script src='js/dragula.js'></script>
<script src='js/example.min.js'></script>
