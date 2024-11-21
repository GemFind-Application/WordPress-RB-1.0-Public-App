function ringFormSubmit(e,url,id){  
  if (window.sitekey && !document.getElementsByClassName('g-recaptcha') ) {
            alert('Something went wrong with captcha..!');
            return false;
        }
    if( id == 'form-drop-hint' ) {
        var action = 'resultdrophint_rb';
    } else if( id == 'form-email-friend' ) {
        var action = 'resultemailfriend_rb';
    } else if( id == 'form-request-info' )  {
        var action = 'resultreqinfo1_rb';                
    } else if( id == 'form-schedule-view' ) {
        var action = 'resultscheview_rb';
    }
     else if( id == 'form-request-info-cr' ) {
        var action = 'resultreqinfo1_cr';
    }
     else if( id == 'form-schedule-view-cr' ) {
        var action = 'resultscheview_cr';
    }

    var dataFormHint = jQuery('#'+id);
    dataFormHint.validate({
        rules: {        
          name: {
            required: true
        },
        email:{
            required: true,
            emailcustom:true,
        },
        recipient_name:{
            required: true
        },
        recipient_email:{
            required: true,
            emailcustom:true,
        },
        gift_reason:{
            required: true
        },
        hint_message:{
            required: true
        },
        friend_name:{
            required: true
        },
        friend_email:{
            required: true,
            emailcustom:true,
        },
        message:{
            required: true
        },
        gift_deadline:{
            required: true,

        },
        phone:{
            required: true,
            phoneno: true
        },
        location:{
            required: true,

        },
        avail_date:{
            required: true,
        },
        appnt_time:{
            required: true,
        },
        contact_pref:{
            required: true,
        }
    },
    messages: {
        gift_deadline: "Select the Gift Deadline.",
        avail_date: "Select your availability.",
        contact_pref: "Please select one of the options.",
    },

    errorPlacement: function(error, element) 
    {
        if ( element.is(":radio") ) 
        {
            error.appendTo( element.parents('.pref_container') );
        }
        else 
        { // This is the default behavior 
            error.insertAfter( element );
        }
    },
    submitHandler: function(form) {
        if(window.sitekey){
            validate(e);   
        }

      
        // console.log(captchaval);
        // return false;
        jQuery.ajax({
            type: 'POST',
            url: url,
            data: {'action': action, 'form_data': jQuery('#'+id).serializeArray()},
            dataType: 'json',
            beforeSend: function(settings) {
                jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
            },
            success: function(response) {                         
                console.log(response);
                if(response.output.status == 1){
                    console.log('email send');
                    var parId = jQuery('#' + id).parent().attr('id');                                
                    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');                                
                    jQuery('#popup-modal .note').html(response.output.msg);
                    jQuery('#popup-modal .note').css('display', 'block');
                    jQuery('#popup-modal .note').css('color', 'green');                                
                    jQuery("#popup-modal").modal('show');
                    jQuery('#popup-modal').on('hidden.bs.modal', function () {
                        console.log('close modal');
                        jQuery('.cancel.preference-btn').click();
                    }); 
                    setTimeout(function(){ jQuery('#' + parId + ' .note').html(''); jQuery('#' + parId + ' .note').css('display', 'none'); jQuery('#' + parId + ' .note').css('background', '#fff');}, 5000);
                } else {
                    console.log('some error');
                    var parId = jQuery('#' + id).parent().attr('id');
                    //jQuery('#' + parId + ' .note').html(response.output.msg);
                    jQuery('#popup-modal .note').html(response.output.msg);
                    jQuery('#popup-modal .modal-title').html('Error');
                    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                    jQuery('#popup-modal .note').css('display', 'block');
                    jQuery('#popup-modal .note').css('color', 'red');
                    jQuery('#popup-modal .note').css('background', '#f7c6c6');
                    jQuery("#popup-modal").modal('show');
                    jQuery('#popup-modal').on('hidden.bs.modal', function () {
                        console.log('close modal');
                        jQuery('.cancel.preference-btn').click();
                    }); 
                    setTimeout(function(){ jQuery('#' + parId + ' .note').html(''); jQuery('#' + parId + ' .note').css('display', 'none'); jQuery('#' + parId + ' .note').css('background', '#fff');}, 5000);
                }
                document.getElementById(id).reset();
                return true;
            }
        });
    }
});

    jQuery.validator.addMethod("emailcustom",function(value,element) {
        return this.optional(element) || /^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/i.test(value);
    },"Please enter valid email address");

    jQuery.validator.addMethod("phoneno", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 && 
        phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
    }, "<br />Please specify a valid phone number");
}

