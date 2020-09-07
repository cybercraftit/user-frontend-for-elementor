<?php
//delete_option( 'ufel_vote' );
//delete_option( 'ufel_later' );
class UFEL_Vote {
    static $text_domain = 'ufel';
    static $plugin_name = 'user-frontend-for-elementor';
    static $plugin_label = 'User Frontend for Elementor';
    static $fb_title = 'User Frontend for Elementor - must have WordPress plugin #UserFrontendforElementor';
    static $tw_title = 'User Frontend for Elementor - must have WordPress plugin #UserFrontendforElementor';
    static $plugin_wp_url = 'https://wordpress.org/plugins/user-frontend-for-elementor/';

    public function __construct() {
        add_action( 'init', array( __CLASS__, 'vote_init' ) );
        add_action( 'wp_ajax_ufel_vote',  array( __CLASS__, 'vote' ) );
    }

    public static function vote_init() {
        /*$ufel_votes = get_option( 'ufel_vote' );
        !is_array($ufel_votes) ? $ufel_votes = array() : '';*/

        //if ( in_array( $vote, array( 'yes', 'tweet' , 'facebook', 'suggest', 'no' ) ) || !$timein ) return;
        add_action( 'admin_notices', array( __CLASS__, 'message' ) );
        add_action( 'admin_head',      array( __CLASS__, 'register' ) );
        add_action( 'admin_footer',    array( __CLASS__, 'enqueue' ) );
    }

    public static function register() {
        wp_register_style( 'ufel-vote', FAEL_ASSET_URL.'/css/vote.css', false );
        wp_register_script( 'ufel-vote', FAEL_ASSET_URL.'/js/vote.js', array( 'jquery' ), false, true );
    }

    public static function enqueue() {
        wp_enqueue_style( 'ufel-vote' );
        wp_enqueue_script( 'ufel-vote' );
        wp_localize_script('ufel-vote', 'cc_vote', array(
                'text_domain' => self::$text_domain
        ));
    }

    public static function message() {
        $timein = time() > ( get_option( 'ufel_later' ) );
        if ( !$timein ) return;

        $ufel_votes = get_option( 'ufel_vote' );
        !is_array($ufel_votes) ? $ufel_votes = array() : '';

        if( isset( $ufel_votes['no'] ) ){
            return;
        } else {

            $btn_str = '';
            if( !isset( $ufel_votes['yes'] ) ) {
                $btn_str .= '<a href="'.admin_url( 'admin-ajax.php' ).'?action=ufel_vote&amp;vote=yes" class="'.self::$text_domain.'-vote-action '.self::$text_domain.'-vote-button button button-small button-primary" data-action="http://wordpress.org/support/view/plugin-reviews/'. self::$plugin_name .'?rate=5#postform">'.__( 'Rate us', 'sm' ).'</a>';
            }
            if( !isset( $ufel_votes['tweet'] ) ) {
                $btn_str .= '<a href="'.admin_url( 'admin-ajax.php' ).'?action=ufel_vote&amp;vote=tweet" class="'.self::$text_domain.'-vote-action '.self::$text_domain.'-vote-button button button-small" data-action="http://twitter.com/share?url='.self::$plugin_wp_url.'&amp;text='.urlencode( __( self::$tw_title, 'sm' ) ).'">'.__( 'Tweet', 'sm' ).'</a>';
            }
            if( !isset( $ufel_votes['facebook'] ) ) {
                $btn_str .= '<a href="'.admin_url( 'admin-ajax.php' ).'?action=ufel_vote&amp;vote=facebook" class="'.self::$text_domain.'-vote-action '.self::$text_domain.'-vote-button button button-small" data-action="http://facebook.com/sharer?u='.self::$plugin_wp_url.'&amp;text='.urlencode( __( self::$fb_title, 'sm' ) ).'">'.__( 'Share on facebook', 'sm' ).'</a>';
            }
            if( !isset( $ufel_votes['no'] ) ) {
                $btn_str .= '<a href="'.admin_url( 'admin-ajax.php' ).'?action=ufel_vote&amp;vote=no" class="'.self::$text_domain.'-vote-action '.self::$text_domain.'-vote-button '.self::$text_domain.'-cancel-button button button-small">'.__( 'No, thanks', 'sm' ).'</a>';
            }

            $btn_str .= '<a href="'.admin_url( 'admin-ajax.php' ).'?action=ufel_vote&amp;vote=suggest" class="'.self::$text_domain.'-vote-action '.self::$text_domain.'-vote-button sugget-button button button-small" data-action="http://cybercraftit.com/contact/">'.__( 'Suggest us', 'sm' ).'</a>';

            if( !isset( $ufel_votes['later'] ) ) {
                $btn_str .= '<a href="'.admin_url( 'admin-ajax.php' ).'?action=ufel_vote&amp;vote=later" class="'.self::$text_domain.'-vote-action '.self::$text_domain.'-vote-button button button-small">'.__( 'Remind me later', 'sm' ).'</a>';
            }
        }



        if( !empty( $btn_str ) ) :
        ?>
        <div class="sm-vote">
            <div class="sm-vote-wrap">
                <div class="sm-vote-gravatar">
                    <a href="http://cybercraftit.com/" target="_blank"><img src="https://ps.w.org/user-frontend-for-elementor/assets/icon-128x128.png?rev=2203573" alt="<?php _e( 'User Frontend for Elementor', 'sm' ); ?>" width="50" height="50"></a>
                </div>
                <div class="sm-vote-message">
                    <p><?php _e( '<h3>Thanks for using <strong>'. self::$plugin_label.'</strong>!<br></h3>If you find this plugin useful, please rate us, share and tweet to let
others know about it, and help us improving it by your valuable suggestions .<br><b>Thank you!</b>', 'sm' ); ?></p>
                    <p>
                        <?php echo $btn_str; ?>
                    </p>
                </div>
                <div class="sm-vote-clear"></div>
            </div>
        </div>
<?php
endif;
    }

    public static function vote() {
        $vote = sanitize_key( $_GET['vote'] );

        if ( !is_user_logged_in() || !in_array( $vote, array( 'yes', 'tweet' , 'facebook', 'no', 'suggest', 'later'  ) ) ) die( 'error' );

        $ufel_votes = get_option( 'ufel_vote' );
        !is_array($ufel_votes)?$ufel_votes = array() : '';
        $ufel_votes[$vote] = $vote;
        update_option( 'ufel_vote', $ufel_votes );

        if ( $vote === 'later' ) update_option( 'ufel_later', time() + 60*60*24*3 );
        die( 'OK: ' . $vote );
    }
}

new UFEL_Vote();