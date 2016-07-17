<?php

	session_start();

	//check login status
	require_once('student-validation.php');

	require_once("../mysql-lib.php");
	require_once("../debug.php");
	
	$pageName = "poster-editor";
	
	//check whether a request is GET or POST 
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["quizID"]) && isset($_POST["week"])){
			$quizID = $_POST["quizID"];
			$week = $_POST["week"];
		} else{
			
		}
	} else{
		
	}
	
	$conn = null;
	
	try{
		$conn = db_connect();
        //check quiz status	
		$status = getQuizStatus($conn, $quizID, $studentID);
		//if quiz is answered, saved poster will be read from database.
		if($status != "UNANSWERED"){
			$posterRes = getPosterRecord($conn, $quizID, $studentID);	
		}	
	}catch(Exception $e){
		if($conn != null){
			db_close($conn);
		}
		
		debug_err($pageName, $e);
		//to do: handle sql error
		//...
		exit;
	}
	
	db_close($conn);
?>

<html>
<body>
	<div id=progress></div>
    <div id="zwibbler" style="margin-left:auto;margin-right:auto;width:800px;height:800px;"></div>
    <input id="saveBtn"type="button" onclick="submitQuiz(<?php echo $quizID; ?>, <?php echo $studentID; ?>, 'SAVE')" value="SAVE"/>
    <input id="submitBtn" type="button" onclick="submitQuiz(<?php echo $quizID; ?>, <?php echo $studentID; ?>, 'SUBMIT')"  value="SUBMIT"/>
	<form id="goBack" method=post action=weekly-task.php>
		<button type="button" onclick="goBack()">GO BACK</button> 
		<input type=hidden name="week" value=<?php echo $week; ?>>
	</form>
	<form style="display:none" method=post enctype="multipart/form-data" action="upload-handler.php">
		<input type=file name=file id=fileinput accept="image/*">
		<input type=hidden name="studentID" value=<?php echo $studentID;?>>
    </form>
	
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://zwibbler.com/zwibbler2.js"></script>
    <script type="text/javascript">
		
	  if(localStorage.getItem("zwibbler-document") != null) {
			localStorage.removeItem("zwibbler-document");  
	  }	
	
	  Zwibbler.addButton({
			name: "imageInsertion",
            image: "http://zwibbler.com/wd-image.png",

			onclick: function(ctx) {
				 $("#fileinput").click();
			}
		});
		
		var zwibbler = Zwibbler.create("zwibbler", {
            showPropertyPanel: true
        });
		
		<?php
				//if quiz is answered, saved poster will be loaded to canvas.
				if($status != "UNANSWERED") { ?>
					var saved = "zwibbler3.";
					saved = saved + '<?php echo $posterRes->ZwibblerDoc ?>';
					zwibbler.load("zwibbler3", saved);
		<?php	} ?>

		<?php
				//if quiz is submitted, canvas and submit button will be disabled.
				if($status == "UNGRADED" || $status == "GRADED") { ?>
					zwibbler.setConfig("readOnly", true);
					$("#saveBtn").attr("disabled","disabled");
					$("#submitBtn").attr("disabled","disabled");
		<?php	} ?>

		function parseFeedback(response){
			var feedback = JSON.parse(response);
			
			if(feedback.message != "success"){
				alert(feedback.message + ". Please try again!");
				return;
			}

			if(feedback.action == "SAVE"){
				alert("Saved Successfully!");
			}

			if(feedback.action == "SUBMIT") {
				alert("Submitted Successfully!");
				zwibbler.setConfig("readOnly", true);
				$("#saveBtn").attr("disabled", "disabled");
				$("#submitBtn").attr("disabled", "disabled");
			}
		}

        function submitQuiz(quizID, studentID, action){
            var zwibblerDoc = zwibbler.save("zwibbler3");

			var postData;

			if(action == "SAVE"){
				postData = "quizID="+quizID+"&studentID="+studentID+"&action="+action+"&zwibblerDoc="+zwibblerDoc.substr(10);
			}

			if(action == "SUBMIT"){
				var dataUrl = zwibbler.save("png");
				postData = "quizID="+quizID+"&studentID="+studentID+"&action="+action+"&zwibblerDoc="+zwibblerDoc.substr(10)+"&dataUrl="+dataUrl;
			}
			
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					parseFeedback(xmlhttp.responseText);
				} 
			};

			xmlhttp.open("POST", "poster-feedback.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send(postData);
        }
		
		function goBack(){
			document.getElementById("goBack").submit();
		}
		
		$("#fileinput").on("change", function(e){
            var form = this.parentNode;
            upload(form);
			form.reset();
        });
		
		function uploadDone(status, result) {
            if (status === "ok") {
				if(result.message == "success"){
					var url = "http://localhost/isnap2change/isnap2change/tmp_poster_img/" + result.fileid;

					zwibbler.beginTransaction();
					var nodeId = zwibbler.createNode("ImageNode", {
						url: url
					});
					zwibbler.translateNode(nodeId, 100, 100);
					zwibbler.commitTransaction();
				} else{
					alert(result.message);
				}

            }
        }
		
		function upload(form) {
            var progress = new ProgressNotification("Reading file");
            var xhr = new XMLHttpRequest();
			
            var fd = new FormData(form);

            xhr.upload.addEventListener("progress", 
                function( e ) {
                    progress.update( e.loaded / e.total );
                }, false
            );

            xhr.addEventListener("load", 
                function( e ) {
                    progress.done();
                    uploadDone("ok", $.parseJSON(xhr.response));
                }, false
            );

            xhr.addEventListener("error", 
                function( e ) {
                    progress.error("Error");
                    uploadDone( "error", null );
                }, false
            );

            xhr.addEventListener("abort", 
                function( e ) {
                    progress.error("Aborted");
                    uploadDone( "aborted", null );
                }, false
            );

            xhr.open(form.method, form.action);
            xhr.send(fd);
        }

        // Display multiple upload progress notifications
        function ProgressNotification(name){
            this.name = name;
            ProgressNotification.all.push(this);
            this.div = $("<div>");
            $("#progress").append(this.div).show();
            this.update(0);
        }

        ProgressNotification.all = [];
        ProgressNotification.prototype = {
            update: function(percent) {
                this.div.text(this.name + "... " + Math.round(percent * 100) +
                        "%");
            },

            error: function(message) {
                var self = this;
                var input = $("<input>").
                    attr("type", "button").
                    val("OK");

                input.click(function(e) {
                    self.done();
                });

                this.div.html(this.name + "... " +  message);
                this.div.append(input);
            },

            done: function() {
                this.div.remove();
                var all = ProgressNotification.all;
                for(var i = 0; i < all.length; i++) {
                    if (all[i] === this) {
                        all.splice(i, 1);
                        break;
                    }
                }

                if (all.length === 0) {
                    $("#progress").hide();
                }
            }
        };
		
    </script>	
</body>
</html>