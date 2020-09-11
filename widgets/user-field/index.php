<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_User_Field extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve FAEL_User_Field widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_user_field';
    }

    /**
     * Get widget title.
     *
     * Retrieve FAEL_User_Field widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'UF User Field', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve FAEL_User_Field widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-user-circle-o';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_User_Field widget belongs to.
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
     * Register FAEL_User_Field widget controls.
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
            'user_field',
            [
                'label' => __( 'Choose Field', 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => [
                    'user_login' => __( 'Username', 'fael' ),
                    'first_name' => __( 'First Name', 'fael' ),
                    'last_name' => __( 'Last Name', 'fael' ),
                    'user_email' => __( 'User Email', 'fael' ),
                    'nickname' => __( 'User Nickname', 'fael' ),
                    'display_name' => __( 'Display Name', 'fael' ),
                    'url' => __( 'Website URL', 'fael' ),
                    'description' => __( 'Biographical Info', 'fael' ),
                    'password' => __( 'Password', 'fael' )
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
                'default' => __( 'User Field', 'fael' )
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


        $this->end_controls_section();

        do_action( 'fael_widget_controls_sections_after', $this );
    }

    /**
     * Render FAEL_User_Field widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $has_fael_widget, $fael_post, $fael_user;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        //
        switch ( $s['user_field'] ) {
            case 'user_login':
                $s['name'] = 'user_login';
                break;
            case 'first_name':
                $s['name'] = 'first_name';
                break;
            case 'last_name':
                $s['name'] = 'last_name';
                break;
            case 'user_email':
                $s['name'] = 'user_email';
                break;
            case 'nickname':
                $s['name'] = 'nickname';
                break;
            case 'display_name':
                $s['name'] = 'display_name';
                break;
            case 'url':
                $s['name'] = 'url';
                break;
            case 'description':
                $s['name'] = 'description';
                break;
            case 'password':
                $s['name'] = 'password';
                break;
        }

        FAEL_Form_Elements()->set_form_element( $s['form_handle'], $s['name'], apply_filters( 'fael_form_field', array(
            'rules' => array(
                'is_required' => $s['is_required']
            ),
            'value' => '',
            'widget' => $this->get_class_name(),
        ), $s));

        FAEL_Form_Elements()->populate_field( $s['form_handle'], $s['name'], $s['value'], false, null, 'user' );
        $fael_forms = FAEL_Form_Elements()->get_form_elements();
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <?php
            switch ( $s['user_field'] ) {
                case 'user_login':
                    $s['name'] = 'user_login';
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
                case 'first_name':
                    $s['name'] = 'first_name';
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
                case 'last_name':
                    $s['name'] = 'last_name';
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
                case 'user_email':
                    $s['name'] = 'user_email';
                    ?>
                    <label for="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]" class="">
                        <?php echo $s['label']; ?>
                        <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
                    </label>
                    <input type="email"
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
                case 'nickname':
                    $s['name'] = 'nickname';
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
                case 'display_name':
                    $s['name'] = 'display_name';
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
                case 'url':
                    $s['name'] = 'url';
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
                case 'password':
                    $s['name'] = 'password';
                    ?>
                    <label for="<?php echo $s['form_handle']; ?>[<?php echo $s['name']; ?>]" class="">
                        <?php echo $s['label']; ?>
                        <?php echo $s['is_required'] == 'yes' ? '<span style="color: red;">&ast;</span>' : ''; ?>
                    </label>
                    <input type="password"
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
            }
            ?>

        </div>
<?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_User_Field() );
