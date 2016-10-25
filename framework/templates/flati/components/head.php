<?php

$controller = get_controller($this);

$state = get_state( $controller );

?>
<meta charset="utf-8">
<title><?=$state->page_title()?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?=$state->meta_description()?>">
<meta name="author" content="">

<!-- Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
<!--[if IE]>
<link href="http://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Lato:400" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Lato:700" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">
<![endif]-->

<link href="<?=$theme_root?>css/bootstrap.css" rel="stylesheet">
<link href="<?=$theme_root?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?=$theme_root?>css/theme.css" rel="stylesheet">
<link href="<?=$theme_root?>css/colour.css" rel="stylesheet" type="text/css"/>
<link href="<?=$theme_root?>css/prettyPhoto.css" rel="stylesheet" type="text/css"/>
<link href="<?=$theme_root?>css/zocial.css" rel="stylesheet" type="text/css"/>
<!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
<link rel="stylesheet" type="text/css" href="<?=$theme_root?>rs-plugin/css/settings.css" media="screen" />

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link href="/css/__appname__.less" rel="stylesheet" type="text/less"/>
