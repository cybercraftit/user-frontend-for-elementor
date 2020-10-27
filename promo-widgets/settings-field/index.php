<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Settings_Field extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve FAEL_Settings_Field widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_settings_field';
    }

    /**
     * Get widget title.
     *
     * Retrieve FAEL_Settings_Field widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Admin Settings Field <span class="fael_promo_text" style="color: red;">(Pro)</span>', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve FAEL_Settings_Field widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'fa fa-text-height';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_Settings_Field widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'fael-pro-cat' ];
    }

    /**
     * Register FAEL_Settings_Field widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'fael' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => __( '', 'fael' ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __( '<span style="color:red;"> This is pro feature </span>.', 'fael' ),
                'placeholder' => __( 'Label', 'fael' ),
            ]
        );

        $this->end_controls_section();

        do_action( 'fael_widget_controls_sections_end', $this );
    }

    /**
     * Render FAEL_Settings_Field widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $has_fael_widget, $wp;
        ?>
        <div id="" class="position-relative form-group">
            <span style="color:red;"><?php _e( 'You need to purchase pro module to access this feature and many more.'); ?></span>
        </div>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Settings_Field() );