<?php
namespace App;

use WP_Query;

class CustomPostTypes
{

    public static function init() {
        $settings = [
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
            /*
            'rewrite' => array(
                'slug' => '/',
                'with_front' => false
            )
            */
        ];

        add_post_type_support( 'page', 'excerpt' );


        add_action( 'rest_' . 'post' . '_query', [ __CLASS__, 'restFilterPostsPerPage' ], 2, 10 );
        add_action( 'rest_' . 'page' . '_query', [ __CLASS__, 'restFilterPostsPerPage' ], 2, 10 );
        #self::register_cpt( Clinics::$post_name, Clinics::$post_name . 's', $settings);

        //$this->register_tax( 'article-category', 'article-categories', 'articles' );
    }


    public static function register_tax($tax_name, $tax_name_plural, $post_type, $args = []) {
        register_taxonomy(
            $tax_name,
            $post_type,
            [
                'label' => __(self::clean_name($tax_name_plural)),
                #'public' => false,
                'rewrite' => false,
                'hierarchical' => true,
            ]
        );
    }


    public static function register_cpt($cpt_name, $cpt_name_plural, $args = []) {

        $labels = [
            'name' => _x(ucwords($cpt_name_plural), 'Post Type General Name'),
            'singular_name' => _x(ucwords($cpt_name) . '', 'Post Type Singular Name'),
            'menu_name' => __(ucwords($cpt_name_plural)),
            'parent_item_colon' => __('Parent ' . ucwords($cpt_name)),
            'all_items' => __('All ' . ucwords($cpt_name_plural)),
            'view_item' => __('View ' . ucwords($cpt_name)),
            'add_new_item' => __('Add New ' . ucwords($cpt_name)),
            'add_new' => __('Add New'),
            'edit_item' => __('Edit ' . ucwords($cpt_name)),
            'update_item' => __('Update ' . ucwords($cpt_name)),
            'search_items' => __('Search ' . ucwords($cpt_name)),
            'not_found' => __('Not found'),
            'not_found_in_trash' => __('Not found in Trash'),
        ];

        $defaults = [
            'label' => __($cpt_name_plural),
            'description' => __(ucwords($cpt_name) . ' Description'),
            'labels' => $labels,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'taxonomies' => [ /*'category', 'post_tag'*/],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-post',
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => [
                'slug' => $cpt_name_plural,
            ],
            'capability_type' => 'page',
        ];
        $args = wp_parse_args($args, $defaults);
        register_post_type(ucwords(str_replace(' ', '-', $cpt_name)), $args);

    }

    public static function register_rest_metadata() {
        register_rest_field( static::$post_name, 'meta', array(
            'get_callback' => function ( $data ) {
                return get_post_meta( $data['id'], '', '' );
            }, )
        );
    }

    public static function clean_name($str) {
        return ucwords(str_replace('-', ' ', $str));
    }


    public static function get_post($post_type = 'post', $where = [] ) {
        $defaults = [
            'post_type' => $post_type,
            'order' => 'DESC',
            'orderby' => 'date',
            'posts_per_page' => 1,
        ];

        $args = wp_parse_args( $where, $defaults );


        $query = new WP_Query( $args );
        $posts = $query->get_posts();

        return reset($posts);
    }


    public static function get_posts($post_type = 'post', $where = [] ) {
        $defaults = [
            'post_type' => $post_type,
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
        ];

        $args = wp_parse_args( $where, $defaults );


        $query = new WP_Query( $args );

        return $query->get_posts();
    }


    public static function get_sticky_posts($post_type = 'post', $where = [] ) {
        $defaults = [
            'post__in'  => get_option( 'sticky_posts' ),
            'ignore_sticky_posts' => 1,
            'order' => 'ASC',
            'orderby' => 'rand',
            'posts_per_page' => -1,
        ];

        $args = wp_parse_args( $where, $defaults );

        return self::get_posts( $post_type, $args );
    }



    public static function add_or_update_post( $post_type = 'post', $data = [], $where = [] ) {
        $defaults = [
            'post_status' => 'publish',
            'post_type' => $post_type,
        ];

        $data = wp_parse_args( $data, $defaults );

        #get post, see if exists
        $post = self::get_post( $post_type, $where );


        if( $post ) {
            # todo: check last modified date, update or return $post
            $data['ID'] = $post->ID;
            return wp_update_post( $data );
        } else {
            return wp_insert_post( $data );
        }
    }



    public static function delete_posts( $args = [] ) {
        $defaults = [
            'post_type'	 => static::$post_name,
            'posts_per_page' => -1,
            //'post_status'    => 'publish',

        ];

        $args = wp_parse_args( $args, $defaults );
        $query = new WP_Query( $args );
        $posts = $query->get_posts();

        foreach( $posts as $post )
            wp_delete_post( $post->ID );
    }


    public static function get_posts_by_salesforce_id( $salesforce_ids=[], $args=[] ) {
        $defaults = [
            'post_type'	 => static::$post_name,
            'posts_per_page' => -1,
            //'post_status'    => 'publish',

            'meta_query' => [
                'relation' => 'AND',
                [
                    'key'     => 'salesforce_id',
                    'value'   => $salesforce_ids,
                    'compare' => 'IN',
                ]
            ]
        ];

        $args = wp_parse_args( $args, $defaults );
        return new WP_Query( $args );
        #return $query->get_posts();

    }


    public static function taxonomy_dropdown( $taxonomy, $args=[] ) {
        $html = '';
        $defaults = [
            'orderby' => 'name',
            'order' => 'ASC',
            #'number' => 100,
        ];

        $args = wp_parse_args( $args, $defaults );
        $terms = get_terms( $taxonomy, $args );

        if ( $terms ) {
            foreach( $terms as $term )
                $html .= sprintf( '<option value="%s">%s</option>', esc_attr( $term->term_id ), esc_html( $term->name ) );
        }

        return $html;
    }





    // creates new custom parameter, "posts_per_page"... so we can bypass wp's "per_page" limit of 100
    public static function restFilterPostsPerPage( array $args, \WP_REST_Request $request ) {
        #$post_per_page = $request->get_param('max_posts_per_page') ? $request->get_param('max_posts_per_page') : 10;
        $args['posts_per_page'] = $request->get_param('max_posts_per_page') ? $request->get_param('max_posts_per_page') : $args['posts_per_page'];
        return $args;
    }

}

add_action( 'init', [ __NAMESPACE__ . '\CustomPostTypes', 'init'] );
