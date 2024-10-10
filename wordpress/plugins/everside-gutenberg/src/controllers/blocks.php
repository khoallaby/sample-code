<?php

namespace Everside;


class Blocks {
  public $blocks;
  public static $blockFolder = '/../blocks/';


  public function __construct() {
    $this->registerBlocks();


    // load actions/filters
    add_filter( 'block_categories', [ $this, 'blockCategories' ], 10, 2 );
    add_filter( 'block_editor_settings' , [ $this, 'removeEditorStyles' ] );
    add_filter( 'allowed_block_types', [ $this, 'disableBlocks' ] );

    #add_action( 'wp_print_styles', [ $this, 'removeGutenbergStyles' ], 100 );

    #add_action('admin_init', [ $this, 'remove_default_stylesheets' ] );
  }






  public function loadBlocks() {
    $json = dirname( __FILE__ ) . static::$blockFolder . 'blocks.json';

    $this->blocks = file_get_contents($json );
    $this->blocks = json_decode( $this->blocks );
    #var_dump( $this->blocks );

    #die();
  }


  public function registerBlock( $block, $parentBlock = false ) {
    $args = [
      'style' => strtolower(__NAMESPACE__) . '-style-css',
      'editor_style' => strtolower(__NAMESPACE__) . '-block-editor-css',
      'editor_script' => strtolower(__NAMESPACE__) . '-block-js',
    ];

    $path = $parentBlock ? static::$blockFolder . $parentBlock . '/innerblocks/' . $block->name : static::$blockFolder . $block->name;

    if (isset($block->render) && $block->render)
      $args['render_callback'] = require dirname(__FILE__) . $path . '/render.php';

    /*
     *
     * to manually call the rendered output
      $file = require 'render.php';
      echo call_user_func_array($file, [ $attr, $content );
     * */

    register_block_type(
      strtolower(__NAMESPACE__) . '/' . $block->name,  // Block name with namespace
      $args
    );

  }

  public function registerBlocks() {
    $this->loadBlocks();

    foreach( $this->blocks as $block ) {

      $this->registerBlock( $block );



      // Add Innerblocks
      if(isset($block->innerblocks) ) {
        foreach ( $block->innerblocks as $innerblock ) {
          $this->registerBlock( $innerblock, $block->name );
        }
      }

      /*
      $args = [
        'style' => strtolower(__NAMESPACE__) . '-style-css',
        'editor_style' => strtolower(__NAMESPACE__) . '-block-editor-css',
        'editor_script' => strtolower(__NAMESPACE__) . '-block-js',
      ];

      if( isset($block->render) && $block->render )
        $args['render_callback'] = require dirname(__FILE__) . $this->blockFolder . $block->name . '/render.php';


      register_block_type(
        strtolower(__NAMESPACE__) . '/' . $block->name,  // Block name with namespace
        $args
      );*/

    }

  }


  public function disableBlocks( $allowed_blocks ) {

    $registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
    #unset($registered_blocks['core/button']);
    #unset($registered_blocks['core/buttons']);

    // Get keys from array
    $registered_blocks = array_keys($registered_blocks);

    // Merge allowed core blocks with plugins blocks
    return $registered_blocks;
  }

  public function blockCategories( $categories, $post ) {
    return array_merge(
      [
        [
          'slug' => 'everside',
          'title' => __( 'Everside' ),
        ],
      ],
      [
        [
          'slug' => 'everside-components',
          'title' => __( 'Everside - Components' ),
        ],
      ],
      $categories
    );
  }








  // this removes the default gutenberg editor styles
  # https://stackoverflow.com/questions/54203925/remove-embedded-stylesheet-for-gutenberg-editor-on-the-back-end
  function removeEditorStyles( $settings ) {
    unset($settings['styles'][0]);

    return $settings;
  }


  function removeGutenbergStyles() {
    wp_dequeue_style( 'wp-block-library' );
  }


  function remove_default_stylesheets() {
    wp_deregister_style('wp-admin');
  }


  public static function echoClassNames( $classes=[], $echo=true ) {
    $classes = array_filter($classes); # removes empty elements

    if( $echo )
      echo !empty($classes) ? 'class="' . implode( ' ', $classes ) . '"' : null;
    else
      return !empty($classes) ? implode( ' ', $classes ) : null;
  }


  public static function echoMarginPaddingClasses( $attr, $echo=true ) {
    $classes = [];
    $classNames = [
      'marginTop' => '-add-margin-top-',
      'marginBottom' => '-add-margin-bottom-',
      'paddingTop' => '-add-padding-top-',
      'paddingBottom' => '-add-padding-bottom-',
    ];

    foreach( $attr as $attrName => $attrValue ) {
      if (array_key_exists($attrName, $classNames))
        $classes[] = $classNames[$attrName] . $attrValue;
    }


    $return = !empty($classes) ? implode( ' ', $classes ): null;

    if( $echo )
      echo $return;
    else
      return $return;
  }


  public static function renderBlock( $blockName, $attr=[], $content='', $parentBlock=false ) {
    $path = $parentBlock ? self::$blockFolder . $parentBlock . '/innerblocks/' . $blockName : self::$blockFolder . $blockName;
    $file = require dirname(__FILE__) . $path . '/render.php';

    return call_user_func_array($file, [ $attr, $content ] );
  }


}

new Blocks();
#add_action( 'init', [ Blocks::class, 'init' ] );
