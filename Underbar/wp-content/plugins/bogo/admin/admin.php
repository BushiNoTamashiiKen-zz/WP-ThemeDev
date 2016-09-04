<?php

require_once BOGO_PLUGIN_DIR . '/admin/includes/user.php';
require_once BOGO_PLUGIN_DIR . '/admin/includes/post.php';
require_once BOGO_PLUGIN_DIR . '/admin/includes/nav-menu.php';
require_once BOGO_PLUGIN_DIR . '/admin/includes/widgets.php';

add_action( 'admin_init', 'bogo_upgrade' );

function bogo_upgrade() {
	$old_ver = bogo_get_prop( 'version' );
	$new_ver = BOGO_VERSION;

	if ( $old_ver != $new_ver ) {
		require_once BOGO_PLUGIN_DIR . '/admin/includes/upgrade.php';
		do_action( 'bogo_upgrade', $new_ver, $old_ver );
		bogo_set_prop( 'version', $new_ver );
	}
}

add_action( 'admin_enqueue_scripts', 'bogo_admin_enqueue_scripts' );

function bogo_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'bogo-admin',
		plugins_url( 'admin/includes/css/admin.css', BOGO_PLUGIN_BASENAME ),
		array(), BOGO_VERSION, 'all' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'bogo-admin-rtl',
			plugins_url( 'admin/includes/css/admin-rtl.css', BOGO_PLUGIN_BASENAME ),
			array(), BOGO_VERSION, 'all' );
	}

	wp_enqueue_script( 'bogo-admin',
		plugins_url( 'admin/includes/js/admin.js', BOGO_PLUGIN_BASENAME ),
		array( 'jquery' ), BOGO_VERSION, true );

	$local_args = array(
		'rest_api' => array(
			'url' => trailingslashit( rest_url( 'bogo/v1' ) ),
			'nonce' => wp_create_nonce( 'wp_rest' ) ) );

	if ( 'nav-menus.php' == $hook_suffix ) {
		$nav_menu_id = absint( get_user_option( 'nav_menu_recently_edited' ) );
		$nav_menu_items = wp_get_nav_menu_items( $nav_menu_id );
		$locales = array();

		foreach ( (array) $nav_menu_items as $item ) {
			$locales[$item->db_id] = $item->bogo_locales;
		}

		$local_args = array_merge( $local_args, array(
			'availableLanguages' => bogo_available_languages( array(
				'exclude_enus_if_inactive' => true,
				'orderby' => 'value' ) ),
			'locales' => $locales,
			'selectorLegend' => __( 'Displayed on pages in', 'bogo' ),
			'cbPrefix' => 'menu-item-bogo-locale' ) );
	}

	if ( 'options-general.php' == $hook_suffix ) {
		$local_args = array_merge( $local_args, array(
			'defaultLocale' => bogo_get_default_locale() ) );
	}

	if ( 'post.php' == $hook_suffix && ! empty( $GLOBALS['post'] ) ) {
		$post = $GLOBALS['post'];
		$local_args = array_merge( $local_args, array(
			'post_id' => $post->ID ) );
	}

	wp_localize_script( 'bogo-admin', '_bogo', $local_args );
}

add_action( 'admin_menu', 'bogo_admin_menu' );

function bogo_admin_menu() {
	$tools = add_management_page(
		__( 'Bogo Tools', 'bogo' ), __( 'Bogo', 'bogo' ),
		'update_core', 'bogo-tools', 'bogo_tools_page' );

	add_action( 'load-' . $tools, 'bogo_load_tools_page' );
}

