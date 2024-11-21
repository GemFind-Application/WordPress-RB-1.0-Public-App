<?php
/**
 * Template Name: Complete Rings
 */
get_header();
include( plugin_dir_path( __FILE__ ) . '/settings/head.php' );
include( plugin_dir_path( __FILE__ ) . '/settings/header.php' );
$ringcookie = $_COOKIE['_wp_ringsetting'];
$ringcookie = json_decode( str_replace( '\"', '"', $ringcookie ), true );
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>
<?php
$noimageurl      = plugins_url( "/assets/images/no-image.jpg", __FILE__ );
$loadingimageurl = plugins_url( "/assets/images/loader-2.gif", __FILE__ );
$setting         = getSelectedRing();
$diamond         = getSelectedDiamond();
$setting['ringData']['currencySymbol'] = ( $setting['ringData']['currencySymbol'] == "US$" ) ? "$" : $setting['ringData']['currencySymbol'];
$ringoptions = getOptions_rb();
// echo '<pre>'; print_r($ringoptions); exit; 
$site_key=$ringoptions['site_key'];

$ringSize = 7;
if ( isset( $setting['selectedData'] ) && $setting['selectedData']['ringsizewithdia'] ) {
  $ringSize = $setting['selectedData']['ringsizewithdia'];
} else {
  $setting['selectedData']['ringsize'] = 7;
}
$ringId = '';
if ( isset( $setting['selectedData'] ) && $setting['selectedData']['setting_id'] ) {
  $ringId = $setting['selectedData']['setting_id'];
}
$diamondId = '';
if ( isset( $diamond['diamondData'] ) && isset( $diamond['diamondData']['diamondId'] ) && $diamond['diamondData']['diamondId'] ) {
  $diamondId = $diamond['diamondData']['diamondId'];
}

