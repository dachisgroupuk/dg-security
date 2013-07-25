<?php
/**
 * DG Security.
 *
 * A plugin to strengthen the default WordPress password strength indicator.
 * It will also force strong passwords for administrators
 *
 * @package   Dg_Security
 * @author    Ross Tweedie <ross.tweedie@dachisgroup.com>
 * @license   GPL-2.0+
 * @link      http://labs.dachisgroup.com
 * @copyright 2013 Dachis Group
 *
 * @wordpress-plugin
 * Plugin Name: Dg Security
 * Plugin URI:  http://labs.dachisgroup.com
 * Description: Strengthen the default WordPress passwords
 * Version:     1.0.0
 * Author:      rtweedie
 * Author URI:  http://labs.dachisgroup.com
 * Text Domain: dg-security-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-dg-security.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Dg_Security', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Dg_Security', 'deactivate' ) );

Dg_Security::get_instance();
