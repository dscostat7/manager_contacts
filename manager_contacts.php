<?php
/**
* Plugin Name: Manager Contacts
* Plugin URI: https://github.com/dscostat7/manager_contacts
* Description: Plugin for management many contacts
* Version: 1.0.0
* Author: Diego Souza
* Author URI: https://beacons.ai/dscostat7
* License: GPL2
*/

if (!defined('ABSPATH')) exit;

define('PEOPLE_CONTACT_PATH', dirname(__FILE__));
define('PEOPLE_CONTACT_TEMPLATE_PATH', PEOPLE_CONTACT_PATH . '/templates');
define('PEOPLE_CONTACT_FOLDER', dirname(plugin_basename(__FILE__)));
define('PEOPLE_CONTACT_URL', str_replace(array('http:','https:'), '', untrailingslashit(plugins_url('/', __FILE__))) );
define('PEOPLE_CONTACT_DIR', WP_PLUGIN_DIR . '/' . PEOPLE_CONTACT_FOLDER);
define('PEOPLE_CONTACT_NAME', plugin_basename(__FILE__));
define('PEOPLE_CONTACT_TEMPLATE_URL', PEOPLE_CONTACT_URL . '/templates');
define('PEOPLE_CONTACT_CSS_URL', PEOPLE_CONTACT_URL . '/assets/css');
define('PEOPLE_CONTACT_JS_URL', PEOPLE_CONTACT_URL . '/assets/js');
define('PEOPLE_CONTACT_IMAGE_URL', PEOPLE_CONTACT_URL . '/assets/images');

if (!defined("PEOPLE_CONTACT_ULTIMATE_URI")) define("PEOPLE_CONTACT_ULTIMATE_URI", "https://a3rev.com/shop/contact-people-ultimate/");

define( 'PEOPLE_CONTACT_KEY', 'contact_us_page_contact_people' );
define( 'PEOPLE_CONTACT_PREFIX', 'people_contact_' );
define( 'PEOPLE_CONTACT_VERSION', '3.6.1' );
define( 'PEOPLE_CONTACT_G_FONTS', true );

use \A3Rev\ContactPeople\FrameWork;

if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	require __DIR__ . '/vendor/autoload.php';

	new \A3Rev\ContactPeople\Ajax();

	global $people_contact_wpml;
	$people_contact_wpml = new \A3Rev\ContactPeople\WPML_Functions();

	/**
	 * Plugin Framework init
	 */
	$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'] = new FrameWork\Admin_Interface();

	global $people_contact_settings_page;
	$people_contact_settings_page = new FrameWork\Pages\People_Contact();

	$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init'] = new FrameWork\Admin_Init();

	$GLOBALS[PEOPLE_CONTACT_PREFIX.'less'] = new FrameWork\Less_Sass();

	// End - Plugin Framework init

	global $people_contact;
	$people_contact = new \A3Rev\ContactPeople\Main();

	new \A3Rev\ContactPeople\Shortcode();

	// Gutenberg blocks init
	new \A3Rev\ContactPeople\Blocks();
	new \A3Rev\ContactPeople\Blocks\Profile();
	
} else {
	return;
}

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/manager-contacts-page-contact-people/manager-contacts-page-contact-people-LOCALE.mo
 * 	 	- WP_LANG_DIR/plugins/manager-contacts-page-contact-people-LOCALE.mo
 * 	 	- /wp-content/plugins/manager-contacts-page-contact-people/languages/manager-contacts-page-contact-people-LOCALE.mo (which if not found falls back to)
 */
function wp_people_contact_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'manager-contacts-page-contact-people' );

	load_textdomain( 'manager-contacts-page-contact-people', WP_LANG_DIR . '/manager-contacts-page-contact-people/manager-contacts-page-contact-people-' . $locale . '.mo' );
	load_plugin_textdomain( 'manager-contacts-page-contact-people', false, PEOPLE_CONTACT_FOLDER.'/languages' );
}

// Editor
include 'tinymce3/tinymce.php';

include ('admin/people-contact-init.php');

/**
 * Call when the plugin is activated
 */
register_activation_hook(__FILE__, 'people_contact_install');
