<?php
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
?>

<div class="wrap">

	<?php
	$reset = ( $_POST['stextcount_hidden'] == 'reset' ) ? 1 : 0;
	$options = options_subscribers_text_counter( $reset );
	$refresh = ( $_POST['stextcount_hidden'] == 'cache' ) ? 1 : 0;
	$counters = counters_subscribers_text_counter( $options, $refresh );
	if ( $_POST['stextcount_hidden'] == 'settings' ) {
		$options['feedburner'] = $_POST['feedburner'];
		$options['twitter']    = $_POST['twitter'];
		$options['facebook']   = $_POST['facebook'];
		$options['facebookk']   = $_POST['facebookk'];
		$options['facebooks']   = $_POST['facebooks'];
		update_option( 'widget_subscribers_text_counter', $options );
	?>
	<div class="updated"><p><strong>Options saved.</strong></p></div>
	<?php
	}
	?>

	<h2>Subscribers Text Counter <?php echo STCVERSION; ?></h2>

	<div style="width: 600px;">
		<div>
			<div style="float: left; width: 300px; padding: 0 10px 0 5px;">
			<p>
				<strong>Quick howto:</strong>
				<ol style="font-size: 10px;">
					<li>To make your website faster counters refresh no more than once per 2 hours</li>
					<li>If you don't need all counters, just leave the settings fields empty</li>
					<li>To install counters on your sidebar you should go to 'Appearance/Widgets' menu</li>
					<li>To display counters use following tags: <strong>'twitter', 'feedburner', 'facebook', 'all'</strong></li>
					<li>To display links use following tags: <strong>'twitterlink', 'feedburnerlink', 'feedburneremail', 'facebooklink'</strong></li>
					<li>In widget area use it like this: <strong>%twitter%</strong></li>
					<li>In post/page content use it like this: <strong>[stcounter type="twitter"]</strong></li>
					<li>In templates use it like this: <strong><&#63;php echo stcounter('twitter') &#63;></strong>
				</ol>
			</p>
			</div>
                        <div style="float: left; width: 250px; border: 2px solid #000; margin: 60px 0 0 15px;">
				<p align="center"><font color="#CC0000" size="3"
			        face="Tahoma"><b>*Radically Unconventional* Niche Website
			        Formula That Generated $1,214,978 From </b><b><u>Free</u></b><b>
			        Website Traffic!</b></font></p>
			        <p align="center"><font size="2" face="Tahoma"><b>You </b><b><u>Don't</u></b><b><i><u>
			        </u></i></b><b><u>Need</u></b><b> Experience, Any
			        Additional Investment... Or Have To Risk Thousands Of
			        Dollars On Paid Advertising...</b></font></p>
			        <p align="center"><a
			        href="http://4c0c16w-p84n6s8gp6t7ck1s4c.hop.clickbank.net/?tid=STC"><font
			        size="2" face="Tahoma"><b>Click to Learn More</b></font></a></p>
			</div>
			<div style="clear: both;">
				<br />
			</div>
			<form name="stextcount_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
			<input type="hidden" name="stextcount_hidden" value="settings">
				<div style="background-color: #fff; padding: 10px; margin: 2px; border: 1px solid #E0E0E0;">
					<strong>twitter settings</strong><br />
					<p />
					twitter handle:
					<input type="text" name="twitter" value="<?php echo $options['twitter']; ?>" size="20"><br />
					<small>ex: "KreCiDev" if url is "<a href="http://www.twitter.com/KreCiDev" target="_blank">http://www.twitter.com/KreCiDev</a>"</small>
				</div>
				<div style="background-color: #fff; padding: 10px; margin: 2px; border: 1px solid #E0E0E0;">
					<strong>Feedburner settings</strong><br />
					<p>
                                                <small style="color: red;">
                                                To use all features you need to enable for your feed at FeedBurner account under "Publicize" tab following services:
                                                <ol style="padding: 10px; font-size: 10px; color: red;">
                                                        <li>"Awareness API" to enable counter</li>
                                                        <li>"Subscribtions via Email" to enable email subscribtions</li>
                                                </ol>
                                                </small>
					</p>
					FeedBurner feed name:
					<input type="text" name="feedburner" value="<?php echo $options['feedburner']; ?>" size="20"><br />
					<small>ex: "KreCiBlogger" if url is "<a href="http://feeds.feedburner.com/KreCiBlogger" target="_blank">http://feeds.feedburner.com/KreCiBlogger</a>"</small><br />
				</div>
				<div style="background-color: #fff; padding: 10px; margin: 2px; border: 1px solid #E0E0E0;">
					<strong>Facebook settings</strong><br />
					<p />
					Page ID:
					<input type="text" name="facebook" value="<?php echo $options['facebook']; ?>" size="20"><br />
					<small>ex: "271624005427" if url is "<a href="http://www.facebook.com/pages/KreCi-Development/271624005427" target="_blank">http://www.facebook.com/pages/KreCi-Development/271624005427</a>"</small>
					<p>
                                                <small style="color: red;">
                                                If you have no API key and secret you need to register new APP on Facebook:
						<ol style="padding: 10px; font-size: 10px; color: red;">
							<li>Make sure you are logged in on Facebook</li>
							<li>Visit <a href="http://developers.facebook.com/setup.php">this page</a></li>
							<li>Fill the form with you website name and URL and click "Next Step"</li>
							<li>Ignore this step and just click "Upload Later" and confirm with "Okay"</li>
							<li>Copy your API Key and Secret to fill the form below and click "Update Settings"</li>
						</ol>
                                                </small>
					</p>
					API key:
					<input type="text" name="facebookk" value="<?php echo $options['facebookk']; ?>" size="40"><br />
					<br />
					API secret:
					<input type="text" name="facebooks" value="<?php echo $options['facebooks']; ?>" size="40"><br />
				</div>
				<p class="submit" style="text-align: right;">
					<input type="submit" name="Submit" value="Update Settings" />
				</p>
			</form>
		</div>
		<div>
			<form name="stextcount_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
				<input type="hidden" name="stextcount_hidden" value="cache">
				<div style="background-color: #fff; padding: 10px; margin: 2px; border: 1px solid #E0E0E0;">
					<strong>Cached data</strong>
					<p>
						RSS Subscribers: <strong><?php echo $counters['feedburner']; ?></strong><br />
						twitter followers: <strong><?php echo $counters['twitter']; ?></strong><br />
						Facebook fans: <strong><?php echo $counters['facebook']; ?></strong><br />
						All subscribers: <strong><?php echo $counters['all']; ?></strong><br />
						Counters will be refreshed in: <strong><?php echo $counters['time']; ?> seconds</strong>
					</p>
				</div>
				<p class="submit" style="text-align: right;">
					<input type="submit" name="Submit" value="Refresh Counters Now" />
				</p>
			</form>
		</div>
                <div>
                        <form name="stextcount_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
                                <input type="hidden" name="stextcount_hidden" value="reset">
                                <div style="background-color: #E8CFCF; padding: 10px; margin: 2px; border: 1px solid red;">
                                        <strong>Reset settings</strong>
                                        <p>
						Push button under this box to reset settings & widget area to default values.<br />
						Be warned that this operation can not be undone!
                                        </p>
                                </div>
                                <p class="submit" style="text-align: right;">
                                        <input type="submit" name="Submit" value="Reset" />
                                </p>
                        </form>
                </div>
	</div>

	<div style="width: 600px;">
		<div style="float: left; margin: 0 0 10px 0;">
			<?php get_feed_subscribers_text_counter("http://feeds.kreci.net/KreCiBlogger"); ?>
		</div>
		<div style="float: left;">
		<div style="border: 1px solid #000; background-color: #FF8080; padding: 5px; width: 300px; margin: 0 0 10px 10px;">
		<p align="center"><font color="#FFFFFF"><strong>Imagine
		        Having a Library of<br>
		        29,768 Niche Market<br>
		        Articles at Your Fingertips...</strong></font></p>
		        <p align="center"><strong>It can be real!<br>
		        <a href="http://92e1cbz6rhay4t0amdq5qpjqcz.hop.clickbank.net/?tid=STC">Click to learn more!</a></strong>
		</p>
		</div>
		<div style="border: 1px solid #000; background-color: rgb(204, 204, 255); padding: 5px; width: 300px; margin: 0 0 10px 10px;">
			<strong>Author homepage:</strong> <a href="http://www.kreci.net">Chris Kwiatkowski</a><br />
			<strong>Documentation:</strong> <a href="http://www.kreci.net/code/wordpress/subscribers-text-counter-widget/">Plugin Homepage</a><br />
			<strong>Follow on twitter:</strong> <a href="http://www.twitter.com/KreCiDev">@KreCiDev</a><br >
		</div>
		</div>
	</div>
</div>
