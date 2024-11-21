jQuery(document).ready(function() {
  ringbuildermain();
  createnav();
  


});
// function getSearchParams(k){
//  var p={};
//  location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
//  return k?p[k]:p;
// }

function getSearchParams(k) {
  const pathSegments = location.pathname.split('/').filter(segment => segment !== '');
  const p = {};

  // Remove specific segments like 'ringbuilder' and 'settings'
  const startIndex = pathSegments.indexOf('ringbuilder');
  if (startIndex >= 0) {
    pathSegments.splice(startIndex, 2); // Remove 2 segments starting from 'ringbuilder'
  }

  for (let i = 0; i < pathSegments.length; i += 2) {
    if (i + 1 < pathSegments.length) {
      p[pathSegments[i]] = pathSegments[i + 1];
    }
  }

  return k ? p[k] : p;
}


function ringbuildermain() {           
        var $searchModule = jQuery('#search-rings');
        var searchringformdata = jQuery('#search-rings-form').serialize();
        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        var ringbackvaluecookiedata = getCookie('_wpsaveringbackvalue');
        var saveringfiltercookiedata = getCookie('_wpsaveringfiltercookie');
        // console.log(saveringfiltercookiedata);
        var ringsettingcookiedata = getCookie('_wp_ringsetting');
        var diamondsettingcookiedata = getCookie('_wp_diamondsetting');
        //var getSearchParams = getSearchParams("shape");
        jQuery.ajax({
            url: myajax.ajaxurl,
            data: {action:'loadringfilter', searchringform:searchringformdata,ringbackvaluecookie:ringbackvaluecookiedata,saveringfiltercookie:saveringfiltercookiedata,ringsettingcookie:ringsettingcookiedata,diamondsettingcookie:diamondsettingcookiedata, 'getSearchParams':getSearchParams() },
            type: 'POST',
            dataType: 'html',
            cache: true,
            beforeSend: function(settings) {                
                if (jQuery(".placeholder-content").length == 0){                    
                    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                }
            },
            success: function(response) {
                jQuery('#filter-main-div').html(response);
                jQuery("#search-rings .tab-section, #search-rings .diamond-filter-title, #search-rings .tab-content, .copyright-text").show();
                jQuery(".placeholder-content-top").remove();
                //jQuery("#search-rings").show();
                jQuery("#search-rings-form #submit").trigger("click");
                jQuery('input:checkbox').change(function() {
                    if (jQuery(this).is(':checked')) {
                        jQuery('#shapeul li > div').removeClass('selected active');
                        jQuery("input:checkbox").attr("checked", false);
                        jQuery(this).attr("checked", true);
                        
                        jQuery(this).parent().addClass('selected active');
                        jQuery("#search-rings-form #submit").trigger("click");
                    } else {
                        jQuery(this).parent().removeClass('selected active');
                        jQuery("#search-rings-form #submit").trigger("click");
                    }
                });
            //Price slider
         var sliders = jQuery("#price_slider")[0];
         if (typeof sliders !== 'undefined' && typeof sliders !== 'undefined') {
            var $min_input = jQuery(sliders).find("input[data-type='min']");
            var $max_input = jQuery(sliders).find("input[data-type='max']");
            
            var $price_min_val = parseInt(jQuery(sliders).attr('data-min'))
            var $price_max_val = parseInt(jQuery(sliders).attr('data-max'));
            
            var $start_price_min = parseInt($min_input.val());
            var $start_price_max = parseInt($max_input.val());
            
            var first_half_interval = 1;
            var last_half_interval = 2500;
            
            if( $price_min_val > 10000 ){
                var range = {
                    'min': [$price_min_val, first_half_interval],
                    '50%': [10000, last_half_interval],
                    'max': [$price_max_val]
                }
            } else {
                var range = {
                    'min': [$price_min_val, first_half_interval],                    
                    'max': [$price_max_val]
                }
            }
            var slider_object = noUiSlider.create(sliders, {
                start: [$start_price_min, $start_price_max],
                //tooltips: [true, wNumb({decimals: 1})],
                connect: true,
                range: range,
                format: wNumb({
                    decimals: 0,
                    prefix: '',
                    thousand: ',',
                })
            });
            sliders.noUiSlider.on('update', function( values, handle ) {
                //console.log( values );
                var value_show = values[handle];
                if ( handle ) {
                    $max_input.val(value_show);
                } else {
                    $min_input.val(value_show);
                }
            });
            sliders.noUiSlider.on('change', function( values, handle ) {
                jQuery("#search-rings-form #submit").trigger("click");
            });
            var $price_input1 = jQuery(sliders).find("input.slider-left");
            var $price_input2 = jQuery(sliders).find("input.slider-right");
            var price_inputs = [$price_input1, $price_input2];
            slider_update_textbox(price_inputs,sliders);
        }
                jQuery('input[type=radio][name=ring_collection]').on('click', function() {
                    $self = jQuery(this);
                    if ($self.hasClass('is-checked')) {
                        $self.prop('checked', false).removeClass('is-checked');
                        jQuery(this).parent().removeClass('selected active');
                        jQuery('#collections-section ul li').removeClass('selected active');
                        jQuery('#collections-section ul li').css('opacity', 1);
                        jQuery('#collections-section ul li').css('pointer-events', 'auto');
                        jQuery("#search-rings-form #submit").trigger("click");
                    } else {
                        jQuery('input[type=radio][name=ring_collection]').removeClass('is-checked');
                        jQuery('#collections-section ul li').removeClass('selected active');
                        $self.addClass('is-checked');
                        jQuery(this).parent().addClass('selected active');
                        jQuery(this).parent().parent().addClass('selected active');
                        jQuery("#search-rings-form #submit").trigger("click");
                    }
                });
                jQuery('input[type=radio][name=ring_metal]').on('click', function() {
                    $self = jQuery(this);
                    if ($self.hasClass('is-checked')) {
                        $self.prop('checked', false).removeClass('is-checked');
                        jQuery(this).parent().removeClass('selected active');
                        jQuery('.metaltypeli ul li').removeClass('selected active');
                        jQuery("#search-rings-form #submit").trigger("click");
                    } else {
                        jQuery('input[type=radio][name=ring_metal]').removeClass('is-checked');
                        jQuery('.metaltypeli ul li').removeClass('selected active');
                        $self.addClass('is-checked');
                        jQuery(this).parent().addClass('selected active');
                        jQuery("#search-rings-form #submit").trigger("click");
                    }
                });
                jQuery('#collections-section input[type=radio][name=ring_collection]').on('click', function() {
                    jQuery.ajax({
                        // url: jQuery('#search-rings-form #baseurl').val() + 'ringbuilder/settings/updatefilter/',
                        url: myajax.ajaxurl,
                        data: {action: 'updatefilter', filter_data: jQuery('#search-rings-form').serializeArray()},
                        type: 'POST',
                        dataType: 'html',
                        cache: true,
                        success: function(response) {
                            //console.log(response);
                            var responseData = jQuery.parseJSON(response);
                            // set default all opacity 1 by default on click 
                            jQuery('.filter-for-shape ul li').css('opacity', 1);
                            jQuery('.filter-for-shape ul li').css('pointer-events', 'auto');
                            //jQuery('.metaltypeli li').css('opacity', 1);
                            //jQuery('.metaltypeli li').css('pointer-events', 'auto');
                            // if specific condition is true then hide the shape
                            if (responseData.hiddenshape) {
                                jQuery(responseData.hiddenshape).css('opacity', 0.5);
                                jQuery(responseData.hiddenshape).css('pointer-events', 'none');
                            }
                            // if specific condition is true then hide the metaType
                            if (responseData.hiddenmetaltype) {
                                jQuery(responseData.hiddenmetaltype).css('opacity', 0.5);
                                jQuery(responseData.hiddenmetaltype).css('pointer-events', 'none');
                            }
                        }
                    });
                });
                jQuery('#shapeul input:checkbox').change(function() {                    
                    jQuery.ajax({
                        //url: jQuery('#search-rings-form #baseurl').val() + 'ringbuilder/settings/updatefilter/',
                        url: myajax.ajaxurl,
                        data: {action: 'updatefilter', filter_data: jQuery('#search-rings-form').serializeArray()},
                        type: 'POST',
                        dataType: 'html',
                        cache: true,
                        success: function(response) {
                            console.log(response);
                            var responseData = jQuery.parseJSON(response);
                            jQuery('#collections-section ul li').css('opacity', 1);
                            jQuery('#collections-section ul li').css('pointer-events', 'auto');
                            //jQuery('.metaltypeli li').css('opacity', 1);
                            //jQuery('.metaltypeli li').css('pointer-events', 'auto');
                            if (responseData.hiddencollection) {
                                jQuery(responseData.hiddencollection).css('opacity', 0.5);
                                jQuery(responseData.hiddencollection).css('pointer-events', 'none');
                                jQuery(responseData.hiddencollection).attr("checked", false);
                            } 
                            if (responseData.hiddenmetaltype) {
                                jQuery(responseData.hiddenmetaltype).css('opacity', 0.5);
                                jQuery(responseData.hiddenmetaltype).css('pointer-events', 'none');
                            }
                        }
                    });
                });
                jQuery("#gemfind-loading-mask").hide();
            },
            error: function(xhr, status, errorThrown) {
                console.log('Error happens. Try again.');
                console.log(errorThrown);
            }
        });
}
function createnav(){
    // jQuery.cookie("_wp_ringsetting", '', {
    //         path: '/',
    //         expires: -1
    //     });
    function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

}
function slider_update_textbox(slider_inputs,slidername){
    // Listen to keydown events on the input field.    
    slider_inputs.forEach(function (input, handle) {
        input.change(function () {
            var vals = parseFloat(this.value);
            if(handle){
                slidername.noUiSlider.set([null, vals]);
            } else {
                slidername.noUiSlider.set([vals, null]);
            }
            jQuery("#search-rings-form #submit").trigger("click");
        });
    });
}


