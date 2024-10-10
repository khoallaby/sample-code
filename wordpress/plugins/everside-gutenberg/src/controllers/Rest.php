<?php
namespace Everside;


class Rest {

  public function __construct() {
    add_action( 'rest_post_query', [ $this, 'query_hide_post' ], 30, 2 );
  }

  // a way to enable meta_query via the `filters` query param: https://github.com/WP-API/rest-filter/blob/master/plugin.php


  // adds the `hide_post` query param to post_type = 'post'
  function query_hide_post( $args, $request ) {
    if( isset($request['hide_post']) ) {
      if ($request['hide_post']) {
        $args['meta_query'] = [
          [
            'key' => 'hide_post',
            'value' => 1,
          ]
        ];
      // not all metadata is created until the post has that setting checked/unchecked. so we have to check if the metakey doesn't exist, which means hide_post = false
      } else {
        $args['meta_query'] = [
          'relation' => 'OR',
          [
            'key' => 'hide_post',
            'value' => 0,
            'compare' => '='
          ],
          [
            'key'     => 'hide_post',
            'compare' => 'NOT EXISTS',
          ],
        ];
      }
    }

    return $args;
  }



}

new Rest();


