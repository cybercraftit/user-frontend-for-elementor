<?php
/**
 * Elementor Checkbox Widget.
 *
 * @since 1.0.0
 */
class FAEL_User_List extends FAEL_Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve User List widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'fael_user_list';
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
        return __( 'UF User List', 'fael' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve User List widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-editor-list-ul';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the FAEL_User_List widget belongs to.
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
     * Register FAEL_User_List widget controls.
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
            'number',
            [
                'label' => __( 'Users per Page', 'fael' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description' => __( 'Number of users per page', 'fael' ),
                'default' => '10'
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
        $this->end_controls_section();

        do_action( 'fael_widget_controls_sections_end', $this, $this->get_name() );
    }

    /**
     * Render FAEL_User_List widget output on the frontend.
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

        $offset = ( $pagenum - 1 ) * $s['number'];


        $args = array(
            'number' => $s['number'],
            'offset' => $offset
        );
        $the_query = new WP_User_Query( $args );
        $users = $the_query->get_results();
        ?>
        <div id="<?php echo $s['element_id']; ?>" class="position-relative form-group <?php echo $s['element_class']; ?>">
            <?php
            // The Loop
            if ( !empty($users) ) {
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
                                <th>User Email</th>
                                <th>User Nicename</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ( $users as $user  ) {
                                $userdata = get_userdata( $user->ID );
                                ?>
                                <tr>
                                    <td><?php echo $user->user_email; ?></td>
                                    <td><?php echo $user->user_nicename; ?></td>
                                    <td>
                                        <a href="<?php echo FAEL_Functions()->get_user_view_url( $user->ID ); ?>"><?php _e( 'View', 'fael');  ?></a>
                                        <a href="<?php echo FAEL_Functions()->get_user_edit_url( $user->ID ); ?>"><?php _e( 'Edit', 'fael');  ?></a>
                                        <a href="<?php echo FAEL_Functions()->get_user_delete_url( $user->ID ); ?>"><?php _e( 'Delete', 'fael');  ?></a>
                                        <?php /*if( get_post_meta( get_the_ID(), '__fael_can_edit_post', true ) ) { */?><!--

                                        <?php /*} */?>
                                        <?php /*if( get_post_meta( get_the_ID(), '__fael_can_delete_post', true ) ) { */?>

                                        --><?php /*} */?>
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
                                        <li class="page-item"><a href="<?php echo add_query_arg(array( 'pagenum' => ( $pagenum - 1 ) ), home_url( $wp->request ) ); ?>" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                    <?php } ?>
                                    <?php
                                    for ( $i = 1; $i <= $the_query->max_num_pages; $i++ ) { ?>
                                        <li class="page-item"><a href="<?php echo add_query_arg(array( 'pagenum' => $i ), home_url( $wp->request ) ); ?>" class="page-link"><?php echo $i; ?></a></li>
                                    <?php } ?>
                                    <?php if( $pagenum < $the_query->max_num_pages ) { ?>
                                        <li class="page-item"><a href="<?php echo add_query_arg(array( 'pagenum' => ( $pagenum + 1 ) ), home_url( $wp->request ) ); ?>" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
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

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new FAEL_User_List() );

