<?php
/**
 * Template part archive content
 * Displays category loop but only the content
 * For use within other template loops
 */
?>
<div id="site-content-column"><?php

		if(is_archive()){
			blt_archive_title( '<h1 class="page-title">', '</h1>' );		
		}


		if(have_posts()){

			//
			/*$categories = get_the_category();
			$category_id = $categories[0]->cat_ID;

			if(is_category($category_id)) {
				echo '<div class="cat-post-thumbnail"><img src="http://a1.dspncdn.com/media/692x/e6/1d/74/e61d7432c874b317ddbd4de7217be10f.jpg"></div>';
			}*/
			//

			$this_cat = get_category_by_slug('category-slug'); 
  			$id = $this_cat->term_id;

			if(is_category('music')) {
				
				echo '<div class="cat-post-wrap">
						<div class="cat-post-thumbnail">
							<img src="http://aeonpet.designsample.biz/Dev/AEONPET/wp-content/uploads/2016/02/ce9cec8845b99dec3a8d38eaa7ed3cf0.jpg" class="clip-square">
						</div>
					  </div>';
			}
			elseif(is_category('nature')) {
				echo '<div class="cat-post-wrap">
						<div class="cat-post-thumbnail">
							<img src="http://aeonpet.designsample.biz/Dev/AEONPET/wp-content/uploads/2016/03/amgmtimes_page_pc_under_header_img.jpg">
						</div>
					  </div>';
			}
			elseif(is_category('design')) {
				echo '<div class="cat-post-wrap">
						<div class="cat-post-thumbnail">
							<img src="http://a1.dspncdn.com/media/692x/0e/3f/c7/0e3fc7c7186f73c2ebae8d56ae117b06.jpg">
						</div>
					  </div>';
			}
			else{
				echo '<div class="cat-post-wrap">
						<div class="cat-post-thumbnail">
							<img src="http://aeonpet.designsample.biz/Dev/AEONPET/wp-content/uploads/2016/03/amgmtimes_page_pc_under_header_img.jpg">
						</div>
					  </div>';
			}
			//
			/*$cat_img = get_category_by_slug('category-slug');
			$id = $cat_img->term_id;

			switch ($id) {
				case 'music':

				$cat_image = 'hello world';

				break;

				case 'nature':

				$cat_image = 'not you!';

				break;

				default: 

				echo 'This is not working';

			}
			echo $cat_image;*/
			//

			echo '<div class="row">';
			include(get_template_directory().'/loop.php');
			echo '</div>';


			// Previous/next page navigation.
			the_posts_pagination(array(
				'prev_text'          => __( 'Previous page', 'bluthemes' ),
				'next_text'          => __( 'Next page', 'bluthemes' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'bluthemes' ) . ' </span>',
			));

		}else{ 

			get_template_part( 'inc/template-parts/content', 'none' );
		}


		?> 
	</div>