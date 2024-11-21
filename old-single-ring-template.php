<?php
include( plugin_dir_path( __FILE__ ) . '/settings/head.php' );
include( plugin_dir_path( __FILE__ ) . '/settings/header.php' );
$setting['ringData']['currencySymbol'] = ( $setting['ringData']['currencySymbol'] = "US$" ) ? "$" : $setting['ringData']['currencySymbol'];
// echo '<pre>';print_r($setting); exit; 

if(sizeof($setting['ringData']) > 0) {   
   $diamondCookie = $_COOKIE['_wp_diamondsetting'];
   $diamondCookieData = json_decode(stripslashes( $_COOKIE['_wp_diamondsetting']), 0);
   // echo "<pre>";
   // print_r($diamondCookieData);
   // exit();
   $options = getOptions_rb();  
   // echo '<pre>'; print_r($options); exit; 
   if(isset($setting['ringData']['mainImageURL'])){
      $imgurl = $setting['ringData']['mainImageURL']; 
   }
   $is_lab_settings = $_COOKIE['_islabsettingurl']; 
   //echo($is_lab_settings); 
if(!empty($setting['ringData']['settingName'])){
  $ringDatawithAdd = getRingById_rb( $setting['ringData']['settingId'],'');
  $diamondsoptionapi = get_option( 'gemfind_ring_builder' );
  //print_r($diamondsoptionapi['carat_ranges']);
  $diamondsoption    = sendRequest_dl( array( 'diamondsoptionapi' => $diamondsoptionapi['diamondsoptionapi'] ) );
   $navigationapi = get_option( 'gemfind_ring_builder' );
    $show_hints_popup    =$navigationapi['show_hints_popup'];
    $sticky_header       =$navigationapi['enable_sticky_header'];
    $results = sendRequest_rb( array( 'navigationapirb' => $navigationapi['navigationapirb'] ) );
    // echo "<pre>";
   	// print_r($setting['ringData']['lightBrillianceURL']);
   	 // print_r($setting['ringData']['configurableProduct']);
   	 // exit();
    //print_r($setting['ringData']['centerStoneMaxCarat']);
                              //exit(); 
$site_key=$options['site_key'];
// print_r($options['site_key']);
// exit();
  ?>
   <div class="loading-mask gemfind-loading-mask" id="gemfind-loading-mask" style="display:none">
      <div class="loader gemfind-loader">
         <p>Please wait...</p>
      </div>
   </div>
   <div id="search-rings" class="flow-tabs">
    <?php if(!empty($options['top_textarea'])) {?>
            <div class="tab-content"  style="display:none;">
               <div class="diamond-bar">
                  <?php echo $options['top_textarea']; ?>
              </div>
            </div>
        <?php } ?>

      <div class="tab-section">
         <ul class="tab-ul">
            <?php if($diamondCookie) { ?>
               <li class="tab-li"><div><a href="<?php echo site_url().'/ringbuilder/diamondlink/'; ?>"><span class="tab-title"><?php _e('Choose Your', 'gemfind-ring-builder'); ?><strong><?php _e('Diamond', 'gemfind-ring-builder'); ?></strong></span><i class="diamond-icon tab-icon"></i></a></div></li>
            <?php } else { ?>
               <li class="tab-li active"><div>
                  <?php
                  global $wp_query;
                  if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {    
                   $labsettings = '/islabsettings/1/';
                }
                ?>
                <a href="<?php echo site_url().'/ringbuilder/settings' . $labsettings; ?>"><span class="tab-title"><?php _e('Choose Your', 'gemfind-ring-builder'); ?><strong><?php _e('Setting', 'gemfind-ring-builder'); ?></strong></span><i class="ring-icon tab-icon"></i></a></div></li>
             <?php } ?>
             <?php if(!$diamondCookie) { ?>
               <li class="tab-li"><div><a href="<?php echo site_url().'/ringbuilder/diamondlink/'; ?>"><span class="tab-title"><?php _e('Choose Your', 'gemfind-ring-builder'); ?><strong><?php _e('Diamond', 'gemfind-ring-builder'); ?></strong></span><i class="diamond-icon tab-icon"></i></a></div></li>
            <?php } else { ?>
               <li class="tab-li active"><div>
                  <?php
                  global $wp_query;
                  if ( get_query_var( 'settings' ) && get_query_var( 'settings' ) == 1 ) {    
                   $labsettings = '/islabsettings/1/';
                }
                ?>
                <a href="<?php echo site_url().'/ringbuilder/settings' . $labsettings; ?>"><span class="tab-title"><?php _e('Choose Your', 'gemfind-ring-builder'); ?><strong><?php _e('Setting', 'gemfind-ring-builder'); ?></strong></span><i class="ring-icon tab-icon"></i></a></div></li>
             <?php } ?>
             <li class="tab-li"><div><a href="javascript:"><span class="tab-title"><?php _e('Review', 'gemfind-ring-builder'); ?><strong><?php _e('Complete Ring', 'gemfind-ring-builder'); ?></strong></span><i class="finalring-icon tab-icon"></i></a></div></li>
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
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="22px">
                                 <path fill-rule="evenodd" fill="rgb(148, 148, 148)" d="M22.001,20.308 C22.001,20.775 21.835,21.174 21.505,21.505 C21.174,21.835 20.775,22.001 20.308,22.001 C19.832,22.001 19.436,21.833 19.118,21.498 L14.583,16.976 C13.006,18.069 11.247,18.616 9.308,18.616 C8.047,18.616 6.842,18.371 5.691,17.882 C4.541,17.393 3.549,16.732 2.716,15.899 C1.883,15.066 1.222,14.074 0.733,12.924 C0.244,11.773 -0.001,10.568 -0.001,9.308 C-0.001,8.047 0.244,6.842 0.733,5.692 C1.222,4.541 1.883,3.550 2.716,2.717 C3.549,1.884 4.541,1.222 5.691,0.733 C6.842,0.244 8.047,-0.001 9.308,-0.001 C10.568,-0.001 11.774,0.244 12.924,0.733 C14.075,1.222 15.066,1.884 15.899,2.717 C16.732,3.550 17.393,4.541 17.882,5.692 C18.371,6.842 18.616,8.047 18.616,9.308 C18.616,11.247 18.070,13.006 16.977,14.583 L21.511,19.119 C21.838,19.445 22.001,19.841 22.001,20.308 ZM13.493,5.123 C12.333,3.964 10.938,3.384 9.308,3.384 C7.677,3.384 6.282,3.964 5.123,5.123 C3.964,6.282 3.384,7.677 3.384,9.308 C3.384,10.938 3.964,12.333 5.123,13.492 C6.282,14.651 7.677,15.231 9.308,15.231 C10.938,15.231 12.333,14.652 13.493,13.492 C14.652,12.333 15.231,10.938 15.231,9.308 C15.231,7.677 14.652,6.282 13.493,5.123 ZM13.116,10.154 L10.154,10.154 L10.154,13.116 C10.154,13.230 10.112,13.330 10.028,13.413 C9.945,13.497 9.846,13.539 9.731,13.539 L8.885,13.539 C8.770,13.539 8.671,13.497 8.587,13.413 C8.504,13.330 8.462,13.230 8.462,13.116 L8.462,10.154 L5.500,10.154 C5.385,10.154 5.286,10.112 5.202,10.028 C5.119,9.944 5.077,9.845 5.077,9.731 L5.077,8.884 C5.077,8.770 5.119,8.671 5.202,8.587 C5.286,8.503 5.385,8.461 5.500,8.461 L8.462,8.461 L8.462,5.500 C8.462,5.385 8.504,5.286 8.587,5.202 C8.671,5.118 8.770,5.077 8.885,5.077 L9.731,5.077 C9.846,5.077 9.945,5.118 10.028,5.202 C10.112,5.286 10.154,5.385 10.154,5.500 L10.154,8.461 L13.116,8.461 C13.231,8.461 13.330,8.503 13.414,8.587 C13.497,8.671 13.539,8.770 13.539,8.884 L13.539,9.731 C13.539,9.845 13.497,9.944 13.414,10.028 C13.330,10.112 13.231,10.154 13.116,10.154 Z"/>
                              </svg>
                           </span>
                           <?php if($hasvideo) { ?>
                            <a href="javascript:;" class="videoicon" data-id="<?php echo $setting['ringData']['settingId']; ?>" onclick="Videorun()">
                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="21px">
                                    <path fill-rule="evenodd" fill="rgb(148, 148, 148)" d="M23.224,21.000 C22.545,21.000 18.182,16.957 18.182,16.425 L18.182,11.128 C18.182,10.596 22.448,6.553 23.224,6.553 C24.000,6.553 24.000,7.275 24.000,7.275 L24.000,20.278 C24.000,20.278 23.903,21.000 23.224,21.000 ZM16.291,19.917 L1.164,19.917 C0.521,19.917 -0.000,19.550 -0.000,19.098 L-0.000,8.455 C-0.000,8.003 0.521,7.636 1.164,7.636 L16.291,7.636 C16.934,7.636 17.454,8.003 17.454,8.455 L17.454,19.098 C17.454,19.550 16.934,19.917 16.291,19.917 ZM12.364,7.223 C10.355,7.223 8.727,5.606 8.727,3.612 C8.727,1.617 10.355,-0.000 12.364,-0.000 C14.372,-0.000 16.000,1.617 16.000,3.612 C16.000,5.606 14.372,7.223 12.364,7.223 ZM4.364,7.223 C2.757,7.223 1.454,5.930 1.454,4.334 C1.454,2.738 2.757,1.444 4.364,1.444 C5.970,1.444 7.273,2.738 7.273,4.334 C7.273,5.930 5.970,7.223 4.364,7.223 Z"/>
                                 </svg>
                              </a>
                           <?php } ?>
                        </div>
                           <div class="diamondimg" id="ringimg">
                              <img src="<?php echo $imgurl; ?>" data-src="<?php echo $imgurl; ?>" id="diamondmainimage" alt="<?php echo $setting['ringData']['settingName'] ?>" title="<?php echo $setting['ringData']['settingName'] ?>">
                           </div>
                           <h2><?php _e('SKU#', 'gemfind-ring-builder') ?><span><?php echo $setting['ringData']['styleNumber'] ?></span></h2>
                        </div>
                        <div class="product-thumb">
                           <div class="thumg-img diamention">
                              <?php if(isset($setting['ringData']['mainImageURL'])){ ?>
                                 <a href="javascript:" onclick="Imageswitch1(event)" id="main_image">
                                    <img src="<?php echo $setting['ringData']['mainImageURL'] ?>" data-src="<?php echo $setting['ringData']['mainImageURL'] ?>" style="width:40px;height:40px" alt="<?php echo $setting['ringData']['settingName'] ?>" title="<?php echo $setting['ringData']['settingName'] ?>" class="thumbimg" id="thumbimg1" />
                                 </a>
                                 <div style="display: none;" id="hidden-content">
                                    <img src="<?php echo $setting['ringData']['mainImageURL'] ?>" alt="<?php echo $setting['ringData']['settingName'] ?>" title="<?php echo $setting['ringData']['settingName'] ?>" />
                                 </div>
                              <?php } ?>
                              <?php $i=2; foreach ($setting['ringData']['extraImage'] as $thumbimage) { ?>
                                 <?php if(is_url_404_rb($thumbimage)) ?>
                                 <a href="javascript:" onclick="Imageswitch2('thumbimg<?php echo $i; ?>')">
                                    <img src="<?php echo $thumbimage; ?>" data-src="<?php echo $thumbimage; ?>" style="width:40px;height:40px" alt="<?php echo $setting['ringData']['settingName'] ?>" title="<?php echo $setting['ringData']['settingName'] ?>" class="thumbimg" id="thumbimg<?php echo $i; ?>" />
                                 </a>
                                 <?php $i++;  } ?>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="ring-details no-padding ring-request-form">
                        <div class="ring-data" id="ring-data">
                           <div class="ring-specification-title">
                              <h2><?php echo $setting['ringData']['settingName'] ?></h2>
                              <h4 class="spec-icon ring_spec_container">
                                <span class="ring_spec" onclick="CallSpecification();">Ring Specification</span>
                                 <a href="javascript:" id="spcfctn" onclick="CallSpecification()" title="Specification">        
                                    <svg data-toggle="tooltip" data-placement="bottom" title="Specification" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    width="20px" height="20px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
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
                        <div class="diamond-content-data" id="ring-content-data">
                           <div class="diamond-desc">
                              <p><?php echo $setting['ringData']['description'] ?></p>
                           </div>
                           <div class="form-field diamonds-info">
                              <div class="intro-field">
                                 <?php if(sizeof($setting['ringData']['configurableProduct']) > 0){ 
                                     foreach ($setting['ringData']['configurableProduct'] as $value) {
                                       $value = (array) $value;
                                       $metalarray[] = $value['metalType'];
                                       if( $value['sideStoneQuality'] ) {
                                         $sidestonearray[] = $value['sideStoneQuality'];

                                      }
                                      if( $value['centerStoneSize'] ) {
                                       $centerstonesizearray[] = $value['centerStoneSize'];
                                      }
                                      $sidestn = strtolower(str_replace(' ', '', str_replace('|', '-', $value['sideStoneQuality'])));
                                      $centerstnsize = strtolower(str_replace(' ', '', $value['centerStoneSize']));
                                      $mtltyp = strtolower(str_replace(' ', '-', $value['metalType']));
                                      ?>
                                      <input type="hidden" name="<?php echo $mtltyp.'-metaltype'.'-'.$sidestn; ?>" id="<?php echo $mtltyp.'-metaltype'.'-'.$sidestn; ?>" value="<?php echo strtolower(str_replace(' ', '-', $setting['ringData']['settingName'])).'-sku-'.$value['gfInventoryId']; ?>">
                                   <?php } ?>
                                   <?php if(sizeof(array_unique($metalarray)) >= 1){ 
                                    $metaltypedata = getMetaltype($setting['ringData']['metalType'],$setting['ringData']['configurableProduct']);
                                    ?>
                                    <div class="metal-type prdctdrpdwn"><span class="title"><?php echo 'Metal Type'; ?></span>
                                       <select class="metaltyp-drpdwn" name="metal_type" id="metal_type" onchange="changemetal(this, '<?php echo get_site_url(); ?>' )">
                                          <?php sort($metaltypedata); foreach ($metaltypedata as $singlemetaltype) { ?>
                                             <option <?php if($singlemetaltype['metaltype'] == $setting['ringData']['metalType']){ ?> value="<?php echo $setting['ringData']['settingId']; ?>" <?php } else { ?> value="<?php echo $singlemetaltype['gfid']; ?>" <?php } ?> data-id="<?php echo strtolower(str_replace(' ', '-', $singlemetaltype['metaltype'].'-metaltype-'.$setting['ringData']['settingName'])); ?>" <?php if($singlemetaltype['metaltype'] == $setting['ringData']['metalType']){ echo 'selected="selected"';} ?>><?php echo $singlemetaltype['metaltype']; ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 <?php } ?>                                 
                                 <?php if(!empty($sidestonearray) && sizeof(array_unique($sidestonearray)) >= 1){ 
                                    $sidestonedata = getSidestone($setting['ringData']['metalType'],$setting['ringData']['configurableProduct']);
                                    foreach ($sidestonedata as $key => $value) {
                                       $finalsidestonedata[] = getSidestonefinal($key, $sidestonedata);
                                    }
                                    ?>
                                    <div class="ring-size prdctdrpdwn"><span class="title"><?php echo 'Side Stone Quality'; ?></span>
                                       <select class="stonequality-drpdwn" name="stonequality" id="stonequality" onchange="changequality(this, '<?php echo get_site_url(); ?>')">
                                          <?php foreach ($finalsidestonedata as $singlesideStoneQuality) { 
                                             if(isset($setting['ringData']['sideStoneQuality'][0])){
                                                $currentsettingsidestoneqty = $setting['ringData']['sideStoneQuality'][0];
                                             } else {
                                                $currentsettingsidestoneqty = '';
                                             }
                                             ?>
                                             <option value="<?php echo $singlesideStoneQuality['gfInventoryId'] ?>" <?php if($singlesideStoneQuality['sideStoneQuality'] == $currentsettingsidestoneqty){ echo 'selected="selected"';} ?>><?php echo $singlesideStoneQuality['sideStoneQuality']; ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 <?php } ?>
                                 
                                 
                                <?php if(!empty($centerstonesizearray) && sizeof(array_unique($centerstonesizearray)) >= 1 && !empty($sidestonearray) && sizeof(array_unique($sidestonearray)) >= 1){

                                 $centarstonedata = getCenterstone($setting['ringData']['metalType'],null,$setting['ringData']['configurableProduct']);  ?>
                                    <div class="ring-size prdctdrpdwn"><span class="title"><?php echo 'Center Stone Size (Ct.)'; ?></span>
                                       <select class="centerstonesize-drpdwn" name="selectedcenterstonesize" id="centerstonesize" onchange="changecenterstone(this, '<?php echo get_site_url(); ?>' )">
                                          <?php  
                                          $previousValue = array();
                                          foreach ($centarstonedata as $centkey => $singlecenterstonesizearray) { 
                                              
                                            ?>
                                             <option value="<?php echo $singlecenterstonesizearray['gfInventoryId'] ?>" <?php if($singlecenterstonesizearray['gfInventoryId'] == $setting['ringData']['settingId']){ $centerStoneSize = $singlecenterstonesizearray['centerStoneSize']; echo 'selected="selected"';} ?>><?php echo $singlecenterstonesizearray['centerStoneSize']; ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 <?php } elseif(!empty($centerstonesizearray) && sizeof(array_unique($centerstonesizearray)) >= 1) {                             
                              
                                  $centarstonedata = getCenterstone($setting['ringData']['metalType'],str_replace('"]', '', str_replace('["', '', $setting['ringData']['sideStoneQuality'])),$setting['ringData']['configurableProduct']);                                 ?>
 
                                                                    
                                    <div class="ring-size prdctdrpdwn"><span class="title"><?php echo 'Center Stone Size (Ct.)'; ?></span>
                                       <select class="centerstonesize-drpdwn" name="centerstonesize" id="centerstonesize" onchange="changecenterstone( this, '<?php echo get_site_url(); ?>' )">
                                          <?php  
                                          $previousValue = array();                                          
                                          foreach ($centarstonedata as $centkey => $singlecenterstonesizearray) { ?>                                             
                                            <option value="<?php echo $singlecenterstonesizearray['gfInventoryId'] ?>" <?php if($singlecenterstonesizearray['gfInventoryId'] == $setting['ringData']['settingId']){ $centerStoneSize = $singlecenterstonesizearray['centerStoneSize']; echo 'selected="selected"';} ?>><?php echo $singlecenterstonesizearray['centerStoneSize']; ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 <?php } ?>

                                 

                                 <?php $ringsizearray = array(); if(sizeof($setting['ringData']['ringSize']) > 0){ ?>
                                    <div class="ring-size prdctdrpdwn"><span class="title"><?php _e('Ring Size', 'gemfind-ring-builder'); ?></span>
                                       <select class="ringsize-drpdwn" name="ring_size" id="ring_size" onchange="updatesize(); this.size=1; this.blur();" onfocus="this.size=10;" onblur="this.size=1;" size="1">
                                          <?php foreach ($setting['ringData']['ringSize'] as $ringsize) { 
                                             $ringsizearray[] = $ringsize;
                                          } ?>
                                           <option value="0"><?php echo "Select Ring Size"; ?></option>
                                          <?php foreach (array_unique($ringsizearray) as $singlevalue) { ?>
                                             <option value="<?php echo $singlevalue; ?>"><?php echo $singlevalue; ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 <?php }                                 
                              } ?>
                              <?php if(!isset($diamondCookie)) { ?>
                                <div class="intro-field">
                                     <div class="metal-type prdctdrpdwn"><span class="title"><?php echo 'Center Diamond Type'; ?></span>
                                       <select class="diamondtype-drpdwn" name="metal_type" id="diamond_type" onchange="changediamondtype()">
                                          <?php print_r($results[0]); ?>
                                          <!-- <?php foreach ($results[0] as $key => $value) { ?>
                                          <?php if($value->navMineSetting){ ?>
                                                <option value="<?php echo $value=='Mined Setting' ? 'Mined':'Lab Grown'; ?>"> <?php echo $value=='Mined Setting' ? 'Mined':'Lab Grown'; ?> </option>
                                         <?php } ?>
                                             <option value="<?php echo $value=='Mined Setting' ? 'Mined':'Lab Grown'; ?>"> <?php echo $value=='Mined Setting' ? 'Mined':'Lab Grown'; ?> </option>
                                          <?php } ?> -->
                                          <option value="mined"> Mined </option> 
                                             <option value="labgrown"> LabGrown </option>
                                       </select>
                                    </div>
                                  </div>
                                <?php } ?>
                           </div>
                           <p class="imagenote"><span><?php _e('NOTE:', 'gemfind-ring-builder'); ?></span><?php _e(' All metal color images may not be available.', 'gemfind-ring-builder'); ?></p>
                            <?php if(!empty($options['detail_textarea'])) {?>
                           <div class="diamond-bar-detail">
                                <?php echo $options['detail_textarea']; ?> 
                            </div>
                          <?php } ?>
                           <div class="product-controler">
                              <ul>
                                 <?php if ( $options['enable_hint'] == 'true' ): ?>
                                    <li><a href="javascript:" class="showForm" onclick="CallShowform(event)" data-target="drop-hint-main"><?php _e('Drop A Hint', 'gemfind-ring-builder');?></a></li>
                                 <?php endif; ?>
                                 <?php if ( $options['enable_more_info'] == 'true' ): ?>
                                    <li><a href="javascript:" class="showForm" onclick="CallShowform(event)" data-target="req-info-main"><?php _e('Request More Info', 'gemfind-ring-builder');?></a></li>
                                 <?php endif; ?>
                                 <?php if ( $options['enable_email_friend'] == 'true' ): ?>
                                    <li><a href="javascript:" class="showForm" onclick="CallShowform(event)" data-target="email-friend-main"><?php _e('E-Mail A Friend', 'gemfind-ring-builder');?></a></li>
                                 <?php endif; ?>
                                 <?php if ( $options['enable_schedule_viewing'] == 'true' ): ?>
                                    <li style="display: none;"><?php _e( 'Print Details', 'gemfind-diamond-link' ) ?></li>
                                    <li><a href="javascript:" class="showForm" onclick="CallShowform(event)" data-target="schedule-view-main"><?php _e('Schedule Viewing', 'gemfind-ring-builder');?></a></li>
                                 <?php endif; ?>
                                
                              </ul>
                           </div>                              
                           <?php 
                           if(isset($diamondCookieData[0]->diamondid)){   
                              //  $carat_rang = [];
                              // if(!empty($diamondsoptionapi['carat_ranges'])){
                                // $settings_carat_ranges = stripslashes(trim($diamondsoptionapi['carat_ranges']));
                                // $carat_ranges_vals = json_decode($settings_carat_ranges, true);
                                // $size = $centerStoneSize;
                                // $carat_weight = sprintf('%.2f', $size);
                                // $carat_rang = $carat_ranges_vals["$carat_weight"];

                               $centerstonemincarat = $diamondCookieData[0]->centerstonemincarat;
                               $centerstonemaxcarat = $diamondCookieData[0]->centerstonemaxcarat;

                                // echo "<pre>";
                                // echo($diamondCookieData[0]->centerstonemincarat);
                                // echo($diamondCookieData[0]->centerstonemaxcarat);
                                // exit();
                               
                              //}
                              
                              $min_range = (isset($centerstonemincarat) ? $centerstonemincarat : ($centerStoneSize - 0.1));
                              $max_range = (isset($centerstonemaxcarat) ? $centerstonemaxcarat : ($centerStoneSize + 0.1)); 

                              if($diamondCookieData[0]->carat <  $min_range || $diamondCookieData[0]->carat > $max_range){ ?>
                                  <div><p style="color:red"><?php _e('This ring will not properly fit with selected diamond.', 'gemfind-ring-builder'); ?></p></div> 
                              <?php } }
                              if( isset( $diamondCookieData[0] ) ) {
                                 $redirectURI = '/diamondlink/completering/';
                               } else {
                                if($is_lab_settings == 1){
                                  $redirectURI = '/diamondlink/navlabgrown'; 
                                } else {
                                  $redirectURI = '/diamondlink/';
                                }
                              }  

                              ?>


                              
                              

                              <div class="diamond-action">
                              	<div class="showlb ">
                                 <span class="showprice">
                                    <?php
                                     $rprice = $setting['ringData']['cost'];
                                     $rprice = str_replace(',', '', $rprice);

                                    if( $setting['ringData']['showPrice'] == true && is_numeric($setting['ringData']['cost']) ) {
                                       // echo '<pre>'; print_r($setting); exit; 
                                       if($options['price_row_format'] == 'left'){

                                          if($setting['ringData']['currencyFrom'] == 'USD'){
                                          
                                            echo "$".number_format($rprice); 
                                          
                                          }else{
                                          
                                            echo number_format($rprice).' '.$setting['ringData']['currencySymbol'].' '.$setting['ringData']['currencyFrom'];
                                          
                                          }
                                          
                                       }else{
                                          
                                          if($setting['ringData']['currencyFrom'] == 'USD'){
                                          
                                            echo "$".number_format($rprice); 
                                          
                                          }else{
                                          
                                            echo $setting['ringData']['currencyFrom'].' '.$setting['ringData']['currencySymbol'].' '.number_format($rprice);   
                                          
                                          }
                                          
                                       }
                                    } else {
                                      _e( 'Call For Price', 'gemfind-ring-builder' );
                                    }       ?>
                                 </span>
                                 <?php if ($setting['ringData']['showLightBrillianceURL'] == true) : ?>
                                    <div class="showLightBrillaince">
                                    	<a title="lightBrilliance" href="<?php echo $setting['ringData']['lightBrillianceURL']; ?>" id="lightBrilliance"><?php echo 'Light Performance'; ?></a>
                                   </div>
                                 <?php endif;   ?>
                                 
                                </div>
                                 
                                 <form action="#" method="post" id="product_addtocart_form">
                                    <input type="hidden" name="ringsizesettingonly" id="ringsizesettingonly" value="7" />                                    
                                    <div class="box-tocart">
                                       <?php
                                       if( $setting['ringData']['showBuySettingOnly'] == 'true') {
                                          $showsettings = 'block';
                                       } else {
                                          $showsettings = 'none';
                                       }

                                         if( isset( $product_id ) && !empty( $product_id ) ) {
                                           if( $setting['ringData']['showPrice'] == true ) {
                                            if( $setting['ringData']['rbEcommerce'] != false ) {
                                              ?>
                                                <button type="submit" title="Buy Setting Only" class="addtocart tocart" id="product_addtocart_button" onclick='directRingAddToCart(event, <?php echo $product_id; ?>);' style="display: <?php echo $showsettings; ?>;"><?php _e('Buy Setting Only', 'gemfind-ring-builder')?></button>
                                              <?php
                                            }                                          
                                         }
                                      } else {
                                         if( $setting['ringData']['showPrice'] == true ) {
                                              if( $setting['ringData']['rbEcommerce'] != false ) {
                                                ?>
                                                  <button type="submit" title="Buy Setting Only" class="addtocart tocart" id="product_addtocart_button" onclick='addToCart(event, <?php echo json_encode( $setting ); ?>);' style="display: <?php echo $showsettings; ?>;" ><?php _e('Buy Setting Only', 'gemfind-ring-builder')?></button>
                                                <?php
                                              }                                          
                                         }
                                      }
                                    
                                    ?>
                                 </div>
                              </form>  


                              <?php 
                              if($setting['ringData']['metalType']){
                                 $metaltype = strtolower(str_replace(' ', '-', $setting['ringData']['metalType'])).'-metaltype-';
                              } else {
                                 $metaltype = '';   
                              } 
                              $name = strtolower(str_replace(' ', '-', $setting['ringData']['settingName']));
                              $sku = '-sku-'.str_replace(' ', '-', $setting['ringData']['settingId']);
                              $ringurl = $metaltype.$name.$sku;

                              ?>    
                                                         
                              <form action="<?php echo get_site_url() . '/ringbuilder' . $redirectURI; ?>" method="post" id="add_diamondtoring_form">
                                 <input type="hidden" name="settingId" id="setting_id" value="<?php echo $setting['ringData']['settingId']; ?>"/>
                                 <input type="hidden" name="ringsizewithdia" id="ringsizewithdia" value="7" />
                                 <input type="hidden" name="centerstonesizevalue" id="centerstonesizevalue" value="<?php echo $setting['ringData']['centerStoneMinCarat'].'-'.$setting['ringData']['centerStoneMaxCarat']; ?>">
                                 <input type="hidden" name="ringmaxcarat" id="ringmaxcarat" value="<?php echo $setting['ringData']['centerStoneMaxCarat'] ?>" />
                                 <input type="hidden" name="ringmincarat" id="ringmincarat" value="<?php echo $setting['ringData']['centerStoneMinCarat'] ?>" />
                                 <input type="hidden" name="centerStoneFit" id="centerStoneFit" value="<?php echo $setting['ringData']['centerStoneFit'] ?>" />
                                 <input type="hidden" name="action" value="add_diamondtoring_rb"/>
                                 <input type="hidden" name="islabsettings" id="islabsettings" value="<?php echo $is_lab_settings?>" />
                                 <input type="hidden" name="shopurl" value="<?php echo site_url(); ?>"/>
                                 <div class="box-tocart">
                                    <?php if($diamondCookie) { ?>
                                       <button type="submit" title="Complete Your Ring" onclick='changeRingSize(event)' class="addtocart tocart complete_button" id="add_diamondtoring_button"><?php _e('Complete Your Ring', 'gemfind-ring-builder')?></button>                                 
                                    <?php } else { ?>
                                       <button type="submit" title="Add Your Diamond" onclick='changeRingSize(event)' class="addtocart tocart add_diamondtoring_button" id="add_diamondtoring_button" onclick="return add_diamondtoring()"><?php _e('Add Your Diamond', 'gemfind-ring-builder')?></button>
                                    <?php }   ?>
                                    <!--Tryon button-->
                                     <?php                                     
                                     //If enabled in Admin Setting then only show
                                     if(isset( $options['enable_tryon']) && $options['enable_tryon'] == 'yes' && $ringDatawithAdd['ringData']['tryon'] == true ){ 
                                       $styleNumber = explode("-",trim($setting['ringData']['styleNumber'])); ?>
                                    <a title="Tryon" href="https://cdn.camweara.com/gemfind/index_client.php?company_name=Gemfind&ringbuilder=1&skus=<?php echo $styleNumber[0]; ?>&buynow=0" class="tryonbtn fancybox fancybox.iframe" data-fancybox-type="iframe" id="tryon"><?php echo 'Virtual Try On' ?></a>
                                     <?php }  ?>
                                 </div>
                              </form>
                              <ul class="list-inline social-share">
                                <?php $imageurl = $setting['ringData']['mainImageURL'];
                                if($diamondsoption[0][0]->show_Pinterest_Share) { ?>
                                <li class="save_pinterest">
                                    <a class="save_pint" data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?url=<?php echo $ringurl;?>&media=<?php echo $imageurl; ?>&description=<?php echo $setting['ringData']['description']; ?>" data-pin-height="28"></a>
                                </li>
                                <?php }  ?>
                                <?php if($diamondsoption[0][0]->show_Twitter_Share) { ?>
                                <li class="share_tweet">
                                    <a href="https://twitter.com/share?ref_src=<?php echo $ringurl;?>" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                                </li>
                                <?php }  ?>
                                <?php if($diamondsoption[0][0]->show_Facebook_Share) { ?>
                                <li class="share_fb">
                                    <div class="fb-share-button" data-href="<?php echo $ringurl;?>" data-layout="button" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $ringurl;?>" class="fb-xfbml-parse-ignore">Share</a></div>
                                </li>
                                <?php }  ?>
                                <?php if($diamondsoption[0][0]->show_Facebook_Like) { ?>
                                <li class="like_fb">
                                    <div class="fb-like" data-href="<?php echo $ringurl;?>" data-width="" data-layout="button_count" data-share="false" data-action="like" data-size="small" ></div>
                                </li>
                                <?php } ?>
                            </ul>
                           </div>
                        </div>
                     </div>
                     <div class="diamond-forms">
                        
                        <?php if ( $options['enable_hint'] == 'true' ): ?>
                           <div class="form-main no-padding diamond-request-form" id="drop-hint-main">
                              <div class="requested-form">
                                 <h2><?php _e('Drop A Hint', 'gemfind-ring-builder');?></h2>
                                 <p><?php _e('Because you deserve this.', 'gemfind-ring-builder');?></p>
                              </div>

                              <div class="note" style="display:none"></div>
                              <form method="post" enctype="multipart/form-data" action="#" data-hasrequired="<?php /* @escapeNotVerified */ _e('* Required Fields', 'gemfind-ring-builder') ?>" data-mage-init='{"validation":{}}' class="form-drop-hint" id="form-drop-hint">
                                 <input name="ringurl" type="hidden" value="<?php echo site_url().'/ringbuilder/settings/product/'.$ringurl; ?>">
                                 <input name="settingid" type="hidden" value="<?php echo $setting['ringData']['settingId']; ?>">
                                 <div class="form-field">
                                    <label>
                                       <input name="name" id="drophint_name" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" type="text" class="" data-validate="{required:true}" placeholder=" ">
                                       <span><?php _e('Your Name', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="email" id="drophint_email" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" type="email" class="" data-validate="{required:true, 'validate-email':true}" placeholder=" ">
                                       <span><?php _e('Your E-mail', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="recipient_name" id="drophint_rec_name" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" type="text" class="" data-validate="{required:true}" placeholder=" ">
                                       <span><?php _e('Hint Recipient\'s Name', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="recipient_email" id="drophint_rec_email" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" type="email" class="" data-validate="{required:true, 'validate-email':true}" placeholder=" ">
                                       <span><?php _e('Hint Recipient\'s E-mail', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="gift_reason" id="gift_reason" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" type="text" class="" data-validate="{required:true}" placeholder=" ">
                                       <span><?php _e('Reason For This Gift', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <textarea name="hint_message" rows="2" cols="20" id="drophint_message" class="" data-validate="{required:true}" placeholder="Add A Personal Message Here ..."></textarea>
                                    </label>
                                    <label>
                                       <div class="has-datepicker--icon">
                                          <input name="gift_deadline" id="gift_deadline" autocomplete="false" readonly title="Gift Deadline" value="" type="text" data-validate="{required:true}" placeholder="Gift Deadline">
                                       </div>
                                    </label>
                                    <div class="prefrence-action">
                                       <div class="prefrence-action action">
                                          <button type="button" data-target="drop-hint-main" onclick="Closeform(event)" class="cancel preference-btn btn-cencel"><span><?php _e('Cancel', 'gemfind-ring-builder');?></span></button>
                                          <button type="submit" onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-drop-hint')" title="<?php _e('Drop Hint', 'gemfind-ring-builder');?>" class="preference-btn">
                                             <span><?php _e('Drop Hint', 'gemfind-ring-builder');?></span>
                                          </button>
                                           
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        <?php endif;  ?>
                        <?php if ( $options['enable_email_friend'] == 'true' ): ?>
                           <div class="form-main no-padding diamond-request-form" id="email-friend-main">
                              <div class="requested-form">
                                 <h2><?php _e('E-Mail A Friend', 'gemfind-ring-builder');?></h2>
                              </div>
                              <div class="note" style="display:none"></div>
                              <form method="post" enctype="multipart/form-data" data-hasrequired="<?php /* @escapeNotVerified */ _e('* Required Fields', 'gemfind-ring-builder') ?>" data-mage-init='{"validation":{}}' class="form-email-friend" id="form-email-friend">
                                 <input name="ringurl" type="hidden" value="<?php echo site_url().'/ringbuilder/settings/product/'.$ringurl; ?>">
                                 <input name="settingid" type="hidden" value="<?php echo $setting['ringData']['settingId']; ?>">
                                 <input name="ringSize" type="hidden" value="<?php echo $setting['ringData']['ringSize'][0]; ?>" id="ringSize" class="ringSize">
                                 <div class="form-field">
                                    <label>
                                       <input id="email_frnd_name" name="name" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" placeholder="" type="text" class="" data-validate="{required:true}">
                                       <span for="email_frnd_name"><?php _e('Your Name', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="email" type="email" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" placeholder="" id="email_frnd_email" class="" data-validate="{required:true}">
                                       <span><?php _e('Your E-mail', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="friend_name" type="text" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" placeholder="" id="email_frnd_fname" class="" data-validate="{required:true}">
                                       <span><?php _e('Your Friend\'s Name', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="friend_email" type="email" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" placeholder="" id="email_frnd_femail" class="" data-validate="{required:true}">
                                       <span><?php _e('Your Friend\'s E-mail', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <textarea name="message" rows="2" placeholder="<?php _e( 'Add A Personal Message Here ...', 'gemfind-ring-builder'); ?>" cols="20" id="email_frnd_message" class="" data-validate="{required:true}"></textarea>
                                    </label>
                                    <div class="prefrence-action">
                                       <div class="prefrence-action action">
                                          <button type="button" data-target="email-friend-main" onclick="Closeform(event)" class="cancel preference-btn btn-cencel"><span><?php _e('Cancel', 'gemfind_ring_builder');?></span></button>
                                          <button type="submit" onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-email-friend')" title="<?php _e('Send To Friend', 'gemfind-ring-builder');?>" class="preference-btn">
                                             <span><?php _e('Send To Friend', 'gemfind-ring-builder');?></span>
                                          </button>
                                          
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        <?php endif;   ?>
                        <?php if ( $options['enable_more_info'] == 'true' ): ?>
                           <div class="form-main no-padding diamond-request-form" id="req-info-main">
                              <div class="requested-form">
                                 <h2><?php _e('Request More Information', 'gemfind-ring-builder');?></h2>
                                 <p><?php _e('Our specialists will contact you.', 'gemfind-ring-builder');?></p>
                              </div>
                              <div class="note" style="display:none"></div>
                              <form method="post" enctype="multipart/form-data" data-hasrequired="<?php /* @escapeNotVerified */ _e('* Required Fields', 'gemfind-ring-builder') ?>" data-mage-init='{"validation":{}}' class="form-request-info" id="form-request-info">
                                 <input name="ringurl" type="hidden" value="<?php echo site_url().'/ringbuilder/settings/product/'.$ringurl; ?>">
                                 <input name="settingid" type="hidden" value="<?php echo $setting['ringData']['settingId']; ?>">
                                 <input name="ringSize" type="hidden" value="<?php echo $setting['ringData']['ringSize'][0]; ?>" id="ringSize" class="ringSize">
                                 <div class="form-field">
                                    <label>
                                       <input name="name" type="text" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" id="reqinfo_name" placeholder="" class="" data-validate="{required:true}">
                                       <span><?php _e('Your Name', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="email" type="email" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" id="reqinfo_email" placeholder="" class="" data-validate="{required:true}">
                                       <span><?php _e('Your E-mail Address', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="phone" type="text" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" id="reqinfo_phone" placeholder="" class="" data-validate="{required:true}">
                                       <span><?php _e('Your Phone Number', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <textarea name="hint_message" rows="2" cols="20" placeholder="Add A Personal Message Here ..." id="reqinfo_message" class="" data-validate="{required:true}"></textarea>
                                    </label>
                                    <div class="prefrence-area">
                                       <p><?php _e('Contact Preference:', 'gemfind-ring-builder');?></p>
                                       <ul class="pref_container">
                                          <li>
                                             <input type="radio" data-validate="{'validate-one-required-by-name':true}" class="radio required-entry" name="contact_pref" value="By Email">
                                             <label><?php _e('By Email', 'gemfind-ring-builder');?></label>
                                          </li>
                                          <li>
                                             <input type="radio" data-validate="{'validate-one-required-by-name':true}" class="radio required-entry" name="contact_pref" value="By Phone">
                                             <label><?php _e('By Phone', 'gemfind-ring-builder');?></label>
                                          </li>
                                       </ul>
                                       <div class="prefrence-action">
                                          <div class="prefrence-action action">
                                             <button type="button" data-target="req-info-main" onclick="Closeform(event)" class="cancel preference-btn btn-cencel">
                                                <span><?php _e('Cancel', 'gemfind-ring-builder');?></span>
                                             </button>
                                             <button type="submit" onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-request-info')" title="<?php _e('Request', 'gemfind-ring-builder');?>" class="preference-btn">
                                                <span><?php _e('Request', 'gemfind-ring-builder');?></span>
                                             </button>
                                              
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        <?php endif;  ?>
                        <?php if ( $options['enable_schedule_viewing'] == 'true' ): ?>
                           <div class="form-main no-padding diamond-request-form" id="schedule-view-main">
                              <div class="requested-form">
                                 <h2><?php _e('Schedule A Viewing', 'gemfind-ring-builder');?></h2>
                                 <p><?php _e('See This Item & More In Our Store', 'gemfind-ring-builder');?></p>
                              </div>
                              <div class="note" style="display:none"></div>
                              <form method="post" enctype="multipart/form-data" data-hasrequired="<?php /* @escapeNotVerified */ _e('* Required Fields', 'gemfind-ring-builder') ?>" data-mage-init='{"validation":{}}' class="form-schedule-view" id="form-schedule-view">
                                 <input name="ringurl" type="hidden" value="<?php echo site_url().'/ringbuilder/settings/product/'.$ringurl; ?>">
                                 <input name="settingid" type="hidden" value="<?php echo $setting['ringData']['settingId']; ?>">
                                 <input name="ringSize" type="hidden" value="<?php echo $setting['ringData']['ringSize'][0]; ?>" id="ringSize" class="ringSize">
                                 <div class="form-field">
                                    <label>
                                       <input name="name" type="text" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" id="schview_name" placeholder="" class="" data-validate="{required:true}">
                                       <span><?php _e('Your Name', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="email" type="email" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" id="schview_email" placeholder="" class="" data-validate="{required:true}">
                                       <span><?php _e('Your E-mail Address', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <input name="phone" type="text" onfocus="focusFunction(this)" onfocusout="focusoutFunction(this)" id="schview_phone" placeholder="" class="" data-validate="{required:true}">
                                       <span><?php _e('Your Phone Number', 'gemfind-ring-builder');?></span>
                                    </label>
                                    <label>
                                       <textarea name="hint_message" rows="2" cols="20" placeholder="Add A Personal Message Here ..." id="schview_message" class="" data-validate="{required:true}"></textarea>
                                    </label>
                                    <?php
/*                                    echo "<pre>";
                                      print_r($ringDatawithAdd['ringData']);
                                    echo "</pre>";*/
                                    $addressList = $ringDatawithAdd['ringData']['addressList'];           
                                    //print_r($addressList);
                                    ?>
                                    <label>
                                       <select data-validate="{required:true}" name="location" placeholder="" id="schview_loc">
                                          <option value=""><?php _e('--Location--', 'gemfind-ring-builder'); ?></option>                                          
                                          <?php foreach ($addressList as $value) { $value = (array) $value; ?>
                                             <option data-locationid="<?php echo $value['locationID']; ?>" value="<?php echo $value['locationName']; ?>"><?php echo $value['locationName']; ?></option>
                                          <?php } ?>
                                       </select>
                                    </label>
                                    <label>
                                       <div class="has-datepicker--icon">
                                          <input name="avail_date" id="avail_date" readonly autocomplete="false" placeholder="When are you available?" title="When are you available?" value="" type="text" data-validate="{required:true}">
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
                                    <div class="prefrence-action">
                                       <div class="prefrence-action action">
                                          <button type="button" data-target="schedule-view-main" onclick="Closeform(event)" class="cancel preference-btn btn-cencel"><span><?php _e('Cancel', 'gemfind-ring-builder');?></span></button>
                                          <button type="submit" onclick="ringFormSubmit(event,'<?php echo admin_url( "admin-ajax.php" ); ?>','form-schedule-view')" title="<?php _e('Request', 'gemfind-ring-builder');?>" class="preference-btn">
                                             <span><?php _e('Request', 'gemfind-ring-builder');?></span>
                                          </button>
                                         
                                       </div>
                                    </div>
                                 </div>
                              </form>

                           </div>
                        <?php endif; ?>


                     </div>
                  </div>
                  <div class="diamond-specification cls-for-hide" id="ring-specification">
                     <div class="specification-info">
                        <div class="specification-title">
                           <h2><?php _e('Setting Details', 'gemfind-ring-builder'); ?></h2>
                           <h4>
                              <a href="javascript:" id="dmnddtl" onclick="CallDiamondDetail()">                                     
                                 <svg version="1.1" data-toggle="tooltip" data-placement="bottom" title="Close" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 52 52" width="20px" height="20px" style="enable-background:new 0 0 52 52;display:inline;vertical-align:text-bottom;fill:#828282!important" xml:space="preserve">
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
                           <?php if(isset($setting['ringData']['styleNumber'])) { ?>
                              <li>
                                 <div class="diamonds-details-title">
                                    <p><?php _e('Setting Number', 'gemfind-ring-builder') ?></p>
                                 </div>
                                 <div class="diamonds-info">
                                    <p><?php echo $setting['ringData']['styleNumber'] ?></p>
                                 </div>
                              </li>
                           <?php }?>
                           <?php if(isset($setting['ringData']['cost'])) { ?>
                              <li>
                                 <div class="diamonds-details-title">
                                    <p><?php _e('Price', 'gemfind-ring-builder') ?></p>
                                 </div>
                                 <div class="diamonds-info">
                                    <p>
                                      <?php if( $setting['ringData']['showPrice'] == true && is_numeric($setting['ringData']['cost']) ) {
                                             if($options['price_row_format'] == 'left'){

                                                if($setting['ringData']['currencyFrom'] == 'USD'){
                                                
                                                  echo "$".number_format( $setting['ringData']['cost'] ); 
                                                
                                                }else{
                                                
                                                  echo number_format( $setting['ringData']['cost'] ).' '.$setting['ringData']['currencySymbol'].' '.$setting['ringData']['currencyFrom'];
                                                
                                                }
                                                
                                                }else{
                                                
                                                if($setting['ringData']['currencyFrom'] == 'USD'){
                                                
                                                  echo "$".number_format( $setting['ringData']['cost'] ); 
                                                
                                                }else{
                                                
                                                  echo $setting['ringData']['currencyFrom'].' '.$setting['ringData']['currencySymbol'].' '.number_format( $setting['ringData']['cost'] );   
                                                
                                                }
                                                
                                                }
                                      } else {
                                        _e( 'Call For Price', 'gemfind-ring-builder' );
                                      } ?>
                                    </p>
                                 </div>
                              </li>
                           <?php } ?>
                           <?php if(isset($setting['ringData']['metalType'])) { ?>
                              <li>
                                 <div class="diamonds-details-title">
                                    <p><?php _e('Metal Type', 'gemfind-ring-builder') ?></p>
                                 </div>
                                 <div class="diamonds-info">
                                    <p><?php echo $setting['ringData']['metalType'] ?></p>
                                 </div>
                              </li>
                           <?php } ?>
                        </ul>
                     </div>
                     <?php  if(!empty($setting['ringData']['sideDiamondDetail1'])){?>
                      <div class="specification-info">
                         <div class="specification-title">
                            <h2><?php echo _e('Side Diamond Details') ?></h2>
                         </div>
                         <ul>
                            <?php $v = 1; foreach ($setting['ringData']['sideDiamondDetail1'] as $singlesideDiamondDetail1) {  ?>
                            <?php if($singlesideDiamondDetail1->noOfDiamonds != '') { ?>
                            <li>
                               <div class="diamonds-details-title">
                                  <p><?php echo _e('Number of Diamonds '.$v,'gemfind-ring-builder') ?></p>
                               </div>
                               <div class="diamonds-info">
                                  <p><?php echo $singlesideDiamondDetail1->noOfDiamonds ?></p>
                               </div>
                            </li>
                            <?php } ?>
                            <?php if($singlesideDiamondDetail1->diamondCut != '') { ?>
                            <li>
                               <div class="diamonds-details-title">
                                  <p><?php echo _e('Cut '.$v,'gemfind-ring-builder') ?></p>
                               </div>
                               <div class="diamonds-info">
                                  <p><?php echo $singlesideDiamondDetail1->diamondCut; ?></p>
                               </div>
                            </li>
                            <?php } ?>
                            <?php if($singlesideDiamondDetail1->minimumCaratWeight != '') { ?>
                            <li>
                               <div class="diamonds-details-title">
                                  <p><?php echo _e('Minimum Carat Weight(ct.tw.) '.$v,'gemfind-ring-builder') ?></p>
                               </div>
                               <div class="diamonds-info">
                                  <p><?php echo $singlesideDiamondDetail1->minimumCaratWeight; ?></p>
                               </div>
                            </li>
                            <?php } ?>
                            <?php if($singlesideDiamondDetail1->minimumColor != '') { ?>
                            <li>
                               <div class="diamonds-details-title">
                                  <p><?php echo _e('Minimum Color '.$v,'gemfind-ring-builder') ?></p>
                               </div>
                               <div class="diamonds-info">
                                  <p><?php echo $singlesideDiamondDetail1->minimumColor ?></p>
                               </div>
                            </li>
                            <?php } ?>
                            <?php if($singlesideDiamondDetail1->minimumClarity != '') { ?>
                            <li>
                               <div class="diamonds-details-title">
                                  <p><?php echo _e('Minimum Clarity '.$v,'gemfind-ring-builder') ?></p>
                               </div>
                               <div class="diamonds-info">
                                  <p><?php echo $singlesideDiamondDetail1->minimumClarity ?></p>
                               </div>
                            </li>
                            <?php } ?>
                            <?php if($singlesideDiamondDetail1->diamondQuality != '') { ?>
                            <li>
                               <div class="diamonds-details-title">
                                  <p><?php echo _e('Diamond Quality '.$v,'gemfind-ring-builder') ?></p>
                               </div>
                               <div class="diamonds-info">
                                  <p><?php echo $singlesideDiamondDetail1->diamondQuality ?></p>
                               </div>
                            </li>
                            <?php } 
                            $v++; 
                          } ?> 
                         </ul>
                      </div>
                      <?php
                     } ?>
                     <?php if($setting['ringData']['sideDiamondDetail']->noOfDiamonds != ''){ ?>
                        <div class="specification-info">
                           <div class="specification-title">
                              <h2><?php _e('Side Diamond Details', 'gemfind-ring-builder') ?></h2>
                           </div>
                           <ul>
                              <?php if($setting['ringData']['sideDiamondDetail']->noOfDiamonds != '') { ?>
                                 <li>
                                    <div class="diamonds-details-title">
                                       <p><?php _e('Number of Diamonds', 'gemfind-ring-builder') ?></p>
                                    </div>
                                    <div class="diamonds-info">
                                       <p><?php echo $setting['ringData']['sideDiamondDetail']->noOfDiamonds ?></p>
                                    </div>
                                 </li>
                              <?php } ?>
                              <?php if($setting['ringData']['sideDiamondDetail']->diamondCut != '') { ?>
                                 <li>
                                    <div class="diamonds-details-title">
                                       <p><?php _e('Cut', 'gemfind-ring-builder') ?></p>
                                    </div>
                                    <div class="diamonds-info">
                                       <p><?php echo $setting['ringData']['sideDiamondDetail']->diamondCut; ?></p>
                                    </div>
                                 </li>
                              <?php } ?>
                              <?php if($setting['ringData']['sideDiamondDetail']->minimumCaratWeight != '') { ?>
                                 <li>
                                    <div class="diamonds-details-title">
                                       <p><?php _e('Minimum Carat Weight(ct.tw.)', 'gemfind-ring-builder') ?></p>
                                    </div>
                                    <div class="diamonds-info">
                                       <p><?php echo $setting['ringData']['sideDiamondDetail']->minimumCaratWeight; ?></p>
                                    </div>
                                 </li>
                              <?php } ?>
                              <?php if($setting['ringData']['sideDiamondDetail']->minimumColor != '') { ?>
                                 <li>
                                    <div class="diamonds-details-title">
                                       <p><?php _e('Minimum Color', 'gemfind-ring-builder') ?></p>
                                    </div>
                                    <div class="diamonds-info">
                                       <p><?php echo $setting['ringData']['sideDiamondDetail']->minimumColor ?></p>
                                    </div>
                                 </li>
                              <?php } ?>
                              <?php if($setting['ringData']['sideDiamondDetail']->minimumClarity != '') { ?>
                                 <li>
                                    <div class="diamonds-details-title">
                                       <p><?php _e('Minimum Clarity', 'gemfind-ring-builder') ?></p>
                                    </div>
                                    <div class="diamonds-info">
                                       <p><?php echo $setting['ringData']['sideDiamondDetail']->minimumClarity ?></p>
                                    </div>
                                 </li>
                              <?php } ?>
                           </ul>
                        </div>
                     <?php } if($setting['ringData']['centerStoneFit'] != ""){ ?>
                        <div class="specification-info canbesetwith">
                           <div class="specification-title">
                              <h2><?php _e('Can Be Set With', 'gemfind-ring-builder') ?></h2>
                           </div>
                           <ul>
                              <?php    $centerstone = explode(',', $setting['ringData']['centerStoneFit']); ?>
                              <?php foreach ($centerstone as $centerstonesingle) { ?>
                                 <li>
                                    <div class="diamonds-details-title">
                                       <p><?php echo $centerstonesingle; ?></p>
                                    </div>
                                    <div class="diamonds-info">
                                       <p><?php echo $setting['ringData']['centerStoneMinCarat'].'-'.$setting['ringData']['centerStoneMaxCarat'] ?></p>
                                    </div>
                                 </li>
                              <?php } ?>
                           </ul>
                        </div>
                     <?php }   ?>
                  </div>
                  <!-- <div id="captcha1" class="g-recaptcha invisible-recaptcha"> </div>  -->

               </div>
            </div>                  
         </div>
      </div>
   </div>



    <?php
      $gemfind_ring_builder = get_option( 'gemfind_ring_builder' );
      if($gemfind_ring_builder['show_copyright']=="yes"){                    ?>
      <div class="copyright-text">
        <p>Powered By <a href="http://www.gemfind.com" target="_blank">GemFind</a></p>
      </div>
    <?php  } ?>
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
      <?php  }    ?>
   <script type="text/javascript">
      jQuery(document).ready(function(){
         jQuery('#ring_size').change(function(){
           var qty = jQuery('#ring_size').val();
           jQuery(".ringSize").val(qty);
         });
         jQuery('[data-toggle="tooltip"]').tooltip();   
    });
      var centerStone = jQuery("#centerstonesize :selected").text();
      jQuery('.canbesetwith .diamonds-info > p').html(centerStone);
      jQuery('div.diamention img').each(function() {
        var src = jQuery(this).attr("data-src"); 
        var id = jQuery(this).attr("id"); 
      //   imageExists1(src, id, function(exists) {
      //     if(exists){
      //       jQuery('#'+id).attr('src',src);
      //    } else {
      //       jQuery('#'+id).attr('src','<?php echo $noimageurl ?>');
      //    } 
      // });
     });
      function imageExists1(url, id, callback) {
        var img = new Image();
        img.onload = function() { callback(true); };
        img.onerror = function() { callback(false); };
        img.src = url;
     }
     jQuery("#internaluselink").on('click',function(){
        jQuery('#msg').html('');
        jQuery('#internaluseform input').val('');
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
          data: {'action': 'authenticateDealer', 'form_data': jQuery('#internaluseform').serializeArray()},
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


   //showLightBrillaince
   jQuery(document).ready(function() {
        jQuery("#lightBrilliance").fancybox({
            'width'             : '1200',
            'height'            : '800',
            'autoScale'         : false,
            'transitionIn'      : 'none',
            'transitionOut'     : 'none',
            'type'              : 'iframe',
            'showCloseButton': true
        });
    });

     jQuery(document).ready(function() {
     jQuery("#zoom_me").click(function() {
       jQuery.fancybox.open({
         src : '#hidden-content',
         type : 'inline',
         opts : {
           afterShow : function( instance, current ) {
             console.info('done!');
          }
       }
    });
    });
  });

    

    function addToCart(e, ringData) {
       e.preventDefault();               
       document.getElementById("gemfind-loading-mask").style.display = "block";
       var ringSize = jQuery("#ringsizesettingonly").val();
       jQuery.ajax({
         type : "POST",
         dataType : "html",
         url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
         data : {action: "add_product_to_cart_rb", ring_size:ringSize, ring_name: "<?php echo $_SERVER['REQUEST_URI']; ?>", ring: JSON.stringify(ringData)},
         success: function(response) {
            document.getElementById("gemfind-loading-mask").style.display = "none";
            window.location.href = '<?php echo site_url(); ?>' + '/cart/' + '?add-to-cart=' + response;
         }
      });
    }

    function directRingAddToCart(e,post_id){
     e.preventDefault();
     window.location.href = '<?php echo get_site_url(); ?>' + '/cart/' + '?add-to-cart=' + post_id;
  }

  

</script>

 

<?php

$uri = $_SERVER['REQUEST_URI'];

$path = parse_url( $uri, PHP_URL_PATH );
if( strpos( $path, 'ringbuilder/diamondlink' ) !== false ) {   ?>
<script type="text/javascript">            
   jQuery(window).bind("load", function() {
     jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
     var video_check = jQuery('#ringvideo video')[0];       
     if( video_check.networkState == 3 && video_check.readyState == 0 ){
        jQuery('#ringimg').css('display','block');
        // jQuery('#ringvideo').css('display','none');
        jQuery('.videoicon').css('display','none');
     }
     if( video_check.networkState == 1 ){
        jQuery('#ringimg').css('display','none');
        // jQuery('#ringvideo').css('display','block');
     }
   });
</script>
<?php } ?>

 <script type="text/javascript">
   jQuery(document).on('submit','form#add_diamondtoring_form',function(e){               
    var ringData = [];
    var data = {};
    var caratRange = jQuery("form#add_diamondtoring_form #centerstonesizevalue").val();
    var caratRangeArray = caratRange.split('-');
    data.ringsizewithdia = jQuery("#ringsizewithdia").val();
    data.ringmaxcarat = jQuery.trim(caratRangeArray[1]);
    data.ringmincarat = jQuery.trim(caratRangeArray[0]);
    data.centerStoneSize = caratRange;
    data.centerStoneFit = jQuery("#centerStoneFit").val();
    data.setting_id = jQuery("#setting_id").val();
    data.isLabSetting = jQuery("#islabsettings").val();
    ringData.push(data);
    var expire = new Date();
    expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
    jQuery.cookie("_wp_ringsetting", JSON.stringify(ringData), {
       path: '/',
       expires: expire
    });
     window.location.href = '<?php echo $final_shop_url.'/diamondlink/'; ?>';      
   });

      //Added code for Ring Pixel track ajax call
     jQuery(window).bind("load", function () {
        <?php
            $diamondajax=$setting;
            $removeaddressList=$diamondajax['ringData']['addressList'][0];
            $removeaddressList=$removeaddressList->address='';
            $diamondajax['ringData']['vendorCompany']='';
        ?>
        var ringData = '<?php echo json_encode( $diamondajax ); ?>';
        var post_ring_data = JSON.stringify(ringData);
        var product_track_url = window.location.href;
        setTimeout(function () {
            jQuery.ajax({
                url: myajax.ajaxurl,
                data: {action: "ringTracking", ring_data: post_ring_data, track_url: product_track_url},
                    type: 'POST',
                    success: function (response) {
                        console.log(response);
                    }
                }).done(function (data) {

                });
            }, 1000
            );
    });

  var saveringfiltercookiedata = getCookie('_wpsaveringfiltercookie');
   if(saveringfiltercookiedata==""){
      jQuery.removeCookie('_wpsaveringbackvalue', {path: '/'});
   }
   
   jQuery(window).on('beforeunload', function() { jQuery("video").css('visibility', 'hidden'); });

	 function changediamondtype() {
	 	
	 	var type = jQuery('.diamondtype-drpdwn option:selected').val();
	 	var url = jQuery("#add_diamondtoring_form").attr("action");
	 	var labgrown = url + "navlabgrown";
	 	
		console.log(type);
		

		if(type=="labgrown"){
			jQuery("#add_diamondtoring_form").attr("action",labgrown);
		}

		if(type=="mined"){
			var mined = url.replace('navlabgrown','',)
			jQuery("#add_diamondtoring_form").attr("action",mined);
			console.log(mined);
		}
		console.log(labgrown);
		console.log(url);
	 }

 
   //For Tryon button
   jQuery(document).ready(function ($) {
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
               if(event.data == "closeIframe"){ //Close
                   var iframe = document.getElementById("iFrameID"); 
                   iframe.contentWindow.location.replace("");
                   iframe.style.display = "none";
                       $.fancybox.close();          
               } else if(event.data.includes("buynow")){
                   $('#product_addtocart_button').click();
               }
           }
       });
   });
 </script> 
 <?php } else { _e('Something went wrong, please try after some time!', 'gemfind-ring-builder'); } ?>
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
      console.log(token);
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
<?php if($diamondCookieData[0]->diamondid){   ?>
<script>
    jQuery("#centerstonesize option").attr("disabled","disabled");
</script>
<?php } ?>
<div id="fb-root"></div>

<?php if(!empty($site_key)) { ?>
  <script type="text/javascript">
     function onSubmit(token) {
          if(!token){
            alert("Something Wrong went with captcha");
            return false;
          }
     }
    window.sitekey = '<?php echo $site_key; ?>';

    function validate(event) {
      event.preventDefault();
     var captchaevent =  grecaptcha.execute();
   }
</script>
  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" data-callback="onSubmit" data-size="invisible"></div>
 <?php } ?>

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId=1003857163475797&autoLogAppEvents=1" nonce="Uo0Kr4VM"></script>
<script async defer src="//assets.pinterest.com/js/pinit.js"></script>

<style type="text/css">
  
.grecaptcha-badge{
      visibility: visible !important;
}
</style> 