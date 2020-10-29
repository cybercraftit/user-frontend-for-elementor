<?php

$taxes = get_taxonomies( array(), 'objects' );

foreach ( $taxes as $name => $tax ) {
    if( $name == 'category' ) continue;

    $tax_widget = new class extends FAEL_Category_List {

        protected $name;
        protected $title;
        protected $icon;
        protected $tax;

        /**
         * Get widget name.
         *
         * Retrieve FAEL_Category_List widget name.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string Widget name.
         */
        public function get_name() {
            return $this->name;
        }

        public function set_name( $name = '' ) {
            $this->name = 'fael_'.$name.'_list';
        }

        public function set_tax( $tax = '' ) {
            $this->tax = $tax;
        }

        /**
         * Get widget title.
         *
         * Retrieve FAEL_Category_List widget title.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string Widget title.
         */
        public function get_title() {
            return $this->title;
        }

        public function set_title( $title ) {
            $this->title = __( 'UF '.$title.' <span class="fael_promo_text" style="color: red;">(Pro)</span>', 'fael' );
        }

        /**
         * Get widget icon.
         *
         * Retrieve FAEL_Category_List widget icon.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string Widget icon.
         */
        public function get_icon( $icon = 'eicon-bullet-list' ) {
            return $this->icon;
        }

        public function set_icon( $icon = 'eicon-bullet-list' ) {
            $this->icon = $icon;
        }

        /**
         * Get widget categories.
         *
         * Retrieve the list of categories the FAEL_Category_List widget belongs to.
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
         * Register FAEL_Category_List widget controls.
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
         * Render FAEL_Taxonomy_List widget output on the frontend.
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
    };

    $tax_widget->set_name( $name );
    $tax_widget->set_title( $tax->label );
    $tax_widget->set_icon();
    $tax_widget->set_tax( $name );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( $tax_widget );
}