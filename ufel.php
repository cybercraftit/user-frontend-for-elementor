<?php
/**
 * Plugin Name: User Frontend for Elementor
 * Description: Create full featured admin panel/dashboard for the frontend.
 * Plugin URI:
 * Version:     2.0.0.5
 * Author:      CyberCraft
 * Author URI:
 * Text Domain: fael
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Defining plugin constants.
 *
 */
define('FAEL_NAME', 'User Frontend for Elementor');
define('FAEL_ROOT', dirname(__FILE__));
define('FAEL_PLUGIN_FILE', __FILE__);
define('FAEL_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('FAEL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('FAEL_PLUGIN_URL', plugins_url('/', __FILE__));
define('FAEL_PLUGIN_VERSION', '2.0.0.4');
define('FAEL_ASSET_PATH', FAEL_PLUGIN_PATH . '/assets');
define('FAEL_ASSET_URL', FAEL_PLUGIN_URL . '/assets');

if( !function_exists( 'pri' ) ) {
    function pri( $data ) {
        echo '<pre>';print_r($data);echo '</pre>';
    }
}

if ( ! function_exists( 'fael_is_elementor_installed' ) ) {

    function fael_is_elementor_installed() {
        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();

        return isset( $installed_plugins[ $file_path ] );
    }
}

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function fael_elementor_pro_fail_load() {
    $screen = get_current_screen();
    if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
        return;
    }

    $plugin = 'elementor/elementor.php';

    if ( fael_is_elementor_installed() ) {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

        $message = '<p>' . __( 'Elementor is not activated. You need to activate Elementor to get the User Frontend for Elementor working.', 'fael' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'fael' ) ) . '</p>';
    } else {
        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }

        $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

        $message = '<p>' . __( 'Elementor is not activated. You need to install and activate Elementor to get the User Frontend for Elementor working.', 'fael' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'fael' ) ) . '</p>';
    }

    echo '<div class="error"><p>' . $message . '</p></div>';
}



