<?php 
/*
Plugin Name: Formidable Multilingual
Description: Add multilingual support for Formidable
Plugin URI: http://formidablepro.com/
Author: Strategy11
Author URI: http://formidablepro.com
Version: 1.03.03
*/

$formidable_wpml = new FormidableWPML();

class FormidableWPML{

	function __construct() {
        add_action('plugins_loaded', 'FormidableWPML::setup');
        add_action('admin_init', 'FormidableWPML::include_updater', 1);
    }

    public static function setup() {
        add_action('frm_form_action_translate', 'FormidableWPML::translate');
        
        if ( !function_exists('icl_t') ) {
            return;
        }
        
        add_filter('WPML_get_translatable_types', 'FormidableWPML::get_translatable_types');
        add_filter('WPML_get_translatable_items', 'FormidableWPML::get_translatable_items', 10, 3);
        add_filter('WPML_get_translatable_item', 'FormidableWPML::get_translatable_item', 10, 2);
        add_filter('WPML_get_link', 'FormidableWPML::get_link', 10, 4);

		add_filter( 'frm_ajax_url', 'FormidableWPML::set_ajax_language' );

        add_filter('frm_pre_display_form', 'FormidableWPML::setup_frm_wpml_form');
        add_filter('frm_setup_edit_entry_vars', 'FormidableWPML::setup_frm_wpml_form_vars', 20, 2);
        add_filter('frm_setup_new_fields_vars', 'FormidableWPML::setup_frm_wpml', 20, 2);
        add_filter('frm_setup_edit_fields_vars', 'FormidableWPML::setup_frm_wpml', 20, 2);
        add_filter('frm_exclude_cats', 'FormidableWPML::filter_taxonomies', 10, 2);
        add_filter('frm_form_replace_shortcodes', 'FormidableWPML::replace_form_shortcodes', 9, 3);
        add_filter('frm_recaptcha_lang', 'FormidableWPML::captcha_lang');
        add_filter('frm_submit_button', 'FormidableWPML::submit_button_label', 20, 2);
        add_filter('frm_validate_field_entry', 'FormidableWPML::setup_frm_wpml_validation', 30, 2);
        //add_filter('frmpro_fields_replace_shortcodes', 'FormidableWPML::views_to_wpml', 9, 4);
        add_action('frm_before_destroy_field', 'FormidableWPML::delete_frm_wpml');
        add_action('frm_delete_message', 'FormidableWPML::delete_message', 10, 2);

        add_action('frm_settings_buttons', 'FormidableWPML::add_translate_button');
        add_action('frm_form_action_update_translate', 'FormidableWPML::update_translate');
        add_filter('frm_form_stop_action_translate', 'FormidableWPML::translated');
        add_filter('frm_form_stop_action_update_translate', 'FormidableWPML::translated');
    }
    
    
    public static function include_updater(){
		if ( class_exists( 'FrmAddon' ) ) {
			include_once( dirname(__FILE__) . '/FrmWpmlUpdate.php' );
			FrmWpmlUpdate::load_hooks();
		}
    }


    public static function get_translatable_types($types) {
        // Tell WPML that we want formidable forms translated
        $types['formidable'] = 'Formidable';
        return $types;
    }

