<?php
$options_rb          = getOptions_rb();
// echo '<pre>'; print_r($options_rb); exit; 
$ringmaxmincaratdata = json_decode( stripslashes( $_COOKIE['_wp_diamondsetting'] ), 1 );
$loadfilter = getRingFiltersRB();
// echo '<pre>'; print_r($loadfilter); exit; 
// echo '<pre>';
// print_r($loadfilter['isShowPrice']);
// exit();
$metalTypeArray=$loadfilter['metalType'];
$ringBackValue = json_decode( stripslashes( $_COOKIE['_wpsaveringbackvalue'] ) );
$loadfilter['currencySymbol'] = ( $loadfilter['currencySymbol'] =="US$" ) ? "$" : $loadfilter['currencySymbol'];
$diamondsettingcoockiedata = json_decode( $diamond_setting_cookie_data )[0];
$save_ring_filter_cookie_data = stripslashes( $_COOKIE['_wpsaveringfiltercookie'] );

$alldata = get_option( 'gemfind_ring_builder' );
$show_hints_popup=$alldata['show_hints_popup'];
$shapeArray                   = array();
if ( $save_ring_filter_cookie_data ) {
	$savedfilter = json_decode( $save_ring_filter_cookie_data );
} else if ( $ring_back_cookie_data ) {
	$savedfilter = json_decode( $ring_back_cookie_data );
} else {
	$savedfilter = new stdClass();
}
if ( isset( $ringBackValue ) ) {  
  if ( isset( $ringBackValue->shapeList ) ) {
    $shapeArray = explode( ',', $ringBackValue->shapeList );
  }
} elseif ( isset( $savedfilter ) && $savedfilter->shapeList ) {
  if ( isset( $savedfilter->shapeList ) ) {
    $shapeArray = explode( ',', $savedfilter->shapeList );
  }
} elseif ( isset( $diamondsettingcoockiedata->centerstone ) ) {  
	$shapeArray = explode( ',', $diamondsettingcoockiedata->centerstone );
} elseif ( isset( $ringmaxmincaratdata[0]['centerstone'] ) ) {
	$shapeArray = explode( ',', $ringmaxmincaratdata[0]['centerstone'] );
}

if(isset($_REQUEST['getSearchParams']['style'])){
  $ringcollection = urldecode($_REQUEST['getSearchParams']['style']);
 
  // Explode the string by space into an array of words
  $words = explode(" ", $ringcollection);

  // Capitalize the first letter of each word
  foreach ($words as &$word) {
    $word = ucfirst($word);
  }

  // Implode the array back into a string with spaces
  $ringcollection = implode(" ", $words);

  // Set the property with the modified value
  $savedfilter->ringcollection = $ringcollection;
}


if(isset($_REQUEST['getSearchParams']['shape'])){
  $shape = urldecode($_REQUEST['getSearchParams']['shape']);

  // Capitalize the first letter of the shape
  $shape = ucfirst($shape);

  // Set the property with the modified value
  $shapeArray = array($shape);

}


if(isset($_REQUEST['getSearchParams']['metal'])){
  $ringmetalList = urldecode($_REQUEST['getSearchParams']['metal']);

  $words = explode(" ", $ringmetalList);

  // Capitalize the first letter of each word
  foreach ($words as &$word) {
    $word = ucfirst($word);
  }

  // Implode the array back into a string with spaces
  $ringmetalList = implode(" ", $words);

  // Set the property with the modified value
  $savedfilter->ringmetalList = $ringmetalList;

}

