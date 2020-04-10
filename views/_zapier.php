<p>Features for Plugin with Zapier </p>
<ol>
	<li> CREATE THE FORM :- CF7, WP forms, Gravity Form and Ninja Form with UTM Parameters instruction </li>
	<li> CREATE ZAPIER WEBHOOK :- you can Google and find how to create Zapier Webhook or login https://zapier.com/app/zaps </li>
	<li> REGISTER THE WEBHOOK in Plugin :-  copy web hook url from Zapier account and paste the webhook URL in to Zapier Webhook URL and "Save Changes"
	</li> 			
</ol>

	<?php       
	echo settings_fields('UtmvGrabber_option_group');
	echo do_settings_sections('UtmvGrabber_options');
	// echo submit_button(); ?>
