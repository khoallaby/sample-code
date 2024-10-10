const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.editor;

import Edit from "./edit";
import * as settings from '../../settings';
import blockSettings from './settings';

import './editor.scss';
import './style.scss';



/**
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 */
registerBlockType( settings.namespace + '/' + blockSettings.name, {
	title: __( blockSettings.title ), // Block title.
  description: __( blockSettings.description ),
	icon: blockSettings.icon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: settings.blockCategory,
	keywords: blockSettings.keywords
    .concat(settings.namespace)
    .map(function(e){
      return __(e);
    }),
  parent: null,
  namespace: settings.namespace,
  attributes: blockSettings.attributes,

	edit: Edit,

	//save: Save,
	save() {
    return <InnerBlocks.Content/>;
  }

} );

