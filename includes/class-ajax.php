<?php

class FAEL_Ajax {

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return FAEL_Ajax An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'wp_ajax_fael_form_submit', array( $this, 'form_submit' ) );
        add_action( 'wp_ajax_nopriv_fael_form_submit', array( $this, 'form_submit' ) );
        add_action( 'wp_ajax_fael_fetch_data', array( $this, 'fael_fetch_data' ));
    }

    public function form_submit() {
        $formdata = $_POST['formdata'];
        $errors = [];

        //get saved form from page/form post
        $fael_forms = FAEL_Page_Frontend()->get_page_forms( $formdata['form_settings']['__container_id'] );

        //if saved form not found
        if ( !$fael_forms || !is_array( $fael_forms ) ) {
            wp_send_json_error();
        }

        //If saved form with the specified handle not found
        if( !isset( $fael_forms[$_POST['form_handle']] ) ) {
            wp_send_json_error();
        }


        //get current form and form settings from saved form
        $current_form = $fael_forms[$_POST['form_handle']];
        $form_settings = $current_form['form_settings'];

        //Check capability for creating item from form settings,
        // if not have capability, user can not create item
        if( !apply_filters( 'form_submit-check_widget_accessibility', FAEL_Accessibility_Functions()->check_widget_accessibility($form_settings), $form_settings ) ) {
            wp_send_json_error(array(
                'errors' => $errors,
                'msg' => __( 'You are not allowed to perform this action', 'fael' )
            ));
        }


        //validation part
        $response = $this->validate_data($current_form,$formdata);


        if( !empty( $response['errors'] ) ) {
            wp_send_json_error(array(
                'errors' => $response['errors']
            ));
        };

        //after validation
        $data = $response['data'];

        if( !isset( $current_form['form_settings'] ) || !is_array( $current_form['form_settings'] ) ) {
            $errors[] = [ __( 'Something went wrong !', 'fael' ) ];
            wp_send_json_error( array(
                'errors' => $errors
            ));
        }

        switch ( $form_settings['submit_type'] ) {
            //create user
            case 'create_user':
                $ret = $this->create_user( $data, $current_form );

                if( isset( $ret['item_id'] ) ) {
                    $item_id = $ret['item_id'];
                    $postdata = $ret['postdata'];

                    //add system meta

                    //add form handle to $postdata
                    update_user_meta( $item_id, '__fael_form_handle', $_POST['form_handle']);
                    //add form page id as post meta
                    update_user_meta( $item_id, '__fael_form_page_id', $form_settings['__container_id'] );


                    /**
                     * Set form settings
                     */
                    $form_settings = $current_form['form_settings'];

                    foreach ( $form_settings as $setting_name => $setting_value ) {
                        switch ( $setting_name ) {
                            case 'can_edit_user':
                                update_user_meta( $item_id, '__fael_can_edit_user', ( $setting_value == 'yes' ? $setting_value : 0) );
                                break;
                            case 'can_delete_user':
                                update_user_meta( $item_id, '__fael_can_delete_user', ( $setting_value == 'yes' ? $setting_value : 0) );
                                break;
                        }
                    }

                    //set system meta
                    do_action( 'fael_after_create_user', $item_id, $postdata, $current_form );

                    $url = '';
                    if( isset( $form_settings['after_create_item'] ) ) {

                        switch ( $form_settings['after_create_item'] ) {
                            case 'redirect_url':
                                $url = isset( $form_settings['create_item_redirect_url']['url'] ) ? $form_settings['create_item_redirect_url']['url'] : '';
                                break;
                            case 'redirect_edit_item':
                                $url = FAEL_Functions()->get_user_edit_url($item_id);
                                break;
                            case 'view_post':
                                $url = FAEL_Functions()->get_user_view_url($item_id);
                                break;
                            case 'to_page':
                                if( $form_settings['create_item_redirect_page'] ) {
                                    $url = get_permalink($form_settings['create_item_redirect_page']);
                                }
                                break;
                        }
                    }

                    $return_data = array(
                        'msg' => __( 'User created successfully', 'fael' )
                    );

                    if( $url ) {
                        $return_data['redirect'] = $url;
                    }

                    wp_send_json_success($return_data);
                }
                wp_send_json_error( ['msg' => $ret] );
                break;


            //create post
            case 'create_post':

                if( $post_id = $this->create_post($data, $current_form) ) {

                    //set terms taxonomies
                    if( isset( $data['taxonomy'] ) && is_array( $data['taxonomy'] ) ) {
                        foreach ( $data['taxonomy'] as $tax_name => $val ) {
                            wp_set_post_terms( $post_id, $val, $tax_name );
                        }
                    }

                    //set featured image
                    if( isset( $data['featured_image'] ) ) {

                        // $filename should be the path to a file in the upload directory.
                        $filename = $data['featured_image'];

                        // The ID of the post this attachment is for.
                        $parent_post_id = $post_id;

                        // Check the type of file. We'll use this as the 'post_mime_type'.
                        $filetype = wp_check_filetype( basename( $filename ), null );

                        // Get the path to the upload directory.
                        $wp_upload_dir = wp_upload_dir();

                        // Prepare an array of post data for the attachment.
                        $attachment = array(
                            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                            'post_mime_type' => $filetype['type'],
                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );

                        // Insert the attachment.
                        $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                        require_once( ABSPATH . 'wp-admin/includes/image.php' );

                        // Generate the metadata for the attachment, and update the database record.
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                        wp_update_attachment_metadata( $attach_id, $attach_data );

                        set_post_thumbnail( $parent_post_id, $attach_id );
                    }

                    //set system meta
                    do_action( 'fael_after_create_post', $post_id, $current_form );

                    $url = '';
                    if( isset( $form_settings['after_create_item'] ) ) {

                        switch ( $form_settings['after_create_item'] ) {
                            case 'redirect_url':
                                $url = isset( $form_settings['create_item_redirect_url']['url'] ) ? $form_settings['create_item_redirect_url']['url'] : '';
                                break;
                            case 'redirect_edit_item':
                                $url = FAEL_Functions()->get_post_edit_url($post_id);
                                break;
                            case 'view_post':
                                $url = FAEL_Functions()->get_post_view_url($post_id);
                                break;
                            case 'to_page':
                                if( $form_settings['create_item_redirect_page'] ) {
                                    $url = get_permalink($form_settings['create_item_redirect_page']);
                                }
                                break;
                        }
                    }

                    $return_data = array(
                        'msg' => __( 'Post created successfully', 'fael' )
                    );

                    if( $url ) {
                        $return_data['redirect'] = $url;
                    }

                    wp_send_json_success($return_data);
                };
                break;

            case 'create_taxonomy':
                if( $ret = $this->create_taxonomy( $data, $current_form )) {

                    $item_id = $ret['item_id'];
                    $postdata = $ret['postdata'];

                    //add system meta

                    //add form handle to $postdata
                    update_term_meta( $item_id, '__fael_form_handle', $_POST['form_handle']);
                    //add form page id as post meta
                    update_term_meta( $item_id, '__fael_form_page_id', $form_settings['__container_id'] );
                    //term author
                    update_term_meta( $item_id, '__fael_term_author', get_current_user_id() );


                    /**
                     * Set form settings
                     */
                    $form_settings = $current_form['form_settings'];

                    foreach ( $form_settings as $setting_name => $setting_value ) {
                        switch ( $setting_name ) {
                            case 'can_edit_term':
                                update_term_meta( $item_id, '__fael_can_edit_term', ( $setting_value == 'yes' ? $setting_value : 0) );
                                break;
                            case 'can_delete_term':
                                update_term_meta( $item_id, '__fael_can_delete_term', ( $setting_value == 'yes' ? $setting_value : 0) );
                                break;
                        }
                    }

                    //set system meta
                    do_action( 'fael_after_create_taxonomy', $item_id, $postdata, $current_form );

                    $url = '';
                    if( isset( $form_settings['after_create_item'] ) ) {

                        switch ( $form_settings['after_create_item'] ) {
                            case 'redirect_url':
                                $url = isset( $form_settings['create_item_redirect_url']['url'] ) ? $form_settings['create_item_redirect_url']['url'] : '';
                                break;
                            case 'redirect_edit_item':
                                $url = FAEL_Functions()->get_item_edit_url($item_id, 'taxonomy' );
                                break;
                            case 'view_post':
                                $url = FAEL_Functions()->get_item_view_url($item_id, 'taxonomy' );
                                break;
                            case 'to_page':
                                if( $form_settings['create_item_redirect_page'] ) {
                                    $url = get_permalink($form_settings['create_item_redirect_page']);
                                }
                                break;
                        }
                    }

                    $return_data = array(
                        'msg' => __( 'User created successfully', 'fael' )
                    );

                    if( $url ) {
                        $return_data['redirect'] = $url;
                    }

                    wp_send_json_success($return_data);
                }
                break;
            default:
                do_action( 'fael_create_item', $data, $current_form, $form_settings );
                break;
        }

        //if none of the above is created,
        // something went wrong
        $response['errors']['wrong'][] = __( 'Whoops ! Something went wrong.', 'fael' );
        wp_send_json_error([
            'success' =>  false,
            'msg' => __( 'Whoops ! Something went wrong.' ),
            'errors' => $response['errors']
        ]);
    }

    /**
     * @param $data
     * @param $current_form
     * @return array|bool
     */
    public function create_taxonomy( $data, $current_form ) {
        global $post;
        $postdata = array();
        /**
         * Set post data
         */
        foreach ( $data as $field => $value ) {
            if( $field == 'form_settings' ) {
                continue;
            } else {
                switch ( $field ) {
                    case '__item_ID':
                        //weather to update or create
                        $postdata['cat_ID'] = $value;
                        break;
                    case 'tag-name':
                        $postdata['cat_name'] = $value;
                        break;
                    case 'slug':
                        $postdata['category_nicename'] = $value;
                        break;
                    case 'description':
                        $postdata['category_description'] = $value;
                        break;
                    case 'parent':
                        $postdata['category_parent'] = $value;
                        break;
                    default:
                        $postdata = apply_filters( 'fael_prepare_taxdata', $postdata, $field, $value, $data );
                        break;
                }
            }
        }

        $postdata = apply_filters( 'fael_after_prepare_taxdata', $postdata, $current_form, $data );

        if( $ret = wp_insert_category( $postdata ) ) {
            return [ 'item_id' => $ret, 'postdata' => $postdata ];
        }

        return false;
    }

    /**
     * Create item
     *
     * @param $data
     * @param $current_form
     * @return int|WP_Error
     */
    public function create_post( $data, $current_form ) {

        global $post;
        $postdata = array();

        /**
         * Set post data
         */
        foreach ( $data as $field => $value ) {
            if( $field == 'form_settings' ) {
                continue;
            } else {
                switch ( $field ) {
                    case '__item_ID':
                        //weather to update or create
                        $postdata['ID'] = $value;
                        break;
                    case 'post_title':
                        $postdata['post_title'] = $value;
                        break;
                    case 'post_content':
                        $postdata['post_content'] = $value;
                        break;
                    case 'post_excerpt':
                        $postdata['post_excerpt'] = $value;
                        break;
                    case 'post_name':
                        $postdata['post_name'] = $value;
                        break;
                    case 'post_author':
                        $postdata['post_author'] = $value;
                        break;
                    case 'post_status':
                        $postdata['post_status'] = $value;
                        break;
                    case 'featured_image':
                        //will be set after creating post
                        break;
                    case 'taxonomy':
                        //will be set after creating post
                        break;
                    default:
                        $postdata['meta_input'][$field] = $value;
                        break;
                }
            }
        }

        /**
         * Set post form settings
         */
        $form_settings = $current_form['form_settings'];

        foreach ( $form_settings as $setting_name => $setting_value ) {
            switch ( $setting_name ) {
                case 'post_type':
                    if( $setting_value == 'default' ) {
                        if( !isset( $postdata['post_type'] ) ) {
                            $postdata['post_type'] = 'post';
                        }
                    } else {
                        $postdata['post_type'] = $setting_value;
                    }
                    break;
                case 'post_status':
                    //post_status field will applied only
                    //if the status setting is set to default in the form settings
                    if( $setting_value == 'default' ) {
                        if( !isset( $postdata['post_status'] ) ) {
                            $postdata['post_status'] = 'draft';
                        }
                    } else {
                        $postdata['post_status'] = $setting_value;
                    }
                    break;
                case 'comment_status':
                    $postdata['comment_status'] = $setting_value;
                    break;
                case 'can_edit_post' :
                    $postdata['meta_input']['__fael_can_edit_post'] = ( $setting_value == 'yes' ? $setting_value : 0);
                    break;
                case 'can_delete_post':
                    $postdata['meta_input']['__fael_can_delete_post'] = ( $setting_value == 'yes' ? $setting_value : 0);
                    break;
                case 'can_draft_post':
                    $postdata['meta_input']['__fael_can_draft_post'] = ( $setting_value == 'yes' ? $setting_value : 0);
                    break;
                case 'post_needs_review':
                    if( $setting_value == 'yes' ) {
                        $postdata['meta_input']['__fael_post_needs_review'] = $setting_value;
                        $postdata['post_status'] = 'private';
                    }
                    break;
            }
        }

        //add form handle to $postdata
        $postdata['meta_input']['__fael_form_handle'] = $_POST['form_handle'];

        //add form page id as post meta
        $postdata['meta_input']['__fael_form_page_id'] = $form_settings['__container_id'];

        $postdata = apply_filters( 'fael_prepare_postdata', $postdata, $current_form);

        return wp_insert_post($postdata);
    }

    /**
     * Creaate User
     *
     * @param $data
     * @param $current_form
     */
    public function create_user( $data, $current_form ) {
        $postdata = array();

        /**
         * Set post data
         */

        foreach ( $data as $field => $value ) {
            if( $field == 'form_settings' ) {
                continue;
            } else {
                switch ( $field ) {
                    case '__item_ID':
                        //weather to update or create
                        $postdata['ID'] = $value;
                        break;
                    case 'user_login':
                        $postdata['user_login'] = $value;
                        break;
                    case 'first_name':
                        $postdata['first_name'] = $value;
                        break;
                    case 'last_name':
                        $postdata['last_name'] = $value;
                        break;
                    case 'user_email':
                        $postdata['user_email'] = $value;
                        break;
                    case 'nickname':
                        $postdata['nickname'] = $value;
                        break;
                    case 'display_name':
                        $postdata['display_name'] = $value;
                        break;
                    case 'url':
                        $postdata['url'] = $value;
                        break;
                    case 'description':
                        $postdata['description'] = $value;
                        break;
                    case 'password':
                        $postdata['user_pass'] = $value;
                        break;
                    default:
                        $postdata = apply_filters( 'fael_prepare_userdata', $postdata, $field, $value, $data );
                        break;
                }
            }
        }

        $postdata = apply_filters( 'fael_after_prepare_userdata', $postdata, $current_form, $data );

        $ret = wp_insert_user( $postdata );

        if( !is_wp_error( $ret ) ) {
            return [ 'item_id' => $ret, 'postdata' => $postdata ];
        }

        return $ret->get_error_message();
    }


    /**
     * @param $current_form
     * @param $formdata
     * @return array
     */
    public function validate_data( $current_form, $formdata ) {
        $data = [];
        $errors = [];

        //check if there is recaptcha
        if( isset( $current_form['recaptcha'] ) ) {
            if( !isset( $formdata['recaptcha'] ) ) {
                return [
                    'data' => [],
                    'errors' => [
                        __( 'Recaptcha should be verified', 'fael' )
                    ]
                ];
            }else{
                $response = isset( $formdata['recaptcha']['value'] ) ? $formdata['recaptcha']['value'] : '';

                $secret = FAEL_Functions()->get_option( 'fael_general', 'recaptcha_private' );
                $verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
                $captcha_success=json_decode($verify);

                if ( $captcha_success->success == false ) {
                    //This user was not verified by recaptcha.
                    return [
                        'data' => [],
                        'errors' => [
                            __( 'Recaptcha should be verified', 'fael' )
                        ]
                    ];
                }

            }

        }


        //pre populate data before filter
        if( isset( $formdata['__item_ID'] ) && is_numeric( $formdata['__item_ID'] ) ) {
            $data['__item_ID'] = $formdata['__item_ID'];
        }

        //check capability  to create/edit post
        switch ( $current_form['form_settings']['submit_type'] ) {
            case 'create_post':
                if( isset( $data['__item_ID'] ) ) {
                    $item = get_post( $data['__item_ID'] );

                    if ( !$item || $item->post_author != get_current_user_id() ) {
                        $errors[] = __( 'You are not allowed to perform this action', 'fael' );
                    }
                }
                break;
            case 'create_user':
                if( isset( $data['__item_ID'] ) ) {
                    $item = get_user_by( 'ID', $data['__item_ID'] );

                    if ( !$item || $item->ID != get_current_user_id() ) {
                        $errors[] = __( 'You are not allowed to perform this action', 'fael' );
                    }
                }
                break;
            case 'create_taxonomy':
                if( isset( $data['__item_ID'] ) ) {
                    $item = get_term( $data['__item_ID'] );

                    if ( !$item || $item->__fael_term_author != get_current_user_id() ) {
                        $errors[] = __( 'You are not allowed to perform this action', 'fael' );
                    }
                }
                break;
        }

        if( empty( $errors ) ) {
            foreach ( $current_form as $field => $field_data ) {
                $should_check = apply_filters( 'fael_before-field_rules_validations', true, $field_data, $formdata, $current_form, $field );
                if( !$should_check ) continue;

                switch ( $field ) {
                    case 'taxonomy':
                        foreach ( $field_data as $tax_name => $tax_field_data ) {

                            $ret = $this->check_rules($formdata[$field][$tax_name], $tax_field_data );
                            if( empty( $ret ) ) {
                                if( !$formdata[$field][$tax_name]['value'] ) {
                                    $formdata[$field][$tax_name]['value'] = array();
                                }
                                $data['taxonomy'][$tax_name] = $formdata[$field][$tax_name]['value'];
                            } else {
                                //$errors = array_merge($errors,$ret);
                                $errors[$field] = $ret;
                            }
                        }

                        break;
                    default:
                        $ret = $this->check_rules( $formdata[$field], $field_data );
                        if( empty( $ret ) ) {
                            $data[$field] = $formdata[$field]['value'];
                        } else {
                            //$errors = array_merge($errors,$ret);
                            $errors[$field] = $ret;
                        }
                        break;
                }
            }
        }

        return [
            'data' => $data,
            'errors' => $errors
        ];
    }

    /**
     * @param $field_data
     * @param $blueprint_form
     * @return array
     */
    public function check_rules( $field_data, $blueprint_form ) {
        $errors = [];
        foreach ( $blueprint_form['rules'] as $rule => $rule_value ) {
            switch ( $rule ) {
                case 'is_required':
                    if( $rule_value == 'yes' ) {
                        if ( !isset( $field_data['value'] ) || empty( $field_data['value'] ) ) {
                            $errors[] = 'Required field is empty';
                        }
                    }
                    break;
            }
        }
        return $errors;
    }


    public function fael_fetch_data() {
        global $fael_editor_actions;
        $data = [];
        $fetch_data = $_POST['fetch_data'];

        if( isset( $_POST['widget'] ) && isset( $fael_editor_actions[$_POST['widget']] ) ) {
            foreach ( $fetch_data as $k => $field ) {
                $data[$field] = $fael_editor_actions[$_POST['widget']][$field]();
            }
        }

        /*foreach ( $fetch_data as $field => $options ) {
            switch ( $field ) {
                case 'post_type':
                    $data[$field] = get_post_types($options);
                    break;
            }
        }*/
        wp_send_json_success(array(
            'data' => $data
        ));
    }
}

function FAEL_Ajax() {
    return FAEL_Ajax::instance();
}

FAEL_Ajax::instance();
