const { __ } = wp.i18n;
const { registerBlockStyle } = wp.blocks;

	wp.domReady( () => {
		wp.blocks.registerBlockStyle( 'core/quote',{
			name: 'gsmtc-verde',
			label: 'verde',
		});
		wp.blocks.registerBlockStyle( 'core/quote',{
			name: 'gsmtc-naranja',
			label: 'naranja',
		});
		wp.blocks.registerBlockStyle( 'core/paragraph',{
			name: 'gsmtc-rojo',
			label: 'rojo',
		});
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-label10',
			label: 'label10',
		});
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-explorer10',
			label: 'explorer10',
		});
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-explorer05',
			label: 'explorer05',
		});
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-explorer01',
			label: 'explorer01',
		});
		wp.blocks.registerBlockStyle( 'core/quote',{
			name: 'gsmtc-explorer',
			label: 'explorer',
		});
		wp.blocks.registerBlockStyle( 'core/buttons',{
			name: 'gsmtc-label0',
			label: 'label0',
		});
		wp.blocks.registerBlockStyle( 'core/nextpage',{
			name: 'gsmtc-label0',
			label: 'label0',
		});
} );