?>
<?php if ( $setting && sizeof( $setting['ringData'] ) > 0 ) {

  $settingid = $setting['ringData']['settingId'];
  $hasvideo  = $type = 0;
  if ( isset( $setting['ringData']['videoURL'] ) && $setting['ringData']['videoURL'] != '' ) {
    $headers = is_404_rb( $setting['ringData']['videoURL'] );
    if ( ! $headers ) {
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
  ?>
  <?php if ( isset( $setting['ringData']['mainImageURL'] ) ) {
   $imgurl = $setting['ringData']['mainImageURL'];
 }
 $ringDatawithAdd = getRingById_rb( $setting['ringData']['settingId'],'');
 ?>
 <div class="loading-mask gemfind-loading-mask" id="gemfind-loading-mask">
  <div class="loader gemfind-loader">
    <p><?php _e( 'Please wait...', 'gemfind-diamond-link' ) ?></p>
  </div>
</div>
<div id="search-rings" class="flow-tabs">
   <?php if(!empty($ringoptions['top_textarea'])) {?>
            <div class="tab-content">
               <div class="diamond-bar">
                  <?php echo $ringoptions['top_textarea']; ?>
              </div>
            </div>
   <?php } ?>
  <div class="tab-section">
    <ul class="tab-ul">
      <li class="tab-li">
        <div>
          <?php
          global $wp_query;
          if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {
           $labsettings = '/islabsettings/1/';
         }
         ?>
         <a href="<?php echo site_url().'/ringbuilder/settings' . $labsettings; ?>">
          <span class="tab-title"><?php _e( 'Choose Your', 'gemfind-diamond-link' ); ?><strong><?php _e( ' Setting', 'gemfind-diamond-link' ); ?></strong></span>
          <i class="ring-icon tab-icon"></i>
        </a>
      </div>
    </li>
    <li class="tab-li">
      <div>
        <a href="<?php echo site_url().'/ringbuilder/diamondlink/'; ?>">
          <span class="tab-title"><?php _e( 'Choose Your', 'gemfind-diamond-link' ); ?><strong><?php _e( ' Diamond', 'gemfind-diamond-link' ); ?></strong></span>
          <i class="diamond-icon tab-icon"></i>
        </a>
      </div>
    </li>
    <li class="tab-li active">
      <div>
        <a href="javascript:;">
          <span class="tab-title"><?php _e( 'Review', 'gemfind-diamond-link' ); ?><strong><?php _e( ' Complete Ring', 'gemfind-diamond-link' ); ?></strong></span>
          <i class="finalring-icon tab-icon"></i>
        </a>
      </div>
    </li>
  </ul>
</div>
<div id="SettingModal" class="Rbmodal">
        <!-- Modal content -->
        <div class="Rbmodal-content">

            <span class="Rbclose">&times;</span>
            <div class="loader_rb" style="display: none;">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "assets/images/ring_rb.gif" ?>" style="width: 200px; height: 200px;">
            </div>
            <iframe src="" id="setting_iframevideo" scrolling="no" style="width:100%; height:98%;" allow="autoplay"> </iframe>

            <video style="width:100%; height:90%;" id="setting_mp4video" loop autoplay>
                <source src=""  type="video/mp4">
            </video>
        </div>
    </div>
<div class="tab-content">
  <div class="d-container">
    <div class="d-row">
      <div class="diamonds-preview no-padding">
        <div class="diamond-info">
          <div class="diamond-image">
            <div class="top-icons">
              <span class="zoom-icon" id="zoom_me">
               <svg
               xmlns="http://www.w3.org/2000/svg"
               xmlns:xlink="http://www.w3.org/1999/xlink"
               width="22px" height="22px">
               <path fill-rule="evenodd" fill="rgb(148, 148, 148)"
               d="M22.001,20.308 C22.001,20.775 21.835,21.174 21.505,21.505 C21.174,21.835 20.775,22.001 20.308,22.001 C19.832,22.001 19.436,21.833 19.118,21.498 L14.583,16.976 C13.006,18.069 11.247,18.616 9.308,18.616 C8.047,18.616 6.842,18.371 5.691,17.882 C4.541,17.393 3.549,16.732 2.716,15.899 C1.883,15.066 1.222,14.074 0.733,12.924 C0.244,11.773 -0.001,10.568 -0.001,9.308 C-0.001,8.047 0.244,6.842 0.733,5.692 C1.222,4.541 1.883,3.550 2.716,2.717 C3.549,1.884 4.541,1.222 5.691,0.733 C6.842,0.244 8.047,-0.001 9.308,-0.001 C10.568,-0.001 11.774,0.244 12.924,0.733 C14.075,1.222 15.066,1.884 15.899,2.717 C16.732,3.550 17.393,4.541 17.882,5.692 C18.371,6.842 18.616,8.047 18.616,9.308 C18.616,11.247 18.070,13.006 16.977,14.583 L21.511,19.119 C21.838,19.445 22.001,19.841 22.001,20.308 ZM13.493,5.123 C12.333,3.964 10.938,3.384 9.308,3.384 C7.677,3.384 6.282,3.964 5.123,5.123 C3.964,6.282 3.384,7.677 3.384,9.308 C3.384,10.938 3.964,12.333 5.123,13.492 C6.282,14.651 7.677,15.231 9.308,15.231 C10.938,15.231 12.333,14.652 13.493,13.492 C14.652,12.333 15.231,10.938 15.231,9.308 C15.231,7.677 14.652,6.282 13.493,5.123 ZM13.116,10.154 L10.154,10.154 L10.154,13.116 C10.154,13.230 10.112,13.330 10.028,13.413 C9.945,13.497 9.846,13.539 9.731,13.539 L8.885,13.539 C8.770,13.539 8.671,13.497 8.587,13.413 C8.504,13.330 8.462,13.230 8.462,13.116 L8.462,10.154 L5.500,10.154 C5.385,10.154 5.286,10.112 5.202,10.028 C5.119,9.944 5.077,9.845 5.077,9.731 L5.077,8.884 C5.077,8.770 5.119,8.671 5.202,8.587 C5.286,8.503 5.385,8.461 5.500,8.461 L8.462,8.461 L8.462,5.500 C8.462,5.385 8.504,5.286 8.587,5.202 C8.671,5.118 8.770,5.077 8.885,5.077 L9.731,5.077 C9.846,5.077 9.945,5.118 10.028,5.202 C10.112,5.286 10.154,5.385 10.154,5.500 L10.154,8.461 L13.116,8.461 C13.231,8.461 13.330,8.503 13.414,8.587 C13.497,8.671 13.539,8.770 13.539,8.884 L13.539,9.731 C13.539,9.845 13.497,9.944 13.414,10.028 C13.330,10.112 13.231,10.154 13.116,10.154 Z"/>
             </svg>
           </span>
           <?php if ( $hasvideo ) { ?>
             <a href="javascript:;" class="videoicon" data-id="<?php echo $setting['ringData']['settingId']; ?>" onclick="Videorun()">
             <svg
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="24px" height="21px">
             <path fill-rule="evenodd" fill="rgb(148, 148, 148)"
             d="M23.224,21.000 C22.545,21.000 18.182,16.957 18.182,16.425 L18.182,11.128 C18.182,10.596 22.448,6.553 23.224,6.553 C24.000,6.553 24.000,7.275 24.000,7.275 L24.000,20.278 C24.000,20.278 23.903,21.000 23.224,21.000 ZM16.291,19.917 L1.164,19.917 C0.521,19.917 -0.000,19.550 -0.000,19.098 L-0.000,8.455 C-0.000,8.003 0.521,7.636 1.164,7.636 L16.291,7.636 C16.934,7.636 17.454,8.003 17.454,8.455 L17.454,19.098 C17.454,19.550 16.934,19.917 16.291,19.917 ZM12.364,7.223 C10.355,7.223 8.727,5.606 8.727,3.612 C8.727,1.617 10.355,-0.000 12.364,-0.000 C14.372,-0.000 16.000,1.617 16.000,3.612 C16.000,5.606 14.372,7.223 12.364,7.223 ZM4.364,7.223 C2.757,7.223 1.454,5.930 1.454,4.334 C1.454,2.738 2.757,1.444 4.364,1.444 C5.970,1.444 7.273,2.738 7.273,4.334 C7.273,5.930 5.970,7.223 4.364,7.223 Z"/>
           </a>
         </span>
         <?php } ?>
       </div>
        <div class="diamondimg"
        id="ringimg">
        <img src="<?php echo $imgurl; ?>" id="diamondmainimage"
        data-src="<?php echo $imgurl; ?>"
        alt="<?php echo $setting['ringData']['settingName'] ?>"
        title="<?php echo $setting['ringData']['settingName'] ?>">
      </div>
      <h2><?php _e( 'SKU#', 'gemfind-diamond-link' ) ?>
        <span><?php echo $setting['ringData']['styleNumber'] ?></span></h2>
      </div>
      <div class="product-thumb">
        <div class="thumg-img diamention">
         <?php if ( isset( $setting['ringData']['mainImageURL'] ) ) { ?>
         <a href="javascript:;" onclick="Imageswitch1(event);" id="main_image">
          <img src="<?php echo $setting['ringData']['mainImageURL']; ?>"
          data-src="<?php echo $setting['ringData']['mainImageURL'] ?>"
          style="width:40px; height: 40px;"
          alt="<?php echo $setting['ringData']['settingName'] ?>"
          title="<?php echo $setting['ringData']['settingName'] ?>"
          class="thumbimg"
          id="thumbimg1"/>
        </a>
        <?php } ?>
        <?php $i = 2;
        foreach ( $setting['ringData']['extraImage'] as $thumbimage ) { ?>
        <?php if ( is_404_rb( $thumbimage ) ) ?>
        <a href="javascript:;"  onclick="Imageswitch2('thumbimg<?php echo $i; ?>');">
          <img src="<?php echo $thumbimage; ?>" data-src="<?php echo $thumbimage; ?>"
          style="width:40px; height: 40px;"
          alt="<?php echo $setting['ringData']['settingName'] ?>"
          title="<?php echo $setting['ringData']['settingName'] ?>" class="thumbimg"
          id="thumbimg<?php echo $i; ?>"/>
        </a>
        <?php $i ++;
      } ?>
    </div>
  </div>
  <div style="display: none;" id="hidden-content">
    <img src="<?php echo $setting['ringData']['mainImageURL'] ?>"
    alt="<?php echo $setting['ringData']['settingName'] ?>"
    title="<?php echo $setting['ringData']['settingName'] ?>"/>
  </div>
</div>
<div class="diamond-content-data">
  <div class="form-field diamonds-info">
    <p class="imagenote">
      <span><?php _e( 'NOTE:', 'gemfind-diamond-link' ); ?></span><?php _e( ' All metal color images may not be available.', 'gemfind-diamond-link' ); ?>
    </p>
  </div>
</div>
</div>
<div class="ring-details no-padding ring-request-form">
  <div class="ring-data" id="ring-data">
    <div class="ring-specification-title">
      <h2><?php echo $setting['ringData']['settingName'] ?></h2>
      <h4 class="spec-icon ring_spec_container">
        <span class="ring_spec" onclick="CallSpecification();">Ring Specification</span>
        <a href="javascript:;" id="spcfctn" onclick="CallSpecification();" title="Specification">
          <svg version="1.1" data-toggle="tooltip" data-placement="bottom"
          title="Specification" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
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
   <div class="form-field diamonds-info">
    <div class="diamond-content-data" id="ring-content-data">
      <div class="diamond-desc">
        <p><?php echo $setting['ringData']['description'] ?></p>
      </div>
      <div class="intro-field">            
        
        <?php if ( isset( $setting['ringData']['metalType'] ) ) { ?>
        <div class="metaltypemaindiv">
          <span class="title metaltypelabel"><?php _e( 'Metal Type', 'gemfind-diamond-link' ); ?></span>
          <span class="metaltypedata"><?php echo $setting['ringData']['metalType']; ?></span>
        </div>
        <?php } ?>
        <?php if ( isset( $setting['ringData']['sideStoneQuality'][0] ) ) { ?>
        <div class="metaltypemaindiv">
          <span class="title sidestonequalitylabel metaltypelabel"><?php _e( 'Side Stone Quality', 'gemfind-diamond-link' ); ?></span>
          <span class="sidestonequalitydata metaltypedata"><?php echo $setting['ringData']['sideStoneQuality'][0]; ?></span>
        </div>
        <?php } ?>
        <?php if ( isset( $setting['selectedData']['ringmincarat'] ) && isset( $setting['selectedData']['ringmaxcarat'] ) ) { ?>
        <div class="metaltypemaindiv">
          <span class="title sidestonequalitylabel"><?php _e( 'Center Stone Size (Ct.)', 'gemfind-diamond-link' ); ?></span>
          <span class="metaltypedata"><?php echo $setting['selectedData']['ringmincarat'] . ' - ' . $setting['selectedData']['ringmaxcarat']; ?></span>
        </div>
        <?php } ?>
        <?php if ( isset( $ringSize ) ) { ?>
        <div class="ring-size metaltypemaindiv">
          <span class="title metaltypelabel"><?php _e( 'Ring Size', 'gemfind-diamond-link' ); ?></span>
          <span class="metaltypedata"><?php echo $ringSize; ?></span>
        </div>
        <?php } ?>
      </div>
      <div class="ringprice">
        <?php
         $rprice = $setting['ringData']['cost'];
         $rprice = str_replace(',', '', $rprice);
        ?>
        <span>
          <?php _e( 'Setting Price: ', 'gemfind-diamond-link' );
            if($setting['ringData']['showPrice'] == true) {
              if($ringoptions['price_row_format'] == 'left'){

                if($setting['ringData']['currencyFrom'] == 'USD'){
                
                    echo "$".number_format( $rprice ); 
                
                }else{
                
                    echo number_format( $rprice ) .' '.$setting['ringData']['currencySymbol'].' '.$setting['ringData']['currencyFrom'];
                
                }
                
              }else{
                
                  if($setting['ringData']['currencyFrom'] == 'USD'){
                
                    echo "$".number_format( $rprice ); 
                
                  }else{
                
                    echo $setting['ringData']['currencyFrom'].' '.$setting['ringData']['currencySymbol'].' '.number_format( $rprice );   
                
                  }
                
              }
            }else{
                _e( 'Call For Price', 'gemfind-ring-builder' );
            } 
            ?>
         </span>
      </div>
    </div>
    <div class="diamond-content-data" id="diamond-content-data">
      <?php
      if ( $setting['ringData']['centerStoneMinCarat'] > '0.00' ) {
       $TempCaratMin = ( $setting['ringData']['centerStoneMinCarat'] * 10 ) / 100;
       $CaratMin     = ( $setting['ringData']['centerStoneMinCarat'] - $TempCaratMin );
     } else {
       $CaratMin = $diamond['diamondData']['caratWeight'];
     }
     if ( $setting['ringData']['centerStoneMaxCarat'] > '0.00' ) {
       $TempCaratMax = ( $setting['ringData']['centerStoneMaxCarat'] * 10 ) / 100;
       $CaratMax     = ( $setting['ringData']['centerStoneMaxCarat'] + $TempCaratMax );
     } else {
       $CaratMax = $setting['ringData']['centerStoneMaxCarat'];
     }     
     if ( $diamond['diamondData']['caratWeight'] > $CaratMax || $diamond['diamondData']['caratWeight'] < $CaratMin ) { ?>     
    <?php } ?>
    
    <div class="diamond-data" id="diamond-data">
                    <div class="ring-specification-title dia-specification-title"> 
                        
                          <h2><?php echo $diamond['diamondData']['mainHeader'] ?></h2>
                          <h4 class="spec-icon diamond_spec_container" onclick="CallSpecificationdb();">
                            <span class="diamond_spec">Diamond Specification</span>
                            <a href="javascript:;" id="spcfctnd" onclick="CallSpecificationdb();" title="Diamond Specification">
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
           </div>
    <div class="diamond-sku"><span
      class="diamond-sku"><?php _e( 'SKU#', 'gemfind-diamond-link' ) ?></span><?php echo $diamond['diamondData']['diamondId'] ?>
    </div>
    <div class="diamond-desc">
      <p><?php echo $diamond['diamondData']['subHeader'] ?></p>
    </div>
    <div class="intro-field">
      <ul>
        <li>
          <strong><?php _e( 'Report:', 'gemfind-diamond-link' ) ?></strong>
          <p>
            <?php if ( $diamond['diamondData']['certificate'] != '' ) {
             echo $diamond['diamondData']['certificate'];
           } else {
             _e( 'NA', 'gemfind-diamond-link' );
           } ?>
         </p>
       </li>
       <li>
        <strong><?php _e( 'Cut:', 'gemfind-diamond-link' ) ?></strong>
        <p>
          <?php if ( $diamond['diamondData']['cut'] != '' ) {
           echo $diamond['diamondData']['cut'];
         } else {
           _e( 'NA', 'gemfind-diamond-link' );
         } ?>
       </p>
     </li>
   </ul>
   <ul>
    <li>
      <strong><?php _e( 'Color:', 'gemfind-diamond-link' ) ?></strong>
      <p>
        <?php 
        if ( $diamond['diamondData']['fancyColorMainBody'] ) {
          $color_to_display = $diamond['diamondData']['fancyColorIntensity'].' '.$diamond['diamondData']['fancyColorMainBody'];
        } else {
          $color_to_display = $diamond['diamondData']['color']; 
        }
        if ( $color_to_display ) {
         echo $color_to_display;
       } else {
         _e( 'NA', 'gemfind-diamond-link' );
       } ?>
     </p>
   </li>
   <li>
    <strong><?php _e( 'Clarity:', 'gemfind-diamond-link' ) ?></strong>
    <p>
      <?php if ( $diamond['diamondData']['clarity'] != '' ) {
       echo $diamond['diamondData']['clarity'];
     } else {
       _e( 'NA', 'gemfind-diamond-link' );
     } ?>
   </p>
 </li>
</ul>
</div>
<div class="certificate-image">
  <?php 
    $dprice = $diamond['diamondData']['fltPrice'];
    $dprice = str_replace(',', '', $dprice);
  ?>
  <span class="diamondprice">
    <?php _e( 'Diamond Price: ', 'gemfind-diamond-link' ) ?>
    <?php if ($diamond['diamondData']['showPrice'] == true) { ?>
        <?php if($ringoptions['price_row_format'] == 'left'){

          if($diamond['diamondData']['currencyFrom'] == 'USD'){

          echo "$".number_format( $dprice ); 

          }else{

            echo number_format( $dprice ) .' '.$diamond['diamondData']['currencySymbol'].' '.$diamond['diamondData']['currencyFrom'];

        }

        }else{

        if($diamond['diamondData']['currencyFrom'] == 'USD'){

          echo "$".number_format( $dprice ); 

        }else{

          echo $diamond['diamondData']['currencyFrom'].' '.$diamond['diamondData']['currencySymbol'].' '.number_format( $dprice );   

        }

      }
    } else {
        echo 'Call For Price';
    } ?>
    </span>
</div>
<div class="product-controler">
  <ul>
    <?php /* if ( $ringoptions['enable_hint'] == 'true' ): ?>
      <li>
        <a href="javascript:;" class="showForm"
        onclick="CallShowform(event);"
        data-target="drop-hint-main"><?php _e( 'Drop A Hint', 'gemfind-diamond-link' ); ?>
      </a>
    </li>
  <?php endif; */ ?>
  <?php if ( $ringoptions['enable_more_info'] == 'true' ): ?>
    <li>
      <a href="javascript:;" class="showForm"
      onclick="CallShowform(event);"
      data-target="req-info-main-cr"><?php _e( 'Request More Info', 'gemfind-diamond-link' ); ?>
    </a>
  </li>
<?php endif; ?>
<?php /* if ( $ringoptions['enable_email_friend'] == 'true' ): ?>
  <li>
    <a href="javascript:;" class="showForm"
    onclick="CallShowform(event);"
    data-target="email-friend-main"><?php _e( 'E-Mail A Friend', 'gemfind-diamond-link' ); ?>
  </a>
</li>
<?php endif;  */ ?>
<?php if ( $ringoptions['enable_schedule_viewing'] == 'true' ): ?>
  <li style="display: none;"><?php _e( 'Print Details', 'gemfind-diamond-link' ) ?></li>
  <li>
    <a href="javascript:;" class="showForm"
    onclick="CallShowform(event);"
    data-target="schedule-view-main-cr"><?php _e( 'Schedule Viewing', 'gemfind-diamond-link' ); ?>
  </a>
</li>
<?php endif; ?>
</ul>
</div>
<div class="diamond-action">
  <?php 
    $rprice = $setting['ringData']['cost'];
    $rprice = str_replace(',', '', $rprice);

    $dprice = $diamond['diamondData']['fltPrice'];
    $dprice = str_replace(',', '', $dprice);
  ?>
  <?php if( $diamond['diamondData']['showPrice'] == true ) {
    ?>
    <span>
      <?php if($ringoptions['price_row_format'] == 'left'){

          if($setting['ringData']['currencyFrom'] == 'USD'){

	        echo "$".number_format( ( $rprice + $dprice ) ); 

        }else{

	        echo number_format( ( $rprice + $dprice ) ).' '.$setting['ringData']['currencySymbol'].' '.$setting['ringData']['currencyFrom'];

        }

      }else{

        if($setting['ringData']['currencyFrom'] == 'USD'){

          echo "$".number_format( ( $rprice + $dprice ) ); 

          }else{

          echo $setting['ringData']['currencyFrom'].' '.$setting['ringData']['currencySymbol'].' '.number_format( ( $rprice + $dprice ) );   

        }

      }?></span>
    <?php
  } else {
    ?>
    <span><?php _e( 'Call For Price', 'gemfind-ring-builder' ); ?></span>
    <?php } ?>                                            
    <form action=""
    method="post" id="product_addtocart_form">
    <input type="hidden" name="ringId" id="ringId"
    value="<?php echo $ringId; ?>"/>
    <input type="hidden" name="ringsizesettingonly" id="ringsizesettingonly"
    value="<?php echo $ringSize; ?>"/>
    <input type="hidden" name="diamondId" id="diamondId"
    value="<?php echo $diamondId; ?>"/>
    <input type="hidden" name="action" id="completepurchase_rb"
    value="completepurchase_rb"/>
    <div class="box-tocart">
      <?php if( $diamond['diamondData']['showPrice'] == true ) { 
        if( $diamond['diamondData']['dsEcommerce'] != false ) {
          ?>
          <button type="submit" title="Add To Cart" class="addtocart tocart addPair"
          id="product_addtocart_button"><?php _e( 'Add To Cart', 'gemfind-diamond-link' ) ?>
        </button>
        <?php
      }
    }
    ?>
     <!--Tryon button-->
   <?php
   //If enabled in Admin Setting then only show
   if (isset($ringoptions['enable_tryon']) && $ringoptions['enable_tryon'] == 'yes' && $ringDatawithAdd['ringData']['tryon'] == true) {
      $styleNumber = explode("-", trim($setting['ringData']['styleNumber'])); ?>
      <a title="Tryon" href="https://cdn.camweara.com/gemfind/index_client.php?company_name=Gemfind&ringbuilder=1&skus=<?php echo $styleNumber[0]; ?>&buynow=0" class="tryonbtn fancybox fancybox.iframe" data-fancybox-type="iframe" id="tryon"><?php echo 'Virtual Try On' ?></a>
   <?php } ?>
  </div>
</form>
</div>
</div>
</div>
<div class="diamond-forms">
 <?php
 if ( $setting['ringData']['metalType'] ) {
  $metaltype = strtolower( str_replace( ' ', '-', $setting['ringData']['metalType'] ) ) . '-metaltype-';
} else {
  $metaltype = '';
}
$name    = strtolower( str_replace( ' ', '-', $setting['ringData']['settingName'] ) );
$sku     = '-sku-' . str_replace( ' ', '-', $setting['ringData']['settingId'] );
$ringurl = $metaltype . $name . $sku;
?>
<?php if ( $ringoptions['enable_hint'] == 'true' ): ?>
  <div class="form-main no-padding diamond-request-form cls-for-hide"
  id="drop-hint-main">
  <div class="requested-form">
    <h2><?php _e( 'Drop A Hint', 'gemfind-diamond-link' ); ?></h2>
    <p><?php _e( 'Because you deserve this.', 'gemfind-diamond-link' ); ?></p>
  </div>
  <div class="note" style="display: none;"></div>
  <form method="post" enctype="multipart/form-data"
  data-hasrequired="<?php /* @escapeNotVerified */
  _e( '* Required Fields', 'gemfind-diamond-link' ) ?>"
  data-mage-init='{"validation":{}}' class="form-drop-hint"
  id="form-drop-hint">
  <input name="ringurl" type="hidden"
  value="<?php echo site_url() . '/ringbuilder/settings/product/' . $ringurl; ?>">
  <input name="settingid" type="hidden"
  value="<?php echo $setting['ringData']['settingId']; ?>">
  <input type="hidden" name="diamondid" value="<?php echo $diamondId; ?>">
  <input name="complateRingpage" type="hidden" value="Yes">
  <input type="hidden" name="diamondid" value="<?php echo $diamondId; ?>">
  <div class="form-field">
    <label>
      <input name="name" id="drophint_name"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" type="text" class=""
      data-validate="{required:true}" placeholder=" ">
      <span><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="email" id="drophint_email"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" type="email" class=""
      data-validate="{required:true, 'validate-email':true}"
      placeholder=" ">
      <span><?php _e( 'Your E-mail', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="recipient_name" id="drophint_rec_name"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" type="text" class=""
      data-validate="{required:true}" placeholder=" ">
      <span><?php _e( 'Hint Recipient\'s Name', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="recipient_email" id="drophint_rec_email"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" type="email" class=""
      data-validate="{required:true, 'validate-email':true}"
      placeholder=" ">
      <span><?php _e( 'Hint Recipient\'s E-mail', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="gift_reason" id="gift_reason"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" type="text" class=""
      data-validate="{required:true}" placeholder=" ">
      <span><?php _e( 'Reason For This Gift', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <textarea name="hint_message" rows="2" cols="20"
      id="drophint_message" class=""
      data-validate="{required:true}"
      placeholder="Add A Personal Message Here ..."></textarea>
    </label>
    <label>
      <div class="has-datepicker--icon">
        <input name="gift_deadline" id="gift_deadline"
        autocomplete="false" readonly title="Gift Deadline"
        value="" type="text" data-validate="{required:true}"
        placeholder="Gift Deadline">
      </div>
    </label>
    <div class="prefrence-action" style="margin-bottom: 10px;">
      <div class=" prefrence-action action">
        <button type="button" data-target="drop-hint-main"
        onclick="Closeform(event);"
        class="cancel preference-btn btn-cencel">
        <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span></button>
        <button type="submit"
        onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-drop-hint')"
        title="<?php _e( 'Drop Hint', 'gemfind-diamond-link' ); ?>"
        class="preference-btn">
        <span><?php _e( 'Drop Hint', 'gemfind-diamond-link' ); ?></span>
      </button>
    </div>
  </div>
</div>
</form>
</div>
<?php endif; ?>
<?php if ( $ringoptions['enable_email_friend'] == 'true' ): ?>
  <div class="form-main no-padding diamond-request-form cls-for-hide"
  id="email-friend-main">
  <div class="requested-form"><h2><?php _e( 'E-Mail A Friend', 'gemfind-diamond-link' ); ?></h2>
  </div>
  <div class="note" style="display: none;"></div>
  <form method="post" enctype="multipart/form-data"
  data-hasrequired="<?php /* @escapeNotVerified */
  _e( '* Required Fields', 'gemfind-diamond-link' ) ?>"
  data-mage-init='{"validation":{}}' class="form-email-friend"
  id="form-email-friend">
  <input name="ringurl" type="hidden"
  value="<?php echo site_url() . '/ringbuilder/settings/product/' . $ringurl; ?>">
  <input name="settingid" type="hidden"
  value="<?php echo $setting['ringData']['settingId']; ?>">
  <input type="hidden" name="diamondid" value="<?php echo $diamondId; ?>">
  <input name="complateRingpage" type="hidden" value="Yes">
  <div class="form-field">
    <label>
      <input id="email_frnd_name" name="name"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" placeholder=""
      type="text" class="" data-validate="{required:true}">
      <span for="email_frnd_name"><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="email" type="email" onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" placeholder=""
      id="email_frnd_email" class=""
      data-validate="{required:true}">
      <span><?php _e( 'Your E-mail', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="friend_name" type="text"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" placeholder=""
      id="email_frnd_fname" class=""
      data-validate="{required:true}">
      <span><?php _e( 'Your Friend\'s Name', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="friend_email" type="email"
      onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" placeholder=""
      id="email_frnd_femail" class=""
      data-validate="{required:true}">
      <span><?php _e( 'Your Friend\'s E-mail', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <textarea name="message" rows="2"
      placeholder="Add A Personal Message Here ..." cols="20"
      id="email_frnd_message" class=""
      data-validate="{required:true}"></textarea>
    </label>
    <div class="prefrence-action" style="margin-bottom: 10px;">
      <div class=" prefrence-action action">
        <button type="button" data-target="email-friend-main"
        onclick="Closeform(event);"
        class="cancel preference-btn btn-cencel">
        <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span></button>
        <button type="submit"
        onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-email-friend')"
        title="<?php _e( 'Send To Friend', 'gemfind-diamond-link' ); ?>"
        class="preference-btn">
        <span><?php _e( 'Send To Friend', 'gemfind-diamond-link' ); ?></span>
      </button>
    </div>
  </div>
</div>
</form>
</div>
<?php endif; ?>
<?php if ( $ringoptions['enable_more_info'] == 'true' ): ?>
  <div class="form-main no-padding diamond-request-form cls-for-hide"
  id="req-info-main-cr">
  <div class="requested-form">
    <h2><?php _e( 'Request More Information', 'gemfind-diamond-link' ); ?></h2>
    <p><?php _e( 'Our specialists will contact you.', 'gemfind-diamond-link' ); ?></p>
  </div>
  <div class="note" style="display: none;"></div>
  <form method="post" enctype="multipart/form-data"
  data-hasrequired="<?php /* @escapeNotVerified */
  _e( '* Required Fields', 'gemfind-diamond-link' ) ?>"
  data-mage-init='{"validation":{}}' class="form-request-info"
  id="form-request-info-cr">
  <input name="ringSize" type="hidden" value="<?php echo $setting['ringData']['ringSize'][0]; ?>" id="ringSize" class="ringSize">
  <input name="ringurl" type="hidden"
  value="<?php echo site_url() . '/ringbuilder/settings/product/' . $ringurl; ?>">
  <input name="settingid" type="hidden"
  value="<?php echo $setting['ringData']['settingId']; ?>">
  <input type="hidden" name="diamondid" value="<?php echo $diamondId; ?>">
  <input name="complateRingpage" type="hidden" value="Yes">
  <div class="form-field">
    <label>
      <input name="name" type="text" onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" id="reqinfo_name"
      placeholder="" class="" data-validate="{required:true}">
      <span><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="email" type="email" onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" id="reqinfo_email"
      placeholder="" class="" data-validate="{required:true}">
      <span><?php _e( 'Your E-mail Address', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="phone" type="text" onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" id="reqinfo_phone"
      placeholder="" class="" data-validate="{required:true}">
      <span><?php _e( 'Your Phone Number', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <textarea name="hint_message" rows="2" cols="20"
      placeholder="<?php echo _e( 'Add A Personal Message Here ...', 'gemfind-diamond-link' ); ?>"
      id="reqinfo_message" class=""
      data-validate="{required:true}"></textarea>
    </label>
    <div class="prefrence-area">
      <p><?php _e( 'Contact Preference:', 'gemfind-diamond-link' ); ?></p>
      <ul class="pref_container">
        <li>
          <input type="radio"
          data-validate="{'validate-one-required-by-name':true}"
          class="radio required-entry" name="contact_pref"
          value="By Email">
          <label><?php _e( 'By Email', 'gemfind-diamond-link' ); ?></label>
        </li>
        <li>
          <input type="radio"
          data-validate="{'validate-one-required-by-name':true}"
          class="radio required-entry" name="contact_pref"
          value="By Phone">
          <label><?php _e( 'By Phone', 'gemfind-diamond-link' ); ?></label>
        </li>
      </ul>
      <div class="prefrence-action" style="margin-bottom: 10px;">
        <div class=" prefrence-action action">
          <button type="button" data-target="req-info-main-cr"
          onclick="Closeform(event);"
          class="cancel preference-btn btn-cencel">
          <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span>
        </button>
        <button type="submit"
        onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-request-info-cr')"
        title="<?php _e( 'Request', 'gemfind-diamond-link' ); ?>"
        class="preference-btn">
        <span><?php _e( 'Request', 'gemfind-diamond-link' ); ?></span>
      </button>
    </div>
  </div>
</div>
</div>
</form>
</div>
<?php endif; ?>
<?php if ( $ringoptions['enable_schedule_viewing'] == 'true' ): ?>
  <div class="form-main no-padding diamond-request-form cls-for-hide"
  id="schedule-view-main-cr">
  <div class="requested-form">
    <h2><?php _e( 'Schedule A Viewing', 'gemfind-diamond-link' ); ?></h2>
    <p><?php _e( 'See This Item & More In Our Store', 'gemfind-diamond-link' ); ?></p>
  </div>
  <div class="note" style="display: none;"></div>
  <form method="post" enctype="multipart/form-data"
  data-hasrequired="<?php /* @escapeNotVerified */
  _e( '* Required Fields', 'gemfind-diamond-link' ) ?>"
  data-mage-init='{"validation":{}}' class="form-schedule-view-cr"
  id="form-schedule-view-cr">
  <input name="ringSize" type="hidden" value="<?php echo $setting['ringData']['ringSize'][0]; ?>" id="ringSize" class="ringSize">
  <input name="ringurl" type="hidden"
  value="<?php echo site_url() . '/ringbuilder/settings/product/' . $ringurl; ?>">
  <input name="settingid" type="hidden"
  value="<?php echo $setting['ringData']['settingId']; ?>">
  <input type="hidden" name="diamondid" value="<?php echo $diamondId; ?>">
  <input name="complateRingpage" type="hidden" value="Yes">
  <div class="form-field">
    <label>
      <input name="name" type="text" onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" id="schview_name"
      placeholder="" class="" data-validate="{required:true}">
      <span><?php _e( 'Your Name', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="email" type="email" onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" id="schview_email"
      placeholder="" class="" data-validate="{required:true}">
      <span><?php _e( 'Your E-mail Address', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <input name="phone" type="text" onfocus="focusFunction(this)"
      onfocusout="focusoutFunction(this)" id="schview_phone"
      placeholder="" class="" data-validate="{required:true}">
      <span><?php _e( 'Your Phone Number', 'gemfind-diamond-link' ); ?></span>
    </label>
    <label>
      <textarea name="hint_message" rows="2" cols="20"
      placeholder="Add A Personal Message Here ..."
      id="schview_message" class=""
      data-validate="{required:true}"></textarea>
    </label>
    <?php     $addressList = $ringDatawithAdd['ringData']['addressList'];           ?>
    <label>
      <select data-validate="{required:true}" name="location" placeholder="" id="schview_loc">
      <option value=""><?php _e( '--Location--', 'gemfind-diamond-link' ); ?></option>      
      <?php foreach ( $addressList as $value ) {
        $value = (array) $value; ?>
        <option data-locationid="<?php echo $value['locationID']; ?>" value="<?php echo $value['locationName']; ?>"><?php echo $value['locationName']; ?></option>
        <?php } ?>
      </select>
    </label>
    <label>
      <div class="has-datepicker--icon">
        <input name="avail_date" id="avail_date" readonly autocomplete="false" placeholder="When are you avilable?" title="When are you avilable?" value="" type="text"
        data-validate="{required:true}">
      </div>
    </label>
<?php
  /*echo "<pre>";
  print_r($ringDatawithAdd['ringData']);
  echo "</pre>";*/ 
  $timingList = $ringDatawithAdd['ringData']['timingList'];            
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
          foreach($dayStatusArr as $key => $value) {  ?>
              <span style="display:none;" class="day_status_arr"><?php echo $value;?></span>
              <?php
          }
          $timing= (Array)$timing;
         ?>
         <span class="timing_days" data-location="<?php  echo $timing['locationID']; ?>" style="display:none;"><?php
         echo json_encode($timingDays);?></span>
  <?php  }  ?>
     <label>
        <select id="appnt_time" class=""  placeholder=""  name="appnt_time" style="display:none;"></select>
     </label>
 <?php } ?>
  <div class="prefrence-action" style="margin-bottom: 10px;">
    <div class=" prefrence-action action">
      <button type="button" data-target="schedule-view-main-cr"
      onclick="Closeform(event);"
      class="cancel preference-btn btn-cencel">
      <span><?php _e( 'Cancel', 'gemfind-diamond-link' ); ?></span></button>
      <button type="submit"
      onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-schedule-view-cr')"
      title="<?php _e( 'Request', 'gemfind-diamond-link' ); ?>"
      class="preference-btn">
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
</div>
<div class="diamond-specification cls-for-hide" id="ring-specification">
  <div class="specification-info">
    <div class="specification-title">
      <h2><?php _e( 'Setting Details', 'gemfind-diamond-link' ); ?></h2>
      <h4>
        <a href="javascript:;" id="dmnddtl" onclick="CallDiamondDetail();">
          <svg version="1.1" data-toggle="tooltip" data-placement="bottom"
          title="Close" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          viewBox="0 0 52 52" width="20px" height="20px"
          style="enable-background:new 0 0 52 52;display:inline;vertical-align:text-bottom;fill:#828282!important"
          xml:space="preserve">
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
   <?php if ( isset( $setting['ringData']['styleNumber'] ) ) { ?>
   <li>
    <div class="diamonds-details-title">
      <p><?php _e( 'Setting Number', 'gemfind-diamond-link' ) ?></p>
    </div>
    <div class="diamonds-info">
      <p><?php echo $setting['ringData']['styleNumber'] ?></p>
    </div>
  </li>
  <?php } ?>
  <?php if ( isset( $setting['ringData']['cost'] ) ) { ?>
  <li>
    <div class="diamonds-details-title">
      <p><?php _e( 'Price', 'gemfind-diamond-link' ) ?></p>
    </div>
    <div class="diamonds-info">
      <p>
        <?php if($setting['ringData']['showPrice'] == true) { ?>
            <?php if($ringoptions['price_row_format'] == 'left'){

              if($setting['ringData']['currencyFrom'] == 'USD'){

              echo "$".number_format( $setting['ringData']['cost'] ); 

            }else{

              echo number_format( $setting['ringData']['cost'] ).' '.$setting['ringData']['currencySymbol'].' '.$setting['ringData']['currencyFrom'];

            }

          }else{

              if($setting['ringData']['currencyFrom'] == 'USD'){

                echo "$".number_format( ( $rprice + $dprice ) ); 

              }else{

                echo $setting['ringData']['currencyFrom'].' '.$setting['ringData']['currencySymbol'].' '.number_format( $setting['ringData']['cost'] );   

              }

          } 
      }else{
        _e( 'Call For Price', 'gemfind-ring-builder' );
      } 
      ?>
        </p>
    </div>
  </li>
  <?php } ?>
  <?php if ( isset( $setting['ringData']['metalType'] ) ) { ?>
  <li>
    <div class="diamonds-details-title">
      <p><?php _e( 'Metal Type', 'gemfind-diamond-link' ) ?></p>
    </div>
    <div class="diamonds-info">
      <p><?php echo $setting['ringData']['metalType'] ?></p>
    </div>
  </li>
  <?php } ?>
</ul>
</div>
<?php if ( $setting['ringData']['sideDiamondDetail']->noOfDiamonds != '' ) { ?>
<div class="specification-info">
  <div class="specification-title">
    <h2><?php _e( 'Side Diamond Details', 'gemfind-diamond-link' ) ?></h2>
  </div>
  <ul>
    <?php if ( $setting['ringData']['sideDiamondDetail']->noOfDiamonds != '' ) { ?>
    <li>
      <div class="diamonds-details-title">
        <p><?php _e( 'Number of Diamonds', 'gemfind-diamond-link' ) ?></p>
      </div>
      <div class="diamonds-info">
        <p><?php echo $setting['ringData']['sideDiamondDetail']->noOfDiamonds ?></p>
      </div>
    </li>
    <?php } ?>
    <?php if ( $setting['ringData']['sideDiamondDetail']->diamondCut != '' ) { ?>
    <li>
      <div class="diamonds-details-title">
        <p><?php _e( 'Cut', 'gemfind-diamond-link' ) ?></p>
      </div>
      <div class="diamonds-info">
        <p><?php echo $setting['ringData']['sideDiamondDetail']->diamondCut; ?></p>
      </div>
    </li>
    <?php } ?>
    <?php if ( $setting['ringData']['sideDiamondDetail']->minimumCaratWeight != '' ) { ?>
    <li>
      <div class="diamonds-details-title">
        <p><?php _e( 'Minimum Carat Weight(ct.tw.)', 'gemfind-diamond-link' ) ?></p>
      </div>
      <div class="diamonds-info">
        <p><?php echo $setting['ringData']['sideDiamondDetail']->minimumCaratWeight; ?></p>
      </div>
    </li>
    <?php } ?>
    <?php if ( $setting['ringData']['sideDiamondDetail']->minimumColor != '' ) { ?>
    <li>
      <div class="diamonds-details-title">
        <p><?php _e( 'Minimum Color', 'gemfind-diamond-link' ) ?></p>
      </div>
      <div class="diamonds-info">
        <p><?php echo $setting['ringData']['sideDiamondDetail']->minimumColor ?></p>
      </div>
    </li>
    <?php } ?>
    <?php if ( $setting['ringData']['sideDiamondDetail']->minimumClarity != '' ) { ?>
    <li>
      <div class="diamonds-details-title">
        <p><?php _e( 'Minimum Clarity', 'gemfind-diamond-link' ) ?></p>
      </div>
      <div class="diamonds-info">
        <p><?php echo $setting['ringData']['sideDiamondDetail']->minimumClarity ?></p>
      </div>
    </li>
    <?php } ?>
  </ul>
</div>
<?php }
if ( $setting['ringData']['centerStoneFit'] != "" ) { ?>
<div class="specification-info">
  <div class="specification-title">
    <h2><?php _e( 'Can Be Set With', 'gemfind-diamond-link' ) ?></h2>
  </div>
  <ul>
    <?php $centerstone = explode( ',', $setting['ringData']['centerStoneFit'] ); ?>
    <?php foreach ( $centerstone as $centerstonesingle ) { ?>
    <li>
      <div class="diamonds-details-title">
        <p><?php echo $centerstonesingle; ?></p>
      </div>
      <div class="diamonds-info">
        <p><?php echo $setting['ringData']['centerStoneMinCarat'] . '-' . $setting['ringData']['centerStoneMaxCarat'] ?></p>
      </div>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
</div>
<div class="diamond-specification-db cls-for-hide" id="diamond-specification-db">
    <div class="specification-info">
        <div class="specification-title">
            <h2><?php _e( 'Diamond Details', 'gemfind-diamond-link' ); ?></h2>
            <h4>
                <a href="javascript:;" id="dmnddtl" onclick="CallDiamondDetaildb();">                                        
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
                <p> <?php if ($diamond['diamondData']['showPrice'] == true) { ?>
                      <?php if($ringoptions['price_row_format'] == 'left'){

                        if($diamond['diamondData']['currencyFrom'] == 'USD'){

                          echo "$".number_format( $dprice ); 

                        }else{

                          echo number_format( $dprice ) .' '.$diamond['diamondData']['currencySymbol'].' '.$diamond['diamondData']['currencyFrom'];

                        }

                      }else{

                      if($diamond['diamondData']['currencyFrom'] == 'USD'){

                        echo "$".number_format( $dprice ); 

                      }else{

                        echo $diamond['diamondData']['currencyFrom'].' '.$diamond['diamondData']['currencySymbol'].' '.number_format( $dprice );   

                      }

                  } 
                    } else {
                      echo 'Call For Price';
                    } ?>
                </p>
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
                    <?php if($ringoptions['price_row_format'] == 'left') { ?>
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
</ul>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>


<script type="text/javascript">
  jQuery(document).ready(function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
  });
  jQuery('div.diamention img').each(function () {
    var src = jQuery(this).attr("data-src");
    var id = jQuery(this).attr("id");
    imageExists1(src, id, function (exists) {
      if (exists) {
        jQuery('#' + id).attr('src', src);
      } else {
        jQuery('#' + id).attr('src', '<?php echo $noimageurl ?>');
      }
    });
  });

  function imageExists1(url, id, callback) {
    var img = new Image();
    img.onload = function () {
      callback(true);
    };
    img.onerror = function () {
      callback(false);
    };
    img.src = url;
  }

  jQuery(document).ready(function () {
    jQuery("#zoom_me").click(function () {
      jQuery.fancybox.open({
        src: '#hidden-content',
        type: 'inline',
        opts: {
          afterShow: function (instance, current) {
            console.info('done!');
          }
        }
      });
    });
  });
  jQuery(window).bind("load", function () {
    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
  });

  jQuery(window).on('beforeunload', function() { jQuery("video").css('visibility', 'hidden'); });
  
</script>
<?php } else {?>
<div class="wrong-msg"><?php _e( 'Something went wrong, please try after some time!', 'gemfind-diamond-link' ); ?></div>
<?php } ?>
<div class="modal fade" id="popup-modal" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p class="note" style="margin: 0; text-align: center;"></p>
      </div>
    </div>
  </div>
</div>
<script>
    function verifyCaptcha(token){
        console.log('success!');
    };

    var onloadCallback = function() {
        jQuery( ".g-recaptcha" ).each(function() {
            grecaptcha.render(jQuery( this ).attr('id'), {
                'sitekey' :  $site_key,
                'callback' : verifyCaptcha
            });
        });
    };
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer ></script>
<?php if(!empty($site_key)) { ?>
  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" data-callback="onSubmit" data-size="invisible"></div>
<?php } ?>
<style type="text/css">
  
.grecaptcha-badge{
      visibility: visible !important;
}
</style>
<script type="text/javascript">
  jQuery('body').addClass('ringbuilder-settings-view page-layout-1column');
  jQuery(".addPair").on('click', function(e) {
    e.preventDefault();      
    var ringId = '<?php echo $ringId; ?>';
    var ringSize = '<?php echo $ringSize; ?>';
    var diamondId = '<?php echo $diamondId; ?>';
    var url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
    var site_url = '<?php echo get_site_url(); ?>';
    jQuery.ajax({
      type: 'POST',
      url: url,
      data: {'action': 'completepurchase_rb', ringId: ringId, ringSize: ringSize, diamondId: diamondId },
      dataType: 'text',
      beforeSend: function() {
        jQuery("#gemfind-loading-mask").show();
      },
      success: function(response) {
        jQuery("#gemfind-loading-mask").hide();        
        jQuery.removeCookie('_wp_diamondsetting', {path: '/'});
        jQuery.removeCookie('_wp_ringsetting', {path: '/'});
        jQuery.removeCookie('wpsavefiltercookie', {path: '/'});
        jQuery.removeCookie('wpsavebackvalue', {path: '/'});
        jQuery.removeCookie('wp_dl_intitialfiltercookielabgrown', {path: '/'});
        jQuery.removeCookie('savefiltercookiefancy', {path: '/'});
        jQuery.removeCookie('savefiltercookielabgrown', {path: '/'});
        jQuery.removeCookie('wpsavefiltercookie', {path: '/'});        
        window.location.replace(site_url + '/cart');
      },
      error: function(response) {
        console.log(JSON.stringify(response));
      }
    });
  });

  //For Tryon button
      jQuery(document).ready(function($) {
         $("a.tryonbtn").fancybox({
            type: "iframe",
            iframe: {
               // Iframe template
               tpl: '<iframe id="tryoniFrameID" allowfullscreen class="fancybox-iframe" scrolling="auto" width="1200" height="800" allow="camera"></iframe>'
            }
         });


         //when click on close
         window.addEventListener('message', function(event) {
            if (~event.origin.indexOf('https://cdn.camweara.com')) {
               if (event.data == "closeIframe") { //Close
                  $.fancybox.close();
               } else if (event.data.includes("buynow")) {
                  $('#product_addtocart_button').click();
               }
            }
         });
      });
</script>
</div>
<?php
  $gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
  if($gemfind_ring_builder['show_copyright']=="yes"){                    ?>
  <div class="copyright-text">
    <p>Powered By <a href="http://www.gemfind.com" target="_blank">GemFind</a></p>
  </div>
  <?php  } ?>
  <?php if(!empty($site_key)) { ?>
  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" data-callback="onSubmit" data-size="invisible"></div>
<?php } ?>


<?php get_footer(); ?>