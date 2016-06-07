<?php
	session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
	if(isset($_POST['richcontenttextarea'])){
        $conn = db_connect();
        $content = $_POST['richcontenttextarea'];
        $materialid = 1;   
        $quizid = 1;
        echo "<h2>Preview</h2>";       
		echo $content;
        
        $update_stmt = "REPLACE INTO Learning_Material(MaterialID,Content,QuizID)
                     VALUES (?,?,?);";			
        $update_stmt = $conn->prepare($update_stmt);                            
        if(! $update_stmt->execute(array($materialid, $content, $quizid))){
            echo "<script language=\"javascript\">  alert(\"Error occurred to submit learning material. Report this bug to reseachers.\"); </script>";
        } else{            
            echo "<script language=\"javascript\">  console.log(\"Learning Material Submitted. materialid: $materialid  quizid: $quizid\"); </script>";
        }        
	}	
?>

<!DOCTYPE html>
<html>
<head>
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
  tinymce.init({
  selector: 'textarea',
  height: 500,
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools'
  ],
  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  toolbar2: 'print preview media | forecolor backcolor emoticons',
  image_advtab: true,
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
 });
 </script>
</head>
<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <textarea name="richcontenttextarea">
  
  <!-- TinyMCE demo 
  
  <p style="text-align: center; font-size: 15px;"><img title="TinyMCE Logo" src="//www.tinymce.com/images/glyph-tinymce@2x.png" alt="TinyMCE Logo" width="110" height="97" />
  </p>
  <h1 style="text-align: center;">Welcome to the TinyMCE editor demo!</h1>
  <p>Please try out the features provided in this full featured example.</p>
  <p>Note that any <b>MoxieManager</b> file and image management functionality in this example is part of our commercial offering ¨C the demo is to show the integration.</h2>

  <h2>Got questions or need help?</h2>
  <ul>
    <li>Our <a href="//www.tinymce.com/docs/">documentation</a> is a great resource for learning how to configure TinyMCE.</li>
    <li>Have a specific question? Visit the <a href="http://community.tinymce.com/forum/">Community Forum</a>.</li>
    <li>We also offer enterprise grade support as part of <a href="http://tinymce.com/pricing">TinyMCE Enterprise</a>.</li>
  </ul>

  <h2>A simple table to play with</h2>
  <table style="text-align: center;">
    <thead>
      <tr>
        <th>Product</th>
        <th>Cost</th>
        <th>Really?</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>TinyMCE</td>
        <td>Free</td>
        <td>YES!</td>
      </tr>
      <tr>
        <td>Plupload</td>
        <td>Free</td>
        <td>YES!</td>
      </tr>
    </tbody>
  </table>

  <h2>Found a bug?</h2>
  <p>If you think you have found a bug please create an issue on the <a href="https://github.com/tinymce/tinymce/issues">GitHub repo</a> to report it to the developers.</p>

  <h2>Finally ...</h2>
  <p>Don't forget to check out our other product <a href="http://www.plupload.com" target="_blank">Plupload</a>, your ultimate upload solution featuring HTML5 upload support.</p>
  <p>Thanks for supporting TinyMCE! We hope it helps you and your users create great content.<br>All the best from the TinyMCE team.</p>
  -->
  <p>Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below. (my own words)</p>
<p><img style="display: block; margin-left: auto; margin-right: auto;" src="https://cmudream.files.wordpress.com/2016/05/0.jpg" alt="" width="632" height="884" /></p>
<p>There are three main layers of the food pyramid. The bottom layer is the most important one for your daily intake of food. It contains vegetables, fruits, grains and legumes. You should be having most of your daily food from this layer. These foods are all derived or grow on plants and contain important nutrients such as vitamins, minerals and antioxidants. They are also responsible for being the main contributor of carbohydrates and fibre to our diet.<br />The middle layer is comprised of dairy based products such as milk, yoghurt, cheese. These are essential to providing our bodies with calcium and protein and important vitamins and minerals.<br />They layer also contains lean meat, poultry, fish, eggs, nuts, seeds, legumes. These foods are our main source of protein and are also responsible for providing other nutrients to us including iodine, iron, zinc, B12 vitamins and healthy fats.<br />The top layer, which is the smallest layer, is the layer you should me eating the least off. This layer is made up of food which has unsaturated fats such as sugar, butter, margarine and oils; small amounts of these unsaturated fats are needed for healthy brain and hear function.<br />(my own words)<br />Source: The Healthy Living Pyramid. Nutrition Australia. [Accessed 28/04/2016 http://www.nutritionaustralia.org/national/resource/healthy-living-pyramid]</p>
</textarea>
<input type="submit" name='submit' value="Submit" class='submit'/><br />
</form>
</body>
</html>