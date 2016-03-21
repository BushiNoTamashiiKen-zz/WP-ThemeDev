<?php 
/**
 * Generic front-end form submission template.
 * Customizable to fit theme styling by referrencing theme classes
 * @param $POST
 *
 */

?>
<form class="blt_posts_form" action="<?php the_permalink(); ?>" method="post">

	<fieldset class="form-group">
		<h3>So you wanna go pro?</h3>
		<p>Submit to proceed otherwise cancel</p>

		<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

		<input type="hidden" name="action" value="1">
		<button class="btn btn-theme btn-lg" name="ugme_submitted" id="ugme_submitted" type="submit"><?php _e('Submit request', 'bluthemes'); ?>
		</button>
		<span></span>
		<button class="btn btn-theme btn-lg" name="ugme_cancelled" id="ugme_submitted" type="submit"><?php _e('Cancel', 'bluthemes'); ?>
		</button>
	</fieldset>
</form>