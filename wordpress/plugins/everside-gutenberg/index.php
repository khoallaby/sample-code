<?php
/**
 * Plugin Name: Everside — Gutenberg Blocks
 * Plugin URI: https://github.com/ahmadawais/create-guten-block/
 * Description: Everside — is a Gutenberg plugin created via create-guten-block.
 * Author: mrahmadawais, maedahbatool
 * Author URI: https://AhmadAwais.com/
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EVERSIDE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Block Initializer.
 */
require_once dirname( __FILE__ ) . '/src/controllers/index.php';
require_once dirname( __FILE__ ) . '/src/controllers/Assets.php';
require_once dirname( __FILE__ ) . '/src/controllers/blocks.php';
require_once dirname( __FILE__ ) . '/src/controllers/colors.php';
require_once dirname( __FILE__ ) . '/src/controllers/Modals.php';
require_once dirname( __FILE__ ) . '/src/controllers/Rest.php';
require_once dirname( __FILE__ ) . '/src/controllers/Utilities.php';
