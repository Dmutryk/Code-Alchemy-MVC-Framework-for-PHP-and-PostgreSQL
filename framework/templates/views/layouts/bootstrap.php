
<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$this->state->page_title()?></title>
    <meta name="description" content="<?=$state->meta_description()?>" />

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="The Name or Title Here">
    <meta itemprop="description" content="This is the page description">
    <meta itemprop="image" content="http://www.example.com/image.jpg">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="Page Title">
    <meta name="twitter:description" content="Page description less than 200 characters">
    <meta name="twitter:creator" content="@author_handle">
    <!-- Twitter summary card with large image must be at least 280x150px -->
    <meta name="twitter:image:src" content="http://www.example.com/image.html">

    <!-- Open Graph data -->
    <meta property="og:title" content="Title Here" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?=$state->open_graph_url()?>" />
    <meta property="og:image" content="http://example.com/image.jpg" />
    <meta property="og:description" content="Description Here" />
    <meta property="og:site_name" content="Site Name, i.e. Moz" />
    <meta property="article:published_time" content="2013-09-17T05:59:00+01:00" />
    <meta property="article:modified_time" content="2013-09-16T19:08:47+01:00" />
    <meta property="article:section" content="Article Section" />
    <meta property="article:tag" content="Article Tag" />
    <meta property="fb:admins" content="Facebook numberic ID" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- my local less file -->
    <link type="text/less" href="/css/_appname_.less" rel="stylesheet">

    <!-- font awesome -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="/js/underscore.js"></script>
    <script src="/js/handlebars.js"></script>
    <script src="/js/backbone.js"></script>

    <!-- google plus -->
    <script src="https://apis.google.com/js/client:platform.js" async defer></script>

    <script data-main="/js/main" src="/js/require.js"></script>

    <script type="text/javascript" src="/js/less.min.js"></script>


    <link rel="shortcut icon" href="/img/favicon.ico" />
</head>
<body>
    <div id="fb-root"></div>
<!-- Facebook Integration -->
<script>
    // This is called with the results from from FB.getLoginStatus().
    function statusChangeCallback(response) {

        console.log('_appname_: Facebook Status Change Callback, result appears below');

        console.log(response);

        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {

            // Logged into your app and Facebook.
            FBAPI();

        } else if (response.status === 'not_authorized') {

            console.log('_appname_: User is not authorized yet for your Facebook App');

        } else {

            console.log("_appname_: User is not logged in to Facebook, so we don't know their disposition.");

        }
    }

    // This function is called when someone finishes with the Login
    // Button.  See the onlogin handler attached to it in the sample
    // code below.
    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    }
    window.fbAsyncInit = function() {

        // Initialize Facebook
        FB.init({
            appId      : '<?=$state->facebook_app_id()?>',
            xfbml      : true,
            version    : 'v2.1'
        });


        // Now that we've initialized the JavaScript SDK, we call
        // FB.getLoginStatus().  This function gets the state of the
        // person visiting this page and can return one of three states to
        // the callback you provide.  They can be:
        //
        // 1. Logged into your app ('connected')
        // 2. Logged into Facebook, but not your app ('not_authorized')
        // 3. Not logged into Facebook and can't tell if they are logged into
        //    your app or not.
        //
        // These three cases are handled in the callback function.

        FB.getLoginStatus(function(response) {

            require(['channel'],function(Channel){

                console.log('_appname_: Facebook SDK is initialized');

                Channel.trigger('facebook.sdk.initialized');

                statusChangeCallback(response);

            });
        });

    };

    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Here we run a very simple test of the Graph API after login is
    // successful.  See statusChangeCallback() for when this call is made.
    function FBAPI() {

        console.log('Welcome!  Fetching your information.... ');

        FB.api('/me', function(response) {

            console.log('_appname_: Successful login for: ' + response.name);

            console.log(response);

            // Load the Channel
            require(['channel'],function(Channel){

                // Send Channel Event that user is connected
                Channel.trigger('user.connected.with.facebook');

            });

        });
    }</script>


<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="../">
                <img src="/img/logo2.png"/>
            </a>
            <button data-target="#navbar-main" data-toggle="collapse" type="button" class="navbar-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar-main" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a id="themes" href="#" data-toggle="dropdown" class="dropdown-toggle">Themes <span class="caret"></span></a>
                    <ul aria-labelledby="themes" class="dropdown-menu">
                        <li><a href="../default/">Default</a></li>
                        <li class="divider"></li>
                        <li><a href="../cerulean/">Cerulean</a></li>
                        <li><a href="../cosmo/">Cosmo</a></li>
                        <li><a href="../cyborg/">Cyborg</a></li>
                        <li><a href="../darkly/">Darkly</a></li>
                        <li><a href="../flatly/">Flatly</a></li>
                        <li><a href="../journal/">Journal</a></li>
                        <li><a href="../lumen/">Lumen</a></li>
                        <li><a href="../paper/">Paper</a></li>
                        <li><a href="../readable/">Readable</a></li>
                        <li><a href="../sandstone/">Sandstone</a></li>
                        <li><a href="../simplex/">Simplex</a></li>
                        <li><a href="../slate/">Slate</a></li>
                        <li><a href="../spacelab/">Spacelab</a></li>
                        <li><a href="../superhero/">Superhero</a></li>
                        <li><a href="../united/">United</a></li>
                        <li><a href="../yeti/">Yeti</a></li>
                    </ul>
                </li>
                <li>
                    <a href="../help/">Help</a>
                </li>
                <li>
                    <a href="http://news.bootswatch.com">Blog</a>
                </li>
                <li class="dropdown">
                    <a id="download" href="#" data-toggle="dropdown" class="dropdown-toggle">Download <span class="caret"></span></a>
                    <ul aria-labelledby="download" class="dropdown-menu">
                        <li><a href="./bootstrap.min.css">bootstrap.min.css</a></li>
                        <li><a href="./bootstrap.css">bootstrap.css</a></li>
                        <li class="divider"></li>
                        <li><a href="./variables.less">variables.less</a></li>
                        <li><a href="./bootswatch.less">bootswatch.less</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a target="_blank" href="http://builtwithbootstrap.com/">Built With Bootstrap</a></li>
                <li><a target="_blank" href="https://wrapbootstrap.com/?ref=bsw">WrapBootstrap</a></li>
                <li class="fb-login-li"><!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

                    <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
                    </fb:login-button>
                </li>
                <li class="gplus-login-li">
                    <span id="signinButton">
                        <span   class="g-signin"
                                data-callback="signinCallback"
                                data-clientid="<?=$state->google_plus_client_id()?>"
                                data-cookiepolicy="single_host_origin"
                                data-requestvisibleactions="http://schema.org/AddAction"
                                data-scope="https://www.googleapis.com/auth/plus.login">
                        </span>
                    </span>
                </li>
            </ul>

        </div>
    </div>
</div>

<div style="margin-top: 50px;" class="container">
    <div class="bs-docs-section clearfix">
        <div class="row">
            <div class="col-lg-12 content-container">

            </div>

        </div>

    </div>
</div>

    <!-- Google Plus Login Callback -->
    <script>

        function signinCallback(authResult) {
            if (authResult['status']['signed_in']) {

                console.log('_appname_: Successfully authorized by Google+');

            } else {

                // Update the app to reflect a signed out user
                // Possible error values:
                //   "user_signed_out" - User is signed-out
                //   "access_denied" - User denied access to your app
                //   "immediate_failed" - Could not automatically log in the user
                console.log('_appname_: Sign-in state: ' + authResult['error']);
            }
        }

    </script>
</body>
</html>

