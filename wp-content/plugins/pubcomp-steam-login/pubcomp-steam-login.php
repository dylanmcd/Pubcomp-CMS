<?php
/*
Plugin Name: PubComp Steam Login
Plugin URL: http://pubcomp.com/
Description: Provides the ability to login and register for the PubComp Wordpress site through Steam
Version: 0.1
Author: The PubComp team
Author URL: http://pubcomp.com/
*/

//add_action( 'init', 'pubcomp_init' );
class PubcompSteamLogin {
    public $OpenId;
    public $version;
    public $db_version;

    function __construct() {
        if (!session_id()) {
            session_start();
        }
        require_once(dirname( __FILE__ ) . '/openid.php');
        require_once(dirname( __FILE__ ) . '/PubcompPlayer.php');
        $this->OpenId = new LightOpenId();
        $this->OpenId->identity = 'http://steamcommunity.com/openid';
        $this->PubcompPlayer = new PubcompPlayer();
        $this->version = '0.1';
        $this->db_version = '0.1';

        add_action( 'bp_before_account_details_fields', array($this, 'pubcomp_before_account_details' ));
        add_action( 'bp_after_account_details_fields', array($this,'clearBuffer' ));
        add_action( 'bp_before_signup_profile_fields', array($this, 'startBuffer'));
        add_action( 'bp_after_signup_profile_fields', array($this,'clearBuffer' ));
        add_action( 'bp_before_sidebar_login_form', array($this, 'startBuffer'));
        add_action( 'bp_after_sidebar_login_form', array($this,'clearBuffer' ));
        add_action( 'login_init', array($this, 'login') );
        remove_action('wp', 'bp_core_screen_activation');
	    add_action( 'wp', array($this,'checkSignup'), 3 );
        register_activation_hook(__FILE__, array($this, 'install'));
    }

