<?php
/**
 * Displays the return ring filters from the ringfilterapi.
 */
function loadringfilter() {
	include( 'filter.php' );
	die();
}

add_action( 'wp_ajax_nopriv_loadringfilter', 'loadringfilter' );
add_action( 'wp_ajax_loadringfilter', 'loadringfilter' );
/**
 * Displays the returned list of ring settings.
 */
function ringsearch() {
	$filter_data = $_POST['filter_data'];
	// echo '<pre>';
	//print_r($filter_data);
	include( 'results.php' );
	die();
}

add_action( 'wp_ajax_nopriv_ringsearch', 'ringsearch' );
add_action( 'wp_ajax_ringsearch', 'ringsearch' );


function getringvideos() {
	$productId = $_POST['product_id'];
	$requestUrl = 'http://api.jewelcloud.com/api/jewelry/GetVideoUrl?InventoryID='.$productId.'&Type=Jewelry';
	$curl         = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl);
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$response        = curl_exec( $curl );
	$results         = (array) json_decode( $response );
	
	//echo json_encode($results);
	//print_r($results);

	$resultdata = array();
	$resultdata['videoURL'] = $results['videoURL'];
	$resultdata['showVideo'] = $results['showVideo'];
	
	echo json_encode($resultdata);
	exit;
}
add_action( 'wp_ajax_nopriv_getringvideos', 'getringvideos' );
add_action( 'wp_ajax_getringvideos', 'getringvideos' );

function getdiamondvideos() {
	$productId = $_POST['product_id'];
	//$requestUrl = 'http://api.jewelcloud.com/api/jewelry/GetVideoUrl?InventoryID='.$productId.'&Type=Jewelry';
	$requestUrldb = 'http://api.jewelcloud.com/api/jewelry/GetVideoUrl?InventoryID='.$productId.'&Type=Diamond';
	
	$curl         = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrldb);
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$response        = curl_exec( $curl );
	$resultsdb         = (array) json_decode( $response );
	
	// echo json_encode($resultsdb);
	// print_r($resultsdb);

	$resultdatadb = array();
	$resultdatadb['videoURL'] = $resultsdb['videoURL'];
	$resultdatadb['showVideo'] = $resultsdb['showVideo'];
	
	echo json_encode($resultdatadb);
	exit;
}
add_action( 'wp_ajax_nopriv_getdiamondvideos', 'getdiamondvideos' );
add_action( 'wp_ajax_getdiamondvideos', 'getdiamondvideos' );

/**
 * @param $request
 *
 * @return array
 */
function getRings( $requestData ) {
	global $wp_query;
	$request = array();
	// print_r($requestData);
	// exit();
	$options = getOptions_rb();
	foreach ( $requestData as $req ) {
		$request[ $req['name'] ] = $req['value'];
	}
	if ( $request == null ) {
		$rings = [
			'meta'       => [ 'code' => 400, 'message' => __( 'No arguments supplied.' ) ],
			'data'       => [],
			'pagination' => [],
			'perpage'    => getResultsPerPage_rb()
		];

		return $rings;
	}
	if ( ! is_array( $request ) ) {
		$rings = [
			'meta'       => [ 'code' => 400, 'message' => $request ],
			'data'       => [],
			'pagination' => [],
			'perpage'    => getResultsPerPage_rb()
		];

		return $rings;
	}
	$shapeValue    = $collection = $metal = [];
	$shapesContent = $collectionContent = $metalContent = $itemperpage = '';
	// Convert the Shapes list into gemfind form
	if ( array_key_exists( 'selected_shape', $request ) ) {
		$shapesContent = $request["selected_shape"];
	}
	// Convert the carat list into gemfind form
	if ( array_key_exists( 'centerstonemincarat', $request ) ) {
		$centerstonemincarat = $request["centerstonemincarat"];
	}
	// Convert the carat list into gemfind form
	if ( array_key_exists( 'centerstonemaxcarat', $request ) ) {
		$centerstonemaxcarat = $request["centerstonemaxcarat"];
	}
	// Convert the Ring_Collection list into gemfind form
	if ( array_key_exists( 'ring_collection', $request ) ) {
		$collectionContent = $request["ring_collection"];
	}
	// Convert the Ring_Metal list into gemfind form
	if ( array_key_exists( 'ring_metal', $request ) ) {
		$metalContent = $request["ring_metal"];
	}
	// Convert the SettingID list into gemfind form
	if ( isset( $request['settingid'] ) ) {
		$settingid = $request['settingid'];
	} else {
		$settingid = '';
	}
	// Convert the SettingID list into gemfind form
	if ( array_key_exists( 'caratvalue', $request ) ) {
		$caratvalueContent = $request["caratvalue"];
	}
	if ( isset( $request['orderby'] ) && $request['orderby'] == "cost-h-l" ) {
		$orderby   = 'cost';
		$direction = 'desc';
	} else {
		$orderby   = 'cost';
		$direction = 'asc';
	}
	// Create the request array to sumbit to gemfind
	if ( $options['load_from_woocommerce'] == '1' ) {
		$price_from = $request['price[from]'];
		$price_to   = $request['price[to]'];
	} else {
		/*$price_from = ( intval( $request["price"]["from"] ) ) ? intval( $request["price"]["from"] ) : 0;
		$price_to   = ( intval( $request["price"]["to"] ) ) ? intval( $request["price"]["to"] ) : '';*/
		$price_from  = ( $request['price[from]'] ) ? str_replace( ',', '', $request['price[from]'] ) : 0;
		$price_to  = ( $request['price[to]'] ) ? str_replace( ',', '', $request['price[to]'] ) : '';
	}
	if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {
		$IsLabSetting = 1;
	}
	$requestData = [
		'shapes'          => $shapesContent,
		'ring_metal'      => $metalContent,
		'ring_collection' => $collectionContent,
		'price_from'      => $price_from,
		'price_to'        => $price_to,
		'centerstonemincarat'=>$centerstonemincarat,
		'centerstonemaxcarat'=>$centerstonemaxcarat,
		'page_number'     => ( $request['currentpage'] ) ? $request['currentpage'] : '',
		'page_size'       => ( $request['itemperpage'] ) ? $request['itemperpage'] : getResultsPerPage_rb(),
		'sort_by'         => $orderby,
		'sort_direction'  => $direction,
		'settingId'       => $settingid,
		// 'IsLabSetting'    => 1,
		'Filtermode'      => ($request['filtermode']) ? $request['filtermode'] : 'navminedsetting'

	];
	if ( isset( $caratvalueContent ) ) {
		$requestData['caratvalue'] = $request['caratvalue'];
	}
	$result = sendRingRequest( $requestData );
	//echo '<pre>'; print_r($result); echo '</pre>';
	// $tax_query = array( 'relation' => 'AND' );
	// if ( isset( $request['selected_shape'] ) && ! empty( $request['selected_shape'] ) ) {
	// 	$tax_query[] =
	// 		array(
	// 			'taxonomy' => 'pa_gemfind_ring_shape',
	// 			'field'    => 'slug',
	// 			'terms'    => $request['selected_shape'],
	// 			'operator' => 'IN'
	// 		);
	// }	
	$meta_query = array( 'relation' => 'AND' );
	if ( isset( $request['settingid'] ) && ! empty( $request['settingid'] ) ) {
		$meta_query[] = array(
			'key'     => '_sku',
			'value'   => $request['settingid'],
			'compare' => '='
		);
	}
	if ( isset( $request['selected_shape'] ) && ! empty( $request['selected_shape'] ) ) {
		$meta_query[] = array(
			'key'     => 'center_stone_fit',
			'value'   => $request['selected_shape'],
			'compare' => '='
		);
	}
	if ( isset( $request['price[from]'] ) && ! empty( $request['price[to]'] ) ) {
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => array( $request['price[from]'], $request['price[to]'] ),
			'compare' => 'BETWEEN'
		);
	}
	if ( isset( $request['ring_metal'] ) && ! empty( $request['ring_metal'] ) ) {
		$meta_query[] = array(
			'key'     => 'metalType',
			'value'   => $request['ring_metal'],
			'compare' => '='
		);
	}
	$meta_query[] = array(
		'key'     => 'product_type',
		'value'   => 'gemfind-ring',
		'compare' => '='
	);

	$paged = ( $request['currentpage'] ) ? $request['currentpage'] : 1;
	if ( $options['load_from_woocommerce'] == '1' ) {
		unset( $result['rings'] );
		unset( $result['total'] );
		$loop = $args = new WP_Query( array(
			'post_type'      => array( 'product' ),
			'post_status'    => 'publish',
			'posts_per_page' => $request['itemperpage'],
			'paged'          => $paged,
			// 'meta_key'       => 'product_type',
			// 'meta_value'     => 'gemfind-ring',
			//'tax_query'      => $tax_query,
			'meta_query'     => $meta_query,
			'orderby'        => $request['orderby']
		) );
		while ( $loop->have_posts() ) : $loop->the_post();
			global $product;
			$ringInfo              = array();
			$ringInfo['name']      = get_the_title();
			$ringInfo['imageUrl']  = get_the_post_thumbnail_url();
			$ringInfo['settingId'] = get_post_meta( $product->get_ID(), '_sku', true );
			$ringInfo['cost']      = get_post_meta( $product->get_ID(), '_price', true );
			$result['rings'][]     = (object) $ringInfo;
		endwhile;
		wp_reset_query();
		$result['total'] = $loop->found_posts;
	}
	$num = ceil( $result['total'] / getResultsPerPage_rb() );
	if ( $request['currentpage'] > $num ) {
		$requestData['page_number'] = 1;
		$request['currentpage']     = 1;
		//$result                     = sendRingRequest( $requestData );
	}
	if ( $result['rings'] != null || $result['total'] != 0 ) {
		$count = 0;
		if ( $request['currentpage'] > 1 ) {
			$count = ( $request['itemperpage'] ) ? $request['itemperpage'] : getResultsPerPage_rb() * ( $request['currentpage'] - 1 );
		}
		$ring = [
			'meta'       => [ 'code' => 200 ],
			'data'       => $result['rings'],
			'pagination' => [
				'currentpage' => $request['currentpage'],
				'count'       => $count,
				'limit'       => count( $result['rings'] ),
				'total'       => $result['total']
			],
			'perpage'    => ( $request['itemperpage'] ) ? $request['itemperpage'] : getResultsPerPage_rb()
		];
	} else {
		$ring = [
			'meta'       => [ 'code' => 404, 'message' => "No Product Found" ],
			'data'       => [],
			'pagination' => [ 'total' => $result['total'] ],
			'perpage'    => getResultsPerPage_rb()
		];
	}

	return $ring;
}

include_once( 'general-functions-rb.php' ); // Includes all api call functions.
// echo WP_PLUGIN_DIR ; exit();
include_once( WP_PLUGIN_DIR . '/ringBuilder/general-functions-dl.php' );


/**
 * For updatefilter call on selecting shape.
 */
