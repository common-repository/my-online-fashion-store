<?php
/**
 * My Online Fashion Store activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.3
 * @package    my-online-fashion-store
 * @subpackage my-online-fashion-store/includes
 * @author     CCwholesaleclothing Team <info@ccwholesaleclothing.com>
 */
class MYOFS_Activator {

	/**
	 * when the plugin activate create table and store option on the database
	 *
	 * @since    1.1.3
	 */
	public static function activate() {

		// Don't do redirects when multiple plugins are bulk activated
		$checked = sanitize_text_field($_POST['checked']);
		$actin   = sanitize_text_field($_REQUEST['action']);
		if ( ( isset( $actin ) && 'activate-selected' === $actin ) &&
			( isset( $checked ) && count( $checked ) > 1 ) ) 
		{
			return;
		}
	}
}