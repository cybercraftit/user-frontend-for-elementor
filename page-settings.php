<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

final class FAEL_Page_Settings {

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
     * @return FAEL_Page_Settings An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public function __construct() {
        add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10);
    }

    public function register_controls( $item ) {

        if( $item->get_post()->post_type == 'fael_form' ) {
            $item->start_controls_section(
                'form_settings_section',
                [
                    'label' => esc_html__('Form Settings', 'essential-addons-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                ]
            );
            do_action( 'fael_page_settings_after-form_settings_section', $item );
            //settings tab
            $item->add_control(
                'submit_type',
                [
                    'label' => __( 'When User Submit Form', 'fael' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'default' => 'create_post',
                    'options' => apply_filters( 'fael_form_submit_types', [
                        'create_post'  => __( 'Create Post', 'plugin-domain' ),
                        'create_user'  => __( 'Create User', 'plugin-domain' ),
                        'create_taxonomy'  => __( 'Create Taxonomy', 'plugin-domain' ),
                    ] ),
                    'description' => __( 'Select what will happen if the user submit form.', 'fael')
                ]
            );

            $post_types = get_post_types( array(
                'public'   => true
            ));

            $item->add_control(
                'post_type',
                [
                    'label' => __( 'Post Type', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'options' => $post_types,
                    'default' => 'post',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                        ],
                    ],
                ]
            );
            $item->add_control(
                'comment_status',
                [
                    'label' => __( 'Comment Status', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'options' => [
                        'close' => __( 'Close', 'fael'),
                        'open' => __( 'Open', 'fael')
                    ],
                    'default' => 'open',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                        ],
                    ],
                ]
            );

            $item->add_control(
                'after_create_item',
                [
                    'label' => __( 'After Submit', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'default' => 'redirect_url',
                    'options' => [
                        'redirect_url' => __( 'Redirect to a URL', 'fael' ),
                        'to_page' => __( 'Redirect to a Page', 'fael' ),
                        'redirect_edit_item' => __( 'Redirect to edit page', 'fael' ),
                        'view_post' => __( 'View created item', 'fael' ),
                    ],
                    'description' => __( 'What to do after the form is submitted successfully.', 'fael' ),
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => apply_filters( 'fael-after_create_item-conditions', [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_user',
                            ],
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_taxonomy',
                            ],
                        ], 'after_create_item')
                    ],
                ]
            );

            //
            $item->add_control(
                'create_item_redirect_url',
                [
                    'label' => __( 'Redirection URL', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::URL,
                    'conditions' => [
                        'terms' => [
                            /*[
                                'name' => 'submit_type',
                                'operator' => 'in',
                                'value' => ['create_post', 'create_user', 'create_taxonomy'],
                            ],*/
                            [
                                'name' => 'after_create_item',
                                'operator' => '==',
                                'value' => 'redirect_url',
                            ],
                        ],
                    ],
                ]
            );
            //
            $page_list = get_posts(array(
                'post_type' => 'page',
                'post_status' => 'publish'
            ));
            $pages = [];

            foreach ( $page_list as $k => $each ) {
                $pages[$each->ID] = $each->post_title;
            }

            $item->add_control(
                'create_item_redirect_page',
                [
                    'label' => __( 'Page to redirect to', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'options' => $pages,
                    'description' => __( 'Select the page that the user will be redirect to after the post created', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            /*[
                                'name' => 'submit_type',
                                'operator' => 'in',
                                'value' => ['create_post', 'create_user', 'create_taxonomy' ],
                            ],*/
                            [
                                'name' => 'after_create_item',
                                'operator' => '==',
                                'value' => 'to_page',
                            ],
                        ],
                    ],
                ]
            );
            $item->add_control(
                'can_edit_post',
                [
                    'label' => __( 'Can User Edit Post ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'Checking this will allow the post creator to edit the created post', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                        ],
                    ],
                ]
            );
            $item->add_control(
                'can_delete_post',
                [
                    'label' => __( 'Can User Delete Post ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'Checking this will allow the post creator to delete the created post', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                        ],
                    ],
                ]
            );
            $item->add_control(
                'can_draft_post',
                [
                    'label' => __( 'Can User Draft Post ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'Checking this will allow the post creator to draft the post', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                        ],
                    ],
                ]
            );
            $item->add_control(
                'post_needs_review',
                [
                    'label' => __( 'Created Post Need Review ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'If this is checked, the created post will need review before being published', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                        ],
                    ],
                ]
            );

            $post_statuses = get_post_statuses();
            $post_statuses['default'] = __( 'Default', 'fael' );

            $item->add_control(
                'status',
                [
                    'label' => __( 'Post Status', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'options' => $post_statuses,
                    'default' => 'publish',
                    'description' => __( 'Status for created post', 'fael'),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_post',
                            ],
                        ],
                    ],
                ]
            );

            //for user
            $item->add_control(
                'can_edit_user',
                [
                    'label' => __( 'Can User Edit User ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'Checking this will allow the post creator to edit the created user', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_user',
                            ],
                        ],
                    ],
                ]
            );
            $item->add_control(
                'can_delete_user',
                [
                    'label' => __( 'Can User Delete User ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'Checking this will allow the post creator to delete the created post', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_user',
                            ],
                        ],
                    ],
                ]
            );

            //for category
            //for user
            $item->add_control(
                'can_edit_term',
                [
                    'label' => __( 'Can User Edit Term ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'Checking this will allow the post creator to edit the created user', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_taxonomy',
                            ],
                        ],
                    ],
                ]
            );
            $item->add_control(
                'can_delete_term',
                [
                    'label' => __( 'Can User Delete Term ?', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fael' ),
                    'label_off' => __( 'No', 'fael' ),
                    'return_value' => 'yes',
                    'default' => 'No',
                    'description' => __( 'Checking this will allow the post creator to delete the created post', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => '==',
                                'value' => 'create_taxonomy',
                            ],
                        ],
                    ],
                ]
            );
            do_action( 'fael_page_settings_before-form_settings_section', $item );
            $item->end_controls_section();
        }

        $item->start_controls_section(
            'fael_page_settings_section',
            [
                'label' => esc_html__('Accessibility', 'essential-addons-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );

        $item->add_control(
            'fael_page_accessability',
            [
                'label' => __( 'Who Can Access This Page', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => apply_filters( 'fael_page_settings-control_options', [
                    'logged_in' => __( 'Logged in users', 'fael' )
                ], 'fael_page_accessability' ),
                //'default' => 'logged_in'
            ]
        );
        $item->add_control(
            'fael_page_access_by_role',
            [
                'label' => __( 'Access by Role', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'fael' ),
                'label_off' => __( 'No', 'fael' ),
                'return_value' => 'yes',
                'default' => 'No',
                'descriptioon' => __( 'Check if you want users with specific roles to have this page access, if switch if off, all logged in users will be
                able to access this page', 'fael' ),
                'condition' => [
                    'fael_page_accessability' => 'logged_in'
                ]
            ]
        );

        global $wp_roles;
        $roles_opts = [];

        foreach ( $wp_roles->roles as $name => $role ) {
            $roles_opts[$name] = $role['name'];
        }


        $item->add_control(
            'fael_page_accessible_roles',
            [
                'label' => __( 'Roles', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $roles_opts,
                'description' => __( 'Users with the roles checked will have the access to this page.', 'fael' ),
                'condition' => [
                    'fael_page_accessability' => 'logged_in',
                    'fael_page_access_by_role' => 'yes'
                ]
            ]
        );
        $item->end_controls_section();
        do_action( 'fael_page_controls_sections_end', $item );
    }

    /**
     * Returns page settings
     *
     * @param $post_id
     * @param $settings_name
     * @return mixed
     */
    function get_page_settings( $post_id, $settings_name = null ) {
        static $page_settings_model;

        if( !isset( $page_settings_model[$post_id] ) ) {
            // Get the page settings manager
            $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

            // Get the settings model for current post
            $page_settings_model[$post_id] = $page_settings_manager->get_model( $post_id )->get_settings();
        }

        if( $settings_name ) {
            if( isset( $page_settings_model[$post_id][$settings_name] ) ) {
                return $page_settings_model[$post_id][$settings_name];
            }
            return false;
        }

        return $page_settings_model[$post_id];
    }

}

function FAEL_Page_Settings() {
    return FAEL_Page_Settings::instance();
}

FAEL_Page_Settings();
