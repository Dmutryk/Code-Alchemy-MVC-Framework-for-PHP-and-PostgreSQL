<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/angle/";

// Web root
$webroot = x_objects::instance()->webroot();

$data = $controller->data();

$content = $state->get_content();


?>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/x-icon" href="<?=$theme_root?>assets/images/favicons/favicon.ico" />
<link rel="icon" type="image/png" href="<?=$theme_root?>assets/images/favicons/favicon.png" />
<!-- For iPhone 4 Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=$theme_root?>assets/images/favicons/apple-touch-icon-114x114-precomposed.png">
<!-- For iPad: -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=$theme_root?>assets/images/favicons/apple-touch-icon-72x72-precomposed.png">
<!-- For iPhone: -->
<link rel="apple-touch-icon-precomposed" href="<?=$theme_root?>assets/images/favicons/apple-touch-icon-60x60-precomposed.png">
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,400italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?=$theme_root?>assets/css/bootstrap.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/theme.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/color-defaults.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-beige-black.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-black-beige.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-black-white.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-black-yellow.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-blue-white.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-green-white.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-red-white.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-white-black.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-white-blue.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-white-green.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-white-red.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/swatch-yellow-black.min.css">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/fonts.min.css" media="screen">
<link rel="stylesheet" href="<?=$theme_root?>assets/css/revolution.min.css" media="screen">
<!-- Place this data between the <head> tags of your website -->
<title>Page Title. Maximum length 60-70 characters</title>
<meta name="description" content="Page description. No longer than 155 characters." />

<!-- Twitter Card data -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="Page Title">
<meta name="twitter:description" content="Page description less than 200 characters">
<meta name="twitter:creator" content="@author_handle">
<-- Twitter Summary card images must be at least 120x120px -->
<meta name="twitter:image" content="http://www.example.com/image.jpg">

<!-- Open Graph data -->
<meta property="og:title" content="Title Here" />
<meta property="og:type" content="article" />
<meta property="og:url" content="http://www.example.com/" />
<meta property="og:image" content="http://example.com/image.jpg" />
<meta property="og:description" content="Description Here" />
<meta property="og:site_name" content="Site Name, i.e. Moz" />
<meta property="fb:admins" content="Facebook numeric ID" />