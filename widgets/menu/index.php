<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Menu extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve oEmbed widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_menu';
    }

    /**
     * Get widget title.
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Menu', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve Checkbox Group widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-menu-bar';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the oEmbed widget belongs to.
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
     * Register oEmbed widget controls.
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

        //repeater
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'label',
            [
                'label' => __( 'Label', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Label', 'fael' ),
                'default' => __( 'Menu Item', 'fael'),
                'description' => __( 'Label for menu item', 'fael')
            ]
        );
        $repeater->add_control(
            'url_type',
            [
                'label' => __( 'URL Type', 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => [
                    'url' => __( 'URL', 'fael' ),
                    'page' => __( 'Redirect to a Page', 'fael' )
                ],
                'default' => __( 'url', 'fael' )
            ]
        );
        $repeater->add_control(
            'url',
            [
                'label' => __( 'Redirection URL', 'your-plugin' ),
                'type' => \Elementor\Controls_Manager::URL,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'url_type',
                            'operator' => '==',
                            'value' => 'url',
                        ],
                    ],
                ],
            ]
        );
        $repeater->add_control(
            'newtab',
            [
                'label' => __( 'Open Link in New Tab ?', 'your-plugin' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'fael' ),
                'label_off' => __( 'No', 'fael' ),
                'return_value' => 'yes',
                'default' => 'No',
                'description' => __( 'Checking this will menu item to open link in new tab', 'fael' ),
            ]
        );

        $page_list = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish'
        ));
        $pages = [];

        foreach ( $page_list as $k => $each ) {
            $pages[$each->ID] = $each->post_title;
        }

        $repeater->add_control(
            'page',
            [
                'label' => __( 'Page to redirect to', 'your-plugin' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $pages,
                'description' => __( 'Select the page that will be linked with this menu item', 'fael' ),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'url_type',
                            'operator' => '==',
                            'value' => 'page',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'sub_items',
            [
                'label' => __( 'Add Sub Menu Item', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ label }}}',
            ]
        );

        //
        $this->add_control(
            'items',
            [
                'label' => __( 'Add Menu Item', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ label }}}',
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
        //
        global $has_fael_widget, $wp;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();
        //
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <ul class="nav flex-column">
                <?php
                foreach ( $s['items'] as $k => $item ) {
                    $url = null;
                    switch ( $item['url_type'] ) {
                        case 'url':
                            $url = $item['url']['url'];
                            break;
                        case 'page':
                            $url = get_permalink( $item['page'] );
                            break;
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo home_url($wp->request) == $url ? 'active' : ''; ?>" href="<?php echo $url; ?>"
                        <?php echo $item['newtab'] == 'yes' ? 'target="_blank"' : ''; ?>
                        ><?php echo $item['label']; ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <div>
                <?php echo $s['description']; ?>
            </div>
        </div>
<?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Menu() );
