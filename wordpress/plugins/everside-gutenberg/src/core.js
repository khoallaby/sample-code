// https://stackoverflow.com/questions/56079022/add-bootstrap-button-styles-to-default-gutenberg-button-block-in-wordpress

wp.domReady( () => {
  wp.blocks.unregisterBlockStyle( 'core/button', 'outline');
  wp.blocks.unregisterBlockStyle( 'core/button', 'fill');
  wp.blocks.registerBlockStyle( 'core/button', {
    name: 'bootstrap-default',
    label: 'Bootstrap Default',
    isDefault: true
  });
  wp.blocks.registerBlockStyle( 'core/button', {
    name: 'bootstrap-primary',
    label: 'Bootstrap Primary',
  });
  wp.blocks.registerBlockStyle( 'core/button', {
    name: 'bootstrap-success',
    label: 'Bootstrap Success',
  });
  wp.blocks.registerBlockStyle( 'core/button', {
    name: 'bootstrap-default-lg',
    label: 'Bootstrap Default Large',
  });
  wp.blocks.registerBlockStyle( 'core/button', {
    name: 'bootstrap-primary-lg',
    label: 'Bootstrap Primary Large',
  });
  wp.blocks.registerBlockStyle( 'core/button', {
    name: 'bootstrap-success-lg',
    label: 'Bootstrap Success Large',
  });

} );
