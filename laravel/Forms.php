<?php
/*
 * This is a custom class for generating form elements (inputs, checkboxes, textareas, etc)
 */

class Forms {
    public static $settings = [
        'container-element' => 'div',
        'label-prefix' => 'form__',
    ];



    public static function generate_label( $type='text', $label='', $name='' ) {
        #todo: need to replace class w/ form-check-label, etc depending on the $type of input
        $is_checkbox_radio = in_array($type, ['checkbox', 'radio']);

        return sprintf(
            '<label class="%s" for="%s%s%s">%s</label>',
            $is_checkbox_radio ? 'form-check-label' : 'form-label',
            self::$settings['label-prefix'],
            $name,
            $is_checkbox_radio ? '__' . self::to_kebab_case($label) : '', // if checkbox/radio... create unique name based on label
            $label
        );
    }

    public static function generate_text( $label='') {
        return sprintf(
            '<div class="form-text">%s</div>',
            $label
        );
    }

    # generates the entire container,label,input
    public static function generate_field( $type='text', $label='', $name='', $value='', $error='', $attributes=[] ) {
        $is_checkbox_radio = in_array($type, ['checkbox', 'radio']);

        $default_attributes = [
            'class' => '',
            'id' => self::$settings['label-prefix'] . $name . ($is_checkbox_radio ? '__' . self::to_kebab_case($label) : ''), // if checkbox/radio... create unique name based on label
            'name' => $is_checkbox_radio ? $name . '[]' : $name,
            'value' => $value,
        ];

        # opening container tag
        $html = sprintf(
            '<%s class="%s %s">',
            self::$settings['container-element'],
            $is_checkbox_radio ? "form-check" : 'form-group',
            isset($attributes['required']) ? "form-group-required" : ''
        );

        if(is_array($label)) {
            $html .= self::generate_label($type, $label['label'], $name);
        } else {
            $html .= self::generate_label($type, $label, $name);
        }

        # gen the actual input
        $html .= self::generate_field_tag($type, $label, $name, $value, $error, array_replace($default_attributes, $attributes));
        $html .= self::generate_error($name, $error);

        if(is_array($label) && isset($label['description']))
            $html .= self::generate_text($label['description']);



        # closing container tag
        $html .= sprintf( '</%s>', self::$settings['container-element']);

        return $html;
    }




    # generates the html of an input
    public static function generate_field_tag( $type='text', $label='', $name='', $value='', $error='', $attributes=[] ) {
        $field_description = '';

        // this is the optional <p class="field-description">DESCRIPTION</p> that goes between label/input
        if(isset($attributes['field-description'])) {
            $field_description = sprintf('<p class="field-description">%s</p>', $attributes['field-description']);
            unset($attributes['field-description']);
        }


        if($error) {
            $attributes['class'] .= ' is-invalid';
            $attributes['aria-describedby '] = sprintf('feedback__%s', $name);
        }

        # if type = input, checkbox, select, etc
        switch ($type){
            case 'text':
            case 'date':
            case 'file':
            case 'email':
            case 'number':
                $attributes['type'] = $type;
                $attributes['class'] .= ' form-control';

                return sprintf( '%s<input %s />', $field_description, self::generate_html_attributes($attributes));
                break;


            case 'checkbox':
            case 'radio':
                $attributes['type'] = $type;
                $attributes['class'] .= ' form-check-input';

                return sprintf( '%s<input %s />', $field_description, self::generate_html_attributes($attributes));
                break;


            case 'textarea':
                unset($attributes['value']);
                $attributes['class'] .= ' form-control';

                return sprintf(
                    '%s<textarea %s>%s</textarea>',
                    $field_description,
                    self::generate_html_attributes($attributes),
                    $value
                );
                break;



            case 'select':
                $options = '';
                $attributes['class'] .= ' form-select';

                //generate all the <option>s
                if(isset($attributes['options'])) {
                    foreach($attributes['options'] as $option_value => $option_label) {
                        $selected = $value === $option_value ? 'selected' : '';

                        $options .= sprintf(
                            '<option value="%s" %s>%s</option>',
                            $option_value,
                            $selected,
                            $option_label
                        );
                    }

                    // unset so it doesnt get generated in the html attrs later
                    unset($attributes['options']);
                }


                return sprintf(
                    '%s<select %s>%s</select>',
                    $field_description,
                    self::generate_html_attributes($attributes),
                    $options
                );
                break;


            default:
                return true;
        }

    }




