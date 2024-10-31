var baseurl,inventorytab,myinventorytab,category_id,sortby,limit,category_name,page,search,redirect_url,Base64,qryurl,start_notice_html,start_error_html,end_errnotice_html,proid_cnt,product_type,combain_product,ajaxurl,menu;
jQuery( document ).ready(function() {	
    ajaxurl        = jQuery("#admin-url").val();
    baseurl        = jQuery('#myofs_plugin_url').val();
    inventorytab   = baseurl+'&tab=all-inventory';
    myinventorytab = baseurl+'&tab=my-inventory';
	Base64  = {_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
	qryurl  = '';

	//jQuery('#page').val('1');
    limit       = jQuery("#limit").val();
    sortby      = jQuery("#sortby").val();
    category_id = jQuery("#category_id").val();        
    search      = jQuery("#search").val(); 
    if (search) {
        search  = Base64.encode(search);
    }else{
        search  = '';
    }
    page        = jQuery("#page").val();

    start_notice_html  = '<div id="message" class="updated notice is-dismissible "><p>';
	start_error_html   = '<div id="message" class="error notice is-dismissible "><p>';
	end_errnotice_html = '</p><button type="button" class="notice-dismiss close"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';


    inventorySidebarFilters();
    if (jQuery('.LeftCarousel .LeftCarousel_inner').hasClass('slick-initialized')) {
      jQuery('.LeftCarousel .LeftCarousel_inner').slick('destroy');
    }
    sidebarslider();
    categoryFilter();
    categorySubmenu();//submenu open close
    if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){

        detailProductDisplay();//detail product popup
        closeProductDetailDisplay();//close popup
        addProducToWCStoreDisplay();//add to product wc popup
        closeaddProducToWCStoreDisplay();//close popup
        selectUnselectProduct(); //products selection
        selectionDropdown();
        multipleProductAdd();// multiple product
        combineProductAdd();//combine product
        itemAddToWC();//item add to wc
        removeSingleProduct();//remove product
        removemultiProducts();// remove multiple all product
    } else if(jQuery('#myofs_myinvproductdta').hasClass('myofs_inventory')){
    	detailProductDisplay();//detail product popup
        closeProductDetailDisplay();//close popup                
        selectUnselectProduct(); //products selection
        selectionDropdown();
        removeSingleProduct();//remove product
        removemultiProducts();//remove multiple products
    }  
    
       
    /*remove success add popup*/
    jQuery('#snackbar .close').click(function () {
        jQuery('#snackbar').removeClass('show_spin');
        jQuery('#snackbar').html('<a class="close" href="javascript:void(0);">×</a><br>');
    });
    jQuery('.myofs-layout .close').click(function () {
        jQuery(this).parents().find('#message').remove();
        
    });
   
});

