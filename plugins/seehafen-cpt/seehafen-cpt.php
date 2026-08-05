<?php
/**
 * Plugin Name:       Seehafen CPTs
 * Plugin URI:        https://github.com/manshu07/seehafen-wordpress
 * Description:       Custom post types and taxonomies for the Seehafen & Partner Immobilien AG site: services, references, offers and team members.
 * Version:           1.0.0
 * Author:            NightMule 9000
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       seehafen
 * Requires PHP:      7.4
 *
 * @package Seehafen_CPT
 */

defined( 'ABSPATH' ) || exit;

define( 'SEEHAFEN_CPT_VERSION', '1.0.0' );
define( 'SEEHAFEN_CPT_PATH', plugin_dir_path( __FILE__ ) );

require_once SEEHAFEN_CPT_PATH . 'includes/class-seehafen-cpt.php';
require_once SEEHAFEN_CPT_PATH . 'includes/class-seehafen-cpt-meta.php';

/**
 * Boot the plugin.
 *
 * @return void
 */
function seehafen_cpt_boot() {
	new Seehafen_CPT();
	new Seehafen_CPT_Meta();
}
add_action( 'plugins_loaded', 'seehafen_cpt_boot' );
