<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Post_Thumbnail extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve Post Title widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_post_thumbnail';
    }

    /**
     * Get widget title.
     *
     * Retrieve Post Title widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Featured Image', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve Post Title widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-thumbnails-right';
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
                'input_type' => 'text'
            ]
        );
        $this->add_control(
            'label',
            [
                'label' => __( 'Label', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'description' => __( 'label for media uploader', 'fael' ),
                'default' => __( 'Featured Image', 'fael' ),
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
     * Render oEmbed widget output on the frontend.
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


        FAEL_Form_Elements()->set_form_element( $s['form_handle'], 'featured_image', apply_filters( 'fael_form_field', array(
            'rules' => array(
                'is_required' => $s['is_required']
            ),
            'value' => '',
            'label' => $s['label'],
            'widget' => $this->get_class_name()
        ), $s) );

        FAEL_Form_Elements()->populate_field( $s['form_handle'], 'featured_image', $s['value'] );
        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="fael_media_uploader position-relative form-group <?php echo $s['element_class']; ?>">
            <label for="<?php echo $s['form_handle']; ?>[<?php echo 'featured_image'; ?>]" class="">
                <?php echo $s['label']; ?>
                <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
            </label>
            <img id="frontend-image" :src="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo 'featured_image'; ?>'].value" />
            <input id="frontend-button" type="button"
                   class="form-control"
                   value="<?php _e( 'Upload', 'fael' ); ?>"
                   @click="open_uploader('<?php echo $s['form_handle']; ?>', '<?php echo 'featured_image'; ?>')"
            >
            <template v-if="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo 'featured_image'; ?>'].value">
                <input type="button"
                       class="form-control"
                       value="<?php _e( 'Remove', 'fael' ); ?>"
                       @click="remove_media('<?php echo $s['form_handle']; ?>', '<?php echo 'featured_image'; ?>')"
                >
            </template>
            <input type="text"
                   name="<?php echo $s['form_handle']; ?>[<?php echo 'featured_image'; ?>]"
                   class="form-control"
                   value="<?php echo $fael_forms[$s['form_handle']]['featured_image']['value']; ?>"

                   v-model="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo 'featured_image'; ?>'].value"
            >
            <div>
                <?php echo $s['description']; ?>
            </div>
        </div>
<?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Post_Thumbnail() );
