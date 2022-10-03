const { __ } = wp.i18n;
const { registerBlockStyle } = wp.blocks;

	wp.domReady( () => {
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-label1',
			label: 'label1',
		});
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-explorer2',
			label: 'explorer2',
		});
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-label2',
			label: 'label2',
		});
		wp.blocks.registerBlockStyle( 'core/button',{
			name: 'gsmtc-explorer',
			label: 'explorer',
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