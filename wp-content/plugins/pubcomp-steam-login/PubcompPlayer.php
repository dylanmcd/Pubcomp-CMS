<?php
class PubcompPlayer {
    public $errors;

    function __construct() {
        global $wpdb;
        require_once(dirname( __FILE__ ) . '/openid.php');
        $this->errors = array();
        $this->table_name = $wpdb->prefix . 'pubcomp_player';
    }

    function getSteamId() {
        return isset($_SESSION['steam_id']) ? $_SESSION['steam_id'] : false;
    }

    function playerExists($steam_id) {
        global $wpdb;
        $steam_id = $this->getSteamId64($steam_id);
        return $this->getUserId($steam_id) ? true : false;
    }

    function getUserId($steam_id) {
        global $wpdb;
        $user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $this->table_name WHERE steam_id_64 = %s", $steam_id));
        return $user_id ? $user_id : false;
    }

    function getSteamIdFromIdentity($identity) {
        return str_replace( 'http://steamcommunity.com/openid/id/', '', $identity );
    }

    function getPlayerInfo($steam_id) {
        if ( !$player = get_transient( 'pubcomp_steam_user_' . $steam_id ) ) {
            $url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . PUBCOMP_STEAM_KEY . '&steamids=' . $steam_id;
            $request = wp_remote_get( $url );
            $request['body'] = preg_replace( '/: (\d+),/', ': "$1",', $request['body'] );
            $player = json_decode( $request['body'], true );
            $player = $player['response']['players'][0];
            set_transient( 'pubcomp_steam_user_' . $steam_id, $player, 3600 );
        }
        return $player;
    }

    function createPlayer($steam_id) {
        global $wpdb;
        $steam_id = $this->getSteamId64($steam_id);
        $password = $this->makePass($steam_id);
        $user_id = wp_create_user('steam_' . $steam_id, $password, $steam_id . '@example.com');
        if (!$user_id || is_wp_error($user_id)) {

            $message = "There was an error creating your wordpress user. This may be a problem with Steam being under heavy load.<br /><br /> If this persists, please contact Parable at parable@pubcomp.com or contact him through Steam with the following information.<br /><br />";
            echo $message;
            print_r($user_id->get_error_messages() );
            die();
        }
        $wpdb->update($wpdb->users, array('user_status' => 2), array( 'ID' => $user_id));
        $wpdb->insert($this->table_name, array('steam_id_64' => $steam_id, 'user_id' => $user_id), array( '%s', '%d' ));
        if (is_wp_error($user_id)) {
            return false;
        }
        return $user_id;
    }

