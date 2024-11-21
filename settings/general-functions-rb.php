<?php
/**
 * Gives the list of ring filters.
 *
 * @return mixed
 */
function getRingFiltersRB() {
	global $wpdb;
	$options = getOptions_rb();	
	if ( $options['load_from_woocommerce'] == '1' ) {
		$loadfilter   = array();
		$loadfilter[] = (object) array( '$id' => 1, 'status' => 1, 'message' => 'Success' );
		$woo_shapes   = get_terms( "pa_gemfind_shape", array( "hide_empty" => 0 ) );
		foreach ( $woo_shapes as $woo_shape ) {
			$shapes_array[] = (object) array( '$id' => '', 'shapeName' => $woo_shape->name, 'shapeImage' => '' );
		}
		//$loadfilter[1][0]['shapes'] = array((object)$woo_shapes);
		$woo_collections = get_terms( "pa_gemfind_ring_collection", array( "hide_empty" => 0 ) );
		foreach ( $woo_collections as $woo_collection ) {
			$term_id             = $woo_collection->term_id;
			$term_data           = get_option( "taxonomy_$term_id" );
			$collections_array[] = (object) array(
				'$id'             => '',
				'collectionName'  => $woo_collection->name,
				'collectionImage' => $term_data['ring_collection_img']
			);
		}
		$woo_metal_types = get_terms( "pa_gemfind_ring_metaltype", array( "hide_empty" => 0 ) );
		foreach ( $woo_metal_types as $woo_metal_type ) {
			$metal_types_array[] = (object) array( '$id' => '', 'metalType' => $woo_metal_type->name );
		}
		$max_flt_price = $wpdb->get_var( "SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='_price' AND b.meta_key = 'product_type' AND b.meta_value = 'gemfind-ring'" );
		$min_flt_price = $wpdb->get_var( "SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='_price' AND b.meta_key = 'product_type' AND b.meta_value = 'gemfind-ring'" );
		if ( $max_flt_price == $min_flt_price ) {
			$min_flt_price = 0;
		}
		$price_values     = array();
		$price_values[0]  = (object) array( '$id' => '', 'maxPrice' => $max_flt_price, 'minPrice' => $min_flt_price );
		$loadfilter[1][0] = (object) array(
			'$id'            => '',
			"collections"    => $collections_array,
			"metalType"      => $metal_types_array,
			"priceRange"     => array( $price_values ),
			"shapes"         => $shapes_array,
			"currencyFrom"   => "USD",
			"currencySymbol" => "US$"
		);

		$results = $loadfilter;
		if ( sizeof( $results ) > 1 && $results[0]->message == 'Success' ) {
			foreach ( $results[1] as $value ) {
				return $value = (array) $value;
			}
		}
	}
	$dealerID = $options['dealerid'];
	// $filterApiRb = $optins
	if ( $dealerID ) {
		$requestUrl = $options['ringfiltersapi'] . 'DealerID=' . $dealerID;
	} else {
		return;
	}	
	//echo $requestUrl;
	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$responce = curl_exec( $curl );
	$results  = (array) json_decode( $responce );	
	if ( sizeof( $results ) > 1 && $results[0]->message == 'Success' ) {
		foreach ( $results[1] as $value ) {
			return $value = (array) $value;
		}
	}

	curl_close( $curl );
}

/**
 * Gives an array of options from the database.
 *
 * @return array
 */
function getOptions_rb() {
	$options = get_option( 'gemfind_ring_builder' );

	return $options;
}

/**
 * For setting general per page parameters of diamond listing.
 */
function getResultsPerPage_rb() {
	return 12;
}

/**
 * For sending request to api calls.
 *
 * @param $requestParam
 *
 * @return mixed
 */
