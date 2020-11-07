<?php

class FAEL_Login {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_filter( 'fael_form_submit_types', [ $this, 'add_login_submit_type' ]);
        add_action( 'fael_create_item', [ $this, 'process_login' ], 10, 3 );
        add_filter( 'fael-after_create_item-conditions', [ $this, 'modify_after_create_item_conditions'], 10, 2 );
    }

    /**
     * @param $submit_types
     */
    public function add_login_submit_type( $submit_types ) {
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
    public function process_login( $data, $current_form, $form_settings ) {
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


}

function FAEL_Login() {
    return FAEL_Login::instance();
}

FAEL_Login();