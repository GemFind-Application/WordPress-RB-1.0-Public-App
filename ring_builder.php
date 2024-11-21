<?php
/*
  Plugin Name: Gemfind Ring Builder
  Plugin URI:  https://www.gemfind.com/
  Description: Plugin for listing and filtering out diamonds on the basis of third pary APIs
  Version:     2.5.0
  Author:      Gemfind
  Author URI:  https://www.gemfind.com/
  Text Domain: gemfind-ring-builder
  Domain Path: /languages
  License:     GPL2
*/
global $wpdb;
define( 'RING_BUILDER_Path', plugin_dir_path( __FILE__ ) );
define( 'RING_BUILDER_URL', plugin_dir_url( __FILE__ ) );
function gemfind_required_plugin_dl() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) ) {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action( 'admin_notices', 'gemfind_required_woocommerce_notice_dl' );
			deactivate_plugins( plugin_basename( __FILE__ ) );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		} else {
		}
	}
}

add_action( 'admin_init', 'gemfind_required_plugin_dl' );
function gemfind_required_woocommerce_notice_dl() {
	_e( '<div class="error"><p><strong>Gemfind Ring Builder is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for Gemfind Ring Builder to work. Please install & activate WooCommerce</a></p></div>', 'diamondlink' );
}

register_activation_hook( __FILE__, 'gemfind_install_dl' );
function gemfind_install_dl() {

	$data = array(
		'dealerid'                => '',
		'from_email_address'      => '',
		'admin_email_address'     => '',
		'dealerauthapi'           => 'http://api.jewelcloud.com/api/RingBuilder/AccountAuthentication',
		'ringfiltersapi'          => 'http://api.jewelcloud.com/api/RingBuilder/GetFilters?',
		'mountinglistapi'         => 'http://api.jewelcloud.com/api/RingBuilder/GetMountingList?',
		'mountingdetailapi'       => 'http://api.jewelcloud.com/api/RingBuilder/GetMountingDetail?',
		'ringstylesettingsapi'    => 'http://api.jewelcloud.com/api/RingBuilder/GetStyleSetting?',
		'navigationapi'           => 'http://api.jewelcloud.com/api/RingBuilder/GetNavigation?',
		'navigationapirb'         => 'http://api.jewelcloud.com/api/RingBuilder/GetRBNavigation?',
		'filterapi'               => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondFilter?',
		'filterapifancy'          => 'http://api.jewelcloud.com/api/RingBuilder/GetColorDiamondFilter?',
		'diamondlistapi'          => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamond?',
		'diamondlistapifancy'     => 'http://api.jewelcloud.com/api/RingBuilder/GetColorDiamond?',
		'diamondshapeapi'         => 'http://api.jewelcloud.com/api/ringbuilder/GetShapeByColorFilter?',
		'diamonddetailapi'        => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondDetail?',
		'stylesettingapi'         => 'http://api.jewelcloud.com/api/RingBuilder/GetStyleSetting?',
		'diamondsoptionapi'       => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondsJCOptions?',
		'enable_hint'             => 'true',
		'enable_email_friend'     => 'true',
		'enable_schedule_viewing' => 'true',
		'enable_more_info'        => 'true',
		'enable_print'            => 'true',
		'default_view'	  		  => 'list',
		'shop_logo'               => '',
		'carat_ranges'			  => '{"0.25":[0.2,0.3],"0.33":[0.31,0.4],"0.50":[0.41,0.65],"0.75":[0.66,0.85],"1.00":[0.86,1.14],"1.25":[1.15,1.40],"1.50":[1.41,1.65],"1.75":[1.66,1.85],"2.00":[1.86,2.15],"2.25":[2.16,2.45],"2.50":[2.46,2.65],"2.75":[2.66,2.85],"3.00":[2.85,3.25]}',
		'load_from_woocommerce'   => 0
	);

	$gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
	if(empty($gemfind_ring_builder)){
		update_option( 'gemfind_ring_builder', $data );
	}
	
	$id_rb = wp_insert_post( array(
		'post_title'  => 'Ring Builder',
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_name'   => 'ringbuilder'
	) );
	define( 'RING_BUILDER_PAGE_ID', $id_rb );
	update_option( 'ring_builder_page_id', $id_rb );
	$id_dl = wp_insert_post( array(
		'post_title'  => 'Diamond Link',
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_name'   => 'diamondlink',
		'post_parent' => $id_rb
	) );
	update_option( 'diamondlink_rb_page_id', $id_dl );
	$id_settings = wp_insert_post( array(
		'post_title'  => 'Settings',
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_name'   => 'settings',
		'post_parent' => $id_rb
	) );
	update_option( 'settings_page_id', $id_settings );
	
	add_rewrite_rule(
		'ringbuilder/settings/product/([^&]+)',
		'index.php?pagename=ringbuilder/settings&settings=$matches[1]',
		'top' );
	add_rewrite_rule(
		'ringbuilder/diamondlink/product/([^&]+)',
		'index.php?pagename=ringbuilder/diamondlink&diamondlink=$matches[1]',
		'top' );
	// add_rewrite_rule(
	// 	'ringbuilder/settings/navlabsetting',
	// 	'index.php?pagename=ringbuilder/settings&type=$matches[1]',
	// 	'top' );

	add_rewrite_rule(
		'ringbuilder/diamondlink/([^&]+)',
		'index.php?pagename=ringbuilder/diamondlink&completering=$matches[1]',
		'top' );
	add_rewrite_rule(
		'ringbuilder/settings/islabsettings/([^&]+)',
		'index.php?pagename=ringbuilder/settings&settings=$matches[1]',
		'top' );
	flush_rewrite_rules();
}

