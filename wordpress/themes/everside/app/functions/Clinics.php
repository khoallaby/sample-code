<?php
namespace App;

class Clinics extends CustomPostTypes {

    public static $post_name = 'clinic';


    public static function init() {
        $settings = [
            'menu_icon' => 'dashicons-building',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
            #'has_archive' => 'health-center-directory',
            'has_archive' => false,
            'exclude_from_search' => false,
            'rewrite' => [
                'slug' => self::$post_name,
            ],
        ];

        static::register_cpt( self::$post_name, self::$post_name . 's', $settings);
        static::register_rest_metadata();


        add_action('rest_api_init', [ __CLASS__, 'register_rest' ] );
    }


    public static function add_clinic( $data ) {
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

        return $clinic_id = self::add_or_update_post( self::$post_name, $data, $where );
    }




    public static function get_clinics( $limit = -1, $args=[] ) {
        $defaults = [
            'posts_per_page' => $limit,
        ];

        $args = wp_parse_args( $args, $defaults );

        return self::get_posts( self::$post_name, $args );
    }

    public static function get_related_providers( $postId, $args=[] ) {
        $salesforceIds = get_post_meta( $postId, 'salesforce_related_provider_ids', true );

        return static::get_posts_by_salesforce_id( $salesforceIds, [
            'post_type' => Providers::$post_name,
        ] );
    }

    public static function getClinicImg( $clinicID ) {
        if ($clinicPhoto = get_post_meta( $clinicID, 'clinic_photo_url', true )) {
            echo sprintf( '<img src="%s" class="img-fluid" />', $clinicPhoto );
        } else {
            #echo ( new Testing() )->get_random_image( [200, 500], [ 200, 400 ], true, 'img-fluid' );
        }
    }


    public static function get_states() {
        $states = [ 'AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC',
            'DE', 'FL', 'GA', 'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA',
            'MA', 'MD', 'ME', 'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE',
            'NH', 'NJ', 'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
            'SD', 'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'
        ];

        return $states;
    }


    public static function register_rest() {

        ( new ClinicsRest() )->register_routes();

    }

}


add_action( 'init', [ __NAMESPACE__ . '\Clinics', 'init'] );