function updatefilter() {
	$requestData = $_POST['filter_data'];
	$data        = array();
	foreach ( $requestData as $req ) {
		$data[ $req['name'] ] = $req['value'];
	}
	$options    = getOptions_rb();
	$dealerID   = $options['dealerid'];
	$collection = $shapes = '';
	if ( isset( $data['ring_collection'] ) ) {
		$collection = $data['ring_collection'];
	}
	if ( isset( $data['ring_shape'] ) ) {
		$shapes = $data['ring_shape'];
	}
	$DealerID = 'DealerID=' . $dealerID . '&';
	if ( $collection && $shapes ) {
		$Collection = 'Collection=' . $collection . '&';
		$Shape      = 'Shape=' . $shapes;
	} else if ( $collection ) {
		$Collection = 'Collection=' . $collection;
		$Shape      = '';
	} else {
		$Shape      = 'Shape=' . $shapes;
		$Collection = '';
	}
	$query_string = $DealerID . $Collection . $Shape;
	$requestUrl   = $options['ringfiltersapi'] . $query_string;
	$requestUrl   = str_replace( ' ', '%20', $requestUrl );
	//echo $requestUrl;
	$curl         = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $requestUrl );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	$responce        = curl_exec( $curl );
	$results         = (array) json_decode( $responce );
	$hiddenmetaltype = $hiddencollection = $hiddenshape = array();
	if ( sizeof( $results ) > 1 && $results[0]->message == 'Success' ) {
		foreach ( $results[1] as $value ) {
			$value = (array) $value;
			foreach ( $value['collections'] as $collection ) {
				$collection = (array) $collection;
				if ( $collection['isActive'] == 0 ) {
					$hiddencollection[] = '#' . strtolower( str_replace( ' ', '', $collection['collectionName'] ) );
				}
			}
			foreach ( $value['shapes'] as $shape ) {
				$shape = (array) $shape;
				if ( $shape['isActive'] == 0 ) {
					$hiddenshape[] = '#' . strtolower( str_replace( ' ', '', $shape['shapeName'] ) );
				}
			}
			foreach ( $value['metalType'] as $metaltype ) {
				$metaltype = (array) $metaltype;
				if ( $metaltype['isActive'] == 0 ) {
					$hiddenmetaltype[] = '#ring_metal_' . strtolower( str_replace( ' ', '', $metaltype['metalType'] ) );
				}
			}
		}
	}
	curl_close( $curl );
	$hiddenshape      = implode( ',', $hiddenshape );
	$hiddencollection = implode( ',', $hiddencollection );
	$hiddenmetaltype  = implode( ',', $hiddenmetaltype );
	$returnData       = [
		'hiddenshape'      => $hiddenshape,
		'hiddencollection' => $hiddencollection,
		'hiddenmetaltype'  => $hiddenmetaltype
	];
	echo json_encode( $returnData );
	// echo('<pre>');
	// print_r($returnData);
	// echo($requestUrl);
	die();
}

add_action( 'wp_ajax_nopriv_updatefilter', 'updatefilter' );
add_action( 'wp_ajax_updatefilter', 'updatefilter' );

/**
 * For creating product in WooCommerce upon add to cart.
 */
function add_product_to_cart_rb() {
	$server_uri = $_POST['ring_name'];
	$ring_path  = rtrim( $server_uri, '/' );
	$ring_path  = end( explode( '/', $ring_path ) );

	$ringData = json_decode( stripslashes( $_POST['ring'] ), true );
	$ringName = $ringData['ringData']['settingName'];
	$ringSize = $_POST['ring_size'];
	$post_id  = get_product_by_sku_rb( $ringData['ringData']['settingId'] );
	if ( isset( $post_id ) && $post_id != '' ) {
		return;
	}
	$post       = array(
		'post_author'  => get_current_user_id(),
		'post_content' => '',
		'post_name'    => $ring_path,
		'post_status'  => "publish",
		'post_title'   => $ringName,
		'post_parent'  => '',
		'post_excerpt' => $ringData['ringData']['description'],
		'post_type'    => "product",
	);
	$shapevalue = $metalTypevalue = $sideStoneQualityvalue = '';
	//Create post
	$post_id = wp_insert_post( $post, $wp_error );
	update_post_meta( $post_id, '_sku', $ringData['ringData']['settingId'] );
	update_post_meta( $post_id, '_price', str_replace( ',', '', $ringData['ringData']['cost'] ) );
	update_post_meta( $post_id, '_regular_price', str_replace( ',', '', $ringData['ringData']['cost'] ) );
	update_post_meta( $post_id, 'original_cost', str_replace( ',', '', $ringData['ringData']['originalCost'] ) );
	update_post_meta( $post_id, 'style_number', $ringData['ringData']['styleNumber'] );
	update_post_meta( $post_id, 'center_stone_fit', $ringData['ringData']['centerStoneFit'] );
	update_post_meta( $post_id, 'center_stone_min_carat', $ringData['ringData']['centerStoneMinCarat'] );
	update_post_meta( $post_id, 'center_stone_max_carat', $ringData['ringData']['centerStoneMaxCarat'] );
	update_post_meta( $post_id, 'metalType', $ringData['ringData']['metalType'] );
	update_post_meta( $post_id, 'metal_id', $ringData['ringData']['metalID'] );
	update_post_meta( $post_id, 'color_id', $ringData['ringData']['colorID'] );
	update_post_meta( $post_id, 'video_url', $ringData['ringData']['videoURL'] );
	update_post_meta( $post_id, 'ring_size', $ringSize );
	update_post_meta( $post_id, 'product_type', 'gemfind-ring' );

	$productAttr = array();
	if ( isset( $ringData['ringData']['shape'] ) ) {
		$shapevalue = $ringData['ringData']['shape'];
	}
	if ( isset( $ringData['ringData']['metalType'] ) ) {
		$metalTypevalue = $ringData['ringData']['metalType'];
	}
	if ( isset( $ringData['ringData']['sideStoneQuality'][0] ) ) {
		$sideStoneQualityvalue = $ringData['ringData']['sideStoneQuality'][0];
	}
	$productAttr['pa_gemfind_product_type']    = 'ring';
	$productAttr['pa_gemfind_ring_collection'] = strtolower( str_replace( " ", "-", $sideStoneQualityvalue ) ); //sidestone
	$productAttr['pa_gemfind_ring_metaltype']  = strtolower( str_replace( " ", "-", $metalTypevalue ) );
	$productAttr['pa_gemfind_ring_shape']      = strtolower( str_replace( " ", "-", $shapevalue ) );

	$product_attributes = array();
	foreach ( $productAttr as $key => $value ) {
		wp_set_object_terms( $post_id, $value, $key, true );
		$product_attributes_meta    = get_post_meta( $post_id, '_product_attributes', true );
		$count                      = ( is_array( $product_attributes_meta ) ) ? count( $product_attributes_meta ) : 0;
		$product_attributes[ $key ] = array(
			'name'        => $key,
			'value'       => $value,
			'position'    => $count, // added
			'is_visible'  => 1,
			'is_taxonomy' => 1
		);
	}
	update_post_meta( $post_id, '_product_attributes', $product_attributes );
	//update_post_meta( $post_id, '_visibility', 'visible' );
	$terms = array( 'exclude-from-catalog', 'exclude-from-search' );
	wp_set_object_terms( $post_id, $terms, 'product_visibility' );
	update_post_meta( $post_id, '_stock_status', 'instock' );
	update_post_meta( $post_id, '_wc_min_qty_product', 0 );
	update_post_meta( $post_id, '_wc_max_qty_product', 1 );
	if ( $ringData['ringData']['mainImageURL'] ) {
		$imageurl = $ringData['ringData']['mainImageURL'];
	}

	if ( isset( $imageurl ) ) {
		custom_thumbnail_set_rb( $post_id, $imageurl, 'featured_image' );
	}
	if ( $ringData['ringData']['extraImage'] ) {
		$galleryIds = array();
		foreach ( $ringData['ringData']['extraImage'] as $image ) {
			$galleryIds[] = custom_thumbnail_set_rb( $post_id, $image, 'gallery_image' );
		}
		update_post_meta( $post_id, '_product_image_gallery', implode( ",", $galleryIds ) );
	}
	echo $post_id;
	die();
}

add_action( 'wp_ajax_nopriv_add_product_to_cart_rb', 'add_product_to_cart_rb' );
add_action( 'wp_ajax_add_product_to_cart_rb', 'add_product_to_cart_rb' );

/**
 * For ringHint form email.
 */