	private static function _get_form_strings( $form ) {
		if ( ! is_object( $form ) ) {
			$form = FrmForm::getOne( $form );
		}

		$fields = FrmField::getAll( array( 'fi.form_id' => $form->id ), 'field_order' );
        
        $string_data = $user_ids = array();
		foreach ( $fields as $k => $field ) {
			if ( $field->form_id != $form->id ) {
				// this field is in a repeating section
				unset( $fields[ $k ] );
				continue;
			}

            if ( $field->type == 'user_id' ) {
                $user_ids[] = $field->id;
            } else if ( $field->type == 'break' && ! isset($string_data['back_label']) ) {
                $string_data['back_label'] = __('Previous', 'formidable');
            }
            unset($field);
        }
        
		$form_keys = array(
		    'name', 'description', 'submit_value', 'submit_msg', 'success_msg',
		    'email_subject', 'email_message', 'ar_email_subject', 'ar_email_message',
		);
		
		// Add edit and delete options
		if ( $form->editable ) {
		    $form_keys[] = 'edit_value';
		    $form_keys[] = 'edit_msg';
		    $string_data['delete_msg'] = __( 'Your entry was successfully deleted', 'formidable' );
		}
						   
		foreach ($form_keys as $key) {
		    if ( isset($form->{$key}) && $form->{$key} != '' ) {
				$string_data[$key] = $form->{$key};
			} else if ( isset($form->options[$key]) && $form->options[$key] != '' && $form->options[$key] != '[default-message]' ) {
			    $string_data[$key] = $form->options[$key];
			}
		}
		
		// Add draft translations
		if ( isset($form->options['save_draft']) && $form->options['save_draft'] ) {
		    if ( isset($form->options['draft_msg']) ) {
		        $string_data['draft_msg'] = $form->options['draft_msg'];
		    }

		    $string_data['draft_label'] = __('Save Draft', 'formidable');
		}
		
		global $frm_settings;
		$string_data['invalid_msg'] = $frm_settings->invalid_msg;

		$keys = array(
		    'name', 'description', 'default_value', 'required_indicator', 'blank', 'unique_msg'
		);
		
		foreach ( $fields as $field ) {
			
			foreach ($keys as $key) {
				if ( $key == 'name' && $field->type == 'end_divider' ) {
					// since the name of an end section field isn't shown, skip it
					continue;
				}

				if ( isset($field->{$key}) && $field->{$key} != '' && $field->{$key} != '*' && ! is_array($field->{$key}) ) {
					$string_data['field-' . $field->id . '-' . $key] = $field->{$key};
				} else if ( isset($field->field_options[$key]) && $field->field_options[$key] != '' && $field->field_options[$key] != '*' && ! is_array($field->field_options{$key}) ) {
    				$string_data['field-' . $field->id . '-' . $key] = $field->field_options[$key];
    			}
				unset( $key );
			}
			
			if ( ! $field->required ) {
			    unset($string_data['field-' . $field->id . '-blank']);
			}
				
			switch ( $field->type ) {
			    case 'date':
			        if ( isset($field->field_options['locale']) && $field->field_options['locale'] != '' ) {
    					$string_data['field-' . $field->id . '-locale'] = $field->field_options['locale'];
    				}
    			break;
			    case 'email':
                case 'url':
                case 'website':
                case 'phone':
                case 'image':
                case 'number':
                case 'file':
                    if ( isset($field->field_options['invalid']) && $field->field_options['invalid'] != '' ) {
    					$string_data['field-' . $field->id . '-invalid'] = $field->field_options['invalid'];
    				}
                break;
				case 'select':
				case 'checkbox':
				case 'radio':
				    if ( in_array($field->id, $user_ids) ) {
				        break;
				    }
				        
				    if ( is_array($field->options) && !isset($field->options['label']) ) {
    					foreach ( $field->options as $index => $choice) {
    					    if(is_array($choice))
    					        $choice = isset($choice['label']) ? $choice['label'] : reset($choice);
    					    $string_data['field-' . $field->id . '-choice-' . $choice] = $choice;
    					}
    				} else {
    				    if ( is_array($field->options) ) {
					        $field->options = isset($field->options['label']) ? $field->options['label'] : reset($field->options);
					    }
					    
    				    $string_data['field-' . $field->id . '-choice-' . $field->options] = $field->options;
    				}
				break;					
			}
			
		}
		
		return $string_data;
		
	}
	
	//filter the form description and title before displaying
	public static function setup_frm_wpml_form($form){
            
        $form_keys = array(
		    'name', 'description',
		    'submit_value', 'submit_msg', 'success_msg',
		    'edit_value', 'edit_msg', 'email_subject', 'email_message', 
            'ar_email_subject', 'ar_email_message', 'draft_msg',
		);
							   
		foreach ( $form_keys as $key ) {
			if ( isset($form->{$key}) && $form->{$key} != '' ) {
		        $form->{$key} = stripslashes_deep(icl_t('formidable', $form->id . '_' . $key, $form->{$key}));
			} else if ( isset($form->options[$key]) && $form->options[$key] != '' ) {
		        $form->options[$key] = stripslashes_deep(icl_t('formidable', $form->id . '_' . $key, $form->options[$key]));
			}
			unset($key);
		}
		
		// override global messages
		global $frm_settings;
		$frm_settings->invalid_msg = stripslashes_deep(icl_t('formidable', $form->id . '_invalid_msg', $frm_settings->invalid_msg));
		
		return $form;
	}
	
