<?php

	session_start();
	require_once("connection.php");
	
	if(! isset($_SESSION["studentid"])) {
		
	}
	
	$studentid = $_SESSION["studentid"];
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(isset($_POST["quizid"]) && isset($_POST["quiztype"]) && isset($_POST["week"]) && isset($_POST["status"])){
			$quizid = $_POST["quizid"];
			$quiztype = $_POST["quiztype"];
			$week = $_POST["week"];
			$status = $_POST["status"];
		} else {
			
		}
		
	} else {
		
	}
	
	$conn = db_connect();
		
	if($status != "UNANSWERED") {
		$posterSql = "SELECT ZwibblerDoc
					  FROM   Poster_Record
					  WHERE StudentID=? AND QuizID=?";
							  
		$posterQuery = $conn->prepare($posterSql);
		$posterQuery->execute(array($studentid, $quizid));
		$posterRes = $posterQuery->fetch(PDO::FETCH_OBJ);	
	}
			
		
?>

<html>
<body>
	<div id=progress></div>
    <div id="zwibbler" style="margin-left:auto;margin-right:auto;width:800px;height:800px;"></div>
    <input id="saveBtn"type="button" onclick="onSave()" value="SAVE"/>
    <input id="submitBtn" type="button" onclick="onSubmit()"  value="SUBMIT"/>
	<form id="goBack" method=post action=weekly-task.php>
		<button type="button" onclick="goBack()">GO BACK</button> 
		<input type=hidden name="week" value=<?php echo $week; ?>></input>
	</form>
	<form style="display:none" method=post enctype="multipart/form-data" action="upload-handler.php">
		<input type=file name=file id=fileinput accept="image/*">
		<input type=hidden name="studentid" value=<?php echo $studentid;?>>
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
				if($status != "UNANSWERED") { ?>
					var saved = "zwibbler3.";
					saved = saved + '<?php echo $posterRes->ZwibblerDoc ?>';
					zwibbler.load("zwibbler3", saved);
		<?php	} ?>

		<?php
				if($status == "UNGRADED" || $status == "GRADED") { ?>
					zwibbler.setConfig("readOnly", true);
					$("#saveBtn").attr("disabled","disabled");
					$("#submitBtn").attr("disabled","disabled");
		<?php	} ?>
		
		function parseSaveFeedback(saveResponse) {
			if(saveResponse == "success") {
				alert("Saved Successfully!");
			} else {
				alert("Fail to save. Please try again!");
			}
		}
		
		function parseSubmitFeedback(submitResponse) {
			if(submitResponse == "success") {
				alert("Submitted Successfully!");
				zwibbler.setConfig("readOnly", true);
				$("#saveBtn").attr("disabled","disabled");
				$("#submitBtn").attr("disabled","disabled");
			} else {
				alert("Fail to submit. Please try again!");
			}
		}
		
        function onSave() {		
            var zwibblerDoc = zwibbler.save("zwibbler3");
			
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					parseSaveFeedback(xmlhttp.responseText);
				} 
			};
			
			xmlhttp.open("POST", "poster-feedback.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("quizid="+<?php echo $quizid; ?>+"&studentid="+<?php echo $studentid; ?>+"&action=SAVE"+"&zwibblerdoc="+zwibblerDoc.substr(10));
        }

        function onSubmit() {		
            var zwibblerDoc = zwibbler.save("zwibbler3");
			var dataUrl = zwibbler.save("png");
			
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					parseSubmitFeedback(xmlhttp.responseText);
				} 
			};
			
			xmlhttp.open("POST", "poster-feedback.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("quizid="+<?php echo $quizid; ?>+"&studentid="+<?php echo $studentid; ?>+"&action=SUBMIT"+"&zwibblerdoc="+zwibblerDoc.substr(10)+"&dataurl="+dataUrl);
        }
		
		function goBack() {
			document.getElementById("goBack").submit();
		}
		
		$("#fileinput").on("change", function(e) {
            var form = this.parentNode;
            upload(form);
			form.reset();
        });
		
		function uploadDone(status, result) {
            if (status === "ok") {	
				var url = "http://localhost/isnap2change/isnap2change/tmp_poster_img/" + result.fileid;
					
				zwibbler.beginTransaction();
				var nodeId = zwibbler.createNode("ImageNode", {
					url: url
				});
				zwibbler.translateNode(nodeId, 100, 100);
				zwibbler.commitTransaction();	
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