<?php

class FrmFormAction {

	public $id_base;			// Root id for all actions of this type.
	public $name;				// Name for this action type.
	public $option_name;
	public $action_options;     // Option array passed to wp_register_sidebar_widget()
	public $control_options;	// Option array passed to wp_register_widget_control()

    public $form_id;        // The ID of the form to evaluate
	public $number = false;	// Unique ID number of the current instance.
	public $id = '';		// Unique ID string of the current instance (id_base-number)
	public $updated = false;	// Set true when we update the data after a POST submit - makes sure we don't do it twice.

	// Member functions that you must over-ride.

	/**
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	public function update($new_instance, $old_instance) {
		return $new_instance;
	}

	/**
     * Echo the settings update form
	 *
	 * @param array $instance Current settings
	 */
	public function form($instance, $args = array()) {
		echo '<p class="no-options-widget">' . esc_html__( 'There are no options for this action.', 'formidable' ) . '</p>';
		return 'noform';
	}

	/**
	 * @return array of the default options
	 */
	public function get_defaults() {
	    return array();
	}

	public function get_switch_fields() {
	    return array();
	}

	public function migrate_values($action, $form) {
	    return $action;
	}

	// Functions you'll need to call.

	/**
	 * PHP5 constructor
	 *
	 * @param string $id_base Optional Base ID for the widget, lower case,
	 * if left empty a portion of the widget's class name will be used. Has to be unique.
	 * @param string $name Name for the widget displayed on the configuration page.
	 * @param array $action_options Optional Passed to wp_register_sidebar_widget()
	 *	 - description: shown on the configuration page
	 *	 - classname
	 * @param array $control_options Optional Passed to wp_register_widget_control()
	 *	 - width: required if more than 250px
	 *	 - height: currently not used but may be needed in the future
	 */
	public function __construct( $id_base, $name, $action_options = array(), $control_options = array() ) {
	    if ( ! defined('ABSPATH') ) {
            die('You are not allowed to call this page directly.');
        }

		$this->id_base = strtolower($id_base);
		$this->name = $name;
		$this->option_name = 'frm_' . $this->id_base .'_action';

        $default_options = array(
            'classes'   => '',
            'active'    => true,
			'event'     => array( 'create' ),
            'limit'     => 1,
            'force_event' => false,
            'priority'  => 20,
            'ajax_load' => true,
            'tooltip'   => $name,
        );

		$this->action_options = wp_parse_args( $action_options, $default_options );
		$this->control_options = wp_parse_args( $control_options, array( 'id_base' => $this->id_base ) );
	}

	/**
	 * @param string $id_base
	 */
	public function FrmFormAction( $id_base, $name, $action_options = array(), $control_options = array() ) {
		FrmFormAction::__construct( $id_base, $name, $action_options, $control_options );
	}

	/**
	 * Constructs name attributes for use in form() fields
	 *
	 * This function should be used in form() methods to create name attributes for fields to be saved by update()
	 *
	 * @param string $field_name Field name
	 * @return string Name attribute for $field_name
	 */
	public function get_field_name($field_name, $post_field = 'post_content') {
		return $this->option_name . '[' . $this->number . ']'. ( empty($post_field) ? '' : '['. $post_field .']' ) .'[' . $field_name . ']';
	}

	/**
	 * Constructs id attributes for use in form() fields
	 *
	 * This function should be used in form() methods to create id attributes for fields to be saved by update()
	 *
	 * @param string $field_name Field name
	 * @return string ID attribute for $field_name
	 */
	public function get_field_id($field_name) {
		return $field_name .'_'. $this->number;
	}

	// Private Function. Don't worry about this.

	public function _set($number) {
		$this->number = $number;
		$this->id = $this->id_base . '-' . $number;
	}

    public function prepare_new($form_id = false) {
        if ( $form_id ) {
            $this->form_id = $form_id;
        }

        $post_content = array();
        $default_values = $this->get_global_defaults();

        // fill default values
        $post_content = wp_parse_args( $post_content, $default_values);

        if ( ! isset($post_content['event']) && ! $this->action_options['force_event'] ) {
            $post_content['event'] = array( reset($this->action_options['event']) );
        }

        $form_action = array(
            'post_title'    => $this->name,
            'post_content'  => $post_content,
            'post_excerpt'  => $this->id_base,
            'ID'            => '',
            'post_status'   => 'publish',
            'post_type'     => FrmFormActionsController::$action_post_type,
            'post_name'     => $this->form_id .'_'. $this->id_base .'_'. $this->number,
            'menu_order'    => $this->form_id,
        );
        unset($post_content);

        return (object) $form_action;
    }

    public function create($form_id) {
        $this->form_id = $form_id;

        $action = $this->prepare_new();

        return $this->save_settings($action);
    }