/*
 * Plugin Uninstall Hook
 */
register_deactivation_hook( __FILE__, 'gemfind_uninstall_dl' );
function gemfind_uninstall_dl() {	
	$id_rb = get_option( 'ring_builder_page_id' );
	$id_dl = get_option( 'diamondlink_rb_page_id' );
	$id_settings = get_option( 'settings_page_id' );
	$ids = array( $id_rb, $id_dl, $id_settings );
	// $the_slug = array( 'diamondlink', 'ringbuilder', 'settings' );
	// foreach ( $the_slug as $slug ) {
	// 	$args     = array(
	// 		'name'        => $slug,
	// 		'post_type'   => 'page',
	// 		'post_status' => 'publish',
	// 		'numberposts' => 1
	// 	);
	// 	$my_posts = get_posts( $args );
	// 	if ( $my_posts ) :
	// 		if ( $args['name'] == 'diamondlink' && $my_posts[0]->post_parent != 0 ) {
	// 			wp_delete_post( $my_posts[0]->ID, true );
	// 		} else {			
	// 			wp_delete_post( $my_posts[0]->ID, true );
	// 		}
	// 	endif;
	// }
	foreach( $ids as $key=>$value ) {
		wp_delete_post( $value, true );
	}
}
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

include( RING_BUILDER_Path . "/settings/gemfind-template-assign.php" );
add_action( 'admin_init', 'create_product_meta_fields_dl' );
function create_product_meta_fields_dl() {
	// Display Fields
	add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields_dl' );
	// Save Fields
	add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_dl_save_dl' );
	$the_slug = array( 'settings', 'diamondlink' );
	foreach ( $the_slug as $slug ) {
		$args     = array(
			'name'        => $slug,
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => 1
		);
		$my_posts = get_posts( $args );		
		if ( $slug == 'settings' ) {
			$template = 'template-ringbuilder.php';
		} elseif ( $slug == 'diamondlink' && $my_posts[0]->post_parent != 0 ) {
			$template = 'template-diamondlist-ringbuilder.php';
		}
		if ( $my_posts && $my_posts[0]->post_parent != 0 ) :
			// Update post diamondlink
			$my_post = array(
				'ID'            => $my_posts[0]->ID,
				'page_template' => $template,
			);
			// Update the post into the database
			wp_update_post( $my_post );
		endif;
	}
}

add_filter( 'wc_get_price_decimals', 'change_prices_decimals_dl', 20, 1 );
function change_prices_decimals_dl( $decimals ) {
	if ( is_cart() || is_checkout() ) {
		$decimals = 2;
	}

	return $decimals;
}