function bogo_load_tools_page() {
	require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

	$action = isset( $_GET['action'] ) ? $_GET['action'] : '';

	if ( 'install_translation' == $action ) {
		check_admin_referer( 'bogo-tools' );

		if ( ! current_user_can( 'update_core' ) ) {
			wp_die( __( 'You are not allowed to install translations.', 'bogo' ) );
		}

		$locale = isset( $_GET['locale'] ) ? $_GET['locale'] : null;

		if ( wp_download_language_pack( $locale ) ) {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'install_success' ),
				menu_page_url( 'bogo-tools', false ) );
		} else {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'install_failed' ),
				menu_page_url( 'bogo-tools', false ) );
		}

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'delete_translation' == $action ) {
		check_admin_referer( 'bogo-tools' );

		if ( ! current_user_can( 'update_core' ) ) {
			wp_die( __( 'You are not allowed to delete translations.', 'bogo' ) );
		}

		$locale = isset( $_GET['locale'] ) ? $_GET['locale'] : null;

		if ( bogo_delete_language_pack( $locale ) ) {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'delete_success' ),
				menu_page_url( 'bogo-tools', false ) );
		} else {
			$redirect_to = add_query_arg(
				array( 'locale' => $locale, 'message' => 'delete_failed' ),
				menu_page_url( 'bogo-tools', false ) );
		}

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'deactivate_enus' == $action ) {
		check_admin_referer( 'bogo-tools' );

		bogo_set_prop( 'enus_deactivated', true );

		$redirect_to = add_query_arg(
			array( 'message' => 'enus_deactivated' ),
			menu_page_url( 'bogo-tools', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'activate_enus' == $action ) {
		check_admin_referer( 'bogo-tools' );

		bogo_set_prop( 'enus_deactivated', false );

		$redirect_to = add_query_arg(
			array( 'message' => 'enus_activated' ),
			menu_page_url( 'bogo-tools', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}
}

function bogo_tools_page() {
	$enus_deactivated = bogo_get_prop( 'enus_deactivated' );
	$can_install = wp_can_install_language_pack();

	$default_locale = bogo_get_default_locale();
	$available_locales = bogo_available_locales();

?>
<div class="wrap">

<h2><?php echo esc_html( __( 'Bogo Tools', 'bogo' ) ); ?></h2>

<?php bogo_admin_notice(); ?>

<h3 class="title"><?php echo esc_html( __( 'Available Languages', 'bogo' ) ); ?></h3>

<table id="bogo-languages-table" class="widefat">
<thead>
	<tr><th></th><th><?php echo esc_html( __( 'Language', 'bogo' ) ); ?></th></tr>
</thead>
<tfoot>
	<tr><th></th><th><?php echo esc_html( __( 'Language', 'bogo' ) ); ?></th></tr>
</tfoot>
<tbody id="translations">
	<tr class="active"><th>1</th><td>
		<strong><?php echo esc_html( bogo_get_language( $default_locale ) ); ?></strong>
		[<?php echo esc_html( $default_locale ); ?>]
		<div class="status"><span class="status"><?php echo esc_html( __( 'Site Default Language', 'bogo' ) ); ?></span></div>
	</td></tr>

<?php
	$count = 1;

	foreach ( $available_locales as $locale ) {
		if ( $locale == $default_locale ) {
			continue;
		}

		$count += 1;
		$class = 'active';

		if ( 'en_US' == $locale ) {
			$status = $enus_deactivated
				? esc_html( __( 'Inactive', 'bogo' ) )
				: esc_html( __( 'Active', 'bogo' ) );
		} else {
			$status = esc_html( __( 'Installed', 'bogo' ) );
		}

		if ( 'en_US' == $locale ) {
			if ( $enus_deactivated ) {
				$activate_link = menu_page_url( 'bogo-tools', false );
				$activate_link = add_query_arg(
					array( 'action' => 'activate_enus' ),
					$activate_link );
				$activate_link = wp_nonce_url( $activate_link, 'bogo-tools' );
				$activate_link = sprintf(
					'<a href="%1$s" class="activate">%2$s</a>',
					$activate_link,
					esc_html( __( 'Activate', 'bogo' ) ) );

				$status = sprintf(
					'<div class="status"><span class="status">%s</span> | %s</div>',
					$status, $activate_link );
				$class = '';
			} else {
				$deactivate_link = menu_page_url( 'bogo-tools', false );
				$deactivate_link = add_query_arg(
					array( 'action' => 'deactivate_enus' ),
					$deactivate_link );
				$deactivate_link = wp_nonce_url( $deactivate_link, 'bogo-tools' );
				$deactivate_link = sprintf(
					'<a href="%1$s" class="deactivate">%2$s</a>',
					$deactivate_link,
					esc_html( __( 'Deactivate', 'bogo' ) ) );

				$status = sprintf(
					'<div class="status"><span class="status">%s</span> | %s</div>',
					$status, $deactivate_link );
			}
		} elseif ( $can_install ) {
			$delete_link = menu_page_url( 'bogo-tools', false );
			$delete_link = add_query_arg(
				array( 'action' => 'delete_translation', 'locale' => $locale ),
				$delete_link );
			$delete_link = wp_nonce_url( $delete_link, 'bogo-tools' );

			if ( ! $delete_language = bogo_get_language( $locale ) ) {
				$delete_language = $locale;
			}

			$delete_confirm = sprintf( __( "You are about to delete %s language pack.\n  'Cancel' to stop, 'OK' to delete.", 'bogo' ), $delete_language );

			$delete_link = sprintf( '<a href="%1$s" class="delete" onclick="if (confirm(\'%3$s\')){return true;} return false;">%2$s</a>',
				$delete_link,
				esc_html( __( 'Delete', 'bogo' ) ),
			 	esc_js( $delete_confirm ) );

			$status = sprintf(
				'<div class="status"><span class="status">%s</span> | %s</div>',
				$status, $delete_link );
		}
?>
	<tr class="<?php echo esc_attr( $class ); ?>">
		<th><?php echo $count; ?></th>
		<td>
		<strong><?php echo esc_html( bogo_get_language( $locale ) ); ?></strong>
		[<?php echo esc_html( $locale ); ?>]
		<?php echo $status; ?>
		</td>
	</tr>
<?php
	}

	foreach ( wp_get_available_translations() as $locale => $translation ) {
		if ( in_array( $locale, $available_locales ) ) {
			continue;
		}

		$count += 1;

		$install_link = '';

		if ( $can_install ) {
			$install_link = menu_page_url( 'bogo-tools', false );
			$install_link = add_query_arg(
				array( 'action' => 'install_translation', 'locale' => $locale ),
				$install_link );
			$install_link = wp_nonce_url( $install_link, 'bogo-tools' );
			$install_link = sprintf( '<a href="%1$s" class="install">%2$s</a>',
				$install_link, esc_html( __( 'Install', 'bogo' ) ) );
		}
?>
	<tr><th><?php echo $count; ?></th><td>
		<strong><?php echo esc_html( bogo_get_language( $locale ) ); ?></strong>
		[<?php echo esc_html( $locale ); ?>]
		<?php echo $install_link; ?>
	</td></tr>
<?php
	}
?>

</tbody>
</table>

</div>
<?php
}

function bogo_admin_notice( $reason = '' ) {
	if ( empty( $reason ) && isset( $_GET['message'] ) ) {
		$reason = $_GET['message'];
	}

	if ( 'install_success' == $reason ) {
		$message = __( "Translation installed successfully.", 'bogo' );
	} elseif ( 'install_failed' == $reason ) {
		$message = __( "Translation install failed.", 'bogo' );
	} elseif ( 'delete_success' == $reason ) {
		$message = __( "Translation uninstalled successfully.", 'bogo' );
	} elseif ( 'delete_failed' == $reason ) {
		$message = __( "Translation uninstall failed.", 'bogo' );
	} elseif ( 'enus_deactivated' == $reason ) {
		$message = __( "English (United States) deactivated.", 'bogo' );
	} elseif ( 'enus_activated' == $reason ) {
		$message = __( "English (United States) activated.", 'bogo' );
	} else {
		return false;
	}

	if ( 'install_failed' == $reason || 'delete_failed' == $reason ) {
		echo sprintf(
			'<div class="error notice notice-error is-dismissible"><p>%s</p></div>',
			esc_html( $message ) );
	} else {
		echo sprintf(
			'<div class="updated notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html( $message ) );
	}
}

function bogo_delete_language_pack( $locale ) {
	if ( 'en_US' == $locale
	|| ! bogo_is_available_locale( $locale )
	|| bogo_is_default_locale( $locale ) ) {
		return false;
	}

	if ( ! is_dir( WP_LANG_DIR ) || ! $files = scandir( WP_LANG_DIR ) ) {
		return false;
	}

	$target_files = array(
		sprintf( '%s.mo', $locale ),
		sprintf( '%s.po', $locale ),
		sprintf( 'admin-%s.mo', $locale ),
		sprintf( 'admin-%s.po', $locale ),
		sprintf( 'admin-network-%s.mo', $locale ),
		sprintf( 'admin-network-%s.po', $locale ),
		sprintf( 'continents-cities-%s.mo', $locale ),
		sprintf( 'continents-cities-%s.po', $locale ) );

	foreach ( $files as $file ) {
		if ( '.' === $file[0] || is_dir( $file ) ) {
			continue;
		}

		if ( in_array( $file, $target_files ) ) {
			$result = @unlink( path_join( WP_LANG_DIR, $file ) );

			if ( ! $result ) {
				return false;
			}
		}
	}

	return true;
}
