<?php

	/***
	***	@Members Filter Hooks
	***/
	add_filter('um_prepare_user_query_args', 'um_prepare_user_query_args', 10, 2);
	add_filter('um_prepare_user_query_args', 'um_add_search_to_query', 50, 2);
	add_filter('um_prepare_user_query_args', 'um_search_usernames_emails', 51, 2);
	add_filter('um_prepare_user_query_args', 'um_remove_special_users_from_list', 99, 2);

	/***
	***	@WP API user search
	***/
	function um_search_usernames_emails( $query_args, $args ) {
		global $ultimatemember;
		extract( $args );

		$query = $ultimatemember->permalinks->get_query_array();

		foreach( $ultimatemember->members->core_search_fields as $key ) {
			if ( isset( $query[$key] ) ) {
				$query_args['search']         = '*' . trim($query[$key]) . '*';
			}
		}
		return $query_args;
	}

	/***
	***	@Remove users we do not need to show in directory
	***/
	function um_remove_special_users_from_list( $query_args, $args ) {
		global $ultimatemember;
		extract( $args );

		$query_args['meta_query']['relation'] = 'AND';

		if ( !um_user_can('can_edit_everyone')  ) {

			$query_args['meta_query'][] = array(
				'key' => 'account_status',
				'value' => 'approved',
				'compare' => '='
			);

		}

		$query_args['meta_query'][] = array(
			'key' => 'hide_in_members',
			'value' => '',
			'compare' => 'NOT EXISTS'
		);

		return $query_args;
	}

	/***
	***	@adds search parameters
	***/
	function um_add_search_to_query( $query_args, $args ){
		global $ultimatemember;
		extract( $args );

		if ( isset( $_REQUEST['um_search'] ) ) {

			$query = $ultimatemember->permalinks->get_query_array();

			if ( $query && is_array( $query ) ) {
				foreach( $query as $field => $value ) {

					if(in_array($field, array('members_page'))) continue;

					if ( in_array( $field, array('gender') ) ) {
						$operator = '=';
					} else {
						$operator = 'LIKE';
					}

					if ( in_array( $ultimatemember->fields->get_field_type( $field ), array('checkbox','multiselect') ) ) {
						$operator = 'LIKE';
					}

					if ( $value && $field != 'um_search' && $field != 'page_id' ) {

						if ( !in_array( $field, $ultimatemember->members->core_search_fields ) ) {

							if ( strstr($field, 'role_' ) ) {
								$field = 'role';
								$operator = '=';
							}

							$query_args['meta_query'][] = array(
								'key' => $field,
								'value' => trim($value),
								'compare' => $operator,
							);

						}

					}

				}
			}

		}

		// allow filtering
		$query_args = apply_filters('um_query_args_filter', $query_args );

		if ( count ($query_args['meta_query']) == 1 ) {
			unset( $query_args['meta_query'] );
		}

		return $query_args;

	}

	/***
	***	@adds main parameters
	***/
	function um_prepare_user_query_args($query_args, $args){
		global $ultimatemember;
		extract( $args );

		$query_args['fields'] = 'ID';

		$query_args['number'] = 0;

		$query_args['meta_query']['relation'] = 'AND';

		// must have a profile photo
		if ( $has_profile_photo == 1 && ! um_get_option('use_gravatars') ) {
			$query_args['meta_query'][] = array(
				'relation' => 'OR',
				array(
					'key' => 'synced_profile_photo', // addons
					'value' => '',
					'compare' => '!='
				),
				array(
					'key' => 'profile_photo', // from upload form
					'value' => '',
					'compare' => '!='
				),
				array(
					'key' => 'synced_gravatar_hashed_id', //  gravatar
					'value' => '',
					'compare' => '!='
				)
				
			);
		}

		// must have a cover photo
		if ( $has_cover_photo == 1 ) {
			$query_args['meta_query'][] = array(
				'key' => 'cover_photo',
				'value' => '',
				'compare' => '!='
			);
		}


		// show specific usernames
		if ( isset( $show_these_users ) && $show_these_users && is_array( $show_these_users ) ) {
			foreach( $show_these_users as $username ) {
				$users_array[] = username_exists( $username );
			}
			$query_args['include'] = $users_array;
		}

		// add roles to appear in directory
		if ( !empty( $roles ) ) {

			$query_args['meta_query'][] = array(
				'key' => 'role',
				'value' => $roles,
				'compare' => 'IN'
			);

		}

		// sort members by
		$query_args['order'] = 'ASC';

		if ( isset( $sortby ) ) {


			if ( $sortby == 'other' && $sortby_custom ) {

				$query_args['meta_key'] = $sortby_custom;
				$query_args['orderby'] = 'meta_value, display_name';

			} else if ( in_array( $sortby, array( 'last_name', 'first_name' ) ) ) {

				$query_args['meta_key'] = $sortby;
				$query_args['orderby'] = 'meta_value';

			} else {

				if ( strstr( $sortby, '_desc' ) ) {$sortby = str_replace('_desc','',$sortby);$order = 'DESC';}
				if ( strstr( $sortby, '_asc' ) ) {$sortby = str_replace('_asc','',$sortby);$order = 'ASC';}
				$query_args['orderby'] = $sortby;

			}

			if ( isset( $order ) ) {
				$query_args['order'] = $order;
			}

			$query_args = apply_filters('um_modify_sortby_parameter', $query_args, $sortby);

		}


		return $query_args;
	}

	/***
	***	@sorting by last login date
	***/
	add_filter('um_modify_sortby_parameter', 'um_sortby_last_login', 100, 2 );
	function um_sortby_last_login( $query_args, $sortby ) {
		if ( $sortby == 'last_login' ) {
			$query_args['orderby'] = 'meta_value_num';
			$query_args['order'] = 'desc';
			$query_args['meta_key'] = '_um_last_login';
		}
		return $query_args;
	}

	/***
	***	@hook in the member results array
	***/
	add_filter('um_prepare_user_results_array', 'um_prepare_user_results_array', 50, 2);
	function um_prepare_user_results_array($result){

		if ( empty( $result['users_per_page'] ) ) {
			$result['no_users'] = 1;
		} else {
			$result['no_users'] = 0;
		}

		return $result;
	}

