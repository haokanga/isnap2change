<?php
    session_start();
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    $pageName = "mcq-editor";
    $columnName = array('QuizID','Week','TopicName','Points','Questionnaires','Questions');   
    $mcqQuesColName = array('MCQID','Question','Option', 'Explanation','Edit');
    
    try { 	    
        $conn = db_connect();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['metadataupdate'])){                          
                $metadataupdate = $_POST['metadataupdate'];
                //update
                if($metadataupdate == 0){
                    try{
                        $quizID = $_POST['quizid'];
                        $week = $_POST['week'];
                        $topicName = $_POST['topicname'];
                        $points = $_POST['points'];
                        $questionnaires = $_POST['questionnaires'];
                        $conn->beginTransaction();              
                        
                        $topicResult = getTopicByName($conn, $topicName);
                        updateQuiz($conn, $quizID, $topicResult->TopicID, $week);
                        updateMCQSection($conn, $quizID, $points, $questionnaires);
                        
                        $conn->commit();                    
                    } catch(PDOException $e) {
                        debug_pdo_err($pageName, $e);
                        $conn->rollback();
                    } 
                }
                else if($metadataupdate == 1){  
                    $quizID = $_POST['quizid'];
                    deleteQuiz($conn, $quizID);
                }            
            }
        }
    } catch(PDOException $e) {
        debug_pdo_err($pageName, $e);
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
    
    $updateSql = "REPLACE INTO Learning_Material(MaterialID,Content,QuizID)
                 VALUES (?,?,?);";			
    $updateSql = $conn->prepare($updateSql);                            
    if(! $updateSql->execute(array($materialid, $content, $quizid))){
        echo "<script language=\"javascript\">  alert(\"Error occurred to submit learning material. Report this bug to reseachers.\"); </script>";
    } else{            
        echo "<script language=\"javascript\">  console.log(\"Learning Material Submitted. materialid: $materialid  quizid: $quizid\"); </script>";
    }  
    */    
    
    try{
        if(isset($_GET['quizid'])){
            $quizID = $_GET['quizid'];
            $quizResult = getMCQQuiz($conn, $quizID);
            $topicResult = getTopics($conn);
            $materialRes = getLearningMaterial($conn, $quizID);
            $mcqQuesResult = getOptionsByQuiz($conn, $quizID);
        }
    } catch(PDOException $e) {
        debug_pdo_err($pageName, $e);
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
    .modal-xl {
        width: 90%;
       max-width:1200px;
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
                    
                    <!-- MetaData -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Quiz MetaData
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form id="metadatasubmission" method="post" action="<?php echo 'mcq-editor.php?quizid='.$quizID; ?>">
                                <!--if 0 update; else if -1 delete;-->
                                <input type=hidden name="metadataupdate" id="metadataupdate" value="1" required></input>
                                <label for="QuizID" style="display:none">QuizID</label>
                                <input type="text" class="form-control" id="QuizID" name="quizid" style="display:none" value="<?php echo $quizResult->QuizID; ?>"></input>
                                <br>
                                <label for="Week">Week</label>
                                <input type="text" class="form-control" id="Week" name="week" placeholder="Input Week Number" value="<?php echo $quizResult->Week; ?>"></input> 
                                <br>  
                                <label for='TopicName'>TopicName</label>
                                <select class="form-control" id="TopicName" form="metadatasubmission" name="topicname" required>
                                  <?php for($j=0; $j<count($topicResult); $j++) {?>                  
                                    <option value='<?php echo $topicResult[$j]->TopicName ?>' <?php if($topicResult[$j]->TopicName==$quizResult->TopicName) echo 'selected' ?> ><?php echo $topicResult[$j]->TopicName ?></option>
                                  <?php } ?>
                                </select>
                                <br>
                                <label for="Points">Points</label>
                                <input type="text" class="form-control" id="Points" name="points" placeholder="Input Points" value="<?php echo $quizResult->Points; ?>" required></input>
                                <br>
                                <label for="Questionnaires">Questionnaires</label>
                                <input type="hidden" class="form-control" id="Questionnaires" name="questionnaires" value="0"></input>
                                <input type="checkbox" class="form-control" id="Questionnaires" name="questionnaires" value="1" <?php if($quizResult->Questionnaires!=0) echo 'checked';?>></input>
                                <label for="Questions">Questions</label>
                                <input type="text" class="form-control" id="Questions" name="questions" value="<?php echo $quizResult->Questions; ?>" disabled></input>
                                <br>
                            </form>
                            <!--edit metadata-->
                            <span class="glyphicon glyphicon-remove pull-right" id="metadataremove" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-floppy-saved pull-right" id="metadataedit" aria-hidden="true"></span>    
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
                                        <?php for($i=0; $i<count($mcqQuesResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td style="display:none"><?php echo $mcqQuesResult[$i]->$mcqQuesColName[0]; ?></td>
                                            <td><?php echo $mcqQuesResult[$i]->$mcqQuesColName[1] ?></td>
                                            <td class ="<?php if ($mcqQuesResult[$i]->Content == $mcqQuesResult[$i]->CorrectChoice) {echo 'bg-success';} else {echo 'bg-danger';} ?>">
                                                <?php echo $mcqQuesResult[$i]->Content; ?>
                                            <td><?php echo $mcqQuesResult[$i]->$mcqQuesColName[3] ?></td>
                                            <td>
                                                <span class="glyphicon glyphicon-remove pull-right " aria-hidden="true"></span>
                                                <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                <a href="mcq-option-editor.php?mcqid=<?php echo $mcqQuesResult[$i]->$mcqQuesColName[0]; ?>">
                                                <span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span></a>
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

    <!-- DataTables rowsGroup Plugin -->
    <script src="../bower_components/datatables-plugins/rowsgroup/dataTables.rowsGroup.js "></script>
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" />
    <link rel="stylesheet" href="https://code.jquery.com/qunit/qunit-1.18.0.css" />  
    
    <!-- Page-Level Scripts -->
    <script>
    //DO NOT put them in $(document).ready() since the table has multi pages
    /**
    $('.glyphicon-plus').on('click', function (){
        $('#dialogTitle').text("Add MCQ");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val('');
        }   
    }); 
    $('.glyphicon-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this quiz? If you remove one quiz. All the questions and submission of this quiz will also get deleted (not recoverable). It includes learning material, questions and options, their submissions and your grading/feedback, not only the quiz itself.')) {
            $('#update').val(-1);
            for(i=0;i<$('.dialoginput').length;i++){                
                $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
            }
            $('#submission').submit();
        }           
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate({
          rules: {
            week: {
              required: true,
              digits: true
            },
            points: {
              required: true,
              digits: true
            }
          }
        });   
        $('#submission').submit();
    });
    */
    
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
            responsive: true,
            //rows group for MCQID, Question and edit box
            rowsGroup: [1,4],
            "pageLength":100,
            "aoColumnDefs": [
              { "bSearchable": false, "aTargets": [ 0 ] }
            ]
        })
        $('#metadataedit').on('click', function (){
            $('#metadataupdate').val(0);
            $('#metadatasubmission').validate({
              rules: {
                week: {
                  required: true,
                  digits: true
                },
                points: {
                  required: true,
                  digits: true
                }
              }
            });   
            $('#metadatasubmission').submit();
        });        
    });    
    </script>    
</body>

</html>
