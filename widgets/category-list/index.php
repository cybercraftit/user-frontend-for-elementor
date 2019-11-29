<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Category_List extends FAEL_Widget_Base {

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
        return 'fael_category_list';
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
        return __( 'UF Category List', 'fael' );
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
    public function get_icon() {
        return 'eicon-bullet-list';
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
        return [ 'fael-category' ];
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
            'label',
            [
                'label' => __( 'Label', 'fael' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Label', 'fael' ),
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
            'terms_per_page',
            [
                'label' => __( 'Category per Page', 'fael' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description' => __( 'Number of posts per page', 'fael' ),
                'default' => '10'
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
     * Render FAEL_Category_List widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $has_fael_widget, $wp;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        //
        //pagenumber
        if( isset( $_GET['cagegory-pagenum'] ) ) {
            $pagenum = $_GET['cagegory-pagenum'];
        } else {
            $pagenum = 1;
        }

        $offset = ( $pagenum - 1 ) * $s['terms_per_page'];

        $args = array(
            'taxonomy' => 'category',
            'hide_empty' => false,
            'number' => $s['terms_per_page'],
            'offset' => $offset
        );
        $args = apply_filters( 'fael_category_list-render-term_args', $args, $this, $s ) ;
        $terms = get_terms($args);
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <?php
            // The Loop
            if ( $terms ) {
                ?>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $s['label']; ?></h5>
                        <div>
                            <?php echo $s['description']; ?>
                        </div>
                        <table class="mb-0 table table-striped">
                            <thead>
                            <tr>
                                <th><?php _e( 'Name' , 'fael' ); ?></th>
                                <th><?php _e( 'Description', 'fael' ); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ( $terms as $k => $term ) { ?>
                                <tr>
                                    <td><?php echo $term->name; ?></td>
                                    <td><?php echo $term->description; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="pagination-wrapper">
                            <nav class="" aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php
                                    if( $pagenum > 1 ) { ?>
                                        <li class="page-item"><a href="<?php echo add_query_arg(array( 'cagegory-pagenum' => ( $pagenum - 1 ) ), home_url( $wp->request ) ); ?>" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                    <?php } ?>
                                    <li class="page-item"><a href="<?php echo add_query_arg(array( 'cagegory-pagenum' => ( $pagenum + 1 ) ), home_url( $wp->request ) ); ?>" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <?php

            }
            // Reset Post Data
            wp_reset_postdata();
            ?>
        </div>
        <?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Category_List() );