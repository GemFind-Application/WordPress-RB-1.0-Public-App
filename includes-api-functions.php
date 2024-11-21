<?php
/**
 * Common function for api calls based on method GET.
 */
if (!function_exists('getCurlData_dl')) {
	function getCurlData_dl($url, $headers)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
		$response = curl_exec($curl);

		return $results = json_decode($response);
	}
}
/**
 * Common function for api calls based on method POST.
 */
if (!function_exists('postCurlData_dl')) {
	function postCurlData_dl($url, $headers, $data, $method)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 100,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => $method,
			CURLOPT_POSTFIELDS     => $data,
			CURLOPT_HTTPHEADER     => $headers
		));
		$response = curl_exec($curl);

		return $results = json_decode($response);
	}
}

add_action('wp_ajax_nopriv_getDiamondFilters_dl', 'getDiamondFilters_dl');
add_action('wp_ajax_getDiamondFilters_dl', 'getDiamondFilters_dl');


function getDiamondDetails() {
    $Id = $_POST['id'];
    $shop = $_POST['shop'];
    
    // Determine type based on HTTP_REFERER if no type is sent via POST
    if ($_POST['type'] == "NA") {
        if (strpos($_SERVER["HTTP_REFERER"], "labcreated") !== false) {
            $type = 'labcreated';
        } else {
            $type = '';
        }
    } else {
        $type = $_POST['type'];
    }

    $diamond = getDiamondById_dl($Id, $type, $shop);  
    echo json_encode($diamond);
    exit;
}

add_action( 'wp_ajax_nopriv_getDiamondDetails', 'getDiamondDetails' );
add_action( 'wp_ajax_getDiamondDetails', 'getDiamondDetails' );


function removeDiamondrb()
{
	$removeIdrb = $_POST['selectedcheckboxidrb'];
	$cookiseComparerb = $_COOKIE['comparediamondProductrb'];
	$data = json_decode(stripslashes($_COOKIE['comparediamondProductrb']), 1);
	$key = array_search($removeIdrb, array_column($data, 'ID'));
    unset($data[$key]);
    $updatedkeyrb = array_values($data);
    setcookie('comparediamondProductrb', json_encode($updatedkeyrb, true), time() + (86400 * 30), "/");
	$cookiseComparerb = json_decode(stripslashes($_COOKIE['comparediamondProductrb']), 1);
	exit;
}
add_action('wp_ajax_nopriv_removeDiamondrb', 'removeDiamondrb');
add_action('wp_ajax_removeDiamondrb', 'removeDiamondrb');

/**
 * For the first and basic ajax filter on page load.
 */