    public function duplicate_form_actions($form_id, $old_id) {
        if ( $form_id == $old_id ) {
            // don't duplicate the actions if this is a template getting updated
            return;
        }

        $this->form_id = $old_id;
        $actions = $this->get_all();

        $this->form_id = $form_id;
        foreach ( $actions as $action ) {
            $this->duplicate_one($action, $form_id);
            unset($action);
        }
    }

    /* Check if imported action should be created or updated
    *
    * Since 2.0
    *
    * @param array $action
    * @return integer $post_id
    */
    public function maybe_create_action( $action, $forms ) {
		if ( isset( $action['ID'] ) && is_numeric( $action['ID'] ) && $forms[ $action['menu_order'] ] == 'updated' ) {
            // Update action only
            $action['post_content'] = FrmAppHelper::maybe_json_decode( $action['post_content'] );
            $post_id = $this->save_settings( $action );
        } else {
            // Create action
            $action['post_content'] = FrmAppHelper::maybe_json_decode($action['post_content']);
            $post_id = $this->duplicate_one( (object) $action, $action['menu_order']);
        }
        return $post_id;
    }

    public function duplicate_one($action, $form_id) {
        global $frm_duplicate_ids;

        $action->menu_order = $form_id;
        $switch = $this->get_global_switch_fields();
        foreach ( (array) $action->post_content as $key => $val ) {
			if ( is_numeric( $val ) && isset( $frm_duplicate_ids[ $val ] ) ) {
				$action->post_content[ $key ] = $frm_duplicate_ids[ $val ];
            } else if ( ! is_array( $val ) ) {
				$action->post_content[ $key ] = FrmFieldsHelper::switch_field_ids( $val );
			} else if ( isset( $switch[ $key ] ) && is_array( $switch[ $key ] ) ) {
                // loop through each value if empty
				if ( empty( $switch[ $key ] ) ) {
					$switch[ $key ] = array_keys( $val );
				}

				foreach ( $switch[ $key ] as $subkey ) {
					$action->post_content[ $key ] = $this->duplicate_array_walk( $action->post_content[ $key ], $subkey, $val );
                }
            }

            unset($key, $val);
        }
        unset($action->ID);

        return $this->save_settings($action);
    }

    private function duplicate_array_walk($action, $subkey, $val) {
        global $frm_duplicate_ids;

        if ( is_array($subkey) ) {
            foreach ( $subkey as $subkey2 ) {
                foreach ( (array) $val as $ck => $cv ) {
                    if ( is_array($cv) ) {
						$action[ $ck ] = $this->duplicate_array_walk( $action[ $ck ], $subkey2, $cv );
					} else if ( isset( $cv[ $subkey ] ) && is_numeric( $cv[ $subkey ] ) && isset( $frm_duplicate_ids[ $cv[ $subkey ] ] ) ) {
						$action[ $ck ][ $subkey ] = $frm_duplicate_ids[ $cv[ $subkey ] ];
                    }
                }
            }
        } else {
            foreach ( (array) $val as $ck => $cv ) {
                if ( is_array($cv) ) {
					$action[ $ck ] = $this->duplicate_array_walk( $action[ $ck ], $subkey, $cv );
				} else if ( $ck == $subkey && isset( $frm_duplicate_ids[ $cv ] ) ) {
					$action[ $ck ] = $frm_duplicate_ids[ $cv ];
                }
            }
        }

        return $action;
    }

	/**
	 * Deal with changed settings.
	 *
	 * Do NOT over-ride this function
	 *
	 */
 	public function update_callback( $form_id ) {
        $this->form_id = $form_id;

 		$all_instances = $this->get_settings();

 		// We need to update the data
 		if ( $this->updated ) {
 			return;
 		}

		if ( isset( $_POST[ $this->option_name ] ) && is_array( $_POST[ $this->option_name ] ) ) {
			$settings = $_POST[ $this->option_name ];
 		} else {
 			return;
 		}

        $action_ids = array();

 		foreach ( $settings as $number => $new_instance ) {
 			$this->_set($number);

 			if ( ! isset($new_instance['post_title']) ) {
 			    // settings were never opened, so don't update
 			    $action_ids[] = $new_instance['ID'];
         		$this->updated = true;
         		continue;
 			}

			$old_instance = isset( $all_instances[ $number ] ) ? $all_instances[ $number ] : array();

 			$new_instance['post_type']  = FrmFormActionsController::$action_post_type;
            $new_instance['post_name']    = $this->form_id .'_'. $this->id_base .'_'. $this->number;
            $new_instance['menu_order']   = $this->form_id;
            $new_instance['post_status']  = 'publish';
            $new_instance['post_date'] = isset( $old_instance->post_date ) ? $old_instance->post_date : '';

 			$instance = $this->update( $new_instance, $old_instance );

			/**
			 * Filter an action's settings before saving.
			 *
			 * Returning false will effectively short-circuit the widget's ability
			 * to update settings.
			 *
			 * @since 2.0
			 *
			 * @param array     $instance     The current widget instance's settings.
			 * @param array     $new_instance Array of new widget settings.
			 * @param array     $old_instance Array of old widget settings.
			 * @param WP_Widget $this         The current widget instance.
			 */
			$instance = apply_filters( 'frm_action_update_callback', $instance, $new_instance, $old_instance, $this );

			$instance['post_content'] = apply_filters('frm_before_save_action', $instance['post_content'], $instance, $new_instance, $old_instance, $this);
            $instance['post_content'] = apply_filters('frm_before_save_'. $this->id_base .'_action', $new_instance['post_content'], $instance, $new_instance, $old_instance, $this);

			if ( false !== $instance ) {
				$all_instances[ $number ] = $instance;
			}

            $action_ids[] = $this->save_settings($instance);

     		$this->updated = true;
 		}

 		return $action_ids;
 	}

