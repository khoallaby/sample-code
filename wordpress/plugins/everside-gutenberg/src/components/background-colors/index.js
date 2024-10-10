const { __ } = wp.i18n;
import * as settings from '../../settings';
import './editor.scss';

const {
  SelectControl,
} = wp.components;
const {
  PanelColorSettings,
} = wp.editor;

const { Component } = wp.element;


const options = [
  { label: 'None', value: '' },
  //{ label: 'Light Gray', value: 'light-gray' },
];

// add/loop through colors
for ( const bgColor in settings.bgColors ) {
  options.push( { label: bgColor, value: settings.bgColors[ bgColor ] } );
}


export default class BackgroundColors extends Component {
  render() {
    const {
      bgColor,
      bgColors,
      extraColors,
      label,
      onChange,
    } = this.props;


/*
*
*
  'Light Green': 'very-light-green',
  'Light Purple': 'very-light-purple',
  'Light Blue': 'very-light-blue',
};*/
    const backgroundColors = [
      {
        name: 'None',
        slug: 'none',
        color: '',
      },
      {
        name: 'Light Green',
        slug: 'very-light-green',
        color: '#f5fbf7',
      },
      {
        name: 'Light Purple',
        slug: 'very-light-purple',
        color: '#f5fbf7',
      },
      {
        name: 'Light Blue',
        slug: 'very-light-blue',
        color: '#f5fbf7',
      },
      {
        name: 'Orange',
        slug: 'orange',
        color: '#f7941d',
      },
    ];


    return (
      <div className={ 'wp-component-background-colors' }>
{/*

        <PanelColorSettings
          title={ __( 'Background Color' ) }
          initialOpen={true}
          colorSettings={ [
            {
              //colors: bgColors ? bgColors : options,
              colors: backgroundColors,
              value: bgColor,
              //onChange: ( value ) => setAttributes( { bgColor: value } ),
              onChange: ( value ) => console.log( value ),
              //onChange: { onChange },
              label: __( 'Background Color' ),
            },
          ] }
        />
*/}

        <SelectControl
          label={ label || __( 'Background Color' ) }
          value={ bgColor }
          options={ bgColors ? bgColors : options }
          onChange={ onChange }
          //onChange={( value ) => setAttributes( { bgColor: value } )}
        />
      </div>
    );
  }
}
