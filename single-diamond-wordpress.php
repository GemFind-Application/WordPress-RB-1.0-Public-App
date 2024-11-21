<?php
$checkRingCookieData = json_decode(stripslashes($_COOKIE['_wp_ringsetting']))[0];
if(!empty($diamond['diamondData'])){
	// echo "<pre>";
	// print_r($diamond['diamondData']);
	// exit();

    $options   = getOptions_dl();   
    // echo '<pre>'; print_r($options); exit; 
    $diamondsoptionapi = get_option( 'gemfind_ring_builder' );
    $diamondsoption    = sendRequest_dl( array( 'diamondsoptionapi' => $diamondsoptionapi['diamondsoptionapi'] ) ); 
    $show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;

    $site_key=$options['site_key'];

?>
<script src="https://www.google.com/recaptcha/api.js" async defer ></script>

<div class="loading-mask gemfind-loading-mask" id="gemfind-loading-mask" style="display: none;">
    <div class="loader gemfind-loader">
        <p>Please wait...</p>
    </div>
</div>

<div id="search-rings" class="flow-tabs">
     <?php if(!empty($options['top_textarea'])) {?>
            <div class="tab-content">
               <div class="diamond-bar">
                  <?php echo $options['top_textarea']; ?>
              </div>
            </div>
       <?php } ?>
   <div class="tab-section">
      <ul class="tab-ul">
        <?php if($checkRingCookieData) { ?>
         <li class="tab-li"><div>
            <?php
            global $wp_query;
            if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {    
                $labsettings = '/islabsettings/1/';
            }
            ?>
            <a href="<?php echo site_url().'/ringbuilder/settings' . $labsettings; ?>"><span class="tab-title"><?php _e( 'Choose Your', 'gemfind-diamond-link' ); ?><strong><?php _e( 'Setting', 'gemfind-diamond-link' ); ?></strong></span><i class="ring-icon tab-icon"></i></a></div></li>
            <?php } else { ?>
            <li class="tab-li active"><div><a href="<?php echo get_site_url().'/ringbuilder/diamondlink/'; ?>"><span class="tab-title"><?php _e( 'Choose Your', 'gemfind-diamond-link' ); ?><strong><?php _e( 'Diamond', 'gemfind-diamond-link' ); ?></strong></span><i class="diamond-icon tab-icon"></i></a></div></li>         
            <?php } ?>
            <?php if(!$checkRingCookieData) { ?>
            <li class="tab-li"><div>
                <?php
                global $wp_query;
                if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {    
                    $labsettings = '/islabsettings/1/';
                }
                ?>
                <a href="<?php echo site_url().'/ringbuilder/settings' . $labsettings; ?>"><span class="tab-title"><?php _e( 'Choose Your', 'gemfind-diamond-link' ); ?><strong><?php _e( 'Setting', 'gemfind-diamond-link' ); ?></strong></span><i class="ring-icon tab-icon"></i></a></div></li>
                <?php } else { ?>
                <li class="tab-li active"><div><a href="javascript:;"><span class="tab-title"><?php _e( 'Choose Your', 'gemfind-diamond-link' ); ?><strong><?php _e( 'Diamond', 'gemfind-diamond-link' ); ?></strong></span><i class="diamond-icon tab-icon"></i></a></div></li>
                <?php } ?>
                <li class="tab-li"><div><a href="javascript:;"><span class="tab-title"><?php _e( 'Review', 'gemfind-diamond-link' ); ?><strong><?php _e( 'Complete Ring', 'gemfind-diamond-link' ); ?></strong></span><i class="finalring-icon tab-icon"></i></a></div></li>

            </ul>
        </div>
    </div>

    

<div class="diamonds-product-view">

    <!-- <div class="breadcrumbs">
        <ul class="items">
            <li class="item search">
                <a href="<?php echo get_site_url() . '/ringbuilder/diamondlink'; ?>" title="Return To Search Results">Return To
                Search Results</a>
            </li>
        </ul>
    </div> -->
    <section class="diamonds-search with-specification diamond-page">
        <div class="d-container">
            <div class="d-row">
                <div class="diamonds-preview no-padding">
                    <div class="diamond-info">
                        <div class="product-thumb">
                            <?php if ( isset( $diamond['diamondData']['image1'] ) ) { ?>
                                <div class="thumg-img diamention">
                                    <a href="javascript:;" onclick="Imageswitch1(event);">
                                        <img src="<?php echo $loadingimageurl; ?>"
                                        data-src="<?php echo $diamond['diamondData']['image1'] ?>"
                                        style="width:auto; height: 40px;"
                                        alt="<?php echo $diamond['diamondData']['mainHeader'] ?>"
                                        title="<?php echo $diamond['diamondData']['mainHeader'] ?>"
                                        class="thumbimg" id="thumbimg1"/>
                                    </a>
                                </div>
                                <input type="hidden" id="thumbvar" name="thumbvar" value="<?php echo $diamond['diamondData']['image1'] ?>" />
                            <?php } ?>
                            <div class="thumg-img main_image">
                                <a href="javascript:;" onclick="Imageswitch2(event);">
                                    <img src="<?php echo $imageurl ?>" style="width:auto; height: 40px;"
                                    alt="<?php echo $diamond['diamondData']['mainHeader'] ?>"
                                    title="<?php echo $diamond['diamondData']['mainHeader'] ?>" class="thumbimg"
                                    id="thumbimg2"/>
                                </a>
                            </div>
                            <?php if ( $hasvideo ) { ?>
                                <?php if ( $type == 1 ) { ?>
                                    <div class="thumg-img main_video">
                                        <a href="javascript:;" onclick="Videorun();" data-id="<?php echo $diamond['diamondData']['diamondId']; ?>">
                                            <img style="height: 40px;" src="<?php echo $tszview ?>" id="img1iframe"
                                            class="video"/>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class="thumg-img main_video">
                                        <a href="javascript:;" onclick="Videorun();" data-id="<?php echo $diamond['diamondData']['diamondId']; ?>">
                                            <img style="height: 40px;" src="<?php echo $tszview ?>" id="img1iframe"
                                            class="iframe">
                                        </a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="diamond-image">
                         <div class="diamondimg"
                                id="diamondimg">
                                <img src="<?php echo $imageurl; ?>" id="diamondmainimage"
                                alt="<?php echo $diamond['diamondData']['mainHeader'] ?>"
                                title="<?php echo $diamond['diamondData']['mainHeader'] ?>">
                            </div>
                        </div>
                        <h2>
                            <?php
                            if($diamondsoption[0][0]->show_In_House_Diamonds_First){ ?>
                                <?php _e( 'Stock Number', 'gemfind-diamond-link' ); ?>
                                <span><?php echo $diamond['diamondData']['stockNumber'] ?></span>
                            <?php } else {  ?>
                                <?php _e( 'SKU#', 'gemfind-diamond-link' ); ?>
                                <span><?php echo $diamond['diamondData']['diamondId'] ?></span>
                            <?php } ?>
                        </h2>                        
                        <?php  if($show_Certificate_in_Diamond_Search){ ?>
                        <div class="diamond-report">
                            <p><b><?php _e( 'Diamond Grading Report', 'gemfind-diamond-link' ); ?></b></p>
                            <div class="view_text">
                                <a href="javascript:void(0);"
                                onclick="javascript:window.open('<?php echo $diamond['diamondData']['certificateUrl']; ?>','CERTVIEW','scrollbars=yes,resizable=yes,width=860,height=550')"><?php _e( ' View', 'gemfind-diamond-link' ); ?></a>
                            </div>
                        </div>
                        
                        <div class="diamond-grade">
                            <div class="grade-logo">
                                <img src="<?php echo $diamond['diamondData']['certificateIconUrl'] ?>"
                                style="width:94px; height: 94px; max-width:inherit;"
                                alt="<?php echo $diamond['diamondData']['mainHeader'] ?>"
                                title="<?php echo $diamond['diamondData']['mainHeader'] ?>">
                            </div>
                            <div class="grade-info">
                                <p><?php echo $diamond['diamondData']['subHeader'] ?></p>
                            </div>
                        </div>
                        <?php  } ?>
                    </div>
                    <?php if ( $diamond['diamondData']['internalUselink'] == 'Yes' ) { ?>
                        <?php $dealerInfoarray = (array) $diamond['diamondData']['retailerInfo']; ?>
                        <div class="internaluse">
                            <?php _e( 'Internal use Only:', 'gemfind-diamond-link' ); ?> <a href="javascript:;" id="internaluselink"
                            class="internaluselink"
                            title="<?php _e( 'Dealer Info', 'gemfind-diamond-link' ); ?>"><?php _e( 'Click Here', 'gemfind-diamond-link' ); ?></a> <?php //_e( 'for Dealer Info.', 'gemfind-diamond-link' ); ?>
                            <div class="modal fade auth-section" id="auth-section" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        </div>
                                        <div class="modal-body">
                                            <div class="msg" id="msg"></div>
                                            <form class="internaluseform" id="internaluseform" method="post">
                                                <input type="password" id="auth_password" name="password" value=""
                                                placeholder="<?php _e( 'Enter Your Gemfind Password', 'gemfind-diamond-link' ); ?>">
                                                <input name="shopurl" type="hidden" value="<?php echo $shop; ?>">
                                                <input type="hidden" id="settingid" name="settingid" value="<?php echo $checkRingCookieData->setting_id; ?>" />
                                                <button type="submit" onclick="internaluselink()" title="Submit"
                                                class="preference-btn">
                                                <span><?php _e( 'Submit', 'gemfind-diamond-link' ); ?></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade dealer-detail-section" id="dealer-detail-section" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h1 class="modal-title">Vendor Information</h1>
                                    </div>
                                    <?php
                                        $wholesalePrice = ( $diamond['diamondData']['currencyFrom'] != 'USD' ) ? $diamond['diamondData']['currencyFrom'] . $diamond['diamondData']['currencySymbol'] . number_format( $diamond['diamondData']['wholeSalePrice'] ) : $diamond['diamondData']['currencySymbol'] . number_format( $diamond['diamondData']['wholeSalePrice'] );
                                    ?>
                                    <div class="modal-body">
                                        <div class="dealer-info-section" id="dealer-info-section">
                                            <table>
                                                <tr>
                                                    <td><?php _e( 'Dealer Name:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerName'] ) ? $dealerInfoarray['retailerName'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer Company:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerCompany'] ) ? $dealerInfoarray['retailerCompany'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer City/State:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerCity'] ) ? $dealerInfoarray['retailerCity'] : ''; ?>
                                                    /<?php echo ( $dealerInfoarray['retailerState'] ) ? $dealerInfoarray['retailerState'] : ''; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer Contact No.:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerContactNo'] ) ? $dealerInfoarray['retailerContactNo'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer Email:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerEmail'] ) ? $dealerInfoarray['retailerEmail'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer Lot number of the item:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerLotNo'] ) ? $dealerInfoarray['retailerLotNo'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer Stock number of the item:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerStockNo'] ) ? $dealerInfoarray['retailerStockNo'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Wholesale Price:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $diamond['diamondData']['wholeSalePrice'] ) ? $wholesalePrice : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Third Party:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['thirdParty'] ) ? $dealerInfoarray['thirdParty'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Diamond Id:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['diamondID'] ) ? $dealerInfoarray['diamondID'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Seller Name:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['sellerName'] ) ? $dealerInfoarray['sellerName'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Seller Address:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['sellerAddress'] ) ? $dealerInfoarray['sellerAddress'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer Fax:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerFax'] ) ? $dealerInfoarray['retailerFax'] : '-'; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php _e( 'Dealer Address:', 'gemfind-diamond-link' ); ?></td>
                                                    <td><?php echo ( $dealerInfoarray['retailerAddress'] ) ? $dealerInfoarray['retailerAddress'] : '-'; ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="diamonds-details no-padding diamond-request-form">
                <div class="diamond-data" id="diamond-data">
                    <div class="specification-title">
                        <h2><?php echo $diamond['diamondData']['mainHeader'] ?></h2>
                        <h4 class="spec-icon diamond_spec_container">
                            <span class="diamond_spec" onclick="CallSpecification();">Diamond Specification</span>
                            <a href="javascript:;" id="spcfctn" onclick="CallSpecification();" title="Diamond Specification">
                                <svg data-toggle="tooltip" data-placement="bottom" title="Specification" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                width="20px" height="20px" viewBox="0 0 612 612"
                                style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                <g>
                                 <g id="New_x5F_Post">
                                     <g>
                                         <path d="M545.062,286.875c-15.854,0-28.688,12.852-28.688,28.688v239.062h-459v-459h239.062
                                         c15.854,0,28.688-12.852,28.688-28.688S312.292,38.25,296.438,38.25H38.25C17.136,38.25,0,55.367,0,76.5v497.25
                                         C0,594.883,17.136,612,38.25,612H535.5c21.114,0,38.25-17.117,38.25-38.25V315.562
                                         C573.75,299.727,560.917,286.875,545.062,286.875z M605.325,88.95L523.03,6.655C518.556,2.18,512.684,0,506.812,0
                                         s-11.743,2.18-16.218,6.675l-318.47,318.45v114.75h114.75l318.45-318.45c4.494-4.495,6.675-10.366,6.675-16.237
                                         C612,99.297,609.819,93.445,605.325,88.95z M267.75,382.5H229.5v-38.25L506.812,66.938l38.25,38.25L267.75,382.5z"/>
                                     </g>
                                 </g>
                             </g>
                         </svg>
                     </a>
                 </h4>
             </div>

             <div class="diamond-content-data" id="diamond-content-data">
                <?php  if($show_Certificate_in_Diamond_Search){ ?>
                <div class="diamond-desc">
                    <p><?php echo $diamond['diamondData']['subHeader'] ?></p>
                </div>
                <?php  } ?>
                <div class="form-field diamonds-info">
                    <div class="intro-field">
                        <ul>
                             <li>
                                <strong><?php _e( 'Cut :', 'gemfind-diamond-link' ) ?></strong>
                                <p><?php if ( $diamond['diamondData']['cut'] != '' ) {
                                    echo $diamond['diamondData']['cut'];
                                } else {
                                    _e( 'NA', 'gemfind-diamond-link' );
                                } ?></p>
                            </li>
                            <li>
                                <strong><?php _e( 'Polish :', 'gemfind-diamond-link' ); ?></strong>
                                <p><?php if ( $diamond['diamondData']['polish'] != '' ) {
                                    echo $diamond['diamondData']['polish'];
                                } else {
                                    _e( 'NA', 'gemfind-diamond-link' );
                                } ?></p>
                            </li>
                            <li>
                                <strong><?php _e( 'Symmetry :', 'gemfind-diamond-link' ); ?></strong>
                                <p><?php if ( $diamond['diamondData']['polish'] != '' ) {
                                    echo $diamond['diamondData']['polish'];
                                } else {
                                    _e( 'NA', 'gemfind-diamond-link' );
                                } ?></p>
                            </li>
                           
                        </ul>
                        <ul>
                            <?php
                            if ( $diamond['diamondData']['fancyColorMainBody'] ) {
                                $color_to_display = $diamond['diamondData']['fancyColorIntensity'].' '.$diamond['diamondData']['fancyColorMainBody'];
                            } else {
                                $color_to_display = $diamond['diamondData']['color']; 
                            }
                            ?>
                            <li>
                                <strong><?php _e( 'Color :', 'gemfind-diamond-link' ) ?></strong>
                                <p><?php if ( $color_to_display != '' ) {
                                    echo $color_to_display;
                                    //echo $diamond['diamondData']['color'];
                                } else {
                                    _e( 'NA', 'gemfind-diamond-link' );
                                } ?></p>
                            </li>
                            <li>
                                <strong><?php _e( 'Clarity :', 'gemfind-diamond-link' ); ?></strong>
                                <p><?php if ( $diamond['diamondData']['clarity'] != '' ) {
                                    echo $diamond['diamondData']['clarity'];
                                } else {
                                    _e( 'NA', 'gemfind-diamond-link' );
                                } ?></p>
                            </li>
                             <li>
                                <strong><?php _e( 'Fluorescence :', 'gemfind-diamond-link' ); ?></strong>
                                <p><?php if ( $diamond['diamondData']['fluorescence'] != '' ) {
                                    echo $diamond['diamondData']['fluorescence'];
                                } else {
                                    _e( 'NA', 'gemfind-diamond-link' );
                                } ?></p>
                            </li>
                        </ul>
                    </div>
                    <div class="product-controler">
                        <?php
                        $options = getOptions_dl();

                        ?>
                        <ul>
                            <?php if ( $options['enable_hint'] == 'true' ): ?>
                                <li><a href="javascript:;" class="showForm" onclick="CallShowform(event);"
                                 data-target="drop-hint-main"><?php _e( 'Drop A Hint', 'gemfind-diamond-link' ); ?></a></li>
                             <?php endif; ?>
                             <?php if ( $options['enable_more_info'] == 'true' ): ?>
                                <li><a href="javascript:;" class="showForm" onclick="CallShowform(event);"
                                 data-target="req-info-main"><?php _e( 'Request More Info', 'gemfind-diamond-link' ); ?></a>
                             </li>
                         <?php endif; ?>
                         <?php if ( $options['enable_email_friend'] == 'true' ): ?>
                            <li><a href="javascript:;" class="showForm" onclick="CallShowform(event);"
                             data-target="email-friend-main"><?php _e( 'E-Mail A Friend', 'gemfind-diamond-link' ); ?></a>
                         </li>
                     <?php endif; ?>
                     <?php if ( $options['enable_print'] == 'true' ): ?>
                        <li><a href="javascript:;" data="<?php echo $printIcon; ?>" class="prinddia"
                         id="prinddia"><?php _e( 'Print Details', 'gemfind-diamond-link' ) ?></a></li>
                     <?php endif; ?>
                     <?php if ( $options['enable_schedule_viewing'] == 'true' ): ?>
                        <li><a href="javascript:;" class="showForm" onclick="CallShowform(event);"
                         data-target="schedule-view-main"><?php _e( 'Schedule Viewing', 'gemfind-diamond-link' ); ?></a>
                     </li>
                 <?php endif; ?>
             </ul>
         </div>
         <div class="diamond-action">
            <span><?php 
            $dprice = $diamond['diamondData']['fltPrice'];
            $dprice = str_replace(',', '', $dprice);

            if( $diamond['diamondData']['showPrice'] == true ) {
                if($options['price_row_format'] == 'left'){

                    if($diamond['diamondData']['currencyFrom'] == 'USD'){
                    
                        echo "$".number_format($dprice); 
                    
                    }else{
                    
                        echo number_format($dprice).' '.$diamond['diamondData']['currencySymbol'].' '.$diamond['diamondData']['currencyFrom'];
                    
                    }
                    
                }else{
                    
                      if($diamond['diamondData']['currencyFrom'] == 'USD'){
                    
                        echo "$".number_format($dprice); 
                    
                      }else{
                    
                        echo $diamond['diamondData']['currencyFrom'].' '.$diamond['diamondData']['currencySymbol'].' '.number_format($dprice);   
                    
                      }
                    
                }
            } else {
                echo 'Call For Price';
            }
            ?></span>
            <?php
            if($checkRingCookieData){
             $setting = json_decode(stripslashes($_COOKIE['_wp_ringsetting']));
             if($setting[0]->ringmincarat > '0.00'){
              $TempCaratMin = ($setting[0]->ringmincarat * 10) / 100;
              $CaratMin = ($setting[0]->ringmincarat - $TempCaratMin);
          } else {
              $CaratMin = $diamond['diamondData']['caratWeight'];
          }
          if($setting[0]->ringmaxcarat > '0.00'){
              $TempCaratMax = ($setting[0]->ringmaxcarat * 10) / 100;
              $CaratMax = ($setting[0]->ringmaxcarat + $TempCaratMax);
          } else {
              $CaratMax = $setting[0]->ringmaxcarat;
          }
          if($diamond['diamondData']['caratWeight'] > $CaratMax || $diamond['diamondData']['caratWeight'] < $CaratMin){ ?>
              <!-- <div><p style="color: red;"><?php //_e( 'This diamond will not properly fit with selected setting.', 'gemfind-diamond-link' ); ?></p></div> -->
          <?php } } ?>
          <form action=""
          method="post" id="product_addtocart_form">
          <div class="box-tocart">
        <?php        
        if ( isset( $product_id ) && ! empty( $product_id ) ) {
            update_post_meta( $post_id, '_regular_price', $diamond['diamondData']['fltPrice'] );
            update_post_meta( $post_id, 'FltPrice', $diamond['diamondData']['fltPrice'] );                                            
            update_post_meta( $post_id, '_price', $diamond['diamondData']['fltPrice'] );
            if( $diamond['diamondData']['showPrice'] == true ) {
                if( $diamond['diamondData']['dsEcommerce'] != false ) {
                    ?>
                    <button type="submit" title="Add to Cart" class="addtocart tocart"
                    onclick='redirectOnCart(event, <?php echo $post_id; ?>);'
                    id="product-addtocart-button"><?php _e( 'Add to Cart', 'gemfind-diamond-link' ) ?></button>
                    <?php
                }                
            }
        } else {
            if( $diamond['diamondData']['showPrice'] == true ) {
                if( $diamond['diamondData']['dsEcommerce'] != false ) {
                    ?>
                    <button type="submit" title="Add to Cart" class="addtocart tocart"
                    onclick='showLoader(event, <?php echo json_encode( $diamond ); ?>);'
                    id="product-addtocart-button"><?php _e( 'Add to Cart', 'gemfind-diamond-link' ) ?></button>
                    <?php
                }
            }            
        }
        ?>                                            
        </div>
    </form>
    <?php
    if ( isset( $diamond['diamondData']['shape'] ) ) {
        $urlshape = str_replace( ' ', '-', $diamond['diamondData']['shape'] ) . '-shape-';
    } else {
        $urlshape = '';
    }
    if ( isset( $diamond['diamondData']['caratWeight'] ) ) {
        $urlcarat = str_replace( ' ', '-', $diamond['diamondData']['caratWeight'] ) . '-carat-';
    } else {
        $urlcarat = '';
    }
    if ( isset( $diamond['diamondData']['color'] ) ) {
        $urlcolor = str_replace( ' ', '-', $diamond['diamondData']['color'] ) . '-color-';
    } else {
        $urlcolor = '';
    }
    if ( isset( $diamond['diamondData']['clarity'] ) ) {
        $urlclarity = str_replace( ' ', '-', $diamond['diamondData']['clarity'] ) . '-clarity-';
    } else {
        $urlclarity = '';
    }
    if ( isset( $diamond['diamondData']['cut'] ) ) {
        $urlcut = str_replace( ' ', '-', $diamond['diamondData']['cut'] ) . '-cut-';
    } else {
        $urlcut = '';
    }
    if ( isset( $diamond['diamondData']['certificate'] ) ) {
        $urlcert = str_replace( ' ', '-', $diamond['diamondData']['certificate'] ) . '-certificate-';
    } else {
        $urlcert = '';
    }
    $urlstring      = strtolower( $urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $diamond['diamondData']['diamondId'] );
    $diamondviewurl = '';
    $diamondviewurl = getDiamondViewUrl_dl( $urlstring, $type, get_site_url() . '/diamondlink', $pathprefixshop );
    ?>
    <ul class="list-inline social-share">
        <?php $imageurl = $diamond['diamondData']['image1'];
        if($diamondsoption[0][0]->show_Pinterest_Share) { ?>
        <li class="save_pinterest">
            <a class="save_pint" data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?url=<?php echo $diamondviewurl;?>&media=<?php echo $imageurl; ?>&description=<?php echo $diamond['diamondData']['subHeader']; ?>" data-pin-height="28"></a>
        </li>
        <?php } ?>
        <?php if($diamondsoption[0][0]->show_Twitter_Share) { ?>
        <li class="share_tweet">
            <a href="https://twitter.com/share?ref_src=<?php echo $diamondviewurl;?>" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </li>
        <?php } ?>
        <?php if($diamondsoption[0][0]->show_Facebook_Share) { ?>
        <li class="share_fb">
            <div class="fb-share-button" data-href="<?php echo $diamondviewurl;?>" data-layout="button" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $diamondviewurl;?>" class="fb-xfbml-parse-ignore">Share</a></div>
        </li>
        <?php } ?>
        <?php if($diamondsoption[0][0]->show_Facebook_Like) { ?>
        <li class="like_fb">
            <div class="fb-like" data-href="<?php echo $diamondviewurl;?>" data-width="" data-layout="button_count" data-share="false" data-action="like" data-size="small" ></div>
        </li>
        <?php } ?>
    </ul>

    <?php
    if($checkRingCookieData->setting_id){
      $redirectURI = '/diamondlink/completering/';
  }else{
      $redirectURI = '/settings/';
  }

  $carat_ranges = json_decode( stripslashes($options['carat_ranges']), true );    


    if(!empty($carat_ranges)){
        $ringmincarat_float  = number_format($diamond['diamondData']['caratWeight'],2);
        

        foreach ($carat_ranges as $key => $value) {
           
             $centerstonevalue = range($value[0],$value[1],0.01);
             $caratvalue = array_values($carat_ranges);

             if ( $ringmincarat_float  >= $value[0] && $ringmincarat_float <= $value[1]  ){
                      
                     $caratminval = ($value[0]);
                    $caratmaxval = ($value[1]);
             }
        }      
    }
  ?>
  <form action="<?php echo get_site_url() . '/ringbuilder' . $redirectURI; ?>" method="post" id="completering_addtocart_form">
    <input type="hidden" name="diamondid" id="diamondid" value="<?php echo $diamond['diamondData']['diamondId'] ?>" />
    <input type="hidden" name="centerstone" id="centerstone" value="<?php echo $diamond['diamondData']['shape'] ?>" />
    <input type="hidden" name="carat" id="carat" value="<?php echo $diamond['diamondData']['caratWeight'] ?>" />
    <input type="hidden" name="centerstonemincarat" id="centerstonemincarat" value="<?php echo (isset($caratminval) ? $caratminval : ($diamond['diamondData']['caratWeight'] - 0.1));?>" />
    <input type="hidden" name="centerstonemaxcarat" id="centerstonemaxcarat" value="<?php echo (isset($caratmaxval) ? $caratmaxval : ($diamond['diamondData']['caratWeight'] + 0.1));?>" />
    <input type="hidden" name="islabcreated" id="islabcreated" value="<?php echo $diamond['diamondData']['isLabCreated'] ?>" />
    <div class="box-tocart">
     <?php if($checkRingCookieData->setting_id) { ?>
       <button type="submit" title="Complete Your Ring" class="addtocart tocart" onclick="showLoader();" id="completering_addtocart_button"><?php _e( 'Complete Your Ring', 'gemfind-diamond-link' ); ?></button>
   <?php } else { ?>    
       <button type="submit" title="Add Your Setting" class="addtocart tocart" onclick="showLoader();" id="completering_addtocart_button"><?php _e( 'Add Your Setting', 'gemfind-diamond-link' ); ?></button>
   <?php } ?>
</div>
</form>
</div>
</div>
</div>



<div class="diamond-forms">
    <?php if ( $options['enable_hint'] == true ): ?>
        <div class="form-main no-padding diamond-request-form" id="drop-hint-main">
            <div class="requested-form">
                <h2><?php _e( 'Drop A Hint', 'gemfind-diamond-link' ); ?></h2>
                <p><?php _e( 'Because you deserve this.', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="note" style="display: none;"></div>
            <form method="post" enctype="multipart/form-data"
            data-hasrequired="<?php _e( '* Required Fields', 'gemfind-diamond-link' ); ?>"
            data-mage-init='{"validation":{}}' class="form-drop-hint" id="form-drop-hint">
            <input name="diamondurl" type="hidden" value="<?php echo $diamondviewurl; ?>">
            <input name="diamondid" type="hidden"
            value="<?php echo $diamond['diamondData']['diamondId']; ?>">
            <input name="shopurl" type="hidden" value="<?php echo $shop; ?>">
            <input type="hidden" id="settingid" name="settingid" value="<?php echo $checkRingCookieData->setting_id; ?>" />
            <div class="form-field">
                <label>
                    <input name="name" id="drophint_name" onfocus="focusFunction(this)"
                    onfocusout="focusoutFunction(this)" type="text" class=""
                    data-validate="{required:true}" placeholder=" ">
                    <span><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
                </label>
                <label>
                    <input name="email" id="drophint_email" onfocus="focusFunction(this)"
                    onfocusout="focusoutFunction(this)" type="email" class=""
                    data-validate="{required:true, 'validate-email':true}"
                    placeholder=" ">
                    <span><?php _e( 'Your E-mail', 'gemfind-diamond-link' ); ?></span>
                </label>
                <label>
                    <input name="recipient_name" id="drophint_rec_name"
                    onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)"
                    type="text" class="" data-validate="{required:true}"
                    placeholder=" ">
                    <span><?php _e( 'Hint Recipient\'s Name', 'gemfind-diamond-link' ); ?></span>
                </label>
                <label>
                    <input name="recipient_email" id="drophint_rec_email"
                    onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)"
                    type="email" class=""
                    data-validate="{required:true, 'validate-email':true}"
                    placeholder=" ">
                    <span><?php _e( 'Hint Recipient\'s E-mail', 'gemfind-diamond-link' ); ?></span>
                </label>
                <label>
                    <input name="gift_reason" id="gift_reason" onfocus="focusFunction(this)"
                    onfocusout="focusoutFunction(this)" type="text" class=""
                    data-validate="{required:true}" placeholder=" ">
                    <span><?php _e( 'Reason For This Gift', 'gemfind-diamond-link' ); ?></span>
                </label>
                <label>
                    <textarea name="hint_message" rows="2" cols="20" id="drophint_message"
                    class="" data-validate="{required:true}"
                    placeholder="Add A Personal Message Here ..."></textarea>
                </label>
                <label>
                    <div class="has-datepicker--icon">
                        <input name="gift_deadline" id="gift_deadline" autocomplete="false"
                        readonly title="Gift Deadline" value="" type="text"
                        placeholder="Gift Deadline">
                    </div>
                </label>
                <div class="prefrence-action">
                    <div class=" prefrence-action action">
                        <button type="button" data-target="drop-hint-main"
                        onclick="Closeform(event);"
                        class="cancel preference-btn btn-cencel">
                        <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span></button>
                        <button type="submit"
                        onclick="formSubmit(event,myajax.ajaxurl,'form-drop-hint')"
                        title="<?php _e('Drop Hint', 'gemfind-diamond-link');?>" class="preference-btn">
                        <span><?php _e( 'Drop Hint', 'gemfind-diamond-link' ); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>
