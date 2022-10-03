<?php

class Gsmtc_Block_Styles_Admin{
    
    /**
     * nucleo
     *
     * This method is used as a controler to select the correct method depending of the request
     * 
     * @return void
     */
    public function nucleo(){
        if (isset($_GET['name']))
            $this->edit_block_style($_GET['name']);
        else    
            $this->admin();
    }
    
    /**
     * edit_block_style
     *
     * This method is used to edit the diferent specific classes css of a block
     *  
     * @param  string $name
     * @return void
     */
    public function edit_block_style($name){

        $args = array(
            'post_type' => 'gsmtc_block_style',
            'meta_query' => array(
                array(
                    'key'     => 'gsmtc_block_style',
                    'value'   => $name,
                    'compare' => '=',
                ),
            ),
        );
        $query = new WP_Query( $args );
        $posts = $query->posts;

//        error_log ("Custom posts type 'gsmtc_block_style' : ".var_export($posts,true));

        ?>
        <script type="text/javascript">   
            const wpApi = {
                "restUrl":"<?php echo rest_url( '/gsmtc/custom_block_styles' ); ?>",
                "nonce":"<?php echo wp_create_nonce('wp_rest') ?>",
                "adminTitle":"<?php echo __('Gesimatica Block Styles','gsmtc-block-styles') ?>",
                "statement":"<?php echo __('Edit the block style : ','gsmtc-block-styles') ?>",
                "blockName":"<?php echo $name ?>",
                "formNewClassTitle":"<?php echo __('Introduce a new css class for the block','gsmtc-block-styles') ?>",
                "inputNewClassLabel":"<?php echo __('Set the label for the class : ','gsmtc-block-styles') ?>",
                "buttonAddLabel":"<?php echo __('Add class','gsmtc-block-styles') ?>",
                "formClassTitle":"<?php echo __('Edit the css class for the block','gsmtc-block-styles') ?>",
                "inputClassLabel":"<?php echo __('Label for the class','gsmtc-block-styles') ?>",
                "buttonUpdateLabel":"<?php echo __('Update class','gsmtc-block-styles') ?>",
                "buttonDeleteLabel":"<?php echo __('Delete class','gsmtc-block-styles') ?>",
                "noticeInProgress":"<?php echo __('Please wait','gsmtc-block-styles') ?>",
                "noticeSuccess":"<?php echo __('Success action','gsmtc-block-styles') ?>",
                "noticeError":"<?php echo __('Error','gsmtc-block-styles') ?>",               
            };

        </script>
        <div id="gsmtc-block-styles"></div>
        <script src="<?php echo PLUGIN_DIR_URL.'assets/js/index.f2d62c2a.js' ?>"></script>
        <?php
    }
    
    public function admin(){
        $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
        $block_types_count = count($block_types);
        
        error_log (" Hay : ".var_export($block_types_count,true)." tipos de bloques".PHP_EOL);
        
        $categorias = array();
        foreach($block_types as $block_type){
            $categoria = $block_type->category;
            if ( ! array_search($categoria,$categorias)){
                    $categorias[$categoria] = $categoria;
                }
//            error_log (" Categoria de bloque : ".var_export($block_type->category,true).PHP_EOL);
            error_log (" Tipo de bloque : ".var_export($block_type,true).PHP_EOL);
        }
        sort($categorias);
        $categorias_count = count($categorias);
//        error_log (" Categorias de bloque : ".var_export($categorias,true).PHP_EOL);

        ?>
         <div class="gsmtc-admin-wrap">
            <div class="gsmtc-admin-header">
                <h1 class="gsmtc-admin-title">Gesimatica block styles</h1>
                <p class="gsmtc-admin-paragraph"><?php echo __('There are ','gsmtc-block-styles').$block_types_count.
                __(' blocks registered in server site, organized in ','gsmtc-block-styles').$categorias_count.__(' categories.',''); ?></p>
            </div><!-- gsmtc-admin-header -->
            <div class="gsmtc-admin-body">
                <div class="gsmtc-admin-acordeon-body">
                <?php foreach($categorias as $categoria){
                 ?>
                <div class="gsmtc-admin-button-accordeon"><h3><?php echo __('Category : ','gsmtc-block-styles').$categoria; ?></h3></div>
                <div class="gsmtc-admin-body-items">
                 <?php 
                    foreach($block_types as $block_type){
                        if ($block_type->category == $categoria){
                           ?>
                            <div class="gsmtc-admin-item">
                                <h4><?php echo $block_type->title; ?></h4>
                                <p><?php echo $block_type->description; ?></p>
                                <div class="gsmtc-admin-item-button">
                                    <a class="btn btn-primary align-middle ms-3" href="<?php echo admin_url('admin.php?page=gsmtc-block-styles&name='.$block_type->name) ?>" role="button"><?php  _e('Edit block','gsmtc-block-styles'); ?></a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                </div><!-- gsmtc-admin-body-items -->
                </div><!-- gsmtc-admin-acordeon-body -->
            </div><!-- gsmtc-admin-body -->
        </div><!-- gsmtc-admin-wrap -->
        <script type="text/javascript">
        
        </script>
        <?php
    }
}