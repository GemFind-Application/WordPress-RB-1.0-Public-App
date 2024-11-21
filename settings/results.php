<?php
$list = getRings($filter_data);

//print_r($list);
$noimageurl           = RING_BUILDER_URL . "assets/images/no-image.jpg";
$loaderimg            = RING_BUILDER_URL . "assets/images/loader-2.gif";
$resultperpageoptions = getAllOptionsRings();
$narrow_active        = ($_POST['gridmodenarrow']) ? 'active' : '';
$widecol_active       = ($_POST['gridmodenarrow']) ? '' : 'active';
$gridmode_view        = ($_POST['gridmodenarrow']) ? 'gridmode' : 'gridmodewide';



//$ringDatawithAdd = getRingById_rb($setting['ringData']['settingId'],'');
$options = getOptions_rb();

// echo "<pre>";
// print_r($options);
// exit; 

if ($options['load_from_woocommerce'] == '1') {
}
?>

<div class="search-details no-padding">
    <div class="searching-result">
        <div class="number-of-search">
            <p>
                <strong><?php echo number_format($list['pagination']['total']); ?></strong><?php _e('Settings', 'gemfind-ring-builder'); ?>
            </p>
        </div>
        <div class="view-or-search-result">
            <div class="change-view-result">
                <select class="pagesize" id="pagesize" name="pagesize" onchange="ItemPerPage(this)">
                    <?php foreach ($resultperpageoptions as $value) { ?>
                        <option value="<?php echo $value['value'] ?>"><?php echo $value['label'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="grid-view-sort">
                <select name="gridview-orderby" id="gridview-orderby" class="gridview-orderby" onchange="gridSort(this)">
                    <option value="cost-l-h"><?php echo 'Price: Low - High'; ?></option>
                    <option value="cost-h-l"><?php echo 'Price: High - Low'; ?></option>
                </select>
            </div>
            <div class="change-view">
                <ul>
                    <li class="grid-view" data-toggle="tooltip" data-placement="top" title="Grid view"><a href="javascript:;" class="gridmodenarrow <?php echo $narrow_active; ?>"><?php _e('Grid view 3 column', 'gemfind-ring-builder'); ?></a>
                    </li>
                    <li class="grid-view-wide" data-toggle="tooltip" data-placement="top" title="Grid view wide"><a href="javascript:;" class="<?php echo $widecol_active; ?> gridmodewidecol"><?php _e('Grid view 4 column', 'gemfind-ring-builder'); ?></a>
                    </li>
                </ul>
            </div>
            <div class="search-in-table" id="searchintable">
                <input type="text" name="searchdidfield" id="searchdidfield" placeholder="<?php _e('Search Setting#', 'gemfind-ring-builder'); ?>"><a href="javascript:;" title="close" id="resetsearchdata">X</a>
                <button id="searchsettingid" title="Search Setting"></button>
            </div>
        </div>
    </div>
</div>
</div>
<?php if (isset($list['pagination']['total']) && $list['pagination']['total'] != 0) : ?>
    <div class="search-details no-padding">
        <div class="search-view-grid <?php echo $gridmode_view; ?>" id="grid-mode">
            <div class="grid-product-listing">
                <?php
                // echo "<pre>";
                // print_r($list['data']);
                // exit;
                ?>
                <?php foreach ($list['data'] as $result) : ?>
                    <div class="search-product-grid" id="<?php echo $result->settingId; ?>">
                        <?php if ($result->videoURL) { ?>
                            <a href="javascript:;" class="triggerVideo" data-id="<?php echo $result->settingId; ?>" onclick="showModal()"> <i class="fa fa-video-camera"> </i> </a>
                        <?php } ?>
                        <div class="loading-mask gemfind-loading-mask-grid" style="display: none;">
                            <!-- <div class="loader gemfind-loader"><p>Please wait...</p></div> -->
                            <!-- <img src="<?php echo $loaderimg; ?>"> -->
                            <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
                        </div>
                            <a href="<?php echo getRingViewUrlRB( $result->priceSettingId, $result->name ); ?>"
                             id="ringurlid-<?php echo $result->settingId ?>" class="ringautourl"
                             onclick="SetBackValue('<?php echo $result->settingId ?>','<?php //echo $this->ringbuilder_lib->getRingViewUrl($result->settingId) ?>');"
                             title="<?php _e( 'View Ring', 'gemfind-ring-builder' ); ?>">
                            <div class="product-images">
                                <span class="imagecheck" data-src="<?php echo $result->imageUrl; ?>" data-srcbig="<?php echo $result->imageUrl; ?>" data-id="<?php echo $result->settingId; ?>"></span>
                                <img src="<?php echo $loaderimg; ?>" alt="<?php echo $result->name; ?>" title="<?php echo $result->name; ?>" />
                                <?php if ($result->videoURL) { ?>
                                    <video id="video-<?php echo $result->settingId ?>" style="display: none;">
                                        <source src="<?php //echo $result->videoURL; ?>" type="video/mp4">
                                    </video>
                                <?php } ?>
                            </div>

                            <div class="product-details">
                                <div class="product-item-name">
                                    <span><strong><?php echo $result->name ?></strong></span>
                                </div>
                                <div class="product-box-pricing">
                                    <span>
                                        <?php
                                         $rprice = $result->cost;
                                         $rprice = str_replace(',', '', $rprice);
                                        if ($result->showPrice == true) {
                                            if($options['price_row_format'] == 'left'){

                                                if($result->currencyFrom == 'USD'){
                              
                                                  echo "$".number_format($rprice); 
                              
                                                }else{
                              
                                                  echo number_format($rprice).' '.$result->currencySymbol.' '.$result->currencyFrom;
                              
                                                }
                              
                                            }else{
                              
                                                if($result->currencyFrom == 'USD'){
                              
                                                  echo "$".number_format($rprice); 
                              
                                                }else{
                              
                                                  echo $result->currencyFrom.' '.$result->currencySymbol.' '.number_format($rprice);   
                              
                                                }
                              
                                            }
                                        } else {
                                            _e('Call For Price', 'gemfind-ring-builder');
                                        }
                                        ?>
                                    </span>

                                    
                                </div>
                            </div>

                        </a>

                             <?php                                     
                                 if(isset( $options['enable_tryon']) && $options['enable_tryon'] == 'yes'){ 
                                   $styleNumber = explode("-",trim($result->stockNumber)); ?>
                                <a title="Tryon" href="https://cdn.camweara.com/gemfind/index_client.php?company_name=Gemfind&ringbuilder=1&skus=<?php echo $styleNumber[0]; ?>&buynow=0" class="tryonbtn fancybox fancybox.iframe" data-fancybox-type="iframe" id="tryon"><?php echo 'Virtual Try On' ?></a>
                             <?php }?> 

                        <input type="hidden" name="diamondimage" id="diamondimage-<?php echo $result->settingId; ?>" value="" />
                        <input type="hidden" name="diamondprice" id="diamondprice-<?php echo $result->settingId; ?>" value="<?php echo $result->cost; ?>" />
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="grid-paginatin" style="text-align:center;">
            <?php $current = 1;
            $number        = $list['perpage'];
            $pages         = ceil($list['pagination']['total'] / $number);

            if ($list['pagination']['currentpage'] > 1) {
                $current = $list['pagination']['currentpage'];
            }
            if ($current - 1 == 0) {
                $value = 1;
            } else {
                $value = $current - 1;
            }
            ?>
            <div class="pagination-div pagination_scroll">
                <input type="hidden" name="tool_version" value="Version 2.7.0">
                <ul>
                    <li class="grid-next-double">
                        <a href="javascript:void(0);" onclick="PagerClick('1');"></a>
                    </li>
                    <li <?php echo ($current == 1) ? 'class="disabled grid-next"' : 'class="grid-next"' ?>>
                        <a href="javascript:void(0);" <?php if (($current - 1) != 0) { ?> onclick="PagerClick('<?php echo ($value) ?>');" <?php } ?>><?php echo ($value) ?></a>
                    </li>
                    <?php for ($i = 1; $i <= $pages; $i++) {
                        if ($i <> $current) {
                            if ($i >= $current + 3) {
                                continue;
                            }
                            if ($i <= $current - 3) {
                                continue;
                            }
                    ?>
                            <li>
                                <a href="javascript:void(0);" onclick="PagerClick('<?php echo $i ?>');"><?php echo $i; ?></a>
                            </li>
                        <?php } else { ?>
                            <li class="active">
                                <a href="javascript:void(0);" class="active" onclick="PagerClick('<?php echo $i ?>');"><?php echo $i; ?></a>
                            </li>
                    <?php }
                    } ?>
                    <li <?php echo ($current == $pages) ? 'class="disabled grid-previous"' : 'class="grid-previous"' ?>>
                        <a href="javascript:void(0);" <?php if ($current != $pages) { ?> onclick="PagerClick('<?php echo ($pages); ?>');" <?php } ?>><?php echo ($pages); ?></a>
                    </li>
                    <li class="grid-previous-double">
                        <a href="javascript:void(0);" onclick="PagerClick('<?php echo $pages; ?>');"></a>
                    </li>
                </ul>
            </div>
            <?php
            if ($current == 1) {
                $from = 1;
                $to   = $number;
            } else {
                $from = (($current - 1) * $number) + 1;
                $to   = ($current * $number);
            }
            if ($list['pagination']['total'] < $to) {
                $to = $list['pagination']['total'];
            }
            // $total_page=number_format( $list['pagination']['total'];
            // echo $total_page;
            // exit();
            echo "<div class='page-checked'><div class='result-bottom'>Results " . number_format($from) . " to " . number_format($to) . " of " . number_format($list['pagination']['total']) . " </div></div> ";
            ?>
        </div>
    </div>
    <div id="myRbModal" class="Rbmodal">
        <!-- Modal content -->
        <div class="Rbmodal-content">

            <span class="Rbclose">&times;</span>
            <div class="loader_rb" style="display: none;">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . "images/ring_rb.gif" ?>" style="width: 200px; height: 200px;">
            </div>
            <iframe src="" id="iframevideo" scrolling="no" style="width:100%; height:98%;" allow="autoplay"> </iframe>
            <video style="width:100%; height:90%;" id="mp4video" loop autoplay>
            <source src=""  type="video/mp4">
        </video>
        </div>
    </div>

<?php /*foreach ($list['data'] as $result) : ?>
            <?php $diamondviewurltes[] = $block->getRingViewUrl($result->settingId); ?>
        <?php endforeach;*/ ?>
<?php else : ?>
    <div class="search-details no-padding no-result-main">
        <div class="searching-result no-result-div">
            <?php _e('No Data Found.', 'gemfind-ring-builder'); ?>
        </div>
    </div>
<?php endif; ?>
<script type="text/javascript">
    jQuery("span.imagecheck").each(function() {
        var id = jQuery(this).attr("data-id");
        var src = jQuery(this).attr("data-src");
        imageExists(src, function(exists) {
            if (exists) {
                jQuery('tr#' + id + ' td img').attr('src', src);
                jQuery('div#' + id + ' div.product-images img').attr('src', src);
                jQuery('input#diamondimage-' + id).val(src);
            } else {
                jQuery('tr#' + id + ' td img').attr('src', '<?php echo $noimageurl ?>');
                jQuery('div#' + id + ' div.product-images img').attr('src', '<?php echo $noimageurl ?>');
                jQuery('input#diamondimage-' + id).val('<?php echo $noimageurl ?>');
            }
        });
    });

    function imageExists(url, callback) {
        var img = new Image();
        img.onload = function() {
            callback(true);
        };
        img.onerror = function() {
            callback(false);
        };
        img.src = url;
    }

    jQuery('.change-view ul li a').click(function() {
        //console.log('click');
        jQuery('.change-view ul li a').removeClass('active');
        jQuery(this).addClass('active');
        if (jQuery(this).hasClass('gridmodenarrow')) {
            jQuery('#grid-mode').removeClass('gridmodewide');
            jQuery('#grid-mode').addClass('gridmode');
        } else {
            jQuery('#grid-mode').addClass('gridmodewide');
            jQuery('#grid-mode').removeClass('gridmode');
        }
    });

    function showModal() {
        // jQuery("#iframevideo").removeAttr("src");
        // jQuery('#myRbModal').modal('show');
        // jQuery('.loader_rb').show();

        jQuery("#iframevideo").removeAttr("src");
        jQuery("#mp4video").removeAttr("src");
        jQuery("#mp4video").attr("src", '');
        jQuery('#myRbModal').modal('show');
        jQuery('.loader_rb').show();

        var divid = jQuery(event.currentTarget).data('id');
        jQuery.ajax({
            type: "POST",
            url: myajax.ajaxurl,
            data: {
                action: 'getringvideos',
                product_id: divid
            },
            cache: true,
            success: function(response) {
                response = JSON.parse(response);
                 if (response.showVideo == true) {
                    var fileExtension = response.videoURL.replace(/^.*\./, '');
                    console.log (fileExtension);
                    if(fileExtension=="mp4"){
                        jQuery('#iframevideo').hide();
                        setTimeout(function() {
                           jQuery("#mp4video").attr("src", response.videoURL);
                           jQuery('.loader_rb').hide();
                           jQuery('#mp4video').get(0).play();
                        }, 3000);
                    }
                    else{
                        jQuery('#mp4video').hide();
                        setTimeout(function() {
                            jQuery("#iframevideo").attr("source", response.videoURL);
                            jQuery('.loader_rb').hide();
                            jQuery('#iframevideo').show();
                        }, 3000);
                    }
                }  
            }
        });
    }
    jQuery(".Rbclose").click(function() {
        jQuery('#myRbModal').modal('hide');
    });

    // jQuery('.pagination_scroll').click(
    //     function(e) {
    //         jQuery('html, body').animate({
    //             scrollTop: '0px'
    //         }, 800);
    //     }
    // );

    jQuery('.pagination_scroll').click(
        function(e) {
            jQuery('html, body').animate({
                scrollTop: jQuery('#shapeul').position().top
            }, 800);
        });

    jQuery(document).ready(function() {

            jQuery('[data-toggle="tooltip"]').tooltip({
                trigger: "hover"
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
