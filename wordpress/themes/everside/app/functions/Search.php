<?php
namespace App;

class Search extends CustomPostTypes {
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'modifyRestResponse' ] );
    }


    public static function modifyRestResponse() {
        // add excerpt
        register_rest_field( 'search-result', 'excerpt', array(
            'get_callback' => function( $post_arr ) {
                return get_the_excerpt($post_arr['id']);
            },
        ) );
        register_rest_field( 'search-result', 'contentType', array(
            'get_callback' => function( $post_arr ) {

                $postType = get_post_type($post_arr['id']);
                if( $postType === 'post' ) {
                    $category = get_the_category($post_arr['id']);
                    $postTypeLabel = ucwords( $category[0]->cat_name );
                } else {
                    $postTypeLabel = ucwords( $postType );
                }
                return $postTypeLabel;
            },
        ) );
    }
}


add_action( 'init', [ __NAMESPACE__ . '\Search', 'init'] );