function resultdrophint_rb() {
	$form_data      = $_POST['form_data'];
	$all_options    = getOptions_rb();
	$hint_post_data = array();
	foreach ( $form_data as $data ) {
		$hint_post_data[ $data['name'] ] = $data['value'];
	}
	$store_detail = get_bloginfo( 'name' );
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if($store_logo==''){
		$logo_url_check = et_get_option( 'divi_logo' );
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if($store_logo==''){
			$store_logo = site_url().et_get_option( 'divi_logo' );	
		}
	}
	if ( $hint_post_data && $hint_post_data['name']!="" && $hint_post_data['email']!="" && $hint_post_data['recipient_email']!="") {
		try {
			$ringData = getRingById_rb( $hint_post_data['settingid'], $hint_post_data['shopurl'] );
			if($all_options['admin_email_address']!=""){
				$retaileremail = $all_options['admin_email_address'];
			} else {
				$retaileremail = $ringData['ringData']['vendorEmail'];
			}
			if(isset($hint_post_data['complateRingpage'])){
				$complateRingpage=$hint_post_data['complateRingpage'];
			}
			
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
				'ring_url'        => $hint_post_data['ringurl'],
				'labColumn'=>'',
			);
			// Sender email
			$transport_sender_template = ringhint_email_template_sender($complateRingpage);
			$senderValueReplacement    = array(
				'{{shopurl}}'         => $shopurl,
				'{{shop_logo}}'       => $store_logo,
				'{{shop_logo_alt}}'   => $store_detail->shop->name,
				'{{name}}'            => $hint_post_data['name'],
				'{{gift_reason}}'     => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'   => $hint_post_data['gift_deadline'],
				'{{hint_message}}'    => $hint_post_data['hint_message'],
				'{{ring_url}}'        => $hint_post_data['ringurl'],
				'{{retailername}}'    => $retailername,
				'{{retailerphone}}'   => $ringData['ringData']['vendorPhone'],
				'{{recipient_email}}' => $hint_post_data['recipient_email'],
			);
			$sender_email_body = str_replace( array_keys( $senderValueReplacement ), array_values( $senderValueReplacement ), $transport_sender_template );
			$sender_subject    = "You Sent A Little Hint To ".$hint_post_data['recipient_name'];
			$senderFromAddress = $all_optinos['from_email_address'];
			$headers           = array( 'From: ' . $senderFromAddress . '' );
			$senderToEmail     = $hint_post_data['email'];
			wp_mail( $senderToEmail, $sender_subject, $sender_email_body, $headers );

			// Receiver email
			$transport_receiver_template = ringhint_email_template_receiver($complateRingpage);
			$receiverValueReplacement    = array(
				'{{shopurl}}'        => $shopurl,
				'{{shop_logo}}'      => $store_logo,
				'{{shop_logo_alt}}'  => $store_detail,
				'{{recipient_name}}' => $hint_post_data['recipient_name'],
				'{{gift_reason}}'    => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'  => $hint_post_data['gift_deadline'],
				'{{hint_message}}'   => $hint_post_data['hint_message'],
				'{{ring_url}}'     	 => $hint_post_data['ringurl'],
				'{{retailerphone}}'  => $ringData['ringData']['vendorPhone'],
			);
			$receiver_email_body = str_replace( array_keys( $receiverValueReplacement ), array_values( $receiverValueReplacement ), $transport_receiver_template );
			$receiver_subject     = "A Little Hint from ".$hint_post_data['name'];
			$receiver_fromAddress = $all_optinos['from_email_address'];
			$headers              = array( 'From: ' . $receiver_fromAddress . '' );
			//$headers              = array( 'From: ' . $retailer_fromAddress ,'Reply-To: Somin <jerry@gemfind.com>' );
			$receiver_toEmail     = $hint_post_data['recipient_email'];
			wp_mail( $receiver_toEmail, $receiver_subject, $receiver_email_body, $headers );

			// Retailer email
			$transport_retailer_template = ringhint_email_template_retailer($complateRingpage);
			$retailerValueReplacement    = array(
				'{{shopurl}}'         => $shopurl,
				'{{shop_logo}}'       => $store_logo,
				'{{shop_logo_alt}}'   => $store_detail->shop->name,
				'{{retailername}}'    => $retailername,
				'{{gift_reason}}'     => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'   => $hint_post_data['gift_deadline'],
				'{{hint_message}}'    => $hint_post_data['hint_message'],
				'{{ring_url}}'     	  => $hint_post_data['ringurl'],
				'{{recipient_email}}' => $hint_post_data['recipient_email'],
				'{{name}}'            => $hint_post_data['name'],
				'{{email}}'           => $hint_post_data['email'],
				'{{recipient_name}}'  => $hint_post_data['recipient_name'],
				'{{labColumn}}'  => '',
			);
			$retailer_email_body = str_replace( array_keys( $retailerValueReplacement ), array_values( $retailerValueReplacement ), $transport_retailer_template );
			$retailer_subject     = "Someone Wants To Drop You A Hint";
			$retailer_fromAddress = $all_optinos['from_email_address'];
			//$headers              = array( 'From: ' . $retailer_fromAddress ,'Reply-To: Somin <jerry@gemfind.com>' );
			$headers[]   = 'Reply-To: Somin <jerry@gemfind.com>';
			$retailer_toEmail     = $retaileremail;
			wp_mail( $retailer_toEmail, $retailer_subject, $retailer_email_body, $headers );
			$message = 'Thanks for your submission.';
			$data    = array( 'status' => 1, 'msg' => $message );
			$result  = json_encode( array( 'output' => $data ) );
			echo $result;
			die();
		} catch ( Exception $e ) {
			$message = $e->getMessage();
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_resultdrophint_rb', 'resultdrophint_rb' );
add_action( 'wp_ajax_resultdrophint_rb', 'resultdrophint_rb' );

/**
 * For email a friend.
 */
function resultemailfriend_rb() {
	$form_data              = $_POST['form_data'];
	$all_options            = getOptions_rb();	
	$email_friend_post_data = array();

	foreach ( $form_data as $data ) {
		$email_friend_post_data[ $data['name'] ] = $data['value'];
	}
	$store_detail = get_bloginfo( 'name' );
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if($store_logo==''){
		$logo_url_check = et_get_option( 'divi_logo' );
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if($store_logo==''){
			$store_logo = site_url().et_get_option( 'divi_logo' );	
		}
	}	
	if ( $email_friend_post_data && $email_friend_post_data['email']!="" && $email_friend_post_data['friend_email']!="" ) {
		try {
			$ringData      = getRingById_rb( $email_friend_post_data['settingid'], $email_friend_post_data['shopurl'] );			
			
			if($all_options['admin_email_address']!=""){
				$retaileremail = $all_options['admin_email_address'];
			} else {
				$retaileremail = $ringData['ringData']['vendorEmail'];	
			}	
			$vendorName = ( $ringData['ringData']['vendorName'] ? $ringData['ringData']['vendorName'] : '' );
			if(isset($email_friend_post_data['complateRingpage'])){
				$complateRingpage=$email_friend_post_data['complateRingpage'];
			}
			if( isset( $ringData['ringData']['configurableProduct'][0]->sideStoneQuality ) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality!="" ) {
				$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
				$sideStoneQualityhtm='<tr>
					                  <td class="consumer-title">Side Stone Quality:</td>
					                  <td class="consumer-name">'.$sideStoneQuality.'</td>
					                </tr>';
			}
			if( isset($email_friend_post_data['ringSize']) && $email_friend_post_data['ringSize']!="") {
				$ringSize='<tr>
					                  <td class="consumer-title">Ring Size:</td>
					                  <td class="consumer-name">'.$email_friend_post_data['ringSize'].'</td>
					                </tr>';
			}

			$currency = $ringData['ringData']['currencyFrom'] != 'USD' ? $ringData['ringData']['currencySymbol'] : '$' ;

			if( $ringData['ringData']['showPrice'] == 1){
                 $price  = $ringData['ringData']['cost'] ? $currency . number_format($ringData['ringData']['cost']) : '';
            }else{
                 $price = 'Call For Price';
            }

			$templateVars  = array(
				'name'                => $email_friend_post_data['name'],
				'email'               => $email_friend_post_data['email'],
				'friend_name'         => $email_friend_post_data['friend_name'],
				'friend_email'        => $email_friend_post_data['friend_email'],
				'message'             => $email_friend_post_data['message'],
				'ring_url'            => ( isset( $email_friend_post_data['ringurl'] ) ) ? $email_friend_post_data['ringurl'] : '',
				'setting_id'          => ( isset( $ringData['ringData']['settingId'] ) ) ? $ringData['ringData']['settingId'] : '',
				'stylenumber'         => ( isset( $ringData['ringData']['styleNumber'] ) ) ? $ringData['ringData']['styleNumber'] : '',
				'metaltype'           => ( isset( $ringData['ringData']['metalType'] ) ) ? $ringData['ringData']['metalType'] : '',
				'centerStoneSize'     => ( isset( $ringData['ringData']['configurableProduct'][0]->centerStoneSize ) ) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
				'ringSize'     		  => $ringSize,
				'sideStoneQualityhtm' =>$sideStoneQualityhtm,
				'centerStoneMinCarat' => ( isset( $ringData['ringData']['centerStoneMinCarat'] ) ) ? $ringData['ringData']['centerStoneMinCarat'] : '',
				'centerStoneMaxCarat' => ( isset( $ringData['ringData']['centerStoneMaxCarat'] ) ) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
				'price'               => $price,
				'retailerName'        => ( isset( $ringData['ringData']['vendorName'] ) ) ? $ringData['ringData']['vendorName'] : '',
				'retailerID'          => ( isset( $ringData['ringData']['retailerInfo']->retailerID ) ) ? $ringData['ringData']['retailerInfo']->retailerID : '',
				'retailerEmail'       => ( isset( $ringData['ringData']['retailerInfo']->retailerEmail ) ) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo'   => ( isset( $ringData['ringData']['retailerInfo']->retailerContactNo ) ) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'         => ( isset( $ringData['ringData']['retailerInfo']->retailerFax ) ) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
				'retailerAddress'     => ( isset( $ringData['ringData']['retailerInfo']->retailerAddress ) ) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
				'labColumn'			  =>'',
			);

			$templateValueReplacement = array(
				'{{shopurl}}'             => $shopurl,
				'{{shop_logo}}'           => $store_logo,
				'{{shop_logo_alt}}'       => $store_detail->shop->name,
				'{{name}}'                => $templateVars['name'],
				'{{email}}'               => $templateVars['email'],
				'{{friend_name}}'         => $templateVars['friend_name'],
				'{{friend_email}}'        => $templateVars['friend_email'],
				'{{message}}'             => $templateVars['message'],
				'{{diamond_url}}'         => $templateVars['ring_url'],
				'{{setting_id}}'          => $templateVars['setting_id'],
				'{{stylenumber}}'         => $templateVars['stylenumber'],
				'{{metaltype}}'           => $templateVars['metaltype'],
				'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
				'{{ringSize}}'     		  => $templateVars['ringSize'],
				'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
				'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
				'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
				'{{price}}'               => $templateVars['price'],
				'{{labColumn}}'           => $templateVars['labColumn'],
				'{{retailerID}}'          => $templateVars['retailerID'],
				'{{retailerEmail}}'       => $templateVars['retailerEmail'],
				'{{retailerContactNo}}'   => $templateVars['retailerContactNo'],
				'{{retailerFax}}'         => $templateVars['retailerFax'],
				'{{retailerAddress}}'     => $templateVars['retailerAddress'],
				'{{retailerName}}'        => $templateVars['retailerName'],
				'{{vendorName}}' 		  => $vendorName,
				'{{vendorEmail}}' 		  => $ringData['ringData']['vendorEmail'],
				'{{vendorPhone}}'         => $ringData['ringData']['vendorPhone'],
			);

			// Sender email
			$transport_sender_template = ringemail_friend_email_template_sender($complateRingpage);
			$sender_email_body = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_sender_template );
			$sender_subject    = "A Friend Wants To Share With You";
			$senderFromAddress = $all_optinos['from_email_address'];
			$headers           = array( 'From: ' . $senderFromAddress . '' );
			$senderToEmail     = $email_friend_post_data['email'];			
			wp_mail( $senderToEmail, $sender_subject, $sender_email_body, $headers );

			// Receiver email
			$transport_receiver_template = ringemail_friend_email_template_receiver($complateRingpage);
			$receiver_email_body = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_receiver_template );
			$receiver_subject     = "A Friend Wants To Share With You";
			$receiver_fromAddress = $all_optinos['from_email_address'];
			$headers              = array( 'From: ' . $senderFromAddress . '' );
			$receiver_toEmail     = $email_friend_post_data['friend_email'];
			wp_mail( $receiver_toEmail, $receiver_subject, $receiver_email_body, $headers );

			// Admin email
			$ring_admin_template = ringemail_friend_email_template_admin($complateRingpage);
			$admin_email_body = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $ring_admin_template );
			$sender_subject    = "A Friend Wants To Share With You";
			$senderFromAddress = $all_optinos['from_email_address'];
			$headers           = array( 'From: ' . $senderFromAddress . '' );
			wp_mail( $retaileremail, $sender_subject, $admin_email_body, $headers );

			$message = 'Thanks for your submission.';
			$data    = array( 'status' => 1, 'msg' => $message );
			$result  = json_encode( array( 'output' => $data ) );
			echo $result;
			die();
		} catch ( Exception $e ) {
			$message = $e->getMessage();
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_resultemailfriend_rb', 'resultemailfriend_rb' );
add_action( 'wp_ajax_resultemailfriend_rb', 'resultemailfriend_rb' );

function resultscheview_rb() {
	$form_data          = $_POST['form_data'];
	$all_options        = getOptions_rb();
	$sch_view_post_data = array();
	foreach ( $form_data as $data ) {
		$sch_view_post_data[ $data['name'] ] = $data['value'];
	}
	$store_detail = get_bloginfo( 'name' );
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if($store_logo==''){
		$logo_url_check = et_get_option( 'divi_logo' );
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if($store_logo==''){
			$store_logo = site_url().et_get_option( 'divi_logo' );	
		}
	}

	if ( $sch_view_post_data && $sch_view_post_data['email']!="" && $sch_view_post_data['phone']!="" ) {
		try {
			$ringData = getRingById_rb( $sch_view_post_data['settingid'], $sch_view_post_data['shopurl'] );
			if($all_options['admin_email_address']!=""){
				$retaileremail = $all_options['admin_email_address'];
			} else {
				$retaileremail = $ringData['ringData']['vendorEmail'];	
			}
			if(isset($sch_view_post_data['complateRingpage'])){
				$complateRingpage=$sch_view_post_data['complateRingpage'];
			}

			if( isset( $ringData['ringData']['configurableProduct'][0]->sideStoneQuality ) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality!="" ) {
				$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
				$sideStoneQualityhtm='<tr>
					                  <td class="consumer-title">Side Stone Quality:</td>
					                  <td class="consumer-name">'.$sideStoneQuality.'</td>
					                </tr>';
			}
			if( isset($sch_view_post_data['ringSize']) && $sch_view_post_data['ringSize']!="") {
			$ringSize='<tr>
					                  <td class="consumer-title">Ring Size:</td>
					                  <td class="consumer-name">'.$sch_view_post_data['ringSize'].'</td>
					                </tr>';
			}

			$currency = $ringData['ringData']['currencyFrom'] != 'USD' ? $ringData['ringData']['currencySymbol'] : '$' ;

			if( $ringData['ringData']['showPrice'] == 1){
                 $price  = $ringData['ringData']['cost'] ? $currency . number_format($ringData['ringData']['cost']) : '';
            }else{
                 $price = 'Call For Price';
            }

			$templateVars             = array(
				'name'                => $sch_view_post_data['name'],
				'email'               => $sch_view_post_data['email'],
				'phone'               => $sch_view_post_data['phone'],
				'hint_message'        => $sch_view_post_data['hint_message'],
				'location'            => $sch_view_post_data['location'],
				'avail_date'          => $sch_view_post_data['avail_date'],
				'appnt_time'          => $sch_view_post_data['appnt_time'],
				'ring_url'            => ( isset( $sch_view_post_data['ringurl'] ) ) ? $sch_view_post_data['ringurl'] : '',
				'setting_id'          => ( isset( $ringData['ringData']['settingId'] ) ) ? $ringData['ringData']['settingId'] : '',
				'stylenumber'         => ( isset( $ringData['ringData']['styleNumber'] ) ) ? $ringData['ringData']['styleNumber'] : '',
				'metaltype'           => ( isset( $ringData['ringData']['metalType'] ) ) ? $ringData['ringData']['metalType'] : '',
				'centerStoneSize'     => ( isset( $ringData['ringData']['configurableProduct'][0]->centerStoneSize ) ) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
				'sideStoneQualityhtm' =>$sideStoneQualityhtm,
				'ringSize' 			  =>$ringSize,
				'centerStoneMinCarat' => ( isset( $ringData['ringData']['centerStoneMinCarat'] ) ) ? $ringData['ringData']['centerStoneMinCarat'] : '',
				'centerStoneMaxCarat' => ( isset( $ringData['ringData']['centerStoneMaxCarat'] ) ) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
				'price'               => $price,
				'retailerName'        => ( isset( $ringData['ringData']['vendorName'] ) ) ? $ringData['ringData']['vendorName'] : '',
				'retailerID'          => ( isset( $ringData['ringData']['retailerInfo']->retailerID ) ) ? $ringData['ringData']['retailerInfo']->retailerID : '',
				'retailerEmail'       => ( isset( $ringData['ringData']['retailerInfo']->retailerEmail ) ) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo'   => ( isset( $ringData['ringData']['retailerInfo']->retailerContactNo ) ) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'         => ( isset( $ringData['ringData']['retailerInfo']->retailerFax ) ) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
				'retailerAddress'     => ( isset( $ringData['ringData']['retailerInfo']->retailerAddress ) ) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
				'labColumn'			  =>'',
			);
			$templateValueReplacement = array(
				'{{shopurl}}'             => $shopurl,
				'{{shop_logo}}'           => $store_logo,
				'{{shop_logo_alt}}'       => $store_detail->shop->name,
				'{{name}}'                => $templateVars['name'],
				'{{ring_url}}'            => $templateVars['ring_url'],
				'{{email}}'               => $templateVars['email'],
				'{{phone}}'               => $templateVars['phone'],
				'{{hint_message}}'        => $templateVars['hint_message'],
				'{{location}}'            => $templateVars['location'],
				'{{appnt_time}}'          => $templateVars['appnt_time'],
				'{{avail_date}}'          => $templateVars['avail_date'],
				'{{setting_id}}'          => $templateVars['setting_id'],
				'{{stylenumber}}'         => $templateVars['stylenumber'],
				'{{metaltype}}'           => $templateVars['metaltype'],
				'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
				'{{ringSize}}'     		  => $templateVars['ringSize'],
				'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
				'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
				'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
				'{{price}}'               => $templateVars['price'],
				'{{labColumn}}'           => $templateVars['labColumn'],
				'{{retailerName}}'        => $templateVars['retailerName']
			);
			// Retailer email
			if($retaileremail){
				$transport_retailer_template = ringschedule_view_email_template_admin($complateRingpage);
				$retailer_email_body         = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_retailer_template );
				//echo $retailer_email_body;
				$retailer_subject     = "Request To Schedule A Viewing";
				$retailer_fromAddress = $all_optinos['from_email_address'];
				$retailer_toEmail     = $retaileremail;
				$headers              = array( 'From: ' . $retailer_fromAddress . '' );
				wp_mail( $retailer_toEmail, $retailer_subject, $retailer_email_body, $headers );
			}
			if(isset($templateVars['email']) && $templateVars['email']!=""){
				$transport_user_template = ringschedule_view_email_template_user($complateRingpage);
				$user_email_body         = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_user_template );
				//echo $user_email_body;
				$user_subject     = "Request To Schedule A Viewing";
				$user_fromAddress = $all_optinos['from_email_address'];
				$user_toEmail     = $templateVars['email'];
				$headers = array( 'From: ' . $user_fromAddress . '' );
				wp_mail( $user_toEmail, $user_subject, $user_email_body, $headers );
			}
			$message = 'Thanks for your submission.';
			$data    = array( 'status' => 1, 'msg' => $message );
			$result  = json_encode( array( 'output' => $data ) );
			echo $result;
			die();
		} catch ( Exception $e ) {
			die();
			$message = $e->getMessage();
		}
		$data   = array( 'status' => 0, 'msg' => $message );
		$result = json_encode( array( 'output' => $data ) );
		echo $result;
		die();
	}
	$message = 'Not found all the required fields';
	$data    = array( 'status' => 0, 'msg' => $message );
	$result  = json_encode( array( 'output' => $data ) );
	echo $result;
	die();
}

