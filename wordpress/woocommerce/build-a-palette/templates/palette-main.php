<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

global $post, $product;

$images = '';


$product_palette_id = get_post_meta($post->ID, '_palette_product_id', true);
$product_palette = new WC_Product_Variable( $product_palette_id );




$gabriel = gabriel::get_instance();

$max_colors = 4;
?>
<div class="custom-palette-image" style="display: none;"><?php echo get_the_post_thumbnail_url(); ?></div>

<div class="custom-palette-colors">
    <ul>
    <?php foreach( range(1, $max_colors) as $i ) { ?>
        <li><a href="#custom-palette-color-<?php echo $i; ?>">Color <?php echo $i; ?></a></li>
    <?php } ?>
    </ul>

	<?php foreach( range(1, $max_colors) as $i ) { ?>
    <div id="custom-palette-color-<?php echo $i; ?>" class="custom-palette-color" data-color-id="<?php echo $i;?>">
        <div class="custom-palette-color-label swatch-label">&nbsp;</div>
        <?php $gabriel->woocommerce_display_swatches( $product->id ); ?>
    </div>
    <?php } ?>

    <div class="clear"></div>
</div>
