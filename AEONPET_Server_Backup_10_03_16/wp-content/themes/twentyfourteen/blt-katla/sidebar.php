<?php

// Get position
switch(true){
	
	case is_single():
		$position = 'post';
		break;
	
	case is_page():
		$position = 'page';

			if(is_page_template('user-posts-template.php')){
				$position = 'user-posts';
			}
		break;
	
	default:
		$position = 'primary';
		break;
}

$sidebar = blt_has_sidebar();

if($sidebar != 'none'){

	echo '<aside id="site-content-sidebar">';
		echo '<div class="content-sidebar-wrap">';

		// Regular
		if(is_active_sidebar($position.'-sidebar')){ 

			dynamic_sidebar($position.'-sidebar');

		}else{

			dynamic_sidebar('primary-sidebar');
		}


		// Sticky
		if(is_active_sidebar($position.'-sidebar-sticky') or is_active_sidebar('primary-sidebar-sticky')){ 
			echo '<div class="blt-sidebar-sticky">';
				echo '<div class="blt-sidebar-sticky-wrap">';
					if(is_active_sidebar($position.'-sidebar-sticky')){ 

						dynamic_sidebar($position.'-sidebar-sticky');

					}else{

						dynamic_sidebar('primary-sidebar-sticky');
					}
				echo '</div>';
			echo '</div>';
		}

		echo '</div>';
	echo '</aside>';
}