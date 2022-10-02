<?php
/**
 * @package Gsmtc-Block-Styles
 */
/*
Plugin Name: Gesimatica Custom Block Styles
Plugin URI:  https://carmeload.com/
Description: Plugin para añadir variaciones de estilos a los bloques.
Version:     0.1
Author:      Carmelo Andres
Author URI:  https://carmeloandres.com
Text Domain: gsmtc-block-styles
License:     GPLv2 or later
Domain Path: /Languages
 */

/**
 *  Fuente: https://www.esthersola.com/crear-block-styles-o-estilos-personalizados-para-los-bloques-nativos/
 */

 if ( ! defined( 'ABSPATH' ) ) {die;} ; // to prevent direct access

/**
 * @version 0.1 used to check version and existence of plugin
 */
if( ! defined('GSMTC_BLOCK_STYLES_VERSION')) define( 'GSMTC_BLOCK_STYLES_VERSION', '0.1' ); // used to check version and existence of plugin

if( ! defined('PLUGIN_DIR_URL')) define ('PLUGIN_DIR_URL',plugin_dir_url(__FILE__));
if( ! defined('PLUGIN_DIR_PATH')) define ('PLUGIN_DIR_PATH',plugin_dir_path(__FILE__));

require_once(dirname(__FILE__).'/includes/class-gsmtc-block-styles.php'); 



$gsmtcBlockStyles = new Gsmtc_Block_Styles();

//add_action('admin_enqueue_scripts', array($gsmtcBlockStyles,'enqueue_admin_style'));

/**
 * Creamos la función para cargar el javascript que añadira variaciones de estilos a los diferentes bloques
 */
function osom_block_styles_enqueue() {
	wp_enqueue_script(
		'gsmtc-block-styles',
		plugin_dir_url( __FILE__ ). 'js/block-styles.js',
		array( 'wp-blocks', 'wp-dom-ready' ),
		'1',
		true
	);  
}
add_action( 'enqueue_block_editor_assets', 'osom_block_styles_enqueue' );

/**
 * Función para cargar los estilos
 */
function gsmtc_enqueue_block_assets(){

    wp_enqueue_style( 'gsmtc-block-styles', plugin_dir_url( __FILE__ ). 'css/block-styles.css', array(), '1' );

} 
add_action( 'enqueue_block_assets', 'gsmtc_enqueue_block_assets' );

/**
 * Función leer los tipos de bloques que hay registrador en el servidor
 * se ejecuta al activar el plugin
 */

 function gsmtc_blocks_style_activate(){
	$block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

	$block_types_count = count($block_types);
	error_log (" Hay : ".var_export($block_types_count,true)." tipos de bloques".PHP_EOL); 
	foreach($block_types as $block_type){
		error_log (" Tipo de bloque : ".var_export($block_type,true).PHP_EOL);
	}
//	error_log (" Los tipos de bloques registrador en el servidor son : ".PHP_EOL.var_export($block_types,true)); 
 }
 register_activation_hook(__FILE__,'gsmtc_blocks_style_activate');
