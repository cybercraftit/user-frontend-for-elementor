<?php

class FAEL_Registration {

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
        add_filter( 'fael_settings_fields', [ $this, 'add_settings_fields' ], 10, 2 );
        add_filter( 'site_url', [ $this, 'change_url' ], 10, 3 );
        add_filter('register', [ $this, 'change_reg_url']);
    }

    /**
     * @param $submit_types
     */
    public function add_submit_type( $submit_types ) {
        $submit_types['reg_form'] = __( 'Registration Form', 'fael' );
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
            'value' => 'reg_form',
        ];
        return $conditions;
    }

    /**
     * @param $settings_fields
     * @param $data
     * @return mixed
     */
    public function add_settings_fields( $settings_fields, $data ) {
        $settings_fields['fael_login_reg'][] = array(
            'name'    => 'reg_page_id',
            'label'   => __( 'Registration Page', 'fael' ),
            'desc'    => __( 'Select the page which will be considered as registration page.', 'fael' ),
            'type'    => 'select',
            'options' => $data['pages']
        );
        return $settings_fields;
    }

    /**
     * @param $url
     * @param $path
     * @param $orig_scheme
     * @return false|string
     */
    public function change_url( $url, $path, $orig_scheme ) {
        /*
        Site URL hack to overwrite register url
        http://en.bainternet.info/2012/wordpress-easy-login-url-with-no-htaccess
        */
        if ($orig_scheme !== 'login')
            return $url;

        if ($path == 'wp-login.php?action=register'){
            $id = FAEL_Functions()->get_option( 'fael_login_reg', 'reg_page_id' );
            if ( $id ) {
                $url = get_permalink( $id );
            }
        }

        return $url;
    }

    protected function change_reg_url( $link ) {
        $id = FAEL_Functions()->get_option( 'fael_login_reg', 'reg_page_id' );
        if ( $id ) {
            $link = get_permalink( $id );
        }
        return '<a href="'.$link.'">'.__( 'Register', 'fael' ).'</a>';
    }
}

function FAEL_Registration() {
    return FAEL_Registration::instance();
}

FAEL_Registration();