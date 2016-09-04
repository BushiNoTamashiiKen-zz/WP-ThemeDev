<?php

// Options Page Functions

function themeoptions_admin_menu() 
{
	// here's where we add our theme options page link to the dashboard sidebar
	add_theme_page("テーマ設定", "テーマ設定", 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

function themeoptions_page() 
{
	// here's the main function that will generate our options page
	
	if ( $_POST['update_themeoptions'] == 'true' ) { themeoptions_update(); }
	
	//if ( get_option() == 'checked'
	
	$fonts = array(
		'Bebas_Neue'             => 'Bebas Neue',
		'Premier_League'         => 'Premier League',
		'Stentiga'               => 'Stentiga',
		'Shlop'                  => 'Shlop',
		'Kremlin'                => 'Kremlin',
		'BatmanForeverOutline'   => 'BatmanForeverOutline',
		'BatmanForeverAlternate' => 'BatmanForeverAlternate',
		'Shanghai'               => 'Shanghai',
	);
	
	?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2>レシピ 設定</h2>
	
		<form method="POST" action="">
			<input type="hidden" name="update_themeoptions" value="true" />
			
			<table class="form-table">
				<tr valign="top">
					<th>デフォルトフォント</th>
					<td>
						<select name="cufon_font">
							<?php foreach ($fonts as $value => $name) : ?>
								<option value="<?php echo $value ?>" <?php if (get_option('cufon_font') === $value) : ?>selected="selected" <?php endif ?>><?php echo $name ?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th>デフォルトメインメッセージ</th>
					<td><textarea name="default_message" rows="5" cols="60" ><?php echo get_option('default_message'); ?></textarea></td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="変更を保存" class="button-primary" /></p>
		</form>
	
	</div>
	<?php
}

function themeoptions_update()
{
	update_option('default_message', 	$_POST['default_message']);
	update_option('cufon_font', 	$_POST['cufon_font']);
}

add_action('admin_menu', 'themeoptions_admin_menu');

?>