function get_attribute_id_from_name_dl( $name ) {
	global $wpdb;
	$attribute_id = $wpdb->get_var( "SELECT attribute_id
  		FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
  		WHERE attribute_name LIKE '$name'" );

	return $attribute_id;
}

/**
 * For creating WooCommerce product custom fields.
 */
function woocommerce_product_custom_fields_dl() {
	global $woocommerce, $post;
	echo '<div class="product_custom_field">';
	// Price Per Carat Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'costPerCarat',
			'placeholder' => 'Price Per Carat Field',
			'label'       => __( 'Price Per Carat Field', 'woocommerce' ),
			'desc_tip'    => 'true'
		)
	);
	//Diamond Image 1
	woocommerce_wp_text_input(
		array(
			'id'          => 'image1',
			'placeholder' => 'Diamond Image1 Field',
			'label'       => __( 'Diamond Image1 Field', 'woocommerce' )
		)
	);
	//Diamond Image 2
	woocommerce_wp_text_input(
		array(
			'id'          => 'image2',
			'placeholder' => 'Diamond Image2 Field',
			'label'       => __( 'Diamond Image2 Field', 'woocommerce' )
		)
	);
	//Video Filename Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'videoFileName',
			'placeholder' => 'Video File Name Field',
			'label'       => __( 'Video File Name Field', 'woocommerce' )
		)
	);
	//Certificate Number Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'certificateNo',
			'placeholder' => 'Certificate Number Field',
			'label'       => __( 'Certificate Number Field', 'woocommerce' )
		)
	);
	//Certificate URL Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'certificateUrl',
			'placeholder' => 'Certificate URL Field',
			'label'       => __( 'Certificate URL Field', 'woocommerce' )
		)
	);
	//Certificate icon URL Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'certificateIconUrl',
			'placeholder' => 'Certificate icon URL Field',
			'label'       => __( 'Certificate icon URL Field', 'woocommerce' )
		)
	);
	//Carat Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'caratWeight',
			'placeholder' => 'Carat Field',
			'label'       => __( 'Carat Field', 'woocommerce' )
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'          => 'caratWeightFancy',
			'placeholder' => 'Carat Fancy Field',
			'label'       => __( 'Carat Fancy Field', 'woocommerce' )
		)
	);
	//Depth Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'depth',
			'placeholder' => 'Depth Field',
			'label'       => __( 'Depth Field', 'woocommerce' )
		)
	);

	//Table Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'table',
			'placeholder' => 'Table Field',
			'label'       => __( 'Table Field', 'woocommerce' )
		)
	);
	//Table Fancy
	woocommerce_wp_text_input(
		array(
			'id'          => 'tableFancy',
			'placeholder' => 'Table Fancy Field',
			'label'       => __( 'Table Fancy Field', 'woocommerce' )
		)
	);
	//Measurement Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'measurement',
			'placeholder' => 'Measurement Field',
			'label'       => __( 'Measurement Field', 'woocommerce' )
		)
	);
	//Product Type
	woocommerce_wp_text_input(
		array(
			'id'          => 'productType',
			'placeholder' => 'Product Type',
			'label'       => __( 'Product Type', 'woocommerce' )
		)
	);
	//Origin Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'origin',
			'placeholder' => 'Origin Field',
			'label'       => __( 'Origin Field', 'woocommerce' )
		)
	);
	//Gridle Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'gridle',
			'placeholder' => 'Gridle Field',
			'label'       => __( 'Gridle Field', 'woocommerce' )
		)
	);
	//Culet Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'culet',
			'placeholder' => 'Culet Field',
			'label'       => __( 'Culet Field', 'woocommerce' )
		)
	);
	//Cut Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'cut',
			'placeholder' => 'Cut Field',
			'label'       => __( 'Cut Field', 'woocommerce' )
		)
	);
	//Intensity Field
	woocommerce_wp_text_input(
		array(
			'id'          => 'fancyColorIntensity',
			'placeholder' => 'Fancy Color Intensity Field',
			'label'       => __( 'Fancy Color Intensity Field', 'woocommerce' )
		)
	);
	echo '</div>';
	echo '<div class="product_custom_field ring_fields">';
	//Style Number
	woocommerce_wp_text_input(
		array(
			'id'          => 'style_number',
			'placeholder' => 'Style Number',
			'label'       => __( 'Style Number', 'woocommerce' )
		)
	);
	//Center Stone Fit
	woocommerce_wp_text_input(
		array(
			'id'          => 'center_stone_fit',
			'placeholder' => 'Center Stone Fit',
			'label'       => __( 'Center Stone Fit', 'woocommerce' )
		)
	);
	//Center Stone Min Carat
	woocommerce_wp_text_input(
		array(
			'id'          => 'center_stone_min_carat',
			'placeholder' => 'Center Stone Min Carat',
			'label'       => __( 'Center Stone Min Carat', 'woocommerce' )
		)
	);
	//Center Stone Max Carat
	woocommerce_wp_text_input(
		array(
			'id'          => 'center_stone_max_carat',
			'placeholder' => 'Center Stone Max Carat',
			'label'       => __( 'Center Stone Max Carat', 'woocommerce' )
		)
	);
	//MetalID
	woocommerce_wp_text_input(
		array(
			'id'          => 'metal_id',
			'placeholder' => 'MetalID',
			'label'       => __( 'MetalID', 'woocommerce' )
		)
	);
	//ColorID
	woocommerce_wp_text_input(
		array(
			'id'          => 'color_id',
			'placeholder' => 'ColorID',
			'label'       => __( 'ColorID', 'woocommerce' )
		)
	);
	//Ring Size
	woocommerce_wp_text_input(
		array(
			'id'          => 'ring_size',
			'placeholder' => 'Ring Size',
			'label'       => __( 'Ring Size', 'woocommerce' )
		)
	);
	//Video Url
	woocommerce_wp_text_input(
		array(
			'id'          => 'video_url',
			'placeholder' => 'Video Url',
			'label'       => __( 'Video Url', 'woocommerce' )
		)
	);
	//Metal Type
	woocommerce_wp_text_input(
		array(
			'id'          => 'metalType',
			'placeholder' => 'Metal Type',
			'label'       => __( 'metalType', 'woocommerce' )
		)
	);
	echo '</div>';
}

