<?php
    session_start();
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    require_once("/researcher-validation.php");
    $pageName = "mcq-option-editor";
    $columnName = array('OptionID','Content','Explanation','Edit');
    
    try{
        $conn = db_connect();        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['metadataupdate'])){                          
                $metadataupdate = $_POST['metadataupdate'];
                if($metadataupdate == 0){
                    $mcqID = $_POST['mcqid'];
                    $question = $_POST['question'];
                    updateMCQQuestion($conn, $mcqID, $question);
                }
                else if($metadataupdate == -1){
                    $mcqID = $_POST['mcqid'];                    
                    deleteMCQQuestion($conn, $mcqID); 
                }             
            }            
            if(isset($_POST['update'])){                          
                $update = $_POST['update'];
                if($update == 1){
                    $mcqID = $_POST['mcqid'];
                    $content = $_POST['content'];
                    $explanation = $_POST['explanation'];
                    createOption($conn, $mcqID, $content, $explanation);
                }
                else if($update == 0){
                    $optionID = $_POST['optionid'];
                    $content = $_POST['content'];
                    $explanation = $_POST['explanation'];
                    updateOption($conn, $optionID, $content, $explanation);
                }
                else if($update == -1){  
                    $optionID = $_POST['optionid'];
                    deleteOption($conn, $optionID); 
                }             
            }
        }      
    } catch(PDOException $e) {
        debug_pdo_err($pageName, $e);
    }
    
    try{
        if(isset($_GET['mcqid'])){
            $mcqID = $_GET['mcqid'];
            $mcqQuesResult = getMCQQuestion($conn, $mcqID); 
            $optionResult = getOptions($conn, $mcqID);  
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
                    <h1 class="page-header">Multiple Choice Question Editor</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- MetaData -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Multiple Choice Question MetaData
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form id="metadatasubmission" method="post" action="<?php echo $pageName.'.php?mcqid='.$mcqID; ?>">
                                <!--if 0 update; else if -1 delete;-->
                                <input type=hidden name="metadataupdate" id="metadataupdate" value="1" required></input>
                                <label for="MCQID" style="display:none">MCQID</label>
                                <input type="text" class="form-control" id="MCQID" name="mcqid" style="display:none" value="<?php echo $mcqQuesResult->MCQID; ?>" required></input>
                                <br>
                                <label for="Question">Question</label>
                                <input type="text" class="form-control" id="Question" name="question" value="<?php echo $mcqQuesResult->Question; ?>" required></input>
                                <br>
                            </form>
                            <!--edit metadata-->
                            <span class="glyphicon glyphicon-remove pull-right" id="metadataremove" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-floppy-saved pull-right" id="metadataedit" aria-hidden="true"></span>    
                        </div>                            
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                
                
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Option Information Table <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal" data-target="#dialog"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="datatables">
                                    <thead>
                                        <tr>
                                        <?php for($i=0; $i<count($columnName); $i++){ ?>
                                            <th <?php if ($i==0){ echo 'style="display:none"';} ?>><?php echo $columnName[$i]; ?></th>
                                        <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($optionResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <?php for($j=0; $j<count($columnName); $j++){ ?>
                                                <td <?php if ($j==0){ echo 'style="display:none"';} ?>>
                                                    <?php 
                                                    if($j!=count($columnName)-1) 
                                                        echo $optionResult[$i]->$columnName[$j]; 
                                                    else { ?>
                                                        <span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span>
                                                        <span class="pull-right" aria-hidden="true">&nbsp;</span>
                                                        <span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>                                            
                                        </tr>
                                    <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>Multiple Choice Question Editor Notification</h4>
                                <div class="alert alert-info">
                                    <p>You can create/update/delete any options of this multiple choice question or the question itself.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
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
              <h4 class="modal-title" id="dialogTitle">Edit Option</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $pageName.'.php?mcqid='.$mcqID; ?>">
                <!--if 1, insert; else if -1 delete;-->
                <input type=hidden name="update" id="update" value="1" required></input>
                <label for="OptionID" style="display:none">OptionID</label>                
                <input type="text" class="form-control dialoginput" id="OptionID" name="optionid" style="display:none"></input>
                <label for="Content">Content</label>
                <input type="text" class="form-control dialoginput" id="Content" name="content"  placeholder="Input Content" required></input> 
                <br>  
                <label for="Explanation">Explanation</label>
                <input type="text" class="form-control dialoginput" id="Explanation" name="explanation"  placeholder="Input Explanation" required></input> 
                <br>
                <label for="MCQID" style="display:none">MCQID</label>
                <input type="text" class="form-control dialoginput" id="MCQID" name="mcqid" style="display:none" value="<?php echo $mcqQuesResult->MCQID; ?>" required></input>
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btnSave" class="btn btn-default">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>
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
    //DO NOT put them in $(document).ready() since the table has multi pages 
    $('.glyphicon-plus').on('click', function (){
        $('#dialogTitle').text("Add Option");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length-1;i++){                
            $('.dialoginput').eq(i).val('');
        }   
    });     
    $('td > .glyphicon-edit').on('click', function (){
        $('#dialogTitle').text("Edit Option");
        $('#update').val(0);
        for(i=0;i<$('.dialoginput').length-1;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }   
    });     
    $('td > .glyphicon-remove').on('click', function (){        
        $('#update').val(-1);
        for(i=0;i<$('.dialoginput').length-1;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        $('#submission').submit();                   
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate();
        for(i=0;i<$('.dialoginput').length;i++){                
            console.log($('.dialoginput').eq(i).val());
        }        
        $('#submission').submit();
    });
    
    //include html
    w3IncludeHTML();   
    $(document).ready(function() {
        var table = $('#datatables').DataTable({
                responsive: true,
                "order": [[ 1, "asc" ]],
                "pageLength":10,
                "aoColumnDefs": [
                  { "bSearchable": false, "aTargets": [ 0 ] }
                ]
        })
        $('#metadataedit').on('click', function (){
            $('#metadataupdate').val(0);
            $('#metadatasubmission').validate();   
            $('#metadatasubmission').submit();
        }); 
        $('#metadataremove').on('click', function (){
            $('#metadataupdate').val(-1);   
            $('#metadatasubmission').submit();
        });
    });        
    </script>
</body>

</html>