function CallSpecification() {
    document.getElementById("ring-data").style.display = "none";
    document.getElementById("ring-specification").style.display = "block";
    
}
function CallSpecificationdb() {
    document.getElementById("ring-data").style.display = "none";
    //document.getElementsByClassName("diamonds-preview").style.display = "none";
    document.getElementById("diamond-specification-db").style.display = "block";
    
}


function changemetal(object, site_url) {
    /*
       var metal = $('#metal_type').val();
       
       var quality = $('#stonequality').val();
       if(quality){
            var ringid = $('#'+metal+quality).val();
       } else {
            var ringid = $('#'+metal).val();
       //}*/
       
       //window.location.href = window.location.origin +'/apps/ringbuilder/settings/view/path/'+metal+ringid;

       var metal = jQuery('#metal_type').val();
       var urlstring = jQuery('#metal_type').find('option:selected').attr('data-id');
       window.location.href = site_url +'/ringbuilder/settings/product/'+urlstring+'-sku-'+metal;
    
}


function changequality(object, site_url) {
       var quality = jQuery('#stonequality').val();
       console.log(quality);
       var urlstring = jQuery('#metal_type').find('option:selected').attr('data-id');
       window.location.href = site_url +'/ringbuilder/settings/product/'+urlstring+'-sku-'+quality;
}


function changecenterstone(object, site_url) {
       var centerstone = jQuery('#centerstonesize').val();
       var urlstring = jQuery('#metal_type').find('option:selected').attr('data-id');
       window.location.href = site_url +'/ringbuilder/settings/product/'+urlstring+'-sku-'+centerstone;
}


function updatesize() {
    
       var ring_size = jQuery('#ring_size').val();
       jQuery('#ringsizesettingonly').val(ring_size);
       jQuery('#ringsizewithdia').val(ring_size);
    
}

 function changeRingSize(event){
    var ring_size = jQuery('#ring_size').val();
    console.log(ring_size);
    if(ring_size == 0 ){
        alert('Please Select Ring Size');
        event.preventDefault();
    }
    
 }


function CallDiamondDetail() {
    document.getElementById("ring-data").style.display = "block";
    document.getElementById("ring-content-data").style.display = "block";
    document.getElementById("ring-specification").style.display = "none";
    var el1 = document.getElementById("drop-hint-main");
    if(el1){
        el1.style.display = "none";    
        document.getElementById("form-drop-hint").reset();
    }
    var el2 = document.getElementById("email-friend-main");
    if(el2){
        el2.style.display = "none";    
        document.getElementById("form-email-friend").reset();
    }
    var el3 = document.getElementById("req-info-main");
    if(el3){
        el3.style.display = "none";    
        document.getElementById("form-request-info").reset();
    }
    var el4 = document.getElementById("schedule-view-main");
    if(el4){
        el4.style.display = "none";    
        document.getElementById("form-schedule-view").reset();
    }
    var el5 = document.getElementById("req-info-main-cr");
    if(el5){
        el5.style.display = "none";    
        document.getElementById("form-request-info-cr").reset();
    }
    var el6 = document.getElementById("schedule-view-main-cr");
    if(el6){
        el5.style.display = "none";    
        document.getElementById("form-schedule-view-cr").reset();
    }
}

function CallDiamondDetaildb() {
    document.getElementById("ring-data").style.display = "block";
    document.getElementById("ring-content-data").style.display = "block";
    document.getElementById("diamond-data").style.display = "block";
    //document.getElementsByClassName("diamonds-preview").style.display = "block";
    document.getElementById("diamond-specification-db").style.display = "none";
    var el1 = document.getElementById("drop-hint-main");
    if(el1){
        el1.style.display = "none";    
        document.getElementById("form-drop-hint").reset();
    }
    var el2 = document.getElementById("email-friend-main");
    if(el2){
        el2.style.display = "none";    
        document.getElementById("form-email-friend").reset();
    }
    var el3 = document.getElementById("req-info-main");
    if(el3){
        el3.style.display = "none";    
        document.getElementById("form-request-info").reset();
    }
    var el4 = document.getElementById("schedule-view-main");
    if(el4){
        el4.style.display = "none";    
        document.getElementById("form-schedule-view").reset();
    }
    var el5 = document.getElementById("req-info-main-cr");
    if(el5){
        el5.style.display = "none";    
        document.getElementById("form-request-info-cr").reset();
    }
    var el6 = document.getElementById("schedule-view-main-cr");
    if(el6){
        el5.style.display = "none";    
        document.getElementById("form-schedule-view-cr").reset();
    }
}



