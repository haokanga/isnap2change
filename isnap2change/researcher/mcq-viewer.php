<?php
    session_start();
    require_once("../connection.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    require_once("/get-quiz-points.php");	    
    $conn = db_connect();
    $overviewName = "quiz";
    $columnName = array('QuizID','Week','TopicName','Points', 'Questions');     
    //edit/delete quiz
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['update'])){                          
            $update = $_POST['update'];
            //update
            if($update == 0){
                
            }
            else if($update == 1){  
                $quizID = $_POST['quizid'];
                $update_stmt = "DELETE FROM Quiz WHERE QuizID = ?";			
                $update_stmt = $conn->prepare($update_stmt);
                if(! $update_stmt -> execute(array($quizID))){
                    echo "<script language=\"javascript\">  alert(\"Error occurred to delete quiz. Contact with developers.\"); </script>";
                } else{
                } 
            }            
        }
    }
    
    /**
    //learning-material-editor
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
    if(! $update_stmt -> execute(array($materialid, $content, $quizid))){
        echo "<script language=\"javascript\">  alert(\"Error occurred to submit learning material. Report this bug to reseachers.\"); </script>";
    } else{            
        echo "<script language=\"javascript\">  console.log(\"Learning Material Submitted. materialid: $materialid  quizid: $quizid\"); </script>";
    }  
    */
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET['quizid'])){
        $quizID = $_GET['quizid'];    
        // get quiz and topic
        $quizSql = "SELECT QuizID, Week, TopicName, COUNT(*) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section NATURAL JOIN MCQ_Question WHERE QuizID = ?";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($quizID));
        $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ); 
        }
        //get topic
        $topicSql = "SELECT TopicID, TopicName FROM Topic ORDER BY TopicID";
        $topicQuery = $conn->prepare($topicSql);
        $topicQuery->execute(array());
        $topicResult = $topicQuery->fetchAll(PDO::FETCH_OBJ); 
        
        $materialPreSql = "SELECT COUNT(*) 
					   FROM   Learning_Material
					   WHERE  QuizID = ?";							
        $materialPreQuery = $conn->prepare($materialPreSql);
        $materialPreQuery->execute(array($quizID));                
        if($materialPreQuery->fetchColumn() != 1){
                    
        }                
        $materialSql = "SELECT Content, TopicName 
                        FROM   Learning_Material NATURAL JOIN Quiz
                                                 NATURAL JOIN Topic
                        WHERE  QuizID = ?";
                                
        $materialQuery = $conn->prepare($materialSql);
        $materialQuery->execute(array($quizID));
        $materialRes = $materialQuery->fetch(PDO::FETCH_OBJ);

        //get questions and options
        $mcqSql = "SELECT MCQID, Question, CorrectChoice, Content
				   FROM   MCQ_Section NATURAL JOIN MCQ_Question
								  NATURAL JOIN `Option`
			       WHERE  QuizID = ?
			       ORDER BY MCQID";
								
		$mcqQuery = $conn->prepare($mcqSql);
		$mcqQuery->execute(array($quizID));
        $mcqResult = $mcqQuery->fetchAll(PDO::FETCH_OBJ); 


        //get max option num        
        $optionNumSql = "SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM MCQ_Question natural JOIN `Option` WHERE QuizID = ? GROUP BY MCQID) AS OptionNumbTable;";								
		$optionNumQuery = $conn->prepare($optionNumSql);
		$optionNumQuery->execute(array($quizID));
        $optionNumResult = $optionNumQuery->fetch(PDO::FETCH_OBJ);
        /**
        $mcqQuesColName[] = 'QuizID';
        $mcqQuesColName[] = 'Question';
        for($i=0; $i<$optionNumResult->MaxOptionNum; $i++) {
            $mcqQuesColName[] = 'Option'.($i+1);
        }
        */
        $mcqQuesColName = array('QuizID','Question','Option');
	}   
    db_close($conn); 
    
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>iSNAP2Change Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <!-- <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet"> -->

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--w3data.js to include html-->
    <script src="../js/w3data.js"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
      /**
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
     */
    </script>
    <style>
    .glyphicon:hover {
        background-color: rgb(153, 153, 102);
    }
    </style> 
</head>

