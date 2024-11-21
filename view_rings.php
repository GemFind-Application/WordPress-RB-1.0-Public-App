<?php
/**
 * Template Name: View Rings
 */
get_header();
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>
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
$setting = getProduct_rb();
$product_id = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='" . $setting['ringData']['settingId'] . "'" );
if ( is_null( $product_id ) ) {	

	$settingid = $setting['ringData']['settingId'];
	$hasvideo  = $type = 0;	
	if ( isset( $setting['ringData']['videoURL'] ) && $setting['ringData']['videoURL'] != '' ) {
		$headers = is_url_404_rb( $setting['ringData']['videoURL'] );		
		//echo $setting['ringData']['videoURL'];
		if ( $headers ) {
			$hasvideo = 1;			
			if ( strpos( $setting['ringData']['videoURL'], '.mp4' ) !== false ) {
				$type = 1;				
			} else {
				$type = 2;
			}
		}
	} else {		
		$hasvideo = 0;
	}	
	include( 'single-ring-template.php' );
} elseif ( isset( $product_id ) && ! empty( $product_id ) ) {
	$post_id      = get_product_by_sku_rb( $settingid );
	$product_meta = get_post_meta( $post_id, '', false );
	$settingid    = $setting['ringData']['settingId'];
	$hasvideo     = $type = 0;
	if ( isset( $setting['ringData']['videoURL'] ) && $setting['ringData']['videoURL'] != '' ) {
		$headers = is_url_404_rb( $setting['ringData']['videoURL'] );
		if ( $headers ) {
			$hasvideo = 1;
			if ( strpos( $setting['ringData']['videoURL'], '.mp4' ) !== false ) {
				$type = 1;
			} else {
				$type = 2;
			}
		}
	} else {
		$hasvideo = 0;
	}

	$setting['ringData']['style_number']           = $product_meta['style_number'][0];
	$setting['ringData']['center_stone_fit']       = $product_meta['center_stone_fit'][0];
	$setting['ringData']['center_stone_min_carat'] = $product_meta['center_stone_min_carat'][0];
	$setting['ringData']['center_stone_max_carat'] = $product_meta['center_stone_max_carat'][0];
	$setting['ringData']['metalID']                = $product_meta['metal_id'][0];
	$setting['ringData']['style_number']           = $product_meta['color_id'][0];
	$setting['ringData']['colorID']                = $product_meta['_sku'][0];
	$setting['ringData']['ring_size']              = $product_meta['ring_size'][0];
	$setting['ringData']['_price']                 = $product_meta['_price'][0];
	$setting['ringData']['_regular_price']         = $product_meta['_regular_price'][0];
	$setting['ringData']['settingName']            = get_the_title( $post_id );
	$setting['ringData']['description']            = get_the_excerpt( $post_id );

	/*$sidestone                                  = get_the_terms( $post_id, 'pa_gemfind_ring_collection' );
	$matelType                                        = get_the_terms( $post_id, 'pa_gemfind_ring_metaltype' );
	$shap                                      = get_the_terms( $post_id, 'pa_gemfind_ring_shape' );
	$setting['ringData']['sidestone']        = $sidestone[0]->name;
	$setting['ringData']['matelType']                = $matelType->name;
	$setting['ringData']['shap']              = $shap[0]->name;*/

	include( 'single-ring-template.php' );
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
    <script type="text/javascript">    	
    	jQuery('body').addClass('ringbuilder-settings-view page-layout-1column');    	
    </script>
<?php get_footer(); ?>