function inventorySidebarFilters(){
    
    jQuery('#limitbox').change(function() {
        limit = jQuery(this).val();       
        returnSuccessRedirect(limit,sortby,category_id,search,page);
    });
    //pagination
    jQuery('.myofs-pagination li.active').click(function () {

        page = jQuery(this).attr('p');
        returnSuccessRedirect(limit,sortby,category_id,search,page);
    }); 
    /*sortby*/
    jQuery('#filter_by').change(function() {
        sortby = jQuery(this).val();   

        returnSuccessRedirect(limit,sortby,category_id,search,page);             
        
    });
    // search input
    jQuery('#search_filter').click(function () {
        search = jQuery("#search").val(); 
        jQuery("#category_id").val('');
        category_id = jQuery("#category_id").val();  
        if (search != '') {            
            search = Base64.encode(search);
        	returnSuccessRedirect(limit,sortby,category_id,search,page);

        } else {
            var display = jQuery("#clear_search_inventory").css("display");
            if( display == 'block' ){
                jQuery('#clear_search_inventory').trigger('click');
            }
            jQuery("#search_inventory").focus();
        }
    });
    jQuery("#clear_search_inventory").click(function() {        
        jQuery(this).hide();      
        jQuery("#search").val('');
        search = jQuery("#search").val();            
        returnSuccessRedirect(limit,sortby,category_id,search,page);          
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
// category filter
function categoryFilter(){
    // category filter
    jQuery('.myofs-nav-item .title').click(function() {
        category_id   = jQuery(this).attr('id');
        category_name = jQuery(this).text();       

        jQuery("#search").val('');
        search =  jQuery("#search").val();
        qryurl = '';
        if (sortby.length > 0) {
            qryurl += 'sortby='+sortby+'&';
        }
        if (category_id.length > 0) {
            qryurl += 'category_id'+'='+category_id+'&'+'category_name'+'='+category_name+'&';
            qryurl += 'category_id='+category_id+'&';
        }
        if (jQuery('#myofs-sidebar').hasClass('sidebar-menu-expand')) {
            qryurl += '&menu=expand';
        }
        console.log('qryurl2',qryurl);
        redirect_url = inventorytab+'&'+'limit'+'='+limit+'&'+'current_page'+'='+page+'&'+qryurl;
        window.location.href = redirect_url;

        
    });
    /*clear category filter*/
    jQuery('#clear_category').click(function() {
        jQuery("#category_id").val('');
             
        category_id   = jQuery("#category_id").val();
        qryurl = '';
        if (sortby.length > 0) {
            qryurl += 'sortby='+sortby+'&';
        }
        if (category_id.length > 0) {
            qryurl += 'category_id='+category_id+'&';
        }
        if (jQuery('#myofs-sidebar').hasClass('sidebar-menu-expand')) {
            qryurl += '&menu=expand';
        }
        redirect_url = inventorytab+'&'+'limit'+'='+limit+'&'+'current_page'+'='+page+'&'+qryurl;
        window.location.href = redirect_url;
    });
}
/*collspan category sub menus*/
function categorySubmenu(){
   
    jQuery('li.myofs-nav-item [class^="arrow"]').click(function (event) {
        event.preventDefault(); // prevent default action
        cls  = jQuery(this).parents('li').children('ul');
        gcls = cls.attr('class');
        console.log('gcls===',gcls);
        srpl = gcls.replace("sub-menu", "");
        jQuery(this).toggleClass('open'+srpl);
        jQuery(this).parents('li.myofs-nav-item').toggleClass('open'+srpl);
        // setTimeout(()=>{

            if (jQuery(this).hasClass('open'+srpl)){
                cls.css('max-height','2500px');
                cls.css('visibility','visible');
                cls.css('opacity','1');
                cls.css('display','contents');
                // cls.addClass('subm-expand');
            } else {
                 // cls.removeClass('subm-expand');
                jQuery(this).parents('li').find('.'+gcls).attr('style');
                jQuery(this).parents('li').find('.'+gcls).css('visibility','');
                jQuery(this).parents('li').find('.'+gcls).css('opacity','');
                jQuery(this).parents('li').find('.'+gcls).css('max-height','');
                jQuery(this).parents('li').find('.'+gcls).css('display','');
                
            }
        // },90);
        
        
    });    
}
// View Details of Products on popup
function detailProductDisplay(){
    jQuery('.detail-view').click(function (event) {       
        event.preventDefault(); //prevent default action
        var productid = jQuery(this).attr('data-product-id');
        var ajaxurl = jQuery("#admin-url").val();
        var post_data = {
            productid: productid,
            action: "productdisplay",
        };
        jQuery.ajax({
            type: 'GET',
            url: ajaxurl,
            data: post_data,
            dataType:"JSON",
            beforeSend: function() {
                jQuery(".apploader").css({"display":"block","background-color":"black","z-index":"999","width":"100%","opacity":"0.5"});
            },
            success: function (response) {
                jQuery(".apploader").removeAttr('style');
                if(response.status == 1){ 
                    var data   = response.data;
                    if (data) {                        
                        var id        = data.id;                    
                        var name      = data.name;
                        var sku       = data.sku;
                        var weight    = data.weight;
                        var image     = data.featured_image;
                        var gallery   = data.gallery_image;
                        var glen      = gallery.length;
                        var stock     = data.stock;
                        var inventory = data.inventory;
                        var price     = data.price;
                        var optname   = data.optionname;
                        var opt       = data.option;
                        var olen      = opt.length;
                        var opts      = data.options;
                        var oslen     = opts.length;
                        var desc      = data.description;
                        var tags      = data.tags;
                        var tlen      = tags.length;
                        var caption        = new Array(); 
                         
                        jQuery('#big-image,#thumbs,h2.item-title,div#inv_btn,div#proddata,div#optsdata,div#skdecsdata,div#tags').empty();

                        jQuery('#big-image').html('<img src="'+image+'" style="width:100%;" >');
                        if (glen != 0) {
                            for ( var i = 0; i < glen; i++ ) {
                                caption.push(gallery[i].caption);
                                jQuery('#thumbs').append('<div class="img-container ng-scope active"><img  src="'+gallery[i].image+'" alt=""><span class="caption">'+gallery[i].caption+'</span></div>');
                            }  
                        }
                        jQuery('h2.item-title').text(name);
                        if (inventory == 'no') {
                            jQuery('div#inv_btn').html('<a class="myofs-add-wc uppercase myofs_pd_addwc"  data-product-id="'+id+'"   data-product-name="'+name+'" data-product-stock = "'+stock+'" id="myofs_add_wc">'+'Add to Woocommerce'.toUpperCase()+'</a>');
                            
                        } else {
                            jQuery('div#inv_btn').html('<button class="btn btn-success pull-right ng-scope proitem_download" >Item Added</button>').insertAfter('h2.item-title');
                        } 
                        jQuery.each( price, function( index, value ){ 
                            if (index == 'msrp') {
                               indval = index.toUpperCase();
                            }else{
                                ind = index.replace(/_/g, " ");
                                indval = ind.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                    return letter.toUpperCase();
                                });                           
                                
                            }
                            var cls = ' ';
                            if (index == 'default_selling_price') {
                                cls = 'label label-success';
                            }
                            var phtml = '<div class="good-price ng-scope">'+'<label class="rm-3">'+indval+': </label>'+'<span class="'+cls+' good-price ng-binding">'+value+'</span>'+'</div>';
                            jQuery('div#proddata').append(phtml);
                        });
                        jQuery('div#proddata').append('<div><label class="rm-3">SKU: </label><span class="ng-binding"> '+sku+'</span></div><div><label class="rm-3">Weight: </label><span class="ng-binding"> '+weight+'</span></div>');
                        if (olen != 0) {
                            jQuery('div#optsdata').append('<div class="product-option "><label class="rm-3">'+optname+': </label><ul class="option-data option_data_stock">');
                            for ( var oi = 0; oi < olen; oi++ ) {
                                jQuery('ul.option_data_stock').append('<li><span>'+opt[oi].name+'</span><p> '+opt[oi].stock+ ' PC</p></li>');
                            }
                            jQuery('div#optsdata').append('</ul></div>');
                        }
                        if (oslen != 0) {
                            jQuery('div#optsdata').append('<div class="product-option "><label class="rm-3">'+optname+': </label><ul class="option-data option_data_stock">');
                            for ( var oi = 0; oi < olen; oi++ ) {
                                jQuery('ul.option_data_stock').append('<li><span>' +$opts[oi]+ '</span></li>');
                            }
                            jQuery('div#optsdata').append('</ul></div>');
                        }
                        if (stock != 0) {
                            jQuery('div#skdecsdata').append('<div class="good-stock"><label class="rm-3">In Stock: </label><span class="ng-binding"> '+stock+'</span></div>');
                            
                        }else{                            
                            jQuery('div#skdecsdata').append('<div class="good-stock"><label class="rm-3">Out Of Stock </label></div>');
                        }
                        jQuery('div#skdecsdata').append('<div class="product-description"><label>Description: </label><div class="ng-binding"> '+desc+'</div></div>');
                        if (tlen != 0) {
                            for ( var ti = 0; ti < tlen; ti++ ) {
                                caption.push(tags[ti]);
                            } 
                            var cpunique = caption.filter(function(item, i, caption)
                            { return i == caption.indexOf(item); });
                            var tagarr = cpunique.filter(function(v){return v!==''}) 
                            var cplen  = tagarr.length;
                            if (cplen != 0) {
                                jQuery('div#tags').append('<div class="product-option"><label class="rm-3">Tags: </label><ul class="option-data opttags">');
                                for ( var cpi = 0; cpi < cplen; cpi++ ) {
                                    jQuery('ul.opttags').append('<li><span>'+tagarr[cpi]+'</span> </li>');
                                }
                                jQuery('div#tags').append('</ul></div>');
                            }
                        } 
                        
                        jQuery('#singleprodetail').addClass('in');
                        jQuery('#singleprodetail').show();
                    }                   
                    jQuery('html,body').animate({
                        scrollTop: jQuery("#product_details").offset().top
                    },'slow');
                    closeProductDetailDisplay();
                }else{
                    jQuery('#myofs-layout__notice-list').empty().html(start_error_html + response.error + end_errnotice_html);
                    jQuery('html,body').animate({
                        scrollTop: jQuery("#myofs-layout__notice-list").offset().top
                    },'slow');
                }
           
            },
            error: function (errorThrown) {}
        });  
    });   
}
// close detail product popup
function closeProductDetailDisplay(){
    jQuery('#product_details .close').click(function () {
        jQuery('#singleprodetail').removeClass('in');
        jQuery('#singleprodetail').hide();
        // jQuery("#product_details").html("");
    });
}
// add product to wc popup
function addProducToWCStoreDisplay(){
    jQuery(document).on('click', '#myofs_add_wc', function (event) {
        event.preventDefault(); //prevent default action   
        var checkcls = 'no';   
        if (jQuery(this).hasClass('myofs_pd_addwc')) {
            checkcls = 'yes';
        }
        var id = new Array(); 
        product_id    = jQuery(this).attr('data-product-id');
        id.push(product_id);
        product_type  = 'single';
        combain_product = 0;
        proid_cnt = id.length;
        addToPopupAjaxCall(id,product_type,proid_cnt,combain_product,checkcls);
    });
     /*collection hide show*/  
    jQuery(document).on('click', '.add_categorytostore', function () {
        jQuery('#collection_select').hide();
        jQuery('#collection_manully').show();
        jQuery('#collection_type').val('manully');
    });
    jQuery(document).on('click', '.gocategorylist', function () {
        jQuery('#collection_select').show();
        jQuery('#collection_manully').hide();
        jQuery('#collection_type').val('select');
    });
    /*end collection hide show*/  
    
}
function addToPopupAjaxCall(product_id,product_type,proid_cnt,combain_product,checkcls){
    
    var loadercls;
    if (checkcls == 'yes') { loadercls = ".apploaderp"; }else{ loadercls = ".apploader"; }
    var post_data = {
        productid: product_id,
        product_type: product_type,
        action: "gettagcategories"
    };
    jQuery.ajax({
        type: 'GET',
        url: ajaxurl,
        data: post_data,
        dataType:"JSON",
        beforeSend: function() {                   
            jQuery(loadercls).css({"display":"block","background-color":"black","z-index":"999","width":"100%","opacity":"0.5"});
        },
        success: function (response) {         
            jQuery(loadercls).removeAttr('style');
            if(response.status == 1){
                if(response.data.errorcode == 404){
                    jQuery('#snackbar').addClass('show_spin');
                    jQuery('#snackbar').empty();
                    jQuery('#snackbar').append(response.data.error);
                    setTimeout(function(){
                        jQuery('#snackbar').removeClass('show_spin');                           
                    }, 3000);
                }else{

                    var cat  = response.data.category;
                    var clen = cat.length;
                    var cat_html = opt_html = '';
                    if(clen){
                        for(var i=0; i<clen; i++){
                            opt_html +='<option value="'+cat[i].id+'">'+cat[i].name+'</option>';
                        }                        
                        cat_html ='<select class="js-example-basic-multiple" id="myofs_categoryid" name="myofs_categoryid[]" multiple="multiple" data-live-search="true" data-virtual-scroll="true">'+opt_html+'</select>';
                        jQuery('#myofs_storecat').html(cat_html);                        
                        jQuery('#collection_select').css('display','block');
                        jQuery('#collection_manully').css('display','none');
                        jQuery('#collection_type').val('select');
                    }else{
                        jQuery('#collection_select').css('display','none');
                        jQuery('.gocategorylist').parent().css('display','none');
                        jQuery('#collection_manully').css('display','block');
                        jQuery('#collection_type').val('manually');
                    }
                    if( proid_cnt == 1 ){                     
                        jQuery('#pro_default_tag').show();
                        jQuery('#modal_pro_tags').html(response.data.tags);
                    }else{
                        jQuery('#pro_default_tag').hide();
                    }
                   
                    jQuery('#modal_pro_id').val(product_id);
                    jQuery('#combain_product').val(combain_product);                
                    jQuery('#addtowc_modal').addClass('in');
                    jQuery('#addtowc_modal').show();
                    jQuery('.js-example-basic-multiple').select2({
                        placeholder: " Select a collection ",
                    });
                }
                
            }
        },
        error: function (errorThrown) {}
    }); 
}
/*add single product to wc submit */
function itemAddToWC(){    
    jQuery(document).on('click', '#addtomyofs_btn', function (event) {
    //jQuery("#addtomyofs_btn").click(function (event) {
        event.preventDefault(); //prevent default action
        jQuery('#addtomyofs_btn').prop('disabled', false);         
        jQuery('#addtowc_modal #myofs-layout__notice-list').empty();
        var collection_type  = jQuery('#collection_type').val();
        var amnt             = jQuery('#amount').val();
        var productname      = jQuery('#modal_pro_name').val();
        if(collection_type == 'select'){
            var categoryids = jQuery('#myofs_categoryid option:selected').toArray().map(item => item.value).join();
			
        }else{
            var categoryids = jQuery('#categoryid_nm').val();
        }
		// console.log(categoryids);
        if(amnt != undefined && amnt != null && amnt != ''){
            var amount = amnt;
        }else{ var amount = ''; }        

        var form_data = jQuery('#addtostore_form').serialize(); 
        var name = '';
        if(productname != ''){
            name = productname; 
        }
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: form_data,
            dataType: "json",
            beforeSend: function() {
                jQuery(".apploaderp").css({"display":"block","background-color":"black","z-index":"999","width":"100%","opacity":"0.5"});
            },
            success: function (response) {
                jQuery(".apploaderp").removeAttr('style');
                if( response.status == 1 ){
                    jQuery('#addtowc_modal').removeClass('in');
                    jQuery('#addtowc_modal').hide();
                    jQuery('#snackbar').addClass('show_spin');
                    jQuery('#snackbar').empty();
                     if(response.count > 1){
                        if (response.total_noproduct > 1 && response.total_product > 1) {
                            jQuery('#snackbar').append(response.count+' out of '+response.total_product+' products have been added successfully and '+response.total_noproduct+ ' products has been not migrated because amount calculated is zero, please allow few minutes as the product migrates to your products section.');

                            jQuery("#addtostore_form")[0].reset();                                               
                            setTimeout(function(){
                                jQuery('#snackbar').removeClass('show_spin');
                                returnSuccessRedirect(limit,sortby,category_id,search,page,1);                            
                            }, 8000);
                        }else if(response.total_noproduct > 1 && response.total_product == 0){
                            jQuery('#snackbar').append(response.total_noproduct+' products has been not migrated because amount calculated is zero.');
                            jQuery("#addtostore_form")[0].reset(); 
                            setTimeout(function(){                                
                                jQuery('#snackbar').removeClass('show_spin');
                                returnSuccessRedirect(limit,sortby,category_id,search,page,1);                            
                            }, 2000);
                        }else if(response.total_noproduct == 0 && response.total_product > 1){
                            jQuery('#snackbar').append(response.count+' out of '+response.total_product+' products have been added successfully, please allow few minutes as the product migrates to your products section.');
                            jQuery("#addtostore_form")[0].reset(); 
                            setTimeout(function(){
                                jQuery(".apploaderp").removeAttr('style');
                                jQuery('#snackbar').removeClass('show_spin');
                                returnSuccessRedirect(limit,sortby,category_id,search,page,1);                            
                            }, 8000);
                        }else{
                            jQuery('#snackbar').append('The product has been added successfully, please allow few minutes as the product migrates to your products section.');
                            jQuery("#addtostore_form")[0].reset();                        
                                             
                            setTimeout(function(){
                                jQuery('#snackbar').removeClass('show_spin');
                                returnSuccessRedirect(limit,sortby,category_id,search,page,1);                            
                            }, 8000);
                        }
                    } else{
                        jQuery('#snackbar').append(name +' product has been added successfully, please allow few minutes as the product migrates to your products section.');
                        jQuery("#addtostore_form")[0].reset();                        
                        setTimeout(function(){
                            jQuery('#snackbar').removeClass('show_spin');
                            returnSuccessRedirect(limit,sortby,category_id,search,page,1,1);                            
                        }, 8000);
                    }
                }else{ 
                    jQuery('#addtowc_modal #myofs-layout__notice-list').empty();
                    if (response.error.length != 0) {
                        jQuery('#addtowc_modal #myofs-layout__notice-list').append();
                        if( response.error.product !== '' ) {
                            jQuery('#addtowc_modal #myofs-layout__notice-list').append(start_error_html + response.error.product +end_errnotice_html);
                        }
                        if( response.error.category  !== ''  ) {
                            jQuery('#addtowc_modal #myofs-layout__notice-list').append(start_error_html + response.error.category +end_errnotice_html);
                        }
                        if( response.error.amount  !== ''  ) {
                            jQuery('#addtowc_modal #myofs-layout__notice-list').append(start_error_html + response.error.amount +end_errnotice_html);
                        }                    
                        jQuery('html,body').animate({
                            scrollTop: jQuery('#addtowc_modal #myofs-layout__notice-list').offset().top
                        },'slow');
                    }                     

                }
                
            },
            error: function (errorThrown) {}
        }); 
         

    });
}
// close add product to wc popup
function closeaddProducToWCStoreDisplay(){
    jQuery(document).on('click', '#addtowc_modal .modal-header .close,#addtocancle', function () {
        jQuery("#addtostore_form")[0].reset();
        jQuery('#addtowc_modal').removeClass('in');
        jQuery('#addtowc_modal').hide(); 
    });
}
/*select single product checkbox*/
function selectUnselectProduct() {   
    jQuery(document).on('click', '.product_id', function () {   
        var val = jQuery(this).val();           
        if (jQuery(this).is(":checked")){                       
            jQuery(this).closest("div.portlet").addClass("Pro_selected");
            jQuery(".pro-add-rmv a[data-product-id$=" + val + "]").hide();
            jQuery("#" + val).parent(".check_cnt").addClass("active_check_cnt");
            checkb();            
        }else{        
            
            jQuery(this).closest("div.portlet").removeClass("Pro_selected");
            jQuery(".pro-add-rmv a[data-product-id$=" + val + "]").show();
            checkb();
        }
    });
    
}
/*selected base show combine , multiple ,remove products buttons*/
function checkb(){ 
    var checkboxes = jQuery('.product_id');    
    checkboxes.change(function() {    
        var count = checkboxes.filter(':checked').length;
        if (count > 0) { 
            if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){
                jQuery("#multi-add:first").remove();
                jQuery("#multi-combine-add:first").remove();
                jQuery("#selectionbox").after('<a class="add-wc uppercase" id="multi-add" href="javascript:void(0);">Add to Woocommerce</a>');
                jQuery("#selectionbox").after('<a class="add-wc uppercase" id="multi-combine-add" href="javascript:void(0);">COMBINE PRODUCTS & ADD</a>');      
            }  
            
            if (jQuery(".Pro_selected a").hasClass("myofs-remove-wc")) {
                
                jQuery("#section_n").html('<a class="remove-wc uppercase" id="remove-product" href="javascript:void(0);">Remove Products</a>');
            } else {
                
                jQuery("#section_n").html('');
            }
        } else {
            if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){
                jQuery("#multi-add:first").remove();
                jQuery("#multi-combine-add:first").remove();
            }
            jQuery("#section_n").html('');
        }
    });
}
/*remove single product*/
function removeSingleProduct(){ 
    var sids = new Array();
    jQuery(document).on('click', '#myofs-remove-wc', function () {
        var prid = "#large"+jQuery(this).attr('data-product-id'); 
        jQuery(prid).addClass('in');
        jQuery(prid).show();        
    });  
    jQuery(document).on('click', '#rmv_product', function () {
        var pid = jQuery(this).parent().attr('data-product-id');        
        sids.push(pid);
        jQuery("#large"+pid).removeClass('in');
        jQuery("#large"+pid).hide();
        removeProductAjaxCall(sids);
    });
    jQuery(document).on('click', '.rmv_cancel', function () {
        var pid = jQuery(this).parent().attr('data-product-id');
        jQuery("#large"+pid).removeClass('in');
        jQuery("#large"+pid).hide();
    });
    
} 
/*remove multiple products*/
function removemultiProducts(){
    jQuery(document).on('click', '#remove-product', function () {
              
        if (jQuery(".Pro_selected").is(":not(.item_added_pro_c)")) {
            jQuery("#snackbar").html('<a class="close" href="javascript:void(0);">×</a><br>You have selected unadded product. Please remove it from selected products... ');
            jQuery('#snackbar').addClass('show_spin');                        
            setTimeout(function(){ jQuery('#snackbar').removeClass('show_spin');}, 5000);
        }else{
            jQuery("#multiallinventory").addClass('in');
            jQuery("#multiallinventory").show();
            removemultiproductspopup();
        }
    });

}
function removemultiproductspopup(){
    var product_ids = new Array(); 
    jQuery(document).on('click', '#rmv_mulpro', function () {
        jQuery.each(jQuery(".product_id:checked"), function() {
            product_ids.push(jQuery(this).val());
        });
        jQuery("#multiallinventory").removeClass('in');
        jQuery("#multiallinventory").hide();
        removeProductAjaxCall(product_ids);
    }); 
    jQuery(document).on('click', '.rmv_mulcancel', function () {
        jQuery("#multiallinventory").removeClass('in');
        jQuery("#multiallinventory").hide();
    });
}
function removeProductAjaxCall(product_id){
    
    var post_data = {
        ids:product_id,
        action:'productremovetowcstore'
    };
    jQuery.ajax({
        type: 'GET',
        url: ajaxurl,
        data: post_data,
        dataType: "json",
        beforeSend: function() {
            jQuery(".apploader").css({"display":"block","background-color":"black","z-index":"999","width":"100%","opacity":"0.5"});
        },
        success: function (response) {
            jQuery(".apploader").removeAttr('style');            
            if( response.status == 1 ){
                jQuery('#snackbar').addClass('show_spin');
                jQuery('#snackbar').empty();
                jQuery('#snackbar').append(' product has been deleted successfully, please allow few minutes as the product is deleted from your my inventory section.');
                setTimeout(function(){
                	jQuery('#snackbar').removeClass('show_spin');                        
					returnSuccessRedirect(limit,sortby,category_id,search,page);                    
                }, 4000);
            }else{                    
                if (response.error.api_error) {
                    jQuery('#snackbar').append(response.error.api_error);
                } else if(response.error.product){
                    jQuery('#snackbar').append(response.error.product);
                }
                jQuery('#snackbar').addClass('show_spin');
                setTimeout(function(){
                    jQuery('#snackbar').removeClass('show_spin');                        
                }, 1500);
            }
        },
        error: function (errorThrown) {}
    }); 
}
/*multiple products add to wc*/
function multipleProductAdd(){
    jQuery(document).on('click', '#multi-add', function () {
        if (jQuery(".Pro_selected").hasClass("item_added_pro_c")) {
            jQuery("#snackbar").html('<a class="close" href="javascript:void(0);">×</a><br> You have selected already added product. Please remove it from selected products... ');

            jQuery('#snackbar').addClass('show_spin');
            setTimeout(function(){ jQuery('#snackbar').removeClass('show_spin');}, 5000);
        }else{ 
            jQuery('#addtomyofs_btn').prop('disabled', false);
            //jQuery('.selectpicker').selectpicker('deselectAll'); 
            var ids = new Array();
            jQuery.each(jQuery(".product_id:checked"), function() {
                ids.push(jQuery(this).val());
            });
            jQuery('#modal_pro_id').val(ids);     
            proid_cnt = ids.length;
            product_type = 'multiple';
            if (proid_cnt == 1) {
                product_type = 'single';
            }
            combain_product = 0;
            addToPopupAjaxCall(ids,product_type,proid_cnt,combain_product);   
        }  
    });
}
//combine product add to wc
function combineProductAdd(){
    jQuery(document).on('click', '#multi-combine-add', function () {
        
        jQuery('#addtomyofs_btn').prop('disabled', false);
        var ids = new Array();
        jQuery.each(jQuery(".product_id:checked"), function() {
            ids.push(jQuery(this).val());
        });
        jQuery('#modal_pro_id').val(ids); 
        var ajaxurl     = jQuery("#admin-url").val();
        proid_cnt       = ids.length; 
        product_type    = 'combine';
        combain_product = 1;  
        var post_data = {
            productid: ids,
            action: "checkcombineproduct"
        }; 
        jQuery.ajax({
            type: 'GET',
            url: ajaxurl,
            data: post_data,
            dataType:"JSON",
            beforeSend: function() {
                jQuery(".apploader").css({"display":"block","background-color":"black","z-index":"999","width":"100%","opacity":"0.5"});
            },
            success: function (response) {
                jQuery(".apploader").removeAttr('style');
                if(response.status == 1){
                    addToPopupAjaxCall(ids,product_type,proid_cnt,combain_product);
                   
                } else {  
                    jQuery("#snackbar").html('<a class="close" href="javascript:void(0);">×</a><br>'+response.message);
                    jQuery('#snackbar').addClass('show_spin');                       
                    setTimeout(function(){ jQuery('#snackbar').removeClass('show_spin');}, 5000);
                } 
                
            },
            error: function (errorThrown) {}
        });
	    
    });
}
//selection  dropdown. select all,inverser select all, remove all dropdown
function selectionDropdown(){
    jQuery("#selectionbox button").click(function(){ 
        jQuery(this).parent().toggleClass('select_open');
        jQuery(this).attr('aria-expanded','true');

    });
    jQuery("#selectionbox ul li").click(function(){ 
        var value = jQuery(this).find(' a.myofs_seltag').attr('data-val');
        if(value == 'selectall'){
            selectAll();
        }else if(value == 'removeselectall'){
            removeSelectAll();
        }else if(value == 'inverseselectall'){
            inverseSelectAll();
        }
        jQuery(this).parents().find('#selectionbox').toggleClass('select_open');
    });
    /*jQuery("#selectionbox").change(function () {
        var value = jQuery(this).val();
        if(value == 'selectall'){
            selectAll();
        }else if(value == 'removeselectall'){
            removeSelectAll();
        }else if(value == 'inverseselectall'){
            inverseSelectAll();
        }
        jQuery(this).val(0);
    });*/
}
//select all products
function selectAll(){
    jQuery(".product_id").each(function() {
        this.checked = true;
        jQuery(this).closest("div.portlet").addClass("Pro_selected");
        var val = jQuery(this).attr("id");
        jQuery(".pro-add-rmv a[data-product-id$=" + val + "]").hide();
    });

    var checkboxes = jQuery('.product_id');
    var count = checkboxes.filter(':checked').length;
    if (count > 0) { 
        if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){  
            jQuery("#multi-combine-add:first").remove();
            jQuery("#multi-add:first").remove();
            jQuery("#selectionbox").after('<a class="add-wc uppercase" id="multi-add" href="javascript:void(0);" style="display: inline-block;">Add to Woocommerce</a>');
            jQuery( "#selectionbox").after('<a class="add-wc uppercase" id="multi-combine-add" href="javascript:void(0);" style="display: inline-block;">COMBINE PRODUCTS & ADD</a>'); 
        }
        if (jQuery(".Pro_selected a").hasClass("myofs-remove-wc")) {
                
            jQuery("#section_n").html('<a class="remove-wc uppercase" id="remove-product" href="javascript:void(0);" style="display: inline-block;">Remove Products</a>');
        }
    } else {  
        if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){  

            jQuery("#multi-combine-add:first").remove();
            jQuery("#multi-add:first").remove();
        }
        jQuery("#section_n").html('');
    }
}
// Remove selected all products
function removeSelectAll() {   

    jQuery(".product_id").each(function() {
        this.checked = false;
        jQuery(this).closest("div.portlet").removeClass("Pro_selected");
        var val = jQuery(this).attr("id");
        jQuery(".pro-add-rmv a[data-product-id$=" + val + "]").show();
        
    });
    var checkboxes = jQuery('.product_id');
    var count = checkboxes.filter(':checked').length;
        if (count > 0) {

            if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){  
                jQuery("#multi-add:first").remove();
                jQuery("#multi-combine-add:first").remove();
                jQuery("#selectionbox").after('<a class="add-wc uppercase" id="multi-add" href="javascript:void(0);" style="display: inline-block;">Add to Woocommerce</a>');
            }
                jQuery( "#selectionbox").after('<a class="add-wc uppercase" id="multi-combine-add" href="javascript:void(0);" style="display: inline-block;">COMBINE PRODUCTS & ADD</a>'); 
            
        } else {
            if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){  
                jQuery("#multi-add:first").remove();
                jQuery("#multi-combine-add:first").remove();
            }
            jQuery("#section_n").html('');
        }
}
//inverse product.selected all products and remove all products(toggle)
function inverseSelectAll(){
    jQuery(".product_id").each(function() {
        var val = jQuery(this).attr("id");
        if (!this.checked) {
            this.checked = true;
            jQuery(this).closest("div.portlet").addClass("Pro_selected");
            jQuery(".pro-add-rmv a[data-product-id$=" + val + "]").hide();
            
        } else {
            this.checked = false;
            jQuery(this).closest("div.portlet").removeClass("Pro_selected");
            jQuery(".pro-add-rmv a[data-product-id$=" + val + "]").show();
            
        }
    });
    var checkboxes = jQuery('.product_id');
    var count = checkboxes.filter(':checked').length;
    if (count > 0) {
        if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){  

            jQuery("#multi-combine-add:first").remove();
            jQuery("#multi-add:first").remove();
            jQuery("#selectionbox").after('<a class="add-wc uppercase" id="multi-add" href="javascript:void(0);" style="display: inline-block;">Add to Woocommerce</a>');
            jQuery("#selectionbox").after('<a class="add-wc uppercase" id="multi-combine-add" href="javascript:void(0);" style="display: inline-block;">COMBINE PRODUCTS & ADD</a>'); 
        }

        if (jQuery(".Pro_selected a").hasClass("myofs-remove-wc")) {
                
            jQuery("#section_n").html('<a class="remove-wc uppercase" id="remove-product" href="javascript:void(0);" style="display: inline-block;">Remove Products</a>');
        }
    } else {
        if(jQuery('#myofs_productdta').hasClass('myofs_inventory')){  

            jQuery("#multi-add:first").remove();
            jQuery("#multi-combine-add:first").remove();
        }
        jQuery("#section_n").html('');
    }
}