/**
 * For saving WooCommerce custom fields created.
 */
function woocommerce_product_custom_fields_dl_save_dl( $post_id ) {
	// Cost Per Carat Text Field
	$woocommerce_costPerCarat = $_POST['costPerCarat'];
	if ( ! empty( $woocommerce_costPerCarat ) ) {
		update_post_meta( $post_id, 'costPerCarat', esc_attr( $woocommerce_costPerCarat ) );
	}
	// Diamond Image1 Field
	$woocommerce_image1 = $_POST['image1'];
	if ( ! empty( $woocommerce_image1 ) ) {
		update_post_meta( $post_id, 'image1', esc_attr( $woocommerce_image1 ) );
	}
	// Diamond Image2 Field
	$woocommerce_image2 = $_POST['image2'];
	if ( ! empty( $woocommerce_image2 ) ) {
		update_post_meta( $post_id, 'image2', esc_attr( $woocommerce_image2 ) );
	}
	// Video File Name Field
	$woocommerce_videoFileName = $_POST['videoFileName'];
	if ( ! empty( $woocommerce_videoFileName ) ) {
		update_post_meta( $post_id, 'videoFileName', esc_attr( $woocommerce_videoFileName ) );
	}
	// Certificate No Field
	$woocommerce_certificateNo = $_POST['certificateNo'];
	if ( ! empty( $woocommerce_certificateNo ) ) {
		update_post_meta( $post_id, 'certificateNo', esc_attr( $woocommerce_certificateNo ) );
	}
	// Certificate URL Field
	$woocommerce_certificateUrl = $_POST['certificateUrl'];
	if ( ! empty( $woocommerce_certificateUrl ) ) {
		update_post_meta( $post_id, 'certificateUrl', esc_attr( $woocommerce_certificateUrl ) );
	}
	// Certificate icon URL Field
	$woocommerce_certificateIconUrl = $_POST['certificateIconUrl'];
	if ( ! empty( $woocommerce_certificateIconUrl ) ) {
		update_post_meta( $post_id, 'certificateIconUrl', esc_attr( $woocommerce_certificateIconUrl ) );
	}
	// Carat Field
	$woocommerce_caratWeight = $_POST['caratWeight'];
	if ( ! empty( $woocommerce_caratWeight ) ) {
		update_post_meta( $post_id, 'caratWeight', esc_attr( $woocommerce_caratWeight ) );
	}
	// Depth Field
	$woocommerce_depth = $_POST['depth'];
	if ( ! empty( $woocommerce_depth ) ) {
		update_post_meta( $post_id, 'depth', esc_attr( $woocommerce_depth ) );
	}
	// Table Field
	$woocommerce_table = $_POST['table'];
	if ( ! empty( $woocommerce_table ) ) {
		update_post_meta( $post_id, 'table', esc_attr( $woocommerce_table ) );
	}
	// Measurement Field
	$woocommerce_measurement = $_POST['measurement'];
	if ( ! empty( $woocommerce_measurement ) ) {
		update_post_meta( $post_id, 'measurement', esc_attr( $woocommerce_measurement ) );
	}
	// Origin Field
	$woocommerce_origin = $_POST['origin'];
	if ( ! empty( $woocommerce_origin ) ) {
		update_post_meta( $post_id, 'origin', esc_attr( $woocommerce_origin ) );
	}
	// Gridle Field
	$woocommerce_gridle = $_POST['gridle'];
	if ( ! empty( $woocommerce_gridle ) ) {
		update_post_meta( $post_id, 'gridle', esc_attr( $woocommerce_gridle ) );
	}
	// Culet Field
	$woocommerce_culet = $_POST['culet'];
	if ( ! empty( $woocommerce_culet ) ) {
		update_post_meta( $post_id, 'culet', esc_attr( $woocommerce_culet ) );
	}
	// Cut Field
	$woocommerce_cut = $_POST['cut'];
	if ( ! empty( $woocommerce_cut ) ) {
		update_post_meta( $post_id, 'cut', esc_attr( $woocommerce_cut ) );
	}
	// Internal Use Link Field
	$woocommerce_cut = $_POST['internalUselink'];
	if ( ! empty( $woocommerce_cut ) ) {
		update_post_meta( $post_id, 'internalUselink', esc_attr( $woocommerce_cut ) );
	}
	// Carat Weight Fancy Field
	$woocommerce_cut = $_POST['caratWeightFancy'];
	if ( ! empty( $woocommerce_cut ) ) {
		update_post_meta( $post_id, 'caratWeightFancy', esc_attr( $woocommerce_cut ) );
	}
	// Depth Fancy Field
	$woocommerce_cut = $_POST['depthFancy'];
	if ( ! empty( $woocommerce_cut ) ) {
		update_post_meta( $post_id, 'depthFancy', esc_attr( $woocommerce_cut ) );
	}
	// Table Fancy Field
	$woocommerce_cut = $_POST['tableFancy'];
	if ( ! empty( $woocommerce_cut ) ) {
		update_post_meta( $post_id, 'tableFancy', esc_attr( $woocommerce_cut ) );
	}
	// Product Type Field
	$woocommerce_cut = $_POST['productType'];
	if ( ! empty( $woocommerce_cut ) ) {
		update_post_meta( $post_id, 'productType', esc_attr( $woocommerce_cut ) );
	}
	// metalType Field
	$woocommerce_metal_type = $_POST['metalType'];
	if ( ! empty( $woocommerce_metal_type ) ) {
		update_post_meta( $post_id, 'metalType', esc_attr( $woocommerce_metal_type ) );
	}
}

