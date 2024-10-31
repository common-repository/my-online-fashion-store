jQuery( document ).ready(function() {   
    var search,page;
    jQuery('#clearButton').css('display','none');
    if(jQuery('#myofs_oprderdata').length){
       OrderListing(search = '',page = '');
    }
    jQuery("#quickfind").submit(function(e){
        e.preventDefault();
        search = jQuery('#keywords').val();
        page   = 1;
        jQuery('#clearButton').css('display','block');
        OrderListing(search,page);
    });
    jQuery(document).on('click', '#clearButton', function () {
        jQuery('#quickfind')[0].reset();
        jQuery('#clearButton').css('display','none');
        OrderListing(search = '',page = '');

    });
    
    //pagination    
    jQuery(document).on('click', '#myofs_oprderdata .myofs-pagination li.active', function () {
        page = jQuery(this).attr('p');
        OrderListing(search,page);
    }); 


});
function OrderListing(search,page){
    
    if (search == 'undefined') { search = ' '; }
    if (page == 'undefined') { page = 1; }
    var ajaxurl = jQuery("#admin-url").val();
    var post_data = {
        search:search,
        page:page,
        action: "getordersearchdata",
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
            if (response.status == 1) {
                jQuery('.order_data').empty();
                var data   = response.data;
                var len    = data.length;
                var tr_str;
                if (len != 0) {
                    for(var i=0; i<len; i++){
                        var id         = data[i].ID;                    
                        var date       = data[i].date;
                        var user_email = data[i].user_email;
                        var status     = data[i].status;
                        var items      = data[i].items[id];
                        var ilen       = items.length;
                        var total      = data[i].total;
                        var currency   = data[i].currency;
                        var payment    = data[i].payment;
                        var shipping_info = data[i].shipping_info;
                        var billing_info  = data[i].billing_info;
                        if (ilen != 0) {
                            tr_str = '<tr>' +
                                '<th scope="row">' + id + '</td>' +
                                '<td>' + date + '</td>' +
                                '<td>' + user_email + '</td>' +
                                '<td><span class="label label-default">' + status + '</span></td>' +
                                '<td>' + total + ' '+ currency + '</td>' +
                                '<td><span id="'+id+'" class="myofs_action"> <i class="fa fa-plus"></i> </span></td>'
                                '</tr>';
                            /*order details*/
                            tr_str += '<tr class="'+id+' order_content" style="display:none;">'+
                                        '<td colspan="6">'+
                                            '<div>'+
                                                '<h5><b>Order Details</b></h5>'+
                                                '<div class="row">'+
                                                    '<div class="col-md-12">'+
                                                        '<h6><b>PRODUCTS</b></h6>'+
                                                    '</div>'+
                                                    '<div class="col-md-12">';

                                                        for(var ii=0; ii<ilen; ii++){
                                                            
                                                            var item_name  = items[ii].item_name;
                                                            var item_price = items[ii].item_price;
                                                            var item_qty   = items[ii].item_qty;
                                                            tr_str += '<div class="col-md-6">'+
                                                                        '<span>'+item_name+'</span>'+
                                                                      '</div>'+
                                                                      '<div class="p_price col-md-3">'+
                                                                        '<span>'+item_price+currency+'<span>X <span>'+item_qty+'</span></span></span>'+
                                                                      '</div>';
                                                        }   
                                                    tr_str +='</div>'+
                                                    '<hr>'+
                                                    '<div class="col-md-12 text-right row">'+
                                                        '<div class="p_price order-total">'+
                                                            '<div>'+
                                                                '<b>Subtotal:</b>'+
                                                                total+ ' ' +currency + 
                                                            '</div>'+
                                                            '<div>'+
                                                                '<b>Total:</b>'+
                                                                total+ ' ' +currency +
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="row">'+
                                                    '<div class="col-md-12">'+
                                                        '<div class="col-md-6">'+
                                                            '<h6><b>PAYMENT</b></h6>'+
                                                            '<div>'+
                                                                '<p>'+
                                                                    'Payment Gateway: '+ payment +
                                                                '</p>'+
                                                                '<p>'+
                                                                    'Payment Status: '+ status +
                                                                '</p>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<div class="col-md-6">'+  
                                                            '<h6><b>CUSTOMER DETAILS</b></h6>'+ 
                                                            '<div>'+
                                                                '<p><a href="mailto:'+user_email+'" target="_top">'+user_email+'</a></p>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="row">'+
                                                    '<div class="col-md-12">'+
                                                        '<h5><b>Address</b></h5>';
                                                        if (shipping_info.length != 0) {
                                                            tr_str +='<div class="col-md-6 ship-add">'+
                                                                '<h6><b>SHIPPING ADDRESS</b></h6>'+
                                                                '<div>'+
                                                                    '<div class="address">'+
                                                                        '<span>'+shipping_info.name+'</span>'+
                                                                    '</div>'+
                                                                    '<div class="address">'+
                                                                        '<span>'+shipping_info.company+'</span>'+
                                                                    '</div>'+
                                                                    '<div class="address">'+
                                                                        '<span>'+shipping_info.address+'</span>'+
                                                                    '</div>'+
                                                                '</div>'+
                                                            '</div>';
                                                        }

                                                        tr_str +='<div class="col-md-6 bill-add">'+
                                                            '<h6><b>BILLING ADDRESS</b></h6>'+
                                                            '<div>'+
                                                                '<div class="address">'+
                                                                    '<span>'+billing_info.name+'</span>'+
                                                                '</div>'+
                                                                '<div class="address">'+
                                                                    '<span>'+billing_info.company+'</span>'+
                                                                '</div>'+
                                                                '<div class="address">'+
                                                                    '<span>'+billing_info.address+'</span>'+
                                                                '</div>'+
                                                                '<div class="address">'+
                                                                    '<span>'+billing_info.phone_no+'</span>'+
                                                                '</div>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</td>'+
                                    '</tr>';
                            jQuery('.order_data').append(tr_str); 
                        }                    
                    }    
                    var paginhtml = atob(response.pagination);      
                    var pagination = paginhtml.replace(/Â/g, '');
                    jQuery('.order_pagination').html(pagination); 
                }else{
                    jQuery('.order_data').html('<tr><td>No orders found</td></tr>');     
                    jQuery('.order_pagination').html('');
                    jQuery('.quickfind').hide();
                }
                    
            }else{
                jQuery('.order_data').html('<tr><td>No orders found</td></tr>');     
                jQuery('.order_pagination').html('');
                jQuery('.quickfind').hide();
            }
        },
        error: function (errorThrown) {}
    });
}
function orderResponse(data){
    
}
/*
function orderListAction(){
*/
    /*********ORDER PAGE JS**************/
    jQuery(document).on('click', '.myofs_action', function () {
        var id   = jQuery(this).attr('id');       
        var rows = jQuery('table tr.' + id);
        if (jQuery(this).hasClass("showdata")) {            
            jQuery(this).removeClass("showdata");
            jQuery(rows).slideUp(200);
            jQuery(".myofs_action i").removeClass("fa-minus").addClass("fa-plus");
            return false;
        } else {            
            jQuery(".myofs_action i").removeClass("fa-minus").addClass("fa-plus");
            jQuery(this).find("i").removeClass("fa-plus").addClass("fa-minus");
            jQuery(this).removeClass("showdata");
            jQuery(this).addClass("showdata");
            jQuery(".order_content").slideUp(200);
            jQuery(rows).slideDown(200);
            return false;
        }
    });
/*
}
*/

