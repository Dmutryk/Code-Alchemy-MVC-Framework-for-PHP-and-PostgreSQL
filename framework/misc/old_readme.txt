Parnassus is a light-weight and flexible MVC Framework for PHP and JavaScript.

Parnassus can be used to quickly build two different types of websites or web applications:

* Bootstrap3/jQuery applications and websites
* AngularJS (no jQuery) applications

Parnassus is an MIT-licensed Open Source framework.  As such, you are free to use this framework for any of your web or mobile application development, as long as the license and copyright information remain intact, and as long as you otherwise do no violate any applicable laws or terms of the agreement.

Rapid Theme Incorporation (Theme-izing)
=============

Parnassus' most powerful feature is the ability to program an HTML5 Theme, which can consists of up to hundreds of individual layouts, and some plugins, in a matter of seconds!

After this is done, the process of adding your own unique custom content goes fairly quickly as well.

This makes the entire process of creating new websites and new web applications much faster and more efficient, and more fun.

Quick Start Guide
=============

This Guide will show you how you can get started quickly with Parnassus.

Since Themeizing is the most powerful way to use Parnassus, we'll use an example of Themeizing a commercial HTML5 Theme, in this case, one of the author's personal favorites:

Oxygenna's [Angle Flat Bootstrap3 Responsive Theme](http://themeforest.net/item/angle-flat-responsive-bootstrap-template/8241353 "Angle by Oxygenna").

A couple of quick notes about this:

* If you use Angle, or another commercial theme, be sure to purchase a valid license for any website you will use it with, just as we have.
* You can substitute Angle for any other Bootstrap Responsive HTML5 theme you like
* We will cover AngularJS-based Themes separately

Installing Parnassus
--------------

Since we're on [Packagist](https://packagist.org/packages/alquemedia/parnassus-framework "Parnassus on Packagist") installing is super easy, just use composer.

Add the following to your composer.json file:

```
{
    "require": {
        "alquemedia/parnassus-framework": "dev-master"
    }
}
```
Then simply run composer at the document root of your website or application:

```
username@machine:/var/www/website$ composer install
```

You can then check that Parnassus is installed and ready for use, by running:

```
username@machine:/var/www/website$ vendor/alquemedia/parnassus-framework/x_objects/parnassus
```
When installed correctly, the command-line tool will spit out a lot of information, including the version number, the working directories, and a list of possible commands to run.

Deploying Theme
-------------

Next, we'll create the directory where the theme will live, in our case:

```
username@machine:/var/www/website$ mkdir -p themes/angle
```

Substitute for the canonical name of the theme you'll be using.  It's recommended to stick to lowercase names, and hyphens are allowed.

Extract the contents of the Theme into this directory, such that the Layouts are in the root and all the assets line up accordingly.

In our case, the directory looks like this:

```
davidg@Alquemedia2:/var/www/demo$ ls themes/angle
404.html                blog-styles.html     footer-alt.html      portfolio-2col.html          portfolio-item-video.html  sidebar-right.html
about-me.html           color-swatches.html  footer-options.html  portfolio-3col-circles.html  post.html                  single-service.html
about-us-home.html      contact-alt.html     grid-blog.html       portfolio-3col.html          pricing.html               tables.html
about-us.html           contact.html         header-alt.html      portfolio-3col-rect.html     results.html               typography.html
assets                  contact_mailer.php   header-options.html  portfolio-3col-squares.html  revolution-slider.html     vendor
author.html             countdown.html       icons.html           portfolio-4col.html          scroll-animation.html
background-images.html  custom-icons.html    index.html           portfolio-item-big-alt.html  section-decorations.html
background-videos.html  elements.html        launch.html          portfolio-item-big.html      services-alt-page.html
blog-fullwidth.html     faq.html             office.html          portfolio-item-gallery.html  services-page.html
blog.html               flexslider.html      one-page.html        portfolio-item-small.html    sidebar-left.html
davidg@Alquemedia2:/var/www/demo$
```

Adding a Database
---------

Parnassus requires a MySQL database, for both system and user-defined data.  Before proceeding, make sure you've created a new empty database, and have the credentials handy.

Theme-ize and Deploy
--------

Running a single command will allow you to Theme-ize your Theme (Angle, in our case), and build the structure for a new Website or Web application:

A safety step is required, there must be present a file called "parnassus-ok", so below we've shown the commands in sequence:

```
davidg@Alquemedia2:/var/www/demo$ touch parnassus-ok
davidg@Alquemedia2:/var/www/demo$ vendor/alquemedia/parnassus-framework/x_objects/parnassus webapp
```

A fair amount of output will be sent to the console, but you should be concerned only if you see an error present.


Verifying Install and Website Themization
-------

At this point, assuming all has gone well, you can load up your website into a browser, such as Chrome or Firefox, and from the home page of the site, you will see the theme, exactly as though it were being displayed in the demo.

In some cases, an error might have been produced, which you can usually pick up in Firebug, if related to Javascript or paths, or on-screen, if a less common PHP error occurred.

In all cases, please email us at support@alquemedia.com with as many details as possible, so we can investigate and improve the code.

Par