function CallShowform(e) {
    document.getElementById("ring-specification").style.display = "none";
    var el1 = document.getElementById("drop-hint-main");
    if(el1){
        el1.style.display = "none";    
        document.getElementById("form-drop-hint").reset();
    }
    var el2 = document.getElementById("email-friend-main");
    if(el2){
        el2.style.display = "none";    
        document.getElementById("form-email-friend").reset();
    }
    var el3 = document.getElementById("req-info-main");
    if(el3){
        el3.style.display = "none";    
        document.getElementById("form-request-info").reset();

    }
    var el4 = document.getElementById("schedule-view-main");
    if(el4){
        el4.style.display = "none";    
        document.getElementById("form-schedule-view").reset();
    }
    var el5 = document.getElementById("req-info-main-cr");
    if(el5){
        el5.style.display = "none";    
        document.getElementById("form-request-info-cr").reset();
    }
    var el6 = document.getElementById("schedule-view-main-cr");
    if(el6){
        el6.style.display = "none";    
        document.getElementById("form-schedule-view-cr").reset();
    }

    document.getElementById("ring-content-data").style.display = "none";
    if(document.getElementById("diamond-content-data")){
        document.getElementById("diamond-content-data").style.display = "none";
    }
    var x = e.target.getAttribute("data-target");
    document.getElementById(x).style.display = "block";
    
        jQuery("form input").parent().removeClass('moveUp');
        jQuery("form input").nextAll('span').removeClass('moveUp');
        if (jQuery("body").hasClass("ringbuilder-diamond-completering") == true) {
            document.getElementById("ring-data").style.display = "block";
        }
            jQuery('#gift_deadline').datepicker({minDate: 0});
            //jQuery('#avail_date').datepicker({minDate: 0});
            function appoitmentTime(start,stop) {
                times='';   stopAMPM=stop;  interval=30;
                start=start.split(":");
                starth=parseInt(start[0]);
                startm=((parseInt(start[1])) ? parseInt(start[1]) : '0');
                stop=stop.split(":");
                stopAMPM = stopAMPM.slice(-2);
                stoph=((stopAMPM.trim()==="PM" && (stop[0]!="12" && stop[0]!="12 PM")) ? (+parseInt(stop[0].replace(":", "")) + (+12)) : parseInt(stop[0]));
                stopm=((parseInt(stop[1])) ? parseInt(stop[1]) : '0');
                size= stoph>starth ? stoph-starth+1 : starth-stoph+1
                hours=[...Array(size).keys()].map(i => i + starth);
                option='';
                for (hour in hours) {
                    for (min = startm; min < 60; min += interval)  {
                        startm=0
                        if ((hours.slice(-1)[0] === hours[hour]) && (min > stopm)) {
                            break;
                        }
                        if (hours[hour] > 11 && hours[hour] !== 24 ) {
                            times=('0' + (hours[hour]%12 === 0 ? '12': hours[hour]%12)).slice(-2) + ':' + ('0' + min).slice(-2) + " " + 'PM';
                        } else {
                            times=('0' +  (hours[hour]%12 === 0 ? '12': hours[hour]%12)).slice(-2) + ':' + ('0' + min).slice(-2) + " " + 'AM';
                        }
                    option += "<option value='"+times+"'>"+times+"</option>";
                    }
                }
                return option;
            }

            jQuery('#avail_date').datepicker(
            {
                minDate: 0,  
                onSelect: function(dateText) {
                    var curDate = jQuery(this).datepicker('getDate');
                    var dayName = jQuery.datepicker.formatDate('DD', curDate);
                    if(jQuery(".timing_days").length){
                        var timingDays = jQuery.parseJSON(jQuery(".timing_days.active").html());
                        var dayId;
                        if(dayName == "Sunday")
                        {
                            dayId = 0;
                        }
                        else if(dayName == "Monday")
                        {
                            dayId = 1;
                        }
                        else if(dayName == "Tuesday")
                        {
                            dayId = 2;
                        }
                        else if(dayName == "Wednesday")
                        {
                            dayId = 3;
                        }
                        else if(dayName == "Thursday")
                        {
                            dayId = 4;
                        }
                        else if(dayName == "Friday")
                        {
                            dayId = 5;
                        }
                        else 
                        {
                            dayId = 6;
                        }
                        jQuery.each(timingDays, function( index, value ) { 
                            if(dayId == index)
                            {
                                var key = Object.keys(value);
                                if(index == 0) {
                                    option = appoitmentTime(value.sundayStart,value.sundayEnd);
                                }
                                else if(index == 1) {
                                    option = appoitmentTime(value.mondayStart,value.mondayEnd);
                                }
                                else if(index == 2) {
                                    option = appoitmentTime(value.tuesdayStart,value.tuesdayEnd);
                                }
                                else if(index == 3) {
                                    option = appoitmentTime(value.wednesdayStart,value.wednesdayEnd);                                    
                                }
                                else if(index == 4) {
                                    option = appoitmentTime(value.thursdayStart,value.thursdayEnd);                                    
                                }
                                else if(index == 5) {
                                    option = appoitmentTime(value.fridayStart,value.fridayEnd);
                                }
                                else if(index == 6) {
                                    option = appoitmentTime(value.saturdayStart,value.saturdayEnd);                                    
                                }
                                jQuery("#appnt_time").html(option);
                            }
                        });   
                        jQuery("#appnt_time").show();   
                    } else { 
                        jQuery(".timing_not_avail").show();
                        jQuery(".book-slots").prop("disabled", true);
                    }

                     
                },
                beforeShowDay: function(d) {
                    var day = d.getDay();
                    var closeDay = [];
                    var myarray = [];
                    var timingDays = jQuery.parseJSON(jQuery(".timing_days.active").html());
                    jQuery.each(timingDays, function( index, value ) {
                            var key = Object.keys(value);
                            if(index == 0) {
                                if(value.sundayStart == '' || value.sundayEnd == ''){
                                   closeDay.push(index);
                                }  
                            }
                            else if(index == 1) {
                                if(value.mondayStart == '' || value.mondayEnd == ''){
                                    closeDay.push(index);
                                }
                            }
                            else if(index == 2) {
                                if(value.tuesdayStart == '' || value.tuesdayEnd == ''){
                                    closeDay.push(index);
                                }
                            }
                            else if(index == 3) {
                                if(value.wednesdayStart == '' || value.wednesdayEnd == ''){
                                    closeDay.push(index);
                                }
                            }
                            else if(index == 4) {
                                if(value.thursdayStart == '' || value.thursdayEnd == ''){
                                    closeDay.push(index);
                                }
                            }
                            else if(index == 5) {
                                if(value.fridayStart == '' || value.fridayEnd == ''){
                                    closeDay.push(index);
                                }
                            }
                            else if(index == 6) {
                                if(value.saturdayStart == '' || value.saturdayEnd == ''){
                                    closeDay.push(index);
                                }
                            }
                    });
                    jQuery(".day_status_arr").each(function() {
                        myarray.push(jQuery(this).html());
                        closeDay.push(parseInt(jQuery(this).html()));
                    });
                    if(jQuery.inArray(day, closeDay) != -1) {
                        return [ false, "closed", "Closed on Monday" ];
                        //return [ true, "", "" ];
                    } else {
                        return [ true, "", "" ];
                    } 
                }
            });
            
            jQuery("#schview_loc").on('change', function (e) {
                  $locationid = jQuery(this).find(':selected').attr("data-locationid");
                  if(jQuery('#avail_date').val() != "")
                  {
                    jQuery('#avail_date').datepicker('setDate', null);
                  }
                  jQuery(".timing_days").removeClass("active");
                  jQuery(".timing_days").each(function( index ) {
                      if(jQuery( this ).attr("data-location") == $locationid)
                      {
                        jQuery(this).addClass("active");
                        return false;
                      }
                  });
            });
    
}

 function Videorun() {
    jQuery("#setting_iframevideo").removeAttr("src");
    jQuery("#setting_mp4video").removeAttr("src");
    jQuery("#setting_mp4video").attr("src", '');
    jQuery('#SettingModal').modal('show');
    jQuery('.loader_rb').show();
   var divid = jQuery(event.currentTarget).data('id');
    // alert(divid);
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
                console.log(response);
    
                if (response.showVideo == true) {
                    var fileExtension = response.videoURL.replace(/^.*\./, '');
                    console.log (fileExtension);
                    if(fileExtension=="mp4"){
                        jQuery('#setting_iframevideo').hide();
                        setTimeout(function() {
                           jQuery("#setting_mp4video").attr("src", response.videoURL);
                           jQuery('.loader_rb').hide();
                           jQuery('#setting_mp4video').get(0).play();
                        }, 3000);
                    }
                    else{
                        jQuery('#setting_mp4video').hide();
                        setTimeout(function() {
                            jQuery("#setting_iframevideo").attr("src", response.videoURL);
                            jQuery('.loader_rb').hide();
                            jQuery('#setting_iframevideo').show();
                        }, 3000);
                    }
                }   
            }
        });
        jQuery(".Rbclose").click(function() {
        jQuery('#SettingModal').modal('hide');
        });
}
    