add_action( 'wp_ajax_nopriv_resultscheview_rb', 'resultscheview_rb' );
add_action( 'wp_ajax_resultscheview_rb', 'resultscheview_rb' );

function resultscheview_cr() {
	$form_data          = $_POST['form_data'];
	$all_options        = getOptions_rb();
	$sch_view_post_data = array();
	foreach ( $form_data as $data ) {
		$sch_view_post_data[ $data['name'] ] = $data['value'];
	}
	$store_detail = get_bloginfo( 'name' );
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if($store_logo==''){
		$logo_url_check = et_get_option( 'divi_logo' );
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if($store_logo==''){
			$store_logo = site_url().et_get_option( 'divi_logo' );	
		}
	}

	if ( $sch_view_post_data && $sch_view_post_data['email']!="" && $sch_view_post_data['phone']!="" ) {
		try {
			$ringData = getRingById_rb( $sch_view_post_data['settingid'], $sch_view_post_data['shopurl'] );
			$diamondData   = getDiamondById_dl($sch_view_post_data['diamondid'], $sch_view_post_data['diamondtype'], $req_post_data['shopurl']);

			if($all_options['admin_email_address']!=""){
				$retaileremail = $all_options['admin_email_address'];
			} else {
				$retaileremail = $ringData['ringData']['vendorEmail'];	
			}
			if(isset($sch_view_post_data['complateRingpage'])){
				$complateRingpage=$sch_view_post_data['complateRingpage'];
			}

			if( isset( $ringData['ringData']['configurableProduct'][0]->sideStoneQuality ) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality!="" ) {
				$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
				$sideStoneQualityhtm='<tr>
					                  <td class="consumer-title">Side Stone Quality:</td>
					                  <td class="consumer-name">'.$sideStoneQuality.'</td>
					                </tr>';
			}
			if( isset($sch_view_post_data['ringSize']) && $sch_view_post_data['ringSize']!="") {
			$ringSize='<tr>
					                  <td class="consumer-title">Ring Size:</td>
					                  <td class="consumer-name">'.$sch_view_post_data['ringSize'].'</td>
					                </tr>';
			}
			$currency = $ringData['ringData']['currencyFrom'] != 'USD' ? $ringData['ringData']['currencySymbol'] : '$' ;

			if( $ringData['ringData']['showPrice'] == 1){
                 $pricerb  = $ringData['ringData']['cost'] ? $currency . number_format($ringData['ringData']['cost']) : '';
            }else{
                 $pricerb = 'Call For Price';
            }

            if( $diamondData['diamondData']['showPrice'] == 1){
                 $price  =$diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
            }else{
                 $price = 'Call For Price';
            }

			$templateVars             = array(
				'name'                => $sch_view_post_data['name'],
				'email'               => $sch_view_post_data['email'],
				'phone'               => $sch_view_post_data['phone'],
				'hint_message'        => $sch_view_post_data['hint_message'],
				'location'            => $sch_view_post_data['location'],
				'avail_date'          => $sch_view_post_data['avail_date'],
				'appnt_time'          => $sch_view_post_data['appnt_time'],
				'ring_url'            => ( isset( $sch_view_post_data['ringurl'] ) ) ? $sch_view_post_data['ringurl'] : '',
				'setting_id'          => ( isset( $ringData['ringData']['settingId'] ) ) ? $ringData['ringData']['settingId'] : '',
				'stylenumber'         => ( isset( $ringData['ringData']['styleNumber'] ) ) ? $ringData['ringData']['styleNumber'] : '',
				'metaltype'           => ( isset( $ringData['ringData']['metalType'] ) ) ? $ringData['ringData']['metalType'] : '',
				'centerStoneSize'     => ( isset( $ringData['ringData']['configurableProduct'][0]->centerStoneSize ) ) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
				'sideStoneQualityhtm' =>$sideStoneQualityhtm,
				'ringSize' 			  =>$ringSize,
				'centerStoneMinCarat' => ( isset( $ringData['ringData']['centerStoneMinCarat'] ) ) ? $ringData['ringData']['centerStoneMinCarat'] : '',
				'centerStoneMaxCarat' => ( isset( $ringData['ringData']['centerStoneMaxCarat'] ) ) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
				'price_rb'            => $pricerb,
				'retailerName'        => ( isset( $ringData['ringData']['vendorName'] ) ) ? $ringData['ringData']['vendorName'] : '',
				'retailerID'          => ( isset( $ringData['ringData']['retailerInfo']->retailerID ) ) ? $ringData['ringData']['retailerInfo']->retailerID : '',
				'retailerEmail'       => ( isset( $ringData['ringData']['retailerInfo']->retailerEmail ) ) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo'   => ( isset( $ringData['ringData']['retailerInfo']->retailerContactNo ) ) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'         => ( isset( $ringData['ringData']['retailerInfo']->retailerFax ) ) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
				'retailerAddress'     => ( isset( $ringData['ringData']['retailerInfo']->retailerAddress ) ) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
				'labColumn'			  =>'',
				'diamond_url'       => (isset($req_post_data['diamondurl'])) ? $req_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'shape'             => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price
			);
			$templateValueReplacement = array(
				'{{shopurl}}'             => $shopurl,
				'{{shop_logo}}'           => $store_logo,
				'{{shop_logo_alt}}'       => $store_detail->shop->name,
				'{{name}}'                => $templateVars['name'],
				'{{ring_url}}'            => $templateVars['ring_url'],
				'{{email}}'               => $templateVars['email'],
				'{{phone}}'               => $templateVars['phone'],
				'{{hint_message}}'        => $templateVars['hint_message'],
				'{{location}}'            => $templateVars['location'],
				'{{appnt_time}}'          => $templateVars['appnt_time'],
				'{{avail_date}}'          => $templateVars['avail_date'],
				'{{setting_id}}'          => $templateVars['setting_id'],
				'{{stylenumber}}'         => $templateVars['stylenumber'],
				'{{metaltype}}'           => $templateVars['metaltype'],
				'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
				'{{ringSize}}'     		  => $templateVars['ringSize'],
				'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
				'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
				'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
				'{{price_rb}}'             => $templateVars['price_rb'],
				'{{labColumn}}'           => $templateVars['labColumn'],
				'{{retailerName}}'        => $templateVars['retailerName'],
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
			);
			// Retailer email
			if($retaileremail){
				$transport_retailer_template = ringschedule_view_email_template_complete_ring_admin($complateRingpage);
				$retailer_email_body         = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_retailer_template );
				//echo $retailer_email_body;
				$retailer_subject     = "Request To Schedule A Viewing";
				$retailer_fromAddress = $all_optinos['from_email_address'];
				$retailer_toEmail     = $retaileremail;
				$headers              = array( 'From: ' . $retailer_fromAddress . '' );
				wp_mail( $retailer_toEmail, $retailer_subject, $retailer_email_body, $headers );
			}
			if(isset($templateVars['email']) && $templateVars['email']!=""){
				$transport_user_template = ringschedule_view_email_template_complete_ring_user($complateRingpage);
				$user_email_body         = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_user_template );
				//echo $user_email_body;
				$user_subject     = "Request To Schedule A Viewing";
				$user_fromAddress = $all_optinos['from_email_address'];
				$user_toEmail     = $templateVars['email'];
				$headers = array( 'From: ' . $user_fromAddress . '' );
				wp_mail( $user_toEmail, $user_subject, $user_email_body, $headers );
			}
			$message = 'Thanks for your submission.';
			$data    = array( 'status' => 1, 'msg' => $message );
			$result  = json_encode( array( 'output' => $data ) );
			echo $result;
			die();
		} catch ( Exception $e ) {
			die();
			$message = $e->getMessage();
		}
		$data   = array( 'status' => 0, 'msg' => $message );
		$result = json_encode( array( 'output' => $data ) );
		echo $result;
		die();
	}
	$message = 'Not found all the required fields';
	$data    = array( 'status' => 0, 'msg' => $message );
	$result  = json_encode( array( 'output' => $data ) );
	echo $result;
	die();
}

