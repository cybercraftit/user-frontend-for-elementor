<?php
//category settings
add_action( 'fael_widget_controls_sections_start', function ( $element ) {
    if( $element->get_name() == 'fael_category_list' ) {
        if( $element->get_current_section()['section'] == 'content_section' ) {
            $element->add_control(
                'pro_6',
                [
                    'label' => __( 'Categories to exclude <span class="fael_promo" style="color: red;">(Pro)</span>', 'fael' ),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                ]
            );
            $element->add_control(
                'pro_7',
                [
                    'label' => __( 'Exclude Empty Categorty ? <span class="fael_promo" style="color: red;">(Pro)</span>', 'fael' ),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                ]
            );
        }
    }
},10);

//accessibility settings
add_action( 'fael_widget_controls_sections_after', function ( $element ) {


    $element->start_controls_section(
        'element_accessibility',
        [
            'label' => esc_html__('UF Accessibility', 'essential-addons-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
        ]
    );
    $element->add_control(
        'pro_3',
        [
            'label' => __( 'Who Can Access This Element <span class="fael_promo" style="color: red;">(Pro)</span>', 'elementor' ),
            'type' => \Elementor\Controls_Manager::RAW_HTML,
        ]
    );
    $element->add_control(
        'pro_4',
        [
            'label' => __( 'Access by Role <span class="fael_promo" style="color: red;">(Pro)</span>', 'elementor' ),
            'type' => \Elementor\Controls_Manager::RAW_HTML
        ]
    );


    $element->add_control(
        'pro_5',
        [
            'label' => __( 'Roles <span class="fael_promo" style="color: red;">(Pro)</span>', 'elementor' ),
            'type' => \Elementor\Controls_Manager::RAW_HTML
        ]
    );
    $element->end_controls_section();
} );


add_action( 'fael_widget_controls_sections_start', function ($elem) {
    if( $elem->get_name() == 'fael_post_list' ) {

        if( $elem->get_current_section()['section'] == 'content_section' ) {
            $elem->add_control(
                'pro_1',
                [
                    'label' => __( 'Choose Authors <span class="fael_promo" style="color: red;">(Pro)</span>', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => [],
                    'description' => __( 'Posts of the selected authors will be listed.', 'fael' )
                ]
            );
            $elem->add_control(
                'pro_2',
                [
                    'label' => __( 'Choose Action(s) ? <span class="fael_promo" style="color: red;">(Pro)</span>', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'options' => [],
                    'description' => __( 'Allowed actions to display in action column', 'fael' ),
                ]
            );
        }
    }
},10);

add_action( 'fael_page_settings_before-form_settings_section', function ( $item ) {
    $item->add_control(
        'pro_3',
        [
            'label' => __( 'Taxonomy <span class="fael_promo" style="color: red;">(Pro)</span>', 'your-plugin' ),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => false,
            'options' => [
                'category' => __( 'Category', 'fael' )
            ],
            'description' => __( 'Choose the taxonomy that will be created by this form', 'fael' ),
            'default' => 'open',
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
} );

add_filter( 'fael_form_submit_types', function( $submit_types ) {
    $submit_types['pro_4'] = __( 'Create Admin Settings <span class="fael_promo" style="color: red;">(Pro)</span>', 'fael' );
    return $submit_types;
});
