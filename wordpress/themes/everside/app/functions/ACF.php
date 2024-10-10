<?php
namespace App;


class ACF {

    public static function init() {
        # functions for determining where acf-json files are saved/loaded -- /[root]/src/acf-json/
        add_filter('acf/settings/load_json', [ __CLASS__, 'saveLoadJSON' ] );
        add_filter('acf/settings/save_json', [ __CLASS__, 'saveLoadJSON' ] );

        self::addOptionsPage();
    }


    public static function saveLoadJSON($args) {
        $path = dirname(__FILE__) . '/../acf-json';
        return $path;
    }

    public static function addOptionsPage() {
        if( function_exists('acf_add_options_page') ) {
            acf_add_options_page([
                'page_title' 	=> 'Site Settings',
                'menu_title'	=> 'Site Settings',
                'menu_slug' 	=> 'site-settings',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            ]);
        }
    }

}


add_action( 'init', [ __NAMESPACE__ . '\ACF', 'init'] );
