<?php
/**
 * @param $requestParam
 *
 * @return array
 */
function sendRequest_dl( $requestParam ) {
	$Shape = $CaratMin = $CaratMax = $PriceMin = $PriceMax = $ColorId = $ClarityId = $CutGradeId = $TableMin = $TableMax = $DepthMin = $DepthMax = $SymmetryId = $PolishId = $FluorescenceId = $Certificate = $OrderBy = $OrderType = $PageNumber = $PageSize = $InHouseOnly = $SOrigin = $query_string = $DID = $FancyColor = $intIntensity = $HasVideo = '';

	//print_r($requestParam);
	if ( $requestParam ) {
		$options  = getOptions_dl();
		$DealerID = 'DealerID=' . $options['dealerid'] . '&';
		if ( array_key_exists( 'shapes', $requestParam ) ) {
			if ( $requestParam['shapes'] ) {
				$Shape = 'Shape=' . $requestParam['shapes'] . '&';
			}
		}
		if ( array_key_exists( 'size_from', $requestParam ) ) {
			if ( $requestParam['size_from'] ) {
				$CaratMin = 'CaratMin=' . $requestParam['size_from'] . '&';
			}
		}
		if ( array_key_exists( 'size_to', $requestParam ) ) {
			if ( $requestParam['size_to'] ) {
				$CaratMax = 'CaratMax=' . $requestParam['size_to'] . '&';
			}
		}
		if ( array_key_exists( 'price_from', $requestParam ) ) {
			if ( $requestParam['price_from'] ) {
				$PriceMin = 'PriceMin=' . $requestParam['price_from'] . '&';
			} else {
				$PriceMin = 'PriceMin=0&';
			}
		}
		if ( array_key_exists( 'price_to', $requestParam ) ) {
			if ( $requestParam['price_to'] ) {
				$PriceMax = 'PriceMax=' . $requestParam['price_to'] . '&';
			}
		}
		if ( array_key_exists( 'depth_percent_from', $requestParam ) ) {
			if ( $requestParam['depth_percent_from'] ) {
				$DepthMin = 'DepthMin=' . $requestParam['depth_percent_from'] . '&';
			} else {
				$DepthMin = 'DepthMin=0&';
			}
		}
		if ( array_key_exists( 'depth_percent_to', $requestParam ) ) {
			if ( $requestParam['depth_percent_to'] ) {
				$DepthMax = 'DepthMax=' . $requestParam['depth_percent_to'] . '&';
			}
		}
		if ( array_key_exists( 'diamond_table_from', $requestParam ) ) {
			if ( $requestParam['diamond_table_from'] ) {
				$TableMin = 'TableMin=' . $requestParam['diamond_table_from'] . '&';
			} else {
				$TableMin = 'TableMin=0&';
			}
		}
		if ( array_key_exists( 'diamond_table_to', $requestParam ) ) {
			if ( $requestParam['diamond_table_to'] ) {
				$TableMax = 'TableMax=' . $requestParam['diamond_table_to'] . '&';
			}
		}
		if ( array_key_exists( 'color', $requestParam ) ) {
			if ( $requestParam['color'] ) {
				$ColorId = 'ColorId=' . $requestParam['color'] . '&';
			}
		}
		if ( array_key_exists( 'clarity', $requestParam ) ) {
			if ( $requestParam['clarity'] ) {
				$ClarityId = 'ClarityId=' . $requestParam['clarity'] . '&';
			}
		}
		if ( array_key_exists( 'cut', $requestParam ) ) {
			if ( $requestParam['cut'] ) {
				$CutGradeId = 'CutGradeId=' . $requestParam['cut'] . '&';
			}
		}
		if ( array_key_exists( 'symmetry', $requestParam ) ) {
			if ( $requestParam['symmetry'] ) {
				$SymmetryId = 'SymmetryId=' . $requestParam['symmetry'] . '&';
			}
		}
		if ( array_key_exists( 'polish', $requestParam ) ) {
			if ( $requestParam['polish'] ) {
				$PolishId = 'PolishId=' . $requestParam['polish'] . '&';
			}
		}
		if ( array_key_exists( 'fluorescence_intensities', $requestParam ) ) {
			if ( $requestParam['fluorescence_intensities'] ) {
				$FluorescenceId = 'FluorescenceId=' . $requestParam['fluorescence_intensities'] . '&';
			}
		}
		if ( array_key_exists( 'labs', $requestParam ) ) {
			if ( $requestParam['labs'] ) {
				$Certificate = 'Certificate=' . $requestParam['labs'] . '&';
			}
		}
		if ( array_key_exists( 'sort_by', $requestParam ) ) {
			if ( $requestParam['sort_by'] ) {
				$OrderBy = 'OrderBy=' . $requestParam['sort_by'] . '&';
			}
			if ( $requestParam['sort_by'] == 'Inhouse' ) {
				$OrderBy .= 'ShowInhouseFirst=true&';
			}
		}
		if ( array_key_exists( 'sort_direction', $requestParam ) ) {
			if ( $requestParam['sort_direction'] ) {
				$OrderType = 'OrderType=' . $requestParam['sort_direction'] . '&';
			}
		}
		if ( array_key_exists( 'page_number', $requestParam ) ) {
			if ( $requestParam['page_number'] ) {
				$PageNumber = 'PageNumber=' . $requestParam['page_number'] . '&';
			}
		}
		if ( array_key_exists( 'page_size', $requestParam ) ) {
			if ( $requestParam['page_size'] ) {
				$PageSize = 'PageSize=' . $requestParam['page_size'];
			}
		}
		if ( array_key_exists( 'InHouseOnly', $requestParam ) ) {
			if ( $requestParam['InHouseOnly'] ) {
				$InHouseOnly = '&InHouseOnly=' . $requestParam['InHouseOnly'];
			}
		}
		if ( array_key_exists( 'origin', $requestParam ) ) {
			if ( $requestParam['origin'] ) {
				$SOrigin = '&SOrigin=' . $requestParam['origin'] . '&';
			}
		}
		if ( array_key_exists( 'did', $requestParam ) ) {
			if ( $requestParam['did'] ) {
				$DID = 'DID=' . $requestParam['did'] . '&';
			}
		}
		if ( array_key_exists( 'hasvideo', $requestParam ) ) {
			if ( $requestParam['hasvideo'] ) {
				$HasVideo = 'HasVideo=' . $requestParam['hasvideo'] . '&';
			}
		}
		if ( array_key_exists( 'navigationapi', $requestParam ) ) {
			$requestUrl = $options['navigationapi'] . 'DealerID=' . $options['dealerid'];
		}
		if ( array_key_exists( 'diamondsoptionapi', $requestParam ) ) {
			$requestUrl = $options['diamondsoptionapi'] . 'DealerID=' . $options['dealerid'];
		}
		if ( array_key_exists( 'Filtermode', $requestParam ) ) {
			if ( $requestParam['Filtermode'] != 'navstandard' && $requestParam['Filtermode'] != 'navlabgrown' ) {
				if ( array_key_exists( 'FancyColor', $requestParam ) ) {
					if ( $requestParam['FancyColor'] ) {
						$FancyColor = 'FancyColor=' . $requestParam['FancyColor'] . '&';
					}
				}
				if ( array_key_exists( 'intIntensity', $requestParam ) ) {
					if ( $requestParam['intIntensity'] ) {
						$requestParam['intIntensity'] = str_replace( ' ', '+', $requestParam['intIntensity'] );
						$intIntensity                 = 'intIntensity=' . $requestParam['intIntensity'] . '&';
					}
				}
				$IsLabGrown   = '&IsLabGrown=false';
				$query_string = $DealerID . $Shape . $CaratMin . $CaratMax . $PriceMin . $PriceMax . $ClarityId . $CutGradeId . $TableMin . $TableMax . $DepthMin . $DepthMax . $SymmetryId . $PolishId . $FluorescenceId . $FancyColor . $intIntensity . $Certificate . $SOrigin . $DID . $OrderBy . $OrderType . $PageNumber . $PageSize . $InHouseOnly . $IsLabGrown;
				$requestUrl   = $options['diamondlistapifancy'] . $query_string;
			} else {
				if ( $requestParam['Filtermode'] == 'navlabgrown' ) {
					$IsLabGrown = '&IsLabGrown=true';
				} else {
					$IsLabGrown = '&IsLabGrown=false';
				}
				$query_string = $DealerID . $Shape . $CaratMin . $CaratMax . $PriceMin . $PriceMax . $ColorId . $ClarityId . $CutGradeId . $TableMin . $TableMax . $DepthMin . $DepthMax . $SymmetryId . $PolishId . $FluorescenceId . $Certificate . $SOrigin . $DID . $OrderBy . $OrderType . $PageNumber . $PageSize . $InHouseOnly . $IsLabGrown;
				$requestUrl   = $options['diamondlistapi'] . $query_string;
			}
		}
	}
	//echo $requestUrl; 
	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$responce = curl_exec( $curl );
	$results  = json_decode( $responce );
	if ( curl_errno( $curl ) ) {
		return $returnData = [ 'diamonds' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
	}
	if ( isset( $results->message ) ) {
		return $returnData = [ 'diamonds' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
	}
	curl_close( $curl );
	if ( ! array_key_exists( 'navigationapi', $requestParam ) && $results->diamondList != "" && $results->count > 0 ) {
		$returnData = [ 'diamonds' => $results->diamondList, 'total' => $results->count ];
	} elseif ( array_key_exists( 'navigationapi', $requestParam ) ) {
		$returnData = $results;
	} elseif ( array_key_exists( 'diamondsoptionapi', $requestParam ) ) {
		$returnData = $results;
	} else {
		$returnData = [ 'diamonds' => [], 'total' => 0 ];
	}

	return $returnData;
}

/**
 * @return int
 */
function getResultPerPage_dl() {
	return 20;
}

/**
 * @param $shop
 *
 * @return int
 */
function getOptions_dl() {
	$options = get_option( 'gemfind_ring_builder' );
	return $options;
}

/**
 * Get options for the per page list on grid.
 */
function getAllOptions_dl() {
	return [
		[
			'label' => 20,
			'value' => 20
		],
		[
			'label' => 50,
			'value' => 50
		],
		[
			'label' => 100,
			'value' => 100
		]
	];
}

/**
 * @param $param ,$type,$shopurl,$pathprefixshop
 *
 * @return string
 */
function getDiamondViewUrl_dl( $param, $type, $shopurl, $pathprefixshop ) {
	$route = $shopurl . $pathprefixshop . "/product/";

	return getUrl_dl( $route, [ 'path' => $param, 'type' => $type, '_secure' => true ] );
}

/**
 * @param $route ,$params
 *
 * @return string
 */
function getUrl_dl( $route = '', $params = [] ) {
	if ( $params['path'] ) {
		return $route . $params['path'];
	} else {
		return $route . $params['id'];
	}
}

if ( ! function_exists( 'is_404_dl' ) ) {
	function is_404_dl( $url ) {
		$handle = curl_init( $url );
		curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec( $handle );
		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
		curl_close( $handle );
		/* If the document has loaded successfully without any redirection or error */
		if ( $httpCode >= 200 && $httpCode < 300 ) {
			return false;
		} else {
			return true;
		}
	}
}
if ( ! function_exists( 'getDiamondSkuByPath_dl' ) ) {
	function getDiamondSkuByPath_dl( $path ) {
		$urlstring = $path;
		$temp = explode( '/', $urlstring );		
		if( end($temp) == 'labcreated' ) {					
			$urlstring = rtrim( $urlstring, '/labcreated' );			
		} elseif( end($temp) == 'fancydiamonds' ) {					
			$urlstring = rtrim( $urlstring, '/fancydiamonds' );			
		}
		$urlarray  = explode( '-sku-', $urlstring );
		return rtrim( $urlarray[1] );
	}
}
/**
 * For getting style settings for our shop.
 */
function getStyleSettings_dl() {
	$options         = getOptions_dl();
	$DealerID        = 'DealerID=' . $options['dealerid'];
	$query_string    = $DealerID;
	$stylesettingapi = $options['stylesettingapi'];
	$requestUrl      = $stylesettingapi . $query_string . '&ToolName=RB';
	$curl            = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$response = curl_exec( $curl );
	$results  = (array) json_decode( $response );
	if ( curl_errno( $curl ) ) {
		return $returnData = [ 'settings' => [], ];
	}
	if ( isset( $results[0][0] ) ) {
		$settings   = (array) $results[0][0];
		$returnData = [ 'settings' => $settings ];

		return $returnData;
	}
}

/**
 * @param $shop
 *
 * @return string
 */
function getCurrencySymbol_dl() {
	$options         = getOptions_dl();
	$DealerID        = 'DealerID=' . $options['dealerid'];
	$query_string    = $DealerID;
	$stylesettingapi = $options['filterapi'];
	$requestUrl      = $stylesettingapi . $query_string;
	$curl            = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$responce = curl_exec( $curl );
	$results  = (array) json_decode( $responce );
	if ( sizeof( $results ) > 1 && $results[0]->message == 'Success' ) {
		foreach ( $results[1] as $value ) {
			$value->currencySymbol = ( $value->currencySymbol == "US$" ) ? "$" : $value->currencySymbol;
			return ( $value->currencyFrom != 'USD' ) ? $value->currencyFrom . $value->currencySymbol : $value->currencySymbol;
		}
	}
	curl_close( $curl );
}

/**
 * @return array|product
 */
function getProduct_dl() {
	$diamond_path    = rtrim( $_SERVER['REQUEST_URI'], '/' ); //end( explode( '/', $_SERVER['REQUEST_URI'] ) );
	$temp = explode( '/', $diamond_path ) ;
	if( end($temp) == 'labcreated' ) {
		$type =  end($temp);
	} elseif( end($temp) == 'fancydiamonds' ) {
		$type =  end($temp);
	} else {
		$type =  '';
	}
	$id              = getDiamondSkuByPath_dl( $diamond_path );
	$shop            = get_site_url();
	$diamond_product = getDiamondById_dl( $id, $type, $shop );

	return $diamond_product;
}

/**
 * @param $id
 * @param $shop
 *
 * @return array
 */
function getDiamondById_dl( $id, $type, $shop ) {
	$IslabGrown = '';
	$IsFancy = '';
    if($type && $type == 'labcreated'){    	
        $IslabGrown = '&IslabGrown=true';    
    } elseif($type && $type == 'fancydiamonds'){    	
        $IsFancy = '&IsFancy=true';    
    } else {
        $IslabGrown = "";
        $IsFancy = "";
    }
	$options      = getOptions_dl();
	$DealerID     = 'DealerID=' . $options['dealerid'] . '&';
	$DID          = 'DID=' . $id;
	$query_string = $DealerID . $DID . $IslabGrown . $IsFancy;
	$requestUrl   = $options['diamonddetailapi'] . $query_string;	
	$curl         = curl_init();
	//echo $requestUrl; //exit();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$response = curl_exec( $curl );
	$results  = json_decode( $response );
	 //echo 'curl';
	 //print_r($results);
	
	if ( curl_errno( $curl ) ) {
		return $returnData = [ 'diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
	}
	if ( isset( $results->message ) ) {
		return $returnData = [ 'diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
	}
	curl_close( $curl );
	//print_r($results->diamondId); 
	if ($results->diamondId) {
		$diamondData = (array) $results;
		//print_r($diamondData);
		$returnData  = [ 'diamondData' => $diamondData ];
		//echo($results->diamondId);
	} else {
		$returnData = [ 'diamondData' => [] ];
	}

	return $returnData;
}

/**
 * For setting general per page parameters of diamond listing.
 */
function getResultsPerPage_dl() {
	return 20;
}

/**
 * For programatically set post thumbnail.
 *
 * @param string $post_id id of the post to set thumbnail to.
 * @param string $image_url url of an image to set as post thumbnail.
 */
function custom_thumbnail_set_dl( $post_id, $image_url, $image_type ) {
	// Add Featured Image to Post
	$image_url        = $image_url;
	$image_name       = end( explode( '/', $image_url ) );
	$upload_dir       = wp_upload_dir(); // Set upload folder
	$image_data       = file_get_contents( $image_url ); // Get image data
	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
	$filename         = basename( $unique_file_name ); // Create image file name
	// Check folder permission and define file location
	if ( wp_mkdir_p( $upload_dir['path'] ) ) {
		$file = $upload_dir['path'] . '/' . $filename;
	} else {
		$file = $upload_dir['basedir'] . '/' . $filename;
	}
	// Create the image  file on the server
	file_put_contents( $file, $image_data );
	// Check image file type
	$wp_filetype = wp_check_filetype( $filename, null );
	// Set attachment data
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => sanitize_file_name( $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);
	// Create the attachment
	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
	// Include image.php
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id, $attach_data );
	// And finally assign featured image to post
	if ( $image_type == 'featured_image' ) {
		set_post_thumbnail( $post_id, $attach_id );
	} elseif ( $image_type == 'gallery_image' ) {
		add_post_meta( $post_id, '_product_image_gallery', $attach_id );
	}
}

/**
 * Check if url is returning 404.
 *
 * @param string $url id of the post to set thumbnail to.
 */
function is_url_404_dl( $url ) {
	$diamond_image = preg_replace( '/\s/', '', $url );
	$handle        = curl_init( $diamond_image );
	curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
	/* Get the HTML or whatever is linked in $url. */
	$response = curl_exec( $handle );
	/* Check for 404 (file not found). */
	$httpCode = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
	curl_close( $handle );

	return $httpCode;
}

/**
 * Will authenticate dealer.
 */
function authenticateDealer_dl() {
	$form_data      = $_POST['form_data'];
	$all_options    = getOptions_dl();
	$auth_post_data = array();
	foreach ( $form_data as $data ) {
		$auth_post_data[ $data['name'] ] = $data['value'];
	}
	$curl = curl_init();
	curl_setopt_array( $curl, array(
		CURLOPT_URL            => $all_options['dealerauthapi'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING       => "",
		CURLOPT_MAXREDIRS      => 10,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST  => "POST",
		CURLOPT_POSTFIELDS     => '{"DealerID": "' . $all_options['dealerid'] . '", "DealerPass": "' . $auth_post_data['password'] . '"}',
		CURLOPT_HTTPHEADER     => array(
			"Content-Type: application/json",
			"cache-control: no-cache"
		),
	) );
	$response = curl_exec( $curl );
	$err      = curl_error( $curl );

	// echo $response;
	curl_close( $curl );
	if ( $err ) {
		$data   = array( 'status' => 0, 'msg' => $err );
		$result = json_encode( array( 'output' => $data ) );
		echo $result;
	} else {
		if ( $response == '"User successfully authenticated."' ) {
			$data   = array( 'status' => 1, 'msg' => 'User successfully authenticated.' );
			$result = json_encode( array( 'output' => $data ) );
			echo $result;
		}
		if ( $response == '"User not authenticated."' ) {
			$data   = array( 'status' => 2, 'msg' => 'User not authenticated.' );
			$result = json_encode( array( 'output' => $data ) );
			echo $result;
		}
		if ( $response == '"User not found!"' ) {
			$data   = array( 'status' => 2, 'msg' => 'User not found!' );
			$result = json_encode( array( 'output' => $data ) );
			echo $result;
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_authenticateDealer_dl', 'authenticateDealer_dl' );
add_action( 'wp_ajax_authenticateDealer_dl', 'authenticateDealer_dl' );
/**
 * Gets product id from sku of the product.
 */
function get_product_by_sku_dl( $sku ) {
	global $wpdb;
	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	if ( $product_id ) {
		return $product_id;
	}

	return null;
}

/*
* Changing the minimum quantity to 2 for all the WooCommerce products
*/
function woocommerce_quantity_input_min_callback_dl( $min, $product ) {
	$min = 1;

	return $min;
}

add_filter( 'woocommerce_quantity_input_min', 'woocommerce_quantity_input_min_callback_dl', 10, 2 );
/*
* Changing the maximum quantity to 5 for all the WooCommerce products
*/
function woocommerce_quantity_input_max_callback_dl( $max, $product ) {
	$max = 1;

	return $max;
}

add_filter( 'woocommerce_quantity_input_max', 'woocommerce_quantity_input_max_callback_dl', 10, 2 );
function wc_qty_add_product_field_dl() {
	echo '<div class="options_group">';
	woocommerce_wp_text_input(
		array(
			'id'          => '_wc_min_qty_product',
			'label'       => __( 'Minimum Quantity', 'woocommerce-max-quantity' ),
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a minimum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity' )
		)
	);
	echo '</div>';
	echo '<div class="options_group">';
	woocommerce_wp_text_input(
		array(
			'id'          => '_wc_max_qty_product',
			'label'       => __( 'Maximum Quantity', 'woocommerce-max-quantity' ),
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a maximum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity' )
		)
	);
	echo '</div>';
}

add_action( 'woocommerce_product_options_inventory_product_data', 'wc_qty_add_product_field_dl' );
/*
* This function will save the value set to Minimum Quantity and Maximum Quantity options
* into _wc_min_qty_product and _wc_max_qty_product meta keys respectively
*/
function wc_qty_save_product_field_dl( $post_id ) {
	$val_min = trim( get_post_meta( $post_id, '_wc_min_qty_product', true ) );
	$new_min = sanitize_text_field( $_POST['_wc_min_qty_product'] );
	$val_max = trim( get_post_meta( $post_id, '_wc_max_qty_product', true ) );
	$new_max = sanitize_text_field( $_POST['_wc_max_qty_product'] );
	if ( $val_min != $new_min ) {
		update_post_meta( $post_id, '_wc_min_qty_product', $new_min );
	}
	if ( $val_max != $new_max ) {
		update_post_meta( $post_id, '_wc_max_qty_product', $new_max );
	}
}

add_action( 'woocommerce_process_product_meta', 'wc_qty_save_product_field_dl' );
/*
* Setting minimum and maximum for quantity input args. 
*/
function wc_qty_input_args_dl( $args, $product ) {
	$product_id  = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	$product_min = wc_get_product_min_limit_dl( $product_id );
	$product_max = wc_get_product_max_limit_dl( $product_id );
	if ( ! empty( $product_min ) ) {
		// min is empty
		if ( false !== $product_min ) {
			$args['min_value'] = $product_min;
		}
	}
	if ( ! empty( $product_max ) ) {
		// max is empty
		if ( false !== $product_max ) {
			$args['max_value'] = $product_max;
		}
	}
	if ( $product->managing_stock() && ! $product->backorders_allowed() ) {
		$stock             = $product->get_stock_quantity();
		$args['max_value'] = min( $stock, $args['max_value'] );
	}

	return $args;
}

add_filter( 'woocommerce_quantity_input_args', 'wc_qty_input_args_dl', 10, 2 );
function wc_get_product_max_limit_dl( $product_id ) {
	$qty = get_post_meta( $product_id, '_wc_max_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}

	return $limit;
}

function wc_get_product_min_limit_dl( $product_id ) {
	$qty = get_post_meta( $product_id, '_wc_min_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}

	return $limit;
}

/*
* Validating the quantity on add to cart action with the quantity of the same product available in the cart. 
*/
function wc_qty_add_to_cart_validation_dl( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {
	$product_min = wc_get_product_min_limit_dl( $product_id );
	$product_max = wc_get_product_max_limit_dl( $product_id );
	if ( ! empty( $product_min ) ) {
		// min is empty
		if ( false !== $product_min ) {
			$new_min = $product_min;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}
	if ( ! empty( $product_max ) ) {
		// min is empty
		if ( false !== $product_max ) {
			$new_max = $product_max;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}
	$already_in_cart = wc_qty_get_cart_qty_dl( $product_id );
	$product         = wc_get_product( $product_id );
	$product_title   = $product->get_title();
	if ( ! is_null( $new_max ) && ! empty( $already_in_cart ) ) {
		if ( ( $already_in_cart + $quantity ) > $new_max ) {
			// oops. too much.
			$passed = false;
			/*wc_add_notice( apply_filters( 'isa_wc_max_qty_error_message_already_had', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.', 'woocommerce-max-quantity' ),
				$new_max,
				$product_title,
				'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>',
				$already_in_cart ),
				$new_max,
				$already_in_cart ),
				'error' );*/
		}
	}

	return $passed;
}

add_filter( 'woocommerce_add_to_cart_validation', 'wc_qty_add_to_cart_validation_dl', 1, 5 );
/*
* Get the total quantity of the product available in the cart.
*/
function wc_qty_get_cart_qty_dl( $product_id, $cart_item_key = '' ) {
	global $woocommerce;
	$running_qty = 0; // iniializing quantity to 0
	// search the cart for the product in and calculate quantity.
	foreach ( $woocommerce->cart->get_cart() as $other_cart_item_keys => $values ) {
		if ( $product_id == $values['product_id'] ) {
			if ( $cart_item_key == $other_cart_item_keys ) {
				continue;
			}
			$running_qty += (int) $values['quantity'];
		}
	}

	return $running_qty;
}

/*
* Validate product quantity when cart is UPDATED
*/
function wc_qty_update_cart_validation_dl( $passed, $cart_item_key, $values, $quantity ) {
	$product_min = wc_get_product_min_limit_dl( $values['product_id'] );
	$product_max = wc_get_product_max_limit_dl( $values['product_id'] );
	if ( ! empty( $product_min ) ) {
		// min is empty
		if ( false !== $product_min ) {
			$new_min = $product_min;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}
	if ( ! empty( $product_max ) ) {
		// min is empty
		if ( false !== $product_max ) {
			$new_max = $product_max;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}
	$product         = wc_get_product( $values['product_id'] );
	$already_in_cart = wc_qty_get_cart_qty_dl( $values['product_id'], $cart_item_key );
	if ( ( $already_in_cart + $quantity ) > $new_max ) {
		/*wc_add_notice( apply_filters( 'wc_qty_error_message', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s.', 'woocommerce-max-quantity' ),
			$new_max,
			$product->get_name(),
			'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>' ),
			$new_max ),
			'error' );
		$passed = false;*/
	}
	if ( ( $already_in_cart + $quantity ) < $new_min ) {
		wc_add_notice( apply_filters( 'wc_qty_error_message', sprintf( __( 'You should have minimum of %1$s %2$s\'s to %3$s.', 'woocommerce-max-quantity' ),
			$new_min,
			$product->get_name(),
			'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>' ),
			$new_min ),
			'error' );
		$passed = false;
	}

	return $passed;
}

add_filter( 'woocommerce_update_cart_validation', 'wc_qty_update_cart_validation_dl', 1, 4 );

/*
product tracking script
*/
function diamondTracking_dl() {
	$diamond         = $_POST['diamond_data'];
	$all_options     = getOptions_dl();
	$final_track_url = $_POST['track_url'];
	$diamonddata     = json_decode( json_decode( stripslashes( $_POST['diamond_data'] ) ), true );


	$RetailerID = $VendorID = $DInventoryID = $URL = $StyleNumber = $DealerStockNumber = $RetailerStockNumber = $Price = $DiamondId = $caratWeight = $cut = $color = $clarity = $depth = $table = $polish = $symmetry = $Girdle = $Culet = $Fluorescence = $Measurements = $Certificate = $CertificateNo = $TableMes = $CutGrade = $SellingPrice = $FltPrice = $UsersIPAddress = '';

	$RetailerID        = 'RetailerID=' . ( $diamonddata['diamondData']['dealerId'] ? $diamonddata['diamondData']['dealerId'] . '&' : '&' );
	$VendorID          = 'VendorID=' . ( $diamonddata['diamondData']['retailerInfo']['retailerID'] ? $diamonddata['diamondData']['retailerInfo']['retailerID'] . '&' : '&' );
	$DInventoryID      = 'DInventoryID=' . $diamonddata['diamondData']['diamondId'] . '&';
	$URL               = 'URL=' . urlencode( $final_track_url ) . '&';
	$DiamondId         = 'DiamondID=' . $diamonddata['diamondData']['diamondId'] . '&';
	$DealerStockNumber = ( $diamonddata['diamondData']['retailerInfo']['retailerStockNo'] ? $diamonddata['diamondData']['retailerInfo']['retailerStockNo'] . '&' : '&' );

	$caratWeight   = ( $diamonddata['diamondData']['caratWeight'] ? $diamonddata['diamondData']['caratWeight'] . '&' : '&' );
	$cut           = ( $diamonddata['diamondData']['shape'] ? $diamonddata['diamondData']['shape'] . '&' : '&' );
	$color         = ( $diamonddata['diamondData']['color'] ? $diamonddata['diamondData']['color'] . '&' : '&' );
	$clarity       = ( $diamonddata['diamondData']['clarity'] ? $diamonddata['diamondData']['clarity'] . '&' : '&' );
	$depth         = ( $diamonddata['diamondData']['depth'] ? $diamonddata['diamondData']['depth'] . '&' : '&' );
	$table         = ( $diamonddata['diamondData']['table'] ? $diamonddata['diamondData']['table'] . '&' : '&' );
	$polish        = ( $diamonddata['diamondData']['polish'] ? $diamonddata['diamondData']['polish'] . '&' : '&' );
	$symmetry      = ( $diamonddata['diamondData']['symmetry'] ? $diamonddata['diamondData']['symmetry'] . '&' : '&' );
	$Girdle        = ( $diamonddata['diamondData']['gridle'] ? $diamonddata['diamondData']['gridle'] . '&' : '&' );
	$Culet         = ( $diamonddata['diamondData']['culet'] ? $diamonddata['diamondData']['culet'] . '&' : '&' );
	$Fluorescence  = ( $diamonddata['diamondData']['fluorescence'] ? $diamonddata['diamondData']['fluorescence'] . '&' : '&' );
	$Measurements  = ( $diamonddata['diamondData']['measurement'] ? $diamonddata['diamondData']['measurement'] . '&' : '&' );
	$Certificate   = ( $diamonddata['diamondData']['certificate'] ? $diamonddata['diamondData']['certificate'] . '&' : '&' );
	$CertificateNo = ( $diamonddata['diamondData']['certificateNo'] ? $diamonddata['diamondData']['certificateNo'] . '&' : '&' );
	$TableMes      = ( $diamonddata['diamondData']['table'] ? $diamonddata['diamondData']['table'] . '&' : '&' );
	$CutGrade      = ( $diamonddata['diamondData']['cut'] ? $diamonddata['diamondData']['cut'] . '&' : '&' );
	$SellingPrice  = ( $diamonddata['diamondData']['fltPrice'] ? $diamonddata['diamondData']['fltPrice'] . '&' : '&' );
	$FltPrice      = ( $diamonddata['diamondData']['fltPrice'] ? $diamonddata['diamondData']['fltPrice'] . '&' : '&' );


	$UsersIPAddress = 'UsersIPAddress=' . getRealIpAddr_dl();

	$posturl = str_replace( ' ', '+', 'https://platform.jewelcloud.com/DiamondTracking.aspx?' . $RetailerID . $VendorID . $DInventoryID . $URL . 'DealerStockNo=' . $DealerStockNumber . 'Carat=' . $caratWeight . 'Cut=' . $cut . 'Color=' . $color . 'Clarity=' . $clarity . 'Depth=' . $depth . 'Polish=' . $polish . 'Symmetry=' . $symmetry . 'FltPrice=' . $FltPrice . 'SellingPrice=' . $SellingPrice . 'Girdle=' . $Girdle . 'Culet=' . $Culet . 'Fluorescence=' . $Fluorescence . 'Measurements=' . $Measurements . 'Certificate=' . $Certificate . 'CertificateNo=' . $CertificateNo . 'TableMes=' . $TableMes . 'CutGrade=' . $CutGrade . $UsersIPAddress );
	//echo $posturl;

	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $posturl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$responce = curl_exec( $curl );
	$results  = json_decode( $responce );

	if ( curl_errno( $curl ) ) {
		_e( "error", "gemfind-diamond-link" );
	} else {
		_e( "tracked", "gemfind-diamond-link" );
	}
	curl_close( $curl );

}

add_action( 'wp_ajax_nopriv_diamondTracking_dl', 'diamondTracking_dl' );
add_action( 'wp_ajax_diamondTracking_dl', 'diamondTracking_dl' );

function getRealIpAddr_dl() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) )   //check ip from share internet
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )   //to check ip is pass from proxy
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}
// function mail_from_address($email)
// {
//     return 'perry@gemfind.com';
// }
// add_filter('wp_mail_from', 'mail_from_address');
// function mail_from_name($name)
// {
//     return 'GemFind';
// }
// add_filter('wp_mail_from_name', 'mail_from_name');

?>