function Imageswitch1(e){
        
            jQuery('#ringimg img').attr('src', jQuery('#ringimg img').attr('data-src'));
            jQuery('#hidden-content img').attr('src', jQuery('#ringimg img').attr('data-src'));
            document.getElementById("ringimg").style.display = "block";
            // document.getElementById("ringvideo").style.display = "none";
        
}

function Imageswitch2(id){
            var src = jQuery('#'+id).attr('src');
            jQuery('#ringimg img').attr('src', src);
            jQuery('#hidden-content img').attr('src', src);
            document.getElementById("ringimg").style.display = "block";
            // document.getElementById("ringvideo").style.display = "none";       
       
}

function Closeform(e){
        
        var x = e.target.getAttribute("data-target");
        var el1 = document.getElementById("form-drop-hint");
        if(el1){  
            el1.reset();
            jQuery('#form-drop-hint label.error').remove();
        }
        var el2 = document.getElementById("form-email-friend");
        if(el2){  
            el2.reset();
            jQuery('#form-email-friend label.error').remove();
        }
        var el3 = document.getElementById("form-request-info");
        if(el3){  
            el3.reset();
            jQuery('#form-request-info label.error').remove();
        }
        var el4 = document.getElementById("form-schedule-view");
        if(el4){  
            el4.reset();
            jQuery('#form-schedule-view label.error').remove();
        }
        var el5 = document.getElementById("form-request-info-cr");
        if(el5){  
            el5.reset();
            jQuery('#form-request-info-cr label.error').remove();
        }
        var el6 = document.getElementById("form-schedule-view-cr");
        if(el6){  
            el6.reset();
            jQuery('#form-schedule-view-cr label.error').remove();
        }
        document.getElementById(x).style.display = "none";
        document.getElementById("ring-content-data").style.display = "block";
        if(document.getElementById("diamond-content-data")){
            document.getElementById("diamond-content-data").style.display = "block";
        }
}

