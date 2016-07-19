<?php
/**
 * Template Name: Store Items
 * Description: This page displays an inventory catalog of products.
 * Author: Thabo Mbuyisa
 * Last modified: 15 - 07 - 16
 */
get_header(); 

?>
<div id="site-content" class="clearfix">

	<div id="site-content-column">
		<h1><?php the_title(); ?></h1>
		<div class="row">
		<?php
			$query = new WP_Query( array(
				'post_type' => 'mofumofu-store',
				'posts_per_page' => 5 ) 
			);
				
			while ( $query->have_posts() ) : $query->the_post();?>

				<article <?php post_class('content-post col-xs-12 col-sm-12 col-md-4 col-lg-4'); ?>>

					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"> <?php
						the_post_thumbnail( 'medium' ); ?>
					</a>

					<div class="content-body">
						<?php
							$price = get_post_meta($post->ID, 'store_item_price', true);
						?>
						<h2 class="content-title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
								<span><?php echo '¥' . $price ?></span>
							</a>
						</h2>
						<div class="store-content-text">

							<?php the_content(); ?>
						</div>
					</div>
					<div class="store-content-footer clearfix">
						<form id="mofu-buy-button" action="<?php the_permalink(); ?>" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="action" value="1">
							<button name="mofu-buy-button" id="mofu-buy-button" class="btn btn-theme btn-lg" data-loading-text="<?php _e('Loading...', 'bluthemes') ?>" type="submit"><?php _e('Add to basket', 'bluthemes'); ?></button>
						</form>
					</div>
				</article>
			<?php		
			endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>
