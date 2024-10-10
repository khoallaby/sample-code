<?php
namespace App;

use Everside\Utilities;



use http\Client;


class Salesforce {

    public $salesforce, $salesforce_api;

    public $relations_clinic_providers = [];
    public $relations_clients_clinic = [];


    public function __construct() {
        $this->get_api();

    }



    public function run_cron() {
        if( $this->salesforce_api->is_authorized() ) {
            $this->add_clients();
            $this->add_clinics();
            $this->add_providers();
        }
    }





    public function get_api() {
        /**
        REDACTED
         */
    }


    public function soql_query( $query ) {
        try {
            $result = $this->salesforce_api->query( $query );

            if( isset($result['data']['records']) )
                return $result['data']['records'];
            else
                throw new \Exception($result['data']['message']);

        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }








    public function add_clients() {
        /**
        REDACTED
         */
    }


    public function get_clients() {
        $query = "
            REDACTED 
        ";

        return $this->soql_query($query);
    }







    public function add_clinics() {
        $clinics = $this->get_clinics();
        $relations = $this->get_clinic_relations();
        $clinic_ids = [];




        if( $_GET['debug'] ) {
            echo '<pre style="color: blue;">';
            var_dump($clinics);
            echo '</pre>';
        }


        foreach( $clinics as $clinic ) {
            $data = [
                'post_title' => $clinic['Name'],
                'post_content' => '',
                #'post_author'  => get_current_user_id(),
                'meta_input'   => [
                    'phone' => Utilities::formatPhoneNumber($clinic['Phone']),
                    'salesforce_id' => $clinic['Id'],
                    'salesforce_last_modified_date' => $clinic['LastModifiedDate'],
                    'salesforce_publish' => $clinic['Public__c'],
                    #'salesforce_publish_clinic' => $clinic['PublishClinic__c'], # whether to show clinic on website or not ..... maybe adjust post_status instead
                    'salesforce_related_provider_ids' => $relations[ $clinic['Id' ] ] ?? null,
                ],
            ];

            if( isset($clinic['ShippingAddress']) ) {
                $data['meta_input'] += [
                    'address_street' => $clinic['ShippingAddress']['street'],
                    'address_city' => $clinic['ShippingAddress']['city'],
                    'address_state' => $clinic['ShippingAddress']['state'],
                    'address_zipcode' => $clinic['ShippingAddress']['postalCode'],
                    'address_country' => $clinic['ShippingAddress']['country'],
                    'address_geocode_accuracy' => $clinic['ShippingAddress']['geocodeAccuracy'],
                    'address_latitude' => $clinic['ShippingAddress']['latitude'],
                    'address_longitude' => $clinic['ShippingAddress']['longitude'],
                ];
            }

            $clinic_ids[ $clinic['Id'] ] = Clinics::add_clinic( $data );
        }


        Clinics::delete_posts([
            'post__not_in' => $clinic_ids,
        ]);
    }



    public function get_clinics() {
        $query = "
            SELECT Id, Name, Phone, RecordType.Name, RecordTypeId, LastModifiedDate, ShippingAddress, Public__c
            FROM Account
            WHERE RecordType.Name = 'Clinic'  AND PublishClinic__c = true
        ";

        return $this->soql_query($query);
    }







    public function add_providers() {
        /**
        REDACTED
         */
    }




    public function get_providers() {
        $query = "
            REDACTED
        ";


        /**
        REDACTED LOGIC
         */
        return $this->soql_query($query);
    }






    public function get_clients_clinics_relations() {
        /**
        REDACTED
         */

        return $this->relations_clients_clinic;
    }


    // parses the client's (parent) relations with clinics (children)
    public function get_client_relations() {
        /**
        REDACTED
         */
    }



    public function get_clinics_providers_relations() {
        /**
        REDACTED
         */
    }


    // parses the clinic's (parent) relations with providers (children)
    public function get_clinic_relations() {
        $relations = $this->get_clinics_providers_relations();
        $clinics_relations = [];

        foreach ($relations as $relation)
            $clinics_relations[ $relation['AccountId'] ][] = $relation['ContactId'];

        return $clinics_relations;
    }


    // parses the provider's (parent) relations with clinic (children)
    public function get_provider_relations() {
        /**
        REDACTED
         */
    }

}

