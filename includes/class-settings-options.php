<?php

class FAEL_Settings_Options {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'fael_general',
                'title' => __( 'General Options', 'fael' ),
                'icon' => 'dashicons-admin-generic'
            ),
            array(
                'id'    => 'fael_frontend_posting',
                'title' => __( 'Frontend Posting', 'fael' ),
                'icon' => 'dashicons-welcome-write-blog'
            )
        );

        return apply_filters( 'fael_settings_sections', $sections );
    }

    public function get_settings_fields() {

        $pages = FAEL_Functions()->get_pages();
        $users = FAEL_Functions()->list_users();
        $post_types = get_post_types();

        $login_redirect_pages =  array(
                'previous_page' => __( 'Previous Page', 'fael' )
            ) + $pages;

        $user_roles = array();
        $all_roles = get_editable_roles();
        foreach( $all_roles as $key=>$value ) {
            $user_roles[$key] = $value['name'];
        }

        $settings_fields = array(
            'fael_general' => apply_filters( 'fael_options_others', array(

                array(
                    'name' => 'admin_access',/*show_admin_bar*/
                    'label' => __('Who Can Have Admin Access', 'fael'),
                    'desc' => __('Check the roles who you want to have access to wp admin panel', 'fael'),
                    'type' => 'multicheck',
                    'options' => $user_roles,
                    'default' => ['administrator','editor','author']
                ),
                array(
                    'name'  => 'recaptcha_public',
                    'label' => __( 'reCAPTCHA Site Key', 'fael' ),
                ),
                array(
                    'name'  => 'recaptcha_private',
                    'label' => __( 'reCAPTCHA Secret Key', 'fael' ),
                    'desc'  => __( '<a target="_blank" href="https://www.google.com/recaptcha/">Register here</a> to get reCaptcha Site and Secret keys.', 'fael' ),
                )
            ) ),
            'fael_frontend_posting' => apply_filters( 'fael_options_frontend_posting', array(
                array(
                    'name'    => 'post_edit_page_id',
                    'label'   => __( 'Edit Page for Post', 'fael' ),
                    'desc'    => __( 'Select the page which will be considered edit page for post.', 'fael' ),
                    'type'    => 'select',
                    'options' => $pages
                ),
                array(
                    'name'    => 'user_edit_page_id',
                    'label'   => __( 'Edit Page for User', 'fael' ),
                    'desc'    => __( 'Select the page which will be considered edit page for user.', 'fael' ),
                    'type'    => 'select',
                    'options' => $pages
                ),
                array(
                    'name'    => 'tax_edit_page_id',
                    'label'   => __( 'Edit Page for Term of any Taxonomy', 'fael' ),
                    'desc'    => __( 'Select the page which will be considered edit page for term.', 'fael' ),
                    'type'    => 'select',
                    'options' => $pages
                ),
                array(
                    'name'    => 'default_post_owner',
                    'label'   => __( 'Default Post Owner', 'fael' ),
                    'desc'    => __( 'If guest post is enabled and user details are OFF, the posts are assigned to this user', 'fael' ),
                    'type'    => 'select',
                    'options' => $users,
                    'default' => '1'
                ),
                array(
                    'name'    => 'default_post_form',
                    'label'   => __( 'Default Post Form', 'fael' ),
                    'desc'    => __( 'Fallback form for post editing if no associated form found', 'fael' ),
                    'type'    => 'select',
                    'options' => $pages
                ),
            ) ),
            'fael_dashboard' => apply_filters( 'fael_options_dashboard', array(

            ) ),
            'fael_my_account' => apply_filters( 'fael_options_fael_my_account', array(
                array(
                    'name'    => 'account_page',
                    'label'   => __( 'Account Page', 'fael' ),
                    'desc'    => __( 'Select the page which contains <code>[]</code> shortcode', 'fael' ),
                    'type'    => 'select',
                    'options' => $pages
                ),
                array(
                    'name'    => 'show_subscriptions',
                    'label'   => __( 'Show Subscriptions', 'fael' ),
                    'desc'    => __( 'Show Subscriptions tab in "my account" page where <code>[]</code> is located', 'fael' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'show_billing_address',
                    'label' => __( 'Show Billing Address', 'fael' ),
                    'desc'  => __( 'Show billing address in account page.', 'fael' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),
            ) )
        );

        return apply_filters( 'fael_settings_fields', $settings_fields, [
            'pages' => $pages,
            'users' => $users,
            'user_roles' => $user_roles
        ] );
    }
}

function FAEL_Settings_Options() {
    return FAEL_Settings_Options::instance();
}

FAEL_Settings_Options();