	// filter form last, after button name may have been changed
	public static function setup_frm_wpml_form_vars($values, $entry){
		$form = FrmForm::getOne( $entry->form_id );
	    
	    if ( isset($form->options['edit_value']) && $values['edit_value'] == $form->options['edit_value'] ) {
	        $values['edit_value'] = stripslashes_deep(icl_t('formidable', $entry->form_id . '_edit_value', $values['edit_value']));
	    }
	    
	    return $values;
	}

    /*
    * If a term is excludd in the settings, exclude it for all languages
    */
    public static function filter_taxonomies( $exclude, $field ) {
        if ( empty($exclude) ) {
            // don't continue if there is nothing to exclude
            return $exclude;
        }

        global $sitepress;

        $default_language = $sitepress->get_default_language();
        $current_lang = ICL_LANGUAGE_CODE;

        if ( $current_lang == $default_language ) {
            // don't check if the excluded options are the correct ones to exclude
            return $exclude;
        }

        $post_type = FrmProFormsHelper::post_type($field['form_id']);
        $taxonomy = FrmProAppHelper::get_custom_taxonomy($post_type, $field);

        $excluded_ids = explode(',', $exclude);
        foreach ( $excluded_ids as $id ) {
            $trid = $sitepress->get_element_trid($id, 'tax_' . $taxonomy);
            $translations = $sitepress->get_element_translations($trid, 'tax_' . $taxonomy);

            if ( isset($translations[$current_lang]) ) {
                $excluded_ids[] = $translations[$current_lang]->term_id;
            }
        }

        $exclude = implode(',', $excluded_ids);

        return $exclude;
    }

	public static function captcha_lang($lang){
        $current_lang = ICL_LANGUAGE_CODE;
        $allowed = array(
            'en', 'nl', 'fr', 'de', 'pt', 'ru', 'es', 'tr',
        );
        if ( in_array($current_lang, $allowed) ) {
            $lang = $current_lang;
        }
        
        return $lang;
    }
	
	public static function submit_button_label($submit, $form){
	    global $frm_vars;
	    
	    //check if next button needs to be translated
	    if ( !isset($frm_vars['next_page'][$form->id]) || empty($frm_vars['next_page'][$form->id]) ) {
	        return $submit;
	    }
        
        $field = $frm_vars['next_page'][$form->id];
        
        if ( ! is_object($field) || $submit != $field->name ) {
            return $submit;
        }
            
        $submit = stripslashes_deep(icl_t('formidable', $form->id . '_field-' . $field->id . '-name', $submit));
        
        return $submit;
    }

    //filter the fields for before form is displayed
	public static function setup_frm_wpml($values, $field){
	    //don't interfere with the form builder page
        if ( is_admin() && ! defined('DOING_AJAX') && ( ! isset($_GET) || !isset($_GET['page']) || $_GET['page'] != 'formidable' || ! isset($_GET['frm_action']) || $_GET['frm_action'] != 'translate') ) {
            return $values;
        }
        
		$keys = array(
		    'name', 'description', 'default_value', 
		    'required_indicator', 'invalid', 'locale',
		    'blank', 'unique_msg'
		);
            
        $prev_default = $values['default_value'];
	    foreach ($keys as $key) {
		    if (isset($values[$key]) && $values[$key] != '' && !is_array($values[$key])) {
			    $values[$key] = stripslashes_deep(icl_t('formidable', $values['form_id'] . '_field-' . $values['id'] . '-' . $key, $values[$key]));
				if ( class_exists('FrmProFieldsHelper') ) {
					$values[ $key ] = FrmProFieldsHelper::get_default_value( $values[ $key ], $field, false, ( 'default_value' == $key ) );
				}
		    }
	    }
	    
		if($values['value'] == $prev_default)
		    $values['value'] = $values['default_value'];
			
		if ( ! in_array($values['type'], array('select', 'checkbox', 'radio', 'data')) || $field->type == 'user_id' ) {
		    return $values;
		}
		
		$sep_val = isset($values['separate_value']) ? $values['separate_value'] : 0;
		if ( is_array($values['options']) && !isset($values['options']['label']) ) {
			foreach ($values['options'] as $index => $choice) {
			    if ( is_array($choice) ) {
			        $choice = isset($choice['label']) ? $choice['label'] : reset($choice);
			        
			        // limit to 160 chars
			        $string_name = substr($values['form_id'] . '_field-' . $values['id'] . '-choice-' . $choice, 0, 160);
			        $values['options'][$index]['label'] = stripslashes_deep(icl_t('formidable', $string_name, $choice));
			        
			        if ( !$sep_val && isset($values['options'][$index]['value']) ) {
			            $values['options'][$index]['value'] = $choice;
			        }
			    } else {  
			        // limit to 160 chars
				    $string_name = substr($values['form_id'] . '_field-' . $values['id'] . '-choice-' . $choice, 0, 160);
				    
				    if ( (isset($values['use_key']) && $values['use_key']) || $sep_val || 'data' == $values['type'] ) {
				        $values['options'][$index] = stripslashes_deep(icl_t('formidable', $string_name, $choice));
				    }else{
				        $values['options'][$index] = array(
				            'label' => stripslashes_deep(icl_t('formidable', $string_name, $choice)),
				            'value' => $choice
				        );
				        
				        $values['separate_value'] = true;
				    }
			    }
			}
		}else{	
			if(is_array($values['options'])){
			    $string_name = substr($values['form_id'] . '_field-' . $values['id'] . '-choice-' . $values['options']['label'], 0, 160);
			    $values['options']['label'] = stripslashes_deep(icl_t('formidable', $string_name, $values['options']['label']));
			}else{
			    $string_name = substr($values['form_id'] . '_field-' . $values['id'] . '-choice-' . $values['options'], 0, 160);
			    $values['options'] = stripslashes_deep(icl_t('formidable', $string_name, $values['options']));
			}
		}
		
		return $values;
	}
	
