<?php
$myofs_api = new MYOFS_API();
$callmethod =  $myofs_api->DynamicImagesApi();
$getimage = $callmethod['data']['image']['my_order']; 
?>
<div class="wrap">  
  <div class="myofs-layout" id="myofs-layout__notice-list"></div>
  <div class="apploader"><div class="loader"></div></div>
  <!-- page sidebar  -->
    <?php load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-sidebar-page.php');?>
  <!-- end sidebar -->
  <div class="page-content-wrapper" style="position: relative;">
    <div class="page-content" style="min-height: 547px;">
      <div class="page-bar">
        <h1 class="wp-heading-inline" id="dynamic_heading_title"><?php esc_html_e('My Orders');?></h1>
      </div>

      <div class="row" id="myofs_oprderdata">
        <form action="" class="right quick-find" id="quickfind" method="POST" name="quickfind-form" >
          <input type="text" class="form-control" name="keywords" id="keywords" placeholder="Search...">
          <input type="button" id="clearButton" value="X" />
          <button type="submit" class="quickfind-search"><i class="fa fa-search"></i></button>            
        </form>
        <div class="store_orderlist">
          <table class="table results">
            <thead>
                <tr>
                  <th class="order_head"><?php esc_html_e( 'Order ID' );?></th>
                  <th class="order_head"><?php esc_html_e( 'Date' );?></th>
                  <th class="order_head"><?php esc_html_e( 'Customer' );?></th>
                  <th class="order_head"><?php esc_html_e( 'Status' );?></th>
                  <th class="order_head"><?php esc_html_e( 'Total' );?></th>
                  <th class="order_head"><?php esc_html_e( 'Action' );?></th>
                </tr>               
            </thead>
            <tbody class="order_data">
                
            </tbody>
          </table>

        </div><!-- load store Orders -->
        <div class="order_pagination">
        </div>
        <div class="myorders">
          <div class="container">
            <img src="<?php echo esc_url($getimage['order_banner1']);?>" alt="my orders banner">
            <h2><?php esc_html_e( 'HOW TO PLACE ORDERS');?></h2>
            <p>
              <?php esc_html_e( 'Currently you do have to place the order manually on our wholesale website');?>
			  <a href="<?php echo MYOFS_STORE_URL;?>" target="_blank"><?php esc_html_e( 'ccwholesaleclothing.com.');?></a>
            </p>
            <h3><b><?php esc_html_e( 'STEP#1'); ?></b></h3>
            <p>
              <?php esc_html_e( 'please make sure you create an account on our wholesale website ccwholesaleclothing.com notify us via email at '); ?><a href="mailto:<?php echo MYOFS_STORE_URL;?>" target="_blank"><?php echo sanitize_email('info@ccwholesaleclothing.com');?></a> <?php esc_html_e( 'once an account has been created so we can change your account status to dropship account.');?>
            </p>
            <p><?php esc_html_e( 'Once we are notified we will update your account and email you confirmation within 12-24 hours.'); ?></p>
            <p>
              <?php esc_html_e( 'Min $100 requirement per order will be removed once your account is setup on '); ?><a href="<?php echo MYOFS_STORE_URL;?>" target="_blank"> <?php echo sanitize_email('ccwholesaleclothing.com');?> </a> <?php esc_html_e( 'as a dropship account.');?>
            </p>
            <p><?php esc_html_e( 'Once you receive an order, go to');?> <a href="https://www.ccwholesaleclothing.com" target="_blank"> <?php echo sanitize_email('ccwholesaleclothing.com');?> </a> <?php esc_html_e( 'and place the order to be blind shipped to your customer.Please see steps below.'); ?></p>
            <h3><b><?php esc_html_e( 'STEP#2'); ?></b></h3>
              <ul>
                <li><?php esc_html_e( 'Your customer will order from you and pay you for the item.'); ?></li>
                <li><?php esc_html_e( 'You than logon to our wholesale website'); ?><a href="<?php echo MYOFS_STORE_URL;?>" target="_blank"> <?php echo sanitize_email('ccwholesaleclothing.com');?> </a> <?php esc_html_e( 'to your account that has been setup as a dropshipper account.');?></li>
                <li><?php esc_html_e( 'Find the item your customer ordered from you'); ?></li>
                <li><?php esc_html_e( 'Add to your cart'); ?></li>
                <li><?php esc_html_e( 'Go thru checkout process'); ?></li>
                <li><?php esc_html_e( 'Put your info under billing'); ?></li>
                <li><?php esc_html_e( 'Put your customers info under shipping'); ?></li>
                <li><?php esc_html_e( 'Pay and finalize the order.'); ?></li>
                <li><?php esc_html_e( 'Once the order is placed we will process, ship and email you confirmation &amp; tracking info.'); ?></li>
                <li><?php esc_html_e( 'You can than notify your customer that the order has been shipped and provide them with tracking info.'); ?></li>
              </ul>
            <p class="yel_bg">
              <?php esc_html_e( 'If you are going to process more than 10 orders a day, we can offer a complimentary service where you can supply as with spreadsheet file of your orders daily by email and we will take care of the rest for you.'); ?>
            </p>
            <p><?php esc_html_e( 'IMPORTANT INFO WHEN SEARCHING FOR AN ITEM!'); ?></p>
            <p><?php esc_html_e( 'When searching for an item on'); ?> <a href="<?php echo MYOFS_STORE_URL;?>" target="_blank"> <?php esc_html_e( 'ccwholesaleclothing.com');?> </a> <?php esc_html_e( 'to purchase for your customer please see instructions below');?></p>
            <p><?php esc_html_e( 'example. you have an order for sku: 34233b.1xl'); ?></p>
            <p><?php esc_html_e( 'only type in the search box 34233b or 34233 please leave out .1xl'); ?></p>
            <p><?php esc_html_e( 'when you search using 34233b.1xl the item will not come up after the search.'); ?></p>
            <p class="yel_bg"><?php esc_html_e( 'PLEASE NOTE WE RESTOCK ITEMS DAILY, IF YOU ARE NOT FINDING THE ITEM YOU ARE LOOKING FOR, PLEASE CONTACT US VIA EMAIL AT '); ?>
                <a href="mailto:<?php echo sanitize_email('INFO@CCWHOLESALECLOTHING.COM');?>"><?php echo sanitize_email('INFO@CCWHOLESALECLOTHING.COM');?></a> <?php esc_html_e( 'AND WE WILL CHECK TO SEE WHEN THE ITEM YOU ARE LOOKING FOR WILL BE RESTOCKED.');?>
            </p>
            <h4>
              <a href="<?php echo MYOFS_SHIPPING_PATH;?>" target="_blank"> <?php esc_html_e( 'PLEASE CLICK HERE FOR MORE SHIPPING INFO');?> </a>
            </h4>
            <a href="<?php echo $getimage['order_banner2']['link'];?>" target="_blank">
              <img src="<?php echo esc_url($getimage['order_banner2']['url']);?>" alt="custom packing">
            </a>
            <h3 class="brand">
              <?php esc_html_e( 'BUILD BRAND AWARENESS, PROMOTE YOUR BUSINESS, BE UNIQUE! You can now personalize your packages being dropshipped to your customers.'); ?>
              <?php esc_html_e( 'Build your brand one order at a time &amp; stand out from the rest!'); ?>
             </h3>
             <p><?php esc_html_e( 'CUSTOMIZE YOUR PACKAGES WITH ONE OR ALL THREE OF OUR AVAILABLE OPTIONS BELOW!'); ?></p>
             <ul class="list_style_decimal">
                <li>
                  <h3>
                    <a href="<?php echo MYOFS_PRODUCT_CFI; ?>" target="_blank"><?php esc_html_e( '1. CUSTOM PROMO FLYER INSERT'); ?></a>
                  </h3>
                  <p>
                   <?php esc_html_e( ' Send a thank you note to your customers while offering them a special promotion to be used towards there next purchase.'); ?>
                  </p>
                  <p>
                    <?php esc_html_e( 'We will insert your own custom designed promo flyer in every package we ship for you.'); ?>
                  </p>
                  <p>
                    <?php esc_html_e( 'Add your logo, website name, website message, special promo!'); ?>
                  </p>
                </li>
                <li>
                  <h3>
                    <a href="<?php echo MYOFS_PRODUCT_CL;?>" target="_blank"><?php esc_html_e( '2. CUSTOM PACKAGE COVER LABEL'); ?></a>
                  </h3>
                  <p>
                   <?php esc_html_e( 'First impression is very important!'); ?>
                  </p>
                  <p>
                   <?php esc_html_e( 'Your customer will know exactly who the package is from before they open the order,'); ?>
                  </p>
                  <p>
                   <?php esc_html_e( 'this option is a wonderful way to make your packaging pop. Perfect way to be unique and build brand awareness.'); ?>
                  </p>
                  <p>
                   <?php esc_html_e( 'Add your logo, website name, website message!'); ?>
                  </p>
                </li>
                <li>
                  <h3>
                    <a href="<?php echo MYOFS_PRODUCT_CHT;?>" target="_blank"><?php esc_html_e( '3. CUSTOM CLOTHING HANG-TAG'); ?></a>
                  </h3>
                   <p><?php esc_html_e( 'Add a personal touch to every single clothing item purchased,'); ?></p>
                   <p><?php esc_html_e( 'adding your own custom hang tag will raise brand awareness and add unique touch to every single unit you sell'); ?>.</p>
                   <p><?php esc_html_e( 'Add your logo, website name, website message!'); ?></p>
                </li>
             </ul>
              <h4>
                <a href="<?php echo MYOFS_CUSTOMPACKEING_PATH;?>" target="_blank"> <?php esc_html_e( 'PLEASE CLICK HERE FOR MORE CUSTOM PACKAGING INFO '); ?></a>
              </h4>
             <img src="<?php echo esc_url($getimage['order_banner3']);?>" alt="other apps banner">
             <div class="cpio-wrap" data-position="ehe"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" id="admin-url" class="admin-url" type="hidden" />
</div>  
<div class="myofs_plugin_version">
	<span><?php esc_html_e(MYOFS_FOOTER); ?></span>
</div>