<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_Post_List extends FAEL_Widget_Base {

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
        return 'fael_post_list';
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
        return __( 'UF Post List', 'fael' );
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
        return 'eicon-posts-group';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_Post_List widget belongs to.
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
     * Register FAEL_Post_List widget controls.
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

        $this->add_control(
            'post_author_type',
            [
                'label' => __( 'Display', 'your-plugin' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'multiple' => false,
                'options' => [
                    'owner_posts' => __( 'Owner\'s posts only'),
                    'all' => __( 'All posts'),
                    'specific_authors' => __( 'Posts of specific author(s)'),
                ],
                'return_value' => 'yes',
                'default' => 'No',
                'description' => __( 'Checking this will list only the owner\'s posts', 'fael' ),
            ]
        );

        do_action( 'fael_widget_controls_sections_start', $this, 'content_section' );



        $this->add_control(
            'post_type',
            [
                'label' => __( 'Post Type', 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [],
                'description' => __( 'Choose posts of which post type will be listed', 'fael' ),
                'default' => 'post'
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Post per Page', 'fael' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description' => __( 'Number of posts per page', 'fael' ),
                'default' => '10'
            ]
        );

        $this->add_control(
            'post_status',
            [
                'label' => __( 'Post Status', 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [],
                'description' => __( 'Posts with the post statuses selected will be listed', 'fael' ),
                'default' => array( 'publish' )
            ]
        );


        $taxes = get_taxonomies();
        $tax_temrs = [];

        foreach ( $taxes as $tax_name => $label ) {
            $terms_objects = get_terms( array(
                'taxonomy' => $tax_name,
                'hide_empty' => false,
            ) );
            $terms = [];
            foreach ( $terms_objects as $k => $term ) {
                $terms[$term->term_id] = $term->name;
            }
            $tax_temrs[$tax_name] = $terms;
        }


        $this->add_control(
            'taxonomies',
            [
                'label' => __( 'Taxonomy', 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $taxes,
                'description' => __( 'Posts with the selected taxonomy will be listed, keep blank to list post of all taxonomies', 'fael' ),
            ]
        );

        foreach ( $tax_temrs as $tax_name => $terms ) {
            $this->add_control(
                'tax_terms['.$tax_name.']',
                [
                    'label' => __( 'Select '.$tax_name, 'fael' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $terms,
                    'description' => __( 'Posts with the selected terms will be listed', 'fael' ),
                ]
            );
        }

        /*$this->add_control(
            'list_type',
            [
                'label' => __( 'Which posts to list ?'.$tax_name, 'fael' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => [
                        'self' => __( 'Only the posts that he created' , 'fael' ),
                        'all' => __( 'All Posts' , 'fael' ),
                ],
                'description' => __( 'Which posts to list', 'fael' )
            ]
        );*/

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
     * Render FAEL_Post_List widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        global $wp;
        global $has_fael_widget;
        $has_fael_widget = true;
        $s = $this->get_settings_for_display();

        //pagenumber
        if( isset( $_GET['pagenum'] ) && is_numeric( $_GET['pagenum'] ) ) {
            $pagenum = $_GET['pagenum'];
        } else {
            $pagenum = 1;
        }

        $offset = ( $pagenum - 1 ) * $s['posts_per_page'];

        //retrive post status from url parameter
        if( isset( $_GET['status'] ) && is_string( $_GET['status'] ) && in_array( $_GET['status'], $s['post_status'] ) ) {
            $post_status = $_GET['status'];
        } else {
            $post_status = 'publish';
        }


        $args = array(
                'post_type' => $s['post_type'],
            'post_status' => $post_status,
            'posts_per_page' => $s['posts_per_page'],
            'offset' => $offset
        );

        if( isset( $s['post_author_type'] ) && $s['post_author_type'] ) {
            if ( $s['post_author_type'] == 'owner_posts' ) {
                $args['author__in'] = array( get_current_user_id() );
            }
        }

        //terms taxonomy
        if( !empty( $s['taxonomies'] ) ) {
            foreach ( $s['taxonomies'] as $k => $tax ) {
                if( !empty( $s['tax_terms['.$tax.']'] ) ) {
                    $args['tax_query'][] =  array(
                        'taxonomy' => $tax,
                        'field' => 'id',
                        'terms' => $s['tax_terms['.$tax.']'],
                        'include_children' => false,
                        'operator' => 'IN'
                    );
                }
            }
            $args['tax_query']['relation'] = 'AND';
        }


        $args = apply_filters( 'fael_post_list-render-posts_args', $args, $this, $s );

        $the_query = new WP_Query( $args );
        ?>
        <?php
        //pri(wp_roles()->roles);
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <?php
            // The Loop
            if ( $the_query->have_posts() ) {
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
                                <th>Post Title</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                global $post;
                                ?>
                                <tr>
                                    <td><?php the_title(); ?></td>
                                    <td>
                                        <?php
                                        if( !FAEL_Functions()->is_pro() ) {
                                            ?>
                                            <a href="<?php echo FAEL_Functions()->get_post_view_url( get_the_ID() ); ?>"><?php _e( 'View', 'fael');  ?></a>
                                            <?php if( FAEL_Functions()->can_edit_item( $post, 'post' ) ) { ?>
                                                <a href="<?php echo FAEL_Functions()->get_item_edit_url( get_the_ID(), 'post' ); ?>"><?php _e( 'Edit', 'fael');  ?></a>
                                            <?php } ?>
                                            <?php if( FAEL_Functions()->can_delete_item( $post, 'post' ) ) { ?>
                                                <a href="<?php echo FAEL_Functions()->get_item_delete_url( get_the_ID(), 'post' ); ?>"><?php _e( 'Delete', 'fael');  ?></a>
                                            <?php } ?>
                                            <?php
                                        }
                                        ?>
                                        <?php do_action( 'fael_post_list-render-actions', $this, $s ); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <div class="pagination-wrapper">
                            <nav class="" aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php
                                    if( $pagenum > 1 ) { ?>
                                        <li class="page-item"><a href="<?php echo add_query_arg(array( 'pagenum' => ( $pagenum - 1 ) , 'status' => $post_status ), home_url( $wp->request ) ); ?>" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                    <?php } ?>
                                    <?php
                                    for ( $i = 1; $i <= $the_query->max_num_pages; $i++ ) { ?>
                                        <li class="page-item"><a href="<?php echo add_query_arg(array( 'pagenum' => $i , 'status' => $post_status ), home_url( $wp->request ) ); ?>" class="page-link"><?php echo $i; ?></a></li>
                                    <?php } ?>
                                    <?php if( $pagenum < $the_query->max_num_pages ) { ?>
                                        <li class="page-item"><a href="<?php echo add_query_arg(array( 'pagenum' => ( $pagenum + 1 ) , 'status' => $post_status ), home_url( $wp->request ) ); ?>" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                                    <?php } ?>

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

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_Post_List() );