    function install() {
        global $wpdb;
        $installed_version = get_option('pubcomp_steam_login_db_version');
        if ($installed_version != $this->db_version) {
            $table = $this->PubcompPlayer->table_name;
            $sql = "CREATE TABLE " . $table . " (
               id int(11) NOT NULL AUTO_INCREMENT,
               user_id bigint(20) NOT NULL,
               steam_id_64 varchar(64) NULL,
               INDEX (steam_id_64),
               UNIQUE KEY id (id)
             );";
             require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
             dbDelta($sql);
             update_option('pubcomp_steam_login_db_version', $this->db_version);
        }
    }

    /*
     * Checks to see if the user is trying to signup through BuddyPress
     */
    function checkSignup() {
        global $bp;
        if ($bp->current_component != BP_REGISTER_SLUG) {
            return false;
        }
        header('Location: ' . wp_login_url());
        exit();
    }

    function doFinalize() {
        // To "Finalize" your account means to add a username and email
        if (isset($_SESSION['steam_id']) && $this->PubcompPlayer->playerExists($_SESSION['steam_id']) && !$this->PubcompPlayer->isFinalized()) {
            if (isset($_REQUEST['clear_finalize'])) {
                $this->clearSteamId();
                exit(json_encode(array('success' => 1)));
            }
            else if ($name = $this->PubcompPlayer->finalizePlayer($_SESSION['steam_id'], $_REQUEST['username'], $_REQUEST['email'])) {
                $this->clearSteamId();
                exit(json_encode(array('success' => 1, 'name' => $name)));
            } else {
                $errors = $this->PubcompPlayer->flushErrors();
                exit(json_encode(array('error' => 1, 'errors'=>$errors)));
            }
        }
        exit(json_encode(array('error' => 1, 'errors'=> array(0 => array('type'=>'steam_id_not_found', 'message'=>'No steam id was found. Please try logging in again')))));
    }

    function login() {
        if (isset($_GET['action']) && $_GET['action'] == 'logout') {
            wp_logout();

            $redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : 'wp-login.php?loggedout=true';
            wp_safe_redirect( $redirect_to );
            exit();
        }
        if (isset($_REQUEST['finalize_signup']) && $_REQUEST['finalize_signup']) {
            $this->doFinalize();
        }
        try {
            $validated = $this->OpenId->validate();
        } catch (ErrorException $e) {
            // TODO: Make this pretty
            $validated = false; 
            die("Steam login servers appear to be down. Please try logging into Pubcomp again when they are back up.");
        } 
        if ($validated) {
            $steam_id = $this->PubcompPlayer->getSteamIdFromIdentity($this->OpenId->identity);
            $_SESSION['steam_id'] = $steam_id;
            if ($this->PubcompPlayer->playerExists($steam_id)) {
                if ($this->PubcompPlayer->isFinalized()) {
                    // User is already finalized, log them in
                    $this->PubcompPlayer->login();
                }                 
                $this->doRedirect();
            } else {
                // First time login, create player
                $player = $this->PubcompPlayer->getPlayerInfo($steam_id);
                // TODO: Real error handling, although this probably shouldn't ever happen
                if (!$player) {
                    exit('There was an error fetching your information. Please attempt to login again.');
                }
                $this->PubcompPlayer->createPlayer($steam_id);
                $this->doRedirect();
            }
        } else {
            header('Location: ' . $this->OpenId->authUrl()); 
        }
        exit();
    }

    function clearSteamId() {
        $_SESSION['steam_id'] = null;
    }

    function getFinalizeJs() {
        if (!isset($_SESSION['steam_id']) || $this->PubcompPlayer->isFinalized()) {
            return '';
        }
        $login_url = wp_login_url(get_permalink());
        $js = <<<EOT
<script type="text/javascript">
jQuery(document).ready(function($) {
    $("#signup-dialog").dialog({width: 600});

    $("#complete_signup").click(function(e) {
        var self = $(this);
        e.preventDefault();
        $("#signup-form-errors").html('');
        self.spinner({ position: 'center', hide: true, zIndex:9999 });
        var params = { "finalize_signup": 1, "username": $("#signup_username").val(), "email": $("#signup_email").val() };
        $.post("$login_url", params, function(data) {
            self.spinner('remove');
            var error_html = '';
            if (data.success) {
                $(".signup_dialog").dialog('close');
                window.location.reload();
            } else {
                for (var i = 0; i<data.errors.length; i++) {
                    if (error_html == '') {
                        error_html = data.errors[i].message;
                    } else {
                        error_html += '<br />' + data.errors[i].message;
                    }

                    $("#signup-form-errors").html(error_html);
                }
            }
        }, "json");
    });

    $("#maybe_later").click(function(e) {
        var self = $(this);
        e.preventDefault();
        var params = { "finalize_signup": 1, "clear_finalize": 1 };
        self.spinner({ position: 'center', hide: true, zIndex:9999 });
        $.post("$login_url", params, function(data) {
            self.spinner('remove');
            $(".signup_dialog").dialog('close');
        }, "json");
    });
});
</script>
EOT;
        return $js;
    }

    function getFinalizeHtml() {
        if (!isset($_SESSION['steam_id']) || $this->PubcompPlayer->isFinalized()) {
            return '';
        } 
        $steam_user = $this->PubcompPlayer->getPlayerInfo($_SESSION['steam_id']);
        $provisional_name = sanitize_user($steam_user['personaname']);
        $html = <<<EOT
<h3>Welcome to PubComp! To finalize your account, simply enter your username and email.</h3>
<p>For your username, we recommend it be similiar to your gamer alias on Steam, but without any clan/community tags. Characters accepted are alphanumeric, spaces. PubComp reserves the right to change your username if it is deemed offensive or malicious. We won't spam your email.</p>
<p>
<form class="pubcomp-form"> 
<div class="errors" id="signup-form-errors"></div>
<label for="signup_username">Username: </label>
<input type="text" name="signup_username" id="signup_username" value="$provisional_name" /> 
<label for="signup_email">Email: </label>
<input type="text" name="signup_email" id="signup_email" /><br />
<input type="submit" id="complete_signup" class="button" value="Complete Signup" />
<input type="submit" id="maybe_later" class="button" value="Maybe Later" />
</form>
</p>
EOT;
        return $html;
    }

    function doRedirect() {
        if ( isset( $_REQUEST['redirect_to'] ) ) {
             wp_safe_redirect($_REQUEST['redirect_to']);
        } else {
            header( 'Location: ' . get_home_url() );
        }
        exit;
    }

    function pubcomp_fetch_player( $identity ) {
        $id = str_replace( 'http://steamcommunity.com/openid/id/', '', $identity );
        if ( !$player = get_transient( 'pubcomp_steam_user_' . $id ) ) {
            $url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . PUBCOMP_STEAM_KEY . '&steamids=' . $id;
            $request = wp_remote_get( $url );
            $request['body'] = preg_replace( '/: (\d+),/', ': "$1",', $request['body'] );
            $player = json_decode( $request['body'], true );
            $player = $player['response']['players'][0];
            set_transient( 'pubcomp_steam_user_' . $id, $player, 3600 );
        }
        return $player;
    }


    function pubcomp_create_user() {

    }

    function pubcomp_init() {
        global $pubcomp_signup_message;
        if ( isset( $_POST['steam_login'] ) && isset( $_POST['pubcomp_wpnonce'] ) && wp_verify_nonce( $_POST['pubcomp_wpnonce'], 'steam_login-' . $_POST['steam_login'] ) ) {
            if ( is_email( $_POST['email'] ) ) {
                $user = wp_create_user( 'steam_' . $_POST['steam_login'], wp_generate_password( 32 ), $_POST['email'] );
                $player = pubcomp_fetch_player( $_POST['steam_login'] );
                if ( is_wp_error( $user ) ) {
                    $pubcomp_signup_message = 'There was an error registering your account. Did you already register?';
                } else {
                    add_user_meta( $user, 'pubcomp_steam_id', $player['steamid'] );
                    update_user_meta( $user, 'display_name', $player['personaname'] );
                    $pubcomp_signup_message = 'All set! Now you can log in.';
                }
            } else {
                $player = pubcomp_fetch_player( $_POST['steam_login'] );
                $pubcomp_signup_message = 'That doesn\'t look like an email address to me...' .
                '<input type="text" name="email" placeholder="Email address" />' .
                '<input type="hidden" name="steam_login" value="' . $player['steamid'] . '" />' .
                '<input type="hidden" name="pubcomp_wpnonce" value="' . wp_create_nonce( 'steam_login-' . $player['steamid'] ) . '"/>';
            }
        }
        if ( isset( $_POST['signup_submit'] ) )
            unset( $_POST['signup_submit'] );

        if ( ( isset( $_POST['steam_login'] ) && $_POST['steam_login'] == 'go' ) || ( basename( $_SERVER['PHP_SELF'] ) == 'wp-login.php' && !isset( $_GET['openid_ns'] ) && ( !isset( $_GET['action'] ) || $_GET['action'] != 'logout' ) ) ) {
            pubcomp_get_openid()->identity = 'http://steamcommunity.com/openid';
            header( 'Location: ' . pubcomp_get_openid()->authUrl() );
            exit;
        } elseif ( basename( $_SERVER['PHP_SELF'] ) == 'wp-login.php' && isset( $_GET['openid_ns'] ) ) {
            $openid = pubcomp_get_openid();
            if ( $openid->validate() ) {
                $player = pubcomp_fetch_player( $openid->identity );
                $user = get_user_by( 'login', 'steam_' . $player['steamid'] );
                if ( $user && !is_wp_error( $user ) ) {
                    wp_insert_user( array( 'ID' => $user->ID, 'display_name' => $player['personaname'] ) );
                    wp_set_auth_cookie( $user->ID, true );
                    if ( empty( $_GET['redirect_to'] ) )
                        $_GET['redirect_to'] = get_home_url();
                    header( 'Location: ' . $_GET['redirect_to'] );
                    exit;
                }
            }
            header( 'Location: ' . get_home_url() );
            exit;
        }
    }

    function pubcomp_before_account_details() {
        try {
            $openid = pubcomp_get_openid();
            if ( isset( $GLOBALS['pubcomp_signup_message'] ) ) {
                echo $GLOBALS['pubcomp_signup_message'];
            } elseif ( !$openid->mode || !$openid->validate() ) {
    ?>
                <input type="hidden" name="steam_login" value="go" />
                <input type="submit" value="Sign in with Steam" />
    <?php
            } else {
                $player = pubcomp_fetch_player( $openid->identity );
                echo 'Hello, ', esc_html( $player['personaname'] ), '! Enter your email address to complete registration.';
    ?>
                <input type="text" name="email" placeholder="Email address" />
                <input type="hidden" name="steam_login" value="<?php echo $player['steamid']; ?>" />
                <input type="hidden" name="pubcomp_wpnonce" value="<?php echo wp_create_nonce( 'steam_login-' . $player['steamid'] ); ?>" />
    <?php
            }
        } catch( ErrorException $e ) {
            echo 'Whoops! There was an error.';
        }
        ob_start();
    }
    function before_signup_profile() { ob_start(); }
    function pubcomp_before_sidebar_login() { ob_start(); }
    function pubcomp_clear_buffer() { ob_end_clean(); }
}
function pubcomp_get_finalize_html() {
    global $PubcompSteamLogin; 
    return $PubcompSteamLogin->getFinalizeHtml();
}

function pubcomp_get_finalize_js() {
    global $PubcompSteamLogin; 
    return $PubcompSteamLogin->getFinalizeJs();
}
global $PubcompSteamLogin; 
$PubcompSteamLogin = new PubcompSteamLogin();
