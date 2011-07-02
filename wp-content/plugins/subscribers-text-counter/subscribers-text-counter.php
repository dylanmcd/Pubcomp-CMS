<?php
/*
Plugin Name: Subscribers Text Counter
Plugin URI: http://www.kreci.net/code/wordpress/subscribers-text-counter-widget/
Description: Widget to show feedburner & twitter counters as text
Author: Chris Kwiatkowski
Version: 1.5.4
Author URI: http://www.kreci.net/
*/

/*  Copyright 2010  Chris Kwiatkowski  (email : kreci@kreci.net)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Don't touch this or any line below
// unless you know exacly what you are doing
define( 'STCVERSION', '1.5.4' );
define( 'STCDIR', plugin_basename(dirname(__FILE__)) );
define( 'STCURL', WP_PLUGIN_URL.'/'.STCDIR );
define( 'STCUPDATE', '7200' ); // You should not lower it as you can be banned by FeedBurner

function options_subscribers_text_counter( $reset = 0 ) {
	$options = get_option( 'widget_subscribers_text_counter' );
	if (!is_array( $options ) || $reset ) {
		$options = array(
			'title'          => 'My subscribers:',
			'feedburner'     => '',
			'twitter'        => '',
			'facebook'       => '',
			'facebookk'      => '',
			'facebooks'      => '',
			'text'           => '
<div style="text-align: center; font-size: 10px;">
<a style="width: 80px; float: left; padding: 5px 0 5px 0; 
text-decoration: none;" href="%twitterlink%" title="Subscribe via Twitter" target="_blank">
<img src="'.STCURL.'/icons/twitter.png" alt="Subscribe via Twitter" /><br/>
%twitter% Followers
</a>
<a style="width: 80px; float: left; padding: 5px 0 5px 0; text-decoration: none;" href="%facebooklink%" title="Subscribe via Facebook" target="_blank">
<img src="'.STCURL.'/icons/facebook.png" alt="Subscribe via Facebook" /><br/>
%facebook% Fans
</a>
<a style="width: 80px; float: left; padding: 5px 0 5px 0; text-decoration: none;" href="%feedburneremail%" title="Subscribe via Email" target="_blank">
<img src="'.STCURL.'/icons/email.png" alt="Subscribe via Email" /><br/>
Subscribe
</a>
<a style="width: 80px; float: left; padding: 5px 0 5px 0; text-decoration: none;" href="%feedburnerlink%" title="Subscribe via RSS" target="_blank">
<img src="'.STCURL.'/icons/rss.png" alt="Subscribe via RSS" /><br/>
%feedburner% Readers
</a>
</div>
<div style="clear: both;"></div>'
		);
		$reset  ? update_option( 'widget_subscribers_text_counter', $options )
			: add_option( 'widget_subscribers_text_counter', $options, 'yes' );
	}
	return $options;
}

function counters_subscribers_text_counter( $options, $refresh = '0' ) {
	$counters = get_option( 'widget_subscribers_text_counter_dynamic' );
	if ( !is_array( $counters ) ) {
		$time = 0;
		$new = true;
		$counters['feedburner'] = '0';
		$counters['twitter']    = '0';
		$counters['facebook']   = '0';
	} else {
		$time = STCUPDATE + ( $counters['updated'] - time() );
		$new = false;
	}
	if ( $time <= 0 || $refresh ) {
		if ( !empty( $options['feedburner'] ) ) {
			$feedburner = rss_count( $options['feedburner'] );
			if ( $feedburner['rss_count'] == '0' && !$new ) {
				$feedburner['rss_count'] = $counters['feedburner'];
				$feedburner['page_url']  = $counters['feedburnerlink'];
				$feedburner['email_url'] = $counters['feedburneremail'];
			}
		} else {
			$feedburner['rss_count'] = 0;
		}
		if ( !empty( $options['twitter'] ) ) {
			$twitter = followers_count( $options['twitter'] );
			if ( $twitter['followers_count'] == '0' && !$new ) {
				$twitter['followers_count'] = $counters['twitter'];
				$twitter['page_url'] = $counters['twitterlink'];
			}
		} else {
			$twitter['followers_count'] = 0;
		}
		if ( ( !empty( $options['facebookk'] ) && !empty( $options['facebooks'] ) && !empty( $options['facebook'] ) ) ) {
			$facebook = fans_count( $options['facebookk'], $options['facebooks'], $options['facebook'] );
			if ( $facebook['fans_count'] == '0' && !$new ) {
				$facebook['fans_count'] = $counters['facebook'];
				$facebook['page_url'] = $counters['facebooku'];
			}
		} else {
			$facebook['fans_count'] = 0;
		}
		$counters = array(
			'updated'         => time(),
			'feedburner'      => $feedburner['rss_count'],
			'feedburnerlink'  => $feedburner['page_url'],
			'feedburneremail' => $feedburner['email_url'],
			'twitter'         => $twitter['followers_count'],
			'twitterlink'     => $twitter['page_url'],
			'facebook'        => $facebook['fans_count'],
			'facebooklink'    => $facebook['page_url'],
			'all'             => $feedburner['rss_count'] + $twitter['followers_count'] + $facebook['fans_count']
		);
		$new ? add_option( 'widget_subscribers_text_counter_dynamic', $counters, '', 'yes' )
		     : update_option( 'widget_subscribers_text_counter_dynamic', $counters );
		$time = STCUPDATE;
	}
	$counters['time'] = $time;
	return $counters;
}

function curl_subscribers_text_counter( $xml_url ) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $xml_url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function rss_count( $fb_id ) {
	$feedburner['page_url']  = 'http://feeds.feedburner.com' . '/' . $fb_id;
	$feedburner['email_url'] = 'http://feedburner.google.com/fb/a/mailverify?uri='.$fb_id;
	try {
		@$data = curl_subscribers_text_counter( 'https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=' . $fb_id );
		@$xml = new SimpleXmlElement( $data, LIBXML_NOCDATA );
		@$feedburner['rss_count'] = ( string ) $xml->feed->entry['circulation'];
	} catch (Exception $e) {
		$feedburner['rss_count'] = '0';
	}
	return $feedburner;
}

function followers_count( $twitter_id ) {
	$twitter['page_url'] = 'http://www.twitter.com';
	try {
		$twitter['page_url'] .= '/' . $twitter_id;
		@$data = curl_subscribers_text_counter( 'http://twitter.com/users/show.xml?screen_name=' . $twitter_id );
		@$xml = new SimpleXmlElement( $data, LIBXML_NOCDATA );
		@$twitter['followers_count'] = ( string ) $xml->followers_count;
	} catch (Exception $e) {
		$twitter['followers_count'] = '0';
	}
	return $twitter;
}

function fans_count( $fbkey, $fbsecret, $page_id ) {
	try {
		require_once('facebook.php');
		$fb = new Facebook( $fbkey, $fbsecret );
		$fql = "SELECT fan_count, page_url FROM page WHERE page_id = '" . $page_id . "'";
		$query = $fb -> api_client -> fql_query( $fql );
		$facebook['fans_count'] = $query['0']['fan_count'];
		$facebook['page_url'] = $query['0']['page_url'];
	} catch (Exception $e) {
		$facebook['fans_count'] = '0';
		$facebook['page_url'] = 'http://www.facebook.com';
	}
	return $facebook;
}

function text_subscribers_text_counter( $options ) {
	$counters = counters_subscribers_text_counter( $options );
	$text = stripslashes( $options['text'] );
	$text = str_replace( '%twitterlink%', $counters['twitterlink'], $text );
	$text = str_replace( '%feedburnerlink%', $counters['feedburnerlink'], $text );
	$text = str_replace( '%feedburneremail%', $counters['feedburneremail'], $text );
	$text = str_replace( '%facebooklink%', $counters['facebooklink'], $text );
	$text = str_replace( '%twitter%', $counters['twitter'], $text );
	$text = str_replace( '%feedburner%', $counters['feedburner'], $text );
	$text = str_replace( '%facebook%', $counters['facebook'], $text );
	if ( !isset( $counters['all'] ) ) $counters['all'] = $counters['twitter'] + $counters['feedburner'] + $counters['facebook'];
	$text = str_replace( '%all%', $counters['all'], $text );
	return $text;
}

function widget_subscribers_text_counter( $args ) {
	extract($args);
	$options = options_subscribers_text_counter();
	echo $before_widget;
	echo $before_title;
	echo stripslashes( $options['title'] );
	echo $after_title;
	echo text_subscribers_text_counter( $options );
	echo $after_widget;
}

function control_subscribers_text_counter() {
	$options = options_subscribers_text_counter();   
	if ( $_POST['subscribers_text_counter-Submit'] ) {
		$options['title'] = $_POST['subscribers_text_counter-Title'];
		$options['text']  = $_POST['subscribers_text_counter-Text'];
		update_option( 'widget_subscribers_text_counter', $options );
	}
?>
	<p>
		<label for="subscribers_text_counter-Title">Title:</label><br />
		<input type="text" id="subscribers_text_counter-Title" name="subscribers_text_counter-Title" size="30" value="<?php echo stripslashes ( $options['title'] );?>" />
	</p>
	<p>
		<label for="subscribers_text_counter-Text">Your text:</label><br />
		<textarea id="subscribers_text_counter-Text" name="subscribers_text_counter-Text" rows="5" cols="25"><?php echo stripslashes( $options['text'] );?></textarea>
		<small>
		<p>
				You may use following tags:
				<ol>
					<li>%twitter%, %feedburner%, %facebook% and %all% to display counters</li>
					<li>%twitterlink%, %feedburnerlink%, %feedburneremail% and %facebooklink% to insert links</li>
				<ol>
			</p>
		</small>
	</p>
	<input type="hidden" id="subscribers_text_counter-Submit" name="subscribers_text_counter-Submit" value="1" />
<?php
}

function get_feed_subscribers_text_counter( $feed_url ) {
  @$data = curl_subscribers_text_counter( $feed_url );
  if ( $data ) {
    $x = new SimpleXmlElement( $data );
    echo '<ul style="background-color:#FFD953; font-size:10px; margin-left:0; padding:1px; width:275px;">';
    echo '<li style="list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:1px;">
						<a href="http://www.kreci.net" target="_blank" style="background-color:#000000; color:#FFFFFF; display:block; padding:2px; text-decoration:none;">
							KreCi.net RSS FEED
						</a>
					</li>';
    foreach( $x->channel->item as $entry ) {
      echo "<li style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:1px;'><a href='$entry->link' title='$entry->title' target='_blank' style='background-color:#FFFFA4; display:block; padding:2px; text-decoration:none;'>$entry->title</a></li>";
    }
    echo '</ul>';
  }
}


function plugin_links_subscribers_text_counter($links, $file) {
	if ( $file == STCDIR.'/subscribers-text-counter.php' ) {
		$links[] = '<a href="options-general.php?page=subscribers_text_counter">' . __('Settings', 'Subscribers Text Counter') . "</a>";
		$links[] = '<a href="http://r.kreci.net/paypal">' . __('<strong>Donate</strong>', 'Subscribers Text Counter') . "</a>";
	}
	return $links;
}

function shortcodes_stc( $atts ) {
	if ( isset( $atts['type'] ) ) {
		$data = counters_subscribers_text_counter( options_subscribers_text_counter() );
		$reply = $data[$atts['type']];
	} else {
		$reply = ':)';
	}
	return $reply;
}

function stcounter( $type = 'twitter' ) {
	$data = counters_subscribers_text_counter( options_subscribers_text_counter() );
	$reply = $data[$type];
	return $reply;
}

function admin_subscribers_text_counter() {
	include( 'subscribers-text-counter-admin.php' );
}  

function admin_actions_subscribers_text_counter() {
	add_options_page( 'Subscribers Text Counter', 'Subscribers Text Counter', 1, 'subscribers_text_counter', 'admin_subscribers_text_counter' );
}

function subscribers_text_counter_init() {
	register_sidebar_widget( __('Subscribers Text Counter'), 'widget_subscribers_text_counter' );
	register_widget_control( 'Subscribers Text Counter', 'control_subscribers_text_counter' );
	add_action( 'admin_menu', 'admin_actions_subscribers_text_counter' );
	add_filter( 'plugin_row_meta', 'plugin_links_subscribers_text_counter', 10, 2 );
	add_shortcode( 'stcounter', 'shortcodes_stc' );
	counters_subscribers_text_counter( options_subscribers_text_counter() );
}

add_action( 'plugins_loaded', 'subscribers_text_counter_init' );

?>