	/*
	* Filter out text values before main Formidable plugin does
	*
	* @return string of HTML
	*/
	public static function replace_form_shortcodes( $html, $form, $values = array() ) {
        preg_match_all("/\[(if )?(back_label|draft_label)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $html, $shortcodes, PREG_PATTERN_ORDER);

        if ( empty($shortcodes[0]) ) {
            return $html;
        }
        
        foreach ($shortcodes[0] as $short_key => $tag){
            $replace_with = '';
            $atts = shortcode_parse_atts( $shortcodes[3][$short_key] );
            
            switch ( $shortcodes[2][$short_key] ) {
                case 'back_label':
                case 'draft_label':
                    $translation = stripslashes_deep(icl_t('formidable', $form->id . '_' . $shortcodes[2][$short_key], ''));
                    
                    if ( !empty($translation) ) {
                        $html = str_replace($shortcodes[0][$short_key], $translation, $html);
                    }
                break;
            }
            
            unset($short_key, $tag, $replace_with);
        }
        
        return $html;
	}
	
	public static function setup_frm_wpml_validation($errors, $field){
	    	    
	    $field->field_options = maybe_unserialize($field->field_options);
	    if ( isset($field->field_options['default_blank']) && $field->field_options['default_blank'] && isset($_POST['item_meta'][$field->id]) && $_POST['item_meta'][$field->id] != '' ) {
            $default_value = stripslashes_deep(icl_t('formidable', $field->form_id . '_field-' . $field->id . '-default_value', $field->default_value));
            if ( $_POST['item_meta'][$field->id] == $default_value && !isset($errors['field'. $field->id]) ) {
                $errors['field'. $field->id] = $field->field_options['blank'];
            }
        }
            
        //there are no errors to translate
	    if(!isset($errors['field'. $field->id]))
	        return $errors;
	        
	    $key = false;
	    if ( $errors['field'. $field->id] == $field->field_options['blank'] ) {
	        $key = 'blank';
	    } else if ( isset($field->field_options['invalid']) && $errors['field'. $field->id] == $field->field_options['invalid'] ) {
	        $key = 'invalid';
	    } else if ( isset($field->field_options['unique_msg']) && $errors['field'. $field->id] == $field->field_options['unique_msg'] ) {
	        $key = 'unique_msg';
	    }
	            
	    if($key)
	        $errors['field'.$field->id] = stripslashes_deep(icl_t('formidable', $field->form_id . '_field-' . $field->id . '-' . $key, $errors['field'. $field->id]));
        
	    return $errors;
	}
	
    public static function views_to_wpml($replace_with, $tag, $atts, $field) {
        if ( !in_array($field->type, array('select', 'radio', 'checkbox')) || ( isset($atts['show']) && $atts['show'] == 'value' )) {
            return $replace_with;
        }
        
        if ( is_array($replace_with) ) {
            foreach ( $replace_with as $k => $v ) {
                $string_name = substr($field->form_id . '_field-' . $field->id . '-choice-' . $v, 0, 160);
    			$replace_with[$k] = stripslashes_deep(icl_t('formidable', $string_name, $v));
                unset($k);
                unset($v);
            }
			    
		} else {
			$string_name = substr($field->form_id . '_field-' . $field->id . '-choice-' . $replace_with, 0, 160);
			$replace_with = stripslashes_deep(icl_t('formidable', $string_name, $replace_with));
        }
        
        return $replace_with;
    }
	
	public static function delete_frm_wpml($id){
	    global $wpdb;
	    
	    //delete strings before a field is deleted
	    $strings = $wpdb->get_col($wpdb->prepare("SELECT name FROM {$wpdb->prefix}icl_strings
            WHERE context=%s AND name LIKE %s", 'formidable', "%_field-{$id}-%"));

        if($strings){
            foreach($strings as $string){
                icl_unregister_string('formidable', $string);
                unset($string);
            }
        }
    }
    
    /*
    * Translate the message after an entry is deleted
    * @return string The translated value
    */
    public static function delete_message($message, $entry) {
        $translation = stripslashes_deep(icl_t('formidable', $entry->form_id . '_delete_msg', ''));
        if ( !empty($translation) ) {
            $message = $translation;
        }
        return $message;
    }
	
    public static function get_translatable_items($items, $type, $filter) {
        // Only return items if string translation is available, and if type is for formidable.
        if ( $type != 'formidable' ) {
            return $items;
        }
        
        global $sitepress, $wpdb;

        $default_lang = $sitepress->get_default_language();
        $languages = $sitepress->get_active_languages();

        if(isset($_GET) && isset($_GET['frm_action']) && $_GET['frm_action'] == 'translate' && isset($_GET['id']) && is_numeric($_GET['id'])){
			$form_id = absint( $_GET['id'] );
			$forms = FrmForm::getAll( $wpdb->prepare('parent_form_id=%d or id=%d', $form_id, $form_id ) );
			$form_array = array();
			foreach ( $forms as $form ) {
				$form_array[ $form->id ] = $form;
			}
			$forms = $form_array;
			unset( $form_array );
        }else{
			$forms = FrmForm::getAll( "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name' );
        }

        foreach($forms as $k => $v){
            $new_item = new stdClass();

            $new_item->external_type = true;
            $new_item->type = 'formidable';
            $new_item->id = $v->id;
            $new_item->post_type = 'formidable';
            $new_item->post_id = 'external_' . $new_item->post_type . '_' . $v->id;
            $new_item->post_date = $v->created_at;
			$new_item->post_status = $v->status == 'draft' ? __('Inactive', 'frmwpml') : __('Active', 'frmwpml');
            $new_item->post_title = $v->name;
			$new_item->is_translation = false;
            
			$new_item->string_data = self::_get_form_strings( $v );

            // add to the translation table if required
            $post_trid = $sitepress->get_element_trid($new_item->id, 'post_' . $new_item->post_type);
            if (!$post_trid)
                $sitepress->set_element_language_details($new_item->id, 'post_' . $new_item->post_type, false, $default_lang, null, false);

            // register the strings with WPML

            foreach ($new_item->string_data as $key => $value) {
                $key = (function_exists('mb_substr')) ? mb_substr($new_item->id . '_' . $key, 0, 160) : substr($new_item->id . '_' . $key, 0, 160);
                if (!icl_st_is_registered_string('formidable', $key))
                    icl_register_string('formidable', $key, $value);
                
				unset( $key, $value );
            }

            $post_trid = $sitepress->get_element_trid($new_item->id, 'post_' . $new_item->post_type);
            $post_translations = $sitepress->get_element_translations($post_trid, 'post_' . $new_item->post_type);

            global $iclTranslationManagement;

            $md5 = $iclTranslationManagement->post_md5($new_item);

            foreach ($post_translations as $lang => $translation) {
                $res = $wpdb->get_row("SELECT status, needs_update, md5 FROM {$wpdb->prefix}icl_translation_status WHERE translation_id={$translation->translation_id}");
                if ($res) {
                    if (!$res->needs_update) {
                        // see if the md5 has changed.
                        if ($md5 != $res->md5) {
                            $res->needs_update = 1;
                            $wpdb->update($wpdb->prefix .'icl_translation_status', array('needs_update' => 1), array('translation_id' => $translation->translation_id));
                        }
                    }
                    $_suffix = str_replace('-', '_', $lang);
                    $index = 'status_' . $_suffix;
                    $new_item->$index = $res->status;
                    $index = 'needs_update_' . $_suffix;
                    $new_item->$index = $res->needs_update;
                }
            }

            $items[] = $new_item;

        }

        return $items;
    }

    public static function get_translatable_item($item, $id) {
        if ($item != null)
            return $item;
        
        $parts = explode('_', $id);
        if ($parts[0] != 'external')
            return $item;
        
        $id = array_pop($parts);

        unset($parts[0]);

        $type = implode('_', $parts);

        // this is not ours.
        if ($type != 'formidable')
            return $item;

		$form = FrmForm::getOne( $id );

        $item = new stdClass();

        $item->external_type = true;
        $item->type = 'formidable';
        $item->id = $form->id;
        $item->ID = $form->id;
        $item->post_type = 'formidable';
        $item->post_id = 'external_' . $item->post_type . '_' . $item->id;
        $item->post_date = $form->created_at;
		$item->post_status = ( $form->status == 'draft' ) ? __( 'Inactive', 'frmwpml' ) : __( 'Active', 'frmwpml' );
        $item->post_title = $form->name;
		$item->is_translation = false;

		$item->string_data = self::_get_form_strings( $form );

        return $item;

    }
	
    public static function get_link($item, $id, $anchor, $hide_empty) {
        if ($item != '')
            return $item;
        
        $parts = explode('_', $id);
        if ($parts[0] != 'external')
            return $item;
            
        $id = array_pop($parts);

        unset($parts[0]);

        $type = implode('_', $parts);

        if ($type != 'formidable')
            return $item; // this is ours.

		if ( false === $anchor ) {
			$form = FrmForm::getOne( $id );
						
			if(!$form)
                return $item;
						
			$anchor = stripslashes($form->name);
		}

		$item = sprintf( '<a href="%s">%s</a>', 'admin.php?page=formidable&frm_action=translate&id=' . $id, $anchor );

        return $item;
    }

	public static function set_ajax_language( $url ) {
		global $sitepress;
		if ( is_object( $sitepress ) ) {
			$url = add_query_arg( array( 'lang' => $sitepress->get_current_language() ), $url );
		}
		return $url;
	}

    public static function add_translate_button($values){
?>
<a href="<?php echo esc_url( admin_url( 'admin.php?page=formidable&frm_action=translate&id=' . $values['id'] ) ) ?>" class="button-secondary"><?php _e( 'Translate Form', 'formidable' ) ?></a>
<?php
    }
	
	public static function translate($message = '') {
        if(!function_exists('icl_t')){
            _e('You do not have WPML installed', 'formidable');
            return;
        }
        
        global $wpdb, $sitepress, $sitepress_settings;
        
		$id = FrmAppHelper::get_param( 'id', false, 'get', 'absint' );

		$form = FrmForm::getOne( $id );
		$fields_array = FrmField::getAll( array( 'fi.form_id' => $id ), 'field_order' );

		$fields = $form_ids = array();
		foreach ( $fields_array as $f ) {
			$fields[ $f->id ] = $f;
			$form_ids[ $f->form_id ] = absint( $f->form_id );
			unset( $f );
		}
		unset( $fields_array );

        $langs = $sitepress->get_active_languages();
		$default_language = self::get_string_language();
		ksort($langs);

		$col_order = array( $default_language );
		foreach ( $langs as $lang ) {
            if ( $lang['code'] == $default_language ) {
                continue;
            }

            $col_order[] = $lang['code'];
		}

        $lang_count = (count($langs)-1);
        
        self::get_translatable_items(array(), 'formidable', '');

		$query_args = array( 'formidable' );
		$like = '';
		foreach ( $form_ids as $form_id ) {
			if ( ! empty( $like ) ) {
				$like .= ' OR ';
			}
			$like .= 'name LIKE %s';
			// if this is a child form, only get the field values
			$query_args[] = $form_id . ( $form_id == $id ? '' : '_field-' ) . '_%';
		}

        $strings = $wpdb->get_results( $wpdb->prepare( "SELECT id, name, value, language FROM {$wpdb->prefix}icl_strings WHERE context=%s AND (" . $like . ") ORDER BY name DESC", $query_args ), OBJECT_K
        );

        if($strings){
            $translations = $wpdb->get_results("SELECT id, string_id, value, status, language 
                FROM {$wpdb->prefix}icl_string_translations WHERE string_id in (". implode(',', array_keys($strings)).") 
                ORDER BY language ASC"
            );
        }

		$path = method_exists('FrmAppHelper', 'plugin_path') ? FrmAppHelper::plugin_path() : FRM_PATH;

		include( dirname(__FILE__) .'/translate.php' );
    }

	public static function get_string_language() {
		$string_version = defined('WPML_ST_VERSION') ? WPML_ST_VERSION : 1;
		if ( version_compare( $string_version, '2.2.5', '>' ) ) {
			$default_language = 'en';
		} else {
			global $sitepress, $sitepress_settings;
			$default_language = ! empty( $sitepress_settings['st']['strings_language'] ) ? $sitepress_settings['st']['strings_language'] : $sitepress->get_default_language();
		}

		return $default_language;
	}

	public static function maybe_register_string( $string, $atts ) {
		if ( is_array( $string->value ) ) {
			return $string->value;
		}

		$new_val = false;
		if ( strpos( $string->name, $atts['id'] .'_field-' ) === 0 ) {
			$fid = explode( '-', str_replace( $atts['id'] . '_field-', '', $string->name ), 2 );
			$fields = $atts['fields'];

			if ( isset( $fields[ $fid[0] ]->{$fid[1]} ) && $string->value != $fields[ $fid[0] ]->{$fid[1]} ) {
				$string->value = $fields[ $fid[0] ]->{$fid[1]};
				$new_val = true;
			} else if ( isset( $fields[ $fid[0] ]->field_options[ $fid[1] ] ) && $string->value != $fields[ $fid[0] ]->field_options[ $fid[1] ] ) {
				$string->value = $fields[ $fid[0] ]->field_options[ $fid[1] ];
				$new_val = true;
			}
		} else {
			$form_option_name = str_replace( $atts['id'] . '_', '', $string->name );

			if ( isset( $atts['form']->{$form_option_name} ) && $string->value != $atts['form']->{$form_option_name} ) {
				$string->value = $atts['form']->{$form_option_name};
				$new_val = true;
			} else if ( isset( $atts['form']->options[ $form_option_name ] ) && $string->value != $atts['form']->options[ $form_option_name ] ) {
				$string->value = $atts['form']->options[ $form_option_name ];
				$new_val = true;
			}
		}

		if ( $new_val && $string->value != '' && ! is_array( $string->value ) ) {
			$str_name = ( function_exists( 'mb_substr' ) ) ? mb_substr( $string->name, 0, 160 ) : substr( $string->name, 0, 160 );
			icl_register_string( 'formidable', $str_name, $string->value );
		}

		return $string->value;
	}

    public static function update_translate(){
        if ( ! isset($_POST['frm_wpml']) || ! is_array($_POST['frm_wpml']) ) {
            self::translate();
            return;
        }
        
        global $wpdb;
            
        if ( ! isset($_POST['frm_translate_form']) || ! wp_verify_nonce($_POST['frm_translate_form'], 'frm_translate_form_nonce') ) {
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
            
        foreach($_POST['frm_wpml'] as $tkey => $t){
            $st = array('value' => $t['value']);
            $st['status'] = (isset($t['status'])) ? $t['status'] : ICL_STRING_TRANSLATION_NOT_TRANSLATED;
            
            if(is_numeric($tkey)){
                $wpdb->update($wpdb->prefix .'icl_string_translations', $st, array('id' => $tkey));
            }else if(!empty($t['value'])){
                $info = explode('_', $tkey);
                if(!is_numeric($info[0]))
                    continue;
                    
                $st['string_id'] = $info[0];
                $st['language']  = $info[1];
                $st['translator_id'] = get_current_user_id();
                $st['translation_date'] = current_time('mysql');

                $wpdb->insert($wpdb->prefix .'icl_string_translations', $st);
            }
            unset($t);
            unset($tkey);
        }
        
        $message = __('Settings Successfully Updated', 'formidable');
        self::translate($message);
    }
    
    public static function translated(){
        //don't continue an other action
        return true;
    }
}