add_action( 'wp_ajax_nopriv_resultscheview_cr', 'resultscheview_cr' );
add_action( 'wp_ajax_resultscheview_cr', 'resultscheview_cr' );
/**
 * Will send request info in mail.
 */
function resultreqinfo1_rb() {
	$form_data     = $_POST['form_data'];
	$all_options   = getOptions_rb();
	$req_post_data = array();
	foreach ( $form_data as $data ) {
		$req_post_data[ $data['name'] ] = $data['value'];
	}
	$store_detail = get_bloginfo( 'name' );
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if($store_logo==''){
		$logo_url_check = et_get_option( 'divi_logo' );
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if($store_logo==''){
			$store_logo = site_url().et_get_option( 'divi_logo' );	
		}
	}
	if ( $req_post_data && $req_post_data['email']!="" && $req_post_data['phone']!="" ) {
		try {
			$ringData      = getRingById_rb( $req_post_data['settingid'], $req_post_data['shopurl'] );
			
			if($all_options['admin_email_address']!=""){
				$retaileremail = $all_options['admin_email_address'];
			} else {
				$retaileremail = $ringData['ringData']['vendorEmail'];	
			}
			if(isset($req_post_data['complateRingpage'])){
				$complateRingpage=$req_post_data['complateRingpage'];
			}
			if( isset( $ringData['ringData']['configurableProduct'][0]->sideStoneQuality ) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality!="" ) {
				$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
				$sideStoneQualityhtm='<tr>
					                  <td class="consumer-title">Side Stone Quality:</td>
					                  <td class="consumer-name">'.$sideStoneQuality.'</td>
					                </tr>';
			}
			if( isset($req_post_data['ringSize']) && $req_post_data['ringSize']!="") {
				$ringSize='<tr>
					                  <td class="consumer-title">Ring Size:</td>
					                  <td class="consumer-name">'.$req_post_data['ringSize'].'</td>
					                </tr>';
			}

			$currency = $ringData['ringData']['currencyFrom'] != 'USD' ? $ringData['ringData']['currencySymbol'] : '$' ;

			if( $ringData['ringData']['showPrice'] == 1){
                 $price  = $ringData['ringData']['cost'] ? $currency . number_format($ringData['ringData']['cost']) : '';
            }else{
                 $price = 'Call For Price';
            }
           
			
			$templateVars = array(
				'name'                => ( isset( $req_post_data['name'] ) ) ? $req_post_data['name'] : '',
				'email'               => ( isset( $req_post_data['email'] ) ) ? $req_post_data['email'] : '',
				'phone'               => ( isset( $req_post_data['phone'] ) ) ? $req_post_data['phone'] : '',
				'hint_message'        => ( isset( $req_post_data['hint_message'] ) ) ? $req_post_data['hint_message'] : '',
				'contact_pref'        => ( isset( $req_post_data['contact_pref'] ) ) ? $req_post_data['contact_pref'] : '',
				'ring_url'            => ( isset( $req_post_data['ringurl'] ) ) ? $req_post_data['ringurl'] : '',
				'price'               => $price,
				'setting_id'          => ( isset( $ringData['ringData']['settingId'] ) ) ? $ringData['ringData']['settingId'] : '',
				'stylenumber'         => ( isset( $ringData['ringData']['styleNumber'] ) ) ? $ringData['ringData']['styleNumber'] : '',
				'metaltype'           => ( isset( $ringData['ringData']['metalType'] ) ) ? $ringData['ringData']['metalType'] : '',
				'centerStoneSize'     => ( isset( $ringData['ringData']['configurableProduct'][0]->centerStoneSize ) ) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
				'ringSize'     		  => $ringSize,
				'sideStoneQualityhtm' => $sideStoneQualityhtm,
				'centerStoneMinCarat' => ( isset( $ringData['ringData']['centerStoneMinCarat'] ) ) ? $ringData['ringData']['centerStoneMinCarat'] : '',
				'centerStoneMaxCarat' => ( isset( $ringData['ringData']['centerStoneMaxCarat'] ) ) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
				'retailerName'        => ( isset( $ringData['ringData']['vendorName'] ) ) ? $ringData['ringData']['vendorName'] : '',
				'retailerID'          => ( isset( $ringData['ringData']['retailerInfo']->retailerID ) ) ? $ringData['ringData']['retailerInfo']->retailerID : '',
				'retailerEmail'       => ( isset( $ringData['ringData']['retailerInfo']->retailerEmail ) ) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo'   => ( isset( $ringData['ringData']['retailerInfo']->retailerContactNo ) ) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'         => ( isset( $ringData['ringData']['retailerInfo']->retailerFax ) ) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
				'retailerAddress'     => ( isset( $ringData['ringData']['retailerInfo']->retailerAddress ) ) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
				'labColumn'			  =>'',
			);

			$templateValueReplacement = array(
				'{{shopurl}}'             => $shopurl,
				'{{shop_logo}}'           => $store_logo,
				'{{shop_logo_alt}}'       => $store_detail->shop->name,
				'{{name}}'                => $templateVars['name'],
				'{{email}}'               => $templateVars['email'],
				'{{phone}}'               => $templateVars['phone'],
				'{{hint_message}}'        => $templateVars['hint_message'],
				'{{contact_pref}}'        => $templateVars['contact_pref'],
				'{{ring_url}}'            => $templateVars['ring_url'],
				'{{price}}'               => $templateVars['price'],
				'{{labColumn}}'           => $templateVars['labColumn'],
				'{{setting_id}}'          => $templateVars['setting_id'],
				'{{stylenumber}}'         => $templateVars['stylenumber'],
				'{{metaltype}}'           => $templateVars['metaltype'],
				'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
				'{{ringSize}}'     		  => $templateVars['ringSize'],
				'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
				'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
				'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
				'{{retailerName}}'        => $templateVars['retailerName'],
				'{{retailerEmail}}'       => $templateVars['retailerEmail'],
				'{{retailerContactNo}}'   => $templateVars['retailerContactNo'],
				'{{retailerFax}}'         => $templateVars['retailerFax'],
				'{{retailerAddress}}'     => $templateVars['retailerAddress'],
				'{{retailerID}}'          => $templateVars['retailerID'],
				'{{vendorName}}' 		  => $vendorName,
				'{{vendorEmail}}' 		  => $ringData['ringData']['vendorEmail'],
				'{{vendorPhone}}'         => $ringData['ringData']['vendorPhone'],
			);

			// Retailer email
			$transport_admin_template = ringinfo_email_template_admin($complateRingpage);
			$admin_email_body = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_admin_template );
			$retailer_subject     = "Request For More Info";
			$retailer_fromAddress = $all_optinos['from_email_address'];
			$headers              = array( 'From: ' . $retailer_fromAddress . '' );
			$retailer_toEmail     = $retaileremail;
			wp_mail( $retailer_toEmail, $retailer_subject, $admin_email_body, $headers );

			// Sender email
			$transport_sender_template = ringinfo_email_template_sender($complateRingpage);
			$sender_email_body = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_sender_template );
			$sender_subject     = "Request For More Info";
			$sender_fromAddress = $all_optinos['from_email_address'];
			$headers            = array( 'From: ' . $senderFromAddress . '' );
			$sender_toEmail     = $req_post_data['email'];
			wp_mail( $sender_toEmail, $sender_subject, $sender_email_body, $headers );
			$message = 'Thanks for your submission.';
			$data    = array( 'status' => 1, 'msg' => $message );
			$result  = json_encode( array( 'output' => $data ) );
			echo $result;
			die();

		} catch ( Exception $e ) {
			$message = $e->getMessage();
		}
	}
}

