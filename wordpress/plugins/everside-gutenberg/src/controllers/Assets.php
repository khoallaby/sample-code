<?php
namespace Everside;


class Assets {
  static $image_dir = '/../images/';

  public function __construct() {
    add_action( 'init', [ $this, 'loadAssets' ] );
    add_action( 'wp_enqueue_scripts', [ $this, 'registerScripts' ], 20 );
    add_action( 'wp_enqueue_scripts', [ $this, 'loadBlockAssets' ], 30 );

    add_filter('upload_mimes', [ $this, 'allowSvgUpload' ] );
  }


  /**
   * Enqueue Gutenberg block assets for both frontend + backend.
   *
   * Assets enqueued:
   * 1. blocks.style.build.css - Frontend + Backend.
   * 2. blocks.build.js - Backend.
   * 3. blocks.editor.build.css - Backend.
   *
   */
  function loadAssets() { // phpcs:ignore
    // Register block styles for both frontend + backend.
    wp_register_style(
      strtolower(__NAMESPACE__) . '-style-css', // Handle.
      plugins_url( '../dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
      is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
      null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
    );

    // Register block editor script for backend.
    wp_register_script(
      strtolower(__NAMESPACE__) . '-block-js', // Handle.
      plugins_url( '../dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
      array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
      null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
      true // Enqueue the script in the footer.
    );

    // Register block editor styles for backend.
    wp_register_style(
      strtolower(__NAMESPACE__) . '-block-editor-css', // Handle.
      plugins_url( '../dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
      array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
      null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
    );

    // WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
    wp_localize_script(
      strtolower(__NAMESPACE__) . '-block-js',
      strtolower(__NAMESPACE__) . '_global', // Array containing dynamic data for a JS Global.
      [
        'pluginDirPath' => plugin_dir_path( __DIR__ ),
        'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
        // Add more data here that you want to access from `cgbGlobal` object.
      ]
    );

  }


  function registerScripts() {

    // member search + bs autocomplete
    wp_register_script(
      'bootstrap-autocomplete',
      'https://cdn.jsdelivr.net/npm/bootstrap-4-autocomplete/dist/bootstrap-4-autocomplete.min.js', // bootstrap v4 plugin
      [],
      false,
      true
    );

    wp_register_script(
      strtolower(__NAMESPACE__) . '-client-search',
      plugin_dir_url(dirname(__FILE__)) . 'scripts/client-search.js',
      [
        strtolower(__NAMESPACE__) . '-global',
        'bootstrap-autocomplete',
      ],
      false,
      true
    );



    // member login
    wp_register_script(
      strtolower(__NAMESPACE__) . '-member-login',
      plugin_dir_url(dirname(__FILE__)) . 'scripts/member-login.js',
      [
        'wp-util',
        strtolower(__NAMESPACE__) . '-global'
      ],
      false,
      true
    );



    // card - form
    wp_register_script(
      strtolower(__NAMESPACE__) . '-card-form-recaptcha',
      'https://www.google.com/recaptcha/api.js',
      [],
      false,
      true
    );

    wp_register_script(
      strtolower(__NAMESPACE__) . '-card-form',
      plugin_dir_url(dirname(__FILE__)) . 'scripts/card-form.js',
      [ strtolower(__NAMESPACE__) . '-card-form-recaptcha' ],
      false,
      true
    );


    // cards slider - owl carousel
    wp_register_script(
      'owl-carousel',
      'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',
      [ 'jquery' ],
      false,
      false
    );

    wp_register_style(
      'owl-carousel',
      'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css',
      [],
      false,
      false
    );
  }


  function loadBlockAssets() {
    global $post;
    // enqueue google recaptcha for the `card-form` block

    wp_enqueue_script(
      strtolower(__NAMESPACE__) . '-global',
      plugin_dir_url(dirname(__FILE__)) . 'scripts/global.js',
      [],
      false,
      true
    );

    // inlines the wp ajax url
    wp_localize_script(
      strtolower(__NAMESPACE__) . '-global',
      'everside_js',
      [
        'ajax_url' => admin_url('admin-ajax.php'),
        'wp_api_url' => home_url( '/wp-json/' ),
        'plugin_url' => EVERSIDE_PLUGIN_URL,
        'gmaps_api_key' => GoogleMaps::getApiKey(),
      ]
    );


    if ( has_block( strtolower(__NAMESPACE__) . '/client-search' ) ) {
      wp_enqueue_script( 'bootstrap-autocomplete' );
      wp_enqueue_script( strtolower(__NAMESPACE__) . '-client-search' );
    }

    if ( has_block( strtolower(__NAMESPACE__) . '/member-login' ) ) {
      wp_enqueue_script( strtolower(__NAMESPACE__) . '-member-login' );
    }


    if ( has_block( strtolower(__NAMESPACE__) . '/card-form' ) ||
      ( is_singular( 'post' ) && get_post_meta($post->ID, 'gated_content', true ) && !isset($_COOKIE['gated-content']) )
    ) {
      wp_enqueue_script( strtolower(__NAMESPACE__) . '-card-form-recaptcha' );
      wp_enqueue_script( strtolower(__NAMESPACE__) . '-card-form' );
    }

    if ( has_block( strtolower(__NAMESPACE__) . '/cards-slider' ) ) {
      // owl carousel
      wp_enqueue_style( 'owl-carousel' );
      wp_enqueue_script( 'owl-carousel' );

      wp_add_inline_script( 'owl-carousel', '
        jQuery( document ).ready( function( $ ) {
          $( ".wp-block-everside-cards-slider" ).owlCarousel( {
          //$( ".owl-carousel" ).owlCarousel( {
            dots: false,
            loop: true,
            margin: 10,
            nav: true,
            items: 1,
            navText: [],
          } );
        } );
      ' );

      /*
      wp_enqueue_style(
        'owl-carousel-theme',
        'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css',
        //'https://cdn.jsdelivr.net/npm/@glidejs/glide',
        ['owl-carousel'],
        false,
        false
      );
      */
    }

  }

  public static function getSvg($filename ) {
    return file_get_contents(dirname(__FILE__) . self::$image_dir . $filename );
  }


  function allowSvgUpload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }

}

new Assets();