    function makeAvatar($steam_id) {
        $player = $this->getPlayerInfo($steam_id);
        if (!$player) {
            return false;
        }
        $item_id = $this->getUserId($steam_id);
        $upload_dir = BP_AVATAR_UPLOAD_PATH . '/avatars/' . $item_id . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir);
        }
        $steam_avatar = $upload_dir . 'steam_avatar';
        $avatar_full = $steam_avatar . '-bpfull.jpg';
        $avatar_thumb = $steam_avatar . '-bpthumb.jpg';
        $avatar = $steam_avatar . '.jpg';

        copy($player['avatar'], $avatar_thumb);
        copy($player['avatarmedium'], $avatar);
        copy($player['avatarfull'], $avatar_full);
        return true;
    }

    function finalizePlayer($steam_id, $username, $email) {
        global $wpdb;
        $this->flushErrors();
        if (!$this->playerExists($steam_id)) {
            $this->addError('user_exists', 'Your steam account has not yet been added. Please click Login to login through Steam');
        } else if ($this->isFinalized()) {
            return true;
        }

        if (!validate_username($username)) {
            $this->addError('invalid_username', 'Username must consist only of alphanumeric characters plus spaces, _, ., -, *, and @');
        } else if (username_exists($username)) {
            $this->addError('username_exists', 'This username has already been taken.');
        }
        if (!is_email($email)) {
            $this->addError('invalid_email', 'This does not appear to be a proper email.');
        }
        if (!$this->makeAvatar($steam_id)) {
            $this->addError('steam_down', 'It appears that the steam servers are down. Please wait a moment and try completing signup again.');
        }
        if ($this->hasErrors()) {
            return false;
        }
        $user_id = $this->getUserId($steam_id);
        $wpdb->query($wpdb->prepare("UPDATE $wpdb->users SET user_login = %s, user_email = %s, user_nicename = %s, display_name = %s, user_status = %d WHERE ID = %d", $username, $email, $username, $username, 0, $user_id ));
        $this->login($user_id);
        return $user_id;
    }

    function getUser($steam_id) {
        $user_id = $this->getUserId($steam_id);
        return get_user_by('id', $user_id);
    }

    function login($user_id = false) {
        if (!$user_id && isset($_SESSION['steam_id'])) {
            $user_id = $this->getUserId($_SESSION['steam_id']);
        }
        return wp_set_auth_cookie( $user_id, true );
    }

    function isFinalized($steam_id = false) {
        global $wpdb;
        if (!$steam_id) { 
            if (!isset($_SESSION['steam_id'])) {
                return false;
            }
            $steam_id = $_SESSION['steam_id'];
        }
        $player_table = $this->table_name;
        $user_status = $wpdb->get_var($wpdb->prepare("SELECT u.user_status FROM $wpdb->users as u INNER JOIN $player_table p ON p.steam_id_64 = %s AND p.user_id = u.ID", $steam_id));
        return ($user_status === "0");
    }

    function makePass($steam_id) {
        $steam_id = $this->getSteamId64($steam_id);
        return hash('sha256', AUTH_SALT . $steam_id);
    }

    function makeError($type, $message = '') {
        return array('error'=>1, 'type'=>$type, 'message'=>$message);
    }

    function addError($type, $message) { 
        $this->errors[] = array('type' => $type, 'message'=>$message);
    }

    function hasErrors() {
        return (isset($this->errors) && $this->errors) ? true : false;
    }

    function flushErrors() {
        $errors = $this->errors;
        $this->errors = array();
        return $errors;
    }


	/*
	 * Check whether we have a regular steam ID, or a 64 bit steam ID and return a 64 bit steam ID either way
     * From |LBTG|Regime
	*/
	private function getSteamId64($steam_arg)
	{
		if (preg_match('/^STEAM_[0-9]:[0-9]:[0-9]{1,}/i', $steam_arg))
		{
			return $this->calcSteamId64($steam_arg);
		}
		else if (preg_match('/^7656119[0-9]{10}$/i', $steam_arg))
		{
			return $steam_arg;
		}
		else
		{
			return false;
		}
	}

	/*
	 * Translate a steam ID to a 64 bit steam id as used by Valve
     * From |LBTG|Regime
	*/
	public function calcSteamId64($steam_id)
	{
		if (preg_match('/^STEAM_[0-9]:[0-9]:[0-9]{1,}/i', $steam_id))
		{
			$steam_id = str_replace("_", ":", $steam_id);
			list($part_one, $part_two, $part_three, $part_four) = explode(':', $steam_id);
			$result = bcadd('76561197960265728', $part_four * 2);
			$result = bcadd($result, $part_two);
			return bcadd($result, $part_three);
		}
		else
		{
			return false;
		}
	}

	/*
	* Translate a 64 bit steam ID to a steam id as used by Valve
    * From |LBTG|Relapse
	*/
	public function calcSteamId($steam_id64)
	{
		if (preg_match('/^76561197960[0-9]{6}$/i', $steam_id64))
		{
			$part_one = substr( $steam_id64, -1) % 2 == 0 ? 0 : 1;
			$part_two = bcsub( $steam_id64, '76561197960265728' );
			$part_two = bcsub( $part_two, $part_one );
			$part_two = bcdiv( $part_two, 2 );
			return "STEAM_0:" . $part_one . ':' . $part_two;
		}
		else
		{
			return false;
		}
	}
}
