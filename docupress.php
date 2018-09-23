<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.robertdevore.com/
 * @since             1.0.0
 * @package           DocuPress
 *
 * @wordpress-plugin
 * Plugin Name:       DocuPress
 * Plugin URI:        https://www.robertdevore.com/docupress
 * Description:       Documentation simplified.
 * Version:           1.2
 * Author:            Robert DeVore
 * Author URI:        https://www.robertdevore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       docupress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-docupress-activator.php
 */
function activate_docupress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-docupress-activator.php';
	Docupress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-docupress-deactivator.php
 */
function deactivate_docupress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-docupress-deactivator.php';
	Docupress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_docupress' );
register_deactivation_hook( __FILE__, 'deactivate_docupress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-docupress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_docupress() {

	$plugin = new DocuPress();
	$plugin->run();

}
run_docupress();
