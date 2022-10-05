<?php

require_once(dirname(__FILE__).'/class-gsmtc-block-styles-admin.php'); 

/**
 * Gsmtc_Block_styles
 * 
 * This class manage the core of plugin
 */
class Gsmtc_Block_Styles{

    public $admin;

	function __construct(){

        $this->admin = new Gsmtc_Block_Styles_Admin();

        add_action('init',array($this,'block_styles_post_type'));
		add_action('admin_menu',array($this,'admin_menu'));
		add_action('enqueue_block_editor_assets', array($this,'editor_block_styles_enqueue') );
        add_action('admin_enqueue_scripts', array($this,'enqueue_admin_style'));
		add_action('rest_api_init',array($this,'endpoints')); 
    }

	function editor_block_styles_enqueue(){
		$full_path_file = PLUGIN_DIR_PATH.'assets/js/gsmtc-block-styles.js';
		error_log('path of javascript : '.var_export($full_path_file,true));
		if (file_exists($full_path_file)){
			wp_enqueue_script(
				'gsmtc-block-styles',
				PLUGIN_DIR_URL. 'assets/js/gsmtc-block-styles.js',
				array( 'wp-blocks', 'wp-dom-ready' ),
				'1',
				true
			);  		
		}
		else error_log('El fichero : '.var_export($full_path_file,true).' no existe.');
	}

	/**
	 * This method is fired in the activation
	 */
	function activate(){
		$this->block_styles_post_type();
		flush_rewrite_rules();
	}

	/**
	 * This method is fires in the deactivation
	 */
	function deactivate(){
		flush_rewrite_rules();
	}
	
	/**
	 * endpoints
	 * 
	 * This mehtod is used to set up the endpoints of the rest api
	 *
	 * @return void
	 */
	function endpoints(){
		register_rest_route('gsmtc','custom_block_styles',array(
			'methods'  => 'POST',
			'callback' => array($this,'custom_styles'),
			'permission_callback' => array($this,'get_custom_styles_permissions_check'),			
	
		));
	}
	
	/**
	 * get_custom_styles
	 * 
	 * This method manage de recuest of the endpoints 
	 *
	 * @return void
	 */
		
	function custom_styles(WP_REST_Request $request ){

		$params = $request->get_params();
		$result = json_encode(0);
		error_log('Se ha ejecutado "custom_styles", $param : '.PHP_EOL.var_export($params,true));
		if (isset($params['action'])){
			$action = $this->validate($params['action']);
			error_log ('Estamos dentro del bucle, $action: '.PHP_EOL.var_export($action,true));
			switch ($action){
				case 'save' :
					$result = $this->request_save_custom_styles($params);
					break;
				case 'load':
					$result = $this->request_load_custom_styles($params);
					break;
				case 'delete':
					$result = $this->request_delete_custom_styles($params);
					break;
			}

		} 
		echo $result;
		exit();
		
	}

	function request_delete_custom_styles($params){
		$result = false;
		if (isset($params['idPost'])){
			$result = wp_delete_post ($params['idPost'],true);
		}
		return json_encode($result);
	}

	function request_load_custom_styles($params){
		if (isset($params['blockName'])){
			
				$args = array(
					'post_type' => 'gsmtc_block_style',
					'post_status' => 'draft',
					'numberposts' => -1,
					'meta_query' => array(
						array(
							'key'     => 'gsmtc_block_style',
							'value'   => $params['blockName'],
							'compare' => '=',
						),
					),
				);
				$posts = get_posts($args);
				$result = array();
				foreach( $posts as $post){
					$data = array( 
						'id' => $post->ID,
						'label' => $post->post_title,
						'cssClass' => $post->post_content
					);
					array_push($result,$data); 

				}
				error_log ("Se ha ejecutado request_load_custom_styles, 'gsmtc_block_style' : ".var_export($result,true));
				return json_encode($result);
		
		}else return json_encode([]);
	}
	/**
	 * request_save_custom_styles
	 *
	 * Method to manage the save request from form
	 * 
	 * @param  mixed $params
	 * @return void
	 */
	function request_save_custom_styles($params){
		
		if (isset($params['idPost']) && isset($params['label']) && isset($params['styles']) && isset($params['blockName'])){
			$new_post = array(
				'post_type' => 'gsmtc_block_style',
				'post_title' => $params['label'],
				'post_content' => $params['styles'],
				'meta_input' => array(
					'gsmtc_block_style'=> $params['blockName'],
					)
				);
				
			if ($params['idPost'] > 0)
				$new_post['ID'] = $params['idPost'];
	
			$id_new_post = wp_insert_post($new_post);
			error_log ('Id Nuevo Post : '.var_export($id_new_post,true));
			$this->build_new_assets();
			return json_encode($id_new_post); 
		} else {
			error_log ('No estan todos los parametros necesarios');			
			return json_encode(0); 
		}

	}
	/**
	 * build_new_assets
	 *
	 * This method is used to build new assets files
	 * 
	 * @return void
	 */
	public function build_new_assets(){
		$args = array(
			'numberposts' => '-1',
            'post_type' => 'gsmtc_block_style',
			'post_status' => 'draft'
		);
        
		$posts = get_posts($args);
//		error_log ('List o custom post type "gsmtc_block_style" : '.var_export($posts,true));

		$this->build_css_file($posts);
		$this->build_js_file($posts);
	}

