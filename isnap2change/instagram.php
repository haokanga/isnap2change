<html>
<head>
<script type="text/javascript" src="js/instafeed.min.js"></script>
</head>
<body>
    <div id="instafeed"></div>
    <script type="text/javascript">
    var feed = new Instafeed({
        get: 'tagged',
        tagName: 'awesome',
        clientId: '8f72a8bd53ca4c7881724feda7959875',
    });
    feed.run();
    </script>
    </body>
</html>