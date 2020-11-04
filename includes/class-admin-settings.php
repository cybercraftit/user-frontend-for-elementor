<?php

/**
 * Settings for User Frontend for Elementor
 *
 * Class FAEL_Admin_Settings
 */
class FAEL_Admin_Settings {

    /**
     * Settings API
     *
     * @var \FAEL_Settings_API
     */
    private $settings_api;

    /**
     * Static instance of this class
     *
     * @var \self
     */
    private static $_instance;

    /**
     * public instance of this class
     *
     * @var \self
     */
    public $subscribers_list_table_obj;

    /**
     * The menu page hooks
     *
     * Used for checking if any page is under FAEL menu
     *
     * @var array
     */
    private $menu_pages = array();

    public function __construct() {

        if ( ! class_exists( 'FAEL_Settings_API' ) ) {
            require_once 'class-settings-api.php';
        }

        $this->settings_api = new FAEL_Settings_API();

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Register the admin menu
     *
     * @since 1.0
     */
    function admin_menu() {

        $capability = apply_filters( 'fael_admin_role', 'manage_options' );

        // Translation issue: Hook name change due to translate menu title
        add_submenu_page( 'edit.php?post_type=fael_form', __( 'Settings', 'fael' ), __( 'Settings', 'fael' ), $capability, 'fael-settings', array( $this, 'settings_page' ) );

        do_action( 'fael_admin_menu', $capability );

        if( !FAEL_Functions()->is_pro() ) {
            add_submenu_page( 'edit.php?post_type=fael_form', __( 'Go Pro', 'fael' ), __( '<span style="color: #ff0000;
    font-weight: bold;">Go Pro</span>', 'fael' ), $capability, 'fael-go-pro', array( $this, 'go_pro' ) );
        }
        add_submenu_page( 'edit.php?post_type=fael_form', __( 'Help', 'fael' ), __( '<span style="color: #d54e21">Help</span>', 'fael' ), $capability, 'fael-help', array( $this, 'help' ) );
    }

    public function go_pro() {
        include_once FAEL_ROOT . '/views/admin/go-pro.php';
    }

    public function help() {
        include_once FAEL_ROOT . '/views/admin/help.php';
    }



    /**
     * FAEL Settings sections
     *
     * @since 1.0
     * @return array
     */
    function get_settings_sections() {
        return FAEL_Settings_Options()->get_settings_sections();
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        return FAEL_Settings_Options()->get_settings_fields();
    }

    function settings_page() {
        ?>
        <div class="wrap">

            <h2 style="margin-bottom: 15px;"><?php _e( 'Settings', 'fael' ) ?></h2>
            <div class="wpuf-settings-wrap">
                <?php
                settings_errors();

                $this->settings_api->show_navigation();
                $this->settings_api->show_forms();
                ?>
            </div>
        </div>
        <?php
    }



    /**
     * Check if the current page is a settings/menu page
     *
     * @param  string  $screen_id
     *
     * @return boolean
     */
    public function is_admin_menu_page( $screen ) {
        if ( $screen && in_array( $screen->id, $this->menu_pages ) ) {
            return true;
        }

        return false;
    }

}

function FAEL_Admin_Settings() {
    return FAEL_Admin_Settings::init();
}

FAEL_Admin_Settings();