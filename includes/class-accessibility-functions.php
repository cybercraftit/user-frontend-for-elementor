<?php

Class FAEL_Accessibility_Functions {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * All actions and filters added in
     * Initialization of this class
     * FAEL_Accessibility_Functions constructor.
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'wp_admin_access_check' ), 100 );
        add_action('after_setup_theme', array( $this, 'admin_bar_visibility' ) );
    }

    public function admin_bar_visibility() {
        $redirect = !is_admin() &&  isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
        $admin_roles = FAEL_Functions()->get_option( 'fael_general', 'admin_access' );
        !is_array( $admin_roles ) ? $admin_roles = array() : '';

        if( empty( array_intersect( $admin_roles, wp_get_current_user()->roles ) ) )
            show_admin_bar(false);
    }

    public function wp_admin_access_check() {
        $redirect = !is_admin() &&  isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
        $admin_roles = FAEL_Functions()->get_option( 'fael_general', 'admin_access' );
        !is_array( $admin_roles ) ? $admin_roles = array() : '';

        if( empty( array_intersect( $admin_roles, wp_get_current_user()->roles ) ) ) {
            exit( wp_redirect( $redirect ) );
        }

    }

    /**
     * Return content based on
     * Checking accessibility
     *
     * @param $content
     * @param $widget
     */
    public function is_widget_accessible( $content, $widget ) {
    }
    /**
     * @param $s
     * @return bool
     */
    public function check_widget_accessibility( $s) {

        if( isset( $s['fael_accessibility'] ) ) {

            if( $s['fael_accessibility'] == 'all' ) return true;

            if( $s['fael_accessibility'] == 'logged_in' ) {

                if( !is_user_logged_in() ) return false;

                if( $s['fael_access_by_role'] == 'yes' ) {

                    if(  !empty( array_intersect(get_userdata(get_current_user_id())->roles, $s['fael_accessible_roles'] ) ) ) {
                        return true;
                    }
                    return false;
                }
                return true;
            }
        }
        return true;
    }

}

function FAEL_Accessibility_Functions() {
    return FAEL_Accessibility_Functions::instance();
}

FAEL_Accessibility_Functions();