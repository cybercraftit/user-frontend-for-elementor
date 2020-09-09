<?php

class FAEL_Widget_Base extends \Elementor\Widget_Base {

    public static $fael_forms = array();

    public function get_name() {}

    public function get_title() {}

    public function get_icon() {}

    public function get_categories() {}

    protected function _register_controls() {}

    protected function render() {}

    /**
     * @param $handle
     * @param $name
     * @param null $default
     */
    public static function  populate_field( $handle, $name, $default = null, $use_default = false, $type = null, $module = 'post' ) {
        global  $fael_post, $fael_user;

        $fael_forms = FAEL_Form_Elements()->get_form_elements();

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
    }

    /**
     * @param $element
     */
    public function list_fael_widgets( $element ) {
        global $fael_widgets;
        $s = $element->get_settings_for_display();
        $fael_widgets[$element->get_name()][$element->get_id()] = $s;
        //pri($fael_widgets);
    }

    /**
     * @param $handle
     * @param $name
     */
    public function show_errors($handle, $name) {
        if( isset( $_GET['action']) && $_GET['action'] == 'elementor' ) return;
        ?>
        <div v-if="errors['<?php echo $handle; ?>'] && errors['<?php echo $handle; ?>']['<?php echo $name; ?>']" v-cloak>
            <div class="fael_submit_error fael_error" v-for="(err,k) in errors['<?php echo $handle; ?>']['<?php echo $name; ?>']">
                {{ err }}
            </div>
        </div>
<?php
    }
}