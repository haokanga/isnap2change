<?php
    session_start();
    require_once("../mysql-lib.php");
    require_once("../debug.php");
    require_once("researcher-validation.php");
    $pageName = "school";
    
    //if insert/update/remove school
    try{        
        $conn = db_connect();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['update'])){                          
                $update = $_POST['update'];
                // insert
                if($update == 1){                                     
                    $schoolName = $_POST['schoolname']; 
                    createSchool($conn, $schoolName);                
                }                
                // update 
                else if($update == 0){                    
                    $schoolID = $_POST['schoolid'];
                    $schoolName = $_POST['schoolname'];
                    updateSchool($conn, $schoolID, $schoolName);
                }
                // remove school (with help of DELETE CASCADE) 
                else if($update == -1){                                        
                    $schoolID = $_POST['schoolid'];
                    deleteSchool($conn, $schoolID);
                }            
            }
        }
        
    } catch(Exception $e) {
        debug_err($pageName, $e);
    }
    
    try{  
        $schoolResult = getSchools($conn);
        $classNumResult = getClassNum($conn);
    } catch(Exception $e) {
        debug_err($pageName, $e);
    } 
    
    db_close($conn);    
?>
<!DOCTYPE html>
<html lang="en">

<head> 
    <!-- Header Library -->
    <?php require_once('header-lib.php'); ?>
</head>

<body>

    <div id="wrapper">

        <?php require_once('navigation.php'); ?> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">School Overview</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            School Information Table <span class="glyphicon glyphicon-plus pull-right" data-toggle="modal" data-target="#dialog"></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th style="display:none">SchoolID</th>
                                            <th>SchoolName</th>
                                            <th>Classes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($schoolResult); $i++) {?>
                                        <tr class="<?php if($i % 2 == 0){echo "odd";} else {echo "even";} ?>">
                                            <td style="display:none"><?php echo $schoolResult[$i]->SchoolID ?></td>
                                            <td><a href="class.php?schoolid=<?php echo $schoolResult[$i]->SchoolID ?>"><?php echo $schoolResult[$i]->SchoolName ?></a></td>
                                            <td><?php $count=0; for($j=0; $j<count($classNumResult); $j++){ if ($classNumResult[$j]->SchoolID == $schoolResult[$i]->SchoolID) $count=$classNumResult[$j]->Count; } echo $count; ?><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span><span class="pull-right" aria-hidden="true">&nbsp;</span><span class="glyphicon glyphicon-edit pull-right" data-toggle="modal" data-target="#dialog" aria-hidden="true"></span></td>
                                        </tr>
                                    <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well row">
                                <h4>School Overview Notification</h4>
                                <div class="alert alert-info">
                                    <p>View schools by filtering or searching. You can create/update/delete any school.</p>
                                </div>
                                <div class="alert alert-danger">
                                    <p><strong>Reminder</strong> : If you remove one school. All the student data in this school will also get deleted (not recoverable).</p> It includes <strong>student information, their submissions of every task and your grading/feedback</strong>, not only the school itself.
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
              <h4 class="modal-title" id="dialogTitle">Edit School</h4>
            </div>
            <div class="modal-body">
            <form id="submission" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!--if 1, insert; else if 0 update; else if -1 delete;-->
                <input type=hidden name="update" id="update" value="1"></input>
                <label for="SchoolID" style="display:none">SchoolID</label>
                <input type="text" class="form-control dialoginput" id="SchoolID" name="schoolid" style="display:none">
                <br><label for="SchoolName">SchoolName</label>
                <input type="text" class="form-control dialoginput" id="SchoolName" name="schoolname" required>               
                <br><label for="Classes">Classes</label>
                <input type="text" class="form-control dialoginput" id="Classes" name="Classes">
                <br>
                <div class="alert alert-danger">
                    <p><strong>Reminder</strong> : School Name should be unique and no duplicate names are allowed.</p>
                </div>
            </form>
            </div>
            <div class="modal-footer">            
              <button type="button" id="btnSave" class="btn btn-default">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>          
        </div>
      </div>
    <!-- SB Admin Library -->  
    <?php require_once('sb-admin-lib.php'); ?>

    <!-- Page-Level Scripts -->
    <script>    
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true,
                "aoColumnDefs": [
                  { "bSearchable": false, "aTargets": [ 0 ] }
                ]
        });            
    }); 
    //DO NOT put them in $(document).ready() since the table has multi pages
    $('.glyphicon-edit').on('click', function (){
        $('#dialogTitle').text("Edit School");
        $('#update').val(0);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
        }
        //disable SchoolID and Classes
        $('.dialoginput').eq(0).attr('disabled','disabled');
        $('.dialoginput').eq(2).attr('disabled','disabled');            
    });
    $('.glyphicon-plus').on('click', function (){
        $('#dialogTitle').text("Add School");
        $('#update').val(1);
        for(i=0;i<$('.dialoginput').length;i++){                
            $('.dialoginput').eq(i).val('');
        }
        //disable SchoolID and Classes
        $('.dialoginput').eq(0).attr('disabled','disabled');
        $('.dialoginput').eq(2).attr('disabled','disabled');            
    }); 
    $('.glyphicon-remove').on('click', function (){
        if (confirm('[WARNING] Are you sure to remove this school? All the student data in this school will also get deleted (not recoverable). It includes student information, their submissions of every task and your grading/feedback, not only the school itself.')) {
            $('#update').val(-1);
            //fill required input
            $('.dialoginput').eq(0).prop('disabled',false);
            for(i=0;i<$('.dialoginput').length;i++){                
                $('.dialoginput').eq(i).val($(this).parent().parent().children('td').eq(i).text().trim());
            }
            $('#submission').submit();
        }           
    });
    $('#btnSave').on('click', function (){
        $('#submission').validate();        
        //enable SchoolID
        $('.dialoginput').eq(0).prop('disabled',false);
        $('#submission').submit();
    });
    
    </script>
</body>

</html>
