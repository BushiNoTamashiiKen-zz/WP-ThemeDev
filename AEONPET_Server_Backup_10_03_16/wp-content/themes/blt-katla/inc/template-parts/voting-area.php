<?php
/**
 * Post Vote Up and Down button area display part
 * For inclusion anywhere on any template using get_template_part().
 * 
 * (Separating the logic)
 *
 */

if(blt_get_option('post_voting', true)){ 

	$score = get_post_meta( get_the_ID(), 'blt_score', true );

	if(empty($score)){
		$score = 0;
	} 
	?>
	<div class="content-footer-meta">
		<div class="post-vote-header">
			<a class="btn btn-default post-vote post-vote-up" data-post-id="<?php echo get_the_ID() ?>" href="#vote-up" title="<?php echo esc_attr(__('Like', 'bluthemes')) ?>"><i class="fa fa-chevron-up"></i></a>
			<span class="vote-count"><?php echo esc_attr($score); ?></span>
			<a class="btn btn-default post-vote post-vote-down" data-post-id="<?php echo get_the_ID() ?>" href="#vote-down" title="<?php echo esc_attr(__('Dislike', 'bluthemes')) ?>"><i class="fa fa-chevron-down"></i></a>
		</div>
	</div><?php
}