	public function save_settings($settings) {
		return FrmAppHelper::save_settings( $settings, 'frm_actions' );
	}

	public function get_single_action( $id ) {
	    $action = get_post($id);
	    $action = $this->prepare_action($action);
	    $this->_set($id);
	    return $action;
	}

	public function get_one( $form_id ) {
	    return $this->get_all($form_id, 1);
	}

    public static function get_action_for_form( $form_id, $type = 'all', $limit = 99 ) {
        $action_controls = FrmFormActionsController::get_form_actions( $type );
        if ( empty($action_controls) ) {
            // don't continue if there are no available actions
            return array();
        }

        if ( 'all' != $type ) {
            return $action_controls->get_all( $form_id, $limit );
        }

		$args = self::action_args( $form_id, $limit );
		$actions = FrmAppHelper::check_cache( serialize( $args ), 'frm_actions', $args, 'get_posts' );

        if ( ! $actions ) {
            return array();
        }

        $settings = array();
        foreach ( $actions as $action ) {
			// some plugins/themes are formatting the post_excerpt
			$action->post_excerpt = sanitize_title( $action->post_excerpt );

			if ( ! isset( $action_controls[ $action->post_excerpt ] ) ) {
                continue;
            }

            $action = $action_controls[ $action->post_excerpt ]->prepare_action( $action );
			$settings[ $action->ID ] = $action;

			if ( count( $settings ) >= $limit ) {
				break;
			}
        }

        if ( 1 === $limit ) {
            $settings = reset($settings);
        }

        return $settings;
    }

	public function get_all( $form_id = false, $limit = 99 ) {
	    if ( $form_id ) {
	        $this->form_id = $form_id;
	    }

	    $type = $this->id_base;

	    global $frm_vars;
	    $frm_vars['action_type'] = $type;

	    add_filter( 'posts_where' , 'FrmFormActionsController::limit_by_type' );
		$query = self::action_args( $form_id, $limit );
        $query['post_status']      = 'any';
        $query['suppress_filters'] = false;

		$actions = FrmAppHelper::check_cache( serialize( $query ) . '_type_' . $type, 'frm_actions', $query, 'get_posts' );
        unset($query);

        remove_filter( 'posts_where' , 'FrmFormActionsController::limit_by_type' );

        if ( empty($actions) ) {
            return array();
        }

        $settings = array();
        foreach ( $actions as $action ) {
            if ( count($settings) >= $limit ) {
                continue;
            }

            $action = $this->prepare_action($action);

			$settings[ $action->ID ] = $action;
        }

        if ( 1 === $limit ) {
            $settings = reset($settings);
        }

        return $settings;
	}

	public static function action_args( $form_id = 0, $limit = 99 ) {
		$args = array(
			'post_type'   => FrmFormActionsController::$action_post_type,
			'post_status' => 'publish',
			'numberposts' => $limit,
			'orderby'     => 'title',
			'order'       => 'ASC',
		);

		if ( $form_id && $form_id != 'all' ) {
			$args['menu_order'] = $form_id;
		}

		return $args;
	}

	public function prepare_action($action) {
	    $action->post_content = FrmAppHelper::maybe_json_decode($action->post_content);
		$action->post_excerpt = sanitize_title( $action->post_excerpt );

        $default_values = $this->get_global_defaults();

        // fill default values
        $action->post_content = wp_parse_args( $action->post_content, $default_values);

        foreach ( $default_values as $k => $vals ) {
            if ( is_array($vals) && ! empty($vals) ) {
				if ( 'event' == $k && ! $this->action_options['force_event'] && ! empty( $action->post_content[ $k ] ) ) {
                    continue;
                }
				$action->post_content[ $k ] = wp_parse_args( $action->post_content[ $k ], $vals );
            }
        }

        if ( ! is_array($action->post_content['event']) ) {
            $action->post_content['event'] = explode(',', $action->post_content['event']);
        }

        return $action;
	}

