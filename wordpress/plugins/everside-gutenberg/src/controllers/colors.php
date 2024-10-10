<?php
namespace Everside;


# https://www.billerickson.net/wordpress-color-palette-button-styling-gutenberg/

class Colors
{
  public $blocks;

  public function __construct() {
    add_action( 'after_setup_theme', [ $this, 'editorColorSettings' ] );
  }



  function editorColorSettings() {
    // enable align wide/full
    #add_theme_support('align-wide');
    #add_theme_support('align-full');

    // Disable Custom Colors picker
    #add_theme_support( 'disable-custom-colors' );

    // Disable Gradients
    add_theme_support( 'disable-custom-gradients' );
    add_theme_support( 'editor-gradient-presets', [] );


    add_theme_support( 'editor-color-palette', $this->getColors() );
  }


  function getColors() {
    return [
      [
        'name'  => __( 'Black' ),
        'slug'  => 'black',
        'color'	=> '#000000',
      ],
      [
        'name'  => __( 'White' ),
        'slug'  => 'white',
        'color'	=> '#ffffff',
      ],

      [
        'name'  => __( 'Green' ),
        'slug'  => 'green',
        'color'	=> '#095540',
      ],
      [
        'name'  => __( 'Very Light Green' ),
        'slug'  => 'very-light-green',
        'color' => '#f5fbf7',
      ],
      [
        'name'  => __( 'Purple' ),
        'slug'  => 'purple',
        'color' => '#510C76',
      ],
      [
        'name'  => __( 'Very Light Purple' ),
        'slug'  => 'very-light-purple',
        'color' => '#f0ecf4',
      ],
      [
        'name'  => __( 'Blue' ),
        'slug'  => 'blue',
        'color' => '#003953',
      ],
      [
        'name'  => __( 'Very Light Blue' ),
        'slug'  => 'very-light-blue',
        'color' => '#f4fafb',
      ],

      [
        'name'  => __( 'Dark Gray' ),
        'slug'  => 'dark-gray',
        'color' => '#4A4A4A',
      ],
      [
        'name'  => __( 'Very Light Gray' ),
        'slug'  => 'very-light-gray',
        'color' => '#f1f3f4',
      ],
    ];
  }
}

new Colors();