final class FAEL_Init {

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
     * @return FAEL_Init An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public function __construct() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
        register_activation_hook( __FILE__, array( $this, 'on_activate' ) );

        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', function () {
                ?>
                <?php fael_elementor_pro_fail_load(); ?>
                <?php
            } );
        }

        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts_styles' ) );
        add_action( 'wp_footer', array( $this, 'footer_scripts_styles' ) );
        add_action( 'elementor/editor/before_enqueue_scripts', function() {
            wp_enqueue_style(
                'fael-editor-icons-css',
                FAEL_ASSET_URL.'/css/fontello.css',
                [
                    'elementor-editor', // dependency
                ],
                false,
                true // in_footer
            );
            wp_enqueue_script(
                'fael-editor-app-js',
                FAEL_ASSET_URL.'/js/editor-app.js',
                [
                    'elementor-editor', // dependency
                ],
                false,
                true // in_footer
            );
        } );
        add_action( 'save_post', array( $this, 'save_form_fields' ), 999);

        //for media uploader
        add_filter( 'ajax_query_attachments_args', 'the_dramatist_filter_media' );

        $this->includes();
    }

    /**
     * Load the translation file for current language.
     *
     * @since version 2.0.0.2
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'fael', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    public function on_activate() {

        //add fael_form to post type support
        FAEL_Functions()->add_cpt_support();

        $fael_general = FAEL_Functions()->get_option('fael_general');
        $fael_frontend_posting = FAEL_Functions()->get_option('fael_frontend_posting');

        if( !$fael_general || !is_array( $fael_general ) ) {
            $fael_general = array(
                'admin_access' => [
                    'administrator' => 'administrator'
                ]
            );
            FAEL_Functions()->set_option( 'fael_general', $fael_general );
        }
        if( !$fael_frontend_posting || !is_array( $fael_frontend_posting ) ) {
            $fael_frontend_posting = array(
                'default_post_owner' => get_current_user_id()
            );
            FAEL_Functions()->set_option( 'fael_frontend_posting', $fael_frontend_posting );
        }
    }

    /**
     * This filter insures users only see their own media
     */
    function the_dramatist_filter_media( $query ) {
        // admins get to see everything
        if ( ! current_user_can( 'manage_options' ) )
            $query['author'] = get_current_user_id();
        return $query;
    }

    public function includes() {
        //news
        include_once 'news.php';

        //include moduldes
        include_once 'includes/modules/class-login.php';

        require_once 'includes/class-ajax.php';
        require_once 'vote.php';
        require_once 'widgets-loader.php';
        require_once 'page-settings.php';

        require_once 'includes/editor-actions.php';

        require_once 'includes/class-shortcodes.php';
        require_once 'includes/class-ufe-forms.php';
        require_once 'includes/class-functions.php';
        require_once 'includes/class-page-frontend.php';
        require_once 'includes/class-widget-functions.php';
        require_once 'includes/class-accessibility-functions.php';
        require_once 'includes/class-admin-settings.php';
        require_once 'includes/class-settings-options.php';
        require_once 'includes/class-form-elements.php';
    }

    public function wp_enqueue_scripts_styles() {

        global $ufe_vueobject;
        if( !is_array( $ufe_vueobject ) ) $ufe_vueobject = [];
        wp_enqueue_style( 'fael-app-css', FAEL_ASSET_URL.'/css/app.css');

        //for recaptcha field
        ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <?php
        //js
        wp_enqueue_media();
        wp_enqueue_script(
            'some-script',
            FAEL_ASSET_URL . '/js/media-uploader.js',
            // if you are building a plugin
            // plugins_url( '/', __FILE__ ) . '/js/media-uploader.js',
            array( 'jquery' ),
            null
        );
        ?>
        <script>
            console.log('<?php echo admin_url('admin-ajax.php'); ?>')

            var fael_vuedata = {
                data: {
                    errors: {}
                },
                methods: {
                    open_uploader: function (form_handle, name) {
                        var _this = this;
                        (function($) {
                            // When the DOM is ready.
                            $(function() {
                                var file_frame; // variable for the wp.media file_frame

                                // attach a click event (or whatever you want) to some element on your page
                                // if the file_frame has already been created, just reuse it
                                if ( file_frame ) {
                                    file_frame.open();
                                    return;
                                }

                                file_frame = wp.media.frames.file_frame = wp.media({
                                    title: $( this ).data( 'uploader_title' ),
                                    button: {
                                        text: $( this ).data( 'uploader_button_text' ),
                                    },
                                    multiple: false // set this to true for multiple file selection
                                });

                                file_frame.on( 'select', function() {
                                    attachment = file_frame.state().get('selection').first().toJSON();

                                    //$( '#frontend-button' ).hide();
                                    //$( '#frontend-image' ).attr('src', attachment.url);
                                    _this.fael_forms[form_handle][name].value = attachment.url;
                                });

                                file_frame.open();
                            });

                        })(jQuery);
                    },
                    remove_media: function (form_handle, name) {
                        this.fael_forms[form_handle][name].value = '';
                    },
                    submit_content: function (form_handle) {
                        var _this = this;
                        for( var k in _this.fael_forms[form_handle] ) {
                            if( k == 'form_settings' ) continue;
                            if( typeof _this.fael_forms[form_handle][k].widget != 'undefined' ) {
                                if( _this.fael_forms[form_handle][k].widget == 'FAEL_Post_Excerpt'
                                    || _this.fael_forms[form_handle][k].widget == 'FAEL_Post_Content'
                                    || ( _this.fael_forms[form_handle][k].widget == 'FAEL_Textarea'
                                        && this.fael_forms[form_handle][k].is_rich == true )

                                ) {
                                    if( !tinymce.get(form_handle + '-' + k ) ) continue;
                                    _this.fael_forms[form_handle][k].value = tinymce.get(form_handle + '-' + k ).getContent();
                                } else if ( _this.fael_forms[form_handle][k].widget == 'FAEL_Recaptcha' ) {
                                    _this.fael_forms[form_handle][k].value = grecaptcha.getResponse()
                                }
                            }
                        }

                        jQuery.post(
                            '<?php echo admin_url('admin-ajax.php'); ?>',
                            {
                                action: 'fael_form_submit',
                                formdata: _this.fael_forms[form_handle],
                                //__fael_form_page_id: '<?php echo get_queried_object_id(); ?>',
                                form_handle: form_handle
                            },
                            function (data) {
                                console.log(data);
                                if( data.success ) {
                                    if( typeof data.data.redirect != 'undefined' ) {
                                        window.location = data.data.redirect;
                                    }
                                } else {
                                    if( data.data.errors ) {
                                        //_this.errors = data.data.errors;
                                        Vue.set(_this.errors, form_handle, data.data.errors );
                                        var error_html = '';
                                        for( var k in data.data.errors ) {
                                            error_html = error_html + data.data.errors[k] + '<br>';
                                        }
                                    }
                                }
                            }
                        )
                    }
                },
                computed: {
                },
                created: function () {
                },
                mounted: function () {
                }
            }
        </script>
<?php

    }

    public function admin_enqueue_scripts_styles() {
        //css
        if( !FAEL_Functions()->is_pro() ) {
            wp_enqueue_style( 'fael-promo-css', FAEL_ASSET_URL.'/css/promo.css' );
        }
    }

    /**
     * Scripts to load in footer
     */
    public function footer_scripts_styles() {
        global $has_fael_widget,  $ufe_vueobject;
        $fael_forms = FAEL_Form_Elements()->get_form_elements();

        if( $has_fael_widget ) { ?>
            <script>
                var ufe_vueobject = JSON.parse('<?php echo json_encode($ufe_vueobject); ?>');
                var fael_forms = JSON.parse(atob('<?php echo base64_encode(json_encode($fael_forms)); ?>'));
                fael_vuedata.data = Object.assign(fael_vuedata.data,{
                    fael_forms : fael_forms
                });
            </script>
<?php
            wp_enqueue_script( 'fael-app-js', FAEL_ASSET_URL.'/js/app.js', array('jquery'), false, true );
        }
    }

    /**
     * Save form fields
     * @param $post_id
     */
    public function save_form_fields( $post_id ) {
        global  $post, $fael_widgets;

        //save widgets data in post
        update_post_meta( $post_id, 'fael_widgets', $fael_widgets );

        $fael_forms = FAEL_Form_Elements()->get_form_elements();

        if( isset( $fael_forms ) && is_array( $fael_forms ) && !empty( $fael_forms ) ) {

            //if form post, get page settings and
            // and set them as each form's form settings of that.
            if( get_post_type( $post_id ) == 'fael_form' ) {

                $page_settings = FAEL_Page_Settings()->get_page_settings($post_id);

                $form_settings = apply_filters( 'fael_set_form_settings', array(
                    'submit_type' => $page_settings['submit_type'],
                    'post_type' => $page_settings['post_type'],
                    'post_status' => $page_settings['status'],
                    'comment_status' => $page_settings['comment_status'],
                    'after_create_item' => $page_settings['after_create_item'],
                    'create_item_redirect_url' => $page_settings['create_item_redirect_url'],
                    'create_item_redirect_page' => $page_settings['create_item_redirect_page'],
                    'can_edit_post' => $page_settings['can_edit_post'],
                    'can_edit_user' => $page_settings['can_edit_user'],
                    'can_delete_post' => $page_settings['can_delete_post'],
                    'can_delete_user' => $page_settings['can_delete_user'],
                    'can_draft_post' => $page_settings['can_draft_post'],
                    'post_needs_review' => $page_settings['post_needs_review'],
                    'fael_accessibility' => $page_settings['fael_page_accessability'],
                    'fael_access_by_role' => $page_settings['fael_page_access_by_role'],
                    'fael_accessible_roles' => $page_settings['fael_page_accessible_roles'],
                    '__container_id' => get_the_ID()
                ), $page_settings );

                foreach ( $fael_forms as $handle => $fael_form ) {
                    !isset( $fael_forms[$handle]['form_settings'] ) ? $fael_forms[$handle]['form_settings'] = array() : '';
                    $fael_forms[$handle]['form_settings'] = array_merge($fael_forms[$handle]['form_settings'], $form_settings );
                }

                FAEL_Form_Elements()->set_form_elements( $fael_forms );
            }
            update_post_meta( $post_id, 'fael_forms', $fael_forms );
        }
    }
}

FAEL_Init::instance();

if( !FAEL_Functions()->is_pro() ) {
    require_once FAEL_ROOT . '/promo.php';
}
