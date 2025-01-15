<?php
namespace App;

use Everside\Utilities;


class Salesforce {

    public $salesforce, $salesforce_api;

    public $relations_clinic_providers = [];
    public $relations_clients_clinic = [];


    public function __construct() {
    }


    public function connect() {
        $cmd = sprintf('curl -v %s -d "grant_type=password" -d "client_id=%s" -d "client_secret=%s" -d "username=%s" -d "password=%s"',
            $url = 'https://test.salesforce.com/services/oauth2/token',
            $this->consumer_key,
            $this->consumer_secret,
            $this->consumer_username,
            $this->consumer_password
        );
    }


    public function run_cron() {
        if( $this->salesforce_api->is_authorized() ) {
            $this->add_clients();
            $this->add_clinics();
            $this->add_providers();
        }
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
        $clients = $this->get_clients();
        $relations = $this->get_client_relations();
        $client_ids = [];

        foreach( $clients as $client ) {
            $data = [
                'post_title' => $client['Name'],
                'post_content' => '',
                #'post_author'  => get_current_user_id(),
                'meta_input'   => [
                    'salesforce_id' => $client['Id'],
                    'salesforce_parent_id' => $client['ParentId'],
                    'salesforce_last_modified_date' => $client['LastModifiedDate'],
                    'salesforce_public_name' => $client['PublicClientName__c'],
                    'salesforce_logo_url' => $client['ClientLogoURL__c'],
                    'faq' => $client['FAQ__c'],
                    'available_services' => $client['Client_Available_Services__c'],
                    'welcome_headline' => $client['ClientWelcomeHeadline__c'],
                    'welcome_statement' => $client['ClientWelcomeStatement__c'],
                    'salesforce_publish_client' => $client['PublishClient__c'], # whether to show client on website or not ..... maybe adjust post_status instead
                    'community_pilot_participant' => $client['CommunityPilotParticipant__c'],
                    'salesforce_related_clinic_ids' => $relations[ $client['Id' ] ] ?? null,
                ],
            ];

            $client_ids[ $client['Id'] ] = Clients::add_client( $data );
        }


        Clients::delete_posts([
            'post__not_in' => $client_ids,
        ]);
    }


    public function get_clients() {
        $query = "
            SELECT Id, ParentId, Name, RecordType.Name, RecordTypeId, LastModifiedDate, PublicClientName__c, Client_Available_Services__c, ClientLogoURL__c, ClientWelcomeHeadline__c, ClientWelcomeStatement__c, PublishClient__c, Faq__c, CommunityPilotParticipant__c
            FROM Account
            WHERE RecordType.Name = 'Client' AND AccountStatusClient__c = 'Contracted' AND PublishClient__c = true
        ";

        return $this->soql_query($query);
    }



    public function add_clinics() {
        $clinics = $this->get_clinics();
        $relations = $this->get_clinic_relations();
        $clinic_ids = [];

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
        $providers = $this->get_providers();
        $relations = $this->get_provider_relations();
        $provider_ids = [];

        foreach( $providers as $provider ) {
            $data = [
                'post_title' => $provider['Name'],
                'post_content' => '',
                #'post_author'  => get_current_user_id(),
                'meta_input' => [
                    'bio_long' => $provider['ProviderBioLong__c'],
                    'bio_summary' => $provider['ProviderBioSummary__c'],
                    'headshot_url' => $provider['HeadshotURL__c'],
                    'email' => $provider['Email'],
                    'phone' => Utilities::formatPhoneNumber($provider['Phone']),
                    'title' => $provider['Title'], # todo: maybe set as category/tag
                    'provider_type' => $provider['ProviderType__c'],
                    'salesforce_id' => $provider['Id'],
                    'salesforce_last_modified_date' => $provider['LastModifiedDate'],
                    'salesforce_related_clinic_ids' => $relations[ $provider['Id' ] ] ?? null,
                ],
            ];

            if (isset($provider['Account'])) {
                $data['meta_input'] += [
                    'account_name' => $provider['Account']['Name'],
                    'account_id' => $provider['Account']['Id'],
                    #'account_wp_id' => $clinic['Account']['Id'], # todo: get Wordpress ID of acct
                ];
            }

            $provider_ids[ $provider['Id'] ] = Providers::add_provider( $data );
        }

        Providers::delete_posts([
            'post__not_in' => $provider_ids,
        ]);
    }




    public function get_providers() {
        $query = "
            SELECT Id, Name, Phone, Email, Title, ProviderType__c, Account.Name, Account.Id, RecordType.Name, RecordTypeId, ProviderBioSummary__c, ProviderBioLong__c, HeadshotURL__c, LastModifiedDate
            FROM Contact
            WHERE RecordType.Name = 'Provider' AND PublishPhysician__c = true
        ";

        return $this->soql_query($query);
    }






    public function get_clients_clinics_relations() {
        if( !$this->relations_clients_clinic ) {
            $query = "
                SELECT HealthCloudGA__Account__c, HealthCloudGA__RelatedAccount__c
                FROM HealthCloudGA__AccountAccountRelation__c
                WHERE HealthCloudGA__Active__c = true AND HealthCloudGA__Role__c = 'a121U000000jf70QAA'
            ";

            $this->relations_clients_clinic = $this->soql_query($query);
        }

        return $this->relations_clients_clinic;
    }


    // parses the client's (parent) relations with clinics (children)
    public function get_client_relations() {
        $relations = $this->get_clients_clinics_relations();
        $clients_relations = [];

        foreach ($relations as $relation)
            $clients_relations[ $relation['HealthCloudGA__Account__c'] ][] = $relation['HealthCloudGA__RelatedAccount__c'];

        return $clients_relations;
    }



    public function get_clinics_providers_relations() {
        if( !$this->relations_clinic_providers ) {
            $query = "
                SELECT AccountId, ContactId, IsActive, Type__c
                FROM AccountContactRelation
                WHERE IsActive = true AND Type__c = 'Clinic - Provider'
            ";

            $this->relations_clinic_providers = $this->soql_query($query);
        }

        return $this->relations_clinic_providers;
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
        $relations = $this->get_clinics_providers_relations();

        $providers_relations = [];

        foreach ($relations as $relation)
            $providers_relations[ $relation['ContactId'] ][] = $relation['AccountId'];

        return $providers_relations;
    }

}

