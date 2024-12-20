<?php
namespace Cornerstone\Theme;

$favorites = cornerstone_get_favorites();


if( $properties ) :

foreach( (array) $properties as $property ) :
    if( isset($apartmentData[ $property->ID ]) && isset($apartmentData[ $property->ID ]['available-apts']) )
        $apartmentCount = count($apartmentData[ $property->ID ]['available-apts']);
    else
        $apartmentCount = 0;

    $availableText = sprintf( _n( '%s unit', '%s units', $apartmentCount, 'spirebase' ), $apartmentCount );
    $rents = isset($apartmentData[ $property->ID ]['rent']) ? $apartmentData[ $property->ID ]['rent'] : [];
    $pets = Pets::getPets($property->ID, true);
    ?>
	<article
        id="post-<?php echo $property->ID; ?>"
        <?php post_class( '', $property->ID ); ?>
        data-property-id="<?php echo $property->ID; ?>"
        data-rent="<?php echo Apartments::theRent( $rents, true );?>"
        data-latitude="<?php echo get_post_meta( $property->ID, 'Latitude', true ); ?>"
        data-longitude="<?php echo get_post_meta( $property->ID, 'Longitude', true ); ?>"
    >

        <?php
            $images = Properties::getImages( $property->ID, 10 );
            if( !empty($images) ) :
        ?>
        <figure>
          <?php
          $promo_text = get_post_meta( $property->ID, 'property_promo_text', true );
          $promo_apartments = get_field('promo_apartments', $property->ID );
          $has_promo = !empty($promo_text) && $promo_apartments && !empty( array_intersect($promo_apartments, $apartmentData[$property->ID]['available-apts']) );

          if( $has_promo ) : ?>
            <div class="promo-container">
              <span class="promo-text">
                <?php echo $promo_text; ?>
              </span>
            </div>
          <?php endif; ?>

            <div class="slider-property">
            <?php
                foreach( $images as $image ) :
                    echo sprintf( '<div class="post-thumbnail" id="post-thumbnail-%d" style="background-image: url(%s);"><a class="link-overlay" href="%s" target="_blank"></a></div>',
                        $property->ID,
                        wp_get_attachment_image_url( $image['ID'], 'property-search', false ),
                        get_the_permalink($property->ID)
                    );
                endforeach;
            ?>
            </div>
            <a href="#" class="favorite <?php echo in_array( $property->ID, $favorites ) ? 'isFavorite' : ''; ?>" data-id="<?php echo $property->ID; ?>"></a>
        </figure>
        <?php endif; ?>

        <section>
            <!--
                ***** Redacting certain code sections to avoid providing full source code *****

                This section just displays an apartment's neighborhood, name, address, price range, how many units are available
                As well as other meta data like amenities, # of beds/pets. Mainly all pulled via get_post_meta()

            -->
        </section>

	</article>
<?php endforeach; else : ?>
    <div class="error">No results found</div>
<?php endif; ?>
