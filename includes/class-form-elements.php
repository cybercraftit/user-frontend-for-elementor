<?php

class FAEL_Form_Elements {

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     */
    private static $_instance = null;

    protected $fael_forms = [];

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return FAEL_Form_Elements An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
    }

    public function get_form_elements() {
        return $this->fael_forms;
    }

    /**
     * @param $fael_forms
     */
    public function set_form_elements( $fael_forms ) {
        $this->fael_forms = $fael_forms;
    }

    /**
     * @param $handle
     * @param $name
     * @param $elem_data
     */
    public function set_form_element( $handle, $name, $elem_data ) {
        $this->fael_forms[$handle][$name] = $elem_data;
    }

    /**
     * @param $handle
     * @param $name
     * @param null $default
     * @param bool $use_default
     * @param null $type
     * @param string $module
     */
    public function  populate_field( $handle, $name, $default = null, $use_default = false, $type = null, $module = 'post' ) {
        global  $fael_post, $fael_user;

        $fael_forms = $this->fael_forms;

        $obj = null;
        switch ( $module ) {
            case 'post':
                $obj = $fael_post;
                break;
            case 'user':
                $obj = $fael_user;
                break;
        }

        if( $use_default ) {
            $value = $default;
        } else {
            if( isset( $obj->{ $name } ) ) {
                $value = $obj->{ $name };
            } else {
                switch ( $name ) {
                    case 'featured_image':
                        $value = isset( $obj->ID ) ? get_the_post_thumbnail_url($obj->ID) : $default;
                        break;
                    default:
                        $value = $default;
                        break;
                }

            }
        }

        if( !$type ) {
            $fael_forms[$handle][$name]['value'] = $value;
        } else {
            switch ( $type ) {
                case 'taxonomy':
                    $fael_forms[$handle]['taxonomy'][$name]['value'] = $value;
                    break;
            }
        }

        $this->fael_forms = $fael_forms;
    }

    public function set_form_settings( $handle, $form_settings ) {

    }

}

function FAEL_Form_Elements() {
    return FAEL_Form_Elements::instance();
}