add_action( 'wp_ajax_nopriv_resultreqinfo1_rb', 'resultreqinfo1_rb' );
add_action( 'wp_ajax_resultreqinfo1_rb', 'resultreqinfo1_rb' );


function resultreqinfo1_cr() {
	$form_data     = $_POST['form_data'];
	//print_r($form_data);
	$all_options   = getOptions_rb();
	//print_r($all_options);
	$req_post_data = array();
	//print_r($req_post_data);
	foreach ( $form_data as $data ) {
		$req_post_data[ $data['name'] ] = $data['value'];
	}
	$store_detail = get_bloginfo( 'name' );
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if($store_logo==''){
		$logo_url_check = et_get_option( 'divi_logo' );
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if($store_logo==''){
			$store_logo = site_url().et_get_option( 'divi_logo' );	
		}
	}
	if ( $req_post_data && $req_post_data['name'] !== "" && $req_post_data['email'] && $req_post_data['phone'] && $req_post_data['contact_pref'] ) {
		try {
			$ringData      = getRingById_rb( $req_post_data['settingid'], $req_post_data['shopurl'] );

			$diamondData   = getDiamondById_dl($req_post_data['diamondid'], $req_post_data['diamondtype'], $req_post_data['shopurl']);

			// $diamondData = getDiamondById_dl( $diamondId, $type, $shopurl );
			
			 //print_r($diamondData);

			
			if($all_options['admin_email_address']!=""){
				$retaileremail = $all_options['admin_email_address'];
			} else {
				$retaileremail = $ringData['ringData']['vendorEmail'];	
			}
			if(isset($req_post_data['complateRingpage'])){
				$complateRingpage=$req_post_data['complateRingpage'];
			}
			if( isset( $ringData['ringData']['configurableProduct'][0]->sideStoneQuality ) && $ringData['ringData']['configurableProduct'][0]->sideStoneQuality!="" ) {
				$sideStoneQuality = $ringData['ringData']['configurableProduct'][0]->sideStoneQuality;
				$sideStoneQualityhtm='<tr>
					                  <td class="consumer-title">Side Stone Quality:</td>
					                  <td class="consumer-name">'.$sideStoneQuality.'</td>
					                </tr>';
			}
			if( isset($req_post_data['ringSize']) && $req_post_data['ringSize']!="") {
				$ringSize='<tr>
					                  <td class="consumer-title">Ring Size:</td>
					                  <td class="consumer-name">'.$req_post_data['ringSize'].'</td>
					                </tr>';
			}

  			$currency = $diamondData['diamondData']['currencyFrom'] != 'USD' ? $diamondData['diamondData']['currencySymbol'] : '$' ;

			if( $ringData['ringData']['showPrice'] == 1){
                 $pricerb  = $ringData['ringData']['cost'] ? $currency . number_format($ringData['ringData']['cost']) : '';
            }else{
                 $pricerb = 'Call For Price';
            }

			if( $diamondData['diamondData']['showPrice'] == 1){
                 $price  =$diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
            }else{
                 $price = 'Call For Price';
            }
			
			$templateVars = array(
				'name'                => ( isset( $req_post_data['name'] ) ) ? $req_post_data['name'] : '',
				'email'               => ( isset( $req_post_data['email'] ) ) ? $req_post_data['email'] : '',
				'phone'               => ( isset( $req_post_data['phone'] ) ) ? $req_post_data['phone'] : '',
				'hint_message'        => ( isset( $req_post_data['hint_message'] ) ) ? $req_post_data['hint_message'] : '',
				'contact_pref'        => ( isset( $req_post_data['contact_pref'] ) ) ? $req_post_data['contact_pref'] : '',
				'ring_url'            => ( isset( $req_post_data['ringurl'] ) ) ? $req_post_data['ringurl'] : '',
				'price_rb'            => $pricerb,
				'setting_id'          => ( isset( $ringData['ringData']['settingId'] ) ) ? $ringData['ringData']['settingId'] : '',
				'stylenumber'         => ( isset( $ringData['ringData']['styleNumber'] ) ) ? $ringData['ringData']['styleNumber'] : '',
				'metaltype'           => ( isset( $ringData['ringData']['metalType'] ) ) ? $ringData['ringData']['metalType'] : '',
				'centerStoneSize'     => ( isset( $ringData['ringData']['configurableProduct'][0]->centerStoneSize ) ) ? $ringData['ringData']['configurableProduct'][0]->centerStoneSize : '',
				'ringSize'     		  => $ringSize,
				'sideStoneQualityhtm' => $sideStoneQualityhtm,
				'centerStoneMinCarat' => ( isset( $ringData['ringData']['centerStoneMinCarat'] ) ) ? $ringData['ringData']['centerStoneMinCarat'] : '',
				'centerStoneMaxCarat' => ( isset( $ringData['ringData']['centerStoneMaxCarat'] ) ) ? $ringData['ringData']['centerStoneMaxCarat'] : '',
				'retailerName'        => ( isset( $ringData['ringData']['vendorName'] ) ) ? $ringData['ringData']['vendorName'] : '',
				'retailerID'          => ( isset( $ringData['ringData']['retailerInfo']->retailerID ) ) ? $ringData['ringData']['retailerInfo']->retailerID : '',
				'retailerEmail'       => ( isset( $ringData['ringData']['retailerInfo']->retailerEmail ) ) ? $ringData['ringData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo'   => ( isset( $ringData['ringData']['retailerInfo']->retailerContactNo ) ) ? $ringData['ringData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'         => ( isset( $ringData['ringData']['retailerInfo']->retailerFax ) ) ? $ringData['ringData']['retailerInfo']->retailerFax : '',
				'retailerAddress'     => ( isset( $ringData['ringData']['retailerInfo']->retailerAddress ) ) ? $ringData['ringData']['retailerInfo']->retailerAddress : '',
				'labColumn'			  =>'',
				'diamond_url'       => (isset($req_post_data['diamondurl'])) ? $req_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'shape'             => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price
			);

			$templateValueReplacement = array(
				'{{shopurl}}'             => $shopurl,
				'{{shop_logo}}'           => $store_logo,
				'{{shop_logo_alt}}'       => $store_detail->shop->name,
				'{{name}}'                => $templateVars['name'],
				'{{email}}'               => $templateVars['email'],
				'{{phone}}'               => $templateVars['phone'],
				'{{hint_message}}'        => $templateVars['hint_message'],
				'{{contact_pref}}'        => $templateVars['contact_pref'],
				'{{ring_url}}'            => $templateVars['ring_url'],
				'{{price_rb}}'               => $templateVars['price_rb'],
				'{{labColumn}}'           => $templateVars['labColumn'],
				'{{setting_id}}'          => $templateVars['setting_id'],
				'{{stylenumber}}'         => $templateVars['stylenumber'],
				'{{metaltype}}'           => $templateVars['metaltype'],
				'{{centerStoneSize}}'     => $templateVars['centerStoneSize'],
				'{{ringSize}}'     		  => $templateVars['ringSize'],
				'{{sideStoneQualityhtm}}' => $templateVars['sideStoneQualityhtm'],
				'{{centerStoneMinCarat}}' => $templateVars['centerStoneMinCarat'],
				'{{centerStoneMaxCarat}}' => $templateVars['centerStoneMaxCarat'],
				'{{retailerName}}'        => $templateVars['retailerName'],
				'{{retailerEmail}}'       => $templateVars['retailerEmail'],
				'{{retailerContactNo}}'   => $templateVars['retailerContactNo'],
				'{{retailerFax}}'         => $templateVars['retailerFax'],
				'{{retailerAddress}}'     => $templateVars['retailerAddress'],
				'{{retailerID}}'          => $templateVars['retailerID'],
				'{{vendorName}}' 		  => $vendorName,
				'{{vendorEmail}}' 		  => $ringData['ringData']['vendorEmail'],
				'{{vendorPhone}}'         => $ringData['ringData']['vendorPhone'],
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
			);

			//print_r($templateValueReplacement);

			// Retailer email
			$transport_admin_template = ringinfo_email_template_admin_complete_ring($complateRingpage);
			$admin_email_body = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_admin_template );
			$retailer_subject     = "Request For More Info";
			$retailer_fromAddress = $all_optinos['from_email_address'];
			$headers              = array( 'From: ' . $retailer_fromAddress . '' );
			$retailer_toEmail     = $retaileremail;
			wp_mail( $retailer_toEmail, $retailer_subject, $admin_email_body, $headers );

			// Sender email
			$transport_sender_template = ringinfo_email_template_sender_complete_ring($complateRingpage);
			$sender_email_body = str_replace( array_keys( $templateValueReplacement ), array_values( $templateValueReplacement ), $transport_sender_template );
			$sender_subject     = "Request For More Info";
			$sender_fromAddress = $all_optinos['from_email_address'];
			$headers            = array( 'From: ' . $senderFromAddress . '' );
			$sender_toEmail     = $req_post_data['email'];
			wp_mail( $sender_toEmail, $sender_subject, $sender_email_body, $headers );
			$message = 'Thanks for your submission.';
			$data    = array( 'status' => 1, 'msg' => $message );
			$result  = json_encode( array( 'output' => $data ) );
			echo $result;
			die();

		} catch ( Exception $e ) {
			$message = $e->getMessage();
		}
	}
}

add_action( 'wp_ajax_nopriv_resultreqinfo1_cr', 'resultreqinfo1_cr' );
add_action( 'wp_ajax_resultreqinfo1_cr', 'resultreqinfo1_cr' );
function add_diamondtoring_rb() {

	$ringinfo     = array(
		'settingid'      => $_POST['settingId'],
		'ringsize'       => $_POST['ringsizewithdia'],
		'shapes'         => $_POST['settingId'],
		'caratmax'       => $_POST['ringmaxcarat'],
		'caratmin'       => $_POST['ringmincarat'],
		'centerstonefit' => strtolower( $_POST['centerStoneFit'] )
	);
	$ringinfo     = json_encode( $ringinfo );
	$cookiePath   = parse_url( get_option( 'siteurl' ), PHP_URL_PATH );
	$cookieHost   = parse_url( get_option( 'siteurl' ), PHP_URL_HOST );
	$cookieExpiry = 86400;
	setcookie( 'ringsetting', $ringinfo, $cookieExpiry, '/', $cookieHost );
	$diamondCookie = $_COOKIE['diamondsetting'];
	if ( $diamondCookie ) {
		$url = '/ringbuilder/settings/completering';
	} else {
		$url = '/ringbuilder/settings/';
	}
	$url = get_option( 'siteurl' ) . $url;
	wp_redirect( $url );
	exit;
}

add_action( 'wp_ajax_nopriv_add_diamondtoring_rb', 'add_diamondtoring_rb' );
add_action( 'wp_ajax_add_diamondtoring_rb', 'add_diamondtoring_rb' );

function addNewRing( $id, $ringsize, $diamondId, $shopurl ) {
	$ringData = getRingById_rb( $id, $shopurl );
	global $woocommerce, $wpdb;
	if ( ! count( $ringData ) ) {
		_e( 'Something went wrong. Please try again later!', 'gemfind-ring-builder' );
	}

	$shapevalue  = $metalTypevalue = $sideStoneQualityvalue = '';
	$optionsdata = array();

	$diamondName = $ringData['ringData']['settingName'];

	if ( isset( $ringData['ringData']['shape'] ) ) {
		$shapevalue = $ringData['ringData']['settingName'];
	}

	if ( isset( $ringData['ringData']['metalType'] ) ) {
		$metalTypevalue = $ringData['ringData']['metalType'];
	}

	if ( isset( $ringData['ringData']['sideStoneQuality'][0] ) ) {
		$sideStoneQualityvalue = $ringData['ringData']['sideStoneQuality'][0];
	}

	$product_id = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='" . $id . "'" );
	if ( is_null( $product_id ) ) {
		if ( $metalTypevalue ) {
			$metalType = strtolower( str_replace( ' ', '-', $metalTypevalue ) ) . '-metaltype-';
		} else {
			$metalType = '';
		}

		$ringName = strtolower( str_replace( ' ', '-', $ringData['ringData']['settingName'] ) );
		$sku      = '-sku-' . str_replace( ' ', '-', $ringData['ringData']['settingId'] );
		$urlkey   = $metalType . $ringName . $sku;

		$post    = array(
			'post_author'  => get_current_user_id(),
			'post_content' => '',
			'post_name'    => $urlkey,
			'post_status'  => "publish",
			'post_title'   => $ringData['ringData']['settingName'],
			'post_parent'  => '',
			'post_excerpt' => $ringData['ringData']['description'],
			'post_type'    => "product",
		);
		$post_id = wp_insert_post( $post, $wp_error );

		update_post_meta( $post_id, '_sku', $ringData['ringData']['settingId'] );
		update_post_meta( $post_id, '_price', str_replace( ',', '', $ringData['ringData']['cost'] ) );
		update_post_meta( $post_id, '_regular_price', str_replace( ',', '', $ringData['ringData']['cost'] ) );
		update_post_meta( $post_id, 'original_cost', str_replace( ',', '', $ringData['ringData']['originalCost'] ) );
		update_post_meta( $post_id, 'style_number', $ringData['ringData']['styleNumber'] );
		update_post_meta( $post_id, 'center_stone_fit', $ringData['ringData']['centerStoneFit'] );
		update_post_meta( $post_id, 'center_stone_min_carat', $ringData['ringData']['centerStoneMinCarat'] );
		update_post_meta( $post_id, 'center_stone_max_carat', $ringData['ringData']['centerStoneMaxCarat'] );
		update_post_meta( $post_id, 'metal_id', $ringData['ringData']['metalID'] );
		update_post_meta( $post_id, 'color_id', $ringData['ringData']['colorID'] );
		update_post_meta( $post_id, 'video_url', $ringData['ringData']['videoURL'] );
		update_post_meta( $post_id, 'ring_size', $ringSize );
		update_post_meta( $post_id, 'product_type', 'gemfind-ring' );

		$productAttr = array();
		if ( isset( $ringData['ringData']['shape'] ) ) {
			$shapevalue = $ringData['ringData']['shape'];
		}
		if ( isset( $ringData['ringData']['metalType'] ) ) {
			$metalTypevalue = $ringData['ringData']['metalType'];
		}
		if ( isset( $ringData['ringData']['sideStoneQuality'][0] ) ) {
			$sideStoneQualityvalue = $ringData['ringData']['sideStoneQuality'][0];
		}

		$productAttr['pa_gemfind_product_type']    = 'ring';
		$productAttr['pa_gemfind_ring_collection'] = strtolower( str_replace( " ", "-", $sideStoneQualityvalue ) ); //sidestone
		$productAttr['pa_gemfind_ring_metaltype']  = strtolower( str_replace( " ", "-", $metalTypevalue ) );
		$productAttr['pa_gemfind_ring_shape']      = strtolower( str_replace( " ", "-", $shapevalue ) );

		$product_attributes = array();
		foreach ( $productAttr as $key => $value ) {
			wp_set_object_terms( $post_id, $value, $key, true );
			$product_attributes_meta    = get_post_meta( $post_id, '_product_attributes', true );
			$count                      = ( is_array( $product_attributes_meta ) ) ? count( $product_attributes_meta ) : 0;
			$product_attributes[ $key ] = array(
				'name'        => $key,
				'value'       => $value,
				'position'    => $count, // added
				'is_visible'  => 1,
				'is_taxonomy' => 1
			);
		}
		update_post_meta( $post_id, '_product_attributes', $product_attributes );
		//update_post_meta( $post_id, '_visibility', 'visible' );
		$terms = array( 'exclude-from-catalog', 'exclude-from-search' );
		wp_set_object_terms( $post_id, $terms, 'product_visibility' );
		update_post_meta( $post_id, '_stock_status', 'instock' );
		update_post_meta( $post_id, '_wc_min_qty_product', 0 );
		update_post_meta( $post_id, '_wc_max_qty_product', 1 );

		if ( $ringData['ringData']['mainImageURL'] ) {
			$imageurl = $ringData['ringData']['mainImageURL'];
		}

		if ( isset( $imageurl ) ) {
			custom_thumbnail_set_rb( $post_id, $imageurl, 'featured_image' );
		}
		if ( $ringData['ringData']['extraImage'] ) {
			$galleryIds = array();
			foreach ( $ringData['ringData']['extraImage'] as $image ) {
				$galleryIds[] = custom_thumbnail_set_rb( $post_id, $image, 'gallery_image' );
			}
			update_post_meta( $post_id, '_product_image_gallery', implode( ",", $galleryIds ) );
		}

		$woocommerce->cart->add_to_cart( $post_id );
	} else {
		$woocommerce->cart->add_to_cart( $product_id );
	}
}

function completepurchase_rb() {
	global $woocommerce, $wpdb;
	$ringId              = $_POST['ringId'];
	$ringsizesettingonly = $_POST['ringsizesettingonly'];
	$diamondId           = $_POST['diamondId'];

	$shopurl     = site_url();
	$diamondcookie = json_decode( stripslashes( $_COOKIE['_wp_diamondsetting'] ), 1 );
	$type        = '';
	if($diamondcookie[0]['islabcreated']!=''){
		$type = 'labcreated';
	}	
	$diamondData = getDiamondById_dl( $diamondId, $type, $shopurl );

	addNewRing( $ringId, $ringsizesettingonly, $diamondId, $shopurl );

	$diamondName = $diamondData['diamondData']['mainHeader'];
	$shapevalue  = $cutvalue = '';

	if ( isset( $diamondData['diamondData']['shape'] ) ) {
		$urlshape = str_replace( ' ', '-', $diamondData['diamondData']['shape'] ) . '-shape-';
	} else {
		$urlshape = '';
	}
	if ( isset( $diamondData['diamondData']['carat'] ) ) {
		$urlcarat = str_replace( ' ', '-', $diamondData['diamondData']['carat'] ) . '-carat-';
	} else {
		$urlcarat = '';
	}
	if ( isset( $diamondData['diamondData']['color'] ) ) {
		$urlcolor = str_replace( ' ', '-', $diamondData['diamondData']['color'] ) . '-color-';
	} else {
		$urlcolor = '';
	}
	if ( isset( $diamondData['diamondData']['clarity'] ) ) {
		$urlclarity = str_replace( ' ', '-', $diamondData['diamondData']['clarity'] ) . '-clarity-';
	} else {
		$urlclarity = '';
	}
	if ( isset( $diamondData['diamondData']['cut'] ) ) {
		$urlcut = str_replace( ' ', '-', $diamondData['diamondData']['cut'] ) . '-cut-';
	} else {
		$urlcut = '';
	}
	if ( isset( $diamondData['diamondData']['certificate'] ) ) {
		$urlcertificate = str_replace( ' ', '-', $diamondData['diamondData']['certificate'] ) . '-certificate-';
	} else {
		$urlcertificate = '';
	}

	$urlstring = strtolower( $urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcertificate . 'sku-' . $diamondData['diamondData']['diamondId'] );

	$diamondProductId = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='" . $diamondId . "'" );
	if ( is_null( $diamondProductId ) ) {
		$post    = array(
			'post_author'  => get_current_user_id(),
			'post_content' => '',
			'post_name'    => $urlstring,
			'post_status'  => "publish",
			'post_title'   => $diamondName,
			'post_parent'  => '',
			'post_excerpt' => $diamondData['diamondData']['subHeader'],
			'post_type'    => "product",
		);
		$post_id = wp_insert_post( $post, $wp_error );
		update_post_meta( $post_id, '_sku', $diamondData['diamondData']['diamondId'] );
		update_post_meta( $post_id, '_regular_price', $diamondData['diamondData']['fltPrice'] );
		update_post_meta( $post_id, 'costPerCarat', $diamondData['diamondData']['costPerCarat'] );
		update_post_meta( $post_id, 'image1', $diamondData['diamondData']['image1'] );
		update_post_meta( $post_id, 'image2', $diamondData['diamondData']['image2'] );
		update_post_meta( $post_id, 'videoFileName', $diamondData['diamondData']['videoFileName'] );
		update_post_meta( $post_id, 'certificateNo', $diamondData['diamondData']['certificateNo'] );
		update_post_meta( $post_id, 'certificateUrl', $diamondData['diamondData']['certificateUrl'] );
		update_post_meta( $post_id, 'certificateIconUrl', $diamondData['diamondData']['certificateIconUrl'] );
		update_post_meta( $post_id, 'measurement', $diamondData['diamondData']['measurement'] );
		update_post_meta( $post_id, 'origin', $diamondData['diamondData']['origin'] );
		update_post_meta( $post_id, 'gridle', $diamondData['diamondData']['gridle'] );
		update_post_meta( $post_id, 'culet', $diamondData['diamondData']['culet'] );
		update_post_meta( $post_id, 'cut', $diamondData['diamondData']['cut'] );
		update_post_meta( $post_id, '_price', $diamondData['diamondData']['fltPrice'] );
		update_post_meta( $post_id, 'Color', $diamondData['diamondData']['color'] );
		update_post_meta( $post_id, 'ClarityID', $diamondData['diamondData']['clarity'] );
		update_post_meta( $post_id, 'CutGrade', $diamondData['diamondData']['cut'] );
		update_post_meta( $post_id, 'TableMeasure', $diamondData['diamondData']['table'] );
		update_post_meta( $post_id, 'Polish', $diamondData['diamondData']['polish'] );
		update_post_meta( $post_id, 'Symmetry', $diamondData['diamondData']['symmetry'] );
		update_post_meta( $post_id, 'Measurements', $diamondData['diamondData']['measurement'] );
		update_post_meta( $post_id, 'Certificate', $diamondData['diamondData']['certificate'] );
		update_post_meta( $post_id, 'shape', $diamondData['diamondData']['shape'] );
		update_post_meta( $post_id, 'product_type', 'gemfind' );

		$productAttr = array();

		if ( isset( $diamondData['diamondData']['fancyColorMainBody'] ) && ! empty( $diamondData['diamondData']['fancyColorMainBody'] ) ) {
			$productAttr['pa_gemfind_fancy_certificate']  = $diamondData['diamondData']['certificate'];
			$productAttr['pa_gemfind_fancy_clarity']      = $diamondData['diamondData']['clarity'];
			$productAttr['pa_gemfind_fancy_color']        = $diamondData['diamondData']['fancyColorMainBody'];
			$productAttr['pa_gemfind_fancy_fluorescence'] = $diamondData['diamondData']['fluorescence'];
			$productAttr['pa_gemfind_fancy_polish']       = $diamondData['diamondondData']['polish'];
			$productAttr['pa_gemfind_fancy_shape']        = $diamondData['diamondData']['shape'];
			$productAttr['pa_gemfind_fancy_symmetry']     = $diamondData['diamondData']['symmetry'];
			$productAttr['pa_gemfind_fancy_video']        = $diamondData['diamondData']['videoFileName'];
			$productAttr['pa_gemfind_fancy_intensity']    = $diamondData['diamondData']['fancyColorIntensity'];
			$productAttr['pa_gemfind_fancy_origin']       = $diamondData['diamondData']['origin'];
			update_post_meta( $post_id, 'caratWeightFancy', $diamondData['diamondData']['caratWeight'] );
			update_post_meta( $post_id, 'tableFancy', $diamondData['diamondData']['table'] );
			update_post_meta( $post_id, 'depthFancy', $diamondData['diamondData']['depth'] );
			update_post_meta( $post_id, 'FltPriceFancy', $diamondData['diamondData']['fltPrice'] );
			update_post_meta( $post_id, 'fancyColorIntensity', $diamondData['diamondData']['fancyColorIntensity'] );
		} else {
			$productAttr['pa_gemfind_certificate']  = $diamondData['diamondData']['certificate'];
			$productAttr['pa_gemfind_clarity']      = $diamondData['diamondData']['clarity'];
			$productAttr['pa_gemfind_color']        = $diamondData['diamondData']['color'];
			$productAttr['pa_gemfind_fluorescence'] = $diamondData['diamondData']['fluorescence'];
			$productAttr['pa_gemfind_polish']       = $diamondData['diamondondData']['polish'];
			$productAttr['pa_gemfind_shape']        = $diamondData['diamondData']['shape'];
			$productAttr['pa_gemfind_symmetry']     = $diamondData['diamondData']['symmetry'];
			$productAttr['pa_gemfind_video']        = $diamondData['diamondData']['videoFileName'];
			$productAttr['pa_gemfind_cut']          = $diamondData['diamondData']['cut'];
			update_post_meta( $post_id, 'caratWeight', $diamondData['diamondData']['caratWeight'] );
			update_post_meta( $post_id, 'table', $diamondData['diamondData']['table'] );
			update_post_meta( $post_id, 'depth', $diamondData['diamondData']['depth'] );
			update_post_meta( $post_id, 'FltPrice', $diamondData['diamondData']['fltPrice'] );
			update_post_meta( $post_id, 'productType', 'standard' );
		}
		$product_attributes = array();
		foreach ( $productAttr as $key => $value ) {
			wp_set_object_terms( $post_id, $value, $key, true );
			$product_attributes_meta    = get_post_meta( $post_id, '_product_attributes', true );
			$count                      = ( is_array( $product_attributes_meta ) ) ? count( $product_attributes_meta ) : 0;
			$product_attributes[ $key ] = array(
				'name'        => $key,
				'value'       => $value,
				'position'    => $count, // added
				'is_visible'  => 1,
				'is_taxonomy' => 1
			);
		}
		update_post_meta( $post_id, '_product_attributes', $product_attributes );
		//update_post_meta( $post_id, '_visibility', 'visible' );
		$terms = array( 'exclude-from-catalog', 'exclude-from-search' );
		wp_set_object_terms( $post_id, $terms, 'product_visibility' );
		update_post_meta( $post_id, '_stock_status', 'instock' );
		update_post_meta( $post_id, '_wc_min_qty_product', 0 );
		update_post_meta( $post_id, '_wc_max_qty_product', 1 );

		if ( $diamondData['diamondData']['image2'] ) {
			$imageurl = $diamondData['diamondData']['image2'];
		}

		if ( isset( $imageurl ) ) {
			custom_thumbnail_set_rb( $post_id, $imageurl, 'featured_image' );
		}

		if ( $diamondData['diamondData']['image1'] ) {
			$galleryId = custom_thumbnail_set_rb( $post_id, $diamondData['diamondData']['image1'], 'gallery_image' );
			update_post_meta( $post_id, '_product_image_gallery', $galleryId );
		}

		$woocommerce->cart->add_to_cart( $post_id );

		unset( $_COOKIE['_wp_diamondsetting'] );
		setcookie( '_wp_diamondsetting', '', time() - ( 15 * 60 ) );

		unset( $_COOKIE['_wp_ringsetting'] );
		setcookie( '_wp_ringsetting', '', time() - ( 15 * 60 ) );

		//$cartUrl = $shopurl . '/cart';
		//wp_redirect( $cartUrl );
		die();
	} else {
		$woocommerce->cart->add_to_cart( $diamondProductId );
	}
	die();
}

add_action( 'wp_ajax_nopriv_completepurchase_rb', 'completepurchase_rb' );
add_action( 'wp_ajax_completepurchase_rb', 'completepurchase_rb' );

/**
 * For setting up backvalue when clicking on ring from landing page.
 */
function ringurl_rb() {
// $shop = $this->input->post('shopurl');
// $pathprefixshop = $this->input->post('path_shop_url');
	$id = $_POST['id'];
//$route = "https://".$shop.$pathprefixshop."/settings/view/path/";
	$route = get_site_url() . "/ringbuilder/settings/product/";
	if ( $id ) {
		$settings          = getRingById_rb( $id, get_site_url() );
		$collectionContent = $id = $_POST['requesteddata'];
		if ( sizeof( $settings['ringData'] ) > 0 ) {
			if ( sizeof( $settings['ringData']['configurableProduct'] ) > 0 ) {
				foreach ( $settings['ringData']['configurableProduct'] as $value ) {
					$value                                                                   = (array) $value;
					$metalarray[ strtolower( str_replace( ' ', '', $value['metalType'] ) ) ] = $value['gfInventoryId'];
				}
			}

			if ( $collectionContent != '' ) {
				$metaltype = strtolower( str_replace( ' ', '-', $collectionContent ) ) . '-metaltype-';
				$name      = strtolower( str_replace( ' ', '-', $settings['ringData']['settingName'] ) );
				$name      = strtolower( str_replace( '&', '%26', $name ) );
				$sku       = '-sku-' . str_replace( ' ', '-', $metalarray[ strtolower( str_replace( ' ', '', $collectionContent ) ) ] );
				if ( $metalarray[ strtolower( str_replace( ' ', '', $collectionContent ) ) ] ) {

					$url = getUrl_rb( $route, [ 'path' => $metaltype . $name . $sku, '_secure' => true ] );
				} else {

					$url = getUrl_rb( $route, [ 'path' => $name . $sku, '_secure' => true ] );
				}
			} else {
				$metaltype = '14k-white-gold-metaltype-';
				$name      = strtolower( str_replace( ' ', '-', $settings['ringData']['settingName'] ) );
				$name      = strtolower( str_replace( '&', '%26', $name ) );
				$sku       = '-sku-' . str_replace( ' ', '-', $metalarray['14kwhitegold'] );
				if ( isset( $metalarray['14kwhitegold'] ) ) {
					$url = getUrl_rb( $route, [ 'path' => $metaltype . $name . $sku, '_secure' => true ] );
				} else {
					$url = getUrl_rb( $route, [ 'path' => $name . $sku, '_secure' => true ] );
				}
			}
			$returnData = [ 'diamondviewurl' => $url ];
			echo json_encode( $returnData );
			die();
		} else {
			$returnData = [ 'diamondviewurl' => '' ];
			echo json_encode( $returnData );
			die();
		}
	} else {
		$main_route = get_site_url() . "/ringbuilder/settings/";
		$returnData = [ 'diamondviewurl' => getUrl_rb( $main_route, [ '_secure' => true ] ) ];
		echo json_encode( $returnData );
		die();
	}
	die();
}

add_action( 'wp_ajax_nopriv_ringurl_rb', 'ringurl_rb' );
add_action( 'wp_ajax_ringurl_rb', 'ringurl_rb' );

/**
 * Checks if video url is ok or not.
 */
function is_video_404() {
	$url = $_POST['videoUrl'];
// Use get_headers() function
	$header_check = @get_headers( $url );
	if ( isset( $header_check ) && ! empty( $header_check ) ) {
		$headers = @get_headers( $url );
	} else {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => $url,
			CURLOPT_HEADER         => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_NOBODY         => true
		) );

		$headers = explode( "\n", curl_exec( $curl ) );
		curl_close( $curl );
	}

// Use condition to check the existence of URL
	if ( $headers && strpos( $headers[0], '200' ) ) {
		$status = 200;
	} else {
		$status = 404;
	}

// Display result
	echo( $status );
	die();
}

add_action( 'wp_ajax_nopriv_is_video_404', 'is_video_404' );
add_action( 'wp_ajax_is_video_404', 'is_video_404' );
?>