<body>

    <div id="wrapper">

        <div w3-include-html="navigation.html"></div> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Multiple Choice Quiz Editor</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
                    <!--MetaData-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quiz MetaData
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <!--if 0 update; else if -1 delete;-->
                                <input type=hidden name="update" id="update" value="1"></input>                
                            <?php for($i=0; $i<count($columnName); $i++) {
                                    if($columnName[$i]!='TopicName'){ ?>
                                    <label for='<?php echo $columnName[$i]; ?>' <?php if ($i==0){ echo 'style="display:none"';} ?>><?php echo $columnName[$i]; ?></label>    
                                    <input type="text" class="form-control dialoginput" id="<?php echo $columnName[$i]; ?>" name="<?php echo strtolower($columnName[$i]); ?>"  
                                    <?php if ($i==0){ echo 'style="display:none"';} ?> value="<?php if($i!=3) {echo $quizResult->$columnName[$i];} else  {echo getQuizPoints($quizResult->QuizID);} ?>" required></input>
                                <?php }
                                else {?>
                                    <label for='<?php echo $columnName[$i]; ?>' <?php if ($i==0){ echo 'style="display:none"';} ?>><?php echo $columnName[$i]; ?></label>
                                    <select class="form-control dialoginput" id="<?php echo $columnName[$i]; ?>" form="submission" name="<?php echo strtolower($columnName[$i]);?>" required>
                                      <?php for($j=0; $j<count($topicResult); $j++) {?>                  
                                        <option value='<?php echo $topicResult[$j]->TopicName ?>' <?php if($topicResult[$j]->TopicName == $quizResult->$columnName[$i])echo 'selected' ?>><?php echo $topicResult[$j]->TopicName ?></option>
                                      <?php } ?>
                                    </select>                                     
                                <?php } 
                            }?>
                                <br>
                            </form>
                            <span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span>    
                        </div>                            
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    
                    <!--Learning Material-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Learning Material 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="heading" style="color: black; max-height:10vh; text-align:center; border-bottom: 1px solid #eee;">
                                <h1 style='padding: 0px;'> 
								<i>	<?php echo $materialRes->TopicName; ?> </i>                          
                                </h1> 
                            </div>
                   
                            <div class="para" style="padding-left:15px; padding-right:15px; padding-top:8px; text-align:center;">
                                <div style="color:black; justify-content:center; align-items:center;">
                                <i>
                                  <?php echo $materialRes->Content; ?></i>
                                </div>
                            </div>                        
                        
                            <span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><a href="learning-material-editor.php?quizid=<?php echo $quizResult->QuizID ?>"><span class="glyphicon glyphicon-edit pull-right" aria-hidden="true"></span></a>
                            <!-- data-toggle="modal" data-target="#dialog" -->                            
                        </div>                            
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- Options -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Questions and Options 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="datatables">
                                    <thead>
                                        <tr>
                                        <?php for($i=0; $i<count($mcqQuesColName); $i++) {
                                            if ($i==0){?>
                                            <th style="display:none"><?php echo $mcqQuesColName[$i]; ?></th>
                                            <?php } else {?>                                            
                                            <th><?php echo $mcqQuesColName[$i]; ?></th>
                                        <?php }
                                        }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i=0; $i<count($mcqResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td style="display:none"><?php echo $mcqResult[$i]->QuizID; ?></td>
                                            <td><?php echo $mcqResult[$i]->Question ?></td>
                                            <td class ="<?php if ($mcqResult[$i]->Content == $mcqResult[$i]->CorrectChoice) {echo 'bg-success';} else {echo 'bg-danger';} ?>">
                                                <?php echo $mcqResult[$i]->Content; ?>             
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Multiple Choice Quiz Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>View quizzes by filtering or searching. You can create/update/delete any class.</p>
                                </div>
                                <div class="alert alert-danger">
                                    <p><strong>Warning</strong> : If you remove one quiz. All the <strong>questions and submission</strong> of this quiz will also get deleted (not recoverable).</p> It includes <strong>learning material, questions and options, their submissions and your grading/feedback</strong>, not only the quiz itself.
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
                    
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <!-- Modal -->
      <div class="modal fade" id="dialog" role="dialog">
        <div class="modal-dialog">        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="dialogTitle">Edit Class</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!--if 1, insert; else if 0 update; else if -1 delete;-->
                <input type=hidden name="update" id="update" value="1"></input>
                <label for="ClassID" style="display:none">ClassID</label>
                <input type="text" class="form-control dialoginput" id="ClassID" name="classid" style="display:none">
                <br><label for="ClassName">ClassName</label>
                <input type="text" class="form-control dialoginput" id="ClassName" name="classname" required>
                <br><label for="SchoolName">SchoolName</label>
                <select class="form-control dialoginput" id="SchoolName" form="submission" name="schoolname" required>
                  <?php for($i=0; $i<count($quizResult); $i++) {?>                  
                  <option value="<?php echo $quizResult->SchoolName ?>"><?php echo $quizResult->SchoolName ?></option>
                  <?php } ?>
                </select>                
                <br><label for="TeacherToken">TeacherToken</label><span class="glyphicon glyphicon-random pull-right"></span>
                <input type="text" class="form-control dialoginput" id="TeacherToken" name="teachertoken" required></input>
                <br><label for="QuizToken">QuizToken</label><span class="glyphicon glyphicon-random pull-right"></span>
                <input type="text" class="form-control dialoginput" id="QuizToken" name="quiztoken" required></input>
                <br><label for="EnrolledQuizs">EnrolledQuizs</label>
                <input type="text" class="form-control dialoginput" id="EnrolledQuizs" name="EnrolledQuizs">
                <br><label for="UnlockedProgress">UnlockedProgress</label>
                <input type="text" class="form-control dialoginput" id="UnlockedProgress" name="UnlockedProgress">
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btnSave" class="btn btn-default">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>
      <input type=hidden name="keyword" id="keyword" value="<?php if(isset($_GET['week'])){ echo $_GET['week']; } ?>"></input>
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>    
    
    <!--jQuery Validate plugin-->
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>
    function randomString(length) {
        return Math.round((Math.pow(36, length + 1) - Math.random() * Math.pow(36, length))).toString(36).slice(1);
    }
    //DO NOT put them in $(document).ready() since the table has multi pages
    $('.glyphicon-edit').on('click', function (){
        $('#dialogTitle').text("Edit Class");
        $('#update').val(0);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text());
        }
        //disable ClassID, EnrolledQuizs, UnlockedProgress
        $('.dialoginput').eq(0).attr('disabled','disabled');
        $('.dialoginput').eq(5).attr('disabled','disabled');
        $('.dialoginput').eq(6).attr('disabled','disabled');          
    });
    $('.glyphicon-plus').on('click', function (){
        $('#dialogTitle').text("Add Class");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val('');
        }
        //disable ClassID, EnrolledQuizs, UnlockedProgress
        $('.dialoginput').eq(0).attr('disabled','disabled');
        $('.dialoginput').eq(5).attr('disabled','disabled');    
        $('.dialoginput').eq(6).attr('disabled','disabled');         
    }); 
    $('.glyphicon-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this class? All the quiz data in this class will also get deleted (not recoverable). It includes quiz information, their submissions of every task and your grading/feedback, not only the class itself.')) {
            $('#update').val(-1);
            //fill required input
            $('.dialoginput').eq(0).prop('disabled',false);
            for(i=0;i<$('.dialoginput').length;i++){                
                $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text());
            }
            $('#submission').submit();
        }           
    });
     $('.glyphicon-random').on('click', function (){
        var index = $(this).index();
        if (index == $("#TeacherToken").index() - 1)
            $('#TeacherToken').val(randomString(16)); 
        else if (index == $("#QuizToken").index() - 1)
            $('#QuizToken').val(randomString(16));
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate();        
        //enable ClassID and EnrolledQuizs
        $('.dialoginput').eq(0).prop('disabled',false);
        $('#submission').submit();
    });
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
                responsive: true,
                "initComplete": function(settings, json) {
                    
                    $('.input-sm').eq(1).val($("#keyword").val().trim());                    
                }
        })
        //search keyword (schoolname), exact match
        table.search(
            $("#keyword").val().trim(), true, false, true
        ).draw();
        //TODO: layout of options
        //$(".form-control.input-sm").eq(0).val(100);
    });        
    </script>
</body>

</html>
