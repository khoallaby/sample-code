<?php
use Cornerstone\Theme\Apartments,
	Cornerstone\Theme\Properties;

$max_num_pages = Properties::$wpquery->max_num_pages;

$total_apts = 0;

# get all property ids to count for available apts later, this takes the property level filters into consideration
$properties_all = Properties::getProperties( [
	'post__in' => $property_post_ids,
	'posts_per_page' => -1,
	'fields' => 'ids',
], $_REQUEST );


foreach( $apartmentData as $property_id => $apartmentDatum ) {
	if( in_array($property_id, $properties_all) && isset($apartmentDatum['available-apts']) )
		if( is_array($apartmentDatum['available-apts']) ) {
			$total_apts += count($apartmentDatum['available-apts']);
		}
}



?>
<section class="col-xl-8 col-lg-7 col-md-6 search-filter filter-sort">
    <h4 class="search-totals"><strong><?php echo sprintf( '<span class="total">%d</span>', $total_apts ); ?></strong> <span class="apts-text text">apartments</span> <span class="text">for rent </span></h4>
    <div class="filter-sort-options">
        <input type="radio" id="layout-list" class="layout list" name="layout" title="list layout" value="list" />
        <label for="layout-list"></label>

        <input type="radio" id="layout-grid" class="layout grid checked" name="layout" title="Grid layout" value="grid" checked="checked" />
        <label for="layout-grid"></label>


        <div class="input-wrapper">
            <div class="dropdown singleselect sort">
                <button name="sort" class="btn dropdown-toggle" type="button" data-toggle="dropdown" data-reset='Sort' aria-haspopup="true" aria-expanded="false">Sort</button>
                <div class="dropdown-menu radios">
				    <?php
				    $sorts = [
                        'Price (Hi - Lo)',
                        'Price (Lo - Hi)',
                        /*'New Listings'*/
                    ];

				    foreach ( $sorts as $sort ) :
					    echo sprintf(
						    '<div class="rdio %s"><input type="radio" name="sort" id="sort_%s" value="%s" %s /> <label for="sort_%s">%s</label></div>',
						    isset($_REQUEST['sort']) && $_REQUEST['sort'] == strtolower($sort) ? 'checked' : '',
                            strtolower($sort),
                            strtolower($sort),
						    checked(
                                isset($_REQUEST['sort']) ? $_REQUEST['sort'] : false,
                                strtolower($sort),
                                false
                            ),
                            strtolower($sort),
                            $sort
					    );
				    endforeach;

				    get_template_part('template-parts/apartment-search/filter','clear-apply');
				    ?>
                </div>
            </div>
        </div>

    </div>
</section>
