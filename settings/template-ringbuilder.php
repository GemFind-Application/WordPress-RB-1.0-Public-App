<?php
//defined( 'RING_BUILDER_Path' ) OR exit( 'No direct script access allowed' );
/**
 * Template Name: Ring Builder
 */
get_header();
include( plugin_dir_path( __FILE__ ) . 'head.php' );
include( plugin_dir_path( __FILE__ ) . 'header.php' ); ?>


<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>
<?php
//include_once(plugin_dir_path( __FILE__ ) . 'settings/includes-api-functions-rb.php');
//include( plugin_dir_path( __FILE__ ) . 'assets/js/list.js' );

$checkDiamondCookieData = json_decode( stripslashes( $_COOKIE['_wp_diamondsetting'] ) )[0];
$options = getOptions_rb();

// print_r($options['top_textarea']);
// exit();

if ( ! empty( $options['dealerid'] ) ) { 

    $navigationapi = get_option( 'gemfind_ring_builder' );
    $show_hints_popup    =$navigationapi['show_hints_popup'];
    $sticky_header       =$navigationapi['enable_sticky_header'];
    $results = sendRequest_rb( array( 'navigationapirb' => $navigationapi['navigationapirb'] ) );

    // print_r($results);

  ?>
    <div class="ringbuilder-settings-index">
        <div class="loading-mask gemfind-loading-mask" style="display:none;">
            <div class="loader gemfind-loader"><p>Please wait...</p>
            </div>
        </div>
        <div id="search-rings" class="flow-tabs">
            <div class="placeholder-content placeholder-content-top">
                <div class="placeholder-content_item box1"></div>
                <div class="placeholder-content_item"></div>
                <div class="placeholder-filter_box placeholder-flex placeholder-filter placeholder-ringbuilder">
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>
                    <div class="placeholder-content_item box"></div>                        
                </div>
                <div class="placeholder-flex ringbuilder-filter">
                    <div class="placeholder-filter_inner placeholder-flex placeholder-filter">
                        <div class="placeholder-content_item head"></div>
                        <div class="placeholder-filter_box placeholder-flex">
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>
                            <div class="placeholder-content_item box"></div>                        
                        </div>
                    </div>
                    <div class="placeholder-filter_inner placeholder-flex  placeholder-filter">
                        <div class="placeholder-filter_inner placeholder-flex right-side">
                            <div class="placeholder-content_item head"></div>
                            <div class="placeholder-content_item box-list placeholder-filter_box"></div>
                        </div>
                        <div class="placeholder-filter_inner placeholder-flex right-side">
                        <div class="placeholder-content_item head"></div>
                            <div class="placeholder-filter_box placeholder-flex">
                                <div class="placeholder-content_item box"></div>
                                <div class="placeholder-content_item box"></div>
                                <div class="placeholder-content_item box"></div>
                                <div class="placeholder-content_item box"></div>
                                <div class="placeholder-content_item box"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!empty($options['top_textarea'])) {?>
            <div class="tab-content"  style="display:none;">
               <div class="diamond-bar">
                  <?php echo $options['top_textarea']; ?>
              </div>
            </div>
            <?php } ?>

            <div class="tab-section" style="display:none;">
                <ul class="tab-ul">
                    <?php if ( $checkDiamondCookieData ) { ?>
                        <li class="tab-li">
                            <div><a href="<?php echo site_url().'/ringbuilder/diamondlink/'; ?>"><span
                                            class="tab-title"><?php _e( 'Choose Your', 'gemfind-ring-builder' ); ?><strong><?php _e( 'Diamond', 'gemfind-ring-builder' ); ?></strong></span><i
                                            class="diamond-icon tab-icon"></i></a></div>
                        </li>
                    <?php } else { ?>
                        <li class="tab-li active">
                            <div>
                                <?php
                                    global $wp_query;
                                    if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {    
                                        $labsettings = '/islabsettings/1/';
                                    }
                                ?>
                                            <a href="<?php echo site_url().'/ringbuilder/settings/' . $labsettings; ?>"><span
                                            class="tab-title"><?php _e( 'Choose Your', 'gemfind-ring-builder' ); ?><strong><?php _e( 'Setting', 'gemfind-ring-builder' ); ?></strong></span><i
                                            class="ring-icon tab-icon"></i></a></div>
                        </li>
                    <?php } ?>
                    <?php if ( ! $checkDiamondCookieData ) { ?>
                        <li class="tab-li">
                            <div><a href="<?php echo site_url().'/ringbuilder/diamondlink/'; ?>"><span
                                            class="tab-title"><?php _e( 'Choose Your', 'gemfind-ring-builder' ); ?><strong><?php _e( 'Diamond', 'gemfind-ring-builder' ); ?></strong></span><i
                                            class="diamond-icon tab-icon"></i></a></div>
                        </li>
                    <?php } else { ?>
                        <li class="tab-li active">
                            <div>
                                <?php
                                    global $wp_query;
                                    if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {    
                                        $labsettings = '/islabsettings/1/';
                                    }
                                ?>
                                            <a href="<?php echo site_url().'/ringbuilder/settings' . $labsettings; ?>"><span
                                            class="tab-title"><?php _e( 'Choose Your', 'gemfind-ring-builder' ); ?><strong><?php _e( 'Setting', 'gemfind-ring-builder' ); ?></strong></span><i
                                            class="ring-icon tab-icon"></i></a></div>
                        </li>
                    <?php } ?>
                    <li class="tab-li">
                        <div><a href="javascript:;"><span
                                        class="tab-title"><?php _e( 'Review', 'gemfind-ring-builder' ); ?><strong><?php _e( 'Complete Ring', 'gemfind-ring-builder' ); ?></strong></span><i
                                        class="finalring-icon tab-icon"></i></a></div>
                    </li>

                </ul>
            </div>
           
            <div class="tab-content"  style="display:none;">
                <form id="search-rings-form" method="post"
                      action="" class="search_form">
                    <input name="caratvalue" id="caratvalue" type="hidden" value="<?php if(isset($checkDiamondCookieData->carat)){  echo $checkDiamondCookieData->carat; } ?>">
                    <input name="submitby" id="submitby" type="hidden" value="">
                    <section class="rings-search">
                        <div class="d-container">
                            <div class="d-row">
                                <div class="rings-filter">
                                   <div class="diamond-filter-title save-reset-filters"  style="display:none;">
                            <ul class="filter-left" id="navbar">
                            <?php
                                    $filter_mode = '';
                                    $i = 0;
                                    //print_r($_REQUEST);
                                    foreach ( $results as $result ) {
                                      foreach ( $result as $key => $value ) {

                                          if ( strtolower( $key ) != 'navrequest' && strtolower( $key ) != '$id' && strtolower( $key ) != 'navadvanced' ) {
                                              
                                              if($value){
                                                if( $i == 0 ){
                                                  $filter_mode = strtolower( $key ); }
                                                    if( !empty($_GET['type']=='navlabsetting') ){
                                                       $filter_mode = 'navlabsetting';
                                                    }
                                                                                                                                        
                                                  if( !empty($_GET['type']=='navlabsetting') ){?>
                                                    <li class="<?php echo ( strtolower( $key ) == 'navlabsetting' ) ? 'active' : ''; ?>"
                                                        id="<?php echo strtolower( $key ); ?>">
                                                        <a href="javascript:;"  onclick="moderb(<?php echo strtolower( $key ); ?>); ring_search();"
                                                           title="<?php echo $value; ?>" id="<?php echo ( strtolower( $key ) == 'navcompare' ) ? 'comparetop' : ''; ?>">
                                                            <?php echo $value; ?> </a>
                                                                        </li>
                                                                          <?php } else { ?>

                                                                        <li class="<?php echo ( $i == 0 ) ? 'active' : ''; ?>"
                                                                            id="<?php echo strtolower( $key ); ?>">
                                                                            <a href="javascript:;"
                                                                               onclick="moderb(<?php echo strtolower( $key ); ?>); ring_search();"
                                                                               title="<?php echo $value; ?>"
                                                                               id="<?php echo ( strtolower( $key ) == 'navcompare' ) ? 'comparetop' : ''; ?>">
                                                                                <?php echo $value=='Mined Setting' ? 'Mined':'Lab Grown'; ?> </a>
                                                                            <?php if(strtolower($key) != 'navcompare' && $show_hints_popup=='yes'){  ?>
                                                                                <span class="show-filter-info" onclick="showfilterinfo('<?php echo strtolower( $key ); ?>');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                                                            <?php  } ?>
                                                                        </li>
                                                                    <?php }
                                                                    $i++;
                                                                   }
                                                                }
                                                              }
                                                             }
                                                        ?>
                                                    </ul>
                                        <ul class="filter-right">
                                            <li><a href="javascript:;" onclick="SaveFilterRb();">Save Search</a></li>
                                            <li><a href="javascript:;" onclick="ResetFilterRb();">Reset</a></li>
                                        </ul>
                                    </div>
                                    <div class="filter-main-div" id="filter-main-div">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php             
                            //echo "Filename:".$filename;
                            /*$filter_mode = 'navstandard';*/
                    if( $filename == 'navminedsetting' ){
                        $filter_mode = 'navminedsetting';
                    } else if ( $filename == 'navlabsetting' ) {
                        $filter_mode = 'navlabsetting';
                    }
                          // in case filter_mode blank then
                    if($filter_mode == ''){
                        $filter_mode = 'navminedsetting';
                    }
                    ?>
                    <input type="hidden" name="filtermode" id="filtermode" value="<?php echo $filter_mode ?>">
                    <input type="submit" name="Submit" id="submit" style="visibility: hidden;font-size: 0px;">
                </form>
            </div>
        </div>
        
        <div class="result filter-advanced">
            <div class="placeholder-content">
          <div class="placeholder-content_table">
              <?php if($options['default_view']=="grid"){ ?>
              <div class="placeholder-product_list">
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>  
              </div>
          <?php } ?>
          <?php if($options['default_view']=="list"){ ?>
                <div class="placeholder-product_list">
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>
                  <div class="placeholder-content_list">
                      <div class="placeholder-content_item round-img"></div>
                      <div class="placeholder-content_item placeholder-product"></div>
                      <div class="placeholder-content_item placeholder-price"></div>
                      <div class="placeholder-content_item placeholder-compare"></div>
                  </div>  
              </div>
          <?Php } ?>
          </div>
          <div class="placeholder-content_footer placeholder-flex">
              <div class="placeholder-content_item button-box"></div>
              <div class="placeholder-content_item head"></div>
          </div>
      </div>
        </div>
        <?php
        $gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
        if($gemfind_ring_builder['show_copyright']=="yes"){                    ?>
        <div class="copyright-text" style="display:none;">
          <p>Powered By <a href="http://www.gemfind.com" target="_blank">GemFind</a></p>
        </div>
        <?php  } ?>
    </div>
    <?php
} else { ?>
    <div class="error">
        <p>
            <strong>Error: </strong><?php _e( "Dealer ID not found. Kindly insert correct Dealer ID in plugin configuration.", "gemfind-ring-builder" ); ?>
        </p>
    </div>
<?php } ?>
    <script type="text/javascript">
        jQuery( document ).ready(function() {
            jQuery.removeCookie('_wp_ringsetting', {path: '/'});
            //jQuery.removeCookie('_wp_diamondsetting', {path: '/'});
            <?php
            global $wp_query;
            if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {?>
                var expire = new Date();
                expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
                jQuery.cookie("_islabsettingurl", 1, {
                    path: '/',
                    expires: expire
                });            
            <?php } else { ?>
                jQuery.cookie("_islabsettingurl", '', {
                  path: '/',
                  expires: -1
                });            
            <?php } ?>
        });
       
    </script>
<?php get_footer(); ?>