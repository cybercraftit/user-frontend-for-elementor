<?php

Class FAEL_Page_Frontend {

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'set_editable_post' ) );
    }

    public function set_editable_post() {
        global $fael_post, $wp;

        if( isset( $_GET['fael_action'] ) ) {

            if( $_GET['fael_action'] == 'edit' ) {
                //get form page from post meta
                $form_page_id = null;

                switch ( $_GET['module'] ) {
                    case 'post':
                        $form_page_id = FAEL_Functions()->get_item_edit_page_id( $_GET['id'], 'post' );
                         break;
                    case 'user':
                        $form_page_id = FAEL_Functions()->get_item_edit_page_id( $_GET['id'], 'user' );
                        break;
                    case 'taxonomy':
                        $form_page_id = FAEL_Functions()->get_item_edit_page_id( $_GET['id'], 'taxonomy' );
                        break;
                }

                if( $form_page_id ) {
                    wp_redirect( add_query_arg( array( 'fael_object' => ( isset( $_GET['module'] ) ? $_GET['module'] : 'post' ), 'fael_edit_id' => $_GET['id'] ), get_permalink( $form_page_id ) ) );
                    exit;
                }
            } elseif ( $_GET['fael_action'] == 'delete' ) {
                //check if it is post author
                switch ( $_GET['module'] ) {
                    case 'post':
                        $fael_post = get_post($_GET['id']);

                        if( !$fael_post ) return;
                        if( get_current_user_id() != $fael_post->post_author ) return false;

                        //check from form settings if post can  be deleted
                        if( $fael_post->__fael_can_delete_post == 'yes' ) {
                            wp_trash_post($_GET['id']);
                            wp_redirect(remove_query_arg( array( 'id', 'fael_action', 'module' ), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
                            exit;
                        };
                        break;
                    case 'user':
                        $fael_post = get_user_by('ID', $_GET['id']);

                        if( !$fael_post ) return;

                        //check from form settings if post can  be deleted
                        if( $fael_post->__fael_can_delete_user == 'yes' ) {
                            wp_delete_user($_GET['id']);
                            wp_redirect(remove_query_arg( array( 'id', 'fael_action', 'module' ), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
                            exit;
                        };
                        break;
                }
            } elseif ( $_GET['fael_action'] == 'draft' ) {

                //check if it is post author
                $fael_post = get_post($_GET['post_id']);

                if( !$fael_post ) return;
                if( get_current_user_id() != $fael_post->post_author ) return false;

                //check from form settings if post can  be drafted
                if( $fael_post->__fael_can_draft_post == 'yes' ) {
                    wp_update_post(array(
                        'ID' => $_GET['post_id'],
                        'post_status' => 'draft'
                    ));
                    wp_redirect(remove_query_arg( array( 'id', 'fael_action' ), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
                    exit;
                };
            }
        }

        if( isset( $_GET['fael_object'] ) ) {

            if( $_GET['fael_object'] == 'post' ) {
                $fael_post = get_post( $_GET['fael_edit_id'] );
            } else if ( $_GET['fael_object'] == 'user' ) {
                $fael_post = get_user_by( 'ID', $_GET['fael_edit_id'] );
            } else if ( $_GET['fael_object'] == 'taxonomy' ) {
                $fael_post = get_term( $_GET['fael_edit_id'] );
            }
        }
    }


    /**
     * @param $post_id
     * @param $content
     * @return string|void
     */
    public function form_restriction_filter( $post_id, $content, $is_shortcode = true ) {

        $error = null;
        $is_okay = 0;
        $accessibility = FAEL_Page_Settings()->get_page_settings( $post_id, 'fael_page_accessability' );
        $page_settings = FAEL_Page_Settings()->get_page_settings( $post_id );

        if( $accessibility == 'logged_in' ) {
            if( is_user_logged_in() ) {
                if( FAEL_Page_Settings()->get_page_settings( $post_id, 'fael_page_access_by_role' ) == 'yes' ) {
                    $accessible_roles = FAEL_Page_Settings()->get_page_settings( $post_id, 'fael_page_accessible_roles' );
                    !is_array( $accessible_roles ) ? $accessible_roles = array() : '';
                    if( !empty( array_intersect(get_userdata(get_current_user_id())->roles, $accessible_roles ) ) ) {
                        $is_okay = 1;
                    }
                } else {
                    $is_okay = 1;
                    //return $content;
                }
            }
        }

        $is_okay = apply_filters( 'ufel_after_form_restriction_filter', $is_okay, $accessibility, $page_settings );

        if( !$is_okay ) {
            return $is_okay;
        }

        return apply_filters( 'form_return_content_after_restriction_filter', $content, $is_okay, $accessibility, $page_settings );
    }


    /**
     * @param $page_id
     * @param null $handle
     * @return mixed|void
     */
    public function get_page_forms( $page_id, $handle = null ) {
        if ( $handle ) {
            $fael_forms = get_post_meta( $page_id, 'fael_forms', true );
            if( $fael_forms ) {
                if ( isset( $fael_forms[$handle] ) ) {
                    return $fael_forms[$handle];
                } else {
                    return;
                }
            }
        }
        return get_post_meta( $page_id, 'fael_forms', true );
    }

}

function FAEL_Page_Frontend() {
    return FAEL_Page_Frontend::instance();
}

FAEL_Page_Frontend();