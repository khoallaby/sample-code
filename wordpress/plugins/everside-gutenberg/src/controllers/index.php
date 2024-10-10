<?php

namespace Everside;



class Everside {

  public function __construct() {

    add_action( 'after_setup_theme', [ $this, 'addThemeSupport' ], 20 );
    add_action( 'after_setup_theme', [ $this, 'addImageSizes' ] );

  }


  function addImageSizes() {
    add_image_size( 'card_thumb', 360, 270, true );
    add_image_size( 'card_large', 720, 540, true );
  }


  function addThemeSupport() {
    add_theme_support( 'responsive-embeds' );
  }




  public static function apiRequest($method, $url, $data, $headers=false ){
    $data = json_encode($data );

    $defaultHeaders = [
      'Content-Type' => 'application/json',
    ];

    $headers = wp_parse_args( $headers, $defaultHeaders );
    foreach( $headers as $k =>$header )
      $newHeaders[] = sprintf( '%s: %s', $k, $header );



    // post/put untested
    $curl = curl_init();
    switch ($method) {
      case "POST":
        curl_setopt( $curl, CURLOPT_POST, 1 );
        if ($data)
          curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
        break;

      case "PUT":
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PUT" );
        if ($data)
          curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
        break;

      default:
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
      /*
      if ($data)
        $url = sprintf("%s?%s", $url, http_build_query($data));
      */
    }

    # set options
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_HTTPHEADER, $newHeaders );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );

    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
  }




// https://www.timjensen.us/get-post-excerpt-outside-loop/
  public static function get_post_excerpt( $post_id, $more_link = true ) {

    $excerpt_length = 10;
    $post = get_post( $post_id );

    if ( $post->post_excerpt ) {
      $excerpt = $post->post_excerpt;
    } else {
      $excerpt = strip_shortcodes( $post->post_content );
      $excerpt = apply_filters( 'the_content', $excerpt );
      $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );

      $original_word_count = str_word_count( strip_tags( $excerpt ) );

      $excerpt = wp_trim_words( $excerpt, $excerpt_length, '' );

      $is_trimmed_excerpt = str_word_count( $excerpt ) < $original_word_count;
    }


    // Remove the excerpt more (i.e., '...') when there is no more text to show.
    $excerpt_more = empty( $is_trimmed_excerpt ) ? '' : ' &hellip; ';

    $excerpt = apply_filters( 'get_post_excerpt_excerpt', $excerpt . $excerpt_more );

    if ( false === $more_link ) {
      return $excerpt;
    } else {
      $more_link = sprintf( '<a href="%s" class="more-link read-more-link">%s</a>',
        get_the_permalink( $post->ID ),
        apply_filters( 'get_post_excerpt_read_more_text', 'Read More' )
      );
    }

    return $excerpt . apply_filters( 'get_post_excerpt_read_more_link', $more_link );
  }

  public static function isProd() {
    $urls = [
      'eversidehealth.com',
      'www.eversidehealth.com',
    ];

    return in_array($_SERVER['HTTP_HOST'], $urls) ? true : false;
  }




}

new Everside();