<?php if ( $options['enable_email_friend'] == true ): ?>
    <div class="form-main no-padding diamond-request-form" id="email-friend-main">
        <div class="requested-form">
            <h2><?php _e( 'E-Mail A Friend', 'gemfind-diamond-link' ); ?></h2>
        </div>
        <div class="note" style="display: none;"></div>
        <form method="post" enctype="multipart/form-data"
        data-hasrequired="<?php _e( '* Required Fields', 'gemfind-diamond-link' ); ?>"
        data-mage-init='{"validation":{}}' class="form-email-friend"
        id="form-email-friend">
        <input name="diamondurl" type="hidden" value="<?php echo $diamondviewurl; ?>">
        <input name="diamondid" type="hidden"
        value="<?php echo $diamond['diamondData']['diamondId']; ?>">
        <input name="shopurl" type="hidden" value="<?php echo $shop; ?>">
        <input type="hidden" id="settingid" name="settingid" value="<?php echo $checkRingCookieData->setting_id; ?>" />
        <div class="form-field">
            <label>
                <input id="email_frnd_name" name="name" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" placeholder="" type="text"
                class="">
                <span for="email_frnd_name"><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <input name="email" type="email" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" placeholder=""
                id="email_frnd_email" class="">
                <span><?php _e( 'Your E-mail', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <input name="friend_name" type="text" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" placeholder=""
                id="email_frnd_fname" class="">
                <span><?php _e( 'Your Friend\'s Name', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <input name="friend_email" type="email" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" placeholder=""
                id="email_frnd_femail" class="">
                <span><?php _e( 'Your Friend\'s E-mail', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <textarea name="message" rows="2"
                placeholder="Add A Personal Message Here ..." cols="20"
                id="email_frnd_message" class=""></textarea>
            </label>
            <div class="prefrence-action">
                <div class=" prefrence-action action">
                    <button type="button" data-target="email-friend-main"
                    onclick="Closeform(event);"
                    class="cancel preference-btn btn-cencel">
                    <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span></button>
                    <button type="submit"
                    onclick="formSubmit(event,myajax.ajaxurl,'form-email-friend')"
                    title="Send To Friend" class="preference-btn">
                    <span><?php _e( 'Send To Friend', 'gemfind-diamond-link' ); ?></span>
                </button>
            </div>
        </div>
    </div>
</form>
</div>
<?php endif; ?>
<?php if ( $options['enable_more_info'] == true ): ?>
    <div class="form-main no-padding diamond-request-form" id="req-info-main">
        <div class="requested-form">
            <h2><?php _e( 'Request More Information', 'gemfind-diamond-link' ); ?></h2>
            <p><?php _e( 'Our specialists will contact you.', 'gemfind-diamond-link' ); ?></p>
        </div>
        <div class="note" style="display: none;"></div>
        <form method="post" enctype="multipart/form-data"
        data-hasrequired="<?php _e( '* Required Fields', 'gemfind-diamond-link' ); ?>"
        data-mage-init='{"validation":{}}' class="form-request-info"
        id="form-request-info">
        <input name="diamondurl" type="hidden" value="<?php echo $diamondviewurl; ?>">
        <input name="diamondid" type="hidden"
        value="<?php echo $diamond['diamondData']['diamondId']; ?>">
        <input name="shopurl" type="hidden" value="<?php echo $shop; ?>">
        <input type="hidden" id="settingid" name="settingid" value="<?php echo $checkRingCookieData->setting_id; ?>" />
        <div class="form-field">
            <label>
                <input name="name" type="text" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" id="reqinfo_name"
                placeholder="" class="">
                <span><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <input name="email" type="email" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" id="reqinfo_email"
                placeholder="" class="">
                <span><?php _e( 'Your E-mail Address', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <input name="phone" type="text" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" id="reqinfo_phone"
                placeholder="" class="">
                <span><?php _e( 'Your Phone Number', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <textarea name="hint_message" rows="2" cols="20"
                placeholder="Add A Personal Message Here ..."
                id="reqinfo_message" class=""></textarea>
            </label>
            <div class="prefrence-area">
                <p><?php _e( 'Contact Preference:', 'gemfind-diamond-link' ); ?></p>
                <ul class="pref_container">
                    <li>
                        <input type="radio" class="radio required-entry"
                        name="contact_pref" value="By Email">
                        <label><?php _e( 'By Email', 'gemfind-diamond-link' ); ?></label>
                    </li>
                    <li>
                        <input type="radio" class="radio required-entry"
                        name="contact_pref" value="By Phone">
                        <label><?php _e( 'By Phone', 'gemfind-diamond-link' ); ?></label>
                    </li>
                </ul>
                <div class="prefrence-action">
                    <div class=" prefrence-action action">
                        <button type="button" data-target="req-info-main"
                        onclick="Closeform(event);"
                        class="cancel preference-btn btn-cencel">
                        <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span>
                    </button>
                    <button type="submit"
                    onclick="formSubmit(event,myajax.ajaxurl,'form-request-info')"
                    title="Request" class="preference-btn">
                    <span><?php _e( 'Request', 'gemfind-diamond-link' ); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>
</form>
</div>
<?php endif; ?>
<?php if ( $options['enable_schedule_viewing'] == true ): ?>
    <div class="form-main no-padding diamond-request-form" id="schedule-view-main">
        <div class="requested-form">
            <h2><?php _e( 'Schedule A Viewing', 'gemfind-diamond-link' ); ?></h2>
            <p><?php _e( 'See This Item & More In Our Store', 'gemfind-diamond-link' ); ?></p>
        </div>
        <div class="note" style="display: none;"></div>
        <form method="post" enctype="multipart/form-data"
        data-hasrequired="<?php _e( '* Required Fields', 'gemfind-diamond-link' ); ?>"
        data-mage-init='{"validation":{}}' class="form-schedule-view"
        id="form-schedule-view">
        <input name="diamondurl" type="hidden" value="<?php echo $diamondviewurl; ?>">
        <input name="diamondid" type="hidden" value="<?php echo $diamond['diamondData']['diamondId']; ?>">
        <input name="shopurl" type="hidden" value="<?php echo $shop; ?>">
        <input type="hidden" id="settingid" name="settingid" value="<?php echo $checkRingCookieData->setting_id; ?>" />
        <div class="form-field">
            <label>
                <input name="name" type="text" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" id="schview_name"
                placeholder="" class="">
                <span><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <input name="email" type="email" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" id="schview_email"
                placeholder="" class="">
                <span><?php _e( 'Your E-mail Address', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <input name="phone" type="text" onfocus="focusFunction(this)"
                onfocusout="focusoutFunction(this)" id="schview_phone"
                placeholder="" class="">
                <span><?php _e( 'Your Phone Number', 'gemfind-diamond-link' ); ?></span>
            </label>
            <label>
                <textarea name="hint_message" rows="2" cols="20"
                placeholder="Add A Personal Message Here ..."
                id="schview_message" class=""></textarea>
            </label>
            <label>
                <?php $retailerInfo = (array) $diamond['diamondData']['retailerInfo'];
                $addressList        = (array) $retailerInfo['addressList'];                 
                ?> 
                <select name="location" placeholder="" id="schview_loc">
                    <option value=""><?php _e( '--Location--', 'gemfind-diamond-link' ); ?></option>                    
                    <?php foreach ( $addressList as $value ) {
                        $value = (array) $value; ?>
                        <option data-locationid="<?php echo $value['locationID']; ?>" value="<?php echo $value['locationName']; ?>"><?php echo $value['locationName']; ?></option>
                    <?php } ?>
                </select>
            </label>
            <label>
                <div class="has-datepicker--icon">
                    <input name="avail_date" id="avail_date" readonly autocomplete="false" placeholder="When are you avilable?" title="When are you available?" value="" type="text">
                </div>
            </label>
            <?php
            $timingList = (array)$retailerInfo['timingList'];            
            if(empty($timingList)) {
                ?>
                <label class="timing_not_avail" style="display:none;">Slots not available on selected date</label>
                <?php
            } else {
                foreach ($timingList as $timing) {
                    $timingDays[0] = array(
                        "sundayStart" => $timing->sundayStart,
                        "sundayEnd" => $timing->sundayEnd
                    );
                    $timingDays[1] = array(
                        "mondayStart" => $timing->mondayStart,
                        "mondayEnd" => $timing->mondayEnd
                    );
                    $timingDays[2] = array(
                        "tuesdayStart" => $timing->tuesdayStart,
                        "tuesdayEnd" => $timing->tuesdayEnd
                    );
                    $timingDays[3] = array(
                        "wednesdayStart" => $timing->wednesdayStart,
                        "wednesdayEnd" => $timing->wednesdayEnd
                    );
                    $timingDays[4] = array(
                        "thursdayStart" => $timing->thursdayStart,
                        "thursdayEnd" => $timing->thursdayEnd
                    );
                    $timingDays[5] = array(
                        "fridayStart" => $timing->fridayStart,
                        "fridayEnd" => $timing->fridayEnd
                    );
                    $timingDays[6] = array(
                        "saturdayStart" => $timing->saturdayStart,
                        "saturdayEnd" => $timing->saturdayEnd
                    );
                    if($timing->storeClosedSun == "Yes")
                    {
                        $dayStatusArr[0] = 0;
                    }
                    if($timing->storeClosedMon == "Yes")
                    {
                        $dayStatusArr[1] = 1;
                    }
                    if($timing->storeClosedTue == "Yes")
                    {
                        $dayStatusArr[2] = 2;
                    }
                    if($timing->storeClosedWed == "Yes")
                    {
                        $dayStatusArr[3] = 3;
                    }
                    if($timing->storeClosedThu == "Yes")
                    {
                        $dayStatusArr[4] = 4;
                    }
                    if($timing->storeClosedFri == "Yes")
                    {
                        $dayStatusArr[5] = 5;
                    }
                    if($timing->storeClosedSat == "Yes")
                    {
                        $dayStatusArr[6] = 6;
                    }
                    /*echo "<pre>";
                    print_r($dayStatusArr);*/

                    foreach($dayStatusArr as $key => $value) {  ?>
                        <span style="display:none;" class="day_status_arr"><?php echo $value;?></span>
                        <?php
                    }
                   ?>
                   <span class="timing_days" data-location="<?php  echo $timing->locationID; ?>" style="display:none;"><?php echo json_encode($timingDays);?></span>
            <?php  }  ?>
               <label>
                  <select id="appnt_time" class=""  placeholder=""  name="appnt_time" style="display:none;"></select>
               </label>
           <?php } ?>
            <div class="prefrence-action">
                <div class=" prefrence-action action">
                    <button type="button" data-target="schedule-view-main"
                    onclick="Closeform(event);"
                    class="cancel preference-btn btn-cencel">
                    <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span></button>
                    <button type="submit"
                    onclick="formSubmit(event,myajax.ajaxurl,'form-schedule-view')"
                    title="Request" class="preference-btn">
                    <span><?php _e( 'Request', 'gemfind-diamond-link' ); ?></span>
                </button>
            </div>
        </div>
    </div>
</form>
</div>
<?php endif; ?>
</div>
</div>
<div class="diamond-specification cls-for-hide" id="diamond-specification">
    <div class="specification-info">
        <div class="specification-title">
            <h2><?php _e( 'Diamond Details', 'gemfind-diamond-link' ); ?></h2>
            <h4>
                <a href="javascript:;" id="dmnddtl" onclick="CallDiamondDetail();">                                        
                    <svg version="1.1" data-placement="bottom"  data-toggle="tooltip" title="Close" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 52 52" width="20px" height="20px" style="enable-background:new 0 0 52 52;display: inline;vertical-align: text-bottom; fill:#828282 !important;" xml:space="preserve">
                    <g>
                       <path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M26,50C12.767,50,2,39.233,2,26
                       S12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z"/>
                       <path d="M35.707,16.293c-0.391-0.391-1.023-0.391-1.414,0L26,24.586l-8.293-8.293c-0.391-0.391-1.023-0.391-1.414,0
                       s-0.391,1.023,0,1.414L24.586,26l-8.293,8.293c-0.391,0.391-0.391,1.023,0,1.414C16.488,35.902,16.744,36,17,36
                       s0.512-0.098,0.707-0.293L26,27.414l8.293,8.293C34.488,35.902,34.744,36,35,36s0.512-0.098,0.707-0.293
                       c0.391-0.391,0.391-1.023,0-1.414L27.414,26l8.293-8.293C36.098,17.316,36.098,16.684,35.707,16.293z"/>
                   </g>
               </svg>
           </a>
       </h4>
   </div>
   <ul>
    <?php if ( isset( $diamond['diamondData']['diamondId'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Stock Number', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo $diamond['diamondData']['diamondId'] ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['fltPrice'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Price', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
             
                <p> <?php if ($diamond['diamondData']['showPrice'] == true) { 
                        if($options['price_row_format'] == 'left'){

                            if($diamond['diamondData']['currencyFrom'] == 'USD'){
                            
                                echo "$".number_format($diamond['diamondData']['fltPrice']); 
                            
                            }else{
                            
                                echo number_format($diamond['diamondData']['fltPrice']).' '.$diamond['diamondData']['currencySymbol'].' '.$diamond['diamondData']['currencyFrom'];
                            
                            }
                            
                        }else{
                            
                              if($diamond['diamondData']['currencyFrom'] == 'USD'){
                            
                                echo "$".number_format($diamond['diamondData']['fltPrice']); 
                            
                              }else{
                            
                                echo $diamond['diamondData']['currencyFrom'].' '.$diamond['diamondData']['currencySymbol'].' '.number_format($diamond['diamondData']['fltPrice']);   
                            
                              }
                            
                        }
                             
                    } else {
                        echo 'Call For Price';
                    } ?>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['fltPrice'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Price Per Carat', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <?php if ($diamond['diamondData']['showPrice'] == true) { ?>
                <?php if ( isset( $diamond['diamondData']['caratWeight'] ) ) {
                    $percarat = str_replace( ',', '', $diamond['diamondData']['fltPrice'] ) / $diamond['diamondData']['caratWeight'];
                    ?>
                    <?php if($options['price_row_format'] == 'left') { ?>
                        <p><?php echo ( $diamond['diamondData']['currencyFrom'] != 'USD' ) ?  number_format( $percarat, 2 ) .' '. $diamond['diamondData']['currencySymbol']  .' '. $diamond['diamondData']['currencyFrom']  : $diamond['diamondData']['currencySymbol'] . number_format( $percarat, 0 ); ?></p>
                    <?php } else { ?>
                    <p><?php echo ( $diamond['diamondData']['currencyFrom'] != 'USD' ) ? $diamond['diamondData']['currencyFrom'] . $diamond['diamondData']['currencySymbol'] . number_format( $percarat, 2 ) : $diamond['diamondData']['currencySymbol'] . number_format( $percarat, 0 ); ?></p>
                    <?php } ?>
                <?php } else { ?>
                    <p><?php echo ( $diamond['diamondData']['currencyFrom'] != 'USD' ) ? $diamond['diamondData']['currencyFrom'] . $diamond['diamondData']['currencySymbol'] . number_format( $diamond['diamondData']['fltPrice'] ) : $diamond['diamondData']['currencySymbol'] . number_format( $diamond['diamondData']['fltPrice'] ); ?></p>
                <?php }
                 } else {
                        echo 'Call For Price';
                } ?>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['caratWeight'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Carat Weight', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo $diamond['diamondData']['caratWeight'] ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['cut'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Cut', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo $diamond['diamondData']['cut'] ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $color_to_display ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Color', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo $color_to_display ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['clarity'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Clarity', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo $diamond['diamondData']['clarity'] ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['depth'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Depth %', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['depth'] ) ? $diamond['diamondData']['depth'] . '%' : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['table'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Table %', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['table'] ) ? $diamond['diamondData']['table'] . '%' : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['polish'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Polish', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['polish'] ) ? $diamond['diamondData']['polish'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['symmetry'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Symmetry', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['symmetry'] ) ? $diamond['diamondData']['symmetry'] : '-'; ?></p>
            </div>
        </li>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Origin', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['origin'] ) ? $diamond['diamondData']['origin'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['gridle'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Girdle', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['gridle'] ) ? $diamond['diamondData']['gridle'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['culet'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Culet', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['culet'] ) ? $diamond['diamondData']['culet'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['fluorescence'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Fluorescence', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['fluorescence'] ) ? $diamond['diamondData']['fluorescence'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['measurement'] ) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Measurement', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['measurement'] ) ? $diamond['diamondData']['measurement'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    <?php if ( isset( $diamond['diamondData']['fancyColorMainBody'] ) && !empty($diamond['diamondData']['fancyColorMainBody']) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Fancy Color Main Body', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['fancyColorMainBody'] ) ? $diamond['diamondData']['fancyColorMainBody'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
     <?php if ( isset( $diamond['diamondData']['fancyColorIntensity'] ) && !empty($diamond['diamondData']['fancyColorIntensity']) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Fancy Color Intensity', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['fancyColorIntensity'] ) ? $diamond['diamondData']['fancyColorIntensity'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
     <?php if ( isset( $diamond['diamondData']['fancyColorOvertone'] ) && !empty($diamond['diamondData']['fancyColorOvertone']) ) { ?>
        <li>
            <div class="diamonds-details-title">
                <p><?php _e( 'Fancy Color Overtone', 'gemfind-diamond-link' ); ?></p>
            </div>
            <div class="diamonds-info">
                <p><?php echo ( $diamond['diamondData']['fancyColorOvertone'] ) ? $diamond['diamondData']['fancyColorOvertone'] : '-'; ?></p>
            </div>
        </li>
    <?php } ?>
    
    
</ul>
</div>
</div>
</div>
</div>
</div>
<?php
    $carat_ranges = json_decode( stripslashes($options['carat_ranges']), true );    
    if(!empty($carat_ranges)){        
        $ringmincarat_float  = number_format($ringmaxmincaratdata[0]['ringmincarat'],2);
        if (array_key_exists($ringmincarat_float,$carat_ranges)){
            $caratminval =  $carat_ranges[$ringmincarat_float][0];
            $caratmaxval = $carat_ranges[$ringmincarat_float][1];
        }
    }
?>

<div class="search-form">
    <form id="search-diamonds-form" method="post"
    action="<?php echo get_site_url() . '/' . 'diamondlink/diamondsearch'; ?>">
    <input name="submitby" id="submitby" type="hidden" value=""/>
    <input name="baseurl" id="baseurl" type="hidden" value="<?php echo get_site_url(); ?>"/>
    <input name="shopurl" id="shopurl" type="hidden" value="<?php echo $shop; ?>"/>
    <input name="path_prefix_shop" id="path_shop_url" type="hidden" value="<?php echo $pathprefixshop; ?>"/>
    <input name="viewmode" id="viewmode" type="hidden" value="list"/>
    <input type="hidden" name="orderby" id="orderby" value="FltPrice"/>
    <input type="hidden" name="direction" id="direction" value="ASC"/>
    <input type="hidden" name="currentpage" id="currentpage" value="1"/>
    <input type="hidden" name="diamond_shape[]" id="diamond_shape" value="<?php echo $diamond['diamondData']['shape'] ?>" />
    <input type="hidden" name="diamond_certificates[]" id="diamond_certificates" value="<?php echo $diamond['diamondData']['certificate'] ?>" />
    <?php if ( $diamond['diamondData']['fancyColorMainBody'] ) { ?>
        <input type="hidden" name="filtermode" id="filtermode" value="navfancycolored">
    <?php } else if ( $diamond['diamondData']['isLabCreated'] == true ) { ?>
        <input type="hidden" name="filtermode" id="filtermode" value="navlabgrown">
    <?php } else { ?>
        <input type="hidden" name="filtermode" id="filtermode" value="navstandard">
    <?php } ?>    
    <input type="hidden" name="diamond_certificate[]" value="<?php echo $diamond['diamondData']['certificate'] ?>"/>
    <input type="hidden" name="diamond_carats[from]" value="<?php echo $caratminval ?>"/>
    <input type="hidden" name="diamond_carats[to]" value="<?php echo $caratmaxval ?>"/>
    <input type="hidden" name="price[from]" value=""/>
    <input type="hidden" name="price[to]" value="99999"/>
    <input type="hidden" name="diamond_table[from]" value="0"/>
    <input type="hidden" name="diamond_table[to]" value="100"/>
    <input type="hidden" name="diamond_depth[from]" value="0"/>
    <input type="hidden" name="diamond_depth[to]" value="100"/>
    <input name="itemperpage" id="itemperpage" type="hidden" value="<?php echo getResultsPerPage_dl(); ?>"/>
    <input type="hidden" name="gemfind_diamond_origin" value=""/>
    <input type="hidden" id="settingid" name="settingid" value="<?php echo $checkRingCookieData->setting_id; ?>" />
    <input type="submit" name="Submit" id="submit" style="visibility: hidden;">
</form>
</div>
<div class="result filter-advanced">
    <div style="text-align: center; margin: 50px; font-size: 16px;">
        <b><?php _e( 'Loading Similar Diamonds...', 'gemfind-diamond-link' ); ?></b></div>
    </div>
    <div class="print-diamond-details" style="display: none;">
        <div class="dimond_data"></div>
    </div>

    <div id="printMessageBox">Please wait while we create your document</div>
</section>
  
<?php
  $gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
  if($gemfind_ring_builder['show_copyright']=="yes"){                    ?>
  <div class="copyright-text ring-copyright-txt">
    <p>Powered By <a href="http://www.gemfind.com" target="_blank">GemFind</a></p>
  </div>
  <?php  } ?>
</div>
 <?php } else {  ?>
<div class="modal fade no-info-section" id="no-info-section" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="msg" id="msg">The ring that was sent to you is unfortunately no longer available.</div>
                <a href="<?php echo site_url(); ?>/ringbuilder/settings/" class="button">Ringbuilder</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
        jQuery('#no-info-section').modal({ 
                                        backdrop: 'static',
                                        keyboard: false
                                    });

        jQuery("#no-info-section .close").click(function(){
            window.location.href = '<?php echo site_url(); ?>/ringbuilder/settings/';
        });
</script>
<?php  } ?>

<script src="<?php echo plugins_url( '/assets/js/jquery.PrintArea.js', __FILE__ ); ?>"></script>
<script>
    jQuery(document).ready(function(){
      jQuery('[data-toggle="tooltip"]').tooltip();   
  });
    jQuery(window).bind("load", function () {
        jQuery("#prinddia").click(function () {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {mode: mode};
            var dimond_id = '<?php echo $diamond['diamondData']['diamondId'];?>';
            var shop = '<?php echo $shop; ?>';
            jQuery.ajax({
                url: myajax.ajaxurl,
                data: {action: 'printdiamond_dl', diamondid: dimond_id, shop: shop},
                type: 'POST',
                dataType: 'html',
                cache: true,
                beforeSend: function (settings) {
                    jQuery('#printMessageBox').show();
                },
                success: function (response) {
                    //console.log(response);
                    jQuery(".dimond_data").html(response);
                    setTimeout(function () {
                        jQuery('#printMessageBox').hide();
                        jQuery(".dimond_data").printArea(options);
                    }, 5000);

                },
                error: function (xhr, status, errorThrown) {
                    console.log('Error happens. Try again.');
                    console.log(errorThrown);
                }
            });


        });

        //jQuery(".prinddia").printPage();
    });

    function showLoader(e, diamondData) {
        e.preventDefault();               
        document.getElementById("gemfind-loading-mask").style.display = "block";
        jQuery.ajax({
           type : "POST",
           dataType : "html",
           url : myajax.ajaxurl,
           data : {action: "add_product_to_cart_dl", diamond_name: "<?php echo $_SERVER['REQUEST_URI']; ?>", diamond: JSON.stringify(diamondData)},
           success: function(response) {
            document.getElementById("gemfind-loading-mask").style.display = "none";
            window.location.href = '<?php echo get_site_url(); ?>' + '/cart/' + '?add-to-cart=' + response;
        }
    });
    }
</script>
<script type="text/javascript">
    var src = jQuery('div.diamention img').attr("data-src");
    imageExists1(src, function (exists) {
        if (exists) {
            jQuery('div.diamention img').attr('src', src);
        } else {
            jQuery('div.diamention img').attr('src', '<?php echo $noimageurl ?>');
        }
    });

    function imageExists1(url, callback) {
        var img = new Image();
        img.onload = function () {
            callback(true);
        };
        img.onerror = function () {
            callback(false);
        };
        img.src = url;
    }

    jQuery("#internaluselink").on('click', function () {
        jQuery('#msg').html('');
        jQuery('#internaluseform input#auth_password').val('');
        jQuery("#auth-section").modal("show");
    });


    function internaluselink() {
        jQuery('#internaluseform').validate({
            rules: {
                password: {
                    required: true
                }
            },
            submitHandler: function (form) {
                jQuery.ajax({
                    url: myajax.ajaxurl,
                    data: {'action': 'authenticateDealer_dl', 'form_data': jQuery('#internaluseform').serializeArray()},
                    type: 'POST',
                    dataType: 'json',
                    cache: true,
                    beforeSend: function (settings) {
                        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.output.status == 1) {
                            jQuery('#msg').html('<span class="success">' + response.output.msg + '</span>');
                            jQuery("#auth-section").modal("hide");
                            jQuery("#dealer-detail-section").modal("show");
                        } else {
                            jQuery('#msg').html('<span class="error">' + response.output.msg + '</span>');
                            jQuery('#internaluseform input#auth_password').val('');
                            jQuery('#msg').fadeOut(5000);
                        }
                        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                    }
                });
            }
        });
    }
    
    jQuery(window).on('beforeunload', function() { jQuery("video").css('visibility', 'hidden'); });

    jQuery(window).bind("load", function() {
        <?php
            $diamondajax=$diamond;
            $removeaddressList=$diamondajax['diamondData']['retailerInfo']->addressList;
            $removeaddressList[0]->address='';
        ?>
        var diamondData = '<?php echo json_encode($diamondajax); ?>';
        var post_diamond_data = JSON.stringify(diamondData);
        var product_track_url = window.location.href;
        setTimeout(function(){
            jQuery.ajax({
                url: myajax.ajaxurl,
                data: {action: "diamondTracking_dl", diamond_data: post_diamond_data, track_url:product_track_url },
            //data: {diamond_data:post_diamond_data,track_url:product_track_url},
            type: 'POST',
            //dataType: 'JSON',
            success: function(response) {
                console.log(response);
            }
        }).done(function(data) {
        });
    }, 1000
    );
    });

    function redirectOnCart(e, post_id) {
        e.preventDefault();
        window.location.href = '<?php echo get_site_url(); ?>' + '/cart/' + '?add-to-cart=' + post_id;    
    }    
</script>

<script type="text/javascript">
  jQuery(document).on('submit','form#completering_addtocart_form',function(e){      
      console.log('form is submitting');      
      var diamondData = [];
      var data = {};
      data.centerstone = jQuery("form#completering_addtocart_form #centerstone").val();
      data.carat = jQuery("form#completering_addtocart_form #carat").val();
      data.centerstonemincarat = jQuery("form#completering_addtocart_form #centerstonemincarat").val();
      data.centerstonemaxcarat = jQuery("form#completering_addtocart_form #centerstonemaxcarat").val();
      data.diamondid = jQuery("form#completering_addtocart_form #diamondid").val();
      data.islabcreated = jQuery("form#completering_addtocart_form #islabcreated").val();
      
      diamondData.push(data);
      var expire = new Date();
      expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
      jQuery.cookie("_wp_diamondsetting", JSON.stringify(diamondData), {
       path: '/',
       expires: expire
   });
       //window.location.href = '<?php echo $final_shop_url.'/diamondlink/'; ?>';
       
   });

</script>
<script>
    function verifyCaptcha(token){
        console.log('success!');
    };

    var onloadCallback = function() {
        jQuery( ".g-recaptcha" ).each(function() {
            grecaptcha.render(jQuery( this ).attr('id'), {
                'sitekey' :  '<?php echo $site_key; ?>',
                'callback' : verifyCaptcha
            });
        });
    };
</script>
<?php if(!empty($site_key)) { ?>
  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" data-callback="onSubmit" data-size="invisible"></div>
<?php } ?>
  <div id="detail_DbModal" class="Dbmodal">
    <div class="Dbmodal-content">
        <span class="Dbclose">&times;</span>
         <div class="loader_rb" style="display: none;">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "assets/images/diamond_rb.gif" ?>" style="width: 200px; height: 200px;">
        </div>
        <iframe src="" id="detail_iframevideodb" frameBorder="0" scrolling="no" style="width:100%; height:90%;" allow="autoplay"></iframe>
        <video width="100%" height="90%" id="detail_mp4video" loop autoplay>
            <source src="" type="video/mp4">
        </video>
    </div>
</div>
           
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId=1003857163475797&autoLogAppEvents=1" nonce="Uo0Kr4VM"></script>
<script async defer src="//assets.pinterest.com/js/pinit.js"></script>
<style type="text/css">

.grecaptcha-badge{
      visibility: visible !important;
}
</style>
