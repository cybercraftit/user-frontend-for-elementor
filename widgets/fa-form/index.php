<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Form_Field extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve FAEL_Form_Field widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_form';
    }

    /**
     * Get widget title.
     *
     * Retrieve FAEL_Form_Field widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Form', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve FAEL_Form_Field widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_Form_Field widget belongs to.
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
     * Register FAEL_Form_Field widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        do_action( 'fael_widget_controls_sections_before', $this );

        $items = get_posts([
            'post_type' => 'fael_form',
            'post_publish' => 'publish'
        ]);

        $forms = [];

        foreach ( $items as $k => $item ) {
            $forms[$item->ID] = $item->post_title;
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'fael' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        do_action( 'fael_widget_controls_sections_start', $this, 'content_section' );



        $this->add_control(
            'form',
            [
                'label' => __( 'Select form', 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $forms,
                'description' => __( 'Select the form to display', 'fael' )
            ]
        );

        do_action( 'fael_widget_controls_sections_end', $this, 'content_section' );

        $this->end_controls_section();

        do_action( 'fael_widget_controls_sections_after', $this );

    }

    /**
     * Render FAEL_Form_Field widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $has_fael_widget,  $fael_post;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        //self::$fael_forms = $fael_forms;

        echo do_shortcode('[fael_form id="'.$s['form'].'"]');
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Form_Field() );