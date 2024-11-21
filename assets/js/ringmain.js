jQuery(document).ready(function() {
            jQuery('#search-rings-form #submitby').val('ajax');
                jQuery('form#search-rings-form').submit(function (e) {
                    e.preventDefault();
                    var selected_shape = jQuery("#shapeul .selected input[type='checkbox']").val();
                    var filter_type = jQuery(".filter-left").children('.active').attr('id');
                    jQuery("#selected_shape").val(selected_shape);
                    jQuery.ajax({
                        url: myajax.ajaxurl,
                        data: {action: 'ringsearch', 'filter_type': filter_type, filter_data: jQuery('#search-rings-form').serializeArray()},
                        type: 'POST',
                        dataType: 'html',
                        cache: true,
                        beforeSend: function(settings) {
                            if (jQuery(".placeholder-content").length == 0){
                                jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                            }
                        },
                        success: function(response) {
                            //console.log(response);
                            jQuery(".placeholder-content").remove();
                            jQuery('.result').html(response);
                            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                               if ((jQuery('div.number-of-search strong').html() < 20) && (jQuery('#currentpage').val() > 1)) {
                                jQuery('#currentpage').val(1);
                                jQuery("#search-rings-form #submit").trigger("click");
                            }

                            var mode = jQuery("input#viewmode").val();
                            if (mode == 'grid') {
                                jQuery('li.grid-view a').addClass('active');
                                jQuery('li.grid-view-wide a').removeClass('active');
                                jQuery('#grid-mode-wide').addClass('cls-for-hide');
                                jQuery('#grid-mode, #gridview-orderby, div.grid-view-sort').removeClass('cls-for-hide');
                            }
                            jQuery('.change-view-result li a').click(function() {
                                jQuery(this).addClass('active');
                                if (jQuery(this).parent('li').attr('class') == 'grid-view-wide') {
                                    jQuery('li.grid-view a').removeClass('active');
                                    jQuery('#grid-mode-wide').removeClass('cls-for-hide');
                                    jQuery('#grid-mode').addClass('cls-for-hide');
                                    jQuery("input#viewmode").val('list');
                                } else {
                                    jQuery('li.grid-view-wide a').removeClass('active');
                                    jQuery('#grid-mode-wide').addClass('cls-for-hide');
                                    jQuery('#grid-mode').removeClass('cls-for-hide');
                                    jQuery("input#viewmode").val('grid');
                                }
                            });
                            jQuery(document).click(function(e) {
                                jQuery(".search-product-grid .product-inner-info").removeClass('active');
                            });
                            jQuery("#pagesize option").each(function() {
                                if (jQuery(this).val() == jQuery("input#itemperpage").val()) {
                                    jQuery(this).attr("selected", "selected");
                                    return;
                                }
                            });
                            jQuery("#gridview-orderby option").each(function() {
                                //alert("something");
                                if (jQuery(this).val() == jQuery("input#orderby").val()) {
                                    jQuery(this).attr("selected", "selected");
                                    return;
                                }
                            });
                            jQuery('#searchdidfield').keydown(function(e) {
                                if (e.keyCode == 13) {
                                    jQuery('#searchsettingid').trigger('click');
                                }
                            });
                            jQuery('#searchsettingid').click(function(){
                                if(jQuery('#searchdidfield').val() !=""){
                                    jQuery('input#settingid').val(jQuery('#searchdidfield').val().trim());
                                    jQuery("#search-rings-form #submit").trigger("click");
                                } else {
                                    jQuery('input#searchdidfield').effect("highlight", {color: '#f56666'}, 2000);
                                    return false;
                                }
                            });
                            if(jQuery('input#settingid').val()){
                                jQuery('#searchintable').addClass('executed');
                            }
                            jQuery('#searchdidfield').val(jQuery('input#settingid').val());
                            //jQuery('input#settingid').val('');
                            jQuery('#resetsearchdata').click(function(){
                               jQuery('#searchdidfield').val();
                               jQuery('input#settingid').val('');
                               jQuery("#search-rings-form #submit").trigger("click");
                            });
                            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                       },
                        error: function(xhr, status, errorThrown) {
                            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                            console.log('Error happens. Try again.');
                            console.log(errorThrown);
                        }
                    });
                });      
            });

function ring_search(){          
            var filter_type = jQuery(".filter-left").children('.active').attr('id');
            jQuery.ajax({
            type: "POST",
            url: myajax.ajaxurl,
            data: {
                action: 'ringsearch', 'filter_type': filter_type, filter_data: jQuery('#search-rings-form').serializeArray()
            },
            cache: true,
            beforeSend: function(settings) {
                if (jQuery(".placeholder-content").length == 0){
                    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                }
            },
            success: function(response) {
            // console.log(response);
            jQuery(".placeholder-content").remove();
            jQuery('.result').html(response);
            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
            }
        });
}
function ItemPerPage(selectObject){    
    var resultperpage = document.getElementById("itemperpage").value;
    var selectedvalue = selectObject.value;
    resultperpage = selectedvalue;
    document.getElementById("itemperpage").value = selectedvalue;
    document.getElementById("currentpage").value = 1;
    document.getElementById("submit").click();
}
function PagerClick(intpageNo) {
   document.getElementById("currentpage").value = intpageNo;
   document.getElementById("settingid").value = document.getElementById("searchdidfield").value;
   document.getElementById("submit").click();
   console.log('testing');
}
function gridSort(selectObject) {
    var orderBy = document.getElementById("orderby").value;
    var selectedvalue = selectObject.value;
    orderBy = selectedvalue;
    document.getElementById("orderby").value = selectedvalue;
    document.getElementById("currentpage").value = 1;
    document.getElementById("submit").click();
}
function moderb(targetid) {    
        var id = targetid.id;  
        console.log(id); 
        var items = document.getElementById("navbar").getElementsByTagName("li");
        for (var i = 0; i < items.length; ++i) {
          items[i].className = "";
        }
        document.getElementById(id).className = "active";
        if(id != 'navcompare')
        document.getElementById("filtermode").value = id;
}

