<?php

// Get Controller
$controller = get_controller( $this );

$webroot = \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->webroot();

// Get State
$state = $controller->state();

$oData = $controller->data_as_object();

$aData = $controller->data();


?>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Web Director | Code_Alchemy </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="/css/bootstrap.parnassus.css" rel="stylesheet">

<!-- For Sidebar -->
<link href="/css/simple-sidebar.css" rel="stylesheet">

<link type="text/less" href="/css/parnassus-admin.less" rel="stylesheet">
<!-- font awesome -->
<link href="/fawesome/css/font-awesome.min.css" rel="stylesheet">

<link href="/css/toastr.css" rel="stylesheet">

<link href="/css/jqtree.css" rel="stylesheet">

<style>
    #preloader {
        background-color: white;
        text-align: center;
        vertical-align: middle;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
    }
    #preloader .centerer
    {
        display: inline-block;
        height: 100%;
        vertical-align: middle;
    }
    #preloader img {


        display: inline-block;
        vertical-align: middle;
    }

</style>

<!-- Async Script Loader -->
<script src="/js/script.min.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/less.min.js"></script>
<script type="text/javascript" src="/js/handlebars.js"></script>
<script type="text/javascript" src="/js/bloodhound.js"></script>
<script type="text/javascript" src="/js/typeahead.js"></script>
<script type="text/javascript" src="/js/toastr.js"></script>

<script>

    <?php if ( isset( $aData['service_labels'])){?>
    var states =  {
        <?php
            $first = true;
            foreach($aData['service_labels'] as $token=>$label){?>
                <?=$first?'':','?>'<?=$token?>': '<?=$label?>'
                <?php $first = false; }?>
    };
    <?php }else {?>

    var states = [];

    <?php } ?>


</script>
<script type="text/javascript" src="/js/parnassus-web-director.js"></script>

<link rel="shortcut icon" href="/img/favicon.ico" />

<script src="/js/webDirector.js"></script>
<script>
    $(function(){

        webDirector.initialize();
    });
</script>
