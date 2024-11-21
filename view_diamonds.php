<?php
/**
 * Template Name: View Diamonds
 */
get_header();
include( plugin_dir_path( __FILE__ ) . '/settings/head.php' );
include( plugin_dir_path( __FILE__ ) . '/settings/header.php' );
?>
    <!-- <script type="text/javascript">
		$('body').addClass('ringbuilder-settings-view');
	</script> -->
<?php
$shop            = get_site_url();
$pathprefixshop  = '';
$final_shop_url  = $shop;
$noimageurl      = plugins_url( "/assets/images/no-image.jpg", __FILE__ );
$loadingimageurl = plugins_url( "/assets/images/loader-2.gif", __FILE__ );
$tszview         = plugins_url( "/assets/images/360-view.png", __FILE__ );
$printIcon       = plugins_url( "/assets/images/print_icon.gif", __FILE__ );
$shopdata        = array( 'shopurl' => $shop );
include_once( 'head.php' );
$diamond = getProduct_dl();
$product_id = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='" . $diamond['diamondData']['diamondId'] . "'" );
if ( is_null( $product_id ) ) {
	if ( $diamond['diamondData']['fancyColorMainBody'] ) {
		$httpCode = is_url_404_dl( $diamond['diamondData']['image2'] );
		if ( $httpCode != 404 && $diamond['diamondData']['colorDiamond'] != '' ) {
			$imageurl = $diamond['diamondData']['colorDiamond'];
		} else {
			$imageurl = $noimageurl;
		}
	} else {
		$httpCode = is_url_404_dl( $diamond['diamondData']['image2'] );
		if ( $httpCode != 404 && $diamond['diamondData']['image2'] != '' ) {
			$imageurl = $diamond['diamondData']['image2'];
		} else {
			$imageurl = $noimageurl;
		}
	}
	$hasvideo = $type = 0;
	if ( isset( $diamond['diamondData']['videoFileName'] ) && $diamond['diamondData']['videoFileName'] != '' ) {
		$httpCode = is_url_404_dl( $diamond['diamondData']['videoFileName'] );
		if ( $httpCode != 404 ) {
			$hasvideo = 1;
			if ( strpos( $diamond['diamondData']['videoFileName'], '.mp4' ) !== false ) {
				$type = 1;
			} else {
				$type = 2;
			}
		}
	} else {
		$hasvideo = 0;
	}
	include( 'single-diamond-wordpress.php' );
} elseif ( isset( $product_id ) && ! empty( $product_id ) ) {
	$post_id                          = get_product_by_sku_dl( $diamond['diamondData']['diamondId'] );
	update_post_meta( $product_id, '_regular_price', $diamond['diamondData']['fltPrice'] );
    update_post_meta( $product_id, 'FltPrice', $diamond['diamondData']['fltPrice'] );
    update_post_meta( $product_id, '_price', $diamond['diamondData']['fltPrice'] );
	$product_meta                     = get_post_meta( $post_id, '', false );
	$diamond['diamondData']['image1'] = $product_meta['image1'][0];
	if ( $diamond['diamondData']['fancyColorMainBody'] ) {
		$httpCode = is_url_404_dl( $diamond['diamondData']['image2'] );
		if ( $httpCode != 404 && $diamond['diamondData']['colorDiamond'] != '' ) {
			$imageurl = $diamond['diamondData']['colorDiamond'];
		} else {
			$imageurl = $noimageurl;
		}
	} else {
		$httpCode = is_url_404_dl( $diamond['diamondData']['image2'] );
		if ( $httpCode != 404 && $diamond['diamondData']['image2'] != '' ) {
			$imageurl = $diamond['diamondData']['image2'];
		} else {
			$imageurl = $noimageurl;
		}
	}
	$hasvideo = $type = 0;
	if ( isset( $product_meta['videoFileName'][0] ) && $product_meta['videoFileName'][0] != '' ) {
		$httpCode = is_url_404_dl( $product_meta['videoFileName'][0] );
		if ( $httpCode != 404 ) {
			$hasvideo = 1;
			if ( strpos( $diamond['diamondData']['videoFileName'], '.mp4' ) !== false ) {
				$type = 1;
			} else {
				$type = 2;
			}
		}
	} else {
		$hasvideo = 0;
	}
	$diamond['diamondData']['videoFileName']      = $product_meta['videoFileName'][0];
	$diamond['diamondData']['diamondId']          = $product_meta['_sku'][0];
	$diamond['diamondData']['certificateUrl']     = $product_meta['certificateUrl'][0];
	$diamond['diamondData']['certificateIconUrl'] = $product_meta['certificateIconUrl'][0];
	$diamond['diamondData']['mainHeader']         = get_the_title( $post_id );
	$diamond['diamondData']['subHeader']          = get_the_excerpt( $post_id );
	$certificate                                  = get_the_terms( $post_id, 'pa_gemfind_certificate' );
	$color                                        = get_the_terms( $post_id, 'pa_gemfind_color' );
	$clarity                                      = get_the_terms( $post_id, 'pa_gemfind_clarity' );
	$shape                                        = get_the_terms( $post_id, 'pa_gemfind_shape' );
	$polish                                       = get_the_terms( $post_id, 'pa_gemfind_polish' );
	$symmetry                                     = get_the_terms( $post_id, 'pa_gemfind_symmetry' );
	$fluorescence                                 = get_the_terms( $post_id, 'pa_gemfind_fluorescence' );
/*	$diamond['diamondData']['certificate']        = $certificate[0]->name;
	$diamond['diamondData']['cut']                = $product_meta['cut'][0];
	$diamond['diamondData']['color']              = $color[0]->name;
	$diamond['diamondData']['clarity']            = $clarity[0]->name;
	$diamond['diamondData']['currencySymbol']     = get_woocommerce_currency_symbol();
	$diamond['diamondData']['fltPrice']           = $product_meta['FltPrice'][0];
	$diamond['diamondData']['shape']              = $shape[0]->name;
	$diamond['diamondData']['caratWeight']        = $product_meta['caratWeight'][0];
	$diamond['diamondData']['depth']              = $product_meta['depth'][0];
	$diamond['diamondData']['table']              = $product_meta['table'][0];
	$diamond['diamondData']['polish']             = $polish[0]->name;
	$diamond['diamondData']['symmetry']           = $symmetry[0]->name;
	$diamond['diamondData']['origin']             = $product_meta['origin'][0];
	$diamond['diamondData']['gridle']             = $product_meta['gridle'][0];
	$diamond['diamondData']['culet']              = $product_meta['culet'][0];
	$diamond['diamondData']['fluorescence']       = $fluorescence[0]->name;
	$diamond['diamondData']['measurement']        = $product_meta['measurement'][0];*/
	include( 'single-diamond-wordpress.php' );
} else { ?>
    <div class="diamond-nf">
		<?php _e('Diamond not found. Please try again after some time.', 'gemfind-diamond-link'); ?>
    </div>
<?php } ?>
    <div class="modal fade" id="popup-modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="note"></p>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>