/*
 * Admin Menu Pages
 */
add_action( 'admin_menu', 'gemfind_menu_page_dl' );
function gemfind_menu_page_dl() {
	add_menu_page( 'Diamond Search', 'GemFind Ring Builder', 'manage_options', 'gemfind_ring_builder_dl', 'gemfind_ring_builder_dl', 'dashicons-admin-generic', 90 );
}

function gemfind_ring_builder_dl() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once RING_BUILDER_Path . '/admin/add_gemfind_ring_builder_rb.php';
}

/*add_action( 'admin_enqueue_scripts', 'gemfind_enqueue_admin_scripts_dl' );
function gemfind_enqueue_admin_scripts_dl() {
	//wp_enqueue_style( 'gemfind-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), '1.0.1', false );
	wp_enqueue_script( 'gemfind-custom', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js', array( 'jquery' ), time(), false );
}*/

/**
 * Enqueues necessary scripts and styes for this plugin's front end section
 */
function gemfind_enqueue_scripts_dl() {	
	$uri      = $_SERVER['REQUEST_URI'];
	$path     = parse_url( $uri, PHP_URL_PATH );		
	$filename = pathinfo( $path, PATHINFO_FILENAME );
	$parent = wp_get_post_parent_id( get_the_ID() );	
	if( is_page( 'ringbuilder' ) ||	( is_page( 'diamondlink' ) && $parent != 0 ) || is_page( 'settings' ) ) {
		wp_enqueue_style( 'gemfind-ring-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css', array(), '3.3.5', false );		
		wp_enqueue_style( 'gemfind-ring-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '4.4.0', false );
		wp_enqueue_style( 'gemfind-ring-jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), '1.12.1', false );
		wp_enqueue_style( 'gemfind-ring-custom', plugin_dir_url( __FILE__ ) . 'assets/css/custom.css', array(), time(), false );
		//wp_enqueue_style( 'gemfind-ring-diamond-custom', plugin_dir_url( __FILE__ ) . 'assets/css/custom_dl.css', array(), time(), false );
		wp_enqueue_style( 'gemfind-ring-diamondlist-head', plugin_dir_url( __FILE__ ) . 'assets/css/diamond_list_head.css', array(), time(), false );
		wp_enqueue_script( 'gemfind-ring-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js', array('jquery-core'), '1.4.1', false );
		wp_enqueue_script( 'gemfind-ring-jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array('jquery-core'), '1.12.1', false );
		wp_enqueue_script( 'gemfind-ring-bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array('jquery-core'), '3.3.5', false );
		wp_enqueue_script( 'gemfind-ring-sumoselect-js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.sumoselect.js', array('jquery-core'), '1.0.1', false );
		wp_enqueue_style( 'gemfind-ring-sumoselect', plugin_dir_url( __FILE__ ) . 'assets/css/sumoselect.css', array(), '1.0.1', false );
		wp_enqueue_script( 'gemfind-ring-touchit', plugin_dir_url( __FILE__ ) . 'assets/js/touchit.js', array('jquery-core'), '1.0.1', false );
		wp_enqueue_script( 'gemfind-ring-jquery-vaildate', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.validate.min.js', array('jquery-core'), time(), false );		
		wp_enqueue_style( 'gemfind-ring-nouiSlider-css', 'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/10.0.0/nouislider.css?ver=10.0.0', array(), false );
		wp_enqueue_script( 'gemfind-ring-jquery-wNumb-js', 'https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.2.0/wNumb.js', array(), false );
		wp_enqueue_script( 'gemfind-ring-jquery-nouiSlider-js', 'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/10.0.0/nouislider.js?ver=10.0.0', array(), false );
		if ( is_page( 'ringbuilder' ) || is_page( 'settings' ) || $filename == 'completering' ) {
			wp_enqueue_script( 'gemfind-ring-main', plugin_dir_url( __FILE__ ) . 'assets/js/ringmain.js', array(), time(), false );
			    wp_enqueue_script( 'gemfind-ring-main', plugin_dir_url( __FILE__ ) . 'assets/js/ringmain.js', array(), time(), false );

			wp_enqueue_script( 'gemfind-ring-view', plugin_dir_url( __FILE__ ) . 'assets/js/ringview.js', array(), time(), false );
			if ( $filename != 'compare' ) {
				wp_enqueue_script( 'gemfind-ringbuilder-ajax', plugin_dir_url( __FILE__ ) . 'assets/js/ringbuilder.js', array(), time(), false );
			}
			wp_localize_script( 'gemfind-ringbuilder-ajax', 'myajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		} elseif ( is_page( 'diamondlink' ) ) {
			if ( $filename != 'compare' && strpos( $path, 'ringbuilder/diamondlink' ) !== false ) {	
				wp_dequeue_script( 'gemfind-list-ajax' );
				wp_enqueue_script( 'gemfind-list-ajax', plugin_dir_url( __FILE__ ) . 'assets/js/list.js', array(), time(), false );
			}
			if( strpos( $path, 'ringbuilder/diamondlink' ) !== false ) {
				//echo "here now"; exit;
				wp_dequeue_script( 'gemfind-diamond-main' );
				wp_enqueue_script( 'gemfind-diamond-main', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', array(), time(), false );
				wp_enqueue_script( 'gemfind-diamond-view', plugin_dir_url( __FILE__ ) . 'assets/js/view.js', array(), time(), false );
			}			
			
			wp_localize_script( 'gemfind-list-ajax', 'myajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'gemfind_enqueue_scripts_dl' );

/**
 * Dequeue the twentynineteen theme style.
 *
 * Hooked to the wp_print_styles action, with a late priority (100),
 * so that it is after the style was enqueued.
 */
function wp_67472455_dl() {
	if ( function_exists( 'is_woocommerce' ) && ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
		wp_dequeue_style( 'twentynineteen-style' );
	}
}

add_action( 'wp_print_styles', 'wp_67472455_dl', 100 );
/**
 * For adding tag to the url.
 */
add_action( 'init', 'wpse26388_rewrites_init_dl' );
function wpse26388_rewrites_init_dl() {
	add_rewrite_rule(
		'ringbuilder/settings/product/([^&]+)',
		'index.php?pagename=ringbuilder/settings&settings=$matches[1]',
		'top' );
	add_rewrite_rule(
		'ringbuilder/diamondlink/product/([^&]+)',
		'index.php?pagename=ringbuilder/diamondlink&diamondlink=$matches[1]',
		'top' );
	// add_rewrite_rule(
	// 	'ringbuilder/settings/navlabsetting',
	// 	'index.php?pagename=ringbuilder/settings&type=$matches[1]',
	// 	'top' );

	add_rewrite_rule(
		'ringbuilder/diamondlink/([^&]+)',
		'index.php?pagename=ringbuilder/diamondlink&completering=$matches[1]',
		'top' );
	
	add_rewrite_rule(
		'ringbuilder/settings/islabsettings/([^&]+)',
		'index.php?pagename=ringbuilder/settings&settings=$matches[1]',
		'top' );

	add_rewrite_rule(
		'ringbuilder/settings/style/([^&]+)',
		'index.php?pagename=ringbuilder/settings&style=$matches[1]',
		'top' );

	add_rewrite_rule(
		'ringbuilder/settings/shape/([^&]+)/?$',
		'index.php?pagename=ringbuilder/settings&shape=$matches[1]',
		'top' );


	add_rewrite_rule(
		'ringbuilder/settings/metal/([^&]+)',
		'index.php?pagename=ringbuilder/settings&metal=$matches[1]',
		'top' );

}

/**
 * For defining added query tag as part of query_vars
 */
add_filter( 'query_vars', 'wpse26388_query_vars_dl' );
function wpse26388_query_vars_dl( $query_vars ) {
	$query_vars[] = 'compare';
	$query_vars[] = 'settings';
	$query_vars[] = 'diamondlink';
	$query_vars[] = 'completering';		
	return $query_vars;
}

/**
 * For redirecting pretty permalink created to required templates.
 */
function prefix_url_rewrite_templates_dl() {
	global $wp_query;	
	if ( get_query_var( 'completering' ) && get_query_var( 'completering' ) == 'compare' ) {
		add_filter( 'template_include', function () {
			return plugin_dir_path( __FILE__ ) . 'compare_diamond.php';
		} );
		return false;
	}	
	if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {	
		add_filter( 'template_include', function () {
			return plugin_dir_path( __FILE__ ) . '/settings/template-ringbuilder.php';
		} );
		return false;
	}
	if ( get_query_var( 'settings' ) ) {	
		add_filter( 'template_include', function () {
			return plugin_dir_path( __FILE__ ) . 'view_rings.php';
		} );
		return false;
	}
	if ( get_query_var( 'diamondlink' ) ) {
		add_filter( 'template_include', function () {
			return plugin_dir_path( __FILE__ ) . 'view_diamonds.php';
		} );
		return false;
	}

	if ( get_query_var( 'completering' ) && get_query_var( 'completering' ) == 'completering' ) {
		add_filter( 'template_include', function () {
			return plugin_dir_path( __FILE__ ) . 'complete-rings.php';
		} );
		return false;
	}
	if ( is_page( 'ringbuilder' ) ) {
		$settings_page = get_page_by_path( 'ringbuilder/settings' );
		if ( is_page( 'ringbuilder' ) ) {
			wp_redirect( get_permalink( $settings_page->ID ) );
		}
	}	
}

add_action( 'template_redirect', 'prefix_url_rewrite_templates_dl' );
include_once( RING_BUILDER_Path . 'includes-api-functions.php' ); // Includes all api call functions.
include_once( RING_BUILDER_Path . '/settings/includes-api-functions-rb.php' ); // Includes all api call functions.
add_action( 'init', 'disable_cart_link_dl' );
function disable_cart_link_dl() {
	if ( ! is_admin() ) {
		function wc_cart_item_name_hyperlink_dl( $link_text, $product_data ) {
			$title        = get_the_title( $product_data['product_id'] );
			$product_type = get_post_meta( $product_data['product_id'], 'product_type', true );
			if ( $product_type == 'gemfind' || $product_type == 'gemfind-ring' ) {
				return sprintf( '%s', $title );
			} else {
				return $link_text;
			}
		}

		/* Filter to override cart_item_name */
		add_filter( 'woocommerce_cart_item_name', 'wc_cart_item_name_hyperlink_dl', 10, 2 );
	}
}

add_action( 'wp_head', 'my_custom_styles_dl', 100 );
function my_custom_styles_dl() {
	echo "<style>.woocommerce-cart-form__cart-item.cart_item .product-thumbnail{pointer-events:none;}</style>";

	//Added code for update API url
	$gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
	if(empty(trim($gemfind_ring_builder['dealerid']))){
		$gemfind_ring_builder['dealerid']='1089';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['dealerauthapi']))){
		$gemfind_ring_builder['dealerauthapi']='http://api.jewelcloud.com/api/RingBuilder/AccountAuthentication';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['ringfiltersapi']))){
		$gemfind_ring_builder['ringfiltersapi']='http://api.jewelcloud.com/api/RingBuilder/GetFilters?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['mountinglistapi']))){
		$gemfind_ring_builder['mountinglistapi']='http://api.jewelcloud.com/api/RingBuilder/GetMountingList?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['mountingdetailapi']))){
		$gemfind_ring_builder['mountingdetailapi']='http://api.jewelcloud.com/api/RingBuilder/GetMountingDetail?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['ringstylesettingsapi']))){
		$gemfind_ring_builder['ringstylesettingsapi']='http://api.jewelcloud.com/api/RingBuilder/GetStyleSetting?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['navigationapi']))){
		$gemfind_ring_builder['navigationapi']='http://api.jewelcloud.com/api/RingBuilder/GetNavigation?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['navigationapirb']))){
		$gemfind_ring_builder['navigationapirb']='http://api.jewelcloud.com/api/RingBuilder/GetRBNavigation?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['filterapi']))){
		$gemfind_ring_builder['filterapi']='http://api.jewelcloud.com/api/RingBuilder/GetDiamondFilter?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['filterapifancy']))){
		$gemfind_ring_builder['filterapifancy']='http://api.jewelcloud.com/api/RingBuilder/GetColorDiamondFilter?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['diamondlistapi']))){
		$gemfind_ring_builder['diamondlistapi']='http://api.jewelcloud.com/api/RingBuilder/GetDiamond?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['diamondlistapifancy']))){
		$gemfind_ring_builder['diamondlistapifancy']='http://api.jewelcloud.com/api/RingBuilder/GetColorDiamond?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['diamondshapeapi']))){
		$gemfind_ring_builder['diamondshapeapi']='http://api.jewelcloud.com/api/ringbuilder/GetShapeByColorFilter?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['diamonddetailapi']))){
		$gemfind_ring_builder['diamonddetailapi']='http://api.jewelcloud.com/api/RingBuilder/GetDiamondDetail?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['stylesettingapi']))){
		$gemfind_ring_builder['stylesettingapi']='http://api.jewelcloud.com/api/RingBuilder/GetStyleSetting?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['diamondsoptionapi']))){
		$gemfind_ring_builder['diamondsoptionapi']='http://api.jewelcloud.com/api/RingBuilder/GetDiamondsJCOptions?';
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
	if(empty(trim($gemfind_ring_builder['carat_ranges']))){
		$carat_ranges='{
	"0.25":[0.2,0.3],
	"0.33":[0.31,0.4],
	"0.50":[0.41,0.65],
	"0.75":[0.66,0.85],
	"1.00":[0.86,1.14],
	"1.25":[1.15,1.40],
	"1.50":[1.41,1.65],
	"1.75":[1.66,1.85],	
	"2.00":[1.86,2.15],	
	"2.25":[2.16,2.45],	
	"2.50":[2.46,2.65],	
	"2.75":[2.66,2.85],	
	"3.00":[2.85,3.25]
}';
		$gemfind_ring_builder['carat_ranges']=$carat_ranges;
		update_option( 'gemfind_ring_builder', $gemfind_ring_builder );
	}
}