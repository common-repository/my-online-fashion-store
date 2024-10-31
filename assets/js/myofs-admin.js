/*activation keys*/
var ajaxurl,start_notice_html,start_error_html,end_notice_html;
jQuery(document).ready(function () {
    start_notice_html = '<div id="message" class="updated notice is-dismissible "><p>';
    start_error_html  = '<div id="message" class="error notice is-dismissible "><p>';
    end_notice_html   = '</p><button type="button" class="notice-dismiss close"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    ajaxurl           = jQuery("#admin-url").val(); 
    activationKey();
    //removeActivationKey();// remove key
    helpSectionAccordian();//help page
    returnSectionFormSubmit()//free return page
    sidebarCollapse();
   
    //Start of Zendesk Chat Script
    window.$zopim||(function(d,s){var z=$zopim=function(c){
    z._.push(c)},$=z.s=
    d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
    _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
    $.src='https://v2.zopim.com/?56r3e3RghhlpmibJXaql9NrnRZeI6pPW';z.t=+new Date;$.
    type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
    // End of Zendesk Chat Script
});

function activationKey(){
    jQuery("#myofs-activate-keys").validate({
        rules: {
            activation_email: {
                required: true,
                email: true
            } ,
            activation_key: "required"     
        },
        messages: {
            activation_email: {
                required: "This field is required",
                email: "Please enter a valid email address"
            },                   
            activation_key: "This field is required"
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            jQuery('#myofs-layout__notice-list').html();
            var $form     = jQuery(form);            
            var form_data = $form.serialize();
            jQuery.ajax({
                type:"POST",
                url: ajaxurl,
                dataType: "json",
                data: form_data,
                beforeSend: function() {
                    jQuery(".spin_main").css('display','block');
                },
                success: function (response) {
                    jQuery(".spin_main").removeAttr('style');
                    jQuery(".spin_main").css('display','none');
                    if (response.status == 1) {
                        jQuery('#myofs-layout__notice-list').html(start_notice_html + response.success + end_notice_html);
                        location.reload();                   
                    }else{
                        jQuery('#myofs-layout__notice-list').html(start_error_html + response.error + end_notice_html);
                    }
                },
                error: function (errorThrown) {}
            });
        }
    });
}

//Help Section accordion
function helpSectionAccordian(){
   jQuery(".acc-set > h4").on("click", function() {
        if (jQuery(this).hasClass("active")) {
          jQuery(this).removeClass("active");
          jQuery(this).siblings(".content").slideUp(200);
          jQuery(".acc-set > h4 i").removeClass("fa-minus").addClass("fa-plus");
        } else {
          jQuery(".acc-set > h4 i").removeClass("fa-minus").addClass("fa-plus");
          jQuery(this).find("i").removeClass("fa-plus").addClass("fa-minus");
          jQuery(".acc-set > h4").removeClass("active");
          jQuery(this).addClass("active");
          jQuery(".content").slideUp(200);
          jQuery(this).siblings(".content").slideDown(200);
        }
    }); 
}

function returnSectionFormSubmit(){
    /*return page*/        
    jQuery("#free_returns").validate({
        rules: {
            contactperson: "required",
            email: "required",
            ordernumber: "required",
            itemqty: "required",
            reasonreturn: "required"        
        },
        messages: {
            contactperson: "This field is required",                   
            email: "This field is required",                   
            ordernumber: "This field is required",                   
            itemqty: "This field is required",                   
            reasonreturn: "This field is required"                   
            
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
          
            var $form = jQuery(form);            
            var serializedData = $form.serialize();                      
            jQuery.ajax({
               type: 'POST',
               url: ajaxurl,
               async: false,
               data: serializedData + "&action=submitformreturndata",
               dataType: "json",
               beforeSend: function() {
                  jQuery(".spin_main").css('display','inline-block');
               },
               success: function (response) {
                  jQuery(".spin_main").removeAttr('style');
                  jQuery(".spin_main").css('display','none');
                  if (response.status == 1) {
                    jQuery('#myofs-layout__notice-list').empty().html(start_notice_html + response.success + end_errnotice_html);
                    jQuery('#free_returns')[0].reset();
                  }else{
                    jQuery('#myofs-layout__notice-list').empty().html(start_error_html + response.error + end_errnotice_html);
                  }
                  jQuery('html,body').animate({
                      scrollTop: jQuery("#myofs-layout__notice-list").offset().top
                  },'slow');
               },
               error: function (errorThrown) {
                  jQuery('#myofs-layout__notice-list').empty().html(start_error_html + errorThrown + end_errnotice_html);
                  jQuery('html,body').animate({
                      scrollTop: jQuery("#myofs-layout__notice-list").offset().top
                  },'slow');
               }
           }); 

        }
    });
    /********RESET CLEAR FORM********/
    jQuery('#reset1').click(function(){
        jQuery('#free_returns')[0].reset();
    });
}
function sidebarslider(){
    //sidebar slider 
    jQuery('.LeftCarousel .LeftCarousel_inner').slick({
        dots: false,
        arrows: true,
        infinite: false,
        autoplay: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 500,
        responsive: [
            {
                breakpoint: 990,
                settings: { 
                   slidesToShow: 1,
                   slidesToScroll: 1
                }
            }, 
            {
                breakpoint: 767,
                settings: {
                   slidesToShow: 1,
                   slidesToScroll: 1
                }
            },
            {
                breakpoint: 479,
                settings: {
                   slidesToShow: 1,
                   slidesToScroll: 1
                }
            } 
        ]
    }); 
}
function sidebarCollapse(){ 
  // dislay or hide the menu if the user resize the window
  jQuery(window).resize(function() {
      var wi = jQuery(window).width();
      if (wi >= 1199) { 
          jQuery('#topsidebar-icon').css({'display':'none'});
          jQuery('#sidebar-collapse').css({'display':'block'});
          jQuery('ul.page-header-fixed').css({'display':'block'});
          jQuery('ul.page-header-fixed').removeClass('responsive_sidebar');
          sliderini_destory();      

      } else { 
          jQuery('#topsidebar-icon').css({'display':'block'});
          jQuery('#sidebar-collapse').css({'display':'none'});
          jQuery('ul.page-header-fixed').addClass('responsive_sidebar');
          jQuery('ul.page-header-fixed').css({'display':'none'});       
          sliderini_destory();   
      }
  });
  if(window.matchMedia("(max-width: 1199px)").matches){
      jQuery('#topsidebar-icon').css({'display':'block'});
      jQuery('#sidebar-collapse').css({'display':'none'});
      jQuery('ul.page-header-fixed').addClass('responsive_sidebar');
      jQuery('ul.page-header-fixed').css({'display':'none'});
      sliderini_destory();    
  } else{
      jQuery('#topsidebar-icon').css({'display':'none'});
      jQuery('#sidebar-collapse').css({'display':'block'});
      jQuery('ul.page-header-fixed').css({'display':'block'});
      jQuery('ul.page-header-fixed').removeClass('responsive_sidebar');
      sliderini_destory();  
  }
  //desktop
  jQuery("#sidebar-collapse").on("click", function() {
    if(window.matchMedia("(max-width: 1199px)").matches){
      /*jQuery('div.slick-slide').css('width','955px');
      jQuery('div.slick-track').css('width','8595px');
      jQuery('div.slick-track').css('transform','translate3d(0px, 0px, 0px)');*/
      sliderini_destory();      
    }
    if (jQuery('ul.page-sidebar-menu').hasClass('sidebar-collapse')) {
      jQuery('#myofs-sidebar').addClass('sidebar-menu-expand');
      jQuery('ul.page-sidebar-menu').removeClass('sidebar-collapse');
      jQuery('ul.page-sidebar-menu').addClass('sidebar-expand');
      jQuery(this).attr("aria-expanded","false");
      sliderini_destory();
    }else{
      jQuery('#myofs-sidebar').removeClass('sidebar-menu-expand');
      jQuery('ul.page-sidebar-menu').removeClass('sidebar-expand');
      jQuery('ul.page-sidebar-menu').addClass('sidebar-collapse');
      jQuery(this).attr("aria-expanded","true");
      sliderini_destory();
    }
  });
  /*responsive*/
  jQuery('#topsidebar-icon').click(function(){
    
    if (jQuery('ul.responsive_sidebar').css('display') == 'none') {
        jQuery('ul.responsive_sidebar').css({'display':'block'});
        jQuery(this).addClass('open-sidebar');
        jQuery('.tab-content .wrap').addClass('responsive-sidebar-open');
        sliderini_destory();        
    }else {
        jQuery('ul.responsive_sidebar').css({'display':'none'});
        jQuery(this).removeClass('open-sidebar');
        jQuery('.tab-content .wrap').removeClass('responsive-sidebar-open');
        sliderini_destory();
    }
  });
  
}
function sliderini_destory(){
  if (jQuery('.LeftCarousel .LeftCarousel_inner').hasClass('slick-initialized')) {
    jQuery('.LeftCarousel .LeftCarousel_inner').slick('destroy');
  }
  sidebarslider();
}


  
 