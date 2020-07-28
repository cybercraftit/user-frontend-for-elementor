<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Post_Status extends FAEL_Widget_Base {

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
        return 'fael_post_status';
    }

    /**
     * Get widget title.
     *
     * Retrieve FAEL_Post_Status widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Post Status', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve FAEL_Post_Status widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_Post_Status widget belongs to.
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
     * Register FAEL_Post_Status widget controls.
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
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => []
            ]
        );
        $this->add_control(
            'label',
            [
                'label' => __( 'Label', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Label', 'fael' ),
                'default' => __( 'Post Status', 'fael' ),
            ]
        );
        $this->add_control(
            'placeholder',
            [
                'label' => __( 'Placeholder', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Placeholder', 'fael' ),
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
            'is_required',
            [
                'label' => __( 'Required', 'fael' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'fael' ),
                'label_off' => __( 'No', 'fael' ),
                'return_value' => 'yes',
                'default' => '',
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
     * Render FAEL_Post_Status widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $has_fael_widget, $fael_post;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        FAEL_Form_Elements()->set_form_element( $s['form_handle'], 'post_status', apply_filters( 'fael_form_field', array(
            'rules' => array(
                'is_required' => $s['is_required']
            ),
            'value' => '',
            'widget' => $this->get_class_name(),
        ), $s) );

        FAEL_Form_Elements()->populate_field( $s['form_handle'], 'post_status', $s['value'] );
        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <label for="<?php echo $s['form_handle']; ?>[<?php echo 'post_status'; ?>]" class="">
                <?php echo $s['label']; ?>
                <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
            </label>
            <select
                name="<?php echo $s['form_handle']; ?>[<?php echo 'post_status'; ?>]"
                class="form-control"

                v-model="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo 'post_status'; ?>'].value"
            >
                <option value=""><?php _e( 'Select', 'fael' ); ?></option>
                <?php
                $post_statuses = get_post_statuses();
                foreach (  $post_statuses as $post_status => $label ) {
                    ?>
                    <option value="<?php echo $post_status; ?>"
                        <?php echo $fael_forms[$s['form_handle']]['post_status']['value'] == $post_status ? 'selected' : ''; ?>
                    ><?php echo $label; ?></option>
                    <?php
                }
                ?>
            </select>
            <div>
                <?php echo $s['description']; ?>
            </div>
        </div>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Post_Status() );