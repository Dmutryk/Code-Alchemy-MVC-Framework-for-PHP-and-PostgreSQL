<?php

/**
 * Class business_interface Specifies required methods to be implemented
 * by the Business Object Class (now called a Model)
 */
interface business_interface {

    /**
     * @return \DataSource2 Data Source for the Model
     */
    public static function source();

    /**
     * @param string $view name of view to render
     * @return string Model, as HTML
     */
    public function html( $view );
}
?>