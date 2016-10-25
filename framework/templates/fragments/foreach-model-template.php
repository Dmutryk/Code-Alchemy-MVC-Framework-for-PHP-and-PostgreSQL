<?php
foreach ($scope->page_sections as $section)

    echo $controller->handlebars_string($section['handlebars_template'],array_merge($section,array(

        'theme_root' => $theme_root

    )));

?>
