/**
 * WordPress Block Styles
 */

 const { __ } = wp.i18n;
 const { registerBlockStyle } = wp.blocks;
   
 wp.domReady( () => {
 
 wp.blocks.registerBlockStyle( 'core/quote', {
     name: 'osom-quote',
     label: 'Dakota',
 } ); 
 
 } );
 
  