function SetBackValue(diamondid, requesteddata) {
        var ringcollection = jQuery("input[name='ring_collection']");
        var ringcollectionList = [];
        ringcollection.each(function() {
            if (this.checked === true) {
                ringcollectionList.push(jQuery(this).val());
            }
        });
        var ringshapeCheckboxes = jQuery("input[name='ring_shape[]']");
        var ringshapeList = [];
        ringshapeCheckboxes.each(function() {
            if (this.checked === true) {
                ringshapeList.push(jQuery(this).val());
            }
        });
        var ringmetalCheckboxes = jQuery("input[name='ring_metal']");
        var ringmetalList = [];
        ringmetalCheckboxes.each(function() {
            if (this.checked === true) {
                ringmetalList.push(jQuery(this).val());
            }
        });
        var PriceMin = jQuery("div#price_slider input.slider-left").val();
        var PriceMax = jQuery("div#price_slider input.slider-right").val();
        var orderBy = jQuery("input#orderby").val();
        var direction = jQuery("input#direction").val();
        var currentPage = jQuery("input#currentpage").val();
        var itemperpage = jQuery("input#itemperpage").val();
        var filtermode = jQuery("input#filtermode").val();
        var settingid = jQuery("input#settingid").val();
        var formdata = {
            'shapeList': ringshapeList.toString(),
            'ringcollection': ringcollectionList.toString(),
            'ringmetalList': ringmetalList.toString(),
            'PriceMin': PriceMin,
            'PriceMax': PriceMax,
            'Filtermode': filtermode,
            'currentPage': currentPage,
            'orderBy': orderBy,
            'direction': direction,
            'itemperpage': itemperpage,
            'SID': settingid,
        };
        var expire = new Date();
        expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
            jQuery.cookie("_wpsaveringbackvalue", JSON.stringify(formdata), {
                path: '/',
                expires: expire
            });
       jQuery.ajax({
                url: myajax.ajaxurl,
                data: {action: ringurl, id: diamondid, requesteddata: requesteddata},
                type: 'POST',
                //dataType: 'json',
                //cache: true,
                beforeSend: function(settings) {
                    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                },
                success: function(response) {
                    var responseData = jQuery.parseJSON(response);
                    console.log(responseData.diamondviewurl);
                    if(responseData.diamondviewurl == ''){
                        alert('Something went wrong. Please try again later.');
                        console.log('Something is wrong with Mounting detail API, please try after some time!');
                        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                    } else { 
                        window.location.href = responseData.diamondviewurl;
                        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                    }
                },
                error: function(xhr, status, errorThrown) {
                    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                    console.log('Error happens. Try again.');
                    console.log(errorThrown);
                }
            });
}

function SaveFilterRb() {
        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
        var ringcollection = jQuery("input[name='ring_collection']");
        var ringcollectionList = [];
        ringcollection.each(function() {
            if (this.checked === true) {
               ringcollectionList.push(jQuery(this).val());
           }
        });
        var ringshapeCheckboxes = jQuery("input[name='ring_shape']");
        var ringshapeList = [];
        ringshapeCheckboxes.each(function() {
           if (this.checked === true) {
                ringshapeList.push(jQuery(this).val());
            }
        });
       var ringmetalCheckboxes = jQuery("input[name='ring_metal']");
       var ringmetalList = [];
        ringmetalCheckboxes.each(function() {
            if (this.checked === true) {
                ringmetalList.push(jQuery(this).val());
            }
        });
        var PriceMin = jQuery("div#price_slider input.slider-left").val();
        var PriceMax = jQuery("div#price_slider input.slider-right").val();
        var orderBy = jQuery("input#orderby").val();
        var direction = jQuery("input#direction").val();
        var currentPage = jQuery("input#currentpage").val();
        var itemperpage = jQuery("input#itemperpage").val();
        var filtermode = jQuery("input#filtermode").val();
        var settingid = jQuery("input#settingid").val();
        var formdata = {
            'shapeList': ringshapeList.toString(),
            'ringcollection': ringcollectionList.toString(),
            'ringmetalList': ringmetalList.toString(),
            'PriceMin': PriceMin,
            'PriceMax': PriceMax,
            'Filtermode': filtermode,
            'currentPage': currentPage,
            'orderBy': orderBy,
            'direction': direction,
            'itemperpage': itemperpage,
            'SID': settingid,
        };
        var expire = new Date();
        expire.setDate(expire.getDate() + 10 * 24 * 60 * 60 * 1000);
            jQuery.cookie("_wpsaveringfiltercookie", JSON.stringify(formdata), {
               path: '/',
               expires: expire
            });
       setTimeout(
            function() {
                jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
            }, 400);
}

function ResetFilterRb() {
    if(confirm("Are you sure you want to reset the setting?")){
        jQuery.cookie("_wpsaveringfiltercookie", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("_wpsaveringbackvalue", '', {
            path: '/',
            expires: -1
        });
        jQuery.cookie("_wp_diamondsetting", '', {
            path: '/',
            expires: -1
        });

        if (window.location.href.indexOf('?') > -1) {
        window.location.href = window.location.pathname;
        }
        else{
            window.location.href = window.location.pathname;
        }
    }
}

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

function serializeFormJSON(id){
    var o = {};
    var a = jQuery("#"+id).serializeArray();
    jQuery.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
}