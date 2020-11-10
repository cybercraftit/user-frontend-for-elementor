<?php

class FAEL_Registration {

    private static $_instance = null;
    protected $reg_url = null;

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
        add_action( 'init', [ $this, 'change_url' ], 10, 3 );
        add_filter('register', [ $this, 'change_reg_link']);
        add_filter( 'ufel_after_form_restriction_filter', [ $this, 'show_reg'], 10, 3 );
        add_filter( 'form_submit-check_widget_accessibility', [ $this, 'allow_form_submit' ], 10, 2 );
    }

    /**
     * @param $bool
     * @param $form_settings
     * @return bool
     */
    public function allow_form_submit( $bool, $form_settings ) {
        if( $form_settings['submit_type'] == 'reg_form' ) {
            $bool = true;
        }
        return $bool;
    }

    /**
     * @param $bool
     * @param $accessibility
     * @return bool
     */
    public function show_reg( $bool, $accessibility, $page_settings ) {
        if( isset( $page_settings['submit_type'] ) && $page_settings['submit_type'] == 'reg_form' ) $bool = true;
        return $bool;
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
    public function change_url() {
        $id = FAEL_Functions()->get_option( 'fael_login_reg', 'reg_page_id' );
        if ( !$id ) {
            return;
        }

        $this->reg_url = get_permalink( $id );

        global $pagenow;
        $action = (isset($_GET['action'])) ? $_GET['action'] : '';

        // Check if we're on the login page, and ensure the action is not 'logout'
        if( $pagenow == 'wp-login.php' && ( $action && in_array($action, array('register' ) ) ) ) {
            //get_template_part( 404 );
            wp_redirect($this->reg_url);
            exit;
        }
    }

    public function change_reg_link( $link ) {
        if ( $this->reg_url ) {
            $link = $this->reg_url;
            return '<a href="'.$link.'">'.__( 'Register', 'fael' ).'</a>';
        }
        return $link;
    }

    public function process( $data, $current_form, $form_settings ) {
        $ret = FAEL_Ajax()->create_user( $data, $current_form );

        //user is registered
        if( isset( $ret['item_id'] ) ) {
            $item_id = $ret['item_id'];
            $postdata = $ret['postdata'];


            /**
             * Set form settings
             */
            $form_settings = $current_form['form_settings'];

            //set system meta
            do_action( 'fael_after_user_register', $item_id, $postdata, $current_form );

            //set redirection
            $url = '';
            if( isset( $form_settings['after_create_item'] ) ) {

                switch ( $form_settings['after_create_item'] ) {
                    case 'redirect_url':
                        $url = isset( $form_settings['create_item_redirect_url']['url'] ) ? $form_settings['create_item_redirect_url']['url'] : '';
                        break;
                    case 'redirect_edit_item':
                        $url = FAEL_Functions()->get_user_edit_url($item_id);
                        break;
                    case 'view_post':
                        $url = FAEL_Functions()->get_user_view_url($item_id);
                        break;
                    case 'to_page':
                        if( $form_settings['create_item_redirect_page'] ) {
                            $url = get_permalink($form_settings['create_item_redirect_page']);
                        }
                        break;
                }
            }

            $return_data = array(
                'msg' => __( 'User registered successfully', 'fael' )
            );

            if( $url ) {
                $return_data['redirect'] = $url;
            }

            wp_send_json_success($return_data);
        }
    }
}

function FAEL_Registration() {
    return FAEL_Registration::instance();
}

FAEL_Registration();