	function build_css_file($posts){
		$file = fopen(PLUGIN_DIR_PATH.'/assets/css/gsmtc-block-styles.css','w');
		foreach( $posts as $post){
			$content = $post->post_content;
			$content ='.'.str_replace('core/','',$content);
//			$content_front = 'blockquote'.$content;
//			fwrite($file,$content_front.PHP_EOL);
			fwrite($file,$content.PHP_EOL);
		}
		fclose($file);

		error_log ('has been executed the function "build_css_files"');

	}
		
	function build_js_file($posts){
		$file = fopen(PLUGIN_DIR_PATH.'/assets/js/gsmtc-block-styles.js','w');
		$header_content='const { __ } = wp.i18n;'.PHP_EOL.
						'const { registerBlockStyle } = wp.blocks;'.PHP_EOL.PHP_EOL.
		  				'	wp.domReady( () => {'.PHP_EOL;
		$footer_content='} );';
		fwrite($file,$header_content);				
		foreach($posts as $post){
			$block_name = get_post_meta($post->ID,'gsmtc_block_style',true);
			$label = $post->post_title;
			$name='gsmtc-'.$label;
			$file_content = "		wp.blocks.registerBlockStyle( '".$block_name."',{".PHP_EOL.
							"			name: '".$name."',".PHP_EOL.
							"			label: '".$label."',".PHP_EOL.
							"		});".PHP_EOL;
			fwrite($file,$file_content);
		}
		/*
		wp.blocks.registerBlockStyle( 'core/quote', {
			name: 'osom-quote',
			label: 'Dakota',
		} ); */
		
		
		fwrite($file,$footer_content);
		fclose($file);
		error_log ('has been executed the function "build_js_files"');

	}

	/**
	 * get_custom_styles_permissions_check
	 * 
	 * Method to manage the access permissions to the endpoints
	 * only administrators can access
	 *
	 * @return void
	 */
	function get_custom_styles_permissions_check(){
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'OMG you can not view private data.', 'gsmtc-block-styles' ), array( 'status' => 401 ) );
		}
	
		// This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
		return true;
	
	}
    
    /**
     * enqueue_admin_style
     *
     * This method is used to enqueue de admin styles.
     * 
     * @return void
     */
    function enqueue_admin_style(){
        wp_enqueue_style('gsmtc-block-styles-admin', PLUGIN_DIR_URL.'assets/css/gsmtc-block-styles-admin.css');
    }
	/**
	 * admin_menu
	 *
	 * This method creates the plugin admin menu
	 * 
	 * @return void
	 */
	function admin_menu(){
		add_menu_page('gsmtc-block-styles','Gesimatica block styles','manage_options','gsmtc-block-styles',array($this->admin,'nucleo'));
	}

	/**
	 * This function creates a custom post type to store the alternative styles for the diferent blocks
	 */
	
	function block_styles_post_type() {
		/* source: https://developer.wordpress.org/reference/functions/register_post_type/ */
		register_post_type('gsmtc_block_style',
			array(
				'label'	=> 'Gsmtc_block_styles',
				'description' 	=> 'Custom post type to store each alternative style , in css, created for the user to the diferent gutenberg blocks',
				'public'  		=> false,
				'exclude_from_search' => false,
				'publicly_queryable' => true,
			)
		);
	}

    /**
     * validate, metho to validate data from external origin. 
     *
     * @param  string $input 
     * @return string
     */
    public function validate($input){

        $resultado = trim($input);
        $resultado = stripslashes($resultado);
        $resultado = htmlspecialchars($resultado);
        
        return $resultado;
    }

}
