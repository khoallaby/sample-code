<?php

// for accessing via /page/2 URL, or via AJAX request
if( isset($_REQUEST['paged']) )
	$paged = $_REQUEST['paged'];
elseif( get_query_var( 'paged' ) )
	$paged = get_query_var( 'paged' );
else
	$paged = 1;


	echo paginate_links( array(
		//'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
		'base'         => '%_%',
		'total'        => isset($max_num_pages) ? $max_num_pages : \Cornerstone\Theme\Properties::$wpquery->max_num_pages,
		'current'      => $paged,
		#'format'       => '?paged=%#%',
		'format'       => '/apartment-search/page/%#%',
		'show_all'     => false,
		'type'         => 'plain',
		'end_size'     => 2,
		'mid_size'     => 1,
		'prev_next'    => true,
		'prev_text'    => sprintf( '<i></i> %1$s', __( '&lt;', 'text-domain' ) ),
		'next_text'    => sprintf( '%1$s <i></i>', __( '&gt;', 'text-domain' ) ),
		'add_args'     => false,
		'add_fragment' => '',
	) );