	public function destroy($form_id = false, $type = 'default') {
	    global $wpdb;

	    $this->form_id = $form_id;

	    $query = array( 'post_type' => FrmFormActionsController::$action_post_type );
	    if ( $form_id ) {
	        $query['menu_order'] = $form_id;
	    }
	    if ( 'all' != $type ) {
	        $query['post_excerpt'] = $this->id_base;
	    }

        $post_ids = FrmDb::get_col( $wpdb->posts, $query, 'ID' );

        foreach ( $post_ids as $id ) {
            wp_delete_post($id);
        }
		self::clear_cache();
	}

	/**
	 * Delete the action cache when a form action is created, deleted, or updated
	 *
	 * @since 2.0.5
	 */
	public static function clear_cache( ) {
		FrmAppHelper::cache_delete_group( 'frm_actions' );
	}

	public function get_settings() {
		return self::get_action_for_form( $this->form_id, $this->id_base );
	}

	public function get_global_defaults() {
	    $defaults = $this->get_defaults();

	    if ( ! isset($defaults['event']) ) {
			$defaults['event'] = array( 'create' );
	    }

	    if ( ! isset($defaults['conditions']) ) {
	        $defaults['conditions'] = array(
                'send_stop' => '',
                'any_all'   => '',
            );
        }

        return $defaults;
	}

	public function get_global_switch_fields() {
	    $switch = $this->get_switch_fields();
		$switch['conditions'] = array( 'hide_field' );
	    return $switch;
	}

	/**
	 * Migrate settings from form->options into new action.
	 */
	public function migrate_to_2($form, $update = 'update') {
        $action = $this->prepare_new($form->id);
        $form->options = maybe_unserialize($form->options);

        // fill with existing options
        foreach ( $action->post_content as $name => $val ) {
			if ( isset( $form->options[ $name ] ) ) {
				$action->post_content[ $name ] = $form->options[ $name ];
				unset( $form->options[ $name ] );
            }
        }

        $action = $this->migrate_values($action, $form);

        // check if action already exists
        $post_id = get_posts( array(
            'name'          => $action->post_name,
            'post_type'     => FrmFormActionsController::$action_post_type,
            'post_status'   => $action->post_status,
            'numberposts'   => 1,
        ) );

        if ( empty($post_id) ) {
            // create action now
            $post_id = $this->save_settings($action);
        }

        if ( $post_id && 'update' == $update ) {
            global $wpdb;
            $form->options = maybe_serialize($form->options);

            // update form options
			$wpdb->update( $wpdb->prefix . 'frm_forms', array( 'options' => $form->options ), array( 'id' => $form->id ) );
            wp_cache_delete( $form->id, 'frm_form');
        }

        return $post_id;
    }

	public static function action_conditions_met( $action, $entry ) {
		$notification = $action->post_content;
		$stop = false;
		$met = array();

		if ( ! isset( $notification['conditions'] ) || empty( $notification['conditions'] ) ) {
			return $stop;
		}

		foreach ( $notification['conditions'] as $k => $condition ) {
			if ( ! is_numeric( $k ) ) {
				continue;
			}

			if ( $stop && 'any' == $notification['conditions']['any_all'] && 'stop' == $notification['conditions']['send_stop'] ) {
				continue;
			}

			if ( is_array($condition['hide_opt']) ) {
				$condition['hide_opt'] = reset($condition['hide_opt']);
			}

			$observed_value = isset( $entry->metas[ $condition['hide_field'] ] ) ? $entry->metas[ $condition['hide_field'] ] : '';
			if ( $condition['hide_opt'] == 'current_user' ) {
				$condition['hide_opt'] = get_current_user_id();
			}

			$stop = FrmFieldsHelper::value_meets_condition($observed_value, $condition['hide_field_cond'], $condition['hide_opt']);

			if ( $notification['conditions']['send_stop'] == 'send' ) {
				$stop = $stop ? false : true;
			}

			$met[ $stop ] = $stop;
		}

		if ( $notification['conditions']['any_all'] == 'all' && ! empty( $met ) && isset( $met[0] ) && isset( $met[1] ) ) {
			$stop = ($notification['conditions']['send_stop'] == 'send');
		} else if ( $notification['conditions']['any_all'] == 'any' && $notification['conditions']['send_stop'] == 'send' && isset($met[0]) ) {
			$stop = false;
		}

		return $stop;
	}

	public static function default_action_opts( $class = '' ) {
		return array(
			'classes'   => 'frm_icon_font ' . $class,
			'active'    => false,
			'limit'     => 0,
		);
	}
}