function sendRingRequest( $requestParam ) {	
	$Shape = $MetalType = $Collection = $PriceMin = $PriceMax = $OrderBy = $OrderType = $PageNumber = $PageSize = $settingId = $centerstonemaxcarat = $centerstonemincarat = '';
	if ( $requestParam ) {
		$options  = getOptions_rb();
		$diamondcookie = $_COOKIE['_wp_diamondsetting'];


		if ( $diamondcookie ) {
			$diamondcookie = json_decode( str_replace( '\"', '"', $diamondcookie ), true );
			// echo "<pre>"; print_r($diamondcookie);
			// echo $diamondcookie[0]['centerstonemincarat'];
		}

		//print_r($requestParam);

		$dealerID = $options['dealerid'];
		$DealerID = 'DealerID=' . $dealerID . '&';

		if ( array_key_exists( 'shapes', $requestParam ) ) {
			if ( $requestParam['shapes'] ) {
				$Shape = 'Shape=' . $requestParam['shapes'] . '&';
			}
		}
		if ( array_key_exists( 'ring_metal', $requestParam ) ) {
			if ( $requestParam['ring_metal'] ) {
				$ring_metal = str_replace( ' ', '+', $requestParam['ring_metal'] );
				$MetalType  = 'MetalType=' . $ring_metal . '&';
			}
		}
		if ( array_key_exists( 'ring_collection', $requestParam ) ) {
			if ( $requestParam['ring_collection'] ) {
				$ring_collection = str_replace( ' ', '+', $requestParam['ring_collection'] );
				$Collection      = 'Collection=' . $ring_collection . '&';
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

		if ( array_key_exists( 'sort_by', $requestParam ) ) {
			if ( $requestParam['sort_by'] ) {
				$OrderBy = 'OrderBy=' . $requestParam['sort_by'] . '+' . $requestParam['sort_direction'] . '&';
			}
		}

		if ( array_key_exists( 'page_number', $requestParam ) ) {
			if ( $requestParam['page_number'] ) {
				$PageNumber = 'PageNumber=' . $requestParam['page_number'] . '&';
			}
		}
		if ( array_key_exists( 'page_size', $requestParam ) ) {
			if ( $requestParam['page_size'] ) {
				$PageSize = 'PageSize=' . $requestParam['page_size'] . '&';
			}
		}

		if ( array_key_exists( 'settingId', $requestParam ) ) {
			if ( $requestParam['settingId'] ) {
				$settingId = 'SID=' . $requestParam['settingId'] . '&';
			}
		}

		// if ( $diamondcookie[0]['centerstonemincarat'] ) {
		// 	$centerstonemincarat = 'centerStoneMinCarat=' . $diamondcookie[0]['centerstonemincarat'] . '&';
		// }		

		// if ( $diamondcookie[0]['centerstonemaxcarat'] ) {
		// 	$centerstonemaxcarat = 'centerStoneMaxCarat=' . $diamondcookie[0]['centerstonemaxcarat'] . '&';
		// }
		if (isset($diamondcookie[0]['centerstonemincarat'] )) {
			$centerstonemincarat = 'CenterStoneMinCarat=' . $diamondcookie[0]['centerstonemincarat'] . '&';
		}		

		if ( isset($diamondcookie[0]['centerstonemaxcarat'] )) {
			$centerstonemaxcarat = 'CenterStoneMaxCarat=' . $diamondcookie[0]['centerstonemaxcarat'] . '&';
		}
		// if ( array_key_exists( 'navigationapi', $requestParam ) ) {
		// 	$requestUrl = $options['navigationapi'] . 'DealerID=' . $options['dealerid'];
		// }
		if ( array_key_exists( 'navigationapirb', $requestParam ) ) {
			$requestUrlrb = $options['navigationapirb'] . 'DealerID=' . $options['dealerid'];
		}

		// if ( array_key_exists( 'IsLabSetting', $requestParam ) ) {
		// 	if ( $requestParam['IsLabSetting'] ) {
		// 		$IsLabSetting = 'IsLabSetting=' . $requestParam['IsLabSetting'];
		// 	}
		// }

		if ( $requestParam['Filtermode'] == 'navlabsetting' ) {
					$IsLabSetting = 'IsLabSettingsAvailable=1';
		} else {
					$IsLabSetting = 'IsLabSettingsAvailable=0';
		}


		$query_string = $DealerID . $Shape . $Collection . $MetalType . $PriceMin . $PriceMax . $centerstonemincarat . $centerstonemaxcarat . $settingId . $OrderBy . $PageNumber . $PageSize . $IsLabSetting;
		//$requestUrl   = $options['mountinglistapi'] . $query_string;
		$requestUrlrb   = $options['mountinglistapi'] . $query_string;		
	}	
	$curl = curl_init();
	//echo $requestUrl;
	//echo $requestUrlrb;
	// exit();
	curl_setopt( $curl, CURLOPT_URL, $requestUrlrb );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$responce = curl_exec( $curl );
	$results  = json_decode( $responce );

	if ( curl_errno( $curl ) ) {

		return $returnData = [ 'rings' => [], 'total' => 0 ];
	}
	if ( $results->message != 'Successfull' ) {

		return $returnData = [ 'rings' => [], 'total' => 0 ];
	}
	curl_close( $curl );

	if ( $results->mountingList != "" && $results->count > 0 ) {
		$returnData = [ 'rings' => $results->mountingList, 'total' => $results->count ];
	} else {
		$returnData = [ 'rings' => [], 'total' => 0 ];
	}

	return $returnData;
}

/**
 * Returns all records per page options for ring.
 */
function getAllOptionsRings() {
	return [
		[
			'label' => 'Records Per Page: 12',
			'value' => 12
		],
		[
			'label' => 'Records Per Page: 24',
			'value' => 24
		],
		[
			'label' => 'Records Per Page: 48',
			'value' => 48
		],
		[
			'label' => 'Records Per Page: 99',
			'value' => 99
		]
	];
}

/**
 * Returns style settings for the ring list page.
 */
function getStyleSettingsRB() {
	$options             = getOptions_rb();
	$dealerID            = $options['dealerid'];
	$DealerID            = 'DealerID=' . $dealerID;
	$query_string        = $DealerID;
	$ringstylesettingapi = $options['ringstylesettingsapi'];
	$requestUrl          = $ringstylesettingapi . $query_string . '&ToolName=RB';
	$curl                = curl_init();
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
		$returnData = [ 'settings' => $settings, ];

		return $returnData;
	}
}

/**
 * For creating ring view url in the list view and grid view.
 */
function getRingViewUrlRB( $ringid, $ringname ) {
	$route     = get_site_url() . "/ringbuilder/settings/product/";
	$metaltype = '14k-white-gold-metaltype-';
	$name      = strtolower( str_replace( ' ', '-', $ringname ) );
	$name      = strtolower( str_replace( '&', '%26', $name ) );
	$sku       = '-sku-' . str_replace( ' ', '-', $ringid );

	return $url = getUrlRB( $route, [ 'path' => $metaltype . $name . $sku, '_secure' => true ] );
}

/**
 * @param $route ,$params
 *
 * @return string
 */
function getUrlRB( $route = '', $params = [] ) {
	if ( $params['path'] ) {
		return $route . $params['path'];
	} else {
		return $route . $params['id'];
	}

}

/**
 * @param $requestParam
 *
 * @return array
 */
function sendRequest_rb( $requestParam ) {
	$Shape = $CaratMin = $CaratMax = $PriceMin = $PriceMax = $ColorId = $ClarityId = $CutGradeId = $TableMin = $TableMax = $DepthMin = $DepthMax = $SymmetryId = $PolishId = $FluorescenceId = $Certificate = $OrderBy = $OrderType = $PageNumber = $PageSize = $InHouseOnly = $SOrigin = $query_string = $DID = $FancyColor = $intIntensity = $HasVideo = '';
	if ( $requestParam ) {
		$options  = getOptions_rb();
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
		if ( array_key_exists( 'navigationapirb', $requestParam ) ) {
			$requestUrl = $options['navigationapirb'] . 'DealerID=' . $options['dealerid'];
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
				if ( $requestParam['Filtermode'] == 'navlabsetting' ) {
					$IsLabGrown = '&IsLabGrown=1';
				} else {
					$IsLabGrown = '&IsLabGrown=0';
				}
				$query_string = $DealerID . $Shape . $CaratMin . $CaratMax . $PriceMin . $PriceMax . $ColorId . $ClarityId . $CutGradeId . $TableMin . $TableMax . $DepthMin . $DepthMax . $SymmetryId . $PolishId . $FluorescenceId . $Certificate . $SOrigin . $DID . $OrderBy . $OrderType . $PageNumber . $PageSize . $InHouseOnly . $IsLabGrown;
				$requestUrl   = $options['mountinglistapi'] . $query_string;
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
	} else {
		$returnData = [ 'diamonds' => [], 'total' => 0 ];
	}

	if ( ! array_key_exists( 'navigationapirb', $requestParam ) && $results->diamondList != "" && $results->count > 0 ) {
		$returnData = [ 'diamonds' => $results->diamondList, 'total' => $results->count ];
	} elseif ( array_key_exists( 'navigationapirb', $requestParam ) ) {
		$returnData = $results;
	} else {
		$returnData = [ 'diamonds' => [], 'total' => 0 ];
	}

	return $returnData;
}

/**
 * @return int
 */
function getResultPerPage_rb() {
	return 20;
}

/**
 * Get options for the per page list on grid.
 */
function getAllOptions_rb() {
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
function getDiamondViewUrl_rb( $param, $type, $shopurl, $pathprefixshop ) {
	$route = $shopurl . $pathprefixshop . "/product/";

	return getUrl_rb( $route, [ 'path' => $param, 'type' => $type, '_secure' => true ] );
}

/**
 * @param $route ,$params
 *
 * @return string
 */
function getUrl_rb( $route = '', $params = [] ) {
	if ( $params['path'] ) {
		return $route . $params['path'];
	} else {
		return $route . $params['id'];
	}
}

if ( ! function_exists( 'is_404_rb' ) ) {
	function is_404_rb( $url ) {
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
if ( ! function_exists( 'getDiamondSkuByPath_rb' ) ) {
	function getRingSkuByPath_rb( $path ) {
		$urlstring = $path;
		$urlarray  = explode( '-sku-', $urlstring );

		return $urlarray[1];
	}
}

/**
 * For getting style settings for our shop.
 */
function getStyleSettings_rb() {
	$options         = getOptions_rb();
	$DealerID        = 'DealerID=' . $options['dealerid'];
	$query_string    = $DealerID;
	$stylesettingapi = $options['stylesettingapi'];
	$requestUrl      = $stylesettingapi . $query_string;
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
function getCurrencySymbol_rb() {
	$options         = getOptions_rb();
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
function getProduct_rb() {
	$diamond_path    = rtrim( $_SERVER['REQUEST_URI'], '/' ); //end( explode( '/', $_SERVER['REQUEST_URI'] ) );
	$id              = getRingSkuByPath_rb( $diamond_path );
	$shop            = get_site_url();
	$diamond_product = getRingById_rb( $id, $shop );

	return $diamond_product;
}

/**
 * @param $id
 * @param $shop
 *
 * @return array
 */
function getRingById_rb( $id, $shop ) {	
	$options = getOptions_rb();
	//exit;
	$DealerID     = 'DealerID=' . $options['dealerid'] . '&';
	$DID          = 'SID=' . $id;
	$query_string = $DealerID . $DID;
	$requestUrl   = $options['mountingdetailapi'] . $query_string;
	//echo $requestUrl;
	$curl         = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$response = curl_exec( $curl );
	$results  = json_decode( $response );
	if ( curl_errno( $curl ) ) {
		return $returnData = [ 'ringData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
	}
	if ( isset( $results->message ) ) {
		return $returnData = [ 'ringData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
	}
	curl_close( $curl );
	if ( $results->settingId != "" && $results->settingId > 0 ) {
		$ringData   = (array) $results;
		$returnData = [ 'ringData' => $ringData ];
	} else {
		$returnData = [ 'ringData' => [] ];
	}

	return $returnData;
}

/**
 * For programatically set post thumbnail.
 *
 * @param string $post_id id of the post to set thumbnail to.
 * @param string $image_url url of an image to set as post thumbnail.
 */
function custom_thumbnail_set_rb( $post_id, $image_url, $image_type ) {
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
	}/* elseif ( $image_type == 'gallery_image' ) {
        add_post_meta( $post_id, '_product_image_gallery', $attach_id );
    }*/

	return $attach_id;
}

/**
 * Check if url is returning 404.
 *
 * @param string $url id of the post to set thumbnail to.
 */
function is_url_404_rb( $url ) {		
	if (!$fp = curl_init($url)) return false;
    return true;
}

/**
 * Will authenticate dealer.
 */
function authenticateDealer_rb() {
	$form_data      = $_POST['form_data'];
	$all_options    = getOptions_rb();
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

add_action( 'wp_ajax_nopriv_authenticateDealer_rb', 'authenticateDealer_rb' );
add_action( 'wp_ajax_authenticateDealer_rb', 'authenticateDealer_rb' );



/*
product ring tracking script
*/
function ringTracking() {
	$all_options     = getOptions_rb();

	$final_track_url = $_POST['track_url'];

	$settingdata = json_decode(json_decode(stripslashes($_POST['ring_data'])),true);

	$RetailerID = $VendorID = $GFInventoryID= $URL= $MetalType= $MetalColor= $cost = $UsersIPAddress= ''; 
		
    $RetailerID = 'RetailerID='.($settingdata['ringData']['vendorId'] ? $settingdata['ringData']['vendorId'].'&':'&');
    
    $VendorID = 'VendorID='.($settingdata['ringData']['retailerInfo']['retailerID'] ? $settingdata['ringData']['retailerInfo']['retailerID'].'&':'&');
    
    $GFInventoryID = 'GFInventoryID='.$settingdata['ringData']['settingId'].'&';
    $URL = 'URL='.urlencode($final_track_url).'&';    

    $cost = 'price='.($settingdata['ringData']['cost'] ? $settingdata['ringData']['cost'].'&':'&');

    $MetalType = 'MetalType='.($settingdata['ringData']['metalID'] ? $settingdata['ringData']['metalID'].'&':'&');
    $MetalColor = 'MetalColor='.($settingdata['ringData']['colorID'] ? $settingdata['ringData']['colorID'].'&':'&');

    $UsersIPAddress = 'UsersIPAddress='.getRealIpAddr_dl();
    // echo 'test'; exit(); 

	$posturl = str_replace(' ', '+', 'https://platform.jewelcloud.com/ProductTracking.aspx?'.$RetailerID.$VendorID.$GFInventoryID.$URL.$cost.$MetalType.$MetalColor.$UsersIPAddress);

	echo $posturl;

	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $posturl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$responce = curl_exec( $curl );
	$results  = json_decode( $responce );

	//echo '<pre>'; print_r($responce); exit();
	
	if ( curl_errno( $curl ) ) {
		_e( "error", "gemfind-diamond-link" );
	} else {
		_e( "tracked", "gemfind-diamond-link" );
	}
	curl_close( $curl );

}

add_action( 'wp_ajax_nopriv_ringTracking', 'ringTracking' );
add_action( 'wp_ajax_ringTracking', 'ringTracking' );

/**
 * Gets product id from sku of the product.
 */
function get_product_by_sku_rb( $sku ) {
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
function woocommerce_quantity_input_min_callback_rb( $min, $product ) {
	$min = 1;

	return $min;
}

add_filter( 'woocommerce_quantity_input_min', 'woocommerce_quantity_input_min_callback_rb', 10, 2 );
/*
* Changing the maximum quantity to 5 for all the WooCommerce products
*/
function woocommerce_quantity_input_max_callback_rb( $max, $product ) {
	$max = 1;

	return $max;
}

add_filter( 'woocommerce_quantity_input_max', 'woocommerce_quantity_input_max_callback_rb', 10, 2 );
function wc_qty_add_product_field_rb() {
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

add_action( 'woocommerce_product_options_inventory_product_data', 'wc_qty_add_product_field_rb' );
/*
* This function will save the value set to Minimum Quantity and Maximum Quantity options
* into _wc_min_qty_product and _wc_max_qty_product meta keys respectively
*/
function wc_qty_save_product_field_rb( $post_id ) {
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

add_action( 'woocommerce_process_product_meta', 'wc_qty_save_product_field_rb' );
/*
* Setting minimum and maximum for quantity input args. 
*/
function wc_qty_input_args_rb( $args, $product ) {

	$product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();

	$product_min = wc_get_product_min_limit_rb( $product_id );
	$product_max = wc_get_product_max_limit_rb( $product_id );
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

add_filter( 'woocommerce_quantity_input_args', 'wc_qty_input_args_rb', 10, 2 );
function wc_get_product_max_limit_rb( $product_id ) {
	$qty = get_post_meta( $product_id, '_wc_max_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}

	return $limit;
}

function wc_get_product_min_limit_rb( $product_id ) {
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
function wc_qty_add_to_cart_validation_rb( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {
	$product_min = wc_get_product_min_limit_rb( $product_id );
	$product_max = wc_get_product_max_limit_rb( $product_id );
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
	$already_in_cart = wc_qty_get_cart_qty_rb( $product_id );
	$product         = wc_get_product( $product_id );
	$product_title   = $product->get_title();

	if ( ! is_null( $new_max ) && ! empty( $already_in_cart ) ) {
		if ( ( $already_in_cart + $quantity ) > $new_max ) {
			// oops. too much.
			/*$passed = false;
			wc_add_notice( apply_filters( 'isa_wc_max_qty_error_message_already_had', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.', 'woocommerce-max-quantity' ),
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

add_filter( 'woocommerce_add_to_cart_validation', 'wc_qty_add_to_cart_validation_rb', 1, 5 );
/*
* Get the total quantity of the product available in the cart.
*/
function wc_qty_get_cart_qty_rb( $product_id, $cart_item_key = '' ) {
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
function wc_qty_update_cart_validation_rb( $passed, $cart_item_key, $values, $quantity ) {
	$product_min = wc_get_product_min_limit_rb( $values['product_id'] );
	$product_max = wc_get_product_max_limit_rb( $values['product_id'] );
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
	$already_in_cart = wc_qty_get_cart_qty_rb( $values['product_id'], $cart_item_key );
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

add_filter( 'woocommerce_update_cart_validation', 'wc_qty_update_cart_validation_rb', 1, 4 );
/* Pramod Code */
function getSelectedRing() {
	$ringcookie = $_COOKIE['_wp_ringsetting'];
	if ( $ringcookie ) {
		$shopUrl                  = get_site_url();
		$ringcookie               = json_decode( str_replace( '\"', '"', $ringcookie ), true );
		$ringdata                 = getRingById_rb( $ringcookie[0]['setting_id'], $shopUrl );
		$ringdata['selectedData'] = $ringcookie[0];

		return $ringdata;
	}

	return;
}

function getSelectedDiamond() {
	// $diamondcookie['settingid'] = '157958499';
	// $shopUrl = get_site_url();
	// return getDiamondById_dl($diamondcookie['setting_id'],$shopUrl);
	$diamondcookie = $_COOKIE['_wp_diamondsetting'];


	$shopUrl       = get_site_url();
	if ( $diamondcookie ) {
		$diamondcookie = json_decode( str_replace( '\"', '"', $diamondcookie ), true );
		$type = '';
		if($diamondcookie[0]['islabcreated']!=''){
			$type = 'labcreated';
		}		
		return getDiamondById_dl( $diamondcookie[0]['diamondid'], $type, $shopUrl );
	}
	return;
}

/**
 * @return mixed
*/
function getCenterstone($metaltype,$sidestone,$data){
    $dataarraywithoutsidestone = array();
    if($sidestone == null){
        foreach ($data as $value) {
            $value = (array) $value;
            $dataarraywithoutsidestone[$value['metalType']][] = array('gfInventoryId' => $value['gfInventoryId'], 'centerStoneSize' => $value['centerStoneSize'],);
        }
        usort($dataarraywithoutsidestone[$metaltype], function($a, $b) {if ($a['centerStoneSize'] == $b['centerStoneSize']) {return 0; } return ($a['centerStoneSize'] < $b['centerStoneSize']) ? -1 : 1;}); 
        return $dataarraywithoutsidestone[$metaltype];
    } else {
        foreach ($data as $value) {
            $value = (array) $value;
            $dataarraywithoutsidestone[$value['metalType']][$value['sideStoneQuality']][] = array('gfInventoryId' => $value['gfInventoryId'], 'centerStoneSize' => $value['centerStoneSize'],);
        }
        usort($dataarraywithoutsidestone[$metaltype][$sidestone[0]], function($a, $b) {if ($a['centerStoneSize'] == $b['centerStoneSize']) {return 0; } return ($a['centerStoneSize'] < $b['centerStoneSize']) ? -1 : 1;});        
        return $dataarraywithoutsidestone[$metaltype][$sidestone[0]];               
    }
}

/**
 * @return mixed
*/
function getSidestone($metaltype,$data){
   foreach ($data as $value) {
            $value = (array) $value;
            $dataarraywithoutsidestone[$value['metalType']][$value['sideStoneQuality']][] = array('gfInventoryId' => $value['gfInventoryId'], 'sideStoneQuality' => $value['sideStoneQuality'], 'centerStoneSize' => $value['centerStoneSize'],);
        }   
    return $dataarraywithoutsidestone[$metaltype];  
}

/**
 * @return mixed
*/
function getSidestonefinal($sidestone,$data)
{   
    $keys = array_column($data[$sidestone], 'centerStoneSize');

    array_multisort($keys, SORT_ASC, $data[$sidestone]);

    return array('gfInventoryId' => $data[$sidestone][0]['gfInventoryId'], 'sideStoneQuality' => $data[$sidestone][0]['sideStoneQuality'] );  
}

/**
 * @return mixed
*/
function getMetaltype($metaltype,$data){
   foreach ($data as $value) {
            $value = (array) $value;
            $dataarraymetaltype[$value['centerStoneSize']][$value['metalType']][] = array('gfInventoryId' => $value['gfInventoryId'], 'centerStoneSize' => $value['centerStoneSize'],);
        } 
    ksort($dataarraymetaltype);
    foreach ($dataarraymetaltype as $finkey =>$fiinavalue) {
        foreach ($fiinavalue as $key => $value) {
            $finalmetaldata[$key][] = array('center' => $finkey, 'gfid' => $value[0]['gfInventoryId']);
        }
      }
    foreach ($finalmetaldata as $finalkey => $finalvalue) {
          $finaldata[] = array('metaltype' => $finalkey, 'gfid' => $finalvalue[0]['gfid']);
      }  
    return $finaldata; 
}

/***************************************************************/
include( 'emails/ringemail_friend_email_template_admin.php' );
include( 'emails/ringemail_friend_email_template_receiver.php' );
include( 'emails/ringemail_friend_email_template_sender.php' );
include( 'emails/ringhint_email_template_receiver.php' );
include( 'emails/ringhint_email_template_retailer.php' );
include( 'emails/ringhint_email_template_sender.php' );
include( 'emails/ringinfo_email_template_admin.php' );
include( 'emails/ringinfo_email_template_sender.php' );
include( 'emails/ringschedule_view_email_template_admin.php' );
include( 'emails/ringschedule_view_email_template_user.php' );
include( 'emails/ringinfo_email_template_admin_complete_ring.php' );
include( 'emails/ringinfo_email_template_sender_complete_ring.php' );
include( 'emails/diamondinfo_email_template_retailer.php' );
include( 'emails/diamondinfo_email_template_sender.php' );
include( 'emails/ringschedule_view_email_template_complete_ring_admin.php' );
include( 'emails/ringschedule_view_email_template_complete_ring_user.php' );
include( 'emails/diamondhint_email_template_sender.php' );
include( 'emails/diamondhint_email_template_receiver.php' );
include( 'emails/diamondhint_email_template_retailer.php' );
include( 'emails/diamondemail_friend_email_template_sender.php' );
include( 'emails/diamondemail_friend_email_template_receiver.php' );
include( 'emails/diamondemail_friend_email_template_retailer.php' );
include( 'emails/diamondschedule_view_email_template_admin.php' );
include( 'emails/diamondschedule_view_email_template_user.php' );

?>