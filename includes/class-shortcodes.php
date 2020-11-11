<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

final class FAEL_Shortcode {

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     */
    private static $_instance = null;

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
     * @return FAEL_Shortcode An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public function __construct() {
        add_shortcode( 'fael_form', array( $this, 'form_handler' ));
    }

    public function form_handler( $atts, $content ) {
        global  /*$fael_post,*/ $ufe_vueobject;

        $a = shortcode_atts(
            array(
                'id' => null,
            ),
            $atts
        );

        if( !$a['id'] ) return;
        $form_post = get_post( $a['id'] );

        $fael_forms = FAEL_Page_Frontend()->get_page_forms( $form_post->ID );
        FAEL_Form_Elements()->set_form_elements( $fael_forms );

        $this->populate_form_fields();

        $form_settings = [];

        foreach ( $fael_forms as $handle => $fael_form ) {
            if( !isset( $fael_form['form_settings'] ) ) continue;
            $form_settings[$handle] = $fael_form['form_settings'];
        }

        ob_start();
        $pluginElementor = \Elementor\Plugin::instance();
        $contentElementor = $pluginElementor->frontend->get_builder_content($form_post->ID);

        $vue_id = 'ufe_vueapp-'.rand(1,1000).'-'.$form_post->ID;
        ?>
        <div id="<?php echo $vue_id; ?>">
            <?php
            $content = FAEL_Page_Frontend()->form_restriction_filter( $form_post->ID, $contentElementor, true );
            if( $content ) {
                echo $content;
            } else {
                _e( 'You do not have access to this page', 'fael' );
            }
            ?>
        </div>
        <?php
        $ufe_vueobject[$vue_id] = [];
        return ob_get_clean();
    }

    public function populate_form_fields() {return;
        global $fael_post, $fael_forms;

        $fael_forms = FAEL_Form_Elements()->get_form_elements();

        foreach ( $fael_forms as $handle => $fael_form ) {
            foreach ( $fael_form as $field => $field_data ) {
                if( $field == 'form_settings' ) continue;
                $field_data['widget']::populate_field( $handle, $field, $field_data['value'] );
            }
        }
    }

}

function FAEL_Shortcode() {
    return FAEL_Shortcode::instance();
}

FAEL_Shortcode();
