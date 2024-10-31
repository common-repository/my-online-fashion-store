<?php
//Get the active tab from the $_GET param
$default_tab = null;
$gettab = sanitize_text_field( filter_input( INPUT_GET, 'tab' ));
$tab =  isset($gettab)&& !empty($gettab) ? $gettab : $default_tab;
$tabs = array( 'my-inventory' => 'My Inventory', 'my-orders' => 'My Orders', 'marketing-marterial' => 'Marketing Material','my-account' => 'My Account','help' => 'Help','returns' => 'Returns','upgrade-save' => 'Upgrade & Save' );
?>
<!-- Our admin page content should all be inside .wrap -->
<div class="wrap">
	<?php
	ob_start();
	do_action('myofs_maintenance_mode');
	$content = ob_get_contents();
	ob_end_clean();
	if (isset($content) && !empty($content)) {
	   echo $content;
	} else {
	    ?>
			<!-- Here are our tabs -->
			<nav class="nav-tab-wrapper">
			  <a href="?page=myofs-all-inventory" class="nav-tab <?php if($tab === null || $tab == 'all-inventory'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'All Inventory', 'my-online-fashion-store' ); ?></a>
			  <?php
				foreach( $tabs as $tab_key => $tab_name ){
					$class = ( $tab == $tab_key) ? sanitize_html_class('nav-tab-active') : '';
					$url = '?page=myofs-all-inventory&tab='.$tab_key;
					printf(
						wp_kses(
							__('<a class="nav-tab %1$s" href="%2$s">%3$s</a>','my-online-fashion-store'),
							array(
								'a' => array(
									'href' => array(),
									'class' => array()
								)
							)
						),
						$class,
						$url,
						$tab_name
					);
				}
			  ?>
			</nav>
			<div class="tab-content">
				<?php switch($tab) :      
					case 'my-inventory':
							load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-my-inventory-page.php');
						break;
					case 'my-orders':
							load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-my-orders-page.php');  
						break;
					case 'marketing-marterial':
							load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-marketing-marterial-page.php'); 
						break;
					case 'my-account':
							load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-my-account-page.php');
						break;
					case 'help':
							load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-help-page.php'); 
						break;
					case 'returns':
							load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-returns-page.php'); 
						break;
					case 'upgrade-save':
							load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-upgrade-save-page.php');
						break;
				  default:
						load_template(MYOFS_PLUGIN_TEMPLATE_PATH.'myofs-all-inventory-page.php'); 
					break;
				endswitch; ?>
			</div>
	    <?php
	}
	?>
</div>
	