if ( isset( $loadfilter['collections'][0] ) ) {
	?>
  <div class="filter-details">
    <div class="shape-container">
      <input name="currentpage" id="currentpage" type="hidden"
      value="<?php 
      if( isset( $ringBackValue->currentPage ) ) {
        echo $ringBackValue->currentPage;
      } elseif( $savedfilter->currentPage ) {
        echo $savedfilter->currentPage;
      } else {
        echo '1';
      }
      ?>">
      <input name="itemperpage" id="itemperpage" type="hidden"
      value="<?php
      if( isset( $ringBackValue->itemperpage ) ) {
        echo $ringBackValue->itemperpage;
      } elseif( isset( $savedfilter->itemperpage ) ) {
        echo $savedfilter->itemperpage;
      } else {
        getResultsPerPage_rb();
      }                   
      ?>">
      <input name="settingid" id="settingid" type="hidden"
      value="<?php echo isset( $savedfilter->SID ) ? $savedfilter->SID : '' ?>">
      <input name="viewmode" id="viewmode" type="hidden" value="">
      <input name="orderby" id="orderby" type="hidden"
      value="<?php echo isset( $savedfilter->orderBy ) ? $savedfilter->orderBy : 'cost-l-h' ?>">
			<?php //if (isset($diamondsettingcoockiedata->carat)) {
       ?>
       <input name="caratvalue" id="caratvalue" type="hidden"
       value="">
			<?php //}
			?>
      <div class="color-filter shape-bg" id="collections-section">
        <ul>
         <?php $collectionsOptions = (array) $loadfilter['collections'];
         ?>
         <?php foreach ( $collectionsOptions as $options ) : ?>
          <li class="<?php echo strtolower( str_replace( ' ', '', $options->collectionName ) ) ?> <?php echo ( $options == end( $collectionsOptions ) ) ? 'last' : '' ?> <?php
            if ( isset( $ringBackValue->ringcollection ) ) {
              if ( $ringBackValue->ringcollection ==
               $options->collectionName ) {
                echo "selected active";
              }
            } 
            elseif ( isset( $savedfilter->ringcollection ) ) {
             if ( $savedfilter->ringcollection ==
              $options->collectionName ) {
                echo "selected active";
              }
            }
            ?>" title="<?php echo $options->collectionName ?>"
            id="<?php echo strtolower( str_replace( ' ', '', $options->collectionName ) ) ?>">
            <div class="collection-type">
              <img src="<?php echo $options->collectionImage ?>"
              title="<?php echo $options->collectionName ?>"
              alt="<?php echo $options->collectionName ?>" height="60" width="60"/>
              <input type="radio" class="input-assumpte"
              id="ring_collection_<?php echo strtolower( $options->collectionName ) ?>"
              name="ring_collection"
              value="<?php echo $options->collectionName ?>" <?php if ( isset( $savedfilter->ringcollection ) ) {
               if ( $savedfilter->ringcollection == $options->collectionName ) {
                echo "checked='checked'";
              }
            } ?>/>
          </div>
          <label for="ring_collection_<?php echo $options->collectionName ?>"><?php echo $options->collectionName ?></label>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<div class="shape-container shapepricefiltersection">
  <div class="filter-main filter-alignment-right">
    <div class="filter-for-shape shape-bg">
      <h4>Shape
      <?php
      if($show_hints_popup=='yes'){ ?>
              <span class="show-filter-info" onclick="showfilterinfo('shape');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
            <?php } ?>
      </h4>
      <ul id="shapeul">
        <?php $shapeOptions = (array) $loadfilter['shapes'];
        ?>
        <?php foreach ( $shapeOptions as $options ) : ?>
          <li class="<?php echo strtolower( $options->shapeName ) ?> <?php echo ( $options == end( $shapeOptions ) ) ? 'last' : '' ?>"
            title="<?php echo $options->shapeName ?>"
            id="<?php echo strtolower( str_replace( ' ', '', $options->shapeName ) ) ?>">
            <div class="shape-type <?php echo ( in_array( $options->shapeName, $shapeArray ) ) ? 'selected active' : '' ?>">
              <input type="checkbox" class="input-assumpte"
              id="ring_shape_<?php echo strtolower( $options->shapeName ) ?>"
              name="ring_shape[]"
              value="<?php echo $options->shapeName ?>" <?php echo ( in_array( $options->shapeName, $shapeArray ) ) ? 'checked="checked"' : '' ?>/>
            </div>
            <label for="ring_shape_<?php echo $options->shapeName ?>"><?php echo $options->shapeName ?></label>
          </li>
        <?php endforeach; ?>
        <input type="hidden" name="selected_shape" id="selected_shape" value="">
      </ul>
    </div>
  </div>
  <div class="filter-main filter-alignment-left">
    <?php if ( $loadfilter['isShowPrice'] == '1' ) { ?>
    <div class="filter-main filter-alignment-left price-filter" style="width: 100%;">
      <div class="filter-for-shape shape-bg pricecss">
        <h4 class="title"><?php _e( 'Price', 'gemfind-ring-builder' ); ?> 
        <?php
          if($show_hints_popup=='yes'){ ?>
              <span class="show-filter-info" onclick="showfilterinfo('price');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
            <?php } ?>
          </h4>
        <div class="slider_wrapper">
			<?php $pricerange = (array) $loadfilter['priceRange'][0]; ?>		
			<?php if ( $options_rb['load_from_woocommerce'] == '1' ) {
			  if( isset( $ringBackValue->PriceMin ) ) {
				$price_from =  $ringBackValue->PriceMin;
			  } elseif( isset( $savedfilter->PriceMin ) ) {
				$price_from = $savedfilter->PriceMin;
			  } else {
				$price_from = round( $pricerange[0]->minPrice ); 
			  }									
			} else {
			  if( isset( $ringBackValue->PriceMin ) ) {
				$price_from = $ringBackValue->PriceMin;
			  } elseif( isset( $savedfilter->PriceMin ) ) {
				$price_from = $savedfilter->PriceMin;
			  } else {
				$price_from = 0;
			  }									
			}
			?>
			<?php if ( $options_rb['load_from_woocommerce'] == '1' ) {
              if( isset( $ringBackValue->PriceMax ) ) {
                $price_to = $ringBackValue->PriceMax;
              } elseif( isset( $savedfilter->PriceMax ) ) {
                $price_to = $savedfilter->PriceMax;
              } else {
                $price_to = $pricerange[0]->maxPrice; 
              }									
            } else {
              if( isset( $ringBackValue->PriceMax ) ) {
                $price_to = $ringBackValue->PriceMax;
              } elseif( isset( $savedfilter->PriceMax ) ) {
                $price_to = $savedfilter->PriceMax;
              } else {
                $price_to = $pricerange['maxPrice']; 
              }									
            }
            ?>
            <?php 
                    if($options_rb['price_row_format'] == 'left'){
                      $class_left = 'price-filter_left';
                    }else{
                      $class_left = ''; 
                    }
                  
                    if($options_rb['price_row_format'] == 'left'){
                      $class_right = 'price-filter_right';
                    }else{
                      $class_right = ''; 
                    }
            ?>
          <div class="price-main ui-slider" id="price_slider" data-min="<?php echo $price_from; ?>" data-max="<?php echo str_replace( ',', '', $price_to ); ?>">
            <div class="price-left <?php echo $class_left;?>">
              <span class="currency-icon"><?php echo ( $loadfilter['currencyFrom'] != "USD" ) ? $loadfilter['currencyFrom'] . $loadfilter['currencySymbol'] : $loadfilter['currencySymbol']; ?></span>
              <input type="text"
              class="ui-slider-val slider-left"
              name="price[from]"
              value="<?php echo $price_from; ?>" data-type="min" autocomplete="off" inputmode="numeric" pattern="[-+]?[0-9]*[.,]?[0-9]+" />
            </div>
            <div class="price-right <?php echo $class_right;?>">
              <span class="currency-icon"><?php echo ( $loadfilter['currencyFrom'] != "USD" ) ? $loadfilter['currencyFrom'] . $loadfilter['currencySymbol'] : '$'; ?></span>
              <input type="text"
              class="ui-slider-val slider-right"
              name="price[to]"
              value="<?php echo str_replace( ',', '', $price_to ); ?>" data-type="max" autocomplete="off" inputmode="numeric" pattern="[-+]?[0-9]*[.,]?[0-9]+" />
              <input type="hidden" name="priceto" class="slider-right-val"
              value="<?php echo str_replace( ',', '', $price_to ); ?>">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
    <div class="color-filter clarity-filter shape-bg metaltypeli">
      <h4><?php _e( 'Metal', 'gemfind-ring-builder' ); ?>
        <?php
        if($show_hints_popup=='yes'){ ?>
          <span class="show-filter-info" onclick="showfilterinfo('metal');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
        <?php } ?>
      </h4>
      <ul style="padding-top: 8px;">
        <?php
         $metalTypeOptions1 = array();
         $i=0;
        foreach($metalTypeArray as $metalType){
             $metalTypeOptions1[$i] = $metalType->metalType;
             $i++;
        }
        // echo '<pre>';
        // print_r($metalTypeOptions);
        $metalTypeOptions = array(
            '0' => array('metalType' => '14K White Gold'),
            '1' => array('metalType' => '14K Yellow Gold'),
            '2' => array('metalType' => '14K Rose Gold'),
            '3' => array('metalType' => '18K White Gold'),
            '4' => array('metalType' => '18K Yellow Gold'),
            '5' => array('metalType' => '18K Rose Gold'),
            '6' => array('metalType' => 'Platinum'),
        );

        // echo '<pre>';
        // print_r($metalTypeOptions1);
        // exit();
        foreach ($metalTypeOptions as $metalTypevalue) {
            $metalTypevalue = (array) $metalTypevalue;
            ?>
            <li class="<?php
            if (isset($savedfilter->ringmetalList)) {
                if ($savedfilter->ringmetalList == $metalTypevalue['metalType']) {
                    echo "selected active";
                }
            }
            if (isset($selectedMetal)) {
                if ($selectedMetal == $metalTypevalue['metalType']) {
                    echo "selected active";
                }
            }
            ?>"
            <?php
              if(!in_array($metalTypevalue['metalType'],$metalTypeOptions1)){
                          echo "style='opacity: 0.5; pointer-events: none;'";
              }else{
                      		echo "";
             }
            ?>
            >

            <input type="radio" class="input-assumpte" <?php if(!in_array($metalTypevalue['metalType'],$metalTypeOptions1)){ ?> disabled <?php } ?> id="ring_metal_<?= strtolower(str_replace(' ', '', $metalTypevalue['metalType'])) ?>" name="ring_metal" value="<?= $metalTypevalue['metalType'] ?>" 
			 <?php
                    if (isset($savedfilter->ringmetalList)) {
                        if ($savedfilter->ringmetalList == $metalTypevalue['metalType']) {
                            echo "checked='checked'";
                        }
                    }
                    if (isset($selectedMetal)) {
                        if ($selectedMetal == $metalTypevalue['metalType']) {
                            echo "checked='checked'";
                        }
                    }
                    ?>/>
                <?php
                $metalType = explode(" ", $metalTypevalue['metalType']);
                if ($metalType[0] == "Platinum") {
                    $metalType[0] = "PT";
                    $metalType[1] = "Platinum";
                }
                echo "<span class='metallabel " . strtolower($metalType[1]) . strtolower($metalType[2]) . "'>" . $metalType[0] . "</span> " . $metalType[1] . $metalType[2];
                ?>
            </li>
        <?php } ?>
    </ul>
</div>
</div>
</div>
</div>
<?php } else {
	_e( 'Something went wrong, please try after some time!', 'gemfind-ring-builder' );
} ?>

<?php if($show_hints_popup=='yes'){ ?>
<script type="text/javascript">
   var filtertype = '';
   function showfilterinfo(filtertype){
    var info_html = '';
    var baseurl = '<?php echo RING_BUILDER_URL; ?>';
    var shopname = '<?php echo ($shopdata['shopurl'] == 'bylu.myshopify.com' ? 'Ken & Dana Design' : 'Our site'); ?>';

      if(typeof filtertype !== 'undefined' && filtertype == 'shape'){
         info_html = '<p>A diamond’s shape is not the same as a diamond’s cut. The shape refers to the general outline of the stone, and not its light refractive qualities. Look for a shape that best suits the ring setting you have chosen, as well as the recipient’s preference and personality. Here are some of the more common shapes that '+shopname+' offers:</p><div class="popup-Diamond-Table" style="height:160px;"><ol class="list-unstyled"><li><span class="popup-Dimond-Sketch round"></span><span>Round</span></li><li><span class="popup-Dimond-Sketch asscher"></span><span>Asscher</span></li><li><span class="popup-Dimond-Sketch marquise"></span><span>Marquise</span></li><li><span class="popup-Dimond-Sketch oval"></span><span>Oval</span></li><li><span class="popup-Dimond-Sketch cushion"></span><span>Cushion</span></li><li><span class="popup-Dimond-Sketch radiant"></span><span>Radiant</span></li><li><span class="popup-Dimond-Sketch pear"></span><span>Pear</span></li><li><span class="popup-Dimond-Sketch emerald"></span><span>Emerald</span></li><li><span class="popup-Dimond-Sketch heart"></span><span>Heart</span></li><li><span class="popup-Dimond-Sketch princess"></span><span>Princess</span></li></ol></div>';
      }
      if(typeof filtertype !== 'undefined' && filtertype == 'metal'){
         info_html = 'This refer to different type of Metal Type to filter and select the appropriate ring as per your requirements. Look for a metal type best suit of your chosen ring.';
      }
      if(typeof filtertype !== 'undefined' && filtertype == 'price'){
         info_html = 'This refer to different type of Price to filter and select the appropriate ring as per your requirements. Look for  best suit price of your chosen ring.';
      }

      console.log(filtertype);
      if(typeof filtertype !== 'undefined' && (filtertype == 'navminedsetting')){
         info_html = 'Formed over billions of years, natural diamonds are mined from the earth.  Diamonds are the hardest mineral on earth, which makes them an ideal material for daily wear over a lifetime.  Our natural diamonds are conflict-free and GIA certified.';
      }
      if(typeof filtertype !== 'undefined' && (filtertype == 'navlabsetting')){
         info_html = 'Lab-grown diamonds are created in a lab by replicating the high heat and high pressure environment that causes a natural diamond to form. They are compositionally identical to natural mined diamonds (hardness, density, light refraction, etc), and the two look exactly the same. A lab-grown diamond is an attractive alternative for those seeking a product with less environmental footprint.';
      }

      jQuery('#show-filter-info .modal-body p').html(info_html);
      filtertype = '';
      jQuery("#show-filter-info").modal("show");
   }
</script>
<div class="modal fade" id="show-filter-info" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <!-- <h4 class="modal-title"></h4> -->
        </div>
        <div class="modal-body">
          <p class="note"></p>
        </div>
      </div>
    </div>
</div>
<?php  }  ?>
<script type="text/javascript">
  jQuery("#collections-section ul li, #shapeul li").click(function(){
      var style = jQuery("input[name = 'ring_collection']:checked").val();
      var shape = jQuery("input[name = 'ring_shape[]']:checked").val();
      if(typeof shape !== 'undefined' && typeof style!== 'undefined'){
        var shareurl='?style='+style+'&'+'shape='+shape;
      } else if(typeof shape == 'undefined' && typeof style!== 'undefined'){
        var shareurl='?style='+style;
      } else if(typeof shape !== 'undefined' && typeof style== 'undefined'){
        var shareurl='?shape='+shape;
      } else {
        var shareurl='';
      }
      window.history.pushState('', '', window.location.origin+'/ringbuilder/settings/'+shareurl );
  });
</script>