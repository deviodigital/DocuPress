<?php

/**
 * The plugin bootstrap file
 *
 * @package DocuPress
 * @author  Robert DeVore <contact@deviodigital.com>
 * @license GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link    https://deviodigital.com
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       DocuPress
 * Plugin URI:        https://www.robertdevore.com/docupress
 * Description:       Documentation simplified.
 * Version:           3.1.0
 * Author:            Devio Digital
 * Author URI:        https://deviodigital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       docupress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    wp_die();
}

require 'vendor/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/deviodigital/DocuPress/',
	__FILE__,
	'DocuPress'
);

// Set the branch that contains the stable release.
$myUpdateChecker->setBranch( 'main' );

// Current plugin version.
define( 'DOCUPRESS_VERSION', '3.1.0' );

// Check if Composer's autoloader is already registered globally.
if ( ! class_exists( 'RobertDevore\WPComCheck\WPComPluginHandler' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use RobertDevore\WPComCheck\WPComPluginHandler;

new WPComPluginHandler( plugin_basename( __FILE__ ), 'https://robertdevore.com/why-this-plugin-doesnt-support-wordpress-com-hosting/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-docupress-activator.php
 * 
 * @return void
 */
function activate_docupress() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-docupress-activator.php';
    DocuPress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-docupress-deactivator.php
 * 
 * @return void
 */
function deactivate_docupress() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-docupress-deactivator.php';
    DocuPress_Deactivator::deactivate();
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
 * @since  1.0.0
 * @return void
 */
function run_docupress() {
    $plugin = new DocuPress();
    $plugin->run();
}
run_docupress();