function returnSuccessRedirect(limit,sortby,cat_id,search_str,page,check = 0,count = 0){
    qryurl = '';
    if (sortby.length > 0) {
        qryurl += 'sortby='+sortby+'&';
    }
    if (cat_id.length > 0) {
        qryurl += 'category_id='+cat_id+'&';
    }
    if (search_str.length > 0) {
        search_str = Base64.encode(search_str);
        qryurl += 'search='+search_str+'&';
    }
    if (jQuery('#myofs-sidebar').hasClass('sidebar-menu-expand')) {
        qryurl += '&menu=expand';
    }
    if(jQuery('#myofs_myinvproductdta').length > 0){ 
    	
    	redirect_url = myinventorytab+'&'+'limit'+'='+limit+'&'+'current_page'+'='+page+'&'+qryurl; 
        window.location.href = redirect_url;
    }else{
       
        redirect_url = inventorytab+'&'+'limit'+'='+limit+'&'+'current_page'+'='+page+'&'+qryurl;
        window.location.href = redirect_url;

    }
}
var intervalId = window.setInterval(function(){
    if(jQuery("#myofs_productdta").length != 0) {
        refershProduct();
    }
}, 10000);
// }, 5000);
function refershProduct(){    
    jQuery.ajax({
        type: 'GET',
        url: ajaxurl,
        data: {action:'productadditemaddlbl','limit':limit,'sortby':sortby,'cat_id':category_id,'search':search,'page':page},
        dataType: "json",
        success: function (response) {
            if(response.data){ 
                var dta  = response.data.item_add;   
                var rdta = response.data.item_rmv;   
                for ( var i = 0; i < dta.length; i++ ) {
                    var id      = dta[i]['id'];
                    var rmv_htl = dta[i]['rmv_html'];
                    if (!jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"] .pro_item_added').length){ 
                        jQuery('<div class="pro_item_added"><span>Item Added</span></div>').insertBefore('#myofs_productdta .portlet-body[data-product-id="'+id+'"] .mt-element-overlay');
                        jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"] .mt-element-overlay .mt-overlay .pro-add-rmv').html('<a class="myofs-remove-wc uppercase" id="myofs-remove-wc" data-toggle="modal" data-product-id="'+id+'">Remove From Woocommerce</a>');
                        jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"]').parent().addClass('item_added_pro_c');
                        jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"]').parent().append(rmv_htl);
                    }

                }  
                for ( var i = 0; i < rdta.length; i++ ) { 
                    var id      = rdta[i]['id'];
                    var name    = rdta[i]['name'];
                    var sku     = rdta[i]['sku'];
                    var stock   = rdta[i]['stock'];
                    if (jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"] .pro_item_added').length){                        
                        jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"] .pro_item_added').remove();
                        jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"] .pro_item_added').parent().removeClass('item_added_pro_c');
                        jQuery('#myofs_productdta .portlet-body[data-product-id="'+id+'"] .mt-element-overlay .mt-overlay .pro-add-rmv').html('<a class="myofs-add-wc uppercase" data-product-id="'+id+'" data-product-name="'+name+'" data-product-stock="'+stock+'" data-product-sku="'+sku+'" id="myofs_add_wc">Add to Woocommerce</a>');
                    }
                }  
            }
        }
    });
   
}
