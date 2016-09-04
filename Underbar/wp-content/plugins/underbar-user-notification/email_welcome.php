<?php 
/**
 * Email message template
 *
 */
 ?>
<div style="background: #3c3f3e; padding: 40px; margin-bottom: 30px; font-family: avenir-light, Helvetica, Arial, sans-serif; font-size: 16px; color: #fafafa; line-height: 28px;">

	<a href="<?php echo site_url(); ?>" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 30px;">
		<img align="right" style="float: none;" src="<?php echo $logoUrl; ?>" alt="Underbar Logo Green"/>
	</a>
	 
	<?php if ( $user->first_name != '' ) : ?>

	    <h1 style="font-family: avenir-light, Helvetica, Arial, sans-serif; font-weight: normal; font-size: 35px; line-height: normal;">Hi <?php echo $user->first_name; ?>, Welcome to Underbar</h1>
	<?php else : ?>
		
	    <h1>Welcome to Underbar</h1>
	<?php endif; ?>
	 
	<p>
	    Thanks for registering to be a bartender at Underbar.<br /> 
	    <a style="font-family: avenir-medium, Helvetica, Arial, sans-serif; color: #4dffbb; text-decoration: none;" href="<?php echo site_url('/wp-admin/'); ?>">Log in</a> and start setting up your event.
	</p>

	<p>
	    Thank you,<br>
	    Underbar
	</p>
	<p style="margin: 30px 0;">
	    <a style="background: #4dffbb; border-radius: 2px; padding: 8px 10px; font-family: avenir-medium, Helvetica, Arial, sans-serif; color: #3c3f3e; text-decoration: none;" href="<?php echo site_url('/wp-admin/'); ?>">Start an event</a>
	</p>
</div>