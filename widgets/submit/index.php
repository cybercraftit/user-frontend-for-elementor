<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Submit extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve Submit widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_submit';
    }

    /**
     * Get widget title.
     *
     * Retrieve Submit widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Submit', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve Submit widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-button';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Submit widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'fael-category' ];
    }

    /**
     * Register Submit widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        do_action( 'fael_widget_controls_sections_before', $this );

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'fael' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        do_action( 'fael_widget_controls_sections_start', $this, 'content_section' );

        $this->add_control(
            'form_handle',
            [
                'label' => __( 'Form Handle', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Form Handle', 'fael' ),
                'description' => __( 'Name of form handle, all fields of same form should have the same form handle', 'fael' ),
                'default' => 'form'
            ]
        );
        $this->add_control(
            'value',
            [
                'label' => __( 'Value', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Value', 'fael' ),
                'default' => __( 'Submit', 'fael' )
            ]
        );
        $this->add_control(
            'label',
            [
                'label' => __( 'Label', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Label', 'fael' ),
            ]
        );
        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'textarea',
                'placeholder' => __( 'Description', 'fael' ),
            ]
        );

        $this->add_control(
            'element_id',
            [
                'label' => __( 'ID', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'ID for your element, this should be unique', 'fael')
            ]
        );
        $this->add_control(
            'element_class',
            [
                'label' => __( 'Class', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'CSS Class for your element', 'fael')
            ]
        );

        do_action( 'fael_widget_controls_sections_end', $this, 'content_section' );

        $this->end_controls_section();


        if( get_post_type() != 'fael_form' ) {

            //form settings section
            $this->start_controls_section(
                'form_settings_section',
                [
                    'label' => __( 'Form Settings', 'fael' ),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                ]
            );

            do_action( 'fael_widget_controls_sections_start', $this, 'form_settings_section' );

            //settings tab
            $this->add_control(
                'submit_type',
                [
                    'label' => __( 'When User Submit Form', 'fael' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'default' => 'create_post',
                    'options' => [
                        'create_post'  => __( 'Create Post', 'plugin-domain' ),
                        'create_user'  => __( 'Create User', 'plugin-domain' )
                    ],
                    'description' => __( 'Select what will happen if the user submit form.', 'fael')
                ]
            );

            $post_types = get_post_types( array(
                'public'   => true
            ));

            $this->add_control(
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
            $this->add_control(
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

            $this->add_control(
                'after_create_item',
                [
                    'label' => __( 'After Submit', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'default' => 'redirect_url',
                    'options' => [
                        'redirect_url' => __( 'Redirect to a URL', 'fael' ),
                        'to_page' => __( 'Redirect to a Page', 'fael' ),
                        'redirect_edit_item' => __( 'Redirect to post edit page', 'fael' ),
                        'view_post' => __( 'View created post', 'fael' ),
                    ],
                    'description' => __( 'What to do after the form is submitted successfully.', 'fael' ),
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
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
                        ]
                    ],
                ]
            );

            //
            $this->add_control(
                'create_item_redirect_url',
                [
                    'label' => __( 'Redirection URL', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::URL,
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => 'in',
                                'value' => ['create_post', 'create_user'],
                            ],
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

            $this->add_control(
                'create_item_redirect_page',
                [
                    'label' => __( 'Page to redirect to', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => false,
                    'options' => $pages,
                    'description' => __( 'Select the page that the user will be redirect to after the post created', 'fael' ),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'submit_type',
                                'operator' => 'in',
                                'value' => ['create_post', 'create_user'],
                            ],
                            [
                                'name' => 'after_create_item',
                                'operator' => '==',
                                'value' => 'to_page',
                            ],
                        ],
                    ],
                ]
            );
            $this->add_control(
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
            $this->add_control(
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
            $this->add_control(
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
            $this->add_control(
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

            $this->add_control(
                'post_status',
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
            $this->add_control(
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
            $this->add_control(
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

            do_action( 'fael_widget_controls_sections_end', $this, 'form_settings_section' );

            $this->end_controls_section();
        }

        do_action( 'fael_widget_controls_sections_after', $this );
    }

    /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $has_fael_widget, $fael_forms, $fael_post, $post;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        //

        FAEL_Form_Elements()->set_form_element( $s['form_handle'], 'submit', apply_filters( 'fael_form_field', array(
            'rules' => array(),
            'value' => '',
            'widget' => $this->get_class_name()
        ), $s) );

        self::populate_field( $s['form_handle'], 'submit', $s['value'], true );

        /*$form_settings = array();

        if( isset( $s['submit_type'] ) ) {
            switch( $s['submit_type'] ) {
                case 'create_post':
                case 'create_user':
                    $form_settings = array(
                        'submit_type' => isset( $s['submit_type'] ) ? $s['submit_type'] : '',
                        'post_type' => isset( $s['post_type'] ) ? $s['post_type'] : '',
                        'post_status' => isset( $s['post_status'] ) ? $s['post_status'] : '',
                        'after_create_item' => isset( $s['after_create_item'] ) ? $s['after_create_item'] : '',
                        'create_item_redirect_url' => isset( $s['create_item_redirect_url'] ) ? $s['create_item_redirect_url'] : '',
                        'create_item_redirect_page' => isset( $s['create_item_redirect_page'] ) ? $s['create_item_redirect_page'] : '',
                        'can_edit_post' => isset( $s['can_edit_post'] ) ? $s['can_edit_post'] : '',
                        'can_edit_user' => isset( $s['can_edit_user'] ) ? $s['can_edit_user'] : '',
                        'can_delete_post' => isset( $s['can_delete_post'] ) ? $s['can_delete_post'] : '',
                        'can_delete_user' => isset( $s['can_delete_user'] ) ? $s['can_delete_user'] : '',
                        'can_draft_post' => isset( $s['can_draft_post'] ) ? $s['can_draft_post'] : '',
                        'post_needs_review' => isset( $s['post_needs_review'] ) ? $s['post_needs_review'] : '',
                        'comment_status' => isset( $s['comment_status'] ) ? $s['comment_status'] : '',
                        'fael_accessibility' => isset( $s['fael_accessibility'] ) ? $s['fael_accessibility'] : '',
                        'fael_access_by_role' => isset( $s['fael_access_by_role'] ) ? $s['fael_access_by_role'] : '',
                        'fael_accessible_roles' => isset( $s['fael_accessible_roles'] ) ? $s['fael_accessible_roles'] : array(),
                        '__container_id' => isset( $fael_forms[$s['form_handle']]['form_settings']['__container_id'] ) ? $fael_forms[$s['form_handle']]['form_settings']['__container_id'] : get_the_ID()
                    );
                    break;
            }
        }

        $fael_forms[$s['form_handle']]['form_settings'] = apply_filters( 'fael_form_settings', $form_settings, $s );

        self::$fael_forms = $fael_forms;*/

        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        //pri($fael_forms);
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <?php
            /**
             * Show all the errors here
             */
            foreach ( $fael_forms[$s['form_handle']] as $field => $field_data ) {
                $this->show_errors($s['form_handle'], $field);
            }
            ?>
            <label for="<?php echo $s['form_handle']; ?>[<?php echo 'submit'; ?>]" class=""><?php echo $s['label']; ?></label>
            <input type="submit"
                   name="<?php echo $s['form_handle']; ?>[<?php echo 'submit'; ?>]"
                   class="form-control btn btn-primary"
                   value="<?php echo $fael_forms[$s['form_handle']]['submit']['value']; ?>"

                   v-model="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo 'submit'; ?>'].value"

                   @click="submit_content('<?php echo $s['form_handle']; ?>')"
            >
            <div>
                <?php echo $s['description']; ?>
            </div>
        </div>
<?php
    }

    /**
     * @param $handle
     * @param $name
     * @param null $default
     */
    public static function populate_field( $handle, $name, $default = null, $use_default = false, $type = null, $module = 'post' ) {
        global $fael_post, $fael_user;

        $fael_forms = FAEL_Form_Elements()->get_form_elements();

        if( isset( $fael_post->submit ) ) {
            $value = $fael_post->submit;
        } else {
            $value = $default;
        }
        $fael_forms[$handle][$name]['value'] = $value;

        //this is to decide if it is to edit or create new item
        if( isset( $fael_post->ID ) ) {
            $fael_forms[$handle]['__item_ID'] = $fael_post->ID;
        }

        FAEL_Form_Elements()->set_form_elements( $fael_forms );
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Submit() );
