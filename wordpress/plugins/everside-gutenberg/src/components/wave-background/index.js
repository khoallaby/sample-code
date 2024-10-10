const { __ } = wp.i18n;
import './editor.scss';

const {
  BaseControl,
  PanelRow,
  SelectControl,
  ToggleControl,
} = wp.components;

const { Component } = wp.element;


const waveStyles = [
  { label: 'Normal', value: '' },
  { label: 'Shortened', value: 'shortened' },
  { label: 'Extended', value: 'extended' },
];


export class WaveBackgroundSetting extends Component {
  render() {
    const {
      waveBg,
      waveStyle,
      onChangeWaveBg,
      onChangeWaveStyle,
    } = this.props;

    return (
      <div className={ 'wp-component-wave-background' }>
        <PanelRow>
          <ToggleControl
            label={ __( 'Add Wave Background' ) }
            checked={ waveBg }
            onChange={ onChangeWaveBg }
          />
        </PanelRow>
        { waveBg && (
          <PanelRow>
            <BaseControl className="w-100">
              <SelectControl
                label={ __( 'Wave Style' ) }
                value={ waveStyle }
                options={ waveStyles }
                onChange={ onChangeWaveStyle }
              />
            </BaseControl>
          </PanelRow>
        ) }
      </div>
    );
  }
}

