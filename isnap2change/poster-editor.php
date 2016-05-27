<!DOCTYPE html>
<html>
<!--
* logo
* add page
* remove page
* pointer tool
* image tool
* image upload
* copy
* paste
* undo
* redo
* When something's selected:
    * shadow button
    * font button
* button to draw rect
* button to draw circle
* button to make transparent
* button to change text alignment

Storage:
- image files are shrunk to 1280
* atime is stored

* undo crop image not working
* insert-page-undo-redo-undo time not working

Zwibbler:
* cropping should change shape of selection rect
* TextNode supports align property
* resource-loaded event
* page selector redraws when resource is loaded.
x page has a gray border stroked around it

file:///home/smhanov/fun/blorb.html#fileid=mXOJAJ4ny3RqsjvXK3kx0ZLXsg4
-->
<head>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Special+Elite' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Love+Ya+Like+A+Sister' rel='stylesheet' type='text/css'>
    <style>
        body {
            overflow: hidden;
        }

        .blue {
            background: #222;
            color: #ccc;                        
        }

        #side-menu {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 300px;
            overflow-y: auto;
            text-align: center;
            font-family: Arial;
            color: #ccc;
            font-size: 20px;
        }

        #zwibbler {
            position: absolute;
            top: 0;
            left: 300px;
            bottom: 0;
            right: 200px;
        }

        #pages {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 160px;
            padding: 0 20px;
            overflow-y: scroll;
        }

        .logo {
            font-family: "Special Elite";
            font-size: 40px;
            padding: 20px;
            color: orange;
        }

        .tools a {
            color: #ccc;
            width: 54px;
            height: 54px;
            font-size: 40px;
            line-height: 55px;
            border: 2px solid #ccc;
            display: inline-block;
            text-align: center;
            margin: 5px;
            border-radius: 3px;
        }

        .tools-small a {
            color: white;
            width: 30px;
            height: 30px;
            font-size: 18px;
            line-height: 30px;
            border: 2px solid #ccc;
            display: inline-block;
             text-align: center;
            border-radius: 3px;
        }

        .tools a:hover, .tools-small a:hover, .page-buttons a:hover,
        .text-tools a:hover, .doc-buttons a:hover {
            color: #222;
            background: #ccc;       
        }

        #progress {
            isplay: none;
            position: absolute;
            top: 0;
            right: 200px;
            box-shadow: 3px 3px 3px #444;
            background: #ccc;
            color: black;
            border-bottom-left-radius: 4px;         
            font-family: arial, sans;
        }

        #progress div {
            border-top: 1px solid #888;
            padding: 5px;
        }

        .noselect {
           -moz-user-select: -moz-none;
           -khtml-user-select: none;
           -webkit-user-select: none;
           -ms-user-select: none;
           user-select: none;
        }

        select {
            background: transparent;
            color: #ccc;
            padding: 5px;
            border: 2px solid #ccc;
            font-size: 20px;
        }

        .page-buttons a {
            display: inline-block;
            width: 25px;         
            height: 25px;         
            line-height: 25px;
            text-align: center;
            font-size: 20px;
            color: #ccc;
        }

        .page-buttons {
            text-align: center;
            padding: 10px 0;                    
        }

        .text-tools a {
            display: block;
            height: 30px;
            font-size: 20px;
            vertical-align: middle;
            text-align: left;
            color: #ccc;
            padding: 10px 55px;
            width: 90%;         
            text-decoration: none;
        }

        .text-tools a .fa {
            line-height: 30px;
            text-align: center;
            font-size: 25px;
        }

        .doc-buttons a {
            display: inline-block;
            border: 2px solid #ccc;
            margin: 5px;        
            color: #ccc;
            border-radius: 3px;
            padding: 5px;
            cursor: pointer;
            width: 78px;
        }

    </style>
