<?php

class FAEL_Form {

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
     * @return FAEL_Form An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'register_forms' ) );
    }

    public function register_forms() {

        $labels = array(
            'name'                  => _x( 'Forms', 'Post Type General Name', 'fael' ),
            'singular_name'         => _x( 'Form', 'Post Type Singular Name', 'fael' ),
            'menu_name'             => __( 'User Frontend for Elementor', 'fael' ),
            'name_admin_bar'        => __( 'Form', 'fael' ),
            'archives'              => __( 'Form Archives', 'fael' ),
            'attributes'            => __( 'Form Attributes', 'fael' ),
            'parent_item_colon'     => __( 'Parent Form:', 'fael' ),
            'all_items'             => __( 'All Forms', 'fael' ),
            'add_new_item'          => __( 'Add New Form', 'fael' ),
            'add_new'               => __( 'Add New', 'fael' ),
            'new_item'              => __( 'New Form', 'fael' ),
            'edit_item'             => __( 'Edit Form', 'fael' ),
            'update_item'           => __( 'Update Form', 'fael' ),
            'view_item'             => __( 'View Form', 'fael' ),
            'view_items'            => __( 'View Forms', 'fael' ),
            'search_items'          => __( 'Search Form', 'fael' ),
            'not_found'             => __( 'Not found', 'fael' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'fael' ),
            'featured_image'        => __( 'Featured Image', 'fael' ),
            'set_featured_image'    => __( 'Set featured image', 'fael' ),
            'remove_featured_image' => __( 'Remove featured image', 'fael' ),
            'use_featured_image'    => __( 'Use as featured image', 'fael' ),
            'insert_into_item'      => __( 'Insert into item', 'fael' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'fael' ),
            'items_list'            => __( 'Forms list', 'fael' ),
            'items_list_navigation' => __( 'Forms list navigation', 'fael' ),
            'filter_items_list'     => __( 'Filter items list', 'fael' ),
        );
        $args = array(
            'label'                 => __( 'Form', 'fael' ),
            'description'           => __( 'Form Description', 'fael' ),
            'labels'                => $labels,
            /*'supports'              => false,*/
            //'taxonomies'            => array( 'category' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'fael_form', $args );
    }
}

FAEL_Form::instance();
