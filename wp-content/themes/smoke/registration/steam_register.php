<?php get_header() ?>
<div style="clear:both;"></div>
	<div id="container" class="container_16">
		<div id="content" class="bloglist-panel grid_16">

		<?php do_action( 'bp_before_register_page' ) ?>

		<div class="page" id="register-page">
        <div id="form-wizard" class="blog-post"> 
<p class="large-text">To register for PubComp, we need to both find your steam id, and verify that you own the account. To do this, we will generate a special phrase for you to put in your profile, to verify that it is you.</p>
			<form id="steam-register-form" method="post" action=""> 
            <div id="fieldWrapper"> 
                    <h1 id="step1-title">Step 1: Enter your steam community url id</h1>
<div id="step1-content">
<p>To find your steam community url id: 
<ol><li>Go to <a href="https://steamcommunity.com/" target="_blank">steamcommunity.com</a></li>
<li>Enter your steam username and password to login to the site.</li>
<li>Click "Profile"</li>
<li>Your URL id is the last part of the url that appears your address bar. It defaults to a number, but it can also be a word if you set up a unique id. For example <a href="http://steamcommunity.com/id/parable">http://steamcommunity.com/id/<strong>parable</strong></a> or <a href="http://steamcommunity.com/profiles/76561197972140573">http://steamcommunity.com/profiles/<strong>76561197972140573</strong></a> will both take you to Parable's Steam community url.</li>
<li>Enter <strong>either</strong> your unique profile id or your number.
</ol>
					<table cellpadding="0" cellspacing="0"><tr><td align="right" style="vertical-align: middle;"><label for="signup_community_url" style="font-size: 14px;">Unique id or number</label></td>                    
<td style="vertical-align: middle"><input class="input-underline" style="width: 350px;" type="text" name="community_id" id="community_id" value="" /></td></tr></table></p>
<p><button value="Next" class="form-button" id="step1-next">Next</button></p>
</div> <!-- /step1-content -->

                <h1 id="step2-title">Step 2: Confirm your account</h1>
                <div id="step2-content" style="display: none;">
                <div id="profile-container"></div>
                <p>In this step, we'll confirm that you own the account by </p>
                <ol><li id="step2-1"></li><li id="step2-2"></li><li id="step2-3"></li></ol>
<p><button value="Next" class="form-button" id="step2-back">Back</button>
<button value="Next" class="form-button" id="step2-next">Next</button></p>
</div><!-- /step2-content -->

					<h1>Step 3: Complete Signup</h1>
<div id="step3-content" style="display: none;">
                    <table cellpadding="0" cellspacing="0"><tr><td align="right" style="vertical-align: middle;"><tr><td style="text-align: right; vertical-align: middle;">
					<label for="signup_username"><?php _e( 'Username', 'buddypress' ) ?></label>
</td></td><td style="text-align: right">
                    <input type="text" name="signup_username" id="signup_username" class="input-underline" value="" />
</td>

                    <tr>
<td style="vertical-align: middle; style: text-align: right;"><label for="signup_email"><?php _e( 'Email Address', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label></td><td>
                    <input type="text" name="signup_email" id="signup_email" class="input-underline" value="" /></td></tr>
<tr><td style="vertical-align: middle; style: text-align: right;">
					<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label></td><td>
					<input type="password" name="signup_password" id="signup_password" value="" /></td></tr>    
<tr><td style="vertical-align: middle; style: text-align: right;">
                    <label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label></td>
<td>
					<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" /></td></tr></table>
<p><button value="Next" class="form-button" id="step3-next">Next</button></p>
        </div><!-- /step3-content -->
					<input type="hidden" name="current_step" id="current_step" value="<?php echo isset($_SESSION['registration_current_step']) ? $_SESSION['registration_current_step'] : 1 ?>" />
				</div> 
			</form> 
		</div> 
            <?php wp_nonce_field( 'bp_new_signup' ) ?>
		</div>

        <div style="margin-bottom: 25px;"></div>
        </div><!-- .blog_post -->
		</div><!-- #content -->
	</div><!-- #container -->
    <div style="clear:both;"></div> 
	<?php// locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer() ?>