</head>
<body>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://zwibbler.com/zwibbler-demo.js"></script>
    <div id=side-menu class="blue noselect">
        <div class=logo>
            <div>
                <i class="fa fa-camera-retro"></i> blerb
            </div>
            <div style="font-size:23px">zwibbler demo</div>
        </div>
        <div class=tools>
            <a title="Pick tool" href="javascript:undefined" id=pick-tool><i class="fa fa-hand-o-up"></i></a><a 
            title="Insert image" href="javascript:undefined" id=insert-image><i class="fa fa-file-image-o"></i></a><a 
            title="Insert text" href="javascript:undefined" id=text-tool><i class="fa fa-font"></i></a><br><a 
            title="Pencil" href="javascript:undefined" id=pencil-tool><i class="fa fa-pencil"></i></a><a 
            title="Square" href="javascript:undefined" id=square-tool><i class="fa fa-square"></i></a><a 
            title="Circle" href="javascript:undefined" id=circle-tool><i class="fa fa-circle"></i></a>
        </div>
        <div class=tools-small style="margin-top:20px">
            <a title="Cut" href="javascript:undefined" id=cut><i class="fa fa-cut"></i></a>
            <a title="Copy" href="javascript:undefined" id=copy><i class="fa fa-copy"></i></a>
            <a title="Paste" href="javascript:undefined" id=paste><i class="fa fa-paste"></i></a>
            <a title="undo" href="javascript:undefined" id=undo><i class="fa fa-undo"></i></a>
            <a title="redo" href="javascript:undefined" id=redo><i class="fa fa-repeat"></i></a>
        </div>

        <div class=doc-buttons style="margin-top:20px">
            <a title="New" id=new-document><i class="fa fa-file-o"></i> New</a><a 
            title="Save" id=save-document><i class="fa fa-cloud-upload"></i> Save</a>
        </div>

        <div style="margin-top:20px" class="text-tools">
            <a title="Zoom" href="javascript:undefined" id=zoom><i
                class="fa fa-search"></i>  Zoom</a>
            <a title="Shadow" href="javascript:undefined" id=shadow><i
                class="fa fa-glass" style="text-shadow: 3px 3px 3px #fff"></i>
                Add shadow</a>
            <a title="Make transparent" href="javascript:undefined" id=transparent><i
                class="fa fa-glass" style="opacity:0.5"></i>  Make transparent</a>
            <a title="Font" href="javascript:undefined" id=font><i
                class="fa fa-font"></i>  Change font</a>
            <a title="Inrease font size" href="javascript:undefined"
                id=font-increase><i
                class="fa fa-angle-up"></i>  Increase font size</a>
            <a title="Decrease font size" href="javascript:undefined"
                id=font-decrease><i
                class="fa fa-angle-down"></i>  Decrease font size</a>
            <a title="Text alignment" href="javascript:undefined" id=alignment><i
                class="fa fa-align-center"></i>  Text alignment</a>
        </div>
        <form style="display:none" method=post
            enctype="multipart/form-data"
            action="http://zwibbler.com/temp.py">
            <input type=file name=file id=fileinput accept="image/*">
        </form>
    
    </div>
    <div id=zwibbler>

    </div>
    <div id=progress></div>
    <div id=pages class="blue noselect">
        <div class=page-buttons>
            <a title="add-page" href="javascript:undefined" id=add-page><i
            class="fa fa-plus"></i></a>
            <a title="delete-page" href="javascript:undefined"
                id=delete-page><i class="fa fa-minus"></i></a>
        </div>
        <div id=inner-pages>
        </div>
    </div>

    <script>
        var zwibbler = Zwibbler.create("zwibbler", {
            autoPickTool: true,
            showToolbar: false,
            defaultPaperSize: "letter",
            pageView: true,
            pageSelectorDiv: "#inner-pages",
            defaultFontSize: 50,
            defaultFont: "Bitter",
            multilineText: true,
            defaultLineWidth: 0
        });

        $("#font").hide();
        $("#font-increase").hide();
        $("#font-decrease").hide();
        $("#shadow").hide();
        $("#transparent").hide();
        $("#alignment").hide();

        // Handle image uploads to the server. When the upload begins, display
        // a notification and update it.

        $("#insert-image").click(function(e) {
            $("#fileinput").click();
        });

        $("#pick-tool").click(function(e) {
            zwibbler.usePickTool();
        });

        $("#text-tool").click(function(e) {
            zwibbler.useTextTool();
        });

        $("#undo").click(function(e) {
            zwibbler.undo();
        });

        $("#redo").click(function(e) {
            zwibbler.redo();
        });

        $("#cut").click(function(e) {
            zwibbler.copy();
            zwibbler.deleteSelection();
        });

        $("#copy").click(function(e) {
            zwibbler.copy();
        });

        $("#paste").click(function(e) {
            zwibbler.paste();
        });

        $("#fileinput").on("change", function(e) {
            var form = this.parentNode;
            upload(form);
            form.reset();
        });

        $("#add-page").click(function(e) {
            zwibbler.setCurrentPage(zwibbler.insertPage(zwibbler.getCurrentPage()+1));
        });

        $("#delete-page").click(function(e) {
            zwibbler.deletePage(zwibbler.getCurrentPage());
        });

        $("#square-tool").click(function(e) {
            zwibbler.useSquareTool();
        });

        $("#circle-tool").click(function(e) {
            zwibbler.useCircleTool();
        });

        $("#pencil-tool").click(function(e) {
            zwibbler.useBrushTool();
        });

        var Zoom = "page";
        zwibbler.setZoom(Zoom);

        $("#zoom").click(function(e) {
             if (Zoom === "page") {
                 Zoom = "width";
             } else {
                Zoom = "page";
             }
             zwibbler.setZoom(Zoom);
        });


        var Fonts = [
            "Special Elite",
            "Bitter",
            "Love Ya Like A Sister"
        ];

        var Properties = {};

        // When the selection changes, zwibbler will call this function
        zwibbler.on("selected-nodes", function(e) {
            var ids = zwibbler.getSelectedNodes();

            var types = {};
            Properties = {
                "shadow": false,
                "font": "Bitter",
                "fillStyle": "#808080",
                "textAlign": "left",
                "fontSize": 20
            };

            for(var i = 0; i < ids.length; i++ ) {
                var type = zwibbler.getNodeType(ids[i]);
                if (type === "PathNode") {
                    types[type] = true;
                    if (zwibbler.getNodeProperty(ids[i], "closed")) {
                        type = "PathNode-closed";
                    } else {
                        type = "PathNode-open";
                    }
                } else if (type === "TextNode") {
                    Properties["fontName"] = zwibbler.getNodeProperty(ids[i],
                        "fontName");
                }

                var shadow = zwibbler.getNodeProperty(ids[i], "shadow");
                if (shadow !== undefined) {
                    Properties["shadow"] = shadow;
                }

                var fillStyle = zwibbler.getNodeProperty(ids[i], "fillStyle");
                if (fillStyle !== undefined) {
                    Properties["fillStyle"] = fillStyle;
                }

                var alignment = zwibbler.getNodeProperty(ids[i], "textAlign");
                if (alignment !== undefined) {
                    Properties["textAlign"] = alignment;
                }

                var fontSize = zwibbler.getNodeProperty(ids[i], "fontSize");
                if (fontSize !== undefined) {
                    Properties["fontSize"] = fontSize;
                }

                types[type] = true;
            }

            $("#shadow").toggle(ids.length > 0);
            $("#font").toggle(types["TextNode"] === true);
            $("#transparent").toggle(types["PathNode-closed"] === true);
            $("#alignment").toggle(types["TextNode"] === true);
            $("#font-increase").toggle(types["TextNode"] === true);
            $("#font-decrease").toggle(types["TextNode"] === true);
        });

        $("#shadow").on("click", function(e) {
            Properties["shadow"] = !Properties["shadow"];
            zwibbler.setNodeProperty(zwibbler.getSelectedNodes(), "shadow",
                Properties["shadow"]);
        });

        $("#font").on("click", function(e) {
            for(var i = 0; i < Fonts.length; i++ ) {
                if ( Properties["fontName"] === Fonts[i] ) {
                    i += 1;
                    break;
                }
            }

            var newFont = Fonts[i%Fonts.length];
            Properties["fontName"] = newFont;

            zwibbler.setNodeProperty(zwibbler.getSelectedNodes(), "fontName",
                newFont);
        });

        $("#transparent").on("click", function(e) {
            var colour = Zwibbler.parseColour(Properties["fillStyle"]);
            if (colour.a === 1) {
                colour.a = 0.7;
            } else {
                colour.a = 1;
            }

            Properties["fillStyle"] = Zwibbler.makeColour(colour);

            zwibbler.setNodeProperty(zwibbler.getSelectedNodes(),
                "fillStyle", Properties["fillStyle"]);
        });

        $("#font-increase").click(function(e) {
            Properties["fontSize"] *= 1.1;
            zwibbler.setNodeProperty(zwibbler.getSelectedNodes(),
                "fontSize", Properties["fontSize"]);
        });

        $("#font-decrease").click(function(e) {
            Properties["fontSize"] *= 1/1.1;
            zwibbler.setNodeProperty(zwibbler.getSelectedNodes(),
                "fontSize", Properties["fontSize"]);
        });

        $("#alignment").click(function(e) {
            if (Properties["textAlign"] === "left") {
                Properties["textAlign"] = "centre";
            } else if (Properties["textAlign"] === "centre") {
                Properties["textAlign"] = "right";
            } else {
                Properties["textAlign"] = "left";
            }

            zwibbler.setNodeProperty(zwibbler.getSelectedNodes(), "textAlign",
                Properties["textAlign"]);
        });

        // When the upload is done either successfully or due to an error, this
        // function is called. In the successful case, the status is set to
        // "ok". Otherwise it is set to an error message. The result contains
        // the JSON decoded response from the server. 
        // 
        // This function must then insert the image into the document.
        function uploadDone(status, result) {
            if (status === "ok") {
                // *******************************************************
                // *******************************************************
                // *******************************************************
                // CHANGE THIS CODE TO PROCESS YOUR OWN RESPONSE
                // *******************************************************
                // *******************************************************
                // *******************************************************
                var url = "http://zwibbler.com/temp.py?fileid=" +
                    result.fileid;

                zwibbler.beginTransaction();
                var nodeId = zwibbler.createNode("ImageNode", {
                    url: url
                });
                zwibbler.translateNode(nodeId, 100, 100);
                zwibbler.commitTransaction();
            }
        }

        function upload( form )
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
                    uploadDone( "ok", $.parseJSON( xhr.response ) );
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

        // ----------------------------------------------------------------
        // EXAMPLE CODE FOR SAVING THE DOCUMENT.
        function SaveDocument() {
            $.ajax({
                url: "http://zwibbler.com/temp.py",
                type: "POST",
                data: {file: zwibbler.save()},

                success: function(response, status, xhr) {
                    $("#working").hide();
                    location.hash = "fileid=" + response.fileid;
                },

                error: function(response, status, xhr) {
                    $("#working").hide();
                    alert("Error: " + status);
                }
            });
            $("#working").show();
        }

        // ----------------------------------------------------------------
        // EXAMPLE CODE FOR OPENING THE DOCUMENT
        function OpenDocument(fileid) {
            $.ajax({
                url: "http://zwibbler.com/temp.py",
                type: "GET",
                data: { fileid: fileid },

                success: function(response, status, xhr) {
                    $("#working").hide();
                    zwibbler.load(response);
                },

                error: function(response, status, xhr) {
                    $("#working").hide();
                    alert("Error: " + status);
                }
            });
            $("#working").show();
        }

        $("#save-document").click(function(e) {
            SaveDocument();
        });

        $("#new-document").click(function(e) {
            if (!zwibbler.dirty() || confirm("Discard changes?")) {
                zwibbler.newDocument();
                location.hash = "";
            }
        });

        // ---------------------------------------------------------------
        // EXAMPLE CODE
        // When the page is loaded, and it contains a fileid= parameter,
        // then open that document.
        function SplitQueryString(string, separator) {
            var a, field, fields, i, index, key, value, _i, _len;
            separator = separator || "?";
            a = {};
            index = string.indexOf(separator);
            if (index >= 0) {
                string = string.substr(index + 1);
            }
            index = string.indexOf('#');
            if (index >= 0) {
                string = string.substr(0, index);
            }
            fields = string.split("&");
            for (_i = 0, _len = fields.length; _i < _len; _i++) {
                field = fields[_i];
                i = field.split("=");
                key = decodeURIComponent(i[0]);
                value = i.length > 1 ? decodeURIComponent(i[1]) : "";
                if (key.length) {
                    a[key] = value;
                }
            }
            return a;
        }

        var hash = SplitQueryString(location.hash, "#");
        if ("fileid" in hash) {
            OpenDocument(hash["fileid"]);
        }
    </script>
</body>
</html>
