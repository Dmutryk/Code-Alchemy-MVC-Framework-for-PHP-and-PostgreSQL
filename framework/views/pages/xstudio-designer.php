<?php

/**
 * @param $studio
 * @return \xobjects\xstudio\XStudio
 */
function get_studio(  $studio ){ return $studio; }

$studio = get_studio($this->business_object->xstudio );

?>
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Panel primary</h3>
            </div>
            <div class="panel-body">
                Panel content
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Panel success</h3>
            </div>
            <div class="panel-body">
                Panel content
            </div>
        </div>

        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Panel warning</h3>
            </div>
            <div class="panel-body">
                Panel content
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="bs-component">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Panel primary</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div>

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Panel success</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div>

            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Panel warning</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div>
            <div class="btn btn-primary btn-xs" id="source-button" style="display: none;">&lt; &gt;</div></div>
    </div>
    <div class="col-lg-4">
        <div class="bs-component">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">Panel danger</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Panel info</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div>
        </div>
    </div>
</div>