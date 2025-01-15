<?php
namespace App;

class Providers extends CustomPostTypes {

    public static $post_name = 'provider';


    public static function init() {
        $settings = [
            'menu_icon' => 'dashicons-businessman',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
            #'has_archive' => 'provider-directory',
            'has_archive' => false,
            'exclude_from_search' => false,
            'rewrite' => [
                'slug' => self::$post_name,
            ],
        ];

        static::register_cpt( self::$post_name, self::$post_name . 's', $settings);
        static::register_rest_metadata();
    }


    public static function add_provider( $data ) {
        $where = [
            'post_status' => 'any',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key'     => 'salesforce_id',
                    'value'   => $data['meta_input']['salesforce_id'],
                    'compare' => '=',
                ]
            ]
        ];

        return $provider_id = self::add_or_update_post( self::$post_name, $data, $where );
    }

    public static function get_providers( $limit = -1, $args=[] ) {
        $defaults = [
            'posts_per_page' => $limit,
        ];

        $args = wp_parse_args( $args, $defaults );

        return self::get_posts( self::$post_name, $args );
    }


    public static function get_related_clinics( $postId, $args=[] ) {
        $salesforceIds = get_post_meta( $postId, 'salesforce_related_clinic_ids', true );

        return static::get_posts_by_salesforce_id( $salesforceIds, [
            'post_type' => Clinics::$post_name,
        ] );
    }


    public static function getProviderImg( $providerID ) {
        if ($providerPhoto = get_post_meta( $providerID, 'headshot_url', true)) {
            echo sprintf('<img src="%s" class="img-fluid" />', $providerPhoto);
        } else {
            # show placeholder img
        }
    }
}


add_action( 'init', [ __NAMESPACE__ . '\Providers', 'init'] );
