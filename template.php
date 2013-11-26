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
  $show_sa = array(
    'node/add/simpleads',
    'node/add/advertisement',
    'node/add/resource',
    );

  if (arg(0) == 'node' && is_numeric(arg(1)) && arg(2) == 'edit') {
    $nid = arg(1);
    $node = node_load($nid);
    if ($node->type == 'advertisement' || $node->type == 'resource') {
      array_push($show_sa, current_path());
    }
  }

  if (arg(0) == 'admin' && arg(1) == 'domain' && arg(2) == 'content') {
    array_push($show_sa, current_path());
  }

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
      if (check_plain($choice) === 'South Africa' && !in_array(current_path(), $show_sa)) {
      	$options .= '<option class="' . drupal_clean_css_identifier($key) . '"  value="' . check_plain($key) . '"' . $selected . ' data-hidden="true">' . check_plain($choice) . '</option>';
      } else { 
      	$options .= '<option class="' . drupal_clean_css_identifier($key) . '"  value="' . check_plain($key) . '"' . $selected . '>' . check_plain($choice) . '</option>';
      }
    }
  }
  return $options;
}