function focusFunction(e){
    
        // var form = jQuery(e).closest('form')[0];
        // jQuery("form#"+form.id+" :input").bind("change blur",function(){
        //     jQuery("form#"+form.id+" :input").parent().addClass('moveUp');
        //     jQuery("form#"+form.id+" :input").nextAll('span').addClass('moveUp'); 
        // });
        if(!e.value){
            jQuery(e).parent().addClass('moveUp');
            jQuery(e).nextAll('span:first').addClass('moveUp'); 
        }    
    
}

function focusoutFunction(e){
        console.log(e);
        if(!e.value){
            jQuery(e).parent().removeClass('moveUp');
            jQuery(e).nextAll('span:first').removeClass('moveUp');
        }
    
}
function add_diamondtoring(){
    // var formdata = serializeFormJSON("add_diamondtoring_form");
    // var ringData = {
    //         'settingid': formdata.settingId,
    //         'ringsize': formdata.ringsizewithdia,
    //         'shapes': formdata.settingId,
    //         'caratmax': formdata.ringmaxcarat,
    //         'caratmin': formdata.ringmincarat,
    //         'centerstonefit': formdata.centerStoneFit.toLowerCase(),
    //     };
    // jQuery.cookie("ringsetting", JSON.stringify(ringData), {
    //     path: '/',
    //     expires: 86400
    // });
    // var diamondcookiedata = getCookie('diamondsetting');
    // if(diamondcookiedata){
    //     window.location.href = formdata.shopurl+'/ringbuilder/settings/completering';
    // }else{
    //     window.location.href = formdata.shopurl+'/ringbuilder/diamondlink';
    // }
    // return false;
} 