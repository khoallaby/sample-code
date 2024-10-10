/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */

//import './blocks/_default/block';
//import './core';
//import './blocks/common';

import './blocks/wrapper';
import './blocks/hero';
import './blocks/accordion';
import './blocks/button';
import './blocks/cards';
import './blocks/cards-pair';
import './blocks/cards-slider';
import './blocks/card-2-columns';
import './blocks/card-form';
import './blocks/card-full-width';
import './blocks/card-video';
import './blocks/client-search';
import './blocks/find-health-center-gmaps';
import './blocks/list-icons';
import './blocks/member-login';
import './blocks/mosaic';
import './blocks/table';
import './blocks/testimonials';


// https://wordpress.org/support/topic/extending-columns-block/
// wp.blocks.registerBlockVariation( 'core/columns', { name: 'custom', title: 'Smiley', isDefault: true, innerBlocks: [ [ 'core/column', { width: 33.33 } ], [ 'core/column', { width: 66.66, className: 'custom' } ] ], icon: 'smiley', scope: [ 'block' ] } );
