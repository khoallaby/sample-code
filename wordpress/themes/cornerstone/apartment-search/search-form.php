<?php
use Cornerstone\Theme\Properties;
?>
<form class="row apartment-search-form" action="<?php echo esc_url( home_url( 'apartment-search', 'relative' ) ); ?>/" method="GET">
	<section class="col-md-12 search-filter filter-apartments home-search-form">
        <div class="input-wrapper mobile-filters-header">
            <div>
                <a href="#" class="btn-close" aria-label="Close">X</a>
            </div>
            <div class="text-center">
                <h4>Filters</h4>
                <div class="search-totals">
                    <?php echo sprintf( '<span class="start">%d</span> - <span class="end">%d</span> of <span class="total">%d</span>', $start, $end, Properties::$wpquery->found_posts ); ?> <span class="apts-text text">apartments</span>
                </div>

            </div>
            <div>
                <input class="btn btn-reset" type="reset" value="Reset" />
            </div>


        </div>
		<?php
            get_template_part('template-parts/apartment-search/filter','name');
            get_template_part('template-parts/apartment-search/filter','date');
		    get_template_part('template-parts/apartment-search/filter','price');
		    get_template_part('template-parts/apartment-search/filter','beds');
        ?>
		<input class="btn btn-reset" type="reset" value="Reset" />
        <button class="btn btn-secondary-outline btn-email" type="button" <?php echo $found == 0 ? 'disabled="disabled"' :''; ?>><span class="hide-mobile">Email Search Results</span></button>
	</section>
	<section class="col-md-12 search-filter filter-neighborhoods">
		<?php
            get_template_part('template-parts/apartment-search/filter','pets');
            get_template_part('template-parts/apartment-search/filter','neighborhoods');
            get_template_part('template-parts/apartment-search/filter','amenities');
		?>

        <span class="mobile-layout-icons">
            <input type="radio" id="layout-list-mobile" class="layout list" name="layout" title="list layout" value="list" checked="checked" />
            <label for="layout-list-mobile"></label>
            <input type="radio" id="layout-grid-mobile" class="layout grid" name="layout" title="Grid layout" value="grid" />
            <label for="layout-grid-mobile"></label>
        </span>

	</section>


	<?php
        include( locate_template('template-parts/apartment-search/search-results-sort.php') );
        get_template_part( 'template-parts/apartment-search/map', 'toggle' );
    ?>
    <section class="col-md-12 mobile-submit">
        <a class="btn btn-secondary btn-submit">Apply</a>
    </section>

    <section class="search-filter filter-map">
        <input type="hidden" class="map-radius" name="mapRadius" value="" />
        <input type="hidden" class="map-latitude" name="mapLatitude" value="" />
        <input type="hidden" class="map-longitude" name="mapLongitude" value="" />
    </section>
</form>

<?php get_template_part('template-parts/apartment-search/email', 'modal'); ?>
