<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Category_Meta extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve FAEL_Category_Meta widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_category_form_field';
    }

    /**
     * Get widget title.
     *
     * Retrieve FAEL_Category_Meta widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF Category Form Field', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve FAEL_Category_Meta widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-text-field';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_Category_Meta widget belongs to.
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
     * Register FAEL_Category_Meta widget controls.
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
            'tax_field',
            [
                'label' => __( 'Choose Field', 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => [
                    'tag-name' => __( 'Name', 'fael' ),
                    'slug' => __( 'Slug', 'fael' ),
                    'parent' => __( 'Parent Category', 'fael' ),
                    'description' => __( 'Description', 'fael' ),
                ],

            ]
        );
        $this->add_control(
            'value',
            [
                'label' => __( 'Value', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Value', 'fael' ),
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
     * Render FAEL_Category_Meta widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $has_fael_widget,  $fael_post, $fael_user;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        //
        $s['name'] = $s['tax_field'];

        FAEL_Form_Elements()->set_form_element( $s['form_handle'], $s['name'], apply_filters( 'fael_form_field', array(
            'rules' => array(
                'is_required' => $s['is_required']
            ),
            'value' => '',
            'widget' => $this->get_class_name(),
        ), $s) );

        FAEL_Form_Elements()->populate_field( $s['form_handle'], $s['name'], $s['value'], false, null, 'post' );
        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <?php
            switch ( $s['tax_field'] ) {
                case 'tag-name':
                    ?>
                    <label for="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]" class="">
                        <?php echo $s['label']; ?>
                        <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
                    </label>
                    <input type="text"
                           name="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]"
                           placeholder="<?php echo $s['placeholder']; ?>"
                           class="form-control"
                           value="<?php echo $fael_forms[$s['form_handle']][$s['name']]['value']; ?>"

                           v-model="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo $s['name']; ?>'].value"
                    >
                    <div>
                        <?php echo $s['description']; ?>
                    </div>
                    <?php
                    break;
                case 'slug':
                    ?>
                    <label for="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]" class="">
                        <?php echo $s['label']; ?>
                        <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
                    </label>
                    <input type="text"
                           name="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]"
                           placeholder="<?php echo $s['placeholder']; ?>"
                           class="form-control"
                           value="<?php echo $fael_forms[$s['form_handle']][$s['name']]['value']; ?>"

                           v-model="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo $s['name']; ?>'].value"
                    >
                    <div>
                        <?php echo $s['description']; ?>
                    </div>
            <?php
                    break;
                case 'parent':
                    $terms = get_terms(array(
                            'taxonomy' => 'category',
                        'hide_empty' => false
                    ));
                    ?>
                    <label for="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]" class="">
                        <?php echo $s['label']; ?>
                        <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
                    </label>
                    <select
                           name="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]"
                           class="form-control"

                           v-model="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo $s['name']; ?>'].value"
                    >
                        <?php
                        foreach ( $terms as $k => $term) {
                            ?>
                            <option value="<?php echo $term->term_id; ?>" <?php echo $fael_forms[$s['form_handle']][$s['name']]['value'] == $term->term_id ? 'selected' : ''; ?>>
                                <?php echo $term->name; ?>
                            </option>
                             <?php
                        }
                        ?>

                    </select>
                    <div>
                        <?php echo $s['description']; ?>
                    </div>
            <?php
                    break;
                case 'description':
                    $s['name'] = 'description';
                    ?>
                    <label for="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]" class="">
                        <?php echo $s['label']; ?>
                        <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
                    </label>
                    <textarea name="<?php echo $s['form_handle']; ?>[<?php echo 'description'; ?>]['value']"
                              placeholder="<?php echo $s['placeholder']; ?>"
                              class="form-control"

                              v-model="fael_forms['<?php echo $s['form_handle']; ?>']['<?php echo 'description'; ?>'].value"
                    >
                <?php echo $fael_forms[$s['form_handle']]['description']['value']; ?>
            </textarea>
                    <div>
                        <?php echo $s['description']; ?>
                    </div>
            <?php
                    break;
            }
            ?>

        </div>
<?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Category_Meta() );
