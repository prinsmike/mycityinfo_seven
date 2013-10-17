<?php

// theme_select
function mycityinfo_seven_select($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id', 'name', 'size'));
  _form_set_class($element, array('form-select'));
  return '<select' . drupal_attributes($element['#attributes']) . '>' . mycityinfo_seven_form_select_options($element) . '</select>';
}

/**
 *
 * @param type $element
 * @param type $choices
 * @return string 
 */
function mycityinfo_seven_form_select_options($element, $choices = NULL) {
  if (!isset($choices)) {
    $choices = $element['#options'];
  }
  // array_key_exists() accommodates the rare event where $element['#value'] is NULL.
  // isset() fails in this situation.
  $value_valid = isset($element['#value']) || array_key_exists('#value', $element);
  $value_is_array = $value_valid && is_array($element['#value']);
  $options = '';
  foreach ($choices as $key => $choice) {
    if (is_array($choice)) {
      $options .= '<optgroup label="' . $key . '">';
      $options .= mycityinfo_seven_form_select_options($element, $choice);
      $options .= '</optgroup>';
    }
    elseif (is_object($choice)) {
      $options .= mycityinfo_seven_form_select_options($element, $choice->option);
    }
    else {
      $key = (string) $key;
      if ($value_valid && (!$value_is_array && (string) $element['#value'] === $key || ($value_is_array && in_array($key, $element['#value'])))) {
        $selected = ' selected="selected"';
      }
      else {
        $selected = '';
      }
      if (check_plain($choice) === 'South Africa') {
      	$options .= '<option class="' . drupal_clean_css_identifier($key) . '"  value="' . check_plain($key) . '"' . $selected . ' data-hidden="true">' . check_plain($choice) . '</option>';
      } else { 
      	$options .= '<option class="' . drupal_clean_css_identifier($key) . '"  value="' . check_plain($key) . '"' . $selected . '>' . check_plain($choice) . '</option>';
      }
    }
  }
  return $options;
}