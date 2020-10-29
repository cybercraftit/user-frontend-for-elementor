<?php

Class FAEL_Functions {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
    }

    /**
     * Retrieve or display list of posts as a dropdown (select list).
     *
     * @param string $post_type
     * @return array
     */
    function get_pages( $post_type = 'page' ) {
        global $wpdb;

        $array = array( '' => __( '-- select --', 'wp-user-frontend' ) );
        $pages = get_posts( array('post_type' => $post_type, 'numberposts' => -1) );
        if ( $pages ) {
            foreach ($pages as $page) {
                $array[$page->ID] = esc_attr( $page->post_title );
            }
        }

        return $array;
    }

    /**
     * Get lists of users from database
     *
     * @return array
     */
    function list_users() {
        global $wpdb;

        $users = $wpdb->get_results( "SELECT ID, user_login from $wpdb->users" );

        $list = array();

        if ( $users ) {
            foreach ($users as $user) {
                $list[$user->ID] = $user->user_login;
            }
        }

        return $list;
    }

    public function is_pro() {
        //if( file_exists( FAEL_ROOT.'/pro/loader.php' ) )
        if( defined( 'FAEL_PRO') ) return true;
        return false;
    }

    public function get_post_edit_url($post_id) {
        global $wp;
        return add_query_arg( array( 'fael_action' => 'edit',  'id' => $post_id, 'module' => 'post' ), $wp->request );
    }

    /**
     * Return id of edit page
     *
     * @param $id
     * @param null $module
     * @return mixed|string
     */
    public function get_item_edit_page_id( $id, $module = null ) {
        if( !$module || $module == 'post' ) {
            //return edit page id saved in settings
            if( $edit_page_id = FAEL_Functions()->get_option('fael_frontend_posting', 'post_edit_page_id' ) ) {
                return $edit_page_id;
            }
            //Or return saved page id from created post
            return get_post_meta( $id, '__fael_form_page_id', true );
        } elseif ( $module == 'user' ) {
            if( $edit_page_id = FAEL_Functions()->get_option('fael_frontend_posting', 'user_edit_page_id' ) ) {
                return $edit_page_id;
            }
            return get_user_meta( $id, '__fael_form_page_id', true );
        } elseif ( $module == 'taxonomy' ) {
            if( $edit_page_id = FAEL_Functions()->get_option('fael_frontend_posting', 'tax_edit_page_id' ) ) {
                return $edit_page_id;
            }
            return get_term_meta( $id, '__fael_form_page_id', true );
        }
    }

    public function get_post_delete_url( $post_id ) {
        global $wp;
        return add_query_arg( array( 'fael_action' => 'delete',  'id' => $post_id, 'module' => 'post' ), $wp->request );
    }

    public function get_post_change_status_url( $post_id, $status ) {
        global $wp;
        return add_query_arg( array( 'fael_action' => 'change_status','status' => $status,  'id' => $post_id, 'module' => 'post' ), $wp->request );
    }

    public function get_post_view_url($post_id) {
        return get_permalink($post_id);
    }

    public function get_user_edit_url($user_id) {
        global $wp;
        return add_query_arg( array( 'fael_action' => 'edit',  'id' => $user_id, 'module' => 'user' ), $wp->request );
    }
    public function get_user_delete_url( $user_id ) {
        global $wp;
        return add_query_arg( array( 'fael_action' => 'delete',  'id' => $user_id, 'module' => 'user' ), $wp->request );
    }
    public function get_user_view_url($user_id) {
        return true;
        return get_permalink($user_id);
    }

    public function get_item_edit_url( $item_id, $module = null ) {
        global $wp;
        switch ( $module ) {
            case 'post':
                return add_query_arg( array( 'fael_action' => 'edit',  'id' => $item_id, 'module' => 'post' ), $wp->request );
                break;
            case 'user':
                return add_query_arg( array( 'fael_action' => 'edit',  'id' => $item_id, 'module' => 'user' ), $wp->request );
                break;
            case 'taxonomy':
                return add_query_arg( array( 'fael_action' => 'edit',  'id' => $item_id, 'module' => 'taxonomy' ), $wp->request );
                break;
        }
    }
    public function get_item_delete_url( $item_id, $module = null ) {
        global $wp;
        switch ( $module ) {
            case 'post':
                return add_query_arg( array( 'fael_action' => 'delete',  'id' => $item_id, 'module' => 'post' ), $wp->request );
                break;
            case 'user':
                return add_query_arg( array( 'fael_action' => 'delete',  'id' => $item_id, 'module' => 'user' ), $wp->request );
                break;
            case 'taxonomy':
                return add_query_arg( array( 'fael_action' => 'delete',  'id' => $item_id, 'module' => 'taxonomy' ), $wp->request );
                break;
        }
    }

    public function can_edit_item( $item, $module = 'post' ) {
        switch ( $module ) {
            case 'post':
                if( get_post_meta( $item->ID, '__fael_can_edit_post', true )  ) {
                    if( $item->post_author == get_current_user_id() ) return true;
                }
                break;
            case 'user':
                if( get_user_meta( $item->ID, '__fael_can_edit_user', true )  ) {
                    if( $item->ID == get_current_user_id() ) return true;
                }
                break;
            case 'taxonomy':
                if( get_term_meta(  $item->term_id, '__fael_can_edit_term', true )  ) {
                    if( $item->__fael_term_author == get_current_user_id() ) return true;
                }
                break;
        }
    }

    public function can_delete_item( $item, $module = 'post' ) {
        switch ( $module ) {
            case 'post':
                if( get_post_meta( $item->ID, '__fael_can_delete_post', true )  ) {
                    if( $item->post_author == get_current_user_id() ) return true;
                }
                break;
            case 'user':
                if( get_user_meta( $item->ID, '__fael_can_delete_user', true )  ) {
                    if( $item->ID == get_current_user_id() ) return true;
                }
                break;
            case 'taxonomy':
                if( get_term_meta(  $item->term_id, '__fael_can_delete_term', true )  ) {
                    if( $item->__fael_term_author == get_current_user_id() ) return true;
                }
                break;
        }
    }

    public function get_item_view_url( $item_id, $module = null ) {
        return true;
        return get_permalink($user_id);
    }

    /**
     * @param $section
     * @param null $option
     * @param string $default
     * @return string
     */
    function get_option( $section, $option = null, $default = '' ) {

        $options = get_option( $section );

        if( !$option ) {
            return $options;
        }

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }

    function set_option( $section, $value ) {
        update_option( $section, $value );
    }

    function add_cpt_support() {
        $cpt_support = get_option( 'elementor_cpt_support', [ 'page', 'post' ] );
        $cpt_support[] = 'fael_form';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

}

function FAEL_Functions() {
    return FAEL_Functions::instance();
}

FAEL_Functions();