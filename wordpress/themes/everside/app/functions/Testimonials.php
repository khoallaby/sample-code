<?php
namespace App;

class Testimonials extends CustomPostTypes {

    public static $post_name = 'testimonial';


    public static function init() {
        $settings = [
            'menu_icon' => 'dashicons-format-quote',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_nav_menus ' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'show_in_rest' => true,
            'has_archive' => false,
        ];

        static::register_cpt( self::$post_name, self::$post_name . 's', $settings);
        //static::register_rest_metadata();

        add_action( 'rest_' . self::$post_name . '_query', [ __CLASS__, 'restFilterPostsPerPage' ], 2, 10 );
    }




    public static function get_testimonials( $limit = -1, $args=[] ) {
        $defaults = [
            'posts_per_page' => $limit,
        ];

        $args = wp_parse_args( $args, $defaults );

        return self::get_posts( self::$post_name, $args );
    }

}


add_action( 'init', [ __NAMESPACE__ . '\Testimonials', 'init'] );