    public static function generate_error($name='', $error='') {
        # todo: detect if required field. spit out default client side error msg
        return sprintf( '<div id="%s" class="invalid-feedback">%s</div>', 'feedback__' . $name, $error );
    }

    public static function generate_html_attributes($attributes=[]) {
        $html = '';

        foreach($attributes as $name => $value) {


            // dont print anything if false. like for checkboxes
            if($value == false)
                continue;

            switch($name){
                // these dont display value, just the name
                case 'required':
                    $html .= sprintf( '%s ', $name);
                    break;

                default:
                    $html .= sprintf( '%s="%s" ', $name, $value);

            }
        }

        return $html;
    }

    public static function generate_array_from_object($object, $key_name, $value_name) {
        $array = [];

        foreach ($object as $o)
            $array[ $o->{$key_name} ] = $o->{$value_name};

        return $array;
    }

    // https://developer.wordpress.org/reference/functions/_wp_to_kebab_case/
    public static function to_kebab_case($input_string) {
        /** Used to compose unicode character classes. */
        $rsLowerRange       = 'a-z\\xdf-\\xf6\\xf8-\\xff';
        $rsNonCharRange     = '\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf';
        $rsPunctuationRange = '\\x{2000}-\\x{206f}';
        $rsSpaceRange       = ' \\t\\x0b\\f\\xa0\\x{feff}\\n\\r\\x{2028}\\x{2029}\\x{1680}\\x{180e}\\x{2000}\\x{2001}\\x{2002}\\x{2003}\\x{2004}\\x{2005}\\x{2006}\\x{2007}\\x{2008}\\x{2009}\\x{200a}\\x{202f}\\x{205f}\\x{3000}';
        $rsUpperRange       = 'A-Z\\xc0-\\xd6\\xd8-\\xde';
        $rsBreakRange       = $rsNonCharRange . $rsPunctuationRange . $rsSpaceRange;

        /** Used to compose unicode capture groups. */
        $rsBreak  = '[' . $rsBreakRange . ']';
        $rsDigits = '\\d+'; // The last lodash version in GitHub uses a single digit here and expands it when in use.
        $rsLower  = '[' . $rsLowerRange . ']';
        $rsMisc   = '[^' . $rsBreakRange . $rsDigits . $rsLowerRange . $rsUpperRange . ']';
        $rsUpper  = '[' . $rsUpperRange . ']';

        /** Used to compose unicode regexes. */
        $rsMiscLower = '(?:' . $rsLower . '|' . $rsMisc . ')';
        $rsMiscUpper = '(?:' . $rsUpper . '|' . $rsMisc . ')';
        $rsOrdLower  = '\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])';
        $rsOrdUpper  = '\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])';

        $regexp = '/' . implode(
                '|',
                array(
                    $rsUpper . '?' . $rsLower . '+' . '(?=' . implode( '|', array( $rsBreak, $rsUpper, '$' ) ) . ')',
                    $rsMiscUpper . '+' . '(?=' . implode( '|', array( $rsBreak, $rsUpper . $rsMiscLower, '$' ) ) . ')',
                    $rsUpper . '?' . $rsMiscLower . '+',
                    $rsUpper . '+',
                    $rsOrdUpper,
                    $rsOrdLower,
                    $rsDigits,
                )
            ) . '/u';

        preg_match_all( $regexp, str_replace( "'", '', $input_string ), $matches );
        return strtolower( implode( '-', $matches[0] ) );
    }
}