function getDiamondFilters_dl()
{
	global $wpdb;
	$options   = getOptions_dl();
	// echo '<pre>'; print_r($options); exit; 
	$dealerID  = $options['dealerid'];
	$filterapi = $options['filterapi'];
	$saveinitialvalue 	  = $_POST['saveinitialvalue'];

	$carat_ranges = json_decode(stripslashes($options['carat_ranges']), true);
	$default_shape_filter = $_POST['default_shape_filter'];
	$ringmaxmincaratdata     = json_decode(stripslashes($_COOKIE['_wp_ringsetting']), 1);
	$saveinitialvalue           = json_decode(stripslashes($_POST['saveinitialvalue']), 1);

	if ($_POST['filter_type'] == 'navstandard') {
		$save_filter_cookie_data = json_decode(stripslashes($_COOKIE['wpsavefiltercookie']), 1);
		$back_cookie_data        = json_decode(stripslashes($_COOKIE['wpsavebackvalue']), 1);
		$saveinitialvalue           = json_decode(stripslashes($_POST['saveinitialvalue']), 1);
		$intitialfiltercookie 		= json_decode(stripslashes($_COOKIE['wp_dl_intitialfiltercookierb']), 1);

		if ($save_filter_cookie_data) {
			$savedfilter = (object) $save_filter_cookie_data;
		} elseif ($back_cookie_data) {
			$savedfilter = (object) $back_cookie_data;
		} elseif ($saveinitialvalue) {
			$savedfilter = (object) $saveinitialvalue;
		} else {
			$savedfilter = "";
		}
	} elseif ($_POST['filter_type'] == 'navfancycolored') {
		$save_filter_cookie_data = json_decode(stripslashes($_COOKIE['savefiltercookiefancy']), 1);
		$back_cookie_data        = json_decode(stripslashes($_COOKIE['savebackvaluefancy']), 1);
		$saveinitialvalue           = json_decode(stripslashes($_POST['saveinitialvalue']), 1);
		$intitialfiltercookie 		= json_decode(stripslashes($_COOKIE['wp_dl_intitialfiltercookielabgrownrb']), 1);

		if ($save_filter_cookie_data) {
			$savedfilter = (object) $save_filter_cookie_data;
		} elseif ($back_cookie_data) {
			$savedfilter = (object) $back_cookie_data;
		} elseif ($saveinitialvalue) {
			$savedfilter = (object) $saveinitialvalue;
		} else {
			$savedfilter = "";
		}
	} elseif ($_POST['filter_type'] == 'navlabgrown') {
		$save_filter_cookie_data = json_decode(stripslashes($_COOKIE['savefiltercookielabgrown']), 1);
		$back_cookie_data        = json_decode(stripslashes($_COOKIE['savebackvaluelabgrown']), 1);
		if ($save_filter_cookie_data) {
			$savedfilter = (object) $save_filter_cookie_data;
		} elseif ($back_cookie_data) {
			$savedfilter = (object) $back_cookie_data;
		} elseif ($saveinitialvalue) {
			$savedfilter = (object) $saveinitialvalue;
		} else {
			$savedfilter = "";
		}
	}
	$shapeArray = $symmArray = $polishArray = $fluorArray = $cutArray = $clarityArray = $colorArray = $certiArray = $fancycolorArray = $intintensityArray = $overtoneArray = array();
	if ($default_shape_filter) {
		$shapeArray = array($default_shape_filter);
	}


	if (!empty($saveinitialvalue) && !empty($intitialfiltercookie)) {

		$allshapes = $diamondsinitialfilter[1][0]->shapes;
		if (!empty($allshapes)) {
			$shapeArray = array_column($allshapes, 'shapeName');
			$shapeArray = array_map('nestedLowercase', $shapeArray);
		}

		$allcuts = $diamondsinitialfilter[1][0]->cutRange;
		if (!empty($allcuts)) {
			$cutArray = array_column($allcuts, 'cutId');
		}

		$allcolors = $diamondsinitialfilter[1][0]->colorRange;
		if (!empty($allcolors)) {
			$colorArray = array_column($allcolors, 'colorId');
		}

		$allclarity = $diamondsinitialfilter[1][0]->clarityRange;

		if (!empty($allclarity)) {
			$clarityArray = array_column($allclarity, 'clarityId');
		}

		$allcarat = $diamondsinitialfilter[1][0]->caratRange;
		if (!empty($allcarat)) {
			$savedfilter->caratMin = floor(array_column($allcarat, 'minCarat')[0]);
			$savedfilter->caratMax = floor(array_column($allcarat, 'maxCarat')[0]);
		}

		$allprice  = $diamondsinitialfilter[1][0]->priceRange;
		if (!empty($allprice)) {
			if (!empty(floor(array_column($allprice, 'maxPrice')[0]))) {

				$savedfilter->PriceMin = floor(array_column($allprice, 'minPrice')[0]);
				$savedfilter->PriceMax = floor(array_column($allprice, 'maxPrice')[0]);
			}
		}

		$allpolish = $diamondsinitialfilter[1][0]->polishRange;
		if (!empty($allpolish)) {
		$polishArray = array_column($allpolish, 'polishId');
		}

		$allsymmetry = $diamondsinitialfilter[1][0]->symmetryRange;
		if (!empty($allsymmetry)) {
			$symmArray = array_column($allsymmetry, 'symmetryId');
		}

		$allfluor = $diamondsinitialfilter[1][0]->fluorescenceRange;
		if (!empty($allfluor)) {
			$fluorArray = array_column($allfluor, 'fluorescenceId');
		}

		$alldepth = $diamondsinitialfilter[1][0]->depthRange;

		if (!empty($alldepth)) {
			$savedfilter->depthMin = floor(array_column($alldepth, 'minDepth')[0]);
			$savedfilter->depthMax = floor(array_column($alldepth, 'maxDepth')[0]);
		}

		$alltable  = $diamondsinitialfilter[1][0]->tableRange;
		if (!empty($alltable)) {
			$savedfilter->tableMin = floor(array_column($alltable, 'minTable')[0]);
			$savedfilter->tableMax = floor(array_column($alltable, 'maxTable')[0]);
		}

		$allcerti = $diamondsinitialfilter[1][0]->certificateRange;

		if (!empty($allcerti)) {
			$certiArray = array_column($allcerti, 'certificateName');
		}
	}

	if ($back_cookie_data != "" || $save_filter_cookie_data != "") {

		if (isset($savedfilter->shapeList)) {
			$shapeArray = explode(',', $savedfilter->shapeList);
		}
		if (isset($savedfilter->SymmetryList)) {
			$symmArray = explode(',', $savedfilter->SymmetryList);
		}

		if (isset($back_cookie_data['depthMin']) || isset($back_cookie_data['depthMax'])) {
			$savedfilter->depthMin = $back_cookie_data['depthMin'];
			$savedfilter->depthMax = $back_cookie_data['depthMax'];
		}

		if (isset($save_filter_cookie_data['depthMin']) || isset($save_filter_cookie_data['depthMax'])) {
			$savedfilter->depthMin = $save_filter_cookie_data['depthMin'];
			$savedfilter->depthMax = $save_filter_cookie_data['depthMax'];
		}

		if (isset($back_cookie_data['caratMin']) || isset($back_cookie_data['caratMax'])) {
			$savedfilter->caratMin = $back_cookie_data['caratMin'];
			$savedfilter->caratMax = $back_cookie_data['caratMax'];
		}

		if (isset($save_filter_cookie_data['caratMin']) || isset($save_filter_cookie_data['caratMax'])) {
			$savedfilter->caratMin = $save_filter_cookie_data['caratMin'];
			$savedfilter->caratMax = $save_filter_cookie_data['caratMax'];
		}

		if (isset($back_cookie_data['PriceMin']) || isset($back_cookie_data['PriceMax'])) {
			$savedfilter->PriceMin = $back_cookie_data['PriceMin'];
			$savedfilter->PriceMax = $back_cookie_data['PriceMax'];
		}

		if (isset($save_filter_cookie_data['PriceMin']) || isset($save_filter_cookie_data['PriceMax'])) {
			$savedfilter->PriceMin = $save_filter_cookie_data['PriceMin'];
			$savedfilter->PriceMax = $save_filter_cookie_data['PriceMax'];
		}

		if (isset($back_cookie_data['tableMin']) || isset($back_cookie_data['tableMax'])) {
			$savedfilter->tableMin = $back_cookie_data['tableMin'];
			$savedfilter->tableMax = $back_cookie_data['tableMax'];
		}

		if (isset($save_filter_cookie_data['tableMin']) || isset($save_filter_cookie_data['tableMax'])) {
			$savedfilter->tableMin = $save_filter_cookie_data['tableMin'];
			$savedfilter->tableMax = $save_filter_cookie_data['tableMax'];
		}

		if (isset($savedfilter->polishList)) {
			$polishArray = explode(',', $savedfilter->polishList);
		}
		if (isset($savedfilter->FluorescenceList)) {
			$fluorArray = explode(',', $savedfilter->FluorescenceList);
		}
		if (isset($savedfilter->CutGradeList)) {
			$cutArray = explode(',', $savedfilter->CutGradeList);
		}
		if (isset($savedfilter->ColorList)) {
			$colorArray = explode(',', $savedfilter->ColorList);
		}
		if (isset($savedfilter->ClarityList)) {
			$clarityArray = explode(',', $savedfilter->ClarityList);
		}
		if (isset($savedfilter->FancycolorList)) {
			$fancycolorArray = explode(',', $savedfilter->FancycolorList);
		}
		if (isset($savedfilter->IntintensityList)) {
			$intintensityArray = explode(',', $savedfilter->IntintensityList);
		}
		if (isset($savedfilter->OvertoneList)) {
			$overtoneArray = explode(',', $savedfilter->OvertoneList);
		}
		if (isset($savedfilter->certificate)) {
			$certiArray = explode(',', $savedfilter->certificate);
		}
	}

	if (isset($ringmaxmincaratdata[0]['centerStoneFit'])) {
		$ring_shape = strtolower($ringmaxmincaratdata[0]['centerStoneFit']);
		$shapeArray = explode(',', $ring_shape);
		$shapeArray = array_map('trim', $shapeArray);

		// print_r($shapeArray);
		// exit();
	}
	$filterapifancy = $options['filterapifancy'];
	if ($dealerID) {
		if ($_POST['filter_type'] == 'navstandard') {
			$requestUrl = $filterapi . 'DealerID=' . $dealerID;
		} else if ($_POST['filter_type'] == 'navlabgrown') {
			$requestUrl = $filterapi . 'DealerID=' . $dealerID . '&IsLabGrown=true';
		} else if ($_POST['filter_type'] == 'navfancycolored') {
			$requestUrl = $filterapifancy . 'DealerID=' . $dealerID;
		} else {
			$requestUrl = $filterapi . 'DealerID=' . $dealerID;
		}
	} else {
		return;
	}
	//echo $requestUrl;
	$headers = array("Content-Type:application/json");
	if ($options['load_from_woocommerce'] == '1') {
		if ($_POST['filter_type'] == 'navstandard') {
			$max_carat_weight = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeight' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");
			$max_flt_price    = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='_price' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");
			$max_depth_value  = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depth' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");
			$max_table_value  = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='table' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");

			$min_carat_weight = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeight' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");
			$min_flt_price    = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='_price' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");
			$min_depth_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depth' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");
			$min_table_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='table' AND b.meta_key = 'productType' AND b.meta_value = 'standard'");

			$results      = array();
			$results[0]   = (object) array('message' => 'Success');
			$shapes_array = array();
			$woo_shapes   = get_terms("pa_gemfind_shape", array("hide_empty" => 0));
			foreach ($woo_shapes as $woo_shape) {
				$shapes_array[] = (object) array('$id' => '', 'shapeName' => $woo_shape->name, 'shapeImage' => '');
			}
			$cut_array = array();
			$woo_cuts  = get_terms("pa_gemfind_cut", array("hide_empty" => 0));
			foreach ($woo_cuts as $woo_cut) {
				$cut_array[] = (object) array('$id' => '', 'cutId' => $woo_cut->slug, 'cutName' => $woo_cut->name);
			}
			if ($max_carat_weight == $min_carat_weight) {
				$min_carat_weight = 0;
			}
			$carat_values    = array();
			$carat_values[0] = (object) array('maxCarat' => $max_carat_weight, 'minCarat' => $min_carat_weight);
			if ($max_flt_price == $min_flt_price) {
				$min_flt_price = 0;
			}
			$price_values    = array();
			$price_values[0] = (object) array('maxPrice' => $max_flt_price, 'minPrice' => $min_flt_price);

			$color_array = array();
			$woo_colors  = get_terms("pa_gemfind_color", array("hide_empty" => 0));
			foreach ($woo_colors as $woo_color) {
				$color_array[] = (object) array(
					'$id'       => '',
					'colorId'   => $woo_color->slug,
					'colorName' => $woo_color->name
				);
			}
			$clarity_array = array();
			$woo_clarities = get_terms("pa_gemfind_clarity", array("hide_empty" => 0));
			foreach ($woo_clarities as $woo_clarity) {
				$clarity_array[] = (object) array(
					'$id'         => '',
					'clarityId'   => $woo_clarity->slug,
					'clarityName' => $woo_clarity->name
				);
			}
			$clarity_array = array();
			$woo_clarities = get_terms("pa_gemfind_clarity", array("hide_empty" => 0));
			foreach ($woo_clarities as $woo_clarity) {
				$clarity_array[] = (object) array(
					'$id'         => '',
					'clarityId'   => $woo_clarity->slug,
					'clarityName' => $woo_clarity->name
				);
			}
			if ($max_depth_value == $min_depth_value) {
				$min_depth_value = 0;
			}
			$depth_values    = array();
			$depth_values[0] = (object) array('maxDepth' => $max_depth_value, 'minDepth' => $min_depth_value);
			if ($max_table_value == $min_table_value) {
				$min_table_value = 0;
			}
			$table_values    = array();
			$table_values[0] = (object) array('maxTable' => $max_table_value, 'minTable' => $min_table_value);
			$polish_array    = array();
			$woo_polishes    = get_terms("pa_gemfind_polish", array("hide_empty" => 0));
			foreach ($woo_polishes as $woo_polish) {
				$polish_array[] = (object) array(
					'$id'        => '',
					'polishId'   => $woo_polish->slug,
					'polishName' => $woo_polish->name
				);
			}
			$symmetry_array = array();
			$woo_symmetries = get_terms("pa_gemfind_symmetry", array("hide_empty" => 0));
			foreach ($woo_symmetries as $woo_symmetry) {
				$symmetry_array[] = (object) array(
					'$id'          => '',
					'symmetryId'   => $woo_symmetry->slug,
					'symmteryName' => $woo_symmetry->name
				);
			}
			$fluorescence_array = array();
			$woo_fluorescences  = get_terms("pa_gemfind_fluorescence", array("hide_empty" => 0));
			foreach ($woo_fluorescences as $woo_fluorescence) {
				$fluorescence_array[] = (object) array(
					'$id'              => '',
					'fluorescenceId'   => $woo_fluorescence->slug,
					'fluorescenceName' => $woo_fluorescence->name
				);
			}
			//

			$results[1] = array(
				'0' => (object) array(
					'shapes'            => $shapes_array,
					'cutRange'          => $cut_array,
					'caratRange'        => $carat_values,
					'priceRange'        => $price_values,
					'colorRange'        => $color_array,
					'clarityRange'      => $clarity_array,
					'depthRange'        => $depth_values,
					'tableRange'        => $table_values,
					'polishRange'       => $polish_array,
					'symmetryRange'     => $symmetry_array,
					'fluorescenceRange' => $fluorescence_array,
					'currencyFrom'      => get_woocommerce_currency(),
					'currencySymbol'    => get_woocommerce_currency_symbol()
				)
			);
		} elseif ($_POST['filter_type'] == 'navfancycolored') {
			$max_carat_weight = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeightFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$max_flt_price    = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='FltPriceFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$max_depth_value  = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depthFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$max_table_value  = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='tableFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");

			$min_carat_weight = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeightFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$min_flt_price    = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='FltPriceFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$min_depth_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depthFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$min_table_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='tableFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");

			$results      = array();
			$results[0]   = (object) array('message' => 'Success');
			$shapes_array = array();
			$woo_shapes   = get_terms("pa_gemfind_fancy_shape", array("hide_empty" => 0));
			foreach ($woo_shapes as $woo_shape) {
				$shapes_array[] = (object) array('$id' => '', 'shapeName' => $woo_shape->name, 'shapeImage' => '');
			}
			$cut_array = array();
			$woo_cuts  = get_terms("pa_gemfind_cut", array("hide_empty" => 0));
			foreach ($woo_cuts as $woo_cut) {
				$cut_array[] = (object) array('$id' => '', 'cutId' => $woo_cut->slug, 'cutName' => $woo_cut->name);
			}
			if ($max_carat_weight == $min_carat_weight) {
				$min_carat_weight = 0;
			}
			$carat_values    = array();
			$carat_values[0] = (object) array('maxCarat' => $max_carat_weight, 'minCarat' => $min_carat_weight);
			if ($max_flt_price == $min_flt_price) {
				$min_flt_price = 0;
			}
			$price_values    = array();
			$price_values[0] = (object) array('maxPrice' => $max_flt_price, 'minPrice' => $min_flt_price);
			$color_array     = array();
			$woo_colors      = get_terms("pa_gemfind_fancy_color", array("hide_empty" => 0));

			foreach ($woo_colors as $woo_color) {
				$term_id       = $woo_color->term_id;
				$term_data     = get_option("taxonomy_$term_id");
				$color_array[] = (object) array(
					'$id'                   => '',
					'diamondColorId'        => $woo_color->slug,
					'diamondColorName'      => $woo_color->name,
					'diamondColorImagePath' => $term_data['fancy_color_img']
				);
			}

			$intensity_array = array();
			$woo_intensities = get_terms("pa_gemfind_fancy_intensity", array("hide_empty" => 0));
			foreach ($woo_intensities as $woo_intensity) {
				$intensity_array[] = (object) array('$id' => '', 'intensityName' => $woo_intensity->name);
			}
			$clarity_array = array();
			$woo_clarities = get_terms("pa_gemfind_fancy_clarity", array("hide_empty" => 0));
			foreach ($woo_clarities as $woo_clarity) {
				$clarity_array[] = (object) array(
					'$id'         => '',
					'clarityId'   => $woo_clarity->slug,
					'clarityName' => $woo_clarity->name
				);
			}
			if ($max_depth_value == $min_depth_value) {
				$min_depth_value = 0;
			}
			$depth_values    = array();
			$depth_values[0] = (object) array('maxDepth' => $max_depth_value, 'minDepth' => $min_depth_value);
			if ($max_table_value == $min_table_value) {
				$min_table_value = 0;
			}
			$table_values    = array();
			$table_values[0] = (object) array('maxTable' => $max_table_value, 'minTable' => $min_table_value);
			$polish_array    = array();
			$woo_polishes    = get_terms("pa_gemfind_fancy_polish", array("hide_empty" => 0));
			foreach ($woo_polishes as $woo_polish) {
				$polish_array[] = (object) array(
					'$id'        => '',
					'polishId'   => $woo_polish->slug,
					'polishName' => $woo_polish->name
				);
			}
			$symmetry_array = array();
			$woo_symmetries = get_terms("pa_gemfind_fancy_symmetry", array("hide_empty" => 0));
			foreach ($woo_symmetries as $woo_symmetry) {
				$symmetry_array[] = (object) array(
					'$id'          => '',
					'symmetryId'   => $woo_symmetry->slug,
					'symmteryName' => $woo_symmetry->name
				);
			}
			$fluorescence_array = array();
			$woo_fluorescences  = get_terms("pa_gemfind_fancy_fluorescence", array("hide_empty" => 0));
			foreach ($woo_fluorescences as $woo_fluorescence) {
				$fluorescence_array[] = (object) array(
					'$id'              => '',
					'fluorescenceId'   => $woo_fluorescence->slug,
					'fluorescenceName' => $woo_fluorescence->name
				);
			}
			//
			$results[1] = array(
				'0' => (object) array(
					'shapes'            => $shapes_array,
					'cutRange'          => $cut_array,
					'caratRange'        => $carat_values,
					'priceRange'        => $price_values,
					'diamondColorRange' => $color_array,
					'intensity'         => $intensity_array,
					'clarityRange'      => $clarity_array,
					'depthRange'        => $depth_values,
					'tableRange'        => $table_values,
					'polishRange'       => $polish_array,
					'symmetryRange'     => $symmetry_array,
					'fluorescenceRange' => $fluorescence_array,
					'currencyFrom'      => get_woocommerce_currency(),
					'currencySymbol'    => get_woocommerce_currency_symbol()
				)
			);
		}
	} else {
		$results = getCurlData_dl($requestUrl, $headers);
	}
	if (sizeof($results) > 1 && $results[0]->message == 'Success') {
		foreach ($results[1] as $value) {
			$value = (array) $value;
		}
	}
	unset($curl);
	$back_order_by = ($back_cookie_data['orderBy'] != '') ? $back_cookie_data['orderBy'] : 'Size';
	$back_order_direction = ($back_cookie_data['direction'] != '') ? $back_cookie_data['direction'] : 'ASC';
	$alldata = get_option('gemfind_ring_builder');
	$diamondsoption    = sendRequest_dl(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	$show_Advance_options_as_Default_in_Diamond_Search = $diamondsoption[0][0]->show_Advance_options_as_Default_in_Diamond_Search;

	$show_hints_popup = $alldata['show_hints_popup'];
	$html = '<div class="filter-details 1';
	if ($show_Advance_options_as_Default_in_Diamond_Search != 1) {
		$html .= ' hide-advance-options';
	}
	if(!empty($savedfilter->viewmode)){
		$savedfilter->viewmode = 'grid';
	}
	// echo "<pre>";
	// print_r($results[1][0]->isShowPrice);
	//exit();

	$isShowPrice = $results[1][0]->isShowPrice;
	
	$html .= '">
	 	<input type="hidden" name="inintfilter" id="inintfilter" value="1" />
	 	<input type="hidden" name="shapecookie" id="shapecookie" value='.json_encode($shapeArray,JSON_UNESCAPED_SLASHES).' />
		<input name="viewmode" id="viewmode" type="hidden" value="' . $savedfilter->viewmode . '">
		<input name="itemperpage" id="itemperpage" type="hidden" value="'. $savedfilter->itemperpage.'">
		<input type="hidden" name="orderby" id="orderby" value="' . $back_order_by . '">
		<input type="hidden" name="direction" id="direction" value="' . $back_order_direction . '">
		<input type="hidden" name="currentpage" id="currentpage" value="' . $savedfilter->currentPage . '">
		<input type="hidden" name="did" id="did" value="">
		<input type="hidden" name="backdiamondid" id="backdiamondid" value="' . $back_cookie_data['backdiamondid'] . '">
		<div class="shape-container shape-flex">
			<div class="filter-main filter-alignment-right">
				<div class="filter-for-shape shape-bg">
					<h4>Shape ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'shape'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>
					<ul id="shapeul">';
	foreach ($value['shapes'] as $shape) {
		if ($shapeArray) {
			$selectedActive = (in_array(strtolower($shape->shapeName), $shapeArray)) ? '' : 'unselected';
		} else{
			$selectedActive = (in_array(strtolower($shape->shapeName), $shapeArray)) ? 'selected active' : '';
			$select_checked = (in_array(strtolower($shape->shapeName), $shapeArray)) ? 'checked="checked"' : '';
		}
		if ($shape->shapeName != '') {
			$html           .= '<li class="' . strtolower($shape->shapeName) . ' " title="' . $shape->shapeName . '">
							<div class="shape-type ' . $selectedActive . '">
								<input type="checkbox" class="input-assumpte" id="diamond_shape_' . strtolower($shape->shapeName) . '" name="diamond_shape[]" value="' . strtolower($shape->shapeName) . '" ' . $select_checked . '>
							</div>
							<label for="diamond_shape_' . $shape->shapeName . '">' . $shape->shapeName . '</label>
						</li>';
		}
	}
	// for fancycolored
	if ($_POST['filter_type'] == 'navfancycolored') {
		$html .= '</ul>
							</div>
						</div>';
	}
	if ($_POST['filter_type'] == 'navstandard' || $_POST['filter_type'] == 'navlabgrown') {
		$html .= '</div>
						</div>
						<div class="filter-main filter-alignment-left">
							<div class="filter-for-shape shape-bg">
								<h4>Cut ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'cut'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
							<div class="cut-main">';
	}
	if ($_POST['filter_type'] != 'navfancycolored') {
		$cutName = '';
		$i = 0;
		foreach ($value['cutRange'] as $cut) {
			$i++;
			if (count($value['cutRange']) + 1 != $i) {
				$cutName .= '"' . $cut->cutName . '"' . ',';
			} else {
				$cutName .= '"' . $cut->cutName . '"';
			}
			if (!next($value['cutRange'])) {
				$cutName .= '"Last"';
				$value['cutRange'][]  = (object) ['cutId' => '000', 'cutName' => 'Last'];
			}
		}
		$Cutselected = '';
		$i = 0;
		if (!empty($cutArray)) {
			foreach ($cutArray as $cut) {
				$i++;
				if (count($cutArray) != $i) {
					$Cutselected .= $cut . ',';
				} else {
					$Cutselected .= $cut;
				}

				if ($i == 1) {
					$cutRangedataleft = $cut;
				}
				if (count($cutArray) == $i) {
					$cutRangedataright = (int)$cut + 1;
				}
			}
		}
		$cutRangedataright = $value['cutRange'][count($value['cutRange']) - 2]->cutId + 1;

		// if (empty($Cutselected)) {
  // 			$Cutselected = '1,2,3,4,5';
		// }
?>
		<script type="text/javascript">
			var cutRangeName = '[<?php echo $cutName; ?>]';
		</script>
	<?php
		$html .= '
								<div class="polish-slider right-block ui-slider">
									<input type="hidden" name="diamond_cut[]" id="diamond_cut" class="diamond_cut" value="' . $Cutselected . '" data-cutRangedataleft="1" data-cutRangedataright="2">
					                <div class="price-slider right-block">
					              		<div id="cutRange-slider" data-steps="' . count($value['cutRange']) . '">
						              		<input type="hidden" name="cutRangeleft" id="cutRangeleft" class="cutRangedataleft" value="' . $cutRangedataleft . '">
					    	          		<input type="hidden" name="cutRangeright" id="cutRangeright" class="cutRangedataright" value="' . $cutRangedataright . '">
					              		</div>
					            	</div>
					            </div>
							</div>';
	}
	$html .= '</div>
					</div>
				</div>';
	// $currency = ($value['currencyFrom'] != 'USD') ? $value['currencyFrom'] : '$';

	$currency = $value['currencyFrom'] != 'USD' ? $value['currencyFrom'] . $value['currencySymbol'] : '$' ;

	// echo'<pre>';print_r($value['currencySymbol']); exit; 
	if ($_POST['filter_type'] == 'navfancycolored') {
		$colorClass = "fancy-color";
	}
	$html .= '<div class="color-filter shape-bg ' . $colorClass . '" id="norcolor">
				<h4>Color ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'color'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>';
	if ($_POST['filter_type'] == 'navstandard' || $_POST['filter_type'] == 'navlabgrown') {
		$colorName = '';
		$i = 0;
		foreach ($value['colorRange'] as $color) {
			$i++;
			$colorRangedataleft = $value['colorRange'][0]->colorId;
			if (count($value['colorRange']) + 1 != $i) {
				$colorName .= '"' . $color->colorName . '"' . ',';
			} else {
				$colorName .= '"' . $color->colorName . '"';
				$colorRangedataright = $color->colorId;
			}
			if (!next($value['colorRange'])) {
				$colorName .= '"Last"';
				$value['colorRange'][]  = (object) ['colorId' => '000', 'colorName' => 'Last'];
			}
		}
		$selectedActive = '';
		$i = 0;
		if (!empty($colorArray)) {
			foreach ($colorArray as $color) {
				$i++;
				if (count($colorArray) != $i) {
					$selectedActive .= $color . ',';
				} else {
					$selectedActive .= $color;
				}
				$colorRangedataleft = $colorArray[0];
				if (count($colorArray) == $i) {
					$colorRangedataright = $value['colorRange'][$i]->colorId;
				}
			}
		}
		if (empty($selectedActive)) {
  			$selectedActive = '68,69,70,71,72,73,74,75,76,77,78,79,80';
		}
	?>
		<script type="text/javascript">
			var ColorRangeName = '[<?php echo $colorName; ?>]';
		</script>
	<?php
		$totalColorrange = count($value['colorRange']);
		$colorRangedataright = $value['colorRange'][count($value['colorRange']) - 2]->colorId + 1;
		$html .= '<div class="cut-main">
				<div class="polish-slider right-block ui-slider">
					<div class="price-slider right-block">
	              		<div id="colorRange-slider" data-steps="' . $totalColorrange . '">
	              			<input type="hidden" name="diamond_color[]" id="diamond_color" class="diamond_color" value="' . $selectedActive . '" >
		              		<input type="hidden" name="colorRangeleft" id="colorRangeleft" class="colorRangedataleft" value="' . $value['colorRange'][0]->colorId . '">
	    	          		<input type="hidden" name="colorRangeright" id="colorRangeright" class="colorRangedataright" value="' . $colorRangedataright . '">
	              		</div>
	            	</div>
	            </div>
	            </div>';
	}

	if ($_POST['filter_type'] == 'navfancycolored') {
		$html .= '<div class="cut-main">';
		$diamondColorName = '';
		$fi = 0;
		foreach ($value['diamondColorRange'] as $diamondColor) {
			$fi++;
			if (count($value['diamondColorRange']) + 1 != $fi) {
				$diamondColorName .= '"' . $diamondColor->diamondColorName . '"' . ',';
			} else {
				$diamondColorName .= '"' . $diamondColor->diamondColorName . '"';
			}
			if (!next($value['diamondColorRange'])) {
				$diamondColorName .= '"Last"';
				$value['diamondColorRange'][]  = (object) ['$id' => '000', 'diamondColorId' => 'Last'];
			}
		}
		$diamondColorselected = '';
		$fi = 0;
		if (!empty($fancycolorArray)) {
			foreach ($fancycolorArray as $diamondColor) {
				$fi++;
				if (!empty($diamondColor)) {
					if (count($fancycolorArray) != $fi) {
						$diamondColorselected .= $diamondColor . ',';
					} else {
						$diamondColorselected .= $diamondColor;
					}
				}
				if ($fi == 1 && !empty($diamondColor)) {
					$diamondColorRangedataleft = array_search($diamondColor, array_column($value['diamondColorRange'], 'diamondColorName')) + 1;
				}
				if (count($fancycolorArray) - 1 == $fi) {
					$diamondColorRangedataright = array_search($diamondColor, array_column($value['diamondColorRange'], 'diamondColorName')) + 2;
				}
			}
		}
		if (empty($diamondColorselected)) {
  			$diamondColorselected = 'blue,pink,yellow,champagne,green,grey,purple,chameleon,violet';
		}
	?>
		<script type="text/javascript">
			var diamondColorRangeName = '[<?php echo $diamondColorName; ?>]';
		</script>
		<?php
		if ($diamondColorRangedataright == "") {
			$diamondColorRangedataright = (array)$value['diamondColorRange'][count($value['diamondColorRange']) - 2];
			$diamondColorRangedataright = $diamondColorRangedataright['$id'];
		}
		$html .= '
				<div class="polish-slider right-block ui-slider">
					<div class="diamondColor-slider right-block">
					<input type="hidden" name="diamond_fancycolor[]" id="diamond_fancycolor" class="diamond_fancycolor" value="' . $diamondColorselected . '" data-diamondColorRangedataleft="1" data-diamondColorRangedataright="2">
		            	<div class="diamondColor-slider right-block">
		              		<div id="diamondColorRange-slider" data-steps="' . count($value['diamondColorRange']) . '">
			              		<input type="hidden" name="diamondColorRangeleft" id="diamondColorRangeleft" class="diamondColorRangedataleft" value="' . $diamondColorRangedataleft . '">
		    	          		<input type="hidden" name="diamondColorRangeright" id="diamondColorRangeright" class="diamondColorRangedataright" value="' . $diamondColorRangedataright . '">
		              		</div>
		        		</div>
		    		</div>
		    	</div>
		    	</div>';
	}
	$html .= '</div>';
	if ($_POST['filter_type'] == 'navfancycolored') {
		$html .= '<div class="color-filter fancy-IntIntensity-filter shape-bg">
		<h4>Fancy Intensity';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'fancy_intensity'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
					<div class="cut-main">';
		$intensityName = '';
		$i = 0;

		foreach ($value['intensity'] as $intensity) {
			$i++;
			if (count($value['intensity']) + 1 != $i) {
				$intensityName .= '"' . $intensity->intensityName . '"' . ',';
			} else {
				$intensityName .= '"' . $intensity->intensityName . '"';
			}
			if (!next($value['intensity'])) {
				$intensityName .= '"Last"';
				$value['intensity'][]  = (object) ['$id' => '000', 'intensityName' => 'Last'];
			}
		}
		$intensityselected = '';
		$i = 0;
		if (!empty($intintensityArray)) {
			foreach ($intintensityArray as $intensity) {
				$i++;
				if (!empty($intensity)) {
					if (count($intintensityArray) != $i) {
						$intensityselected .= $intensity . ',';
					} else {
						$intensityselected .= $intensity;
					}
				}
				if ($i == 1 && !empty($intensity)) {
					$intensityRangedataleft = array_search($intensity, array_column($value['intensity'], 'intensityName')) + 1;
				}
				if (count($intintensityArray) - 1 == $i) {
					$intensityRangedataright = array_search($intensity, array_column($value['intensity'], 'intensityName')) + 2;
				}
			}
		}

		if (empty($intensityselected)) {
	  		$intensityselected = 'faint,v.light,light,f.light,fancy intense,intense,fancy vivid,deep,dark';
			}	
		?>
		<script type="text/javascript">
			var intensityRangeName = '[<?php echo $intensityName; ?>]';
		</script>
	<?php
		if ($intensityRangedataright == "") {
			$intensityRangedataright = (array)$value['intensity'][count($value['intensity']) - 2];
			$intensityRangedataright = $intensityRangedataright['$id'];
		}
		$html .= '
					<div class="polish-slider right-block ui-slider">
						<div class="intensity-slider right-block">
						<input type="hidden" name="diamond_intintensity[]" id="diamond_intintensity" class="diamond_intintensity" value="' . $intensityselected . '" data-intensityRangedataleft="1" data-intensityRangedataright="2">
			            	<div class="intensity-slider right-block">
			              		<div id="intensityRange-slider" data-steps="' . count($value['intensity']) . '">
				              		<input type="hidden" name="intensityRangeleft" id="intensityRangeleft" class="intensityRangedataleft" value="' . $intensityRangedataleft . '">
			    	          		<input type="hidden" name="intensityRangeright" id="intensityRangeright" class="intensityRangedataright" value="' . $intensityRangedataright . '">
			              		</div>
			        		</div>
			    		</div>
			    	</div>
				</div>
			</div>';
	}
	$html .= '<div class="color-filter clarity-filter shape-bg">
		<h4>Clarity ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'clarity'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>
				<div class="cut-main">';
	$clarityName = '';
	$i = 0;
	foreach ($value['clarityRange'] as $clarity) {
		$i++;
		if (count($value['clarityRange']) + 1 != $i) {
			$clarityName .= '"' . $clarity->clarityName . '"' . ',';
		} else {
			$clarityName .= '"' . $clarity->clarityName . '"';
		}
		if (!next($value['clarityRange'])) {
			$clarityName .= '"Last"';
			$value['clarityRange'][]  = (object) ['clarityId' => '000', 'clarityName' => 'Last'];
		}
	}

	$clarityselected = '';
	$i = 0;
	if (!empty($clarityArray)) {
		foreach ($clarityArray as $clarity) {
			$i++;
			if (!empty($clarity)) {
				if (count($clarityArray) != $i) {
					$clarityselected .= $clarity . ',';
				} else {
					$clarityselected .= $clarity;
				}
			}
			if ($i == 1 && !empty($clarity)) {
				$clarityRangedataleft = $clarity;
			}
			if (count($clarityArray) == $i && !empty($clarity)) {
				$clarityRangedataright = $clarity;
			}
		}
	}
	if (empty($clarityselected)) {
  		$clarityselected = '1,2,3,4,5,6,7,8,9,10,11';
	}
	?>
	<script type="text/javascript">
		var clarityRangeName = '[<?php echo $clarityName; ?>]';
	</script>
	<?php
	if ($clarityRangedataright == "") {
		$clarityRangedataright = $value['clarityRange'][count($value['clarityRange']) - 2]->clarityId + 1;
	}
	$html .= '<div class="price-slider right-block ui-slider">
							<input type="hidden" name="diamond_clarity[]" id="diamond_clarity" class="diamond_clarity" value="' . $clarityselected . '" data-clarityRangedataleft="1" data-clarityRangedataright="2">
		                	<div class="price-slider right-block">
			              		<div id="clarityRange-slider" data-steps="' . count($value['clarityRange']) . '">
				              		<input type="hidden" name="clarityRangeleft" id="clarityRangeleft" class="clarityRangedataleft" value="' . $clarityRangedataleft . '">
			    	          		<input type="hidden" name="clarityRangeright" id="clarityRangeright" class="clarityRangedataright" value="' . $clarityRangedataright . '">
			              		</div>
		            		</div>
	        		</div>';
	$html .= '</div></div>';
	$carat_from = isset($savedfilter->caratMin) ? $savedfilter->caratMin : $caratminval;
	$carat_to = isset($savedfilter->caratMax) ? $savedfilter->caratMax : $caratmaxval;
	if ($carat_from == "") {
		$carat_from = $caratminval = $value['caratRange'][0]->minCarat;
	}
	if ($carat_to == "") {
		$carat_to = $caratmaxval = $value['caratRange'][0]->maxCarat;
	}
	// new logic for carat range
	if (!empty($carat_ranges)) {
		$ringmincarat_float  = number_format($ringmaxmincaratdata[0]['ringmincarat'], 2);
		$ringmaxcarat_float  = number_format($ringmaxmincaratdata[0]['ringmaxcarat'], 2);
		if (array_key_exists($ringmincarat_float, $carat_ranges)) {
			$caratminval = 	$carat_ranges[$ringmincarat_float][0];
			$caratmaxval = $carat_ranges[$ringmincarat_float][1];

			if (isset($caratminval)) {
				$carat_from = $value['caratRange'][0]->minCarat = $caratminval;
			}
			if (isset($caratmaxval)) {
				$carat_to = $value['caratRange'][0]->maxCarat = $caratmaxval;
			}
		}
		if (isset($ringmaxmincaratdata) && $ringmaxmincaratdata != "") {
			$value['caratRange'][0]->minCarat = $carat_from = $ringmincarat_float;
			$value['caratRange'][0]->maxCarat = $carat_to = $ringmaxcarat_float;
		}
		if ($ringmincarat_float == 0.5 && $ringmaxcarat_float == 4) {
			$value['caratRange'][0]->minCarat = $carat_from = $carat_ranges['[.5ct - 4ct]'][0];
			$value['caratRange'][0]->maxCarat = $carat_to = $carat_ranges['[.5ct - 4ct]'][1];
		}
	}

	if ($carat_from == $carat_to) {
		$carat_from = 0;
	}


	$html .= '<div class="shape-container shape-flex carat-price eq_wrapper">
	<div class="filter-main filter-alignment-right">
		<div class="filter-for-shape shape-bg">
			<h4 class="">Carat ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'carat'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>
			<div class="slider_wrapper">
				<div class="carat-main ui-slider" id="noui_carat_slider" data-min="' . $value['caratRange'][0]->minCarat . '" data-max="' . $value['caratRange'][0]->maxCarat . '">
					<input type="text" class="ui-slider-val slider-left" name="diamond_carats[from]" value="' . $carat_from . '" data-type="min" inputmode="decimal" pattern="[-+]?[0-9]*[.,]?[0-9]+">
					<input type="text" class="ui-slider-val slider-right" name="diamond_carats[to]" value="' . $carat_to . '" data-type="max" inputmode="decimal" pattern="[-+]?[0-9]*[.,]?[0-9]+" >
					<input type="hidden" name="caratto" class="slider-right-val" value="' . $value['caratRange'][0]->maxCarat . '">
				</div>
			</div>
		</div>
	</div>';
	$price_from = isset($savedfilter->PriceMin) ? $savedfilter->PriceMin : $value['priceRange'][0]->minPrice;
	$price_to = isset($savedfilter->PriceMax) ? $savedfilter->PriceMax : $value['priceRange'][0]->maxPrice;
	if ($price_from == $price_to) {
		$price_from = 0;
	}

	if($options['price_row_format'] == 'left'){
		$class_left = 'price-filter_left';
	  }else{
		$class_left = ''; 
	  }
	
	  if($options['price_row_format'] == 'left'){
		$class_right = 'price-filter_right';
	  }else{
		$class_right = ''; 
	  }
	if ($isShowPrice == '1') {
		$html .= '<div class="filter-main filter-alignment-left">
			<div class="filter-for-shape shape-bg">
				<h4 class="">Price ';
				if ($show_hints_popup == 'yes') {
				$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'price'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
				}
				$html .= '</h4>
				<div class="slider_wrapper">
					<div id="noui_price_slider" class="price-main ui-slider" data-min="' . $value['priceRange'][0]->minPrice . '" data-max="' . $value['priceRange'][0]->maxPrice . '"> 
		                <div class="price-left ' . $class_left . '">
		                	<span class="currency-icon">' . $currency  .'</span>
		                	<input class="ui-slider-val slider-left" id="rb_min_price" type="text" data-type="min" name="price[from]" value="' . str_replace(',', '', $price_from) . '"  inputmode="numeric">
		                </div>
		                <div class="price-right ' . $class_right . '">
		                	<span class="currency-icon">' . $currency .'</span>
		                	<input class="ui-slider-val slider-right" id="rb_max_price" type="text" data-type="max" name="price[to]" value="' . str_replace(',', '', $price_to) . '"  pattern="[-+]?[0-9]*[.,]?[0-9]+" inputmode="numeric">
		                </div>
		            </div>
				</div>
			</div>
		</div>
		</div>';
	}

	if ($show_Advance_options_as_Default_in_Diamond_Search == 1) {
		$depthFrom = isset($savedfilter->depthMin) ? $savedfilter->depthMin : floor($value['depthRange'][0]->minDepth);
		$depthTo   = isset($savedfilter->depthMax) ? $savedfilter->depthMax : floor($value['depthRange'][0]->maxDepth);
		$html .= '<div class="filter-advanced shape-bg">
	<button class="accordion">Advanced Search</button>
	<div class="panel cls-for-hide">
		<div class="shape-container shape-flex eq_wrapper">
			<div class="filter-main filter-alignment-left">
				<div class="filter-for-shape shape-bg">
					<h4 class="">Depth ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'depth'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
					<div class="slider_wrapper">
						<div class="depth-main ui-slider" id="noui_depth_slider" data-min="' . floor($value['depthRange'][0]->depthMin) . '" data-max="' . $value['depthRange'][0]->maxDepth . '">
							<div class="depth-left">
								<input type="number" class="ui-slider-val slider-left" name="diamond_depth[from]" value="' . $depthFrom . '" data-type="min" pattern="[\d\.]*">
								<span class="currency-icon">%</span>
							</div>
							<div class="depth-right">
								<input type="number" class="ui-slider-val slider-right" name="diamond_depth[to]" value="' . $depthTo . '" data-type="max" pattern="[\d\.]*">
								<span class="currency-icon">%</span>
								<input type="hidden" name="depthTo" class="slider-right-val" value="' . $value['depthRange'][0]->maxDepth . '"> 
							</div>                     
						</div>
					</div>
				</div>
			</div>
			<div class="filter-main filter-alignment-right">
				<div class="filter-for-shape shape-bg">
					<h4 class="">Table ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'fluorescence'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$tableFrom = isset($savedfilter->tableMin) ? $savedfilter->tableMin : floor($value['tableRange'][0]->minTable);
		$tableTo   = isset($savedfilter->tableMax) ? $savedfilter->tableMax : floor($value['tableRange'][0]->maxTable);
		$html .= '</h4>
					<div class="slider_wrapper">
						<div class="tableper-main ui-slider" id="noui_tableper_slider" data-min="' . floor($value['depthRange'][0]->minTable) . '" data-max="' . $value['tableRange'][0]->maxTable . '">
							<div class="table-left">
								<input type="number" class="ui-slider-val slider-left" name="diamond_table[from]" value="' . $tableFrom . '" data-type="min" pattern="[\d\.]*" >
								<span class="currency-icon">%</span>
							</div>
							<div class="table-right">
								<input type="number" class="ui-slider-val slider-right" name="diamond_table[to]" value="' . $tableTo . '" data-type="max" pattern="[\d\.]*" >
								<span class="currency-icon">%</span>
								<input type="hidden" name="tableTo" class="slider-right-val" value="' . $value['tableRange'][0]->maxTable . '"> 
							</div>                     
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="shape-container shape-flex">
			<div class="filter-advanced-main advance-left">
				<div class="polish-depth">
					<h4>Polish ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'polish'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
					<div class="cut-main">';
		$polishName = '';
		$i = 0;
		//print_r($value['polishRange']);
		foreach ($value['polishRange'] as $polish) {
			$i++;
			if (count($value['polishRange']) + 1 != $i) {
				$polishName .= '"' . $polish->polishName . '"' . ',';
			} else {
				$polishName .= '"' . $polish->polishName . '"';
			}
			if (!next($value['polishRange'])) {
				$polishName .= '"Last"';
				$value['polishRange'][]  = (object) ['polishId' => '000', 'polishName' => 'Last'];
			}
		}
		$polishSelected = '';
		$i = 0;
		if (!empty($polishArray)) {
			foreach ($polishArray as $polish) {
				$i++;
				if (count($polishArray) != $i) {
					$polishSelected .= $polish . ',';
				} else {
					$polishSelected .= $polish;
				}

				if ($i == 1) {
					$polishRangedataleft = $polish;
				}
				if (count($polishArray) == $i) {
					$polishRangedataright = $polish;
				}
			}
		}
	?>
		<script type="text/javascript">
			var polishRangeName = '[<?php echo $polishName; ?>]';
		</script>
		<?php
		$polishRangedataright = $value['polishRange'][count($value['polishRange']) - 2]->polishId + 1;
		$html .= '<div class="polish-slider right-block ui-slider">
								<input type="hidden" name="diamond_polish[]" id="diamond_polish" class="diamond_polish" value="' . $polishSelected . '" data-polishRangedataleft="1" data-polishRangedataright="2">
				                <div class="price-slider right-block">
				              		<div id="polishRange-slider" data-steps="' . count($value['polishRange']) . '">
					              		<input type="hidden" name="polishRangeleft" id="polishRangeleft" class="polishRangedataleft" value="' . $polishRangedataleft . '">
				    	          		<input type="hidden" name="polishRangeright" id="polishRangeright" class="polishRangedataright" value="' . $polishRangedataright . '">
				              		</div>
				            	</div>
				            </div>';
		$html .= '</div>
				</div>            
			</div>
			<div class="filter-advanced-main advance-left">
				<div class="polish-depth filter-Fluoroscence">
					<h4>Fluorescence';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'fluorescence'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
					<div class="cut-main">';
		$fluorName = '';
		$i = 0;
		foreach ($value['fluorescenceRange'] as $fluor) {
			$i++;
			if (count($value['fluorescenceRange']) + 1 != $i) {
				$fluorName .= '"' . $fluor->fluorescenceName . '"' . ',';
			} else {
				$fluorName .= '"' . $fluor->fluorescenceName . '"';
			}
			if (!next($value['fluorescenceRange'])) {
				$fluorName .= '"Last"';
				$value['fluorescenceRange'][]  = (object) ['fluorescenceId' => '000', 'fluorescenceName' => 'Last'];
			}
		}
		$fluorSelected = '';
		$i = 0;
		if (!empty($fluorArray)) {
			foreach ($fluorArray as $fluor) {
				$i++;
				if (count($fluorArray) != $i) {
					$fluorSelected .= $fluor . ',';
				} else {
					$fluorSelected .= $fluor;
				}

				if ($i == 1) {
					$fluorRangedataleft = $fluor;
				}
				if (count($fluorArray) == $i) {
					$fluorRangedataright = $fluor;
				}
			}
		}
		?>
		<script type="text/javascript">
			var fluorRangeName = '[<?php echo $fluorName; ?>]';
		</script>
		<?php
		$fluorRangedataright = $value['fluorescenceRange'][count($value['fluorescenceRange']) - 2]->fluorescenceId + 1;
		$html .= '<div class="polish-slider right-block ui-slider">
								<input type="hidden" name="diamond_fluorescence[]" id="diamond_fluorescence" class="diamond_fluorescence" value="' . $fluorSelected . '" data-fluorRangedataleft="1" data-fluorRangedataright="2">
				                <div class="price-slider right-block">
				              		<div id="fluorRange-slider" data-steps="' . count($value['fluorescenceRange']) . '">
					              		<input type="hidden" name="fluorRangeleft" id="fluorRangeleft" class="fluorRangedataleft" value="' . $fluorRangedataleft . '">
				    	          		<input type="hidden" name="fluorRangeright" id="fluorRangeright" class="fluorRangedataright" value="' . $fluorRangedataright . '">
				              		</div>
				            	</div>
				            </div>
			           	</div>
				</div>
			</div>
			<div class="filter-advanced-main advance-right">
				<div class="polish-depth">
					<h4>Symmetry ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'symmetry'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
					<div class="cut-main">';
		$symmetryName = '';
		$i = 0;
		foreach ($value['symmetryRange'] as $symmetry) {
			$i++;
			if (count($value['symmetryRange']) + 1 != $i) {
				$symmetryName .= '"' . $symmetry->symmteryName . '"' . ',';
			} else {
				$symmetryName .= '"' . $symmetry->symmteryName . '"';
			}
			if (!next($value['symmetryRange'])) {
				$symmetryName .= '"Last"';
				$value['symmetryRange'][]  = (object) ['symmetryId' => '000', 'symmteryName' => 'Last'];
			}
		}
		$symmetrySelected = '';
		$i = 0;
		if (!empty($symmArray)) {
			foreach ($symmArray as $symmetry) {
				$i++;
				if (count($symmArray) != $i) {
					$symmetrySelected .= $symmetry . ',';
				} else {
					$symmetrySelected .= $symmetry;
				}

				if ($i == 1) {
					$symmetryRangedataleft = $symmetry;
				}
				if (count($symmArray) == $i) {
					$symmetryRangedataright = $symmetry;
				}
			}
		}
		?>
		<script type="text/javascript">
			var symmetryRangeName = '[<?php echo $symmetryName; ?>]';
		</script>
	<?php
		$symmetryRangedataright = $value['symmetryRange'][count($value['symmetryRange']) - 2]->symmetryId + 1;
		$html .= '<div class="polish-slider right-block ui-slider">
								<input type="hidden" name="diamond_symmetry[]" id="diamond_symmetry" class="diamond_symmetry" value="' . $symmetrySelected . '" data-symmetryRangedataleft="1" data-symmetryRangedataright="2">
				                <div class="price-slider right-block">
				              		<div id="symmetryRange-slider" data-steps="' . count($value['symmetryRange']) . '">
					              		<input type="hidden" name="symmetryRangeleft" id="symmetryRangeleft" class="symmetryRangedataleft" value="' . $symmetryRangedataleft . '">
				    	          		<input type="hidden" name="symmetryRangeright" id="symmetryRangeright" class="symmetryRangedataright" value="' . $symmetryRangedataright . '">
				              		</div>
				            	</div>
				            </div>';
		$html .= '
					</div>
				</div>            
			</div>
		</div>
		<div class="shape-container shape-flex">
			<div class="filter-advanced-main advance-right">
				<div class="polish-depth advanced-certificate">
					<h4>Certificates</h4>
						<div class="certificate-div">';
		if ($show_Certificate_in_Diamond_Search) {
			$html .= '<select name="diamond_certificates[]" multiple="multiple" id="certi-dropdown" placeholder="Certificates" class="testSelAll SumoUnder" tabindex="-1">';
			foreach ($value['certificateRange'] as $certificate) {
				$selectedActive = (in_array(str_replace(' ', '_', $certificate->certificateName), $certiArray)) ? 'selected="selected"' : '';
				if ($certificate->certificateName != 'Show All Cerificate' && $certificate->certificateName != '') {
					$html .= '<option value="' . $certificate->certificateName . '" class="navstandard_gcal" ' . $selectedActive . '>' . $certificate->certificateName . '</option>';
				}
			}
			$html .= '</select>';
		}
		$html .= '</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	$html .= '</div><script type="text/javascript">	
		jQuery("#certi-dropdown").SumoSelect({
			csvDispCount: 2, 
			okCancelInMulti: false,
			selectAll: true,
			forceCustomRendering: true,
			triggerChangeCombined:false,
			captionFormatAllSelected:"Show All Certificates"
		});
	</script>';
	echo $html;
	die();
	//var_dump($responce);
}

/**
 * Returns list of diamonds with all other parameters i.e. diamonds per page.
 */
add_action('wp_ajax_nopriv_getDiamonds_dl', 'getDiamonds_dl');
add_action('wp_ajax_getDiamonds_dl', 'getDiamonds_dl');
function getDiamonds_dl()
{

	if ($_POST['filter_type'] == 'navstandard') {
		$back_cookie_data        = json_decode(stripslashes($_COOKIE['wpsavebackvalue']), 1);
	} elseif ($_POST['filter_type'] == 'navfancycolored') {
		$back_cookie_data        = json_decode(stripslashes($_COOKIE['savebackvaluefancy']), 1);
	} else {
		$back_cookie_data        = json_decode(stripslashes($_COOKIE['savebackvaluelabgrown']), 1);
	}
	$filter_type                     = $_POST['filter_type'];
	$shop                            = $_POST['shop'];
	$filter_data                     = $_POST['filter_data'];
	$options                         = getOptions_dl();
	$request                         = array();
	$request['diamond_carats']       = array();
	$request['price']                = array();
	$request['diamond_depth']        = array();
	$request['diamond_table']        = array();
	$request['diamond_shape']        = array();
	$request['diamond_cut']          = array();
	$request['diamond_color']        = array();
	$request['diamond_clarity']      = array();
	$request['diamond_polish']       = array();
	$request['diamond_symmetry']     = array();
	$request['diamond_certificates'] = array();
	$request['diamond_fluorescence'] = array();
	$request['diamond_fancycolor']   = array();
	$request['diamond_intintensity'] = array();

	// echo "<pre>";
	//print_r($filter_data[3]['name']);
	$inintfilter = $filter_data[3]['value'];
	// exit();


	foreach ($filter_data as $data) {
		if($inintfilter == '1'){
			if ($data['name'] == 'shapecookie') {
				$shapedata = stripslashes($data['value']);
				$d = json_decode($shapedata,true);
				$request['diamond_shape']= $d;
			}
		}else if($data['name'] == 'diamond_shape[]') {
			$request['diamond_shape'][] = $data['value'];
	    }
			
		
		if ($data['name'] == 'diamond_cut[]') {
			$request['diamond_cut'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_color[]') {
			$request['diamond_color'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_clarity[]') {
			$request['diamond_clarity'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_polish[]') {
			$request['diamond_polish'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_fluorescence[]') {
			$request['diamond_fluorescence'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_symmetry[]') {
			$request['diamond_symmetry'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_certificates[]') {
			$request['diamond_certificates'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_fancycolor[]') {
			$request['diamond_fancycolor'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_intintensity[]') {
			$request['diamond_intintensity'][] = $data['value'];
		}

		$request[$data['name']] = $data['value'];
	}

  
	$request['diamond_carats']['from'] = $request['diamond_carats[from]'];
	$request['diamond_carats']['to']   = $request['diamond_carats[to]'];
	$request['price']['from']          = $request['price[from]'];
	$request['price']['to']            = $request['price[to]'];
	$request['diamond_depth']['from']  = $request['diamond_depth[from]'];
	$request['diamond_depth']['to']    = $request['diamond_depth[to]'];
	$request['diamond_table']['from']  = $request['diamond_table[from]'];
	$request['diamond_table']['to']    = $request['diamond_table[to]'];
	unset($request['diamond_carats[from]']);
	unset($request['diamond_carats[to]']);
	unset($request['price[from]']);
	unset($request['price[to]']);
	unset($request['diamond_depth[from]']);
	unset($request['diamond_depth[to]']);
	unset($request['diamond_table[from]']);
	unset($request['diamond_table[to]']);
	if ($request == null) {
		$diamond = [
			'meta'       => ['code' => 400, 'message' => __('No arguments supplied.')],
			'data'       => [],
			'pagination' => [],
			'perpage'    => getResultPerPage_dl()
		];

		return $diamond;
	}
	if (!is_array($request)) {
		$diamond = [
			'meta'       => ['code' => 400, 'message' => $request],
			'data'       => [],
			'pagination' => [],
			'perpage'    => getResultPerPage_dl()
		];

		return $diamond;
	}
	$shapeValue    = $certificate = $fluorescence = $fancycolor = $colorcontent = $claritycontent =
		$cutcontent = $polishcontent = $symmetrycontent = $fancycolorcontent = $intintensitycontent = [];
	$shapesContent = $symmetrycontentContent = $certificatesContent = $fluorescenceContent =
		$fancycolorContent = $colorcontentContent = $claritycontentContent = $cutcontentContent =
		$polishcontentContent = $symmetrycontentContent = $fancycolorcontentContent =
		$intintensitycontentContent = $itemperpage = '';
	$hasvideo      = 'Yes';
	// Convert the Shapes list into gemfind form
	if (array_key_exists('diamond_shape', $request)) {
		foreach ($request["diamond_shape"] as $value) {
			$shapeValue[] = strtolower($value);
		}
		$shapesContent = implode(',', $shapeValue);
	}
 
	// Convert the Certificate array into gemfind form
	if (array_key_exists('diamond_certificates', $request)) {
		foreach ($request["diamond_certificates"] as $values) {
			$certificate[] = $values;
		}
		$certificatesContent = implode(',', $certificate);
	}
	// Convert the Fluorescence list into gemfind form
	if (array_key_exists('diamond_fluorescence', $request)) {
		foreach ($request["diamond_fluorescence"] as $value) {
			$fluorescence[] = strtolower($value);
		}
		$fluorescenceContent = implode(',', $fluorescence);
	}
	// Convert the color list into gemfind form
	if (array_key_exists('diamond_color', $request)) {
		foreach ($request["diamond_color"] as $value) {
			$colorcontent[] = strtolower($value);
		}
		$colorcontentContent = implode(',', $colorcontent);
	}
	// Convert the clarity list into gemfind form
	if (array_key_exists('diamond_clarity', $request)) {
		foreach ($request["diamond_clarity"] as $value) {
			$claritycontent[] = strtolower($value);
		}
		$claritycontentContent = implode(',', $claritycontent);
	}
	// Convert the Cut list into gemfind form
	if (array_key_exists('diamond_cut', $request)) {
		foreach ($request["diamond_cut"] as $value) {
			$cutcontent[] = strtolower($value);
		}
		$cutcontentContent = implode(',', $cutcontent);
	}
	// Convert the Polish list into gemfind form
	if (array_key_exists('diamond_polish', $request)) {
		foreach ($request["diamond_polish"] as $value) {
			$polishcontent[] = strtolower($value);
		}
		$polishcontentContent = implode(',', $polishcontent);
	}
	// Convert the Symmetry list into gemfind form
	if (array_key_exists('diamond_symmetry', $request)) {
		foreach ($request["diamond_symmetry"] as $value) {
			$symmetrycontent[] = strtolower($value);
		}
		$symmetrycontentContent = implode(',', $symmetrycontent);
	}
	// Convert the DiamondId list into gemfind form
	if (isset($request['did'])) {
		$did = $request['did'];
	} else {
		$did = '';
	}

	// echo '<pre>'; print_r($request['filtermode']); exit();
	// Create the request array to sumbit to gemfind
	$requestData = [
		'shapes'                   => $shapesContent,
		'fluorescence_intensities' => $fluorescenceContent,
		'size_from'                => ($request["diamond_carats"]["from"]) ? $request["diamond_carats"]["from"] : '',
		'size_to'                  => ($request["diamond_carats"]["to"]) ? $request["diamond_carats"]["to"] : '',
		'color'                    => $colorcontentContent,
		'clarity'                  => $claritycontentContent,
		'cut'                      => $cutcontentContent,
		'polish'                   => $polishcontentContent,
		'symmetry'                 => $symmetrycontentContent,
		'price_from'               => ($request["price"]["from"]) ? str_replace(',', '', $request["price"]["from"]) : '',
		'price_to'                 => ($request["price"]["to"]) ? str_replace(',', '', $request["price"]["to"]) : '',
		'diamond_table_from'       => (intval($request["diamond_table"]["from"])) ? intval($request["diamond_table"]["from"]) : '',
		'diamond_table_to'         => (intval($request["diamond_table"]["to"])) ? intval($request["diamond_table"]["to"]) : '',
		'depth_percent_from'       => (intval($request["diamond_depth"]["from"])) ? intval($request["diamond_depth"]["from"]) : '',
		'depth_percent_to'         => (intval($request["diamond_depth"]["to"])) ? intval($request["diamond_depth"]["to"]) : '',
		'labs'                     => $certificatesContent,
		'page_number'              => ($request['currentpage']) ? $request['currentpage'] : '',
		'page_size'                => ($request['itemperpage']) ? $request['itemperpage'] : getResultPerPage_dl(),
		'sort_by'                  => ($request['orderby']) ? $request['orderby'] : '',
		'sort_direction'           => ($request['direction']) ? $request['direction'] : '',
		'did'                      => $did,
		'hasvideo'                 => $hasvideo,
		'Filtermode'               => ($request['filtermode']) ? $request['filtermode'] : 'navstandard'
	];

	

	if (isset($request['filtermode'])) {
		if ($request['filtermode'] != 'navstandard' && $request['filtermode'] != 'navlabgrown') {
			// Convert the Symmetry list into gemfind form			
			if (array_key_exists('diamond_fancycolor', $request)) {
				foreach ($request["diamond_fancycolor"] as $value) {
					$fancycolorcontent[] = strtolower($value);
				}
				$fancycolorcontentContent = implode(',', $fancycolorcontent);
			}
			// Convert the Symmetry list into gemfind form
			if (array_key_exists('diamond_intintensity', $request)) {
				foreach ($request["diamond_intintensity"] as $value) {
					$intintensitycontent[] = strtolower($value);
				}
				$intintensitycontentContent = implode(',', $intintensitycontent);
			}
			$fancyData   = [
				'FancyColor'   => $fancycolorcontentContent,
				'intIntensity' => $intintensitycontentContent
			];
			$requestData = array_merge($requestData, $fancyData);
		}
	}
	$result = sendRequest_dl($requestData);
	// get option api call
	$diamondsoptionapi = get_option('gemfind_ring_builder');
	$diamondsoption    = sendRequest_dl(array('diamondsoptionapi' => $diamondsoptionapi['diamondsoptionapi']));
	$show_in_house_column = $diamondsoption[0][0]->show_In_House_Diamonds_Column_with_SKU;
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	if ($request['orderby'] == 'Cut') {
		unset($request['orderby']);
		$request['orderby'] = 'shape';
	}
	if ($request['orderby'] == 'Size') {
		unset($request['orderby']);
		$request['orderby'] = 'caratWeight';
	}
	if ($request['orderby'] == 'FltPrice' || $request['orderby'] == 'Size' || $request['orderby'] == 'Depth' || $request['orderby'] == 'TableMeasure' || $request['orderby'] == 'Measurements' || $request['orderby'] == 'caratWeight') {
		$orderby = 'meta_value_num';
	} else {
		$orderby = 'meta_value';
	}
	if (isset($request['did']) && $request['did'] != '') {
		$meta_key   = '_sku';
		$meta_value = $request['did'];
	} else {
		$meta_key = $request['orderby'];
	}
	$paged = ($request['currentpage']) ? $request['currentpage'] : 1;

	$tax_query = array();
	$tax_query = array('relation' => 'AND');
	if ($filter_type == 'navstandard') {
		$meta_key = 'productType';
		if (isset($request['diamond_shape']) && !empty($request['diamond_shape'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_shape',
					'field'    => 'slug',
					'terms'    => $request['diamond_shape'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_color']) && !empty($request['diamond_color'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_color',
					'field'    => 'slug',
					'terms'    => $request['diamond_color'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_clarity']) && !empty($request['diamond_clarity'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_clarity',
					'field'    => 'slug',
					'terms'    => $request['diamond_clarity'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_cut']) && !empty($request['diamond_cut'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_cut',
					'field'    => 'slug',
					'terms'    => $request['diamond_cut'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_symmetry']) && !empty($request['diamond_symmetry'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_symmetry',
					'field'    => 'slug',
					'terms'    => $request['diamond_symmetry'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_certificates']) && !empty($request['diamond_certificates'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_certificate',
					'field'    => 'slug',
					'terms'    => $request['diamond_certificates'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_cut']) && !empty($request['diamond_cut'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_cut',
					'field'    => 'slug',
					'terms'    => $request['diamond_cut'],
					'operator' => 'IN'
				);
		}
		$meta_query = array('relation' => 'AND');
		if (isset($request['diamond_carats']) && !empty($request['diamond_carats'])) {
			$meta_query[] = array(
				'key'     => 'caratWeight',
				'value'   => array($request['diamond_carats']['from'], $request['diamond_carats']['to']),
				'compare' => 'BETWEEN'
			);
		}
		if (isset($request['price']) && !empty($request['price'])) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => array($request['price']['from'], $request['price']['to']),
				'compare' => 'BETWEEN'
			);
		}
		if (isset($request['diamond_depth']) && !empty($request['diamond_depth'])) {
			$meta_query[] = array(
				'key'     => 'depth',
				'value'   => floor($request['diamond_depth']['to'] + 1),
				'compare' => '<='
			);
		}

		if (isset($request['diamond_table']) && !empty($request['diamond_table'])) {
			$meta_query[] = array(
				'key'     => 'table',
				'value'   => floor($request['diamond_table']['to'] + 1),
				'compare' => '<='
			);
		}
		if (isset($request['did']) && !empty($request['did'])) {
			$meta_query[] = array(
				'key'     => '_sku',
				'value'   => $request['did'],
				'compare' => '='
			);
		}
	} elseif ($filter_type == 'navfancycolored') {
		$meta_key = 'fancyColorIntensity';
		if (isset($request['diamond_shape']) && !empty($request['diamond_shape'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_shape',
					'field'    => 'slug',
					'terms'    => $request['diamond_shape'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_fancycolor']) && !empty($request['diamond_fancycolor'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_color',
					'field'    => 'slug',
					'terms'    => $request['diamond_fancycolor'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_clarity']) && !empty($request['diamond_clarity'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_clarity',
					'field'    => 'slug',
					'terms'    => $request['diamond_clarity'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_cut']) && !empty($request['diamond_cut'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_cut',
					'field'    => 'slug',
					'terms'    => $request['diamond_cut'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_symmetry']) && !empty($request['diamond_symmetry'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_symmetry',
					'field'    => 'slug',
					'terms'    => $request['diamond_symmetry'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_certificates']) && !empty($request['diamond_certificates'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_certificate',
					'field'    => 'slug',
					'terms'    => $request['diamond_certificates'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_intintensity']) && !empty($request['diamond_intintensity'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_intensity',
					'field'    => 'slug',
					'terms'    => $request['diamond_intintensity'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_polish']) && !empty($request['diamond_polish'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_polish',
					'field'    => 'slug',
					'terms'    => $request['diamond_polish'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_fluorescence']) && !empty($request['diamond_fluorescence'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_fluorescence',
					'field'    => 'slug',
					'terms'    => $request['diamond_fluorescence'],
					'operator' => 'IN'
				);
		}
		$meta_query = array('relation' => 'AND');
		if (isset($request['diamond_carats']) && !empty($request['diamond_carats'])) {
			$meta_query[] = array(
				'key'     => 'caratWeight',
				'value'   => $request['diamond_carats']['to'],
				'compare' => '<='
			);
		}
		if (isset($request['price']) && !empty($request['price'])) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => $request['price']['to'],
				'compare' => '<='
			);
		}
		if (isset($request['diamond_depth']) && !empty($request['diamond_depth'])) {
			$meta_query[] = array(
				'key'     => 'depth',
				'value'   => floor($request['diamond_depth']['to'] + 1),
				'compare' => '<='
			);
		}
		if (isset($request['diamond_table']) && !empty($request['diamond_table'])) {
			$meta_query[] = array(
				'key'     => 'table',
				'value'   => floor($request['diamond_table']['to'] + 1),
				'compare' => '<='
			);
		}
		if (isset($request['did']) && !empty($request['did'])) {
			$meta_query[] = array(
				'key'     => '_sku',
				'value'   => $request['did'],
				'compare' => '='
			);
		}
	}


	if ($options['load_from_woocommerce'] == '1') {
		unset($result['diamonds']);
		unset($result['total']);
		$loop = $args = new WP_Query(array(
			'post_type'      => array('product'),
			'post_status'    => 'publish',
			'posts_per_page' => $request['itemperpage'],
			'paged'          => $paged,
			'meta_key'       => $meta_key,
			// 'meta_value'     => $meta_value,
			'tax_query'      => $tax_query,
			'meta_query'     => $meta_query,
			'orderby'        => $request['orderby'],
			'order'          => $request['direction']
		));




		while ($loop->have_posts()) : $loop->the_post();
			global $product;
			$isFancy = get_post_meta($product->get_id(), 'fancyColorIntensity', true);
			$product_meta                      = get_post_meta($product->get_ID(), '', false);
			$product_attributes                = unserialize($product_meta['_product_attributes'][0]);
			$diamondInfo                       = array();
			$diamondInfo['diamondId']          = $product_meta['_sku'][0];
			$diamondInfo['diamondImage']       = get_the_post_thumbnail_url();
			$diamondInfo['biggerDiamondimage'] = $product_meta['image2'][0];
			$filter_type                       = (isset($request['filtermode']) && !empty($request['filtermode'])) ? $request['filtermode'] : 'navstandard';



			if ($filter_type == 'navstandard') {
				$shape = explode(',', $product->get_attribute('pa_gemfind_shape'));
				if (is_array($shape)) {
					$diamondInfo['shape'] = $shape[0];
				} else {
					$diamondInfo['shape'] = $product->get_attribute('pa_gemfind_shape');
				}
				$polish = explode(',', $product->get_attribute('pa_gemfind_polish'));
				if (is_array($polish)) {
					$diamondInfo['polish'] = $polish[0];
				} else {
					$diamondInfo['polish'] = $product->get_attribute('pa_gemfind_polish');
				}
				$cert = explode(',', $product->get_attribute('pa_gemfind_certificate'));
				if (is_array($cert)) {
					$diamondInfo['cert'] = $cert[0];
				} else {
					$diamondInfo['cert'] = $product->get_attribute('pa_gemfind_certificate');
				}
				$clarity = explode(',', $product->get_attribute('pa_gemfind_clarity'));
				if (is_array($clarity)) {
					$diamondInfo['clarity'] = $clarity[0];
				} else {
					$diamondInfo['clarity'] = $product->get_attribute('pa_gemfind_clarity');
				}
				$color = explode(',', $product->get_attribute('pa_gemfind_color'));
				if (is_array($color)) {
					$diamondInfo['color'] = $color[0];
				} else {
					$diamondInfo['color'] = $product->get_attribute('pa_gemfind_color');
				}
				$symmetry = explode(',', $product->get_attribute('pa_gemfind_symmetry'));
				if (is_array($symmetry)) {
					$diamondInfo['symmetry'] = $symmetry[0];
				} else {
					$diamondInfo['symmetry'] = $product->get_attribute('pa_gemfind_symmetry');
				}
				$fluorescence = explode(',', $product->get_attribute('pa_gemfind_fluorescence'));
				if (is_array($fluorescence)) {
					$diamondInfo['fluorescence'] = $fluorescence[0];
				} else {
					$diamondInfo['fluorescence'] = $product->get_attribute('pa_gemfind_fluorescence');
				}
				$cut = explode(',', $product->get_attribute('pa_gemfind_cut'));
				if (is_array($cut)) {
					$diamondInfo['cut'] = $cut[0];
				} else {
					$diamondInfo['cut'] = $product->get_attribute('pa_gemfind_cut');
				}
				$diamondInfo['carat'] = $product_meta['caratWeight'][0];
				$diamondInfo['depth'] = $product_meta['depth'][0];
				$diamondInfo['table'] = $product_meta['table'][0];
				$diamondInfo['price'] = $product_meta['FltPrice'][0];
			} elseif ($filter_type == 'navfancycolored') {
				$isFancy = get_post_meta($product->get_id(), 'fancyColorIntensity', true);
				if (isset($isFancy) && $isFancy != '') {
					$shape = explode(',', $product->get_attribute('pa_gemfind_fancy_shape'));
					if (is_array($shape)) {
						$diamondInfo['shape'] = $shape[0];
					} else {
						$diamondInfo['shape'] = $product->get_attribute('pa_gemfind_fancy_shape');
					}
					$polish = explode(',', $product->get_attribute('pa_gemfind_fancy_polish'));
					if (is_array($polish)) {
						$diamondInfo['polish'] = $polish[0];
					} else {
						$diamondInfo['polish'] = $product->get_attribute('pa_gemfind_fancy_polish');
					}
					$cert = explode(',', $product->get_attribute('pa_gemfind_fancy_certificate'));
					if (is_array($cert)) {
						$diamondInfo['cert'] = $cert[0];
					} else {
						$diamondInfo['cert'] = $product->get_attribute('pa_gemfind_fancy_certificate');
					}
					$clarity = explode(',', $product->get_attribute('pa_gemfind_fancy_clarity'));
					if (is_array($clarity)) {
						$diamondInfo['clarity'] = $clarity[0];
					} else {
						$diamondInfo['clarity'] = $product->get_attribute('pa_gemfind_fancy_clarity');
					}
					$color = explode(',', $product->get_attribute('pa_gemfind_fancy_color'));
					if (is_array($color)) {
						$diamondInfo['color'] = $color[0];
					} else {
						$diamondInfo['color'] = $product->get_attribute('pa_gemfind_fancy_color');
					}
					$symmetry = explode(',', $product->get_attribute('pa_gemfind_fancy_symmetry'));
					if (is_array($symmetry)) {
						$diamondInfo['symmetry'] = $symmetry[0];
					} else {
						$diamondInfo['symmetry'] = $product->get_attribute('pa_gemfind_fancy_symmetry');
					}
					$fluorescence = explode(',', $product->get_attribute('pa_gemfind_fancy_fluorescence'));
					if (is_array($fluorescence)) {
						$diamondInfo['fluorescence'] = $fluorescence[0];
					} else {
						$diamondInfo['fluorescence'] = $product->get_attribute('pa_gemfind_fancy_fluorescence');
					}
					// $cut = explode( ',', $product->get_attribute('pa_gemfind_cut') );
					// if( is_array( $cut ) ) {
					// 	$diamondInfo['cut']      = $cut[0];
					// } else {
					// 	$diamondInfo['cut'] = $product->get_attribute('pa_gemfind_cut');
					// }
					$diamondInfo['carat'] = $product_meta['caratWeightFancy'][0];
					$diamondInfo['depth'] = $product_meta['depthFancy'][0];
					$diamondInfo['table'] = $product_meta['tableFancy'][0];
					$diamondInfo['price'] = $product_meta['FltPriceFancy'][0];
				}
			}
			$diamondInfo['measurement']    = $product_meta['measurement'][0];
			$diamondInfo['certificateUrl'] = $product_meta['certificateUrl'][0];
			$diamondInfo['gridle']         = $product_meta['gridle'][0];
			$diamondInfo['culet']          = $product_meta['culet'][0];
			$result['diamonds'][]          = (object) $diamondInfo;
		//}			
		endwhile;
		wp_reset_query();
		$result['total'] = $loop->found_posts;
	}

	$num = ceil($result['total'] / getResultPerPage_dl());
	if ($result['diamonds'] != null || $result['total'] != 0) {
		$count = 0;
		if ($request['currentpage'] > 1) {
			$count = ($request['itemperpage']) ? $request['itemperpage'] : getResultPerPage_dl() * ($request['currentpage'] - 1);
		}
		$diamond = [
			'meta'       => ['code' => 200],
			'data'       => $result['diamonds'],
			'pagination' => [
				'currentpage' => $request['currentpage'],
				'count'       => $count,
				'limit'       => count($result['diamonds']),
				'total'       => $result['total']
			],
			'perpage'    => ($request['itemperpage']) ? $request['itemperpage'] : getResultPerPage_dl()
		];
	} else {
		$diamond = [
			'meta'       => ['code' => 404, 'message' => "No Product Found"],
			'data'       => [],
			'pagination' => ['total' => $result['total']],
			'perpage'    => getResultPerPage_dl()
		];
	}
	$html = '';
	?>
	<?php
	if (isset($diamond['pagination']['total']) && $diamond['pagination']['total'] != 0) : ?>
		<?php
		// $class = ( !isset( $back_cookie_data['viewmode'] ) ) ? "active" : "";
		$grid_active_class = '';
		$list_active_class = '';
		// check weater cookies is set for view grid / list
		if (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'grid') {
			$cls_hide_grid = 'cls-for-hide';
			$grid_active_class = 'active';
		} elseif (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'list') {
			$cls_hide_list = 'cls-for-hide';
			$list_active_class = 'active';
		}
		// code for check if cookies is empty then get default view from admin settings		
		if (!isset($back_cookie_data['viewmode']) && $options['default_view'] == 'grid') {
			$grid_active_class = 'active';
			$cls_hide_grid = 'cls-for-hide';
		}
		if (!isset($back_cookie_data['viewmode']) && ($options['default_view'] == 'list' || empty($options['default_view']))) {
			$list_active_class = 'active';
			$cls_hide_list = 'cls-for-hide';
		}
		// print_r($back_cookie_data['viewmode']);
		$file = 'common_log.txt';
		file_put_contents($file, $back_cookie_data['viewmode'] .''.$options['default_view']);

		?>
		<div class='search-details no-padding'>
			<div class='searching-result'>
				<div class='number-of-search'>
					<?php $dia_count =0; ?>
					<p><strong><?php echo number_format($diamond['pagination']['total']); ?></strong>Similar Diamonds | </p>  
					<p> Compare Items (<span id="totaldiamonds" ><?php echo $dia_count; ?></span>)</p>
				</div>

				<?php //echo "<pre>"; print_r($diamond['data']['0']->fancyColorIntensity); exit(); ?>
				<?php //echo $filter_type; ?>

				<div class='search-in-table' id='searchintable'>
					<input type='text' name='searchdidfield' id='searchdidfield' placeholder='Search Diamond Stock #'><a href='javascript:;' title='close' id='resetsearchdata'>X</a>
					<button id='searchdid' title='Search Diamond'></button>
				</div>
				<div class='view-or-search-result'>
					<div class='change-view-result'>
						<ul>
							<li class='grid-view' data-toggle="tooltip" data-placement="top" title="Grid view">
								<a href='javascript:;' class="<?php echo $grid_active_class; ?>">Grid view</a>
							</li>
							<li class='list-view' data-toggle="tooltip" data-placement="top" title="List view">
								<a href='javascript:;' class="<?php echo $list_active_class; ?>">list view</a>
							</li>
						</ul>
						<div class='item-page'>
							<p class='leftpp'>Per Page</p>
							<select class='pagesize SumoUnder' id='pagesize' name='pagesize' onchange='ItemPerPage(this)' tabindex='-1'>
								<?php
								$all_options = getAllOptions_dl();
								foreach ($all_options as $value) {
								?>
									<option value='<?php echo $value['value']; ?>'><?php echo $value['label']; ?></option>
								<?php
								}
								?>
							</select>
						</div>
						
						<div class='grid-view-sort cls-for-hide'>
							<select name='gridview-orderby' id='gridview-orderby' class='gridview-orderby SumoUnder' onchange='gridSort(this)' tabindex='-1'>
								<option value='Cut'>Shape</option>

								<option value='Size'>Carat</option>
								<option value='Color'>Color</option>
								<?php if ($request['filtermode'] == 'navfancycolored'){ ?>
								<option value='FancyColorIntensity'>Intensity</option>
								<?php } ?>
								<option value='ClarityID'>Clarity</option>
								<option value='CutGrade'>Cut</option>
								<option value='Depth'>Depth</option>
								<option value='TableMeasure'>Table</option>
								<option value='Polish'>Polish</option>
								<option value='Symmetry'>Symmetry</option>
								<option value='Measurements'>Measurement</option>
								<?php if ($show_Certificate_in_Diamond_Search) { ?>
									<option value='Certificate'>Certificate</option>
								<?php } ?>
								<?php if ($show_in_house_column) { ?>
									<option value='Inhouse'>In House</option>
								<?php } ?>
								<option value='FltPrice' selected='selected'>Price</option>
							</select>
							<div class='gridview-dir-div'>
								<a href='javascript:;' onclick='gridDire("DESC")' id='ASC' title='Set Descending Direction' class='active'>ASC</a><a href='javascript:;' title='Set Ascending Direction' onclick='gridDire("ASC")' id='DESC'>DESC</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class='search-details no-padding'>
			<div class='table-responsive <?php echo $cls_hide_grid; ?>' id='list-mode'>
				<table class='table' id='result_table'>
					<thead class="table_header_wrapper">
						<tr>
							<th scope='col'>javascript:;</th>
							<th scope='col' class='table-sort' title="Shape Sorting Asc/Desc" id='Cut' onclick='fnSort("Cut");'>Shape</th>
							<th scope='col' class='table-sort' title="Carat Sorting Asc/Desc" id='Size' onclick='fnSort("Size");'>Carat</th>
							<th scope='col' class='table-sort' title="Color Sorting Asc/Desc" id='Color' onclick='fnSort("Color");'>Color</th>
							<?php if ($request['filtermode'] == 'navfancycolored'){ ?>
							<th scope='col' class='table-sort' title="Intensity Sorting Asc/Desc" id='FancyColorIntensity' onclick='fnSort("FancyColorIntensity");'>Intensity</th>
							<?php } ?>
							<th scope='col' class='table-sort' title="Clarity Sorting Asc/Desc" id='ClarityID' onclick='fnSort("ClarityID");'>Clarity</th>
							<th scope='col' class='table-sort' title="Cut Sorting Asc/Desc" id='CutGrade' onclick='fnSort("CutGrade");'>Cut</th>
							<th scope='col' class='table-sort' id='Depth' title="Depth Sorting Asc/Desc" onclick='fnSort("Depth");'>Depth</th>
							<th scope='col' class='table-sort' id='TableMeasure' title="Table Sorting Asc/Desc" onclick='fnSort("TableMeasure");'>Table</th>
							<th scope='col' class='table-sort' id='Polish' title="Polish Sorting Asc/Desc" onclick='fnSort("Polish");'>Polish</th>
							<th scope='col' class='table-sort' id='Symmetry' title="Symmetry Sorting Asc/Desc" onclick='fnSort("Symmetry");'>Sym.</th>
							<th scope='col' class='table-sort' id='Measurements' title="Measurement Sorting Asc/Desc" onclick='fnSort("Measurements");'>Measurement</th>
							<?php if ($show_Certificate_in_Diamond_Search) { ?>
								<th scope='col' class='table-sort' id='Certificate' title="Certificate Sorting Asc/Desc" onclick='fnSort("Certificate");'>Cert.</th>
							<?php } ?>
							<?php if ($show_in_house_column) { ?>
								<th scope='col' class='table-sort' title="In House Sorting Asc/Desc" id='inhouse' onclick='fnSort("Inhouse");'>In House</th>
							<?php } ?>
							<th scope='col' class='table-sort' id='FltPrice' title="Price Sorting Asc/Desc" onclick='fnSort("FltPrice");'>Price (<?php echo isset($diamond['data']) ? $diamond['data'][0]->currencyFrom : '' ;?>)</th>
							
							<th scope='col' class='view-data' id='dia_view'> </th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($diamond['data'] as $diamondData) {
							// echo '<pre>';print_r($diamondData); exit; 
							if ($diamondData->fancyColorMainBody) {
								if ($diamondData->diamondImage) {
									$imageurl = $diamondData->diamondImage;
								} else {
									$imageurl = $noimageurl;
								}
								// for showing color value in column of color ( fancycolor ) 
								//$color_to_display = $diamondData->fancyColorIntensity . ' ' . $diamondData->fancyColorMainBody;
								$color_to_display = $diamondData->fancyColorMainBody;
								$Intensity_to_display = $diamondData->fancyColorIntensity ;
							} else {
								if ($diamondData->biggerDiamondimage) {
									$imageurl = $diamondData->biggerDiamondimage;
								} else {
									$imageurl = $noimageurl;
								}
								// for showing color value in column of color ( standard tab )
								$color_to_display = $diamondData->color;
							}
							if ($diamondData->isLabCreated) {
								$type = 'labcreated';
							} elseif ($filter_type == 'navfancycolored') {
								$type = 'fancydiamonds';
							} elseif (strpos($_SERVER["HTTP_REFERER"], "navfancycolored")!== false) {
								$type = 'fancydiamonds';
							}else {
								$type = '';
							}
							if (isset($diamondData->shape)) {
								$urlshape = str_replace(' ', '-', $diamondData->shape) . '-shape-';
							} else {
								$urlshape = '';
							}
							if (isset($diamondData->carat)) {
								$urlcarat = str_replace(' ', '-', $diamondData->carat) . '-carat-';
							} else {
								$urlcarat = '';
							}
							if (isset($diamondData->color)) {
								$urlcolor = str_replace(' ', '-', $diamondData->color) . '-color-';
							} else {
								$urlcolor = '';
							}
							if (isset($diamondData->clarity)) {
								$urlclarity = str_replace(' ', '-', $diamondData->clarity) . '-clarity-';
							} else {
								$urlclarity = '';
							}
							if (isset($diamondData->cut)) {
								$urlcut = str_replace(' ', '-', $diamondData->cut) . '-cut-';
							} else {
								$urlcut = '';
							}
							if (isset($diamondData->cert)) {
								$urlcert = str_replace(' ', '-', $diamondData->cert) . '-certificate-';
							} else {
								$urlcert = '';
							}
							$urlstring = strtolower($urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $diamondData->diamondId);
							$diamondviewurl = '';
							$diamondviewurl = getDiamondViewUrl_dl($urlstring, $type, get_site_url() . '/ringbuilder/diamondlink', $pathprefixshop) . '/' . $type;
							$class = ($diamondData->diamondId == $back_cookie_data['did']) ? "selected_row" : "";
						?>
							<tr id="<?php echo $diamondData->diamondId; ?>" class="<?php echo $class; ?>">
								<th scope="row" class="table-selecter">
									<input type="checkbox" name="compare" value="<?php echo $diamondData->diamondId; ?>">
									<div class="state"><label>Fill</label></div>
								</th>
								<td class="Cutcol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<span class="imagecheck" data-src="<?php echo $imageurl; ?>" data-srcbig="<?php echo $diamondData->biggerDiamondimage; ?>" data-id="<?php echo $diamondData->diamondId; ?>"></span>
									<img src="<?php echo $loaderimg; ?>" width="21" height="18" alt="<?php echo $diamondData->shape . ' ' . $diamondData->carat . ' CARAT'; ?>" title="<?php echo $diamondData->shape . ' ' . $diamondData->carat . ' CARAT'; ?>">
									<span class="shape-name"><?php echo $diamondData->shape; ?></span>
								</td>
								<td class="Sizecol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php echo $diamondData->carat; ?>
								</td>
								<td class="Colorcol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php echo ($color_to_display != '') ? $color_to_display : '-'; ?>
								</td>
								<?php if ($diamondData->fancyColorMainBody) { ?>
								<td class="Intensitycol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php echo ($Intensity_to_display != '') ? $Intensity_to_display : '-'; ?>
								</td>
								<?php } ?>
								<td class="ClarityIDcol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php echo ($diamondData->clarity != '') ? $diamondData->clarity : '-'; ?>
								</td>
								<td class="CutGradecol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php
									if ($diamondData->cut == 'Good') {
										echo 'G';
									} else if ($diamondData->cut == 'Very good') {
										echo 'VG';
									} else if ($diamondData->cut == 'Excellent') {
										echo 'Ex';
									} else if ($diamondData->cut == 'Fair') {
										echo 'F';
									} else if ($diamondData->cut == 'Ideal') {
										echo 'I';
									} else {
										echo '-';
									}
									//echo ( $diamondData->cut != '' ) ? $diamondData->cut : '-';
									?>
								</td>
								<td class="Depthcol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php echo ($diamondData->depth != '') ? $diamondData->depth : '-'; ?>
								</td>
								<td class="TableMeasurecol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php echo ($diamondData->table != '') ? $diamondData->table : '-'; ?>
								</td>
								<td class="Polishcol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php
									if ($diamondData->polish == 'Good') {
										echo 'G';
									} else if ($diamondData->polish == 'Very good') {
										echo 'VG';
									} else if ($diamondData->polish == 'Excellent') {
										echo 'Ex';
									} else if ($diamondData->polish == 'Fair') {
										echo 'F';
									} else {
										echo '-';
									}
									//echo ( $diamondData->polish != '' ) ? $diamondData->polish : '-'; 
									?>
								</td>
								<td class="Symmetrycol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php
									if ($diamondData->symmetry == 'Good') {
										echo 'G';
									} else if ($diamondData->symmetry == 'Very good') {
										echo 'VG';
									} else if ($diamondData->symmetry == 'Excellent') {
										echo 'Ex';
									} else if ($diamondData->symmetry == 'Fair') {
										echo 'F';
									} else {
										echo '-';
									}
									//echo ( $diamondData->symmetry != '' ) ? $diamondData->symmetry : '-'; 
									?>
								</td>
								<td class="Measurementscol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php echo $diamondData->measurement; ?>
								</td>
								<?php if ($show_Certificate_in_Diamond_Search) { ?>
									<td class="Certificatecol"><a href="javascript:void(0)" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'"><?php echo $diamondData->cert; ?></a>
									</td>
								<?php } ?>
								<?php if ($show_in_house_column) { ?>
									<td class="inhousecol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
										<?php echo $diamondData->inhouse; ?>
									</td>
								<?php } ?>
								<?php //$strprice=[".00", ","]; $strreplaceprice=["", ""]; ?>
								<td class="FltPricecol" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
									<?php
									$dprice = $diamondData->fltPrice;
									$dprice = str_replace(',', '', $dprice);
									if ($diamondData->showPrice == true) {
										if($options['price_row_format'] == 'left'){

											if($diamondData->currencyFrom == 'USD'){
											
												echo "$".number_format($dprice); 
											
											}else{
											
												echo number_format($dprice).' '.$diamondData->currencySymbol.' '.$diamondData->currencyFrom;
											
											}
											
										}else{
											
											  if($diamondData->currencyFrom == 'USD'){
											
												echo "$".number_format($dprice); 
											
											  }else{
											
												echo $diamondData->currencyFrom.' '.$diamondData->currencySymbol.' '.number_format($dprice);   
											
											  }
											
										}
									} else {
										echo 'Call';
									}
									?>
								</td>
								
								<!-- <td class="view-data dia_viewcol" onclick="SetBackValue('<?php //echo $diamondData->diamondId; ?>'); location.href='<?php //echo $diamondviewurl; ?>'">
									<a href="javascript:;" title="View Diamond">
										<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="29px" height="17px" viewBox="0 0 72.767 72.766" style="enable-background:new 0 0 72.767 72.766;" xml:space="preserve">
											<g>
												<g id="Eye_Outline">
													<g>
														<path d="M72.163,34.225C71.6,33.292,58.086,11.352,37.48,11.352h-2.195c-20.605,0-34.117,21.94-34.681,22.873
								c-0.805,1.323-0.805,2.993,0,4.316c0.564,0.94,14.076,22.873,34.682,22.873h2.195c20.604,0,34.118-21.933,34.683-22.873
								C72.968,37.218,72.968,35.548,72.163,34.225z M37.48,53.141h-2.195c-12.696,0-22.625-11.793-26.242-16.758
								c3.621-4.971,13.546-16.758,26.242-16.758h2.195c12.7,0,22.632,11.802,26.246,16.766C60.125,41.363,50.237,53.141,37.48,53.141z
								M36.383,29.66c-3.666,0-6.633,3.016-6.633,6.724c0,3.716,2.967,6.725,6.633,6.725c3.664,0,6.635-3.009,6.635-6.725
								C43.018,32.675,40.047,29.66,36.383,29.66z"></path>
													</g>
												</g>
											</g>
										</svg>
									</a>
								</td> -->

								<td class="view-data dia_viewcol" onmouseover="onMouseOverMoreInfo(this)" onmouseout="onMouseOutMoreInfo(this)">
								    <a href="javascript:;" title="More Info">
								        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
								    </a>

								    <div class="icon-list">
								        <a href="javascript:;" title="View Diamond" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
								            <svg fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
												 viewBox="0 0 512 512" xml:space="preserve">
											<g>
												<g>
													<path d="M256,189.079c-36.871,0.067-66.853,30.049-66.92,66.923c0.068,36.871,30.049,66.853,66.92,66.92
														c36.871-0.067,66.853-30.049,66.92-66.92C322.853,219.128,292.871,189.147,256,189.079z M256,289.461
														c-18.48,0-33.46-14.98-33.46-33.46c0-18.48,14.98-33.46,33.46-33.46s33.46,14.98,33.46,33.46
														C289.46,274.481,274.48,289.461,256,289.461z"/>
												</g>
											</g>
											<g>
												<g>
													<path d="M509.082,246.729C451.986,169.028,353.822,89.561,256,89.231c-98.014,0.33-196.379,80.332-253.082,157.498
														c-3.89,5.576-3.89,12.965,0,18.541C60.015,342.972,158.179,422.44,256,422.769c98.014-0.329,196.38-80.332,253.082-157.498
														C512.973,259.693,512.973,252.305,509.082,246.729z M256,356.379c-55.407-0.027-100.351-44.974-100.38-100.378
														c0.029-55.405,44.975-100.354,100.38-100.38c55.407,0.027,100.351,44.974,100.38,100.38
														C356.351,311.406,311.407,356.353,256,356.379z"/>
												</g>
											</g>
											</svg>
								        </a>
								        <?php if ($diamondData->hasVideo == true && !empty($diamondData->videoFileName)) { ?>
								        <a href="javascript:;" title="Watch Video"  class="triggerVideo" data-id="<?php echo $diamondData->diamondId; ?>" onclick="showModaldb()">
								          
								            <svg fill="#000000" width="20px" height="20px" viewBox="-4 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
												<title>video</title>
												<path d="M15.5 13.219l6.844-3.938c0.906-0.531 1.656-0.156 1.656 0.938v11.625c0 1.063-0.75 1.5-1.656 0.969l-6.844-3.969v2.938c0 1.094-0.875 1.969-1.969 1.969h-11.625c-1.063 0-1.906-0.875-1.906-1.969v-11.594c0-1.094 0.844-1.938 1.906-1.938h11.625c1.094 0 1.969 0.844 1.969 1.938v3.031z"></path>
												</svg>
								        </a>
								    <?php } ?>
								        <a href="javascript:;" title="Additional Information" data-id="<?php echo $diamondData->diamondId; ?>" onclick="showAdditionalInformation('<?php echo $diamondData->diamondId; ?>')">
								            <svg height="20px" width="20px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
												 viewBox="0 0 512 512"  xml:space="preserve">											
												<g>
													<path class="st0" d="M290.671,135.434c37.324-3.263,64.949-36.175,61.663-73.498c-3.241-37.324-36.152-64.938-73.476-61.675
														c-37.324,3.264-64.949,36.164-61.686,73.488C220.437,111.096,253.348,138.698,290.671,135.434z"/>
													<path class="st0" d="M311.31,406.354c-16.134,5.906-43.322,22.546-43.322,22.546s20.615-95.297,21.466-99.446
														c8.71-41.829,33.463-100.86-0.069-136.747c-23.35-24.936-53.366-18.225-79.819,7.079c-17.467,16.696-26.729,27.372-42.908,45.322
														c-6.55,7.273-9.032,14.065-5.93,24.717c3.332,11.515,16.8,17.226,28.705,12.871c16.134-5.895,43.3-22.534,43.3-22.534
														s-12.595,57.997-18.869,87c-0.874,4.137-36.06,113.292-2.505,149.18c23.35,24.949,53.343,18.226,79.819-7.066
														c17.467-16.698,26.729-27.373,42.908-45.334c6.55-7.263,9.009-14.054,5.93-24.706C336.66,407.733,323.215,402.01,311.31,406.354z"
														/>
												</g>
											</svg>
								        </a>
								    </div>
								</td>

							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<div class='search-view-grid <?php echo (isset($cls_hide_list)) ? $cls_hide_list : '';
											echo (!isset($cls_hide_list) && !isset($cls_hide_grid)) ? 'cls-for-hide' : ''; ?>' id='grid-mode'>
				<div class='grid-product-listing'>
					<?php
					foreach ($diamond['data'] as $diamondData) {
						if ($diamondData->fancyColorMainBody) {
							if ($diamondData->diamondImage) {
								$imageurl = $diamondData->diamondImage;
							} else {
								$imageurl = $noimageurl;
							}
							// for showing color value in column of color ( fancycolor ) 
							//$color_to_display = $diamondData->fancyColorIntensity . ' ' . $diamondData->fancyColorMainBody;
							$color_to_display = $diamondData->fancyColorMainBody;
							$Intensity_to_display = $diamondData->fancyColorIntensity;
						} else {
							if ($diamondData->biggerDiamondimage) {
								$imageurl = $diamondData->biggerDiamondimage;
							} else {
								$imageurl = $noimageurl;
							}
							// for showing color value in column of color ( standard tab )
							$color_to_display = $diamondData->color;
						}
						if ($diamondData->isLabCreated) {
							$type = 'labcreated';
						} elseif ($filter_type == 'navfancycolored') {
							$type = 'fancydiamonds';
						} else {
							$type = '';
						}
						if (isset($diamondData->shape)) {
							$urlshape = str_replace(' ', '-', $diamondData->shape) . '-shape-';
						} else {
							$urlshape = '';
						}
						if (isset($diamondData->carat)) {
							$urlcarat = str_replace(' ', '-', $diamondData->carat) . '-carat-';
						} else {
							$urlcarat = '';
						}
						if (isset($diamondData->color)) {
							$urlcolor = str_replace(' ', '-', $diamondData->color) . '-color-';
						} else {
							$urlcolor = '';
						}
						if (isset($diamondData->clarity)) {
							$urlclarity = str_replace(' ', '-', $diamondData->clarity) . '-clarity-';
						} else {
							$urlclarity = '';
						}
						if (isset($diamondData->cut)) {
							$urlcut = str_replace(' ', '-', $diamondData->cut) . '-cut-';
						} else {
							$urlcut = '';
						}
						if (isset($diamondData->cert)) {
							$urlcert = str_replace(' ', '-', $diamondData->cert) . '-certificate-';
						} else {
							$urlcert = '';
						}
						$urlstring = strtolower($urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $diamondData->diamondId);
						$diamondviewurl = '';
						$diamondviewurl = getDiamondViewUrl_dl($urlstring, $type, get_site_url() . '/ringbuilder/diamondlink', $pathprefixshop) . '/' . $type;
						$class = ($diamondData->diamondId == $back_cookie_data['did']) ? "selected_grid" : "";
					?>
						<div class="search-product-grid <?php echo $class; ?>" id="<?php echo $diamondData->diamondId; ?>">
							<?php if ($diamondData->hasVideo == true && !empty($diamondData->videoFileName)) { ?>
								<a href="javascript:;" class="triggerVideo" data-id="<?php echo $diamondData->diamondId; ?>" onclick="showModaldb()"> <i class="fa fa-video-camera"> </i> </a>
							<?php } ?>

							<div class="product-images">
								<?php
								if ($filter_type == 'navfancycolored') {
									$diamondImage = $diamondData->diamondImage;
								} else {
									$diamondImage = $diamondData->biggerDiamondimage;
								}
								?>
								<img src="<?php echo $imageurl; ?>" alt="<?php echo $diamondData->shape . ' ' . $diamondData->carat; ?> CARAT" title="<?php echo $diamondData->shape . ' ' . $diamondData->carat; ?> CARAT" class="main-diamond-img">
								<?php if ($diamondData->hasVideo && preg_match('/^.*\.(mp4|mov)$/i', $diamondData->videoFileName)) { ?>
									<div style="display:none;"><?php echo $diamondData->videoFileName . "<pre>";
																//print_r($diamondData); ?></div>
									<video class="diamond_video" width="" height="" autoplay="" loop="" muted="" playsinline="" style="display: none;">
										<source src="<?php //echo $diamondData->videoFileName; 
														?>" type="video/mp4">
									</video>
								<?php } ?>
							</div>
							<div class="product-details">
								<div class="product-item-name"><a href="<?php echo $diamondviewurl; ?>" onclick="SetBackValue('<?php echo $diamondData->diamondId; ?>');" title="View Diamond"><span><?php echo $diamondData->shape; ?> <strong><?php echo $diamondData->carat; ?></strong> CARAT</span>
										<span><?php echo $color_to_display; ?>, <?php echo $diamondData->clarity; ?>, <?php echo $diamondData->cut; ?></span>
									</a>
								</div>
								<?php //$strprice=[".00", ","]; $strreplaceprice=["", ""]; ?>
								<div class="product-box-pricing"><a href="<?php echo $diamondviewurl; ?>" title="View Diamond"><span>
                                    <?php
         								$dprice = $diamondData->fltPrice;
										$dprice = str_replace(',', '', $dprice);
									
									if ($diamondData->showPrice == true) { ?>
									<?php if($options['price_row_format'] == 'left'){

											if($diamondData->currencyFrom == 'USD'){

												echo "$".number_format($dprice); 

											}else{

												echo number_format($dprice).' '.$diamondData->currencySymbol.' '.$diamondData->currencyFrom;

											}

											}else{

  											if($diamondData->currencyFrom == 'USD'){

												echo "$".number_format($dprice); 

  											}else{

												echo $diamondData->currencyFrom.' '.$diamondData->currencySymbol.' '.number_format($dprice);   

  											}

										}
									} else {
										echo 'Call For Price';
									} ?>
									
									</span></a>
								</div>
								<div class="product-box-action">
									<input type="checkbox" name="compare" value="<?php echo $diamondData->diamondId; ?>">
									<div class="state"><label>Add to compare</label></div>
								</div>
							</div>
							<div class="product-video-icon" style="display:none;"><?php if ($diamondData->hasVideo == true && preg_match('/^.*\.(mp4|mov)$/i', $diamondData->videoFileName)) { ?><span title="Video" class="videoicon">
										<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="29px" height="17px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
											<g>
												<g>
													<path d="M387.118,500.728c0,0,55.636,0,55.636-55.637V166.909c0-55.637-55.636-55.637-55.636-55.637H55.636
		                  c0,0-55.636,0-55.636,55.637v278.182c0,55.637,55.636,55.637,55.636,55.637H387.118z" />
													<polygon points="475.162,219.958 475.162,393.043 612,500.728 612,111.272    " />
												</g>
											</g>
										</svg>
									</span> <?php } ?>
							</div>
							<div class="product-slide-button"><a href="javascript:void(0)" class="trigger-info">menu</a>
							</div>
							<div class="product-inner-info">
								<ul>
									<li>
										<p><span>Diamond ID </span><span><?php echo $diamondData->diamondId; ?></span>
										</p>
									</li>
									<li>
										<p><span>Shape</span><span><?php echo $diamondData->shape; ?></span></p>
									</li>
									<li>
										<p>
											<span>Carat</span><span><?php echo ($diamondData->carat) ? $diamondData->carat : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Color</span><span><?php echo ($color_to_display) ? $color_to_display : '-'; ?></span>
										</p>
									</li>
									<?php if ($diamondData->fancyColorMainBody) { ?>
									<li>
										<p>
											<span>Intensity</span><span><?php echo ($Intensity_to_display) ? $Intensity_to_display : '-'; ?></span>
										</p>
									</li>
									<?php } ?>
									<li>
										<p>
											<span>Clarity</span><span><?php echo ($diamondData->clarity) ? $diamondData->clarity : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Cut</span><span><?php echo ($diamondData->cut) ? $diamondData->cut : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Depth</span><span><?php echo ($diamondData->depth) ? $diamondData->depth . '%' : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Table</span><span><?php echo ($diamondData->table) ? $diamondData->table . '%' : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Polish</span><span><?php echo ($diamondData->polish) ? $diamondData->polish : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Symmetry</span><span><?php echo ($diamondData->symmetry) ? $diamondData->symmetry : '-'; ?></span>
										</p>
									</li>
									<li>
										<p><span>Measurement</span><span><?php echo $diamondData->measurement; ?></span>
										</p>
									</li>
									<?php if ($show_Certificate_in_Diamond_Search) { ?>
										<li>
											<p><span>Certificate</span><span><a href="javascript:;" onclick="javascript:window.open('<?php echo $diamondData->certificateUrl; ?>','CERTVIEW','scrollbars=yes,resizable=yes,width=860,height=550')"><?php echo $diamondData->cert; ?></a></span>
											</p>
										</li>
									<?php } ?>
									<?php if ($show_in_house_column) { ?>
										<li>
											<p><span>In House</span><span><?php echo $diamondData->inhouse; ?></span></p>
										</li>
									<?php } ?>
									<li>
										<p>
											<span>Price</span><span>
											<?php if ($diamondData->showPrice == true) { 
													echo ($diamondData->currencyFrom != 'USD') ? $diamondData->currencyFrom . number_format($diamondData->fltPrice) : $diamondData->currencySymbol . number_format($diamondData->fltPrice); 
												} else {
													echo 'Call For Price';
											} ?>
										</span>
										</p>
									</li>
								</ul>
							</div>
							<input type="hidden" name="diamondtype" id="diamondtype-<?php echo $diamondData->diamondId; ?>" value="<?php echo $type; ?>">
							<input type="hidden" name="diamondshape" id="diamondshape-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->shape; ?>">
							<input type="hidden" name="diamondsku" id="diamondsku-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->diamondId; ?>">
							<input type="hidden" name="diamondcarat" id="diamondcarat-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->carat; ?>">
							<input type="hidden" name="diamondtable" id="diamondtable-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->table; ?>">
							<input type="hidden" name="diamondcolor" id="diamondcolor-<?php echo $diamondData->diamondId; ?>" value="<?php echo $color_to_display; ?>">
							<input type="hidden" name="diamondintensity" id="diamondintensity-<?php echo $diamondData->diamondId; ?>" value="<?php echo $Intensity_to_display; ?>">
							<input type="hidden" name="diamondpolish" id="diamondpolish-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->polish; ?>">
							<input type="hidden" name="diamondsymm" id="diamondsymm-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->symmetry; ?>">
							<input type="hidden" name="diamondclarity" id="diamondclarity-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->clarity; ?>">
							<input type="hidden" name="diamondflr" id="diamondflr-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->fluorescence; ?>">
							<input type="hidden" name="diamonddepth" id="diamonddepth-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->depth; ?>">
							<input type="hidden" name="diamondmeasure" id="diamondmeasure-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->measurement; ?>">
							<input type="hidden" name="diamondcerti" id="diamondcerti-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->cert; ?>">
							<input type="hidden" name="diamondcutgrade" id="diamondcutgrade-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondData->cut; ?>">
							<?php
							if ($filter_type == 'navfancycolored') {
								$diamondImage = $diamondData->diamondImage;
							} else {
								$diamondImage = $diamondData->biggerDiamondimage;
							}
							?>
							<?php if ($diamondData->showPrice == true) {
								$diamondPrice =  $diamondData->price;
							}else{
								$diamondPrice = 'Call For Price';
							}
							?>
							<input type="hidden" name="diamondimage" id="diamondimage-<?php echo $diamondData->diamondId; ?>" value="<?php echo $imageurl; ?>">
							<input type="hidden" name="diamondprice" id="diamondprice-<?php echo $diamondData->diamondId; ?>" value="<?php echo $diamondPrice; ?>">
						</div>
					<?php } ?>
				</div>
			</div>
			<div class='grid-paginatin' style='text-align:center;'>
				<div class='btn-compare'><a href='javaScript:void(0)' onclick="SetBackValue();" id='compare-main'>Compare(<span id="totaldiamonds1">0</span>)
				</a>
				</div>
				<?php
				$current = 1;
				$number  = $diamond['perpage'];
				$pages   = ceil($diamond['pagination']['total'] / $number);
				if ($diamond['pagination']['currentpage'] > 1) {
					$current = $diamond['pagination']['currentpage'];
				}
				if ($current - 1 == 0) {
					$value = 1;
				} else {
					$value = $current - 1;
				}
				?>
				<div class='pagination-div pagination_scroll'>
					<input type="hidden" name="tool_version" value="Version 2.7.0">
					<ul>
						<li class="grid-next-double">
							<a href="javascript:void(0);" onclick="PagerClick('1');"></a>
						</li>
						<li data-toggle="tooltip" data-placement="bottom" title="Previous" <?= ($current == 1) ? 'class="disabled grid-next"' : 'class="grid-next"' ?>>
							<a href="javascript:void(0);" <?php if (($current - 1) != 0) { ?> onclick="PagerClick('<?php echo ($value) ?>');" <?php } ?>><?php echo ($value) ?></a>
						</li>
						<?php
						$lastPageNumber = '';
						for ($i = 1; $i <= $pages; $i++) {
							if ($i <> $current) {
								if ($i >= $current + 3) {
									continue;
								}
								if ($i <= $current - 3) {
									continue;
								}
						?>
								<li>
									<a href='javascript:void(0);' onclick='PagerClick("<?php echo $i; ?>");'><?php echo $i; ?></a>
								</li>
							<?php
							} else {
							?>
								<li class='active'>
									<a href='javascript:void(0);' class='active' onclick='PagerClick("<?php echo $i; ?>");'><?php echo $i; ?></a>
								</li>
						<?php
							}
						}
						?>
						<li data-toggle="tooltip" data-placement="bottom" title="Next" <?= ($current == $pages) ? 'class="disabled grid-previous"' : 'class="grid-previous"' ?>>
							<a href="javascript:void(0);" <?php if ($current != $pages) { ?> onclick="PagerClick('<?php echo ($current + 1); ?>');" <?php } ?>><?php echo ($current + 1); ?></a>
						</li>


						<li class="grid-previous-double">
							<a href="javascript:void(0);" onclick="PagerClick('<?php echo $pages; ?>');"></a>
						</li>
					</ul>
				</div>
				<?php
				if ($current == 1) {
					$from = 1;
					$to   = $number;
				} else {
					$from = (($current - 1) * $number) + 1;
					$to   = ($current * $number);
				}
				if ($diamond['pagination']['total'] < $to) {
					$to = $diamond['pagination']['total'];
				}
				?>
				<div class='page-checked'>
					<div class='result-bottom'>Results <?php echo number_format($from); ?>
						to <?php echo number_format($to); ?>
						of <?php echo number_format($diamond['pagination']['total']); ?> </div>
				</div>
			</div>
		</div>
		</div>
	<?php else : ?>
		<div class='search-details no-padding'>
			<div class='searching-result'>
				<div class='number-of-search'>
					<p><strong><?php echo number_format($diamond['pagination']['total']); ?></strong>Similar Diamonds
					</p>
				</div>
				<div class='search-in-table' id='searchintable'>
					<input type='text' name='searchdidfield' id='searchdidfield' placeholder='Search Diamond Stock #'><a href='javascript:;' title='close' id='resetsearchdata'>X</a>
					<button id='searchdid' title='Search Diamond'></button>
				</div>
				<div class='view-or-search-result'>
					<div class='change-view-result'>
					
						<ul>
							<li class='grid-view'><a href='javascript:;' class='<?php echo (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'grid') ? 'active' : ''; ?>'>Grid view</a></li>
							<li class='list-view'><a href='javascript:;' class="<?php echo (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'list') ? 'active' : '';echo $class ?>">list view</a></li>
						</ul>

						<div class='item-page'>
							<p class='leftpp'>Per Page</p>
							<select class='pagesize SumoUnder' id='pagesize' name='pagesize' onchange='ItemPerPage(this)' tabindex='-1'>
								<?php
								$all_options = getAllOptions_dl();
								foreach ($all_options as $value) {
								?>
									<option value='<?php echo $value['value']; ?>'><?php echo $value['label']; ?></option>
								<?php
								}
								?>
							</select>
						</div>
						<div class='grid-view-sort cls-for-hide'>
							<select name='gridview-orderby' id='gridview-orderby' class='gridview-orderby SumoUnder' onchange='gridSort(this)' tabindex='-1'>
								<option value='Cut'>Shape</option>
								<option value='Size'>Carat</option>
								<option value='Color'>Color</option>
								<option value='ClarityID'>Clarity</option>
								<option value='CutGrade'>Cut</option>
								<option value='Depth'>Depth</option>
								<option value='TableMeasure'>Table</option>
								<option value='Polish'>Polish</option>
								<option value='Symmetry'>Symmetry</option>
								<option value='Measurements'>Measurement</option>
								<?php if ($show_Certificate_in_Diamond_Search) { ?>
									<option value='Certificate'>Certificate</option>
								<?php } ?>
								<?php if ($show_in_house_column) { ?>
									<option value='Inhouse'>In House</option>
								<?php } ?>
								<option value='FltPrice' selected='selected'>Price</option>
							</select>
							<div class='gridview-dir-div'>
								<a href='javascript:;' onclick='gridDire("DESC")' id='ASC' title='Set Descending Direction' class='active'>ASC</a><a href='javascript:;' title='Set Ascending Direction' onclick='gridDire("ASC")' id='DESC'>DESC</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="search-details no-padding no-result-main">
			<div class="searching-result no-result-div">
				<?php _e('No Data Found.', 'gemfind-diamond-link') ?>
			</div>
		</div>
	<?php endif; ?>


	<div id="myDbModal" class="Dbmodal">
		<!-- Modal content -->
		<div class="Dbmodal-content">

			<span class="Dbclose">&times;</span>
			<div class="loader_rb" style="display: none;">
				<img src="<?php echo plugin_dir_url( __FILE__ ) . "assets/images/diamond_rb.gif" ?>" style="width: 200px; height: 200px;">
			</div>
			<iframe src="" id="iframevideodb" scrolling="no" style="width:100%; height:98%;" allow="autoplay"></iframe>
			<video style="width:100%; height:90%;" id="mp4video" loop autoplay>
            <source src=""  type="video/mp4">
        </video>
		</div>
	</div>

	<!-- Additional Information Modal -->
	<div id="dl-diamondInfoModal" class="dl-modal">
	    <div class="dl-modal-content">
	        <span class="dl-close">&times;</span>
	        <h2>Additional Information</h2>
	        <table id="dl-diamond-info-table">	           
	        </table>
	    </div>
	</div>


	<script type="text/javascript">
		jQuery(document).ready(function() {

			//SET TOTAL DIAMOND ON LOAD
			var cookiesarraylenn = compareItemsarrayrb.length
			if(JSON.parse(localStorage.getItem("compareItemsrb"))){
               var localStoragedataload = JSON.parse(localStorage.getItem("compareItemsrb")).length;  
            }
            else{
                var localStoragedataload = 0;
            }

            var totalcntarray = cookiesarraylenn + localStoragedataload;
            jQuery('#totaldiamonds').text(totalcntarray);
            jQuery('#totaldiamonds1').text(totalcntarray);

			var backdid = jQuery('#backdiamondid').val();
			if (backdid) {
				jQuery('.search-details #list-mode #' + backdid).addClass('selected_row');
				setTimeout(function() {
					ResetBackCookieFilter();
					jQuery('#backdiamondid').val("");
				}, 800);
			}
			var is_enable_sticky = jQuery('#sticky_header').val();
			if (is_enable_sticky == 'yes' && jQuery('.table_header_wrapper').length) {
				var stickyTop = jQuery('.table_header_wrapper').offset().top;
				jQuery(window).scroll(function() {
					var windowTop = jQuery(window).scrollTop();
					if (stickyTop < windowTop) {
						jQuery('.table_header_wrapper').addClass('fixed-table-head');
					} else {
						jQuery('.table_header_wrapper').removeClass('fixed-table-head');
					}
				});
			}

			jQuery('[data-toggle="tooltip"]').tooltip({
				trigger: "hover",
				'show': true
			});
			var viewmode = jQuery('#viewmode').val();
			var headerheight = jQuery('header').outerHeight() + jQuery('#top-header').outerHeight();
			if (viewmode == '' || viewmode == 'list') {
				if (jQuery('#list-mode tbody tr').hasClass('selected_row')) {
					setTimeout(function() {
						jQuery('html, body').animate({
							scrollTop: jQuery('.selected_row').offset().top - headerheight
						}, 1000);
					}, 100);
				}
			} else {
				if (jQuery('#grid-mode .grid-product-listing div').hasClass('selected_grid')) {
					setTimeout(function() {
						jQuery('html, body').animate({
							scrollTop: jQuery('#grid-mode .selected_grid').offset().top - headerheight
						}, 1000);
					}, 100);
				}
			}
		});

		jQuery('#compare-main').unbind().click(function() {
			console.log('form is submitting');
			compareDiamonds_dl();
		});
		jQuery('#comparetop').unbind().click(function() {
			console.log('form is submitting');
			compareDiamonds_dl();
		});

		jQuery("input:checkbox[name=compare]").click(function() {
			var selectedcheckboxidrb = jQuery(this).val();
			var checkbox = jQuery(this).is(':checked');	

			if (checkbox == true) {

                var maxAllowed = 5;
	            var cnt = compareItemsarrayrb.length;
	            if(JSON.parse(localStorage.getItem("compareItemsrb"))){
	               var localStoragedatarb = JSON.parse(localStorage.getItem("compareItemsrb")).length;  
	            }
	            else{
	                var localStoragedatarb = 0;
	            }
	            var totalcnt = cnt + localStoragedatarb;
	            if (totalcnt > maxAllowed) {
	                jQuery(this).prop("checked", "");
	                alert('You can select a maximum of 6 diamonds to compare! Please check your compare item page you have some items in your compare list.');
	                 return false;
	            }

				compareItemsarrayrb.push(selectedcheckboxidrb);
				var datacomparerb = {};
				datacomparerb.Image = jQuery("#diamondimage-" + selectedcheckboxidrb).val(), datacomparerb.Shape = jQuery("#diamondshape-" + selectedcheckboxidrb).val(), datacomparerb.Type = jQuery("#diamondtype-" + selectedcheckboxidrb).val(), datacomparerb.Sku = jQuery("#diamondsku-" + selectedcheckboxidrb).val(), datacomparerb.Carat = jQuery("#diamondcarat-" + selectedcheckboxidrb).val(), datacomparerb.Table = jQuery("#diamondtable-" + selectedcheckboxidrb).val(), datacomparerb.Color = jQuery("#diamondcolor-" + selectedcheckboxidrb).val(), datacomparerb.Polish = jQuery("#diamondpolish-" + selectedcheckboxidrb).val(), datacomparerb.Symmetry = jQuery("#diamondsymm-" + selectedcheckboxidrb).val(), datacomparerb.Clarity = jQuery("#diamondclarity-" + selectedcheckboxidrb).val(), datacomparerb.Fluorescence = jQuery("#diamondflr-" + selectedcheckboxidrb).val(), datacomparerb.Depth = jQuery("#diamonddepth-" + selectedcheckboxidrb).val(), datacomparerb.Measurement = jQuery("#diamondmeasure-" + selectedcheckboxidrb).val(), datacomparerb.Cert = jQuery("#diamondcerti-" + selectedcheckboxidrb).val(), datacomparerb.Cut = jQuery("#diamondcutgrade-" + selectedcheckboxidrb).val(), datacomparerb.Price = jQuery("#diamondprice-" + selectedcheckboxidrb).val(), datacomparerb.ID = jQuery("#diamondsku-" + selectedcheckboxidrb).val();
				compareItemsrb.push(datacomparerb);

				var total_diamonds = compareItemsarrayrb.length + localStoragedatarb;
				if (total_diamonds  <=  6) {
					jQuery('#totaldiamonds').text(total_diamonds);
					jQuery('#totaldiamonds1').text(total_diamonds);
				}
				// console.log(compareItemsarrayrb);
				// console.log(datacomparerb);
			} else {
				if(JSON.parse(localStorage.getItem("compareItemsrb"))){
	               var localStoragedatarb = JSON.parse(localStorage.getItem("compareItemsrb")).length;  
	            }
	            else{
	                var localStoragedatarb = 0;
	            }

				compareItemsarrayrb.pop(selectedcheckboxidrb);
				var total_diamonds = compareItemsarrayrb.length + localStoragedatarb;				
				jQuery('#totaldiamonds').text(total_diamonds);
				jQuery('#totaldiamonds1').text(total_diamonds);				
				
				jQuery.each(compareItemsrb, function(key, value) {
					if(value){
					if (selectedcheckboxidrb == value.ID) {
						compareItemsrb.splice(key, 1);
					}
					}
				});
			}
		});

		function compareDiamonds_dl() {
			var count = compareItemsrb.length;
			var shop_url = '<?php echo get_site_url(); ?>';
			if (count > 0) {
				var expire = new Date();
				expire.setDate(expire.getDate() + 0.2 * 24 * 60 * 60 * 1000);
				var compareClickCount = localStorage.getItem("compareItemsrbClick");

				var finalItems = [];
				if (compareClickCount == '1') {
					const compareItemsNew = JSON.parse(localStorage.getItem("compareItemsrb"));
					finalItems = compareItemsNew.concat(compareItemsrb);

					jQuery.cookie("comparediamondProductrb", JSON.stringify(finalItems), {
						path: '/',
						expires: expire
					});

					localStorage.setItem("compareItemsrb", JSON.stringify(finalItems));
				} else {
					finalItems = compareItemsrb;

					jQuery.cookie("comparediamondProductrb", JSON.stringify(finalItems), {
						path: '/',
						expires: expire
					});
				}
				
				if (compareClickCount == null) {
					localStorage.setItem("compareItemsrbClick", 1);
					localStorage.setItem("compareItemsrb", JSON.stringify(finalItems));
				}
				window.location.href = shop_url + '/ringbuilder/diamondlink/compare';
			} else {
				if(JSON.parse(localStorage.getItem("compareItemsrb"))){
					window.location.href = shop_url + '/ringbuilder/diamondlink/compare';
				}else{
				  alert('Please select minimum 2 diamonds to compare.');
	     		}
	     		document.querySelector('#navcompare').classList.remove('active');
			}
		}

		// function compareDiamonds_dl() {
		// 	var compareItems = [];
		// 	var count = jQuery("input[name='compare']:checked").length;
		// 	var shop_url = ';
		// 	if (count > 1) {
		// 		jQuery("input:checkbox[name=compare]:checked").each(function () {
		// 			var selecteddid = jQuery(this).val();
		// 			var data = {};
		// 			data.Image = jQuery("#diamondimage-" + selecteddid).val(), data.Shape = jQuery("#diamondshape-" + selecteddid).val(), data.Type = jQuery("#diamondtype-" + selecteddid).val(), data.Sku = jQuery("#diamondsku-" + selecteddid).val(), data.Carat = jQuery("#diamondcarat-" + selecteddid).val(), data.Table = jQuery("#diamondtable-" + selecteddid).val(), data.Color = jQuery("#diamondcolor-" + selecteddid).val(), data.Polish = jQuery("#diamondpolish-" + selecteddid).val(), data.Symmetry = jQuery("#diamondsymm-" + selecteddid).val(), data.Clarity = jQuery("#diamondclarity-" + selecteddid).val(), data.Fluorescence = jQuery("#diamondflr-" + selecteddid).val(), data.Depth = jQuery("#diamonddepth-" + selecteddid).val(), data.Measurement = jQuery("#diamondmeasure-" + selecteddid).val(), data.Cert = jQuery("#diamondcerti-" + selecteddid).val(), data.Cut = jQuery("#diamondcutgrade-" + selecteddid).val(), data.Price = jQuery("#diamondprice-" + selecteddid).val(), data.ID = jQuery("#diamondsku-" + selecteddid).val();
		// 			compareItems.push(data);
		// 		});
		// 		var expire = new Date();
		// 		expire.setDate(expire.getDate() + 0.2 * 24 * 60 * 60 * 1000);
		// 		jQuery.cookie("comparediamondProductrb", JSON.stringify(compareItems), {
		// 			path: '/',
		// 			expires: expire
		// 		});
		// 		window.location.href = shop_url + '/ringbuilder/diamondlink/compare';
		// 	} else {
		// 		alert('Please select minimum 2 diamonds to compare.');
		// 	}
		// }


		jQuery("span.imagecheck").each(function() {
			var id = jQuery(this).attr("data-id");
			if (jQuery('input#viewmode').val() == 'list') {
				var src = jQuery(this).attr("data-src");
				imageExists_dl(src, function(exists) {
					if (exists) {
						jQuery('tr#' + id + ' td img').attr('src', src);
						//jQuery('div#' + id + ' div.product-images img').attr('src', src);
						//jQuery('input#diamondimage-' + id).val(src);
					} else {
						jQuery('tr#' + id + ' td img').attr('src', '<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
						jQuery('div#' + id + ' div.product-images img').attr('src', '<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
						jQuery('input#diamondimage-' + id).val('<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
					}
				});
			} else {
				if (jQuery('input#filtermode').val() == 'navfancycolored') {
					var src = jQuery(this).attr("data-src");
					imageExists_dl(src, function(exists) {
						if (exists) {
							jQuery('tr#' + id + ' td img').attr('src', src);
						} else {
							jQuery('tr#' + id + ' td img').attr('src', '<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
							jQuery('div#' + id + ' div.product-images img').attr('src', '<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
							jQuery('input#diamondimage-' + id).val('<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
						}
					});
				} else {
					var src = jQuery(this).attr("data-srcbig");
					imageExists_dl(src, function(exists) {
						if (exists) {
							jQuery('tr#' + id + ' td img').attr('src', src);
						} else {
							jQuery('tr#' + id + ' td img').attr('src', '<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
							jQuery('div#' + id + ' div.product-images img').attr('src', '<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
							jQuery('input#diamondimage-' + id).val('<?php echo RING_BUILDER_URL ?>/assets/images/no-image.jpg');
						}
					});
				}
			}
		});

		function imageExists_dl(url, callback) {
			var img = new Image();
			img.onload = function() {
				callback(true);
			};
			img.onerror = function() {
				callback(false);
			};
			img.src = url;
		}

		jQuery('.pagination_scroll').click(
        function(e) {
            jQuery('html, body').animate({
                scrollTop: jQuery('#noui_carat_slider').position().top
            }, 800);
        });


		//Grid View
		// jQuery('.search-product-grid').each(function(num,val){
		//   var divid = jQuery(this).attr('id');
		//   var videosrc = jQuery('#grid-mode #'+divid+' div.product-images video source').attr('src');      
		//     jQuery.ajax({
		//       url: myajax.ajaxurl,
		//       data: {'action':'checkvideo_dl', 'setting_video_url': videosrc},
		//       type: 'POST',          
		//       //dataType: 'json',
		//       cache: true,
		//       success: function(response) {
		//           if(response != 1){
		//             jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-video').remove();
		// jQuery('#grid-mode #'+divid+' div.product-video-icon').show();
		//           }else{
		//             jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-video').remove();
		//             jQuery('#grid-mode #'+divid+' div.product-images video').remove();
		// jQuery('#grid-mode #'+divid+' div.product-video-icon').remove();
		//           }
		//       }
		//   });
		// });

		//Added code for hover video
		// jQuery(".search-product-grid").mouseenter(function(){
		//     var divid = jQuery(this).attr('id');
		//     if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//     jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','none');
		//       jQuery('#grid-mode #'+divid+' div.product-images video').css('display','block');
		//     }else{
		//       jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//     }
		// });
		// jQuery(".search-product-grid").mouseleave(function(){
		//     var divid = jQuery(this).attr('id');
		//     if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//       jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//       jQuery('#grid-mode #'+divid+' div.product-images video').css('display','none');
		//     }
		// });
		//Mobile event for video
		// jQuery(".search-product-grid").on("touchstart",function(){
		//        var divid = jQuery(this).attr('id');
		//        if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','none');
		//          jQuery('#grid-mode #'+divid+' div.product-images video').css('display','block');
		//        }else{
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//        }
		//    });
		// jQuery(".search-product-grid").on("touchend",function(){
		//        var divid = jQuery(this).attr('id');
		//        if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//          jQuery('#grid-mode #'+divid+' div.product-images video').css('display','none');
		//        }
		//    });

		var videoList = document.getElementsByTagName("video");
		jQuery(videoList).on('loadstart', function(event) {
			jQuery(this).addClass('loading');
		});
		jQuery(videoList).on('canplay', function(event) {
			jQuery(this).removeClass('loading');
		});

		function showModaldb() {
			// jQuery("#iframevideodb").removeAttr("src");
			// jQuery('#myDbModal').modal('show');
			// jQuery('.loader_rb').show();
            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');                                

			jQuery("#iframevideodb").removeAttr("src");
            jQuery("#mp4video").removeAttr("src");
        	jQuery("#mp4video").attr("src", '');
            jQuery('#myDbModal').modal('show');
            jQuery('.loader_rb').show();

			var divid = jQuery(event.currentTarget).data('id');
			jQuery.ajax({
				type: "POST",
				url: myajax.ajaxurl,
				data: {
					action: 'getdiamondvideos',
					product_id: divid
				},
				cache: true,
				success: function(response) {
					response = JSON.parse(response);					
					 if (response.showVideo == true) {
	                    var fileExtension = response.videoURL.replace(/^.*\./, '');
	                    console.log (fileExtension);
	                    if(fileExtension=="mp4"){
	                        jQuery('#iframevideodb').hide();
	                        setTimeout(function() {
	                           jQuery("#mp4video").attr("src", response.videoURL);
	                           jQuery('.loader_rb').hide();
	                           jQuery('#mp4video').get(0).play();
	                        }, 3000);
	                    }else{
	                    	jQuery('#mp4video').hide();
	                    	setTimeout(function() {
	                            jQuery("#iframevideodb").attr("src", response.videoURL);
	                            jQuery('.loader_rb').hide();
	                            jQuery('#iframevideodb').show();
	                        }, 3000);
	                    }    
                    }
				}
			});
		}
		jQuery(".Dbclose").click(function() {
			jQuery('#myDbModal').modal('hide');
		});

		function onMouseOverMoreInfo(element) {
		    const iconList = element.querySelector('.icon-list');
		    if (iconList) {
		        iconList.classList.add('show'); // Show the icon list
		    }
		}

		function onMouseOutMoreInfo(element) {
		    const iconList = element.querySelector('.icon-list');
		    if (iconList) {
		        iconList.classList.remove('show'); // Hide the icon list
		    }
		}


		var modal = document.getElementById("dl-diamondInfoModal");

		function showAdditionalInformation(diamondId) {
		    var domain = "<?php echo $_SERVER['HTTP_HOST']; ?>";

		    // Show the global loader
		    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
		    document.body.classList.add("dl-AddInfoModal");
		    
		    // Check if on the diamond detail page
		    const isDetailPage = window.location.pathname.includes('/diamondlink/product/');
		    let diamondType = 'NA';

		    if (!isDetailPage) {
		        diamondType = document.getElementById('diamondtype-' + diamondId).value;
		    }

		    $.ajax({
		        type: "POST",
		        url: myajax.ajaxurl,
		        data: {
		            action: 'getDiamondDetails',
		            id: diamondId,
		            shop: "https://" + domain,
		            type: diamondType,
		        },
		        success: function(response) {
		            // Parse the JSON response
		            var data = JSON.parse(response);
		            var diamondData = data.diamondData;

		            // Reference to the table element
		            var table = document.getElementById("dl-diamond-info-table");

		            // Create table rows dynamically based on the diamondData object
		            table.innerHTML = `
		                <tr><th>Diamond ID</th><td>${diamondData.diamondId}</td></tr>             
		                <tr><th>Shape</th><td>${diamondData.shape || 'N/A'}</td></tr>
		                <tr><th>Carat Weight</th><td>${diamondData.caratWeight || 'N/A'}</td></tr>               
		                <tr><th>Color</th><td>${diamondData.color || 'N/A'}</td></tr>
		                <tr><th>Clarity</th><td>${diamondData.clarity || 'N/A'}</td></tr>
		                <tr><th>Cut Grade</th><td>${diamondData.cutGrade || 'N/A'}</td></tr>
		                <tr><th>Depth</th><td>${diamondData.depth || 'N/A'}</td></tr>
		                <tr><th>Table</th><td>${diamondData.table || 'N/A'}</td></tr>
		                <tr><th>Polish</th><td>${diamondData.polish || 'N/A'}</td></tr>
		                <tr><th>Certificate</th><td>${diamondData.certificate || 'N/A'}</td></tr>
		                <tr><th>Measurement</th><td>${diamondData.measurement || 'N/A'}</td></tr>
		                <tr><th>Price</th><td>${diamondData.fltPrice || 'N/A'}</td></tr>              
		            `;
		            
		            // Show the modal
		            modal.style.display = "block";

		            // Hide the global loader
		            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
		        },
		        error: function() {
		            // Handle errors if needed
		            alert("An error occurred while fetching the diamond details.");
		            
		            // Hide the global loader even if there's an error
		            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
		        }
		    });
		}


		// Event listener to close the modal when the close button is clicked
	    var closeModal = document.getElementsByClassName("dl-close")[0];

	    closeModal.onclick = function() {
	        modal.style.display = "none";
	        document.body.classList.remove("dl-AddInfoModal");
	    };

	    // Event listener to close the modal when clicking outside of the modal content
	    window.onclick = function(event) {
	        if (event.target == modal) {
	            modal.style.display = "none";
	        	document.body.classList.remove("dl-AddInfoModal");
	        }
	    };

	</script>
<?php
	die();
}

add_action('wp_ajax_nopriv_checkvideo_dl', 'checkvideo_dl');
add_action('wp_ajax_checkvideo_dl', 'checkvideo_dl');
function checkvideo_dl()
{
	$setting_video_url = '';
	$setting_video_url = $_POST['setting_video_url'];
	$headers = is_404($setting_video_url);
	echo $headers;
	exit;
}

/**
 * For diamondHint form email.
 */
function resultdrophint_dl()
{
	$form_data      = $_POST['form_data'];
	$all_options    = getOptions_dl();
	$hint_post_data = array();
	$alldata = get_option('gemfind_ring_builder');
	$diamondsoption    = sendRequest_dl(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	foreach ($form_data as $data) {
		$hint_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}
	if ($hint_post_data && $hint_post_data['email'] != "" && $hint_post_data['recipient_email'] != "") {
		try {
			$ringData = getRingById_rb($hint_post_data['settingid'], $hint_post_data['shopurl']);
			$diamond   = getDiamondById_dl($hint_post_data['diamondid'], $hint_post_data['diamondtype'], $hint_post_data['shopurl']);
			if ($all_options['admin_email_address'] != "") {
				$retaileremail = $all_options['admin_email_address'];
			} else {
				$retaileremail = $ringData['ringData']['vendorEmail'];
			}

			$certificateUrl  = (isset($diamond['diamondData']['certificateUrl'])) ? ' <a href=' . $diamond['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';
			$certificate   = (isset($diamond['diamondData']['certificate'])) ? $diamond['diamondData']['certificate'] : 'Not Available';
			$certificateNo = (isset($diamond['diamondData']['certificateNo'])) ? $diamond['diamondData']['certificateNo'] : '';

			if (!empty($certificateUrl) && !empty($show_Certificate_in_Diamond_Search)) {
				$labColumn = '<tr>
		              <td class="consumer-title">Lab:</td>
		              <td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
		            </tr>';
			} else {
				$labColumn = '';
			}

			$complateRingpage = 'complateRingpage';
			$retailername  = $ringData['ringData']['vendorName'];
			$templateVars  = array(
				'retailername'    => $retailername,
				'retailerphone'   => $ringData['ringData']['vendorPhone'],
				'name'            => $hint_post_data['name'],
				'email'           => $hint_post_data['email'],
				'recipient_name'  => $hint_post_data['recipient_name'],
				'recipient_email' => $hint_post_data['recipient_email'],
				'gift_reason'     => $hint_post_data['gift_reason'],
				'hint_message'    => $hint_post_data['hint_message'],
				'gift_deadline'   => $hint_post_data['gift_deadline'],
				'ring_url'        => $hint_post_data['diamondurl'],
				'labColumn'   	  => (isset($labColumn)) ? $labColumn : '',
			);
			// Sender email
			$transport_sender_template = diamondhint_email_template_sender($complateRingpage);
			$senderValueReplacement    = array(
				'{{shopurl}}'         => $shopurl,
				'{{shop_logo}}'       => $store_logo,
				'{{shop_logo_alt}}'   => $store_detail->shop->name,
				'{{name}}'            => $hint_post_data['name'],
				'{{gift_reason}}'     => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'   => $hint_post_data['gift_deadline'],
				'{{hint_message}}'    => $hint_post_data['hint_message'],
				'{{ring_url}}'        => $hint_post_data['diamondurl'],
				'{{retailername}}'    => $retailername,
				'{{retailerphone}}'   => $ringData['ringData']['vendorPhone'],
				'{{recipient_email}}' => $hint_post_data['recipient_email'],
				'{{diamond_url}}'	  => $_SERVER['HTTP_REFERER'],
			);
			$sender_email_body = str_replace(array_keys($senderValueReplacement), array_values($senderValueReplacement), $transport_sender_template);
			$sender_subject    = "You Sent A Little Hint To " . $hint_post_data['recipient_name'];
			$senderFromAddress = $all_optinos['from_email_address'];
			$headers           = array('From: ' . $senderFromAddress . '');
			$senderToEmail     = $hint_post_data['email'];
			wp_mail($senderToEmail, $sender_subject, $sender_email_body, $headers);

			// Receiver email
			$transport_receiver_template = diamondhint_email_template_receiver($complateRingpage);
			$receiverValueReplacement    = array(
				'{{shopurl}}'        => $shopurl,
				'{{shop_logo}}'      => $store_logo,
				'{{shop_logo_alt}}'  => $store_detail,
				'{{recipient_name}}' => $hint_post_data['recipient_name'],
				'{{gift_reason}}'    => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'  => $hint_post_data['gift_deadline'],
				'{{hint_message}}'   => $hint_post_data['hint_message'],
				'{{ring_url}}'       => $hint_post_data['diamondurl'],
				'{{retailerphone}}'  => $ringData['ringData']['vendorPhone'],
			);
			$receiver_email_body = str_replace(array_keys($receiverValueReplacement), array_values($receiverValueReplacement), $transport_receiver_template);
			$receiver_subject     = "A Little Hint from " . $hint_post_data['name'];
			$receiver_fromAddress = $all_optinos['from_email_address'];
			$headers              = array('From: ' . $receiver_fromAddress . '');
			$receiver_toEmail     = $hint_post_data['recipient_email'];
			wp_mail($receiver_toEmail, $receiver_subject, $receiver_email_body, $headers);

			// Retailer email
			$transport_retailer_template = diamondhint_email_template_retailer($complateRingpage);
			$retailerValueReplacement    = array(
				'{{shopurl}}'         => $shopurl,
				'{{shop_logo}}'       => $store_logo,
				'{{shop_logo_alt}}'   => $store_detail->shop->name,
				'{{retailername}}'    => $retailername,
				'{{gift_reason}}'     => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'   => $hint_post_data['gift_deadline'],
				'{{hint_message}}'    => $hint_post_data['hint_message'],
				'{{ring_url}}'        => $hint_post_data['diamondurl'],
				'{{recipient_email}}' => $hint_post_data['recipient_email'],
				'{{name}}'            => $hint_post_data['name'],
				'{{email}}'           => $hint_post_data['email'],
				'{{recipient_name}}'  => $hint_post_data['recipient_name'],
				'{{labColumn}}'       => $templateVars['labColumn'],
			);
			$retailer_email_body = str_replace(array_keys($retailerValueReplacement), array_values($retailerValueReplacement), $transport_retailer_template);
			$retailer_subject     = "Someone Wants To Drop You A Hint";
			$retailer_fromAddress = $all_optinos['from_email_address'];
			$headers              = array('From: ' . $retailer_fromAddress . '');
			$retailer_toEmail     = $retaileremail;
			wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);
			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);
			$result  = json_encode(array('output' => $data));
			echo $result;
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
	}
	die();
}

add_action('wp_ajax_nopriv_resultdrophint_dl', 'resultdrophint_dl');
add_action('wp_ajax_resultdrophint_dl', 'resultdrophint_dl');

/**
 * For email a friend.
 */
function resultemailfriend_dl()
{
	$form_data              = $_POST['form_data'];
	$all_options            = getOptions_dl();
	$email_friend_post_data = array();
	$alldata = get_option('gemfind_ring_builder');
	$diamondsoption    = sendRequest_dl(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;

	foreach ($form_data as $data) {
		$email_friend_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}
	// if ($email_friend_post_data && $email_friend_post_data['email'] != "" && $email_friend_post_data['friend_email'] != "") {
	// 	try {
	// 		$ringData  = getRingById_rb($email_friend_post_data['settingid'], $email_friend_post_data['shopurl']);
	// 		$diamond   = getDiamondById_dl($email_friend_post_data['diamondid'], $email_friend_post_data['diamondtype'], $email_friend_post_data['shopurl']);

	// 		if ($all_options['admin_email_address'] != "") {
	// 			$retaileremail = $all_options['admin_email_address'];
	// 		} else {
	// 			$retaileremail = $ringData['ringData']['vendorEmail'];
	// 		}

	// 		$currency = ($ringData['ringData']['currencyFrom'] != 'USD') ? $ringData['ringData']['currencyFrom'] . $ringData['ringData']['currencySymbol'] . ' ' . number_format($ringData['ringData']['cost']) : $ringData['ringData']['currencySymbol'] . ' ' . number_format($ringData['ringData']['cost']);

	// 		$certificateUrl  = (isset($diamond['diamondData']['certificateUrl'])) ? ' <a href=' . $diamond['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';
	// 		$certificate   = (isset($diamond['diamondData']['certificate'])) ? $diamond['diamondData']['certificate'] : 'Not Available';
	// 		$certificateNo = (isset($diamond['diamondData']['certificateNo'])) ? $diamond['diamondData']['certificateNo'] : '';

	// 		if (!empty($certificateUrl) && !empty($show_Certificate_in_Diamond_Search)) {
	// 			$labColumn = '<tr>
	// 	              <td class="consumer-title">Lab:</td>
	// 	              <td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
	// 	            </tr>';
	// 		}
	// 		if (isset($ringData['ringData']['configurableProduct'][0]->sideStoneQuality) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality != "") {
	// 			$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
	// 			$sideStoneQualityhtm = '<tr>
	// 					                  <td class="consumer-title">Side Stone Quality:</td>
	// 					                  <td class="consumer-name">' . $sideStoneQuality . '</td>
	// 					                </tr>';
	// 		}
	// 		if (isset($email_friend_post_data['ringSize']) && $email_friend_post_data['ringSize'] != "") {
	// 			$ringSize = '<tr>
	// 					                  <td class="consumer-title">Ring Size:</td>
	// 					                  <td class="consumer-name">' . $email_friend_post_data['ringSize'] . '</td>
	// 					                </tr>';
	// 		}
	// 		$templateVars  = array(
	// 			'name'                => $email_friend_post_data['name'],
	// 			'email'               => $email_friend_post_data['email'],
	// 			'friend_name'         => $email_friend_post_data['friend_name'],
	// 			'friend_email'        => $email_friend_post_data['friend_email'],
	// 			'message'             => $email_friend_post_data['message'],
	// 			'ring_url'            => (isset($email_friend_post_data['ringurl'])) ? $email_friend_post_data['ringurl'] : '',
	// 			'setting_id'          => (isset($ringData['ringData']['settingId'])) ? $ringData['ringData']['settingId'] : '',
	// 			'stylenumber'         => (isset($ringData['ringData']['styleNumber'])) ? $ringData['ringData']['styleNumber'] : '',
	// 			'metaltype'           => (isset($ringData['ringData']['metalType'])) ? $ringData['ringData']['metalType'] : '',
	// 			'centerStoneSize'     => (isset($ringData['ringData']['configurableProduct'][0]->centerStoneSize)) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
	// 			'sideStoneQualityhtm' => $sideStoneQualityhtm,
	// 			'ringSize' 			  => $ringSize,
	// 			'centerStoneMinCarat' => (isset($ringData['ringData']['centerStoneMinCarat'])) ? $ringData['ringData']['centerStoneMinCarat'] : '',
	// 			'centerStoneMaxCarat' => (isset($ringData['ringData']['centerStoneMaxCarat'])) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
	// 			'price'               => (isset($ringData['ringData']['cost'])) ? $currency : '',
	// 			'retailerName'        => (isset($ringData['ringData']['vendorName'])) ? $ringData['ringData']['vendorName'] : '',
	// 			'retailerID'          => (isset($ringData['ringData']['retailerInfo']->retailerID)) ? $ringData['ringData']['retailerInfo']->retailerID : '',
	// 			'retailerEmail'       => (isset($ringData['ringData']['retailerInfo']->retailerEmail)) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
	// 			'retailerContactNo'   => (isset($ringData['ringData']['retailerInfo']->retailerContactNo)) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
	// 			'retailerFax'         => (isset($ringData['ringData']['retailerInfo']->retailerFax)) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
	// 			'retailerAddress'     => (isset($ringData['ringData']['retailerInfo']->retailerAddress)) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
	// 			'labColumn'   => (isset($labColumn)) ? $labColumn : '',
	// 		);

	// 		$templateValueReplacement = array(
	// 			'{{shopurl}}'             => $shopurl,
	// 			'{{shop_logo}}'           => $store_logo,
	// 			'{{shop_logo_alt}}'       => $store_detail->shop->name,
	// 			'{{name}}'                => $templateVars['name'],
	// 			'{{email}}'               => $templateVars['email'],
	// 			'{{friend_name}}'         => $templateVars['friend_name'],
	// 			'{{friend_email}}'        => $templateVars['friend_email'],
	// 			'{{message}}'             => $templateVars['message'],
	// 			'{{ring_url}}'            => $templateVars['ring_url'],
	// 			'{{diamond_url}}'		  => $_SERVER['HTTP_REFERER'],
	// 			'{{setting_id}}'          => $templateVars['setting_id'],
	// 			'{{stylenumber}}'         => $templateVars['stylenumber'],
	// 			'{{metaltype}}'           => $templateVars['metaltype'],
	// 			'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
	// 			'{{ringSize}}'     		  => $templateVars['ringSize'],
	// 			'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
	// 			'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
	// 			'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
	// 			'{{price}}'               => $templateVars['price'],
	// 			'{{labColumn}}'         => $templateVars['labColumn'],
	// 			'{{retailerID}}'          => $templateVars['retailerID'],
	// 			'{{retailerEmail}}'       => $templateVars['retailerEmail'],
	// 			'{{retailerContactNo}}'   => $templateVars['retailerContactNo'],
	// 			'{{retailerFax}}'         => $templateVars['retailerFax'],
	// 			'{{retailerAddress}}'     => $templateVars['retailerAddress'],
	// 			'{{retailerName}}'        => $templateVars['retailerName'],
	// 		);

	// 		// Admin email
	// 		$transport_sender_template = diamondemail_friend_email_template_sender($complateRingpage);
	// 		$sender_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_sender_template);
	// 		$sender_subject            = "A Friend Wants To Share With You";
	// 		$senderFromAddress         = $all_optinos['from_email_address'];
	// 		$headers                   = array('From: ' . $senderFromAddress . '');
	// 		//echo $sender_email_body;
	// 		wp_mail($retaileremail, $sender_subject, $sender_email_body, $headers);

	// 		// Sender email
	// 		$transport_sender_template = diamondemail_friend_email_template_receiver($complateRingpage);
	// 		$sender_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_sender_template);
	// 		$sender_subject            = "A Friend Wants To Share With You";
	// 		$senderFromAddress         = $all_optinos['from_email_address'];
	// 		$headers                   = array('From: ' . $senderFromAddress . '');
	// 		$senderToEmail             = $email_friend_post_data['email'];
	// 		//echo $sender_email_body;
	// 		wp_mail($senderToEmail, $sender_subject, $sender_email_body, $headers);

	// 		// Receiver email
	// 		$transport_receiver_template = diamondemail_friend_email_template_retailer($complateRingpage);
	// 		$receiver_email_body = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_receiver_template);
	// 		$receiver_subject     = "A Friend Wants To Share With You";
	// 		$receiver_fromAddress = $all_optinos['from_email_address'];
	// 		$headers              = array('From: ' . $senderFromAddress . '');
	// 		$receiver_toEmail     = $email_friend_post_data['friend_email'];
	// 		wp_mail($receiver_toEmail, $receiver_subject, $receiver_email_body, $headers);

	// 		$message = 'Thanks for your submission.';
	// 		$data    = array('status' => 1, 'msg' => $message);
	// 		$result  = json_encode(array('output' => $data));
	// 		die();
	// 	} catch (Exception $e) {
	// 		$message = $e->getMessage();
	// 	}
	// }
	if ($email_friend_post_data && $email_friend_post_data['email'] != "" && $email_friend_post_data['friend_email'] != "" && $email_friend_post_data['message'] != "") {
		try {
			$diamondData = getDiamondById($email_friend_post_data['diamondid'], $email_friend_post_data['diamondtype'],  $email_friend_post_data['shopurl']);
			//$retaileremail = $diamondData['diamondData']['vendorEmail'];	
			$retaileremail = ($all_options['admin_email_address'] ? $all_options['admin_email_address'] : $diamondData['diamondData']['vendorEmail']);
			$retailername  = ($diamondData['diamondData']['vendorName'] ? $diamondData['diamondData']['vendorName'] : $store_detail);
			$certificateUrl  = (isset($diamondData['diamondData']['certificateUrl'])) ? ' <a href=' . $diamondData['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';
			if ($show_Certificate_in_Diamond_Search) {
				$certificate   = (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : 'Not Available';
				$certificateNo = (isset($diamondData['diamondData']['certificateNo'])) ? $diamondData['diamondData']['certificateNo'] : '';
				$certificateUrl = (isset($certificateUrl)) ? $certificateUrl : '';
				$show_Certificate = '<tr>
					<td class="consumer-title">Lab:</td>
					<td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
					</tr>';
			}

			$currency = $diamondData['diamondData']['currencyFrom'] != 'USD' ? $diamondData['diamondData']['currencySymbol'] : '$' ;

			if( $diamondData['diamondData']['showPrice'] == 1){
                 $price  =$diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
            }else{
                 $price = 'Call For Price';
            }

			$templateVars  = array(
				'name'              => $email_friend_post_data['name'],
				'email'             => $email_friend_post_data['email'],
				'friend_name'       => $email_friend_post_data['friend_name'],
				'friend_email'      => $email_friend_post_data['friend_email'],
				'message'           => $email_friend_post_data['message'],
				'diamond_url'       => (isset($email_friend_post_data['diamondurl'])) ? $email_friend_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'shape'             => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price,
				'vendorID'          => (isset($diamondData['diamondData']['vendorID'])) ? $diamondData['diamondData']['vendorID'] : '',
				'vendorName'        => (isset($diamondData['diamondData']['vendorName'])) ? $diamondData['diamondData']['vendorName'] : '',
				'vendorEmail'       => (isset($diamondData['diamondData']['vendorEmail'])) ? $diamondData['diamondData']['vendorEmail'] : '',
				'vendorContactNo'   => (isset($diamondData['diamondData']['vendorContactNo'])) ? $diamondData['diamondData']['vendorContactNo'] : '',
				'vendorStockNo'     => (isset($diamondData['diamondData']['vendorStockNo'])) ? $diamondData['diamondData']['vendorStockNo'] : '',
				'vendorFax'         => (isset($diamondData['diamondData']['vendorFax'])) ? $diamondData['diamondData']['vendorFax'] : '',
				'wholeSalePrice'    => (isset($diamondData['diamondData']['wholeSalePrice'])) ? $diamondData['diamondData']['wholeSalePrice'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'retailerName'      => (isset($diamondData['diamondData']['retailerInfo']->retailerName)) ? $diamondData['diamondData']['retailerInfo']->retailerName : '',
				'retailerID'        => (isset($diamondData['diamondData']['retailerInfo']->retailerID)) ? $diamondData['diamondData']['retailerInfo']->retailerID : '',
				'retailerEmail'     => (isset($diamondData['diamondData']['retailerInfo']->retailerEmail)) ? $diamondData['diamondData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo' => (isset($diamondData['diamondData']['retailerInfo']->retailerContactNo)) ? $diamondData['diamondData']['retailerInfo']->retailerContactNo : '',
				'retailerStockNo'   => (isset($diamondData['diamondData']['retailerInfo']->retailerStockNo)) ? $diamondData['diamondData']['retailerInfo']->retailerStockNo : '',
				'retailerFax'       => (isset($diamondData['diamondData']['retailerInfo']->retailerFax)) ? $diamondData['diamondData']['retailerInfo']->retailerFax : '',
				'retailerAddress'   => (isset($diamondData['diamondData']['retailerInfo']->retailerAddress)) ? $diamondData['diamondData']['retailerInfo']->retailerAddress : '',
				'show_Certificate'	=> (isset($show_Certificate)) ? $show_Certificate : '',
			);

			$templateValueReplacement = array(
				'{{shopurl}}'           => $shopurl,
				'{{shop_logo}}'         => $store_logo,
				'{{shop_logo_alt}}'     => $store_detail,
				'{{name}}'              => $templateVars['name'],
				'{{email}}'             => $templateVars['email'],
				'{{friend_name}}'       => $templateVars['friend_name'],
				'{{friend_email}}'      => $templateVars['friend_email'],
				'{{message}}'           => $templateVars['message'],
				'{{diamond_id}}'        => $templateVars['diamond_id'],
				'{{diamond_url}}'       => $templateVars['diamond_url'],
				'{{shape}}'             => $templateVars['shape'],
				'{{size}}'              => $templateVars['size'],
				'{{cut}}'               => $templateVars['cut'],
				'{{color}}'             => $templateVars['color'],
				'{{clarity}}'           => $templateVars['clarity'],
				'{{depth}}'             => $templateVars['depth'],
				'{{table}}'             => $templateVars['table'],
				'{{measurment}}'        => $templateVars['measurment'],
				'{{certificate}}'       => $templateVars['certificate'],
				'{{show_Certificate}}'  => $templateVars['show_Certificate'],
				'{{price}}'             => $templateVars['price'],
				'{{wholeSalePrice}}'    => $templateVars['wholeSalePrice'],
				'{{vendorName}}'        => $retailername,
				'{{vendorStockNo}}'     => $templateVars['vendorStockNo'],
				'{{vendorEmail}}'       => $templateVars['vendorEmail'],
				'{{vendorContactNo}}'   => $templateVars['vendorContactNo'],
				'{{vendorFax}}'         => $templateVars['vendorFax'],
				'{{vendorAddress}}'     => $templateVars['vendorAddress'],
				'{{retailerName}}'      => $templateVars['retailerName'],
				'{{retailerEmail}}'     => $templateVars['retailerEmail'],
				'{{retailerContactNo}}' => $templateVars['retailerContactNo'],
				'{{retailerStockNo}}'   => $templateVars['retailerStockNo'],
				'{{retailerFax}}'       => $templateVars['retailerFax'],
				'{{retailerAddress}}'   => $templateVars['retailerAddress'],

			);

			// Sender email
			$transport_sender_template = diamondemail_friend_email_template_sender();
			$sender_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_sender_template);
			$sender_subject            = "A Friend Wants To Share With You";
			$senderFromAddress         = $all_optinos['from_email_address'];
			$headers                   = array('From: ' . $senderFromAddress . '');
			$senderToEmail             = $email_friend_post_data['email'];
			wp_mail($senderToEmail, $sender_subject, $sender_email_body, $headers);

			// Receiver email
			$transport_receiver_template = diamondemail_friend_email_template_receiver();
			$receiver_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_receiver_template);
			$receiver_subject            = "A Friend Wants To Share With You";
			$receiver_fromAddress        = $all_optinos['from_email_address'];
			$headers                     = array('From: ' . $senderFromAddress . '');
			$receiver_toEmail            = $email_friend_post_data['friend_email'];
			wp_mail($receiver_toEmail, $receiver_subject, $receiver_email_body, $headers);

			// Retailer email
			$transport_retailer_template = diamondemail_friend_email_template_retailer();
			$retailer_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_retailer_template);
			$retailer_subject            = "A Friend Wants To Share With You";
			$retailer_fromAddress        = $all_optinos['from_email_address'];
			$headers                     = array('From: ' . $retailer_fromAddress . '');
			$retailer_toEmail            = $retaileremail;
			wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);

			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);
			$result  = json_encode(array('output' => $data));
			echo $result;
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
	}
	die();
}

add_action('wp_ajax_nopriv_resultemailfriend_dl', 'resultemailfriend_dl');
add_action('wp_ajax_resultemailfriend_dl', 'resultemailfriend_dl');

function resultscheview_dl()
{
	$form_data          = $_POST['form_data'];
	$all_options        = getOptions_dl();
	$sch_view_post_data = array();
	$alldata = get_option('gemfind_ring_builder');
	$diamondsoption    = sendRequest_dl(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	foreach ($form_data as $data) {
		$sch_view_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}
	if ($sch_view_post_data && $sch_view_post_data['email'] != "" && $sch_view_post_data['phone'] != "" && $sch_view_post_data['avail_date'] != "") {
		try {
			$diamondData = getDiamondById($sch_view_post_data['diamondid'], $sch_view_post_data['diamondtype'], $sch_view_post_data['shopurl']);
			//$retaileremail = $diamondData['diamondData']['vendorEmail'];
			$retaileremail            = ($all_options['admin_email_address'] ? $all_options['admin_email_address'] : $diamondData['diamondData']['vendorEmail']);
			$retailername             = ($diamondData['diamondData']['vendorName'] ? $diamondData['diamondData']['vendorName'] : $store_detail);
			$certificateUrl  = (isset($diamondData['diamondData']['certificateUrl'])) ? ' <a href=' . $diamondData['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';

			if ($show_Certificate_in_Diamond_Search) {
				$certificate   = (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : 'Not Available';
				$certificateNo = (isset($diamondData['diamondData']['certificateNo'])) ? $diamondData['diamondData']['certificateNo'] : '';
				$certificateUrl = (isset($certificateUrl)) ? $certificateUrl : '';
				$show_Certificate = '<tr>
				<td class="consumer-title">Lab:</td>
				<td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
				</tr>';
			}

			$currency = $diamondData['diamondData']['currencyFrom'] != 'USD' ? $diamondData['diamondData']['currencySymbol'] : '$' ;

			if( $diamondData['diamondData']['showPrice'] == 1){
                 $price  =$diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
            }else{
                 $price = 'Call For Price';
            }

			$templateVars             = array(
				'name'              => $sch_view_post_data['name'],
				'email'             => $sch_view_post_data['email'],
				'phone'             => $sch_view_post_data['phone'],
				'hint_message'      => $sch_view_post_data['hint_message'],
				'location'          => $sch_view_post_data['location'],
				'avail_date'        => $sch_view_post_data['avail_date'],
				'appnt_time'        => $sch_view_post_data['appnt_time'],
				'diamond_url'       => (isset($sch_view_post_data['diamondurl'])) ? $sch_view_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'shape'             => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price,
				'vendorID'          => (isset($diamondData['diamondData']['vendorID'])) ? $diamondData['diamondData']['vendorID'] : '',
				'vendorName'        => (isset($diamondData['diamondData']['vendorName'])) ? $diamondData['diamondData']['vendorName'] : '',
				'vendorEmail'       => (isset($diamondData['diamondData']['vendorEmail'])) ? $diamondData['diamondData']['vendorEmail'] : '',
				'vendorContactNo'   => (isset($diamondData['diamondData']['vendorContactNo'])) ? $diamondData['diamondData']['vendorContactNo'] : '',
				'vendorStockNo'     => (isset($diamondData['diamondData']['vendorStockNo'])) ? $diamondData['diamondData']['vendorStockNo'] : '',
				'vendorFax'         => (isset($diamondData['diamondData']['vendorFax'])) ? $diamondData['diamondData']['vendorFax'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'wholeSalePrice'    => (isset($diamondData['diamondData']['wholeSalePrice'])) ? $diamondData['diamondData']['wholeSalePrice'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'retailerName'      => (isset($diamondData['diamondData']['retailerInfo']->retailerName)) ? $diamondData['diamondData']['retailerInfo']->retailerName : '',
				'retailerID'        => (isset($diamondData['diamondData']['retailerInfo']->retailerID)) ? $diamondData['diamondData']['retailerInfo']->retailerID : '',
				'retailerEmail'     => (isset($diamondData['diamondData']['retailerInfo']->retailerEmail)) ? $diamondData['diamondData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo' => (isset($diamondData['diamondData']['retailerInfo']->retailerContactNo)) ? $diamondData['diamondData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'       => (isset($diamondData['diamondData']['retailerInfo']->retailerFax)) ? $diamondData['diamondData']['retailerInfo']->retailerFax : '',
				'retailerAddress'   => (isset($diamondData['diamondData']['retailerInfo']->retailerAddress)) ? $diamondData['diamondData']['retailerInfo']->retailerAddress : '',
				'show_Certificate'	=> (isset($show_Certificate)) ? $show_Certificate : '',
			);
			$templateValueReplacement = array(
				'{{shopurl}}'       => $shopurl,
				'{{shop_logo}}'     => $store_logo,
				'{{shop_logo_alt}}' => $store_detail->shop->name,
				'{{name}}'          => $templateVars['name'],
				'{{email}}'         => $templateVars['email'],
				'{{phone}}'         => $templateVars['phone'],
				'{{hint_message}}'  => $templateVars['hint_message'],
				'{{location}}'      => $templateVars['location'],
				'{{appnt_time}}'    => $templateVars['appnt_time'],
				'{{avail_date}}'    => $templateVars['avail_date'],
				'{{diamond_id}}'    => $templateVars['diamond_id'],
				'{{diamond_url}}'   => $templateVars['diamond_url'],
				'{{shape}}'         => $templateVars['shape'],
				'{{size}}'          => $templateVars['size'],
				'{{cut}}'           => $templateVars['cut'],
				'{{color}}'         => $templateVars['color'],
				'{{clarity}}'       => $templateVars['clarity'],
				'{{depth}}'         => $templateVars['depth'],
				'{{table}}'         => $templateVars['table'],
				'{{measurment}}'    => $templateVars['measurment'],
				'{{certificate}}'   => $templateVars['certificate'],
				'{{price}}'         => $templateVars['price'],
				'{{show_Certificate}}'   => $templateVars['show_Certificate'],
				'{{retailerName}}'  => $retailername

			);

			$transport_retailer_template = diamondschedule_view_email_template_admin();
			$retailer_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_retailer_template);
			$retailer_subject            = "Request To Schedule A Viewing";
			$retailer_fromAddress        = $all_optinos['from_email_address'];
			$headers                     = array('From: ' . $retailer_fromAddress . '');
			if ($all_options['enable_admin_notify'] == true) {
				// Retailer email				
				$retailer_toEmail            = $retaileremail;
				wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);
			}

			$userEmail = $templateVars['email'];
			if (isset($userEmail) && $userEmail != "") {
				$transport_user_template = diamondschedule_view_email_template_user();
				$user_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_user_template);
				wp_mail($userEmail, $retailer_subject, $user_email_body, $headers);
			}

			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);
			$result  = json_encode(array('output' => $data));
			echo $result;
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
		$data   = array('status' => 0, 'msg' => $message);
		$result = json_encode(array('output' => $data));
		echo $result;
		die();
	}
	// if ($sch_view_post_data && $sch_view_post_data['email'] != "" && $sch_view_post_data['phone'] != "") {
	// 	try {
	// 		$ringData = getRingById_rb($sch_view_post_data['settingid'], $sch_view_post_data['shopurl']);
	// 		$diamond  = getDiamondById_dl($sch_view_post_data['diamondid'], $sch_view_post_data['diamondtype'], $sch_view_post_data['shopurl']);

	// 		if ($all_options['admin_email_address'] != "") {
	// 			$retaileremail = $all_options['admin_email_address'];
	// 		} else {
	// 			$retaileremail = $ringData['ringData']['vendorEmail'];
	// 		}
	// 		$complateRingpage = 'complateRingpage';
	// 		$currency = ($ringData['ringData']['currencyFrom'] != 'USD') ? $ringData['ringData']['currencyFrom'] . $ringData['ringData']['currencySymbol'] . ' ' . number_format($ringData['ringData']['cost']) : $ringData['ringData']['currencySymbol'] . ' ' . number_format($ringData['ringData']['cost']);

	// 		$certificateUrl  = (isset($diamond['diamondData']['certificateUrl'])) ? ' <a href=' . $diamond['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';
	// 		$certificate   = (isset($diamond['diamondData']['certificate'])) ? $diamond['diamondData']['certificate'] : 'Not Available';
	// 		$certificateNo = (isset($diamond['diamondData']['certificateNo'])) ? $diamond['diamondData']['certificateNo'] : '';

	// 		if (!empty($certificateUrl) && !empty($show_Certificate_in_Diamond_Search)) {
	// 			$labColumn = '<tr>
	// 	              <td class="consumer-title">Lab:</td>
	// 	              <td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
	// 	            </tr>';
	// 		}
	// 		if (isset($ringData['ringData']['configurableProduct'][0]->sideStoneQuality) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality != "") {
	// 			$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
	// 			$sideStoneQualityhtm = '<tr>
	// 					                  <td class="consumer-title">Side Stone Quality:</td>
	// 					                  <td class="consumer-name">' . $sideStoneQuality . '</td>
	// 					                </tr>';
	// 		}
	// 		if (isset($sch_view_post_data['ringSize']) && $sch_view_post_data['ringSize'] != "") {
	// 			$ringSize = '<tr>
	// 					                  <td class="consumer-title">Ring Size:</td>
	// 					                  <td class="consumer-name">' . $sch_view_post_data['ringSize'] . '</td>
	// 					                </tr>';
	// 		}
	// 		$templateVars             = array(
	// 			'name'                => $sch_view_post_data['name'],
	// 			'email'               => $sch_view_post_data['email'],
	// 			'phone'               => $sch_view_post_data['phone'],
	// 			'hint_message'        => $sch_view_post_data['hint_message'],
	// 			'location'            => $sch_view_post_data['location'],
	// 			'avail_date'          => $sch_view_post_data['avail_date'],
	// 			'appnt_time'          => $sch_view_post_data['appnt_time'],
	// 			'ring_url'            => (isset($sch_view_post_data['ringurl'])) ? $sch_view_post_data['ringurl'] : $sch_view_post_data['diamondurl'],
	// 			'setting_id'          => (isset($ringData['ringData']['settingId'])) ? $ringData['ringData']['settingId'] : '',
	// 			'stylenumber'         => (isset($ringData['ringData']['styleNumber'])) ? $ringData['ringData']['styleNumber'] : '',
	// 			'metaltype'           => (isset($ringData['ringData']['metalType'])) ? $ringData['ringData']['metalType'] : '',
	// 			'centerStoneSize'     => (isset($ringData['ringData']['configurableProduct'][0]->centerStoneSize)) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
	// 			'ringSize' 			  => $ringSize,
	// 			'sideStoneQualityhtm' => $sideStoneQualityhtm,
	// 			'centerStoneMinCarat' => (isset($ringData['ringData']['centerStoneMinCarat'])) ? $ringData['ringData']['centerStoneMinCarat'] : '',
	// 			'centerStoneMaxCarat' => (isset($ringData['ringData']['centerStoneMaxCarat'])) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
	// 			'price'               => (isset($ringData['ringData']['cost'])) ? $currency : '',
	// 			'retailerName'        => (isset($ringData['ringData']['vendorName'])) ? $ringData['ringData']['vendorName'] : '',
	// 			'retailerID'          => (isset($ringData['ringData']['retailerInfo']->retailerID)) ? $ringData['ringData']['retailerInfo']->retailerID : '',
	// 			'retailerEmail'       => (isset($ringData['ringData']['retailerInfo']->retailerEmail)) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
	// 			'retailerContactNo'   => (isset($ringData['ringData']['retailerInfo']->retailerContactNo)) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
	// 			'retailerFax'         => (isset($ringData['ringData']['retailerInfo']->retailerFax)) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
	// 			'retailerAddress'     => (isset($ringData['ringData']['retailerInfo']->retailerAddress)) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
	// 			'labColumn'   => (isset($labColumn)) ? $labColumn : '',
	// 		);
	// 		$templateValueReplacement = array(
	// 			'{{shopurl}}'             => $shopurl,
	// 			'{{shop_logo}}'           => $store_logo,
	// 			'{{shop_logo_alt}}'       => $store_detail->shop->name,
	// 			'{{name}}'                => $templateVars['name'],
	// 			'{{email}}'               => $templateVars['email'],
	// 			'{{phone}}'               => $templateVars['phone'],
	// 			'{{hint_message}}'        => $templateVars['hint_message'],
	// 			'{{ring_url}}'		      => $templateVars['ring_url'],
	// 			'{{location}}'            => $templateVars['location'],
	// 			'{{appnt_time}}'          => $templateVars['appnt_time'],
	// 			'{{avail_date}}'          => $templateVars['avail_date'],
	// 			'{{setting_id}}'          => $templateVars['setting_id'],
	// 			'{{stylenumber}}'         => $templateVars['stylenumber'],
	// 			'{{metaltype}}'           => $templateVars['metaltype'],
	// 			'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
	// 			'{{ringSize}}'     		  => $templateVars['ringSize'],
	// 			'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
	// 			'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
	// 			'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
	// 			'{{price}}'               => $templateVars['price'],
	// 			'{{labColumn}}'           => $templateVars['labColumn'],
	// 			'{{retailerName}}'        => $templateVars['retailerName']
	// 		);
	// 		// Retailer email
	// 		$retailer_toEmail     = $retaileremail;
	// 		if ($retailer_toEmail) {
	// 			$transport_retailer_template = diamondschedule_view_email_template_admin($complateRingpage);
	// 			$retailer_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_retailer_template);
	// 			//echo $retailer_email_body;
	// 			$retailer_subject     = "Request To Schedule A Viewing";
	// 			$retailer_fromAddress = $all_optinos['from_email_address'];

	// 			$headers              = array('From: ' . $retailer_fromAddress . '');
	// 			wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);
	// 		}

	// 		if (isset($templateVars['email']) && $templateVars['email'] != "") {
	// 			$transport_user_template = diamondschedule_view_email_template_user($complateRingpage);
	// 			$user_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_user_template);
	// 			//echo $user_email_body;
	// 			$user_subject     = "Request To Schedule A Viewing";
	// 			$user_fromAddress = $all_optinos['from_email_address'];
	// 			$user_toEmail     = $templateVars['email'];
	// 			$headers              = array('From: ' . $user_fromAddress . '');
	// 			wp_mail($user_toEmail, $user_subject, $user_email_body, $headers);
	// 		}
	// 		$message = 'Thanks for your submission.';
	// 		$data    = array('status' => 1, 'msg' => $message);
	// 		$result  = json_encode(array('output' => $data));
	// 		echo $result;
	// 		die();
	// 	} catch (Exception $e) {
	// 		echo "something";
	// 		die();
	// 		$message = $e->getMessage();
	// 	}
	// 	die();
	// 	$data   = array('status' => 0, 'msg' => $message);
	// 	$result = json_encode(array('output' => $data));
	// 	echo $result;
	// 	die();
	// }
	$message = 'Not found all the required fields';
	$data    = array('status' => 0, 'msg' => $message);
	$result  = json_encode(array('output' => $data));
	echo $result;
	die();
}

add_action('wp_ajax_nopriv_resultscheview_dl', 'resultscheview_dl');
add_action('wp_ajax_resultscheview_dl', 'resultscheview_dl');

/**
 * Will send request info in mail.
 */
function resultreqinfo1_dl()
{
	$form_data     = $_POST['form_data'];
	$all_options   = getOptions_dl();
	$req_post_data = array();
	$alldata = get_option('gemfind_ring_builder');
	$diamondsoption    = sendRequest_dl(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	foreach ($form_data as $data) {
		$req_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}
	//$complateRingpage = 'complateRingpage';
	if ($req_post_data && $req_post_data['name'] !== "" && $req_post_data['email'] && $req_post_data['phone'] && $req_post_data['contact_pref']) {
		try {
			$diamondData   = getDiamondById($req_post_data['diamondid'], $req_post_data['diamondtype'], $req_post_data['shopurl']);
			$retaileremail = ($all_options['admin_email_address'] ? $all_options['admin_email_address'] : $diamondData['diamondData']['vendorEmail']);
			$retailername  = $diamondData['diamondData']['retailerInfo']->retailerName;
			$certificateUrl  = (isset($diamondData['diamondData']['certificateUrl'])) ? ' <a href=' . $diamondData['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';

			if ($show_Certificate_in_Diamond_Search) {
				$certificate   = (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : 'Not Available';
				$certificateNo = (isset($diamondData['diamondData']['certificateNo'])) ? $diamondData['diamondData']['certificateNo'] : '';
				$certificateUrl = (isset($certificateUrl)) ? $certificateUrl : '';
				$show_Certificate = '<tr>
					<td class="consumer-title">Lab:</td>
					<td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
					</tr>';
			}

			$currency = $diamondData['diamondData']['currencyFrom'] != 'USD' ? $diamondData['diamondData']['currencySymbol'] : '$' ;

			if( $diamondData['diamondData']['showPrice'] == 1){
                 $price  =$diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
            }else{
                 $price = 'Call For Price';
            }

			$templateVars  = array(
				'name'              => (isset($req_post_data['name'])) ? $req_post_data['name'] : '',
				'email'             => (isset($req_post_data['email'])) ? $req_post_data['email'] : '',
				'phone'             => (isset($req_post_data['phone'])) ? $req_post_data['phone'] : '',
				'hint_message'      => (isset($req_post_data['hint_message'])) ? $req_post_data['hint_message'] : '',
				'contact_pref'      => (isset($req_post_data['contact_pref'])) ? $req_post_data['contact_pref'] : '',
				'diamond_url'       => (isset($req_post_data['diamondurl'])) ? $req_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'shape'               => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price,
				'vendorID'          => (isset($diamondData['diamondData']['vendorID'])) ? $diamondData['diamondData']['vendorID'] : '',
				'vendorName'        => (isset($diamondData['diamondData']['vendorName'])) ? $diamondData['diamondData']['vendorName'] : '',
				'vendorEmail'       => (isset($diamondData['diamondData']['vendorEmail'])) ? $diamondData['diamondData']['vendorEmail'] : '',
				'vendorContactNo'   => (isset($diamondData['diamondData']['vendorContactNo'])) ? $diamondData['diamondData']['vendorContactNo'] : '',
				'vendorStockNo'     => (isset($diamondData['diamondData']['vendorStockNo'])) ? $diamondData['diamondData']['vendorStockNo'] : '',
				'vendorFax'         => (isset($diamondData['diamondData']['vendorFax'])) ? $diamondData['diamondData']['vendorFax'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'wholeSalePrice'    => (isset($diamondData['diamondData']['wholeSalePrice'])) ? $diamondData['diamondData']['wholeSalePrice'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'retailerName'      => (isset($diamondData['diamondData']['retailerInfo']->retailerName)) ? $diamondData['diamondData']['retailerInfo']->retailerName : '',
				'retailerID'        => (isset($diamondData['diamondData']['retailerInfo']->retailerID)) ? $diamondData['diamondData']['retailerInfo']->retailerID : '',
				'retailerEmail'     => (isset($diamondData['diamondData']['retailerInfo']->retailerEmail)) ? $diamondData['diamondData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo' => (isset($diamondData['diamondData']['retailerInfo']->retailerContactNo)) ? $diamondData['diamondData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'       => (isset($diamondData['diamondData']['retailerInfo']->retailerFax)) ? $diamondData['diamondData']['retailerInfo']->retailerFax : '',
				'retailerAddress'   => (isset($diamondData['diamondData']['retailerInfo']->retailerAddress)) ? $diamondData['diamondData']['retailerInfo']->retailerAddress : '',
				'retailerStockNo'   => (isset($diamondData['diamondData']['retailerInfo']->retailerStockNo)) ? $diamondData['diamondData']['retailerInfo']->retailerStockNo : '',
				'show_Certificate'	=> (isset($show_Certificate)) ? $show_Certificate : '',
			);

			$templateValueReplacement = array(
				'{{shopurl}}'           => $shopurl,
				'{{shop_logo}}'         => $store_logo,
				'{{shop_logo_alt}}'     => 'Gemfind Diamond Link',
				'{{name}}'              => $templateVars['name'],
				'{{email}}'             => $templateVars['email'],
				'{{phone}}'             => $templateVars['phone'],
				'{{hint_message}}'      => $templateVars['hint_message'],
				'{{contact_pref}}'      => $templateVars['contact_pref'],
				'{{diamond_id}}'        => $templateVars['diamond_id'],
				'{{diamond_url}}'       => $templateVars['diamond_url'],
				'{{size}}'              => $templateVars['size'],
				'{{shape}}'             => $templateVars['shape'],
				'{{cut}}'               => $templateVars['cut'],
				'{{color}}'             => $templateVars['color'],
				'{{clarity}}'           => $templateVars['clarity'],
				'{{depth}}'             => $templateVars['depth'],
				'{{table}}'             => $templateVars['table'],
				'{{measurment}}'        => $templateVars['measurment'],
				'{{certificate}}'       => $templateVars['certificate'],
				'{{price}}'             => $templateVars['price'],
				'{{show_Certificate}}'  => $templateVars['show_Certificate'],
				'{{wholeSalePrice}}'    => $templateVars['wholeSalePrice'],
				'{{vendorName}}'        => $templateVars['vendorName'],
				'{{vendorStockNo}}'     => $templateVars['vendorStockNo'],
				'{{vendorEmail}}'       => $templateVars['vendorEmail'],
				'{{vendorContactNo}}'   => $templateVars['vendorContactNo'],
				'{{vendorFax}}'         => $templateVars['vendorFax'],
				'{{vendorAddress}}'     => $templateVars['vendorAddress'],
				'{{retailerName}}'      => $retailername,
				'{{retailerEmail}}'     => $templateVars['retailerEmail'],
				'{{retailerContactNo}}' => $templateVars['retailerContactNo'],
				'{{retailerFax}}'       => $templateVars['retailerFax'],
				'{{retailerAddress}}'   => $templateVars['retailerAddress'],
				'{{retailerStockNo}}'   => $templateVars['retailerStockNo'],

			);

			if ($all_options['enable_admin_notify'] == true) {
				// Retailer email
				$transport_retailer_template = diamondinfo_email_template_retailer();
				$retailer_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_retailer_template);
				$retailer_subject            = "Request For More Info";
				$retailer_fromAddress        = $all_optinos['from_email_address'];
				$headers                     = array('From: ' . $retailer_fromAddress . '');
				$retailer_toEmail            = $retaileremail;
				wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);
			}

			// Sender email
			$transport_sender_template = diamondinfo_email_template_sender();
			$sender_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_sender_template);
			$sender_subject            = "Request For More Info";
			$sender_fromAddress        = $all_optinos['from_email_address'];
			$headers                   = array('From: ' . $senderFromAddress . '');
			$sender_toEmail            = $req_post_data['email'];
			wp_mail($sender_toEmail, $sender_subject, $sender_email_body, $headers);
			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);
			$result  = json_encode(array('output' => $data));
			echo $result;
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
	}
	// if ($req_post_data && $req_post_data['email'] != "" && $req_post_data['phone'] != "") {
	// 	try {
	// 		$ringData  = getRingById_rb($req_post_data['settingid'], $req_post_data['shopurl']);
	// 		$diamond   = getDiamondById_dl($req_post_data['diamondid'], $req_post_data['diamondtype'], $req_post_data['shopurl']);
	// 		if ($all_options['admin_email_address'] != "") {
	// 			$retaileremail = $all_options['admin_email_address'];
	// 		} else {
	// 			$retaileremail = $ringData['ringData']['vendorEmail'];
	// 		}

	// 		$currency = ($ringData['ringData']['currencyFrom'] != 'USD') ? $ringData['ringData']['currencyFrom'] . $ringData['ringData']['currencySymbol'] . ' ' . number_format($ringData['ringData']['cost']) : $ringData['ringData']['currencySymbol'] . ' ' . number_format($ringData['ringData']['cost']);

	// 		$certificateUrl  = (isset($diamond['diamondData']['certificateUrl'])) ? ' <a href=' . $diamond['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';
	// 		$certificate   = (isset($diamond['diamondData']['certificate'])) ? $diamond['diamondData']['certificate'] : 'Not Available';
	// 		$certificateNo = (isset($diamond['diamondData']['certificateNo'])) ? $diamond['diamondData']['certificateNo'] : '';

	// 		if (!empty($certificateUrl) && !empty($show_Certificate_in_Diamond_Search)) {
	// 			$labColumn = '<tr>
	// 	              <td class="consumer-title">Lab:</td>
	// 	              <td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
	// 	            </tr>';
	// 		} else {
	// 			$labColumn = '';
	// 		}
	// 		if (isset($ringData['ringData']['configurableProduct'][0]->sideStoneQuality) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality != "") {
	// 			$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
	// 			$sideStoneQualityhtm = '<tr>
	// 					                  <td class="consumer-title">Side Stone Quality:</td>
	// 					                  <td class="consumer-name">' . $sideStoneQuality . '</td>
	// 					                </tr>';
	// 		}
	// 		if (isset($req_post_data['ringSize']) && $req_post_data['ringSize'] != "") {
	// 			$ringSize = '<tr>
	// 					                  <td class="consumer-title">Ring Size:</td>
	// 					                  <td class="consumer-name">' . $req_post_data['ringSize'] . '</td>
	// 					                </tr>';
	// 		}
	// 		$templateVars = array(
	// 			'name'                => (isset($req_post_data['name'])) ? $req_post_data['name'] : '',
	// 			'email'               => (isset($req_post_data['email'])) ? $req_post_data['email'] : '',
	// 			'phone'               => (isset($req_post_data['phone'])) ? $req_post_data['phone'] : '',
	// 			'hint_message'        => (isset($req_post_data['hint_message'])) ? $req_post_data['hint_message'] : '',
	// 			'contact_pref'        => (isset($req_post_data['contact_pref'])) ? $req_post_data['contact_pref'] : '',
	// 			'ring_url'            => (isset($req_post_data['ringurl'])) ? $req_post_data['ringurl'] : $req_post_data['diamondurl'],
	// 			'price'               => (isset($ringData['ringData']['cost'])) ? $currency : '',
	// 			'setting_id'          => (isset($ringData['ringData']['settingId'])) ? $ringData['ringData']['settingId'] : '',
	// 			'stylenumber'         => (isset($ringData['ringData']['styleNumber'])) ? $ringData['ringData']['styleNumber'] : '',
	// 			'metaltype'           => (isset($ringData['ringData']['metalType'])) ? $ringData['ringData']['metalType'] : '',
	// 			'centerStoneSize'     => (isset($ringData['ringData']['configurableProduct'][0]->centerStoneSize)) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
	// 			'sideStoneQualityhtm' => $sideStoneQualityhtm,
	// 			'ringSize' 			  => $ringSize,
	// 			'centerStoneMinCarat' => (isset($ringData['ringData']['centerStoneMinCarat'])) ? $ringData['ringData']['centerStoneMinCarat'] : '',
	// 			'centerStoneMaxCarat' => (isset($ringData['ringData']['centerStoneMaxCarat'])) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
	// 			'retailerName'        => (isset($ringData['ringData']['vendorName'])) ? $ringData['ringData']['vendorName'] : '',
	// 			'retailerID'          => (isset($ringData['ringData']['retailerInfo']->retailerID)) ? $ringData['ringData']['retailerInfo']->retailerID : '',
	// 			'retailerEmail'       => (isset($ringData['ringData']['retailerInfo']->retailerEmail)) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
	// 			'retailerContactNo'   => (isset($ringData['ringData']['retailerInfo']->retailerContactNo)) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
	// 			'retailerFax'         => (isset($ringData['ringData']['retailerInfo']->retailerFax)) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
	// 			'retailerAddress'     => (isset($ringData['ringData']['retailerInfo']->retailerAddress)) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
	// 			'labColumn'   => (isset($labColumn)) ? $labColumn : '',
	// 		);

	// 		$templateValueReplacement = array(
	// 			'{{shopurl}}'             => $shopurl,
	// 			'{{shop_logo}}'           => $store_logo,
	// 			'{{shop_logo_alt}}'       => $store_detail->shop->name,
	// 			'{{name}}'                => $templateVars['name'],
	// 			'{{email}}'               => $templateVars['email'],
	// 			'{{phone}}'               => $templateVars['phone'],
	// 			'{{hint_message}}'        => $templateVars['hint_message'],
	// 			'{{contact_pref}}'        => $templateVars['contact_pref'],
	// 			'{{ring_url}}'            => $templateVars['ring_url'],
	// 			'{{price}}'               => $templateVars['price'],
	// 			'{{labColumn}}'         => $templateVars['labColumn'],
	// 			'{{setting_id}}'          => $templateVars['setting_id'],
	// 			'{{stylenumber}}'         => $templateVars['stylenumber'],
	// 			'{{metaltype}}'           => $templateVars['metaltype'],
	// 			'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
	// 			'{{ringSize}}'     		  => $templateVars['ringSize'],
	// 			'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
	// 			'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
	// 			'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
	// 			'{{retailerName}}'        => $templateVars['retailerName'],
	// 			'{{retailerEmail}}'       => $templateVars['retailerEmail'],
	// 			'{{retailerContactNo}}'   => $templateVars['retailerContactNo'],
	// 			'{{retailerFax}}'         => $templateVars['retailerFax'],
	// 			'{{retailerAddress}}'     => $templateVars['retailerAddress'],
	// 			'{{retailerID}}'          => $templateVars['retailerID'],
	// 		);

	// 		// Retailer email
	// 		$transport_admin_template = ringinfo_email_template_admin($complateRingpage);
	// 		$admin_email_body = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_admin_template);
	// 		$retailer_subject     = "Request For More Info";
	// 		$retailer_fromAddress = $all_optinos['from_email_address'];
	// 		$headers              = array('From: ' . $retailer_fromAddress . '');
	// 		$retailer_toEmail     = $retaileremail;
	// 		wp_mail($retailer_toEmail, $retailer_subject, $admin_email_body, $headers);

	// 		// Sender email
	// 		$transport_sender_template = ringinfo_email_template_sender($complateRingpage);
	// 		$sender_email_body = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_sender_template);
	// 		$sender_subject     = "Request For More Info";
	// 		$sender_fromAddress = $all_optinos['from_email_address'];
	// 		$headers            = array('From: ' . $senderFromAddress . '');
	// 		$sender_toEmail     = $req_post_data['email'];
	// 		wp_mail($sender_toEmail, $sender_subject, $sender_email_body, $headers);
	// 		$message = 'Thanks for your submission.';
	// 		$data    = array('status' => 1, 'msg' => $message);
	// 		$result  = json_encode(array('output' => $data));
	// 		echo $result;
	// 		die();
	// 	} catch (Exception $e) {
	// 		$message = $e->getMessage();
	// 	}
	// }
}

add_action('wp_ajax_nopriv_resultreqinfo1_dl', 'resultreqinfo1_dl');
add_action('wp_ajax_resultreqinfo1_dl', 'resultreqinfo1_dl');
/**
 * For print diamond.
 */
function printdiamond_dl()
{
	if(strpos($_SERVER["HTTP_REFERER"], "labcreated")!== false){
		$type = 'labcreated';
	}
	else{
		$type = '';
	}
		
	//print_r($_SERVER["HTTP_REFERER"]);	
	$printData = array('diamond_id' => $_POST['diamondid'], 'shop' => $_POST['shop']);
	$diamond   = getDiamondById_dl($_POST['diamondid'], $type, $_POST['shop']);
	
?>
	<div class="printdiv" id="printdiv">
		<div class="print-header" style="background-color:#1979c3 !important; color: #fff !important;">
			<div class="header-container">
				<div class="header-title">
					<h2 style="color: #fff !important;"><?php _e('Diamond Detail', 'gemfind-diamond-link'); ?></h2>
				</div>
				<div class="header-date">
					<h4 style="color: #fff !important;"><?php echo date("d/m/Y"); ?></h4>
				</div>
			</div>
		</div>
		<section class="diamonds-search with-specification diamond-page">
			<div class="d-container">
				<div class="d-row">
					<div class="diamonds-print-preview no-padding" style="background-color: #f7f7f7 !important; border: 1px solid #e8e8e8 !important;">
						<div class="diamond-info-one">
							<img src="<?php echo $diamond['diamondData']['image2'] ?>" style="height: 100px;width: 100px;" />
						</div>
						<div class="diamond-info-two">
							<img src="<?php echo $diamond['diamondData']['image1'] ?>" style="height: 100px;width: 165px;" />
						</div>
						<div class="print-info">
							<p>SKU# <span><?php echo $diamond['diamondData']['diamondId'] ?></span></p>
						</div>
					</div>
					<div class="print-diamond-certifications">
						<div class="diamonds-grade">
							<img src="<?php echo $diamond['diamondData']['certificateIconUrl'] ?>" style="height: 75px;width: 75px;" />
						</div>
						<div class="diamonds-grade-info">
							<p><?php echo $diamond['diamondData']['subHeader']; ?></p>
						</div>
					</div>
					<div class="print-details">
						<div class="diamond-title">
							<div class="diamond-name">
								<h2><?php echo $diamond['diamondData']['mainHeader']; ?></h2>
								<p><?php echo $diamond['diamondData']['subHeader']; ?></p>
							</div>
							<?php if($diamond['diamondData']['showPrice'] == true || $diamond['diamondData']['showPrice'] == 'true'){ ?>
							<div class="diamond-price" style="text-align: right;">
								<span><?php echo ($diamond['diamondData']['currencyFrom'] != 'USD') ? $diamond['diamondData']['currencyFrom'] . $diamond['diamondData']['currencySymbol'] . number_format($diamond['diamondData']['fltPrice']) : $diamond['diamondData']['currencySymbol'] . number_format($diamond['diamondData']['fltPrice']); ?></span>
							</div>
							<?php } else { ?>
							<div class="diamond-price" style="text-align: right;">
								<span><?php echo 'Call For Price'; ?></span>
							</div>
							<?php } ?>
						</div>
						<div class="diamond-inner-details">
							<ul>
								<?php if (isset($diamond['diamondData']['diamondId'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Stock Number', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['diamondId'] ?></p>
										</div>
									</li>
								<?php } ?>
								
								<?php if($diamond['diamondData']['showPrice'] == true || $diamond['diamondData']['showPrice'] == 'true'){ ?>
								<?php if (isset($diamond['diamondData']['fltPrice'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Price', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo ($diamond['diamondData']['currencyFrom'] != 'USD') ? $diamond['diamondData']['currencyFrom'] . $diamond['diamondData']['currencySymbol'] . number_format($diamond['diamondData']['fltPrice']) : $diamond['diamondData']['currencySymbol'] . number_format($diamond['diamondData']['fltPrice']); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php } else { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Price', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo 'Call For Price' ; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['caratWeight'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Carat Weight', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['caratWeight'] ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['cut'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Cut', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['cut'] ?></p>
										</div>
									</li>
								<?php } ?>
								<?php
								if ($diamond['diamondData']['fancyColorMainBody']) {
									$color_to_display = $diamond['diamondData']['fancyColorIntensity'] . ' ' . $diamond['diamondData']['fancyColorMainBody'];
								} else {
									$color_to_display = $diamond['diamondData']['color'];
								}
								?>
								<?php if (isset($color_to_display)) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Color', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $color_to_display; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['clarity'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Clarity', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['clarity'] ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['certificate'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Report', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<span><?php echo $diamond['diamondData']['certificate']; ?></span>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['depth'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Depth %', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['depth'] . '%'; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['table'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Table %', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['table'] . '%'; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['polish'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Polish', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['polish']; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['symmetry'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Symmetry', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['symmetry']; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['gridle'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Girdle', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['gridle']; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['culet'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Culet', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['culet']; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['fluorescence'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Fluorescence', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['fluorescence']; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['measurement'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php _e('Measurement', 'gemfind-diamond-link'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo $diamond['diamondData']['measurement']; ?></p>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php
	die();
}

add_action('wp_ajax_nopriv_printdiamond_dl', 'printdiamond_dl');
add_action('wp_ajax_printdiamond_dl', 'printdiamond_dl');
/**
 * For allowing html content in mail.
 */
// In theme's functions.php or plug-in code:
function wpse27856_set_content_type_dl()
{
	return "text/html";
}

add_filter('wp_mail_content_type', 'wpse27856_set_content_type_dl');

/**
 * For creating product in WooCommerce upon add to cart.
 */
function add_product_to_cart_dl()
{
	$server_uri   = $_POST['diamond_name'];
	$diamond_path = rtrim($server_uri, '/');
	$diamond_path = end(explode('/', $diamond_path));
	$diamond      = json_decode(stripslashes($_POST['diamond']), 1);
	$post_id      = get_product_by_sku_dl($diamond['diamondData']['diamondId']);
	if (isset($post_id) && $post_id != '') {
		return;
	}
	$post = array(
		'post_author'  => get_current_user_id(),
		'post_content' => '',
		'post_name'    => $diamond_path,
		'post_status'  => "publish",
		'post_title'   => $diamond['diamondData']['mainHeader'],
		'post_parent'  => '',
		'post_excerpt' => $diamond['diamondData']['subHeader'],
		'post_type'    => "product",
	);
	//Create post
	$post_id = wp_insert_post($post, $wp_error);
	update_post_meta($post_id, '_sku', $diamond['diamondData']['diamondId']);
	update_post_meta($post_id, '_regular_price', $diamond['diamondData']['fltPrice']);
	update_post_meta($post_id, 'FltPrice', $diamond['diamondData']['fltPrice']);
	update_post_meta($post_id, '_price', $diamond['diamondData']['fltPrice']);
	update_post_meta($post_id, 'costPerCarat', $diamond['diamondData']['costPerCarat']);
	update_post_meta($post_id, 'image1', $diamond['diamondData']['image1']);
	update_post_meta($post_id, 'image2', $diamond['diamondData']['image2']);
	update_post_meta($post_id, 'videoFileName', $diamond['diamondData']['videoFileName']);
	update_post_meta($post_id, 'certificateNo', $diamond['diamondData']['certificateNo']);
	update_post_meta($post_id, 'certificateUrl', $diamond['diamondData']['certificateUrl']);
	update_post_meta($post_id, 'certificateIconUrl', $diamond['diamondData']['certificateIconUrl']);
	update_post_meta($post_id, 'measurement', $diamond['diamondData']['measurement']);
	update_post_meta($post_id, 'origin', $diamond['diamondData']['origin']);
	update_post_meta($post_id, 'gridle', $diamond['diamondData']['gridle']);
	update_post_meta($post_id, 'culet', $diamond['diamondData']['culet']);
	update_post_meta($post_id, 'cut', $diamond['diamondData']['cut']);
	update_post_meta($post_id, '_price', $diamond['diamondData']['fltPrice']);
	update_post_meta($post_id, 'Color', $diamond['diamondData']['color']);
	update_post_meta($post_id, 'ClarityID', $diamond['diamondData']['clarity']);
	update_post_meta($post_id, 'CutGrade', $diamond['diamondData']['cut']);
	update_post_meta($post_id, 'TableMeasure', $diamond['diamondData']['table']);
	update_post_meta($post_id, 'Polish', $diamond['diamondData']['polish']);
	update_post_meta($post_id, 'Symmetry', $diamond['diamondData']['symmetry']);
	update_post_meta($post_id, 'Measurements', $diamond['diamondData']['measurement']);
	update_post_meta($post_id, 'Certificate', $diamond['diamondData']['certificate']);
	update_post_meta($post_id, 'shape', $diamond['diamondData']['shape']);
	update_post_meta($post_id, 'product_type', 'gemfind');
	update_post_meta($post_id, 'internalUselink', $diamond['diamondData']['internalUselink']);
	$productAttr = array();
	if (isset($diamond['diamondData']['fancyColorMainBody']) && !empty($diamond['diamondData']['fancyColorMainBody'])) {
		$productAttr['pa_gemfind_fancy_certificate']  = $diamond['diamondData']['certificate'];
		$productAttr['pa_gemfind_fancy_clarity']      = $diamond['diamondData']['clarity'];
		$productAttr['pa_gemfind_fancy_color']        = $diamond['diamondData']['fancyColorMainBody'];
		$productAttr['pa_gemfind_fancy_fluorescence'] = $diamond['diamondData']['fluorescence'];
		$productAttr['pa_gemfind_fancy_polish']       = $diamond['diamondondData']['polish'];
		$productAttr['pa_gemfind_fancy_shape']        = $diamond['diamondData']['shape'];
		$productAttr['pa_gemfind_fancy_symmetry']     = $diamond['diamondData']['symmetry'];
		$productAttr['pa_gemfind_fancy_video']        = $diamond['diamondData']['videoFileName'];
		$productAttr['pa_gemfind_fancy_intensity']    = $diamond['diamondData']['fancyColorIntensity'];
		$productAttr['pa_gemfind_fancy_origin']       = $diamond['diamondData']['origin'];
		update_post_meta($post_id, 'caratWeightFancy', $diamond['diamondData']['caratWeight']);
		update_post_meta($post_id, 'tableFancy', $diamond['diamondData']['table']);
		update_post_meta($post_id, 'depthFancy', $diamond['diamondData']['depth']);
		update_post_meta($post_id, 'FltPriceFancy', $diamond['diamondData']['fltPrice']);
		update_post_meta($post_id, 'fancyColorIntensity', $diamond['diamondData']['fancyColorIntensity']);
		//$productAttr['pa_fancy_cut']        	= $diamond['diamondData']['cut'];
		update_post_meta($post_id, 'productType', 'fancy');
	} else {
		$productAttr['pa_gemfind_certificate']  = $diamond['diamondData']['certificate'];
		$productAttr['pa_gemfind_clarity']      = $diamond['diamondData']['clarity'];
		$productAttr['pa_gemfind_color']        = $diamond['diamondData']['color'];
		$productAttr['pa_gemfind_fluorescence'] = $diamond['diamondData']['fluorescence'];
		$productAttr['pa_gemfind_polish']       = $diamond['diamondondData']['polish'];
		$productAttr['pa_gemfind_shape']        = $diamond['diamondData']['shape'];
		$productAttr['pa_gemfind_symmetry']     = $diamond['diamondData']['symmetry'];
		$productAttr['pa_gemfind_video']        = $diamond['diamondData']['videoFileName'];
		$productAttr['pa_gemfind_cut']          = $diamond['diamondData']['cut'];
		update_post_meta($post_id, 'caratWeight', $diamond['diamondData']['caratWeight']);
		update_post_meta($post_id, 'table', $diamond['diamondData']['table']);
		update_post_meta($post_id, 'depth', $diamond['diamondData']['depth']);
		update_post_meta($post_id, 'FltPrice', $diamond['diamondData']['fltPrice']);
		update_post_meta($post_id, 'productType', 'standard');
	}
	$product_attributes = array();
	foreach ($productAttr as $key => $value) {
		wp_set_object_terms($post_id, $value, $key, true);
		$product_attributes_meta    = get_post_meta($post_id, '_product_attributes', true);
		$count                      = (is_array($product_attributes_meta)) ? count($product_attributes_meta) : 0;
		$product_attributes[$key] = array(
			'name'        => $key,
			'value'       => $value,
			'position'    => $count, // added
			'is_visible'  => 1,
			'is_taxonomy' => 1
		);
	}
	update_post_meta($post_id, '_product_attributes', $product_attributes);
	//update_post_meta( $post_id, '_visibility', 'visible' );
	$terms = array('exclude-from-catalog', 'exclude-from-search');
	wp_set_object_terms($post_id, $terms, 'product_visibility');
	update_post_meta($post_id, '_stock_status', 'instock');
	update_post_meta($post_id, '_wc_min_qty_product', 0);
	update_post_meta($post_id, '_wc_max_qty_product', 1);
	$image_url = (isset($diamond['diamondData']['colorDiamond']) && !empty($diamond['diamondData']['colorDiamond'])) ? $diamond['diamondData']['colorDiamond'] : $diamond['diamondData']['image2'];
	custom_thumbnail_set($post_id, $image_url, 'featured_image');
	echo $post_id;
	die();
}

add_action('wp_ajax_nopriv_add_product_to_cart_dl', 'add_product_to_cart_dl');
add_action('wp_ajax_add_product_to_cart_dl', 'add_product_to_cart_dl');

function mj_taxonomy_add_fancy_color_field($tag)
{
	//check for existing taxonomy meta for term ID
	$t_id      = $tag->term_id;
	$term_meta = get_option("taxonomy_$t_id");
?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Attribute Image Url'); ?></label></th>
		<td>
			<input type="text" name="term_meta[fancy_color_img]" id="term_meta[fancy_color_img]" size="3" style="width:60%;" value="<?php echo $term_meta['fancy_color_img'] ? $term_meta['fancy_color_img'] : ''; ?>"><br />
			<span class="description"><?php _e('Image for Fancy Color: use full url', 'gemfind_diamond_link'); ?></span>
		</td>
	</tr>


<?php
}

add_action('pa_gemfind_fancy_color_add_form_fields', 'mj_taxonomy_add_fancy_color_field', 10, 2);
add_action('pa_gemfind_fancy_color_edit_form_fields', 'mj_taxonomy_add_fancy_color_field', 10, 2);

add_action('edited_pa_gemfind_fancy_color', 'save_fancy_color_field_dl', 10, 2);
function save_fancy_color_field_dl($term_id)
{
	if (isset($_POST['term_meta'])) {
		$t_id      = $term_id;
		$term_meta = get_option("taxonomy_$t_id");
		$cat_keys  = array_keys($_POST['term_meta']);
		foreach ($cat_keys as $key) {
			if (isset($_POST['term_meta'][$key])) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		//save the option array
		update_option("taxonomy_$t_id", $term_meta);
	}
}

function mj_taxonomy_add_ring_collection_field($tag)
{
	//check for existing taxonomy meta for term ID
	$t_id      = $tag->term_id;
	$term_meta = get_option("taxonomy_$t_id");
?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Attribute Image Url'); ?></label></th>
		<td>
			<input type="text" name="term_meta[ring_collection_img]" id="term_meta[ring_collection_img]" size="3" style="width:60%;" value="<?php echo $term_meta['ring_collection_img'] ? $term_meta['ring_collection_img'] : ''; ?>"><br />
			<span class="description"><?php _e('Image for Ring Collection: use full url', 'gemfind_diamond_link'); ?></span>
		</td>
	</tr>
<?php
}

add_action('pa_gemfind_ring_collection_add_form_fields', 'mj_taxonomy_add_ring_collection_field', 10, 2);
add_action('pa_gemfind_ring_collection_edit_form_fields', 'mj_taxonomy_add_ring_collection_field', 10, 2);

add_action('edited_pa_gemfind_ring_collection', 'save_ring_collection_field', 10, 2);
function save_ring_collection_field($term_id)
{
	if (isset($_POST['term_meta'])) {
		$t_id      = $term_id;
		$term_meta = get_option("taxonomy_$t_id");
		$cat_keys  = array_keys($_POST['term_meta']);
		foreach ($cat_keys as $key) {
			if (isset($_POST['term_meta'][$key])) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		//save the option array
		update_option("taxonomy_$t_id", $term_meta);
	}
}

require_once('general-functions-dl.php');
