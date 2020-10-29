<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Tag_Selector extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve FAEL_Tag_Selector widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_tag_selector';
    }

    /**
     * Get widget title.
     *
     * Retrieve FAEL_Tag_Selector widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Tags Selector', 'fael' );
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
        return 'eicon-tags';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_Tag_Selector widget belongs to.
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
     * Register FAEL_Tag_Selector widget controls.
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
            'label',
            [
                'label' => __( 'Label', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Label', 'fael' ),
                'default' => __( 'Tag Selector', 'fael' )
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
     * Render FAEL_Tag_Selector widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        //
        global $has_fael_widget, $fael_post;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        //
        $value = [];
        if( $fael_post ) {
            $post_terms = get_the_terms(  $fael_post, 'post_tag' );
            !is_array( $post_terms ) ? $post_terms = array() : '';
            $terms = [];
            foreach ( $post_terms as $k => $term ) {
                $terms[] = $term->name;
            }
            $value = $terms;
        } else {
            $value = [];
        }

        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        $fael_forms[$s['form_handle']]['taxonomy']['post_tag'] = apply_filters( 'fael_form_field', array(
            'rules' => array(
                'is_required' => $s['is_required'],
                'label' => $s['label']
            ),
            'value' => $value,
            'widget' => $this->get_class_name()
        ), $s);

        FAEL_Form_Elements()->set_form_elements( $fael_forms );
        //self::populate_field( $s['form_handle'], 'post_tag', $value, true, 'taxonomy' );
        FAEL_Form_Elements()->populate_field( $s['form_handle'], 'post_tag', $value, true, 'taxonomy' );
        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        ?>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $s['label']; ?>
                    <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
                </h5>
                <div>
                    <?php echo $s['description']; ?>
                </div>
                <?php
                $args = array(
                    'taxonomy' => 'post_tag',
                    'hide_empty' => false,
                );
                $terms = get_terms($args);
                ?>
                <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
                    <?php
                    foreach ( $terms as $k => $term ) { ?>
                        <div class="position-relative form-check">
                            <label class="form-check-label">
                                <input type="checkbox"
                                       class="form-check-input"
                                       value="<?php echo $term->name; ?>"
                                       name="<?php echo $s['form_handle']; ?>[taxonomy][<?php echo 'post_tag'; ?>][]"
                                    <?php echo in_array( $term->term_id, $fael_forms[$s['form_handle']]['taxonomy']['post_tag']['value'] ) ? 'checked' : '' ;?>

                                       v-model="fael_forms['<?php echo $s['form_handle']; ?>']['taxonomy']['<?php echo 'post_tag' ?>'].value"
                                >
                                <?php echo $term->name; ?>
                            </label>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
<?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Tag_Selector() );
