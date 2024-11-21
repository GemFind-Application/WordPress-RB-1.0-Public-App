<?php
if ( isset( $_POST['settings'] ) && ! empty( $_POST['settings'] ) ) {
	global $wpdb;
	unset( $_POST['settings'] );
	$post_data = $_POST['gemfind_ring_builder'];
	if ( $post_data['dealerid'] == '' ) {
		_e( '<div class="error"><p>Dealerid is required.</p></div>', 'ringbuilder' );
	}
	$password       = $post_data['password'];
	$hashedPassword = wp_hash_password( $password );
	$data           = array(
		'dealerid'                => $post_data['dealerid'],
		'password'                => $hashedPassword,
		'from_email_address'      => $post_data['from_email_address'],
		'admin_email_address'     => $post_data['admin_email_address'],
		'dealerauthapi'           => $post_data['dealerauthapi'],
		'ringfiltersapi'          => $post_data['ringfiltersapi'],
		'mountinglistapi'         => $post_data['mountinglistapi'],
		'mountingdetailapi'       => $post_data['mountingdetailapi'],
		'ringstylesettingsapi'    => $post_data['ringstylesettingsapi'],
		'navigationapi'           => $post_data['navigationapi'],
		'navigationapirb'         => $post_data['navigationapirb'],
		'filterapi'               => $post_data['filterapi'],
		'filterapifancy'          => $post_data['filterapifancy'],
		'diamondlistapi'          => $post_data['diamondlistapi'],
		'diamondlistapifancy'     => $post_data['diamondlistapifancy'],
		'diamondshapeapi'         => $post_data['diamondshapeapi'],
		'diamonddetailapi'        => $post_data['diamonddetailapi'],
		'stylesettingapi'         => $post_data['stylesettingapi'],
		'diamondsoptionapi'       => $post_data['diamondsoptionapi'],
		'enable_hint'             => $post_data['enable_hint'],
		'enable_email_friend'     => $post_data['enable_email_friend'],
		'enable_schedule_viewing' => $post_data['enable_schedule_viewing'],
		'enable_more_info'        => $post_data['enable_more_info'],
		'enable_print'            => $post_data['enable_print'],
		'enable_admin_notify'     => $post_data['enable_admin_notify'],
		'default_view'            => $post_data['default_view'],
	    'show_hints_popup'        => $post_data['show_hints_popup'],
	    'show_copyright'          => $post_data['show_copyright'],
	    'enable_sticky_header'    => $post_data['enable_sticky_header'],
	    'enable_tryon'   		  => $post_data['enable_tryon'],
		'shop_logo'               => $post_data['shop_logo'],
		'site_key'                => $post_data['site_key'],
		'secret_key'              => $post_data['secret_key'],
		'carat_ranges'			  => $post_data['carat_ranges'],
		'top_textarea'			  => $post_data['top_textarea'],
		'detail_textarea'		  => $post_data['detail_textarea'],
		'ring_meta_title'		  => $post_data['ring_meta_title'],
		'ring_meta_description'		  => $post_data['ring_meta_description'],
		'ring_meta_keywords'		  => $post_data['ring_meta_keywords'],
		'diamond_meta_title'		  => $post_data['diamond_meta_title'],
		'diamond_meta_description'		  => $post_data['diamond_meta_description'],
		'price_row_format'			=>	$post_data['price_row_format'],
		'diamond_meta_keyword'		  => $post_data['diamond_meta_keyword'],
		'load_from_woocommerce'   => ( isset( $_POST['woocommerce'] ) ) ? $_POST['woocommerce'] : 0
	);
	if ( isset( $post_data['dealerid'] ) && $post_data['dealerid'] != '' ) {
		update_option( 'gemfind_ring_builder', $data );
	}
	$gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
	$product_type         = array( 'diamond', 'ring' );

	// Attribute for identifying whether the product is a ring or a diamond.
	if ( ! empty( $product_type ) ) {
		$name              = 'gemfind_product_type';
		$label             = 'Diamnond Or Ring';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		if ( ! term_exists( $product_type[0], 'pa_' . $name ) ) {
			wp_insert_term( ucfirst( $product_type[0] ), 'pa_' . $name, array( 'slug' => $product_type[0] ) );
		}
		if ( ! term_exists( $product_type[1], 'pa_' . $name ) ) {
			wp_insert_term( ucfirst( $product_type[1] ), 'pa_' . $name, array( 'slug' => $product_type[1] ) );
		}
	}

	// For creating ring attributes.
	$results_ring = getRingFiltersRB();
	foreach ( $results_ring as $k => $v ) {
		if ( $k == 'shapes' ) {
			$shape_array_ring = $v;
		} elseif ( $k == 'collections' ) {
			$collection_array_ring = $v;
		} elseif ( $k == 'metalType' ) {
			$metalType_array_ring = $v;
		}
	}
	if ( ! empty( $shape_array_ring ) ) {
		$name              = 'gemfind_ring_shape';
		$label             = 'Shape Ring';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $shape_array_ring as $k => $shape ) {
			if ( ! term_exists( $shape->shapeName, 'pa_' . $name ) ) {
				wp_insert_term( $shape->shapeName, 'pa_' . $name );
			}
		}
	}
	if ( ! empty( $collection_array_ring ) ) {
		$name              = 'gemfind_ring_collection';
		$label             = 'Collection Ring';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $collection_array_ring as $k => $collection ) {
			if ( ! term_exists( $collection->collectionName, 'pa_' . $name ) ) {
				$term_array                       = wp_insert_term( $collection->collectionName, 'pa_' . $name );
				$t_id                             = $term_array['term_id'];
				$term_meta                        = get_option( "taxonomy_$t_id" );
				$term_meta['ring_collection_img'] = $collection->collectionImage;
				update_option( "taxonomy_$t_id", $term_meta );
			}
		}
	}
	if ( ! empty( $metalType_array_ring ) ) {
		$name              = 'gemfind_ring_metaltype';
		$label             = 'Metal Type Ring';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $metalType_array_ring as $k => $metalType ) {
			if ( ! term_exists( $metalType->metalType, 'pa_' . $name ) ) {
				wp_insert_term( $metalType->metalType, 'pa_' . $name );
			}
		}
	}
	// if ( ! empty( $metalType_array_ring ) ) {
	//   $name      = 'gemfind_ring_metalType';
	//   $label = 'MetalType Ring';
	//   $attribute_name = str_replace(" ", "-", strtolower(trim($name)));
	//   $attribute_label = $label;
	//   $attribute_type = 'select';
	//   $attribute_orderby = 'menu_order';
	//   $attribute_public = 0;
	//   $args = array(
	//     'name' => $attribute_label,
	//     'slug' => $attribute_name,
	//     'type' => $attribute_type,
	//     'order_by' => $attribute_orderby,
	//     'has_archives' => $attribute_public,
	//     );
	//   $attribute_id = $wpdb->get_var( "SELECT attribute_id
	//     FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
	//     WHERE attribute_name LIKE '$name'" );
	//   if( empty( $attribute_id ) ) {
	//     wc_create_attribute($args);
	//   }
	//   register_taxonomy( 'pa_' . $name, array('product') );
	//   $metalType_array_ring = array();
	//   $metalType_array_ring[] = (object)array("metalType" => 'asdf', "isActive" => 1);
	//   echo '<pre>'; print_r($metalType_array_ring); echo '</pre>';
	//   $i = 1;
	//   foreach ( $metalType_array_ring as $k => $metalType ) {
	//         if ( ! term_exists( $metalType->metalType, 'pa_' . $name ) ) {
	//             wp_insert_term( $metalType->metalType, 'pa_' . $name, array('description'=> 'A yummy apple.', 'slug' => $i   ) );
	//         }
	//         $i++;
	//     }
	// }

	// For creating diamond attributes.
	$requestUrl = $gemfind_ring_builder['filterapi'] . 'DealerID=' . $gemfind_ring_builder['dealerid'];

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $requestUrl );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 15 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	$response = curl_exec( $ch );
	$results  = json_decode( $response );
	// For filterapi
	if ( sizeof( $results ) > 1 && $results[0]->message == 'Success' ) {
		//echo '<pre>'; print_r($results[1]); echo '</pre>'; exit;
		foreach ( $results[1] as $value ) {
			$value1 = (array) $value;
			foreach ( $value1 as $k => $v ) {
				if ( $k == 'shapes' ) {
					$shape_array = $v;
				} elseif ( $k == 'cutRange' ) {
					$cut_array = $v;
				} elseif ( $k == 'clarityRange' ) {
					$clarity_array = $v;
				} elseif ( $k == 'colorRange' ) {
					$color_array = $v;
				} elseif ( $k == 'polishRange' ) {
					$polish_array = $v;
				} elseif ( $k == 'symmetryRange' ) {
					$symmetry_array = $v;
				} elseif ( $k == 'fluorescenceRange' ) {
					$fluorescence_array = $v;
				} elseif ( $k == 'certificateRange' ) {
					$certificate_array = $v;
				} elseif ( $k == 'videoFlag' ) {
					$video_array = $v;
				} else {
					$normal_values = $k;
				}
			}
		}	
	if ( ! empty( $shape_array ) ) {
		$name              = 'gemfind_shape';
		$label             = 'Shape';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $shape_array as $k => $shape ) {
			if ( ! term_exists( $shape->shapeName, 'pa_' . $name ) ) {
				wp_insert_term( $shape->shapeName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $cut_array ) ) {
		$name              = 'gemfind_cut';
		$label             = 'Cut';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $cut_array as $k => $cut ) {
			if ( ! term_exists( $cut->cutName, 'pa_' . $name ) ) {
				wp_insert_term( $cut->cutName, 'pa_' . $name, array( 'slug' => $cut->cutId ) );
			}
		}
	}

	if ( ! empty( $clarity_array ) ) {
		$name              = 'gemfind_clarity';
		$label             = 'Clarity';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $clarity_array as $k => $clarity ) {
			if ( ! term_exists( $clarity->clarityName, 'pa_' . $name ) ) {
				wp_insert_term( $clarity->clarityName, 'pa_' . $name, array( 'slug' => $clarity->clarityId ) );
			}
		}
	}

	if ( ! empty( $color_array ) ) {
		$name              = 'gemfind_color';
		$label             = 'Color';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_color';
		foreach ( $color_array as $k => $color ) {
			if ( ! term_exists( $color->colorName, 'pa_' . $name ) ) {
				wp_insert_term( $color->colorName, 'pa_' . $name, array( 'slug' => $color->colorId ) );
			}
		}
	}

	if ( ! empty( $polish_array ) ) {
		$name              = 'gemfind_polish';
		$label             = 'Polish';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_polish';
		foreach ( $polish_array as $k => $polish ) {
			if ( ! term_exists( $polish->polishName, 'pa_' . $name ) ) {
				wp_insert_term( $polish->polishName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $symmetry_array ) ) {
		$name              = 'gemfind_symmetry';
		$label             = 'Symmetry';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_symmetry';
		foreach ( $symmetry_array as $k => $symmetry ) {
			if ( ! term_exists( $symmetry->symmteryName, 'pa_' . $name ) ) {
				wp_insert_term( $symmetry->symmteryName, 'pa_' . $name, array( 'slug' => $symmetry->symmetryId ) );
			}
		}
	}

	if ( ! empty( $fluorescence_array ) ) {
		$name              = 'gemfind_fluorescence';
		$label             = 'Fluorescence';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_fluorescence';
		foreach ( $fluorescence_array as $k => $fluorescence ) {
			if ( ! term_exists( $fluorescence->fluorescenceName, 'pa_' . $name ) ) {
				wp_insert_term( $fluorescence->fluorescenceName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $certificate_array ) ) {
		$name              = 'gemfind_certificate';
		$label             = 'Certificate';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_certificate';
		foreach ( $certificate_array as $k => $certificate ) {
			if ( ! term_exists( $certificate->certificateName, 'pa_' . $name ) ) {
				wp_insert_term( $certificate->certificateName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $video_array ) ) {
		$name              = 'gemfind_video';
		$label             = 'Video';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_video';
		foreach ( $video_array as $k => $video ) {
			if ( ! term_exists( $video->hasVideo, 'pa_' . $name ) ) {
				wp_insert_term( $video->hasVideo, 'pa_' . $name );
			}
		}
	}

	$name              = 'gemfind_carat';
	$label             = 'Carat';
	$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
	$attribute_label   = $label;
	$attribute_type    = 'select';
	$attribute_orderby = 'menu_order';
	$attribute_public  = 0;
	$args              = array(
		'name'         => $attribute_label,
		'slug'         => $attribute_name,
		'type'         => $attribute_type,
		'order_by'     => $attribute_orderby,
		'has_archives' => $attribute_public,
	);
	$attribute_id      = $wpdb->get_var( "SELECT attribute_id
      FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
      WHERE attribute_name LIKE '$name'" );
	if ( empty( $attribute_id ) ) {
		wc_create_attribute( $args );
	}
	register_taxonomy( 'pa_' . $name, array( 'product' ) );

	$name              = 'gemfind_depth';
	$label             = 'Depth';
	$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
	$attribute_label   = $label;
	$attribute_type    = 'select';
	$attribute_orderby = 'menu_order';
	$attribute_public  = 0;
	$args              = array(
		'name'         => $attribute_label,
		'slug'         => $attribute_name,
		'type'         => $attribute_type,
		'order_by'     => $attribute_orderby,
		'has_archives' => $attribute_public,
	);
	$attribute_id      = $wpdb->get_var( "SELECT attribute_id
      FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
      WHERE attribute_name LIKE '$name'" );
	if ( empty( $attribute_id ) ) {
		wc_create_attribute( $args );
	}
	register_taxonomy( 'pa_' . $name, array( 'product' ) );
	$name              = 'gemfind_table';
	$label             = 'Table';
	$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
	$attribute_label   = $label;
	$attribute_type    = 'select';
	$attribute_orderby = 'menu_order';
	$attribute_public  = 0;
	$args              = array(
		'name'         => $attribute_label,
		'slug'         => $attribute_name,
		'type'         => $attribute_type,
		'order_by'     => $attribute_orderby,
		'has_archives' => $attribute_public,
	);
	$attribute_id      = $wpdb->get_var( "SELECT attribute_id
      FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
      WHERE attribute_name LIKE '$name'" );
	if ( empty( $attribute_id ) ) {
		wc_create_attribute( $args );
	}
	register_taxonomy( 'pa_' . $name, array( 'product' ) );
	$name              = 'gemfind_origin';
	$label             = 'Origin';
	$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
	$attribute_label   = $label;
	$attribute_type    = 'select';
	$attribute_orderby = 'menu_order';
	$attribute_public  = 0;
	$args              = array(
		'name'         => $attribute_label,
		'slug'         => $attribute_name,
		'type'         => $attribute_type,
		'order_by'     => $attribute_orderby,
		'has_archives' => $attribute_public,
	);
	$attribute_id      = $wpdb->get_var( "SELECT attribute_id
      FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
      WHERE attribute_name LIKE '$name'" );
	if ( empty( $attribute_id ) ) {
		wc_create_attribute( $args );
	}
	register_taxonomy( 'pa_' . $name, array( 'product' ) );
	$name              = 'gemfind_gridle';
	$label             = 'Gridle';
	$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
	$attribute_label   = $label;
	$attribute_type    = 'select';
	$attribute_orderby = 'menu_order';
	$attribute_public  = 0;
	$args              = array(
		'name'         => $attribute_label,
		'slug'         => $attribute_name,
		'type'         => $attribute_type,
		'order_by'     => $attribute_orderby,
		'has_archives' => $attribute_public,
	);
	$attribute_id      = $wpdb->get_var( "SELECT attribute_id
      FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
      WHERE attribute_name LIKE '$name'" );
	if ( empty( $attribute_id ) ) {
		wc_create_attribute( $args );
	}
	$something = new WP_Error();
	register_taxonomy( 'pa_' . $name, array( 'product' ) );
	$name              = 'gemfind_culet';
	$label             = 'Culet';
	$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
	$attribute_label   = $label;
	$attribute_type    = 'select';
	$attribute_orderby = 'menu_order';
	$attribute_public  = 0;
	$args              = array(
		'name'         => $attribute_label,
		'slug'         => $attribute_name,
		'type'         => $attribute_type,
		'order_by'     => $attribute_orderby,
		'has_archives' => $attribute_public,
	);
	$attribute_id      = $wpdb->get_var( "SELECT attribute_id
      FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
      WHERE attribute_name LIKE '$name'" );
	if ( empty( $attribute_id ) ) {
		wc_create_attribute( $args );
	}
	register_taxonomy( 'pa_' . $name, array( 'product' ) );
	$name              = 'gemfind_measurement';
	$label             = 'Measurement';
	$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
	$attribute_label   = $label;
	$attribute_type    = 'select';
	$attribute_orderby = 'menu_order';
	$attribute_public  = 0;
	$args              = array(
		'name'         => $attribute_label,
		'slug'         => $attribute_name,
		'type'         => $attribute_type,
		'order_by'     => $attribute_orderby,
		'has_archives' => $attribute_public,
	);
	$attribute_id      = $wpdb->get_var( "SELECT attribute_id
      FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
      WHERE attribute_name LIKE '$name'" );
	if ( empty( $attribute_id ) ) {
		wc_create_attribute( $args );
	}
	register_taxonomy( 'pa_' . $name, array( 'product' ) );
	}
	$gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
	$requestUrl           = $gemfind_ring_builder['filterapifancy'] . 'DealerID=' . $gemfind_ring_builder['dealerid'];

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $requestUrl );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 15 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	$response = curl_exec( $ch );
	$results  = json_decode( $response );
	//echo '<pre>'; print_r($results); echo '</pre>'; exit;
	if ( sizeof( $results ) > 1 && $results[0]->message == 'Success' ) {
		foreach ( $results[1] as $value ) {
			$value1 = (array) $value;
			foreach ( $value1 as $k => $v ) {
				if ( $k == 'shapes' ) {
					$shape_array = $v;
				} elseif ( $k == 'clarityRange' ) {
					$clarity_array = $v;
				} elseif ( $k == 'diamondColorRange' ) {
					$color_array = $v;
				} elseif ( $k == 'polishRange' ) {
					$polish_array = $v;
				} elseif ( $k == 'symmetryRange' ) {
					$symmetry_array = $v;
				} elseif ( $k == 'fluorescenceRange' ) {
					$fluorescence_array = $v;
				} elseif ( $k == 'certificateRange' ) {
					$certificate_array = $v;
				} elseif ( $k == 'videoFlags' ) {
					$video_array = $v;
				} elseif ( $k == 'intensity' ) {
					$intensity_array = $v;
				} elseif ( $k == 'originRange' ) {
					$origin_array = $v;
				} else {
					$normal_values = $k;
				}
			}
		}	
	//echo '<pre>'; print_r($clarity_array); echo '</pre>';
	if ( ! empty( $shape_array ) ) {
		$name              = 'gemfind_fancy_shape';
		$label             = 'Shape Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $shape_array as $k => $shape ) {
			if ( ! term_exists( $shape->shapeName, 'pa_' . $name ) ) {
				wp_insert_term( $shape->shapeName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $clarity_array ) ) {
		$name              = 'gemfind_fancy_clarity';
		$label             = 'Clarity Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $clarity_array as $k => $clarity ) {
			if ( ! term_exists( $clarity->clarityName, 'pa_' . $name ) ) {
				wp_insert_term( $clarity->clarityName, 'pa_' . $name, array( 'slug' => $clarity->clarityId ) );
			}
		}
	}

	if ( ! empty( $color_array ) ) {
		$name              = 'gemfind_fancy_color';
		$label             = 'Color Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_fancy_color';
		foreach ( $color_array as $k => $color ) {
			if ( ! term_exists( $color->diamondColorName, 'pa_' . $name ) ) {
				$term_array                   = wp_insert_term( $color->diamondColorName, 'pa_' . $name );
				$t_id                         = $term_array['term_id'];
				$term_meta                    = get_option( "taxonomy_$t_id" );
				$term_meta['fancy_color_img'] = $color->diamondColorImagePath;
				update_option( "taxonomy_$t_id", $term_meta );
			}
		}
	}

	if ( ! empty( $intensity_array ) ) {
		$name              = 'gemfind_fancy_intensity';
		$label             = 'Intensity Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_fancy_intensity';
		foreach ( $intensity_array as $k => $intensity ) {
			if ( ! term_exists( $intensity->intensityName, 'pa_' . $name ) ) {
				wp_insert_term( $intensity->intensityName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $polish_array ) ) {
		$name              = 'gemfind_fancy_polish';
		$label             = 'Polish Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_fancy_polish';
		foreach ( $polish_array as $k => $polish ) {
			if ( ! term_exists( $polish->polishName, 'pa_' . $name ) ) {
				wp_insert_term( $polish->polishName, 'pa_' . $name, array( 'slug' => $polish->polishId ) );
			}
		}
	}

	if ( ! empty( $symmetry_array ) ) {
		$name              = 'gemfind_fancy_symmetry';
		$label             = 'Symmetry Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		$name = 'gemfind_fancy_symmetry';
		foreach ( $symmetry_array as $k => $symmetry ) {
			if ( ! term_exists( $symmetry->symmteryName, 'pa_' . $name ) ) {
				wp_insert_term( $symmetry->symmteryName, 'pa_' . $name, array( 'slug' => $symmetry->symmetryId ) );
			}
		}
	}

	if ( ! empty( $fluorescence_array ) ) {
		$name              = 'gemfind_fancy_fluorescence';
		$label             = 'Fluorescence Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $fluorescence_array as $k => $fluorescence ) {
			if ( ! term_exists( $fluorescence->fluorescenceName, 'pa_' . $name ) ) {
				wp_insert_term( $fluorescence->fluorescenceName, 'pa_' . $name, array( 'slug' => $fluorescence->fluorescenceId ) );
			}
		}
	}

	if ( ! empty( $certificate_array ) ) {
		$name              = 'gemfind_fancy_certificate';
		$label             = 'Certificate Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $certificate_array as $k => $certificate ) {
			if ( ! term_exists( $certificate->certificateName, 'pa_' . $name ) ) {
				wp_insert_term( $certificate->certificateName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $origin_array ) ) {
		$name              = 'gemfind_fancy_origin';
		$label             = 'Origin Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $origin_array as $k => $origin ) {
			if ( ! term_exists( $origin->originName, 'pa_' . $name ) ) {
				wp_insert_term( $origin->originName, 'pa_' . $name );
			}
		}
	}

	if ( ! empty( $video_array ) ) {
		$name              = 'gemfind_fancy_video';
		$label             = 'Video Fancy';
		$attribute_name    = str_replace( " ", "-", strtolower( trim( $name ) ) );
		$attribute_label   = $label;
		$attribute_type    = 'select';
		$attribute_orderby = 'menu_order';
		$attribute_public  = 0;
		$args              = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);
		$attribute_id      = $wpdb->get_var( "SELECT attribute_id
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
        WHERE attribute_name LIKE '$name'" );
		if ( empty( $attribute_id ) ) {
			wc_create_attribute( $args );
		}
		register_taxonomy( 'pa_' . $name, array( 'product' ) );
		foreach ( $video_array as $k => $video ) {
			if ( ! term_exists( $video->hasVideo, 'pa_' . $name ) ) {
				wp_insert_term( $video->hasVideo, 'pa_' . $name );
			}
		}
	}
}
}
$gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
if ( isset( $_POST['woocommerce'] ) || $gemfind_ring_builder['load_from_woocommerce'] == 1 ) {
	$checked = 'checked="checked"';
} else {
	$checked = '';
}
?>
<div class="wrap">
    <h2><?php _e( 'GemFind Ring Builder Options', 'ringbuilder' ); ?></h2>
    <form method="POST" action="" class="rb_admin_settings">
        <table width="100%" border="0" cellspacing="3" cellpadding="5">
            <tr>
                <td width="200">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Dealer ID', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_ring_builder[dealerid]"
                           value="<?php echo $gemfind_ring_builder['dealerid']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <!-- <tr>
                <td><strong><?php _e( 'Password', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="password" name="gemfind_ring_builder[password]" value="" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'From Email Address', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_ring_builder[from_email_address]"
                           value="<?php echo $gemfind_ring_builder['from_email_address']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr> -->
            <tr>
                <td><strong><?php _e( 'Admin Email Address', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_ring_builder[admin_email_address]"
                           value="<?php echo $gemfind_ring_builder['admin_email_address']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Dealer Auth API', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[dealerauthapi]"
                           value="<?php echo $gemfind_ring_builder['dealerauthapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Ring Filters API URL', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[ringfiltersapi]"
                           value="<?php echo $gemfind_ring_builder['ringfiltersapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Mountinglist API URL', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[mountinglistapi]"
                           value="<?php echo $gemfind_ring_builder['mountinglistapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'MountingDetail API URL', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[mountingdetailapi]"
                           value="<?php echo $gemfind_ring_builder['mountingdetailapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Ring Style Settings API URL', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[ringstylesettingsapi]"
                           value="<?php echo $gemfind_ring_builder['ringstylesettingsapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Navigation Api', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[navigationapi]"
                           value="<?php echo $gemfind_ring_builder['navigationapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Filter API', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[filterapi]"
                           value="<?php echo $gemfind_ring_builder['filterapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Filter API Fancy', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[filterapifancy]"
                           value="<?php echo $gemfind_ring_builder['filterapifancy']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Diamond List API', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[diamondlistapi]"
                           value="<?php echo $gemfind_ring_builder['diamondlistapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Diamond List API Fancy', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[diamondlistapifancy]"
                           value="<?php echo $gemfind_ring_builder['diamondlistapifancy']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Diamond Shape API', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[diamondshapeapi]"
                           value="<?php echo $gemfind_ring_builder['diamondshapeapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Diamond Detail API', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[diamonddetailapi]"
                           value="<?php echo $gemfind_ring_builder['diamonddetailapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Style Setting API', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[stylesettingapi]"
                           value="<?php echo $gemfind_ring_builder['stylesettingapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Diamonds Option API', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_ring_builder[diamondsoptionapi]"
                           value="<?php echo $gemfind_ring_builder['diamondsoptionapi']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Hint', 'ringbuilder' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_hint]" id="enable_hint" class="form-control">
                        <option value="true" <?php if ( $gemfind_ring_builder['enable_hint'] == 'true' ) {
							echo 'selected="selected"';
						} ?>>Yes
                        </option>
                        <option value="false" <?php if ( $gemfind_ring_builder['enable_hint'] == 'false' ) {
							echo 'selected="selected"';
						} ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Email Friend', 'ringbuilder' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_email_friend]" id="enable_email_friend"
                            class="form-control">
                        <option value="true" <?php if ( $gemfind_ring_builder['enable_email_friend'] == 'true' ) {
							echo 'selected="selected"';
						} ?>>Yes
                        </option>
                        <option value="false" <?php if ( $gemfind_ring_builder['enable_email_friend'] == 'false' ) {
							echo 'selected="selected"';
						} ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Schedule Viewing', 'ringbuilder' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_schedule_viewing]" id="enable_schedule_viewing"
                            class="form-control">
                        <option value="true" <?php if ( $gemfind_ring_builder['enable_schedule_viewing'] == 'true' ) {
							echo 'selected="selected"';
						} ?>>Yes
                        </option>
                        <option value="false" <?php if ( $gemfind_ring_builder['enable_schedule_viewing'] == 'false' ) {
							echo 'selected="selected"';
						} ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable More Info', 'ringbuilder' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_more_info]" id="enable_more_info" class="form-control">
                        <option value="true" <?php if ( $gemfind_ring_builder['enable_more_info'] == 'true' ) {
							echo 'selected="selected"';
						} ?>>Yes
                        </option>
                        <option value="false" <?php if ( $gemfind_ring_builder['enable_more_info'] == 'false' ) {
							echo 'selected="selected"';
						} ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Print', 'ringbuilder' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_print]" id="enable_print" class="form-control">
                        <option value="true" <?php if ( $gemfind_ring_builder['enable_print'] == 'true' ) {
							echo 'selected="selected"';
						} ?>>Yes
                        </option>
                        <option value="false" <?php if ( $gemfind_ring_builder['enable_print'] == 'false' ) {
							echo 'selected="selected"';
						} ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Vendor Notification', 'diamondlink' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_admin_notify]" id="enable_admin_notify"
                            class="form-control">
                        <option value="true" <?php if ( $gemfind_ring_builder['enable_admin_notify'] == 'true' ) {
							echo 'selected="selected"';

						} ?>>Yes
                        </option>
                        <option value="false" <?php if ( $gemfind_ring_builder['enable_admin_notify'] == 'false' ) {
							echo 'selected="selected"';

						} ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Default view', 'diamondlink' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[default_view]" id="default_view" class="form-control">
                    	<option value="grid" <?php echo ($gemfind_ring_builder['default_view'] == 'grid')?'selected':'' ?>>Grid</option>
                        <option value="list" <?php echo ($gemfind_ring_builder['default_view'] == 'list')?'selected':'' ?>>List</option>                        
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Apply Hints Popup', 'diamondlink' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[show_hints_popup]" id="show_hints_popup" class="form-control">
                        <option value="yes" <?php echo ($gemfind_ring_builder['show_hints_popup'] == 'yes')?'selected':'' ?>>Yes</option>
                        <option value="no" <?php echo ($gemfind_ring_builder['show_hints_popup'] == 'no')?'selected':'' ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Copyright text', 'diamondlink' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[show_copyright]" id="show_copyright" class="form-control">
                        <option value="yes" <?php echo ($gemfind_ring_builder['show_copyright'] == 'yes')?'selected':'' ?>>Yes</option>
                        <option value="no" <?php echo ($gemfind_ring_builder['show_copyright'] == 'no')?'selected':'' ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Sticky Header?', 'diamondlink' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_sticky_header]" id="enable_sticky_header" class="form-control">
                        <option value="yes" <?php echo ($gemfind_ring_builder['enable_sticky_header'] == 'yes')?'selected':'' ?>>Yes</option>
                        <option value="no" <?php echo ($gemfind_ring_builder['enable_sticky_header'] == 'no')?'selected':'' ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Enable Tryon?', 'diamondlink' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[enable_tryon]" id="enable_tryon" class="form-control">
                    	<option value="no" <?php echo ($gemfind_ring_builder['enable_tryon'] == 'no')?'selected':'' ?>>No</option>
                        <option value="yes" <?php echo ($gemfind_ring_builder['enable_tryon'] == 'yes')?'selected':'' ?>>Yes</option>                        
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Shop Logo URL', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_ring_builder[shop_logo]"
                           value="<?php echo $gemfind_ring_builder['shop_logo']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Settings Carat Ranges', 'ringbuilder' ); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_ring_builder[carat_ranges]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['carat_ranges']); ?></textarea> -->

                    <?php wp_editor( stripcslashes($gemfind_ring_builder['carat_ranges']) , 'carat_ranges', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[carat_ranges]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e( 'Google Captcha - Site Key', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_ring_builder[site_key]"
                           value="<?php echo $gemfind_ring_builder['site_key']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Google Captcha - Secret Key', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_ring_builder[secret_key]"
                           value="<?php echo $gemfind_ring_builder['secret_key']; ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Top TextArea', 'ringbuilder' ); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_ring_builder[top_textarea]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['top_textarea']); ?></textarea> -->
                    <?php wp_editor( $gemfind_ring_builder['top_textarea'] , 'top_textarea', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[top_textarea]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Ring Details TextArea', 'ringbuilder' ); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_ring_builder[detail_textarea]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['detail_textarea']); ?></textarea> -->

                    <?php wp_editor( $gemfind_ring_builder['detail_textarea'] , 'detail_textarea', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[detail_textarea]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Ring Meta Title', 'ringbuilder' ); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_ring_builder[ring_meta_title]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['ring_meta_title']); ?></textarea> -->
                    <?php wp_editor( $gemfind_ring_builder['ring_meta_title'] , 'ring_meta_title', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[ring_meta_title]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Ring Meta Keyword', 'ringbuilder' ); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_ring_builder[ring_meta_keywords]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['ring_meta_keywords']); ?></textarea> -->
                    <?php wp_editor($gemfind_ring_builder['ring_meta_keywords'] , 'ring_meta_keywords', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[ring_meta_keywords]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Ring Meta Description', 'ringbuilder' ); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_ring_builder[ring_meta_description]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['ring_meta_description']); ?></textarea> -->
                     <?php wp_editor($gemfind_ring_builder['ring_meta_description'] , 'ring_meta_description', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[ring_meta_description]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Diamond Meta Title', 'ringbuilder' ); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_ring_builder[diamond_meta_title]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['diamond_meta_title']); ?></textarea> -->
                     <?php wp_editor($gemfind_ring_builder['diamond_meta_title'] , 'diamond_meta_title', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[diamond_meta_title]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Diamond Meta Keyword', 'ringbuilder' ); ?></strong></td>
                <td>
                   <!--  <textarea name="gemfind_ring_builder[diamond_meta_keyword]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['diamond_meta_keyword']); ?></textarea> -->
                    <?php wp_editor($gemfind_ring_builder['diamond_meta_keyword'] , 'diamond_meta_keyword', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[diamond_meta_keyword]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>
             <tr>
                <td><strong><?php _e( 'Diamond Meta Description', 'ringbuilder' ); ?></strong></td>
                <td>
                   <!--  <textarea name="gemfind_ring_builder[diamond_meta_description]" class="form-control"><?php //echo stripcslashes($gemfind_ring_builder['diamond_meta_description']); ?></textarea> -->
                     <?php wp_editor($gemfind_ring_builder['diamond_meta_description'] , 'diamond_meta_description', array(
					    'wpautop'       => true,
					    'media_buttons' => false,
					    'textarea_name' => 'gemfind_ring_builder[diamond_meta_description]',
					    'editor_class'  => 'form-control',
					    'textarea_rows' => 10
					) ); ?>
                </td>
            </tr>

			<tr>
                <td><strong><?php _e( 'Currency Symbol Location', 'diamondlink' ); ?></strong></td>
                <td>
                    <select name="gemfind_ring_builder[price_row_format]" id="price_row_format" class="form-control">
                        <option value="right" <?php echo ($gemfind_ring_builder['price_row_format'] == 'right')?'selected':'' ?>>Right</option>
                        <option value="left" <?php echo ($gemfind_ring_builder['price_row_format'] == 'left')?'selected':'' ?>>Left</option>
                    </select>
                </td>
            </tr>
            <!-- <tr>
                <td><strong><?php _e( 'Load Products From Website?', 'ringbuilder' ); ?></strong></td>
                <td>
                    <input type="checkbox" name="woocommerce" id="woocommerce" value="1" class="form-control"
                           maxlength="255" <?php echo $checked; ?>>
                    <label for="woocommerce"></label>
                    <br>
                    <span class="extra-info"><i>By checking this option, the products will load from website rather than Jewel Cloud API.</i></span>
                </td>
            </tr> -->
            <tr class="submit-button">
                <td></td>
                <td><input type="submit" name="settings" id="settings" value="Save"
                           class="button button-primary button-large"></td>
            </tr>
        </table>
    </form>
    <script type="text/javascript">
        jQuery('#woocommerce').on('click', function () {
            if (jQuery('#woocommerce').is(':checked')) {
                jQuery('#woocommerce').attr('checked', true); // Checks it                
            } else {
                jQuery('#woocommerce').attr('checked', false); // Unchecks it
            }
        });
    </script>
    <style type="text/css">
        input[type=checkbox] {height: 0;width: 0;visibility: hidden;}
        label {cursor: pointer;text-indent: -9999px;width: 46px;height: 23px;background: #fbfbfb;border: 1px solid #e8eae9;display: block;border-radius: 100px;position: relative;-moz-box-shadow: 0px 3px 3px 0px #ccc;-webkit-box-shadow: 0px 3px 3px 0px #ccc;box-shadow: 0px 3px 3px 0px #ccc;}
        label:after {content: '';position: absolute;top: 2px;left: 3px;width: 18px;height: 18px;background: #fbfbfb;box-shadow: 0 0 0 1px rgba(0, 0, 0, .1), 0 4px 0 rgba(0, 0, 0, .08);border-radius: 30px;transition: 0.3s;}
        input:checked + label { background: #349644;}
        input:checked + label:after { left: calc(100% - 5px);transform: translateX(-100%);}
        label:active:after {width: 80px;}
        .extra-info { top: 11px; position: relative; padding-bottom: 0; display: block;}
        .submit-button {clear: both;display: block;position: relative;top: 25px;}
        .submit-button .button {width: 120px; /*height: 38px !important;*/font-size: 21px;}
        .rb_admin_settings textarea { min-height: 90px; min-width: 300px;   resize: none; }
    </style>