<?php

$studentid = 1;

?>

<html>
<body>
	<div id=progress></div>
    <div id="zwibbler" style="margin-left:auto;margin-right:auto;width:800px;height:800px;"></div>
    <input type="button" onclick="onSave()" value="Save"/>
    <input id="loadButton" type="button" onclick="onLoad()" disabled="disabled" value="Load"/>
    <input type="button" onclick="onImage()" value="Open as image"/>
    <input type="button" onclick="zwibbler.onResize()" value="Open as image"/>
	<form style="display:none" method=post enctype="multipart/form-data" action="upload-handler.php">
		<input type=file name=file id=fileinput accept="image/*">
		<input type=hidden name="studentid" value=<?php echo $studentid;?>>
    </form>
	<img id="res"></img>
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://zwibbler.com/zwibbler2.js"></script>
    <script type="text/javascript">
        
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
		
        var saved = null;
		
        function onSave() {
            saved = zwibbler.save("zwibbler3");
            $("#loadButton").removeAttr("disabled");
        }

        function onLoad() {
            zwibbler.load("zwibbler3", saved);
        }

        function onImage() {
            
			//var dataUrl = zwibbler.save('svg');
			var dataUrl = zwibbler.save("png");
		    $("#res").attr("src", dataUrl);
			//$("img").attr("width","500");
            //window.open(dataUrl, "other");
        }
		
		$("#fileinput").on("change", function(e) {
            var form = this.parentNode;
            upload(form);
			form.reset();
        });
		
		function uploadDone(status, result) {
            if (status === "ok") {	
			
				var url = "http://localhost/isnap2change/isnap2change/tmp_poster_image/" + result.fileid;
				
			//	var url = "http://localhost/isnap2change/isnap2change/tmp_poster_image/Picture3.png"
				
				var dataURL;
					
				var img = new Image();
				img.crossOrigin = 'Anonymous';
				img.src = url;
				img.onload = function(){
					var canvas = document.createElement('CANVAS');
					var ctx = canvas.getContext('2d');
					
					
					canvas.height = this.height;
					canvas.width = this.width;
					ctx.drawImage(this, 0, 0);
					dataURL = canvas.toDataURL();
			
					zwibbler.beginTransaction();
					var nodeId = zwibbler.createNode("ImageNode", {
						url: dataURL
					});
					zwibbler.translateNode(nodeId, 100, 100);
					zwibbler.commitTransaction();	
					canvas = null; 
				};
            }
        }
		
		function upload(form)
        {
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
        function ProgressNotification(name)
        {
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