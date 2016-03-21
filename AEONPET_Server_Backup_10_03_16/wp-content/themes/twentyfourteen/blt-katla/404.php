<?php get_header(); ?>

<div id="site-content" class="clearfix">
	
	<div id="site-content-column"><?php

			get_template_part( 'inc/template-parts/content', 'none' ); ?>

	</div><?php

	# 	
	# 	SIDEBAR
	# 	========================================================================================
	#   Load the sidebar if needed
	# 	========================================================================================
	# 		
	if(in_array(blt_get_option('sidebar_layout', 'right'), array('left', 'right'), true)){
		get_sidebar();
	} ?>

</div>

<?php get_footer(); ?>