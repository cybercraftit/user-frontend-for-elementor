<?php

class FAEL_Login {

    private static $_instance = null;
    protected $login_url;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_filter( 'fael_form_submit_types', [ $this, 'add_submit_type' ]);
        add_action( 'fael_create_item', [ $this, 'process' ], 10, 3 );
        add_filter( 'fael-after_create_item-conditions', [ $this, 'modify_after_create_item_conditions'], 10, 2 );
        add_filter( 'fael_settings_sections', [ $this, 'add_new_settings_section' ] );
        add_filter( 'fael_settings_fields', [ $this, 'add_settings_fields' ], 10, 2 );
        add_filter( 'login_url', [ $this, 'change_url' ] );
        // Hook the appropriate WordPress action
        add_action('init', [ $this, 'prevent_wp_login' ] );
        add_filter( 'ufel_after_form_restriction_filter', [ $this, 'show_login'], 10, 3 );
        add_filter( 'form_submit-check_widget_accessibility', [ $this, 'allow_form_submit' ], 10, 2 );
    }

    /**
     * @param $bool
     * @param $form_settings
     * @return bool
     */
    public function allow_form_submit( $bool, $form_settings ) {
        if( $form_settings['submit_type'] == 'login_form' ) {
            $bool = true;
        }
        return $bool;
    }

    public function show_login( $bool, $accessibility, $page_settings ) {
        if( isset( $page_settings['submit_type'] ) && $page_settings['submit_type'] == 'login_form' ) $bool = true;
        return $bool;
    }

    public function prevent_wp_login() {
        $id = FAEL_Functions()->get_option( 'fael_login_reg', 'login_page_id' );
        if ( !$id ) {
            return;
        }
        $this->login_url = get_permalink( $id );

        // WP tracks the current page - global the variable to access it
        global $pagenow;
        // Check if a $_GET['action'] is set, and if so, load it into $action variable
        $action = (isset($_GET['action'])) ? $_GET['action'] : '';
        // Check if we're on the login page, and ensure the action is not 'logout'
        if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass', 'register'))))) {
            //get_template_part( 404 );
            wp_redirect($this->login_url);
            exit;
        }
    }

    public function change_url( $login_url ) {
        if( $this->login_url ) {
            return $this->login_url;
        }
        return $login_url;
    }

    /**
     * @param $submit_types
     */
    public function add_submit_type( $submit_types ) {
        $submit_types['login_form'] = __( 'Login Form', 'fael' );
        return $submit_types;
    }

    /**
     * @param $conditions
     * @param $field_name
     * @return array
     */
    public function modify_after_create_item_conditions( $conditions, $field_name ) {
        $conditions[] = [
            'name' => 'submit_type',
            'operator' => '==',
            'value' => 'login_form',
        ];
        return $conditions;
    }

    /**
     * @param $data
     * @param $current_form
     * @param $form_settings
     */
    public function process( $data, $current_form, $form_settings ) {
        if( !is_wp_error( $this->authenticate_user( $data, $current_form ) ) ) {
            //set system meta
            do_action( 'fael_after_user_login', $data, $current_form );

            $url = '';

            if( isset( $form_settings['after_create_item'] ) ) {

                switch ( $form_settings['after_create_item'] ) {
                    case 'redirect_url':
                        $url = isset( $form_settings['create_item_redirect_url']['url'] ) ? $form_settings['create_item_redirect_url']['url'] : '';
                        break;
                    case 'to_page':
                        if( $form_settings['create_item_redirect_page'] ) {
                            $url = get_permalink($form_settings['create_item_redirect_page']);
                        }
                        break;
                }
            }

            $return_data = array(
                'msg' => __( 'User logged in successfully', 'fael' )
            );

            if( $url ) {
                $return_data['redirect'] = $url;
            }

            wp_send_json_success($return_data);
        }
    }

    /**
     * @param $data
     * @param $current_form
     * @return WP_Error|WP_User
     */
    public function authenticate_user ( $data, $current_form ) {
        $creds = [];
        if( isset( $data['user_login'] ) ) {
            $creds['user_login'] = $data['user_login'];
        }
        if( isset( $data['user_email'] ) ) {
            $creds['user_email'] = $data['user_email'];
        }
        if ( isset( $data['password'] ) ) {
            $creds['user_password'] = $data['password'];
        }

        /*$creds = [
            'user_login'    => 'admin',
            'user_password' => 'admin',
            'remember'      => true
        ];*/
        return wp_signon( $creds );
    }

    /**
     * Add login and registration section
     * @param $sections
     * @return array
     */
    public function add_new_settings_section( $sections ) {
        $sections[] = array(
            'id'    => 'fael_login_reg',
            'title' => __( 'Login and Registration', 'fael' ),
            'icon' => 'dashicons-user'
        );
        return $sections;
    }

    /**
     * @param $settings_fields
     * @param $data
     * @return mixed
     */
    public function add_settings_fields( $settings_fields, $data ) {
        $settings_fields['fael_login_reg'][] = array(
            'name'    => 'login_page_id',
            'label'   => __( 'Login Page', 'fael' ),
            'desc'    => __( 'Select the page which will be considered as login page.', 'fael' ),
            'type'    => 'select',
            'options' => $data['pages']
        );
        return $settings_fields;
    }


}

function FAEL_Login() {
    return FAEL_Login::instance();
}

FAEL_Login();