<?php

$saved_r = (string)get_post_meta( get_the_ID(), '_um_synced_role', true );
$saved_r = ( $saved_r ) ? $saved_r : '';

$url = add_query_arg('um_adm_action', 'mass_role_sync', $ultimatemember->permalinks->get_current_url() );
$post = get_post( get_the_ID() );
$slug = $post->post_name;
$url = add_query_arg('um_role', $slug, $url);
$url = add_query_arg('wp_role', $saved_r, $url);

?>

<div class="um-admin-metabox">

	<div class="">
		
		<p>
			<label class="um-admin-half"><?php _e('Link to WordPress role','ultimatemember'); ?> <?php $this->tooltip( __('Users who get this community role will be assigned this WordPress role automatically','ultimatemember') ); ?></label>
			<span class="um-admin-half">
				<select name="_um_synced_role" id="_um_synced_role">
					<option value="0"><?php _e('None','ultimatemember'); ?></option>
					<?php wp_dropdown_roles( $saved_r ); ?>
				</select>
			</span>
		</p><div class="um-admin-clear"></div>
		
		<p><a href="<?php echo $url; ?>" class="button"><?php _e('Sync / update all existing users','ultimatemember'); ?></a></p>

	</div>
	
	<div class="um-admin-clear"></div>
	
</div>