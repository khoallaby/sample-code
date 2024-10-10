const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

import * as settings from '../settings';
//import * as blockSettings from './settings';



function eversideRegisterBlockType( blockSettings2, edit, save ) {
  var blockSettings = blockSettings2;

  /**
   * @link https://wordpress.org/gutenberg/handbook/block-api/
   */

  blockSettings.title = __( blockSettings.title );
  blockSettings.description = __( blockSettings.description );
  blockSettings.category = settings.blockCategory;
  blockSettings.keywords = blockSettings.keywords
    .concat(settings.namespace)
    .map(function(e){
      return __(e);
    });
  blockSettings.namespace = settings.namespace;
  blockSettings.edit = edit;
  blockSettings.save = save;


  return registerBlockType( settings.namespace + '/' + blockSettings.name, blockSettings );

}


export function generateRandomID( seed = 36 ) {
  const number = Math.random();
  number.toString( seed );
  return number.toString( seed ).substr( 2, 9 );
}

export { eversideRegisterBlockType };
