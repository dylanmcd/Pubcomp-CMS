<?php
/**
 * Plugin Name: DP Twitter Widget
 * Plugin URI: http://dedepress.com/plugins/dp-twitter-widget/
 * Description: A twitter widget for plugin with YQL support, for the people that twitter.com blocked for them. 
 * Version: 1.0
 * Author: Cloud Stone
 * Author URI: http://dedepress.com/
 * License: GPL V2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class DP_Twitter_Widget extends WP_Widget {
	var $prefix;
	var $textdomain;
	
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.7
	 */
	function DP_Twitter_Widget() {
		$this->prefix = 'dp';
		$this->textdomain = 'dp-twitter-widget';
		
		$this->plugin_file = 'dp-twitter-widget/dp-twitter-widget.php';
		$this->settings_url = admin_url( 'widgets.php' );
		$this->donate_url = !empty($this->donate_url) ? $this->donate_url : 'http://dedepress.com/donate/';
		$this->support_url = !empty($this->support_url) ? $this->support_url : 'http://dedepress.com/support/';
		
		$widget_ops = array('classname' => 'widget-twitter', 'description' => __( 'Display your tweets from Twitter with YQL support.', $this->textdomain) );
		parent::WP_Widget("{$this->prefix}-twitter-widget", __('DP Twitter', $this->textdomain), $widget_ops);
		
		// Filtering pluginn action links and plugin row meta
		add_filter( 'plugin_action_links', array(&$this, 'plugin_action_links'),  10, 2 );
		add_filter( 'plugin_row_meta', array(&$this, 'plugin_row_meta'),  10, 2 );

	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.7
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
		
		$screen_name = trim( urlencode( $instance['screen_name'] ) );
		if ( empty($screen_name) )
			return;
			
		$number = $instance['number'];
		if($number > 200)
			$number = 200;
			
		$link_attr = '';
		if(isset($instance['target_blank']))
			$link_attr = ' target="_blank"';
		if(isset($instance['nofollow']))
			$link_attr .= ' rel="nofollow"';
					
		$use_yql = $instance['use_yql'];
		
		if ( !$tweets = wp_cache_get( 'widget-twitter-' . $this->number , 'widget' ) ) {
			$params = array(
				'screen_name' => $screen_name,
				'trim_user' => true,
				'include_entities' => true,
				'exclude_replies' => (bool)$instance['exclude_replies'],
				'count' => (bool)$instance['exclude_replies'] ? 100 : $number,
				'include_rts' => (bool)$instance['include_rts']
			);
			
			$twitter_json_url = esc_url_raw( 'http://api.twitter.com/1/statuses/user_timeline.json?' . http_build_query($params), array('http', 'https') );
			unset($params);
			
			if($use_yql) {
				$yqlQuery = 'select * from json where url="http://twitter.com/statuses/user_timeline/'.$screen_name.'.json?count='.$number.'"';
				
				$r = json_decode(file_get_contents('http://query.yahooapis.com/v1/public/yql?q='.urlencode($yqlQuery).'&format=json&diagnostics=false&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys'))->query->results;
				$tweets = $r->json->json;
				$expire = 900;
			} else {

				$response = wp_remote_get( $twitter_json_url, array( 'User-Agent' => 'WordPress.com Twitter Widget' ) );
				$response_code = wp_remote_retrieve_response_code( $response );
			
				if ( 200 == $response_code ) {
					$tweets = wp_remote_retrieve_body( $response );
					$tweets = json_decode( $tweets);
				
					$expire = 900;
					if ( !is_array( $tweets ) || isset( $tweets['error'] ) ) {
						$tweets = 'error';
						$expire = 300;
					}
				} else {
					$tweets = 'error';
					$expire = 300;
					wp_cache_add( 'widget-twitter-response-code-' . $this->number, $response_code, 'widget', $expire);
				}
			}
			
			wp_cache_add( 'widget-twitter-' . $this->number, $tweets, 'widget', $expire );
		}
	
		if ( 'error' != $tweets ) {
			echo '<ul class="tweets">' . "\n";
			$tweets_out = 0;
			foreach ( (array) $tweets as $tweet ) {
				if ( $tweets_out >= $number )
					break;
					
				if ( empty( $tweet->text ) )
					continue;
				
				$text = make_clickable( esc_html( $tweet->text ) );

				/*
				 * Create links from plain text based on Twitter patterns
				 * @link http://github.com/mzsanford/twitter-text-rb/blob/master/lib/regex.rb Official Twitter regex
				 */
				$text = preg_replace_callback('/(^|[^0-9A-Z&\/]+)(#|\xef\xbc\x83)([0-9A-Z_]*[A-Z_]+[a-z0-9_\xc0-\xd6\xd8-\xf6\xf8\xff]*)/iu',  array($this, '_wpcom_widget_twitter_hashtag'), $text);
				$text = preg_replace_callback('/([^a-zA-Z0-9_]|^)([@\xef\xbc\xa0]+)([a-zA-Z0-9_]{1,20})(\/[a-zA-Z][a-zA-Z0-9\x80-\xff-]{0,79})?/u', array($this, '_wpcom_widget_twitter_username'), $text);
				if ( isset($tweet->id_str) )
					$tweet_id = urlencode($tweet->id_str);
				else
					$tweet_id = urlencode($tweet->id);
				
				$text = '<li>' . $text . '<a href="' . esc_url( "http://twitter.com/{$screen_name}/statuses/{$tweet_id}" ) . '" class="timesince">' . str_replace(' ', '&nbsp;', wpcom_time_since(strtotime($tweet->created_at))) . "&nbsp;ago</a></li>\n";
				
				$text = str_replace(' rel="nofollow"', '', $text);
				if($link_attr)
					$text = str_replace('<a href=', '<a'.$link_attr.' href=', $text);
					
				echo $text;
				
				unset($tweet_id);
				$tweets_out++;
			}

			echo "</ul>\n";
		} else {
			if ( 401 == wp_cache_get( 'widget-twitter-response-code-' . $this->number , 'widget' ) )
				echo '<p>' . esc_html( sprintf( __( 'Error: Please make sure the Twitter account is <a href="%s">public</a>.'), 'http://support.twitter.com/forums/10711/entries/14016' ) ) . '</p>';
			else
				echo '<p>' . esc_html__('Error: Twitter did not respond. Please wait a few minutes and refresh this page.') . '</p>';
		}

		echo $after_widget;
	}
	
	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.7
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;
		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['screen_name'] = trim( strip_tags( stripslashes( $new_instance['screen_name'] ) ) );
		$instance['number'] = absint($new_instance['number']);
		$instance['exclude_replies'] = isset($new_instance['exclude_replies']);
		$instance['include_rts'] = isset($new_instance['include_rts']);
		$instance['target_blank'] = isset($new_instance['target_blank']);
		$instance['nofollow'] = isset($new_instance['nofollow']);
		$instance['use_yql'] = isset($new_instance['use_yql']);

		wp_cache_delete( 'widget-twitter-' . $this->number , 'widget' );
		wp_cache_delete( 'widget-twitter-response-code-' . $this->number, 'widget' );

		return $instance;
	}
	
	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.7
	 */
	function form( $instance ) {
		$defaults = array(
			'title' => __('Twitter Updates', $this->textdomain),
			'screen_name' => '',
			'number' => 5, 
			'exclude_replies' => false,
			'include_rts' => false,
			'use_yql' => false,
			'nofollow' => true,
			'target_blank' => true
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<div class="dp-widget-controls">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->textdomain ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('screen_name'); ?>"><?php _e('Username:',$this->textdomain); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('screen_name'); ?>" value="<?php echo esc_attr( $instance['screen_name'] ); ?>" class="widefat" id="<?php echo $this->get_field_id('screen_name'); ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number:',$this->textdomain); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>">
				<?php for ( $i = 1; $i <= 20; ++$i ) { ?>
					<option value="<?php echo $i; ?>" <?php selected( $instance['number'], $i ); ?>><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_replies' ); ?>">
			<input <?php disabled($instance['use_yql'], true); ?> class="checkbox" type="checkbox" <?php checked( $instance['exclude_replies'], true ); ?> id="<?php echo $this->get_field_id( 'exclude_replies' ); ?>" name="<?php echo $this->get_field_name( 'exclude_replies' ); ?>" /> <?php _e( 'Exclude replies?', $this->textdomain); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'include_rts' ); ?>">
			<input <?php disabled($instance['use_yql'], true); ?> class="checkbox" type="checkbox" <?php checked( $instance['include_rts'], true ); ?> id="<?php echo $this->get_field_id( 'include_rts' ); ?>" name="<?php echo $this->get_field_name( 'include_rts' ); ?>" /> <?php _e( 'Include Retweets?', $this->textdomain); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'nofollow' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['nofollow'], true ); ?> id="<?php echo $this->get_field_id( 'nofollow' ); ?>" name="<?php echo $this->get_field_name( 'nofollow' ); ?>" /> <?php _e( 'Add nofolow to link?', $this->textdomain); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'target_blank' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['target_blank'], true ); ?> id="<?php echo $this->get_field_id( 'target_blank' ); ?>" name="<?php echo $this->get_field_name( 'target_blank' ); ?>" /> <?php _e( 'Open links in new window?', $this->textdomain); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'use_yql' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['use_yql'], true ); ?> id="<?php echo $this->get_field_id( 'use_yql' ); ?>" name="<?php echo $this->get_field_name( 'use_yql' ); ?>" /> <?php _e( 'Use YQL?', $this->textdomain); ?></label>
		</p>
		</div>
	<?php 
	}

	/**
	 * Link a Twitter user mentioned in the tweet text to the user's page on Twitter.
	 *
	 * @param array $matches regex match
	 * @return string Tweet text with inserted @user link
	 */
	function _wpcom_widget_twitter_username( $matches ) { // $matches has already been through wp_specialchars
		return "$matches[1]@<a href='" . esc_url( 'http://twitter.com/' . urlencode( $matches[3] ) ) . "'>$matches[3]</a>";
	}

	/**
	 * Link a Twitter hashtag with a search results page on Twitter.com
	 *
	 * @param array $matches regex match
	 * @return string Tweet text with inserted #hashtag link
	 */
	function _wpcom_widget_twitter_hashtag( $matches ) { // $matches has already been through wp_specialchars
		return "$matches[1]<a href='" . esc_url( 'http://twitter.com/search?q=%23' . urlencode( $matches[3] ) ) . "'>#$matches[3]</a>";
	}
	
	function plugin_action_links( $actions, $plugin_file ) {
			if ( $plugin_file == $this->plugin_file && $this->settings_url)
				$actions[] = '<a href="'.$this->settings_url.'">' . __('Settings', 'dp-core') .'</a>';
			
			return $actions;
		}
	
	function plugin_row_meta( $plugin_meta, $plugin_file ){
			if ( $plugin_file == $this->plugin_file ) {
				$plugin_meta[] = '<a href="'.$this->donate_url.'">' . __('Donate', 'dp-core') .'</a>';
				$plugin_meta[] = '<a href="'.$this->support_url.'">' . __('Support', 'dp-core') .'</a>';
			}

			return $plugin_meta;
		}

}

if ( !function_exists('wpcom_time_since') ) {
	function wpcom_time_since( $original, $do_more = 0 ) {
        // array of time period chunks
        $chunks = array(
                array(60 * 60 * 24 * 365 , 'year'),
                array(60 * 60 * 24 * 30 , 'month'),
                array(60 * 60 * 24 * 7, 'week'),
                array(60 * 60 * 24 , 'day'),
                array(60 * 60 , 'hour'),
                array(60 , 'minute'),
        );

        $today = time();
        $since = $today - $original;

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
                $seconds = $chunks[$i][0];
                $name = $chunks[$i][1];

                if (($count = floor($since / $seconds)) != 0)
                        break;
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

        if ($i + 1 < $j) {
                $seconds2 = $chunks[$i + 1][0];
                $name2 = $chunks[$i + 1][1];

                // add second item if it's greater than 0
                if ( (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) && $do_more )
                        $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
        return $print;
	}
}


add_action('widgets_init', 'register_dp_twitter_widget');

function register_dp_twitter_widget() {
	register_widget('DP_Twitter_Widget');
}