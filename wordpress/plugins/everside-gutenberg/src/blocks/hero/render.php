<?php
namespace Everside;

return function ( $attr, $content ) {
	ob_start();
	?>

  <section <?php Blocks::echoClassNames( [
    'container-full-width',
    'wp-block',
    'wp-block-everside-hero',
    '-wave-bg',
    !empty($attr['bgImageId']) ? 'has-image' : '',
    isset($attr['bgColor']) ? 'background--' . $attr['bgColor'] : '',
    isset($attr['waveColor']) ? '-wave-color wave-color--' . $attr['waveColor'] : '',
    isset($attr['className']) ? $attr['className'] : '', // custom classes from user
    ]); ?>
    style="<?php echo isset($attr['bgImageId']) ? sprintf('background-image: url(%s);', wp_get_attachment_image_url($attr['bgImageId'], 'full')) : ''; ?>">
  <div class="container">
    <div class="row">
      <div class="<?php echo !empty($attr['bgImageId']) ? 'col-sm-6' : 'col-sm-12'; ?> content-area fade-in-left">
        <?php echo $content; ?>
      </div>
      <div class="col-sm-6 content-image">
        <?php
          if( isset($attr['bgMobileImageId']) )
            echo wp_get_attachment_image( $attr['bgMobileImageId'], 'medium-large', false, [ 'class' => 'img-fluid' ] );
          ?>
      </div>
    </div>
  </div>
  </section>

	<?php
	return ob_get_clean();
};

