const { __ } = wp.i18n;
const blockSettings = {
  name:                  'hero',
  title:                 'Everside - Hero',
  description:           'Hero banner',
  icon:                  'cover-image',
  category:              '',
  keywords:              ['hero', 'image'],
  parent:                null, //[]
  ALLOWED_BLOCKS: [
    'core/heading',
    'core/paragraph',
    'everside/button',
  ],
  INNERBLOCKS_TEMPLATE: [
    [ 'core/heading', {
      level: 1,
      content: '',
      //anchor: 'news', # anchor link
      placeholder: __( 'Title' ),
    } ],
    [ 'core/heading', {
      level: 2,
      content: '',
      placeholder: __( 'Subtitle' ),
    } ],

    [ 'everside/button', {
      content: '',
      placeholder: 'Button text',
    } ],
    /*
    ['core/paragraph', {
      //align: 'center',
      //className: 'block-press__contact',
      content: '',
      placeholder: 'Paragraph text..'
    } ],
    */
  ],

  attributes: {
    bgColor: {
      type: 'string',
      default: '',
    },
    bgImageId: {
      type: 'number',
      default: 0,
    },
    bgImageUrl: {
      type: 'string',
      default: '',
    },
    bgMobileImageId: {
      type: 'number',
      default: 0,
    },
    bgMobileImageUrl: {
      type: 'string',
      default: '',
    },
    waveColor: {
      type: 'string',
      default: '',
    },
  },

};


export default blockSettings;
