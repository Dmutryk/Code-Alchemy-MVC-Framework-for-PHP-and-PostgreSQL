<?php


namespace Code_Alchemy\AngularJS;


use Code_Alchemy\Core\Array_Representable_Object;

class AngularJS_Cloudflare_Includes extends Array_Representable_Object{

    public function __construct(){

        $this->array_values = array(

           "\t".   '<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>'."\r\n",
           "\t".     '<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular-route.min.js"></script>'."\r\n",
           "\t".   '<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.12.1/ui-bootstrap-tpls.min.js"></script>'."\r\n"

        );
    }

}

