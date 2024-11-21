<?php
/**
 * Template Name: Compare Diamonds
 */
get_header();
include_once( 'head.php' );
include_once( plugin_dir_path( __FILE__ ) . '/settings/head.php' );
include_once( plugin_dir_path( __FILE__ ) . '/settings/header.php' );
$compare_diamond_data = $_COOKIE['comparediamondProductrb'];

$navigationapi = get_option( 'gemfind_ring_builder' );
$results       = sendRequest_dl( array( 'navigationapi' => $navigationapi['navigationapi'] ), get_site_url() );
//$currency = $block->getCurrencySymbol_dl();
if ( count( $results[0] ) > 0 ) : ?>
    <style type="text/css">
        tr.remove {
            display: none;
        }
    </style>
    <section id="gemfind-product-demo-site" class="diamond-list-screen">
    <main class="main-content grid__item" id="MainContent" role="main">
    <div class="loading-mask gemfind-loading-mask">
        <div class="loader gemfind-loader">
            <p>Please wait...</p>
        </div>
    </div>
    <div id="search-diamonds">
        <section class="compare-product diamonds-search">
            <div class="d-container">
                <div class="d-row">
                    <div class="diamonds-details no-padding">
                        <div class="diamonds-filter">
                            <div class="filter-title">
                                <ul class="filter-left">
									<?php
                                    foreach ( $results as $result ) {
                                        foreach ( $result as $key => $value ) {
                                            if ( strtolower( $key ) != 'navrequest' && strtolower( $key ) != '$id' && strtolower( $key ) != 'navadvanced' ) {
                                                if ( strtolower( $key ) == 'navstandard' ) {
                                                    $href = get_site_url() . '/ringbuilder/diamondlink/navstandard';
                                                } elseif ( strtolower( $key ) == 'navfancycolored' ) {
                                                    $href = get_site_url() . '/ringbuilder/diamondlink/navfancycolored';
                                                } elseif ( strtolower( $key ) == 'navlabgrown' ) {
                                                    $href = get_site_url() . '/ringbuilder/diamondlink/navlabgrown';
                                                } else {
                                                    $href = "javascript:;";
                                                }
                                                ?>
                                                <li class="<?php echo ( strtolower( $key ) == 'navcompare' ) ? 'active' : ''; ?>"
                                                    id="<?php echo strtolower( $key ); ?>">
                                                    <a href="<?php echo $href; ?>"
                                                       onclick="mode(<?php echo strtolower( $key ); ?>); diamond_search();"
                                                       title="<?php echo $value; ?>"
                                                       id="<?php echo ( strtolower( $key ) == 'navcompare' ) ? 'comparetop' : ''; ?>">
                                                        <?php echo $value; ?> </a>
                                                </li>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="compare-info">
							<?php
							$noimageurl       = plugins_url( __DIR__ ) . "/assets/images/no-image.jpg";
							$compare_products = json_decode( stripslashes( $compare_diamond_data ), 1 );
							# Initialise a new array.
							$compareItems = [];
							# Iterate over every associative array in the data array.
							foreach ( $compare_products as $compare_product ) {
								# Iterate over every key-value pair of the associative array.
								foreach ( $compare_product as $key => $value ) {
									# Ensure the sub-array specified by the key is set.
									if ( ! isset( $compareItems[ $key ] ) ) {
										$compareItems[ $key ] = [];
									}
									# Insert the value in the sub-array specified by the key.
									$compareItems[ $key ][] = $value;
								}
							}

							 if (empty($compare_products)) { ?>
							 	<div class="emptydata">
									<h2> NO DIAMONDS TO COMPARE </h2>
								</div>

							<?php } else {
							// $alphaarray = array( 0 => 'a_col', 1 => 'b_col', 2 => 'c_col', 3 => 'd_col', 4 => 'e_col' );
							$alphaarray = array( 0 => 'a_col', 1 => 'b_col', 2 => 'c_col', 3 => 'd_col', 4 => 'e_col',5 => 'f_col' ); ?>
							
                            <div class="responsive-table">

                                <table id="compare-sortable">
									<?php
									$i = 0;
									// print_r($compareItems['Price']);
									// 	exit;

									foreach ( $compareItems as $key => $value ):
										if ( $key == 'Image' ):
											?>
                                            <thead class="thead-dark">
                                            <tr class="ui-state-default" id="disable-drag">
												<?php
												for ( $j = 0; $j < count( $value ); $j ++ ) {
													if ( $i ++ == 0 ): ?>
                                                        <td></td>
													<?php endif; ?>
                                                    <td class="<?php echo $alphaarray[ $j ]; ?>">
														<?php
														$diamond_image = preg_replace( '/\s/', '', $value[ $j ] );
														$handle        = curl_init( $diamond_image );
														curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );

														/* Get the HTML or whatever is linked in $url. */
														$response = curl_exec( $handle );

														/* Check for 404 (file not found). */
														$httpCode = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
														curl_close( $handle );
														?>
                                                        <img alt="No Image" src="<?php if ( $httpCode != 404 ) {
															echo $diamond_image;
														} else {
															echo $noimageurl;
														} ?>"/>
                                                    </td>
												<?php } ?>
                                            </tr>
                                            </thead>
                                            <tbody>
										<?php
										else: $k = 0; ?>
											<?php if ( $key == 'ID' ): continue; endif; ?>                                            
                                            <tr class="enable-drag">
												<?php
												for ( $j = 0; $j < count( $value ); $j ++ ):
													if ( $k ++ == 0 ): ?>
                                                        <th><a data-toggle="tooltip" data-placement="bottom"
                                                               title="Remove Row" href="javascript:;" class="rowremove"
                                                               onclick="this.parentNode.parentNode.className= 'remove'"><?php _e( 'Remove', 'gemfind-diamond-link' ); ?> </a>
															<?php if ( $key == 'Sku' ): echo '#'; endif;
															echo $key; ?>
                                                        </th>
													<?php endif; ?>
                                                    <td class="<?php echo $alphaarray[ $j ]; ?>">
														<?php
														if ( $key == 'Price' && preg_match('/[0-9,]/' , $value[$j] ) ): echo getCurrencySymbol_dl(); endif;
														
														echo ( str_ireplace(".00", "", $value[ $j ] )) ? str_ireplace(".00", "",$value[ $j ]) : '-';
														if ( $key == 'Table' && $value[ $j ] ): echo '%'; endif;
														if ( $key == 'Depth' && $value[ $j ] ): echo '%'; endif;
														?>
                                                    </td>
												<?php endfor; ?>
                                            </tr>
										<?php endif;
									endforeach;
									?>
									</tbody>
                                    <tfoot>
                                    <tr class="compare-actions">
										<?php
										$k = 0;
										for ( $i = 0; $i < count( $compareItems['Sku'] ); $i ++ ):
											?>
											<?php if ( $k ++ == 0 ): ?>
                                            <td></td>
										<?php endif; ?>
                                            <td class="<?php echo $alphaarray[ $i ]; ?>">
                                                <div class="actions-row">
													<?php
													if ( isset( $compareItems['Shape'] ) ) {
														$urlshape = str_replace( ' ', '-', $compareItems['Shape'][ $i ] ) . '-shape-';
													} else {
														$urlshape = '';
													}

													if ( isset( $compareItems['Carat'] ) ) {
														$urlcarat = str_replace( ' ', '-', $compareItems['Carat'][ $i ] ) . '-carat-';
													} else {
														$urlcarat = '';
													}
													if ( isset( $compareItems['Color'] ) ) {
														$urlcolor = str_replace( ' ', '-', $compareItems['Color'][ $i ] ) . '-color-';
													} else {
														$urlcolor = '';
													}
													if ( isset( $compareItems['Clarity'] ) ) {
														$urlclarity = str_replace( ' ', '-', $compareItems['Clarity'][ $i ] ) . '-clarity-';
													} else {
														$urlclarity = '';
													}
													if ( isset( $compareItems['Cut'] ) ) {
														$urlcut = str_replace( ' ', '-', $compareItems['Cut'][ $i ] ) . '-cut-';
													} else {
														$urlcut = '';
													}
													if ( isset( $compareItems['Cert'] ) ) {
														$urlcert = str_replace( ' ', '-', $compareItems['Cert'][ $i ] ) . '-certificate-';
													} else {
														$urlcert = '';
													}

													$urlstring = strtolower( $urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $compareItems['ID'][ $i ] );

													$type = '';
													if ( isset( $compareItems['Type'] ) ) {
														$type = $compareItems['Type'][ $i ];
													}
													$diamondviewurl = getDiamondViewUrl_dl( $urlstring, $type, get_site_url() . '/ringbuilder/diamondlink', $pathprefixshop ) . '/' . $compareItems['Type'][ $i ];
													?>
                                                    <a href="<?php echo $diamondviewurl; ?>"
                                                       class="view-product"><?php _e( 'View', 'gemfind-diamond-link' ); ?>
                                                    </a>
                                                    <a data-toggle="tooltip" data-placement="bottom"
                                                       title="Remove Diamond" href="javascript:;" class="delete-row"
                                                       onclick="removeDummy('<?php echo $alphaarray[ $i ]; ?>','<?php echo $compareItems['ID'][ $i ]; ?>')"></a>
                                                </div>
                                            </td>
										<?php endfor;
										?>
                                    </tr>
                                    </tfoot>
                                </table>
                                
                                <div class="mobile-compare-view">
                                    <div class="compare-main-container">
										<?php
										foreach ( $compare_products as $key => $value ):
											?>
                                            <div class="compare-items">
                                                <div class="item-col-1">
                                                    <img alt="Diamond" src="<?php echo $value['Image'] ?>"/>
                                                    <span class="diamond-value shape-type"><?php echo $value['Shape'] ?></span>
                                                </div>
                                                <div class="item-col-2">
                                                    <span class="diamond-value"><?php echo( $value['Carat'] ? $value['Carat'] : "-" ) ?></span>
                                                    <span class="diamond-label"><?php _e( "Carat", "gemfind-diamond-link" ) ?></span>
                                                    <span class="diamond-value"><?php echo $value['Clarity'] ?></span>
                                                    <span class="diamond-label"><?php _e( "Clarity", "gemfind-diamond-link" ) ?></span>
                                                </div>
                                                <div class="item-col-3">
                                                    <span class="diamond-value"><?php echo( $value['Cut'] ? $value['Cut'] : "-" ) ?></span>
                                                    <span class="diamond-label"><?php _e( "Cut", "gemfind-diamond-link" ) ?></span>
                                                    <span class="diamond-value"><?php echo $value['Color'] ?></span>
                                                    <span class="diamond-label"><?php _e( "Color", "gemfind-diamond-link" ) ?></span>
                                                </div>
                                                <div class="item-col-4">
                                                    <span class="diamond-value"><?php echo  $value['Price'] ?></span>
                                                    <span class="diamond-label"><?php _e( "Price", "gemfind-diamond-link" ) ?></span>
													<?php if ( isset( $value['Shape'] ) ) {
														$urlshape = str_replace( ' ', '-', $value['Shape'] ) . '-shape-';
													} else {
														$urlshape = '';
													}

													if ( isset( $value['Carat'] ) ) {
														$urlcarat = str_replace( ' ', '-', $value['Carat'] ) . '-carat-';
													} else {
														$urlcarat = '';
													}
													if ( isset( $value['Color'] ) ) {
														$urlcolor = str_replace( ' ', '-', $value['Color'] ) . '-color-';
													} else {
														$urlcolor = '';
													}
													if ( isset( $value['Clarity'] ) ) {
														$urlclarity = str_replace( ' ', '-', $value['Clarity'] ) . '-clarity-';
													} else {
														$urlclarity = '';
													}
													if ( isset( $value['Cut'] ) ) {
														$urlcut = str_replace( ' ', '-', $value['Cut'] ) . '-cut-';
													} else {
														$urlcut = '';
													}
													if ( isset( $value['Cert'] ) ) {
														$urlcert = str_replace( ' ', '-', $value['Cert'] ) . '-certificate-';
													} else {
														$urlcert = '';
													}

													$urlstring = strtolower( $urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $value['ID'] );

													$type = '';
													if ( isset( $value['Type'] ) ) {
														$type = $value['Type'];
													}
													$diamondviewurl = getDiamondViewUrl_dl( $urlstring, $type, get_site_url() . '/ringbuilder/diamondlink', $pathprefixshop ) . '/' . $type;
													?>
                                                    <a href="<?php echo $diamondviewurl; ?>"
                                                       class="view-product"><?php _e( 'View', 'gemfind-diamond-link' ); ?>
                                                    </a>
                                                </div>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                       
                        <?php } ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function ($) {                                    
                                    jQuery('[data-toggle="tooltip"]').tooltip();
                                });

                                function removeDummy(className,selectedcheckboxidrb) {
                                    jQuery('.loading-mask').show();
                                    var elements = document.getElementsByClassName(className);
                                    while (elements.length > 0) {
                                        elements[0].parentNode.removeChild(elements[0]);
                                    }
                                    if(jQuery('.compare-actions td').length == 1 ){
                                        var redirectURL = '<?php echo $site_url; ?>' + '/ringbuilder/diamondlink';
                                        window.location.replace(redirectURL);
                                        //console.log("if");
                                    }
                                    compareItemsarrayrb.pop(selectedcheckboxidrb);
                                    jQuery.ajax({ 
                                     url: sbiajaxurl,
                                     data: {action: 'removeDiamondrb', selectedcheckboxidrb : selectedcheckboxidrb },
                                     type: 'POST',
                                     success: function(response) {
                                        if(JSON.parse(localStorage.getItem("compareItemsrb"))){
                                        let delLocaldatarb = JSON.parse(localStorage.getItem("compareItemsrb"));
                                        let removeLocaldata = delLocaldatarb.map(delLocaldatarb => delLocaldatarb.ID);
                                        let index = delLocaldatarb.findIndex(delLocaldatarb => delLocaldatarb.ID == selectedcheckboxidrb);
                                        if (index > -1) {
                                             delLocaldatarb.splice(index, 1);
                                             localStorage.removeItem('compareItemsrb');
                                        }
                                        localStorage.setItem('compareItemsrb', JSON.stringify(delLocaldatarb));
                                        }   
                                        jQuery('.loading-mask').hide();
                                      }
                                    });
                                 }


                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
      $gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
      if($gemfind_ring_builder['show_copyright']=="yes"){                    ?>
      <div class="copyright-text">
        <p>Powered By <a href="http://www.gemfind.com" target="_blank">GemFind</a></p>
      </div>
      <?php  } ?>
  </main>
 </section>
<?php else: _e( 'Please configure the Gemfind Diamond Search App from admin.', 'gemfind-diamond-link' ); endif;
?>
<?php get_footer(); ?>