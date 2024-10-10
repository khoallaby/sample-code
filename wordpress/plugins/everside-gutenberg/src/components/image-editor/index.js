const { __ } = wp.i18n;
const { MediaPlaceholder, MediaUpload, MediaUploadCheck } = wp.editor;

const {
  IconButton,
} = wp.components;

const {
  Component,
  Fragment,
} = wp.element;

const { compose } = wp.compose;
const { withSelect } = wp.data;

//import classNames from 'classnames';
import classNames from 'classnames';
import './editor.scss';

const componentClassName = 'wp-component-image-editor';


/// another potential solution: https://github.com/WordPress/gutenberg/issues/14623#issuecomment-499501805
// https://awhitepixel.com/blog/wordpress-gutenberg-add-image-select-custom-block/
// https://laptrinhx.com/add-an-image-selector-to-a-gutenberg-block-in-wordpress-1907381684/
// https://jschof.com/gutenberg-blocks/wordpress-gutenberg-blocks-example-creating-a-hero-image-block-with-inspector-controls-color-palette-and-media-upload-part-2/

// https://www.liip.ch/en/blog/add-an-image-selector-to-a-gutenberg-block

class ImageEditor extends Component {


  constructor() {
    super( ...arguments );
    this.updateImage = this.updateImage.bind( this );
  }

  renderEditor( open ) {
    const {
      attributes,
      style,
      label,
      help,
      image,
      imageClassName,
      className,
    } = this.props;

    const mediaId = attributes[ this.props.mediaIdField ];


    return (
      <div className={ componentClassName }>
        { label && ( <h3>{ label }</h3> ) }
        { help && ( <p className={ 'help' }>{ help }</p> ) }

        { mediaId === 0 && (
          <button
            type="button"
            className={ classNames(
              'components-button',
              componentClassName + '__select-image',
            ) }
            onClick={ open }
          >
            { __( 'Select image' ) }
          </button>
        ) }

        <button className={ componentClassName + '__edit-image' } onClick={ open }>
          <span className={ componentClassName + '__edit-image-label' }>{ __( 'Edit Image' ) }</span>
          { mediaId > 0 && image && (
            <img src={ image.media_details.sizes.full.source_url }
              style={ style }
              className={ imageClassName }
              alt={ '' }
            />
          ) }
        </button>

        { mediaId > 0 && (
          <button
            type="button"
            className="components-button is-link is-destructive"
            onClick={ () => this.updateImage( 0, '' ) }
          >
            { __( 'Remove image' ) }
          </button>
        ) }
      </div>
    );

  }

  renderInlineEditor( open ) {
    const {
      attributes,
      style,
      image,
      imageClassName,
      className,
    } = this.props;

    const mediaId = attributes[ this.props.mediaIdField ];


    // add video --- https://webdevstudios.com/2018/04/12/creating-a-global-options-component-in-gutenberg/



    return (
      <Fragment>
        { mediaId === 0 && (
          <Fragment>
            <button
              type="button"
              className={ classNames(
                'components-button',
                componentClassName + '__select-image',
              ) }
              onClick={ open }
            >
              { __( 'Select image' ) }
            </button>
            <IconButton
              label={ __( 'Select image' ) }
              icon="format-image"
              className={ componentClassName + '__change-image' }
              onClick={ open }
            />
          </Fragment>
        ) }

        { mediaId > 0 && image && (
          <Fragment>
            <IconButton
              label={ __( 'Remove image' ) }
              icon="no-alt"
              className={ componentClassName + '__change-image' }
              onClick={ () => this.updateImage( 0, '' ) }
            />
            { /*
            <button
              className={ classNames(
                componentClassName + '__edit-image',
                inlineButtonClassName,
              ) }
              onClick={ open }
              role="button"
            >
            */ }
            <img src={ image.media_details.sizes.full.source_url }
              style={ style }
              className={ imageClassName }
              alt={ '' }
            />
            { /*</button>*/ }
          </Fragment>
        ) }
      </Fragment>
    );

  }

  updateImage( id ) {
    this.props.setAttributes( {
      [ this.props.mediaIdField ]: id,
    } );
  }


  render() {

    const {
      allowedTypes,
      attributes,
      inlineEditor,
    } = this.props;

    const mediaId = attributes[ this.props.mediaIdField ];

    const instructions = <p>{ __( 'To edit the background image, you need permission to upload media.' ) }</p>;

    return (
      <Fragment>
        <MediaUploadCheck fallback={ instructions }>
          <MediaUpload
            //type="video"
            onSelect={ ( media ) => this.updateImage( media.id, media.url ) }
            allowedTypes={ allowedTypes }
            value={ mediaId }
            render={ ( { open } ) => (
              inlineEditor ? this.renderInlineEditor( open ) : this.renderEditor( open )
            ) }
          />
        </MediaUploadCheck>
      </Fragment>
    );

  }

}


export default compose(
  withSelect( ( select, props ) => {
    const mediaId = props.attributes[ props.mediaIdField ];
    const image = mediaId ? select( 'core' ).getMedia( mediaId ) : null;

    return { image: image };
  } ),
)( ImageEditor );
