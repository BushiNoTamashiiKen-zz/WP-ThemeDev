<form action="<?php echo home_url( '/' ); ?>" role="search">
	<input type="text" class="form-control" name="s" placeholder="<?php _e('Search...', 'bluthemes') ?>" value="<?php the_search_query(); ?>">
</form>