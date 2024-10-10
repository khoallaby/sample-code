<?php
namespace App;

use Everside\GoogleMaps;
use WP_REST_Controller,
    WP_REST_Server,
    WP_REST_Request,
    WP_REST_Response,
    WP_Error,
    WP_Query;



// https://upnrunn.com/blog/2018/04/how-to-extend-wp-rest-api-from-your-custom-plugin-part-3/
class ClinicsRest extends WP_REST_Controller
{
    public $returnProviders = false;

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        $version = '1';
        $namespace = 'everside/v' . $version;
        $base = 'clinics';
        register_rest_route($namespace, '/' . $base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));
    }

    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request) {
        $this->returnProviders = false;

        $original_args = $args = [
            'post_type' => Clinics::$post_name,
            'posts_per_page' => $request['per_page'],
            'paged' => $request['page'],
            'order' => $request['order'],
            'orderby' => $request['orderby'],
            'status' => 'publish',
        ];

        // for post__in
        if( !empty($request['include']) )
            $args['post__in'] = $request['include'];

        // if we're searching for providers only
        if( $request['type'] == 'providers' && !$request['zipcode'] && !$request['state'] ) :
            $this->returnProviders = true;
            $args['post_type'] = Providers::$post_name;
        else :
            // search by zipcode/range
            if( $request['zipcode'] ) {
                if( $latLong = GoogleMaps::getLatLongFromZipcode( $request['zipcode'] ) ) {
                    $args['geo_query'] = [
                        'lat_field' => 'address_latitude',  // this is the name of the meta field storing latitude
                        'lng_field' => 'address_longitude', // this is the name of the meta field storing longitude
                        'latitude' => $latLong[0],    // this is the latitude of the point we are getting distance from
                        'longitude' => $latLong[1],   // this is the longitude of the point we are getting distance from
                        'distance' => $request['range'],           // this is the maximum distance to search
                        'units' => 'miles'       // this supports options: miles, mi, kilometers, km
                    ];
                    # sort by distance
                    #$args['orderby'] = 'distance';
                    #$args['order'] = 'ASC';
                }

                // search by state
            } elseif( $request['state'] ) {
                $args['meta_query'] = [
                    [
                        'relation' => 'AND',
                        [
                            'key' => 'address_state',
                            'value' => $request['state'],
                            'compare' => '='
                        ]
                    ],
                ];
            }

            // if we want to return providers (filtered by zipcode/state), we have to search/filter for the clinics first, then get all the providers later
            if( $request['type'] == 'providers' ) {
                $this->returnProviders = true;
                $provider_salesforce_ids = [];
                $args['posts_per_page'] = -1;

                $posts_query  = new WP_Query();
                $items = $posts_query->query( $args );

                foreach ( $items as $item ) :
                    $salesforce_related_provider_ids = (array) get_post_meta($item->ID, 'salesforce_related_provider_ids', true );
                    $provider_salesforce_ids = array_merge( $provider_salesforce_ids, $salesforce_related_provider_ids );
                endforeach;

                if( $provider_salesforce_ids = array_unique($provider_salesforce_ids) ) {
                    // reset the args
                    $args = $original_args;
                    // filter the providers based on the related SF IDs from above
                    $args['post_type'] = Providers::$post_name;
                    $args['meta_query'] = [
                        [
                            'relation' => 'AND',
                            [
                                'key' => 'salesforce_id',
                                'value' => $provider_salesforce_ids,
                                'compare' => 'IN'
                            ]
                        ],
                    ];
                // no related providers, so we want to force it to return 0
                } else {
                    $args['post__in'] = [0];
                }
            }


        endif;



        $posts_query  = new WP_Query();
        $items = $posts_query->query( $args );
        $data = [];

        #var_dump($items);

        if ( $items ) {
            foreach ( $items as $item ) :
                $itemdata = $this->prepare_item_for_response( $item, $request );
                $data[] = $this->prepare_response_for_collection( $itemdata );
            endforeach;
        }


        $total_posts = $posts_query->found_posts;
        $max_pages = ceil( $total_posts / (int) $posts_query->query_vars['posts_per_page'] );

        $response = rest_ensure_response( $data );
        $response->header( 'X-WP-Total', (int) $total_posts );
        $response->header( 'X-WP-TotalPages', (int) $max_pages );
        return $response;

    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check($request) {
        return true;
    }


    public function prepare_item_for_response( $item, $request ) {
        if( $this->returnProviders ) {
            $data = array(
                'id' => $item->ID,
                'name' => $item->post_title,
                'link' => get_the_permalink( $item->ID ),
                'image' => get_post_meta( $item->ID, 'headshot_url', true ),
                'provider_type' => get_post_meta( $item->ID, 'provider_type', true ),
            );
        } else {
            $data = array(
                'id' => $item->ID,
                'name' => $item->post_title,
                'content' => $item->post_content,
                'link' => get_the_permalink( $item->ID ),
                #'image' => 'https://picsum.photos/350/350',
                'phone' => get_post_meta( $item->ID, 'phone', true ),
                'address_street' => get_post_meta( $item->ID, 'address_street', true ),
                'address_city' => get_post_meta( $item->ID, 'address_city', true ),
                'address_state' => get_post_meta( $item->ID, 'address_state', true ),
                'address_zipcode' => get_post_meta( $item->ID, 'address_zipcode', true ),
                'address_latitude' => get_post_meta( $item->ID, 'address_latitude', true ),
                'address_longitude' => get_post_meta( $item->ID, 'address_longitude', true ),
            );
        }

        return $data;
    }



    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params() {
        return array(
            'page' => array(
                'description' => 'Current page of the collection.',
                'type' => 'integer',
                'default' => 1,
                'sanitize_callback' => 'absint',
            ),
            'per_page' => array(
                'description' => 'Maximum number of items to be returned in result set.',
                'type' => 'integer',
                'default' => 10,
                'sanitize_callback' => 'absint',
            ),
            'include' => array(
                'description' => __('Limit result set to specific ids.'),
                'type' => 'array',
                'default' => array(),
                'sanitize_callback' => 'wp_parse_id_list',
            ),
            'order' => array(
                'description' => __( 'Order sort attribute ascending or descending.' ),
                'type'        => 'string',
                'default'     => 'asc',
                'enum'        => array(
                    'asc',
                    'desc',
                ),
            ),
            'orderby' => array(
                'description' => __( 'Sort collection by object attribute.' ),
                'type'        => 'string',
                'default'     => 'title',
                'enum'        => array(
                    'date',
                    'title',
                ),
            ),

            // search filters
            'type' => array(
                'description' => __( 'What type of object to return, clinics or providers.' ),
                'type'        => 'string',
                'default'     => 'clinics',
                'enum'        => [
                    'clinics',
                    'providers',
                ],
            ),

            // search filters
            'state' => array(
                'description' => __( 'Filter results by state.' ),
                'type'        => 'string',
                'default'     => '',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'range' => array(
                'description' => __( 'Filter results by range.' ),
                'type'        => 'integer',
                'default'     => 5,
                'sanitize_callback' => 'absint',
            ),
            'zipcode' => array(
                'description' => __( 'Filter results by zipcode.' ),
                'type'        => 'integer',
                'default'     => '',
                'sanitize_callback' => 'absint',
            ),
        );
    }
}
