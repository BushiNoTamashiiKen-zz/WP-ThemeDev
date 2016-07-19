<?php 
/**
 * Generic front-end form submission template.
 * Customizable to fit theme styling by referrencing theme classes
 * @param $POST
 *
 */

?>
<!--<form class="blt_posts_form" action="<?php the_permalink(); ?>" method="post">

	<fieldset class="form-group">
		<h3>Done Mofu Mofu shopping?</h3>
		<p>Checkout to proceed otherwise cancel</p>

		<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

		<input type="hidden" name="action" value="1">
		<button class="btn btn-theme btn-lg" name="checkout_submitted" id="checkout_submitted" type="submit"><?php _e('Checkout', 'bluthemes'); ?>
		</button>
		<span></span>
		<button class="btn btn-theme btn-lg" name="checkout_cancelled" id="checkout_cancelled" type="submit"><?php _e('Cancel', 'bluthemes'); ?>
		</button>
	</fieldset>
</form>-->
<div class="page-body">
	<h1 class="page-title"><i class="fa fa-check" style="color:#A5D535"></i> <?php _e('Checkout complete!', 'bluthemes'); ?></h1>
	<div class="page-text">
	<hr>
	<p class="lead"><?php _e('Thank you for using your mofu mofu points!<br>After our staff has reviewed your purchase you will recieve an email', 'bluthemes'); ?></p>
	</div>
</div>