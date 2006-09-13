<?php

  /**
  * Render form label element. This helper makes it really simple to mark reqired elements
  * in a standard way
  *
  * @param string $text Label content
  * @param string $for ID of related elementet
  * @param boolean $is_required Mark as a required fiedl
  * @param array $attributes Additional attributes
  * @param string $after_label Label text sufix
  * @return null
  */
  function label_tag($text, $for = null, $is_required = false, $attributes = null, $after_label = ':') {
    if(trim($for)) {
      if(is_array($attributes)) {
        $attributes['for'] = trim($for);
      } else {
        $attributes = array('for' => trim($for));
      } // if
    } // if
    
    $render_text = trim($text) . $after_label;
    if($is_required) $render_text .= ' <span class="label_required">*</span>';
    
    return open_html_tag('label', $attributes) . $render_text . close_html_tag('label');
  } // form_label

  /**
  * Render input field
  *
  * @param string $name Field name
  * @param mixed $value Field value. Default is NULL
  * @param array $attributes Additional field attributes
  * @return string
  */
  function input_field($name, $value = null, $attributes = null) {
    $field_attributes = is_array($attributes) ? $attributes : array();
    
    $field_attributes['name'] = $name;
    $field_attributes['value'] = $value;
    
    return open_html_tag('input', $field_attributes, true);
  } // input_field
  
  /**
  * Render text field
  *
  * @param string $name
  * @param mixed $value
  * @param array $attributes Array of additional attributes
  * @return string
  */
  function text_field($name, $value = null, $attributes = null) {
    if(array_var($attributes, 'type', false) === false) {
      if(is_array($attributes)) {
        $attributes['type'] = 'text';
      } else {
        $attributes = array('type' => 'text');
      } // if
    } // if
    
    return input_field($name, $value, $attributes);
  } // text_field
  
  /**
  * Render password field
  *
  * @param string $name
  * @param mixed $value
  * @param array $attributes
  * @return string
  */
  function password_field($name, $value = null, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['type'] = 'password';
    } else {
      $attributes = array('type' => 'password');
    } // if
    
    return text_field($name, $value, $attributes);
  } // password_filed
  
  /**
  * Return file field
  *
  * @param string $name
  * @param mixed $value
  * @param array $attributes
  * @return string
  */
  function file_field($name, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['type'] = 'file';
    } else {
      $attributes = array('type' => 'file');
    } // if
    
    return text_field($name, array_var($attributes, 'value'), $attributes);
  } // file_field
  
  /**
  * Render radio field
  *
  * @param string $name Field name
  * @param mixed $value
  * @param boolean $checked
  * @param array $attributes Additional attributes
  * @return string
  */
  function radio_field($name, $checked = false, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['type'] = 'radio';
      if(!isset($attributes['class'])) {
        $attributes['class'] = 'checkbox';
      } // if
    } else {
      $attributes = array('type' => 'radio', 'class' => 'checkbox');
    } // if
    
    // Value
    $value = array_var($attributes, 'value', false);
    if($value === false) {
      $value = 'checked';
    } // if
    
    // Checked
    if($checked) {
      $attributes['checked'] = 'checked';
    } else {
      if(isset($attributes['checked'])) {
        unset($attributes['checked']);
      } // if
    } // if
    
    return input_field($name, $value, $attributes);
  } // radio_field
  
  /**
  * Render checkbox field
  *
  * @param string $name Field name
  * @param boolean $checked Checked?
  * @param array $attributes Additional attributes
  * @return string
  */
  function checkbox_field($name, $checked = false, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['type'] = 'checkbox';
      if(!isset($attributes['class'])) {
        $attributes['class'] = 'checkbox';
      } // if
    } else {
      $attributes = array('type' => 'checkbox', 'class' => 'checkbox');
    } // if
    
    // Value
    $value = array_var($attributes, 'value', false);
    if($value === false) {
      $value = 'checked';
    } // if
    
    // Checked
    if($checked) {
      $attributes['checked'] = 'checked';
    } else {
      if(isset($attributes['checked'])) {
        unset($attributes['checked']);
      } // if
    } // if
    
    return input_field($name, $value, $attributes);
  } // checkbox_field
  
  /**
  * This helper will render select list box. Options is array of already rendered option 
  * and optgroup tags
  *
  * @param string $name
  * @param array $options Array of already rendered option and optgroup tags
  * @param array $attributes Additional attributes
  * @return null
  */
  function select_box($name, $options, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['name'] = $name;
    } else {
      $attributes = array('name' => $name);
    } // if
    
    $output = open_html_tag('select', $attributes) . "\n";
    if(is_array($options)) {
      foreach($options as $option) {
        $output .= $option . "\n";
      } // foreach
    } // if
    return $output . close_html_tag('select') . "\n";
  } // select_box
  
  /**
  * Render option tag
  *
  * @param string $text Option text
  * @param mixed $value Option value
  * @param array $attributes
  * @return string
  */
  function option_tag($text, $value = null, $attributes = null) {
    if(!is_null($value)) {
      if(is_array($attributes)) {
        $attributes['value'] = $value;
      } else {
        $attributes = array('value' => $value);
      } // if
    } // if
    return open_html_tag('option', $attributes) . clean($text) . close_html_tag('option');
  } // option_tag
  
  /**
  * Render option group
  *
  * @param string $label Group label
  * @param array $options
  * @param array $attributes
  * @return string
  */
  function option_group_tag($label, $options, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['label'] = $label;
    } else {
      $attributes = array('label' => $label);
    } // if
    
    $output = open_html_tag('optgroup', $attributes) . "\n";
    if(is_array($options)) {
      foreach($options as $option) {
        $output .= $option . "\n";
      } // foreach
    } // if
    return $output . close_html_tag('optgroup') . "\n";
  } // option_group_tag

  /**
  * Render submit button
  *
  * @param string $this Button title
  * @param string $accesskey Accesskey. If NULL accesskey will be skipped
  * @param array $attributes Array of additinal attributes
  * @return string
  */
  function submit_button($title, $accesskey = 's', $attributes = null) {
    if(!is_array($attributes)) {
      $attributes = array();
    } // if
    
    $attributes['class'] = 'submit';
    $attributes['type'] = 'submit';
    $attributes['accesskey'] = $accesskey;
    
    $show_title = empty($accesskey) ? $title : $title . ' (Alt+' . strtoupper($accesskey) . ')';
    return open_html_tag('button', $attributes) . $show_title . close_html_tag('button');
  } // submit_button
  
  /**
  * Return textarea tag
  *
  * @param string $name
  * @param string $value
  * @param array $attributes Array of additional attributes
  * @return string
  */
  function textarea_field($name, $value, $attributes = null) {
    if(!is_array($attributes)) {
      $attributes = array();
    } // if
    $attributes['name'] = $name;
    if(!isset($attributes['rows']) || trim($attributes['rows'] == '')) {
      $attributes['rows'] = '10'; // required attribute
    } // if
    if(!isset($attributes['cols']) || trim($attributes['cols'] == '')) {
      $attributes['cols'] = '40'; // required attribute
    } // if
    
    return open_html_tag('textarea', $attributes) . clean($value) . close_html_tag('textarea');
  } // textarea
  
  // ---------------------------------------------------
  //  Widgets
  // ---------------------------------------------------
  
  /**
  * Return date time picker widget
  *
  * @param string $name Field name
  * @param string $value Date time value
  * @return string
  */
  function pick_datetime_widget($name, $value = null) {
    return text_field($name, $value);
  } // pick_datetime_widget
  
  /**
  * Return pick date widget
  *
  * @param string $name Name prefix
  * @param Angie_DateTime $value Can be Angie_DateTime object, integer or string
  * @param integer $year_from Start counting from this year. If NULL this value will be set
  *   to current year - 10
  * @param integer $year_to Count to this year. If NULL this value will be set to current
  *   year + 10
  * @return null
  */
  function pick_date_widget($name, $value = null, $year_from = null, $year_to = null) {
    if(!($value instanceof Angie_DateTime)) {
      $value = new Angie_DateTime($value);
    } // if
    
    $month_options = array();
    for($i = 1; $i <= 12; $i++) {
      $option_attributes = $i == $value->getMonth() ? array('selected' => 'selected') : null;
      $month_options[] = option_tag(lang("month $i"), $i, $option_attributes);
    } // for
    
    $day_options = array();
    for($i = 1; $i <= 31; $i++) {
      $option_attributes = $i == $value->getDay() ? array('selected' => 'selected') : null;
      $day_options[] = option_tag($i, $i, $option_attributes);
    } // for
    
    $year_from = (integer) $year_from < 1 ? $value->getYear() - 10 : (integer) $year_from;
    $year_to = (integer) $year_to < 1 || ((integer) $year_to < $year_from) ? $value->getYear() + 10 : (integer) $year_to;
    
    $year_options = array();
    for($i = $year_from; $i <= $year_to; $i++) {
      $option_attributes = $i == $value->getYear() ? array('selected' => 'selected') : null;
      $year_options[] = option_tag($i, $i, $option_attributes);
    } // if
    
    return select_box($name . '_month', $month_options) . select_box($name . '_day', $day_options) . select_box($name . '_year', $year_options);
  } // pick_date_widget
  
  /**
  * Return pick time widget
  *
  * @param string $name
  * @param string $value
  * @return string
  */
  function pick_time_widget($name, $value = null) {
    return text_field($name, $value);
  } // pick_time_widget
  
  /**
  * Return WYSIWYG editor widget
  *
  * @param string $name
  * @param string $value
  * @return string
  */
  function editor_widget($name, $value = null, $attributes = null) {
    $editor_attributes = is_array($attributes) ? $attributes : array();
    if(!isset($editor_attributes['class'])) {
      $editor_attributes['class'] = 'editor';
    } // if
    return textarea_field($name, $value, $editor_attributes);
  } // editor_widget
  
  /**
  * Render yes no widget
  *
  * @param string $name
  * @param $id_base
  * @param boolean $value If true YES will be selected, otherwise NO will be selected
  * @param string $yes_lang
  * @param string $no_lang
  * @return null
  */
  function yes_no_widget($name, $id_base, $value, $yes_lang, $no_lang) {
    $yes_input = radio_field($name, $value, array('id' => $id_base . 'Yes', 'class' => 'yes_no', 'value' => 1));
    $no_input = radio_field($name, !$value, array('id' => $id_base . 'No', 'class' => 'yes_no', 'value' => 0));
    $yes_label = label_tag($yes_lang, $id_base . 'Yes', false, array('class' => 'yes_no'), '');
    $no_label = label_tag($no_lang, $id_base . 'No', false, array('class' => 'yes_no'), '');
    
    return $yes_input . ' ' . $yes_label . ' ' . $no_input . ' ' . $no_label;
  } // yes_no_widget
  
  /**
  * Show select country box
  *
  * @param string $name Control name
  * @param string $value Country code of selected country
  * @param array $attributes Array of additional select box attributes
  * @return string
  */
  function select_country_widget($name, $value, $attributes = null) {
    $country_codes = array_keys(CountryCodes::getAll());
    $country_options = array(option_tag(lang('none'), ''));
    foreach($country_codes as $code) {
      $option_attributes = $code == $value ? array('selected' => true) : null;
      $country_options[] = option_tag(lang("country $code"), $code, $option_attributes);
    } // foreach
    
    return select_box($name, $country_options, $attributes);
  } // select_country_widget
  
  /**
  * Render select timezone widget
  *
  * @param string $name Name of the select box
  * @param float $value Timezone value. If NULL GMT will be selected
  * @param array $attributes Array of additional attributes
  * @return string
  */
  function select_timezone_widget($name, $value = null, $attributes = null) {
    $selected_value = (float) $value;
    $all_timezones = Timezones::getTimezones();
    
    $options = array();
    foreach($all_timezones as $timezone) {
      $option_attributes = $selected_value == $timezone ? array('selected' => true) : null;
      $option_text = $timezone > 0 ? lang("timezone gmt +$timezone") : lang("timezone gmt $timezone");
      $options[] = option_tag($option_text, $timezone, $option_attributes);
    } // if
    
    return select_box($name, $options, $attributes);
  } // select_timezone_widget

?>