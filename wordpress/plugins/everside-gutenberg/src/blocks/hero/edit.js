import classNames from 'classnames';
import ImageEditor from '../../components/image-editor';
import * as settings from '../../settings';
import blockSettings from './settings';
import BackgroundColors from '../../components/background-colors';


const { __ } = wp.i18n;
const { compose } = wp.compose;
const { withSelect } = wp.data;

const {
  Component,
  Fragment,
} = wp.element;

const {
  InnerBlocks,
  InspectorControls,
} = wp.editor;

const {
  PanelBody,
  PanelRow,
} = wp.components;


const bgColors = [
  { label: 'None', value: '' },
  { label: 'Light Gray', value: 'light-gray' },
];

for ( const bgColor in settings.bgColors ) {
  bgColors.push( { label: bgColor, value: settings.bgColors[ bgColor ] } );
}


class Edit extends Component {

  render() {
    const {
      attributes: {
        bgColor,
        className,
        bgImageId,
        bgMobileImageId,
        waveColor,
      },
      setAttributes,
      images,
    } = this.props;


    return (
      <Fragment>
        <InspectorControls>
          <PanelBody
            title={ __( 'Layout Options' ) }
            initialOpen={ true }
          >
            <PanelRow>
              <BackgroundColors
                //{...this.props}
                bgColor={ bgColor }
                bgColors={ bgColors }
                onChange={ ( value ) => setAttributes( { bgColor: value } ) }
              />
            </PanelRow>
            <PanelRow>
              <BackgroundColors
                label={ 'Wave Color' }
                bgColor={ waveColor }
                onChange={ ( value ) => setAttributes( { waveColor: value } ) }
              />
            </PanelRow>
            <PanelRow>
              <ImageEditor
                { ...this.props }
                label={ __( 'Background Image' ) }
                help={ __( 'Image should be approximately ~1200x675' ) }
                mediaIdField={ 'bgImageId' }
                allowedTypes={ settings.ALLOWED_MEDIA_TYPES }
              />
            </PanelRow>
            <PanelRow>
              <ImageEditor
                { ...this.props }
                label={ __( 'Mobile - Background Image' ) }
                mediaIdField={ 'bgMobileImageId' }
                allowedTypes={ settings.ALLOWED_MEDIA_TYPES }
              />
            </PanelRow>

          </PanelBody>
        </InspectorControls>

        <section
          className={ classNames(
            'container-full-width',
            'wp-block',
            wp.blocks.getBlockDefaultClassName( this.props.name ),
            className,
            '-wave-bg',
            bgColor ? 'background--' + bgColor : '',
            waveColor ? '-wave-color wave-color--' + waveColor : '',
          ) }
          style={ {
            backgroundImage: images.bgImageId ? `url('${ images.bgImageId.source_url }')` : 'none',
          } }
        >
          <div className="container">
            <div className="row">
              <div className="col-sm-6 content-area fade-in-left">
                <InnerBlocks
                  allowedBlocks={ blockSettings.ALLOWED_BLOCKS }
                  template={ blockSettings.INNERBLOCKS_TEMPLATE }
                  templateLock={ false }
                />
              </div>

              <div className="col-sm-6 content-image">
                { images.bgMobileImageId && (
                  <img src={ images.bgMobileImageId.source_url } alt="" className="img-fluid" />
                ) }
              </div>
            </div>
          </div>
        </section>
      </Fragment>
    );
  }

}

//export default Edit;

export default compose(
  withSelect( ( select, props ) => {
    const images = {};
    const mediaIdFields = [ 'bgImageId', 'bgMobileImageId' ];

    mediaIdFields.forEach( mediaIdField => {
      const mediaId = props.attributes[ mediaIdField ];

      if ( mediaId ) {
        images[ mediaIdField ] = select( 'core' ).getMedia( mediaId );
      }
    } );

    return { images: images };
  } ),
)( Edit );
