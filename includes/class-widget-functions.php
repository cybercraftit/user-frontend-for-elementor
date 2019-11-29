<?php

Class FAEL_Widget_Functions {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

    }

}

function FAEL_Widget_Functions() {
    return FAEL_Widget_Functions::instance();
}

FAEL_Widget_Functions();