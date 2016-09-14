<?php
/**
 * Template Name: Store Items
 * Description: This page displays an inventory catalog of products.
 * Author: Thabo Mbuyisa
 * Last modified: 19 - 07 - 16
 */
get_header(); 

?>
<div id="" class="">

	<div id="">
		<h1><?php the_title(); ?></h1>
		<?php

			$current_user = wp_get_current_user();
			$current_user_id = get_current_user_id();
			$browny_points = get_user_meta($current_user_id, 'browny_points', true);

			if($browny_points == '') {

				echo'<h3><strong>Welcome' . ' ' . $current_user->display_name . ' ' . '</strong></h3>';
				echo'<p><strong>You currently do not have any browny points in your account.<br />Upload a post and get some browny points to spend.</strong></p><br />';
			}else{

				echo'<h3><strong>Welcome' . ' ' . $current_user->display_name . ' ' . '</strong></h3>';
				echo'<p><strong>Your current points balance is' . ' ' . $browny_points . '</strong></p>';
			}
		?>
		<div class="">
		<?php
			$query = new WP_Query( array(
				'post_type' => 'mofumofu-store',
				'posts_per_page' => 5 ) 
			);
				
			while ( $query->have_posts() ) : $query->the_post();?>

				<article <?php post_class('content-post col-xs-12 col-sm-12 col-md-4 col-lg-4'); ?>>

					<a class="" href="<?php the_permalink(); ?>" aria-hidden="true"> <?php
						the_post_thumbnail( 'medium' ); ?>
					</a>

					<div class="">
						<?php
							$price = get_post_meta($post->ID, 'store_item_price', true);
						?>
						<h2 class="">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
								<span><?php echo '¥' . $price ?></span>
							</a>
						</h2>
						<div class="">

							<?php the_content(); ?>
						</div>
					</div>
					<div class="">
						<form id="mofu-buy-button" action="<?php the_permalink(); ?>" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="action" value="1">
							<button name="mofu-buy-button" id="mofu-buy-button" class="" data-loading-text="<?php _e('Loading...', 'bluthemes') ?>" type="submit"><?php _e('Add to basket', 'html5blank'); ?></button>
						</form>
					</div>
				</article>
			<?php		
			endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>
