<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/instafeed.min.js"></script>
</head>
<body>    

    <!--Facebook Page Plugin JavaScript SDK -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=909271749154924";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <!--Facebook Page Plugin JavaScript SDK -->
    
    <!--Twitter widgets.js -->
    <script>window.twttr = (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
      if (d.getElementById(id)) return t;
      js = d.createElement(s);
      js.id = id;
      js.src = "https://platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js, fjs);
     
      t._e = [];
      t.ready = function(f) {
        t._e.push(f);
      };
     
      return t;
    }(document, "script", "twitter-wjs"));</script>
    
	<div class="row">
        <div class="col-sm-4" style="background-color:lavender;">
        
    <!--Facebook Page Plugin 
    
    Facebook Page URL
    https://www.facebook.com/cpuresearch
    Tabs
    timeline, messages
    -->
    <h1>Facebook Timeline/Message</h1>
    <div class="fb-page" data-href="https://www.facebook.com/cpuresearch" data-tabs="timeline, messages" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/cpuresearch"><a href="https://www.facebook.com/cpuresearch">CPU Research</a></blockquote></div></div>
    
        </div>
        
        <div class="col-sm-4" style="background-color:lavenderblush;">
            <!--Twitter Widget User Timeline-->
            <h1>Twitter Timeline</h1>
            <a class="twitter-timeline" href="https://twitter.com/CPUResearch" data-widget-id="732758309019607040">Tweets by @CPUResearch</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        
        <div class="col-sm-4" style="background-color:lavender;">
        <!--Twitter Widget Search-->
        <h1>Twitter Hashtag</h1>
        <a class="twitter-timeline" href="https://twitter.com/hashtag/Trump2016" data-widget-id="732759547232714752">#Trump2016 Tweets</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        
    </div>
    
    <div id="instafeed"></div>
    <script type="text/javascript">
    var feed = new Instafeed({
        get: 'tagged',
        tagName: 'awesome',
        clientId: '8f72a8bd53ca4c7881724feda7959875'
    });
    feed.run();
    </script>
    </body>
</html>