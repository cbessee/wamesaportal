<?php
/**
 * @package wpDataTables
 * @version 1.4.1
 */
/*
Plugin Name: wpDataTables
Plugin URI: http://codecanyon.net/user/cjbug
Description: Add interactive tables easily from any input source
Version: 1.4.1
Author: cjbug
Author URI: http://codecanyon.net/user/cjbug
*/
?>
<?php

	define('PDT_ROOT_PATH', plugin_dir_path(__FILE__)); // full path to the wpDataTables root directory
	define('PDT_ROOT_URL', plugin_dir_url(__FILE__)); // URL of wpDataTables plugin

    require_once(PDT_ROOT_PATH.'/config.inc');
    
	function wpdatatables_load(){
	    require_once(PDT_ROOT_PATH.'source/class.tpl.php');
	    if(is_admin()){
	    	require_once(PDT_ROOT_PATH.'wdt_admin.php');
	    }
	    require_once(PDT_ROOT_PATH.'source/class.sql.php');
	    require_once(PDT_ROOT_PATH.'source/class.table.php');
	    require_once(PDT_ROOT_PATH.'source/class.row.php');
	    require_once(PDT_ROOT_PATH.'source/class.column.php');
	    require_once(PDT_ROOT_PATH.'source/class.cell.php');
	}
	
	/**
	 * The installation/activation method, installs the plugin table
	 */
	function wpdatatables_activation(){
		global $wpdb;
		$tables_table_name = $wpdb->prefix .'wpdatatables';
		$tables_sql = "CREATE TABLE {$tables_table_name} (
						id INT( 11 ) NOT NULL AUTO_INCREMENT,
						title varchar(255) NOT NULL,
						table_type enum('mysql','xml','json','csv','xls','serialized') NOT NULL,
						content text NOT NULL,
						filtering tinyint(1) NOT NULL default '1',
						sorting tinyint(1) NOT NULL default '1',
						tools tinyint(1) NOT NULL default '1',
						server_side tinyint(1) NOT NULL default '0',
						editable tinyint(1) NOT NULL default '0',
						mysql_table_name varchar(255) NOT NULL default '',
						display_length int(3) NOT NULL default '10',
						fixed_columns tinyint(1) NOT NULL default '-1',
						chart enum('none','area','bar','column','line','pie') NOT NULL,
						chart_title varchar(255) NOT NULL,
						fixed_layout tinyint(1) NOT NULL default '0',
						responsive tinyint(1) NOT NULL default '0',
						word_wrap tinyint(1) NOT NULL default '0',
						UNIQUE KEY id (id)
						)";
		$columns_table_name = $wpdb->prefix.'wpdatatables_columns';
		$columns_sql = "CREATE TABLE {$columns_table_name} (
						id INT( 11 ) NOT NULL AUTO_INCREMENT,
						table_id int(11) NOT NULL,
						orig_header varchar(255) NOT NULL,
						display_header varchar(255) NOT NULL,
						filter_type enum('null','text','number','number-range','date-range','select','checkbox') NOT NULL,
						column_type enum('autodetect','string','int','float','date','link','email','image') NOT NULL,
						input_type enum('text','textarea','date','link','email','selectbox','multi-selectbox','attachment') NOT NULL default 'text',
						id_column tinyint(1) NOT NULL default '0',
						group_column tinyint(1) NOT NULL default '0',
						sort_column tinyint(1) NOT NULL default '0',
						hide_on_phones tinyint(1) NOT NULL default '0',
						hide_on_tablets tinyint(1) NOT NULL default '0',
						use_in_chart tinyint(1) NOT NULL default '0',
						chart_horiz_axis tinyint(1) NOT NULL default '0',
						visible tinyint(1) NOT NULL default '1',
						width VARCHAR( 4 ) NOT NULL default '',
						possible_values VARCHAR(700) NOT NULL default '',
						pos tinyint(1) NOT NULL default '0',
						UNIQUE KEY id (id)
						)";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($tables_sql);		
		dbDelta($columns_sql);		
		update_option('wdtUseSeparateCon', false);
		update_option('wdtMySqlHost', '');
		update_option('wdtMySqlDB', '');
		update_option('wdtMySqlUser', '');
		update_option('wdtMySqlPwd', '');
		update_option('wdtRenderCharts', 'below');
		update_option('wdtRenderFilter', 'footer');
		update_option('wdtTopOffset', '0');
		update_option('wdtLeftOffset', '0');
		update_option('wdtDateFormat', 'd/m/Y');
		update_option('wdtInterfaceLanguage', '');
	}
	
	function wpdatatables_deactivation(){
	}
	
	/**
	 * Uninstall hook
	 */
	function wpdatatables_uninstall(){
		global $wpdb;
		
		delete_option('wdtUseSeparateCon');
		delete_option('wdtMySqlHost');
		delete_option('wdtMySqlDB');
		delete_option('wdtMySqlUser');
		delete_option('wdtMySqlPwd');
		delete_option('wdtRenderCharts');
		delete_option('wdtTopOffset');
		delete_option('wdtLeftOffset');
		delete_option('wdtDateFormat');
		delete_option('wdtInterfaceLanguage');
		
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpdatatables");
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpdatatables_columns");
	}	
	
	// Make sure we don't expose any info if called directly
	if ( !function_exists( 'add_action' ) ) {
		echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
		exit;
	}
	
	/**
	 * Helper method which gets the columns from DB
	 * for a provided table ID
	 */
	 function wdt_get_columns_by_table_id( $table_id ) {
	 	global $wpdb;
		// get the columns from DB
		$query = 'SELECT *
					FROM '.$wpdb->prefix.'wpdatatables_columns
				WHERE table_id='.$table_id.'
				ORDER BY pos';
		$columns = $wpdb->get_results( $query );
		return $columns;
	 }	
	 
	 /**
	  * Helper function which returns all data for a table
	  */
	  function wdt_get_table_by_id( $table_id ){
	  	global $wpdb;
	  	$query = "SELECT * 
	  				FROM {$wpdb->prefix}wpdatatables
	  				WHERE id={$table_id}";
	  	$data = $wpdb->get_row( $query, ARRAY_A );
	  	$data['content'] = stripslashes($data['content']);
	  	return $data;
	  }
	  
	  /**
	   * Helper func that prints out the table
	   */
	  function wdt_output_table( $id, $no_scripts = 1 ) {
	  	global $wp_scripts;
	  	echo wpdatatable_shortcode_handler( array('id'=>$id, 'no_scripts' => $no_scripts ) );
	  }
	  
	  /**
	   * Handler for the shortcode
	   */
	  function wpdatatable_shortcode_handler( $atts, $content = null ) {
		global $wpdb;
		   extract( shortcode_atts( array(
		      'id' => '0',
		      'show_only_chart' => false,
		      'no_scripts' => 0
		      ), $atts ) );
		   // protection
		   if(!$id){ return false; }
		   $table_data = wdt_get_table_by_id( $id );
		   $column_data = wdt_get_columns_by_table_id( $id );
		   // preparing column properties
		   $column_order = array();
		   $column_headers = array();
		   $column_widths = array();
		   $column_types = array();
		   $column_possible_values = array();
		   foreach($column_data as $column){
		   		$column_order[(int)$column->pos] = $column->orig_header;
		   		if($column->display_header){
			   		$column_headers[$column->orig_header] = $column->display_header;
		   		}
		   		if($column->width){
					$column_widths[$column->orig_header] = $column->width;
		   		}
		   		if($column->column_type != 'autodetect'){
			   		$column_types[$column->orig_header] = $column->column_type;
		   		}
			   $column_possible_values[$column->orig_header] = $column->possible_values;
		   }
		   $tbl = new PHPDataTable();
		   $tbl->setWpId( $id );
		   switch($table_data['table_type']){
		   		case 'mysql' : 
	   				if($table_data['server_side']){
	   					$tbl->enableServerProcessing();
	   				}
	   				if($table_data['editable']){
	   					$tbl->enableEditing();
	   				}
		   			$tbl->buildByQuery($table_data['content'], array(),
		   				array(
		   					'data_types'=>$column_types,
		   					'column_names'=>$column_headers
		   					)
	   					);
		   			break;
		   		case 'xls':
		   		case 'csv':
		   			$tbl->buildByExcel($table_data['content'], 
		   				array(
		   					'data_types'=>$column_types,
		   					'column_names'=>$column_headers
		   					)
		   			);
		   			break;
		   		case 'xml':
		   			$tbl->buildByXML($table_data['content'], 
		   				array(
		   					'data_types'=>$column_types,
		   					'column_names'=>$column_headers
		   					)
		   			);
		   			break;
		   		case 'json':
		   			$tbl->buildByJSON($table_data['content'], 
		   				array(
		   					'data_types'=>$column_types,
		   					'column_names'=>$column_headers
		   					)
		   			);
		   			break;
		   		case 'serialized':
					$array = unserialize( file_get_contents ( $table_data['content'] ) );
		   			$tbl->buildByArray( $array, 
		   				array(
		   					'data_types'=>$column_types,
		   					'column_names'=>$column_headers
		   					)
		   			);
		   			break;
		   }
		   if(!$tbl->getNoData()){
			   $tbl->reorderColumns( $column_order );
			   $tbl->setColumnsWidth( $column_widths );
			   $tbl->setColumnsPossibleValues( $column_possible_values );
		   }
		   // Applying responsiveness
		   if($table_data['responsive']){
		   		$tbl->setResponsive(true);
		   }
		   // Applying filter, if enabled
		   if($table_data['filtering']){
		   		$tbl->enableAdvancedFilter();
		   }
		    if(!$no_scripts){
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-widget');
				wp_enqueue_script('jquery-ui-dialog');
				wp_enqueue_script('jquery-ui-progressbar');
				wp_enqueue_script('jquery-ui-datepicker');
				wp_register_style( 'jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/smoothness/jquery-ui.css', true);
				wp_enqueue_style('jquery-ui-style');
		    }else{
		    	$tbl->disableScripts();
		    }
		   if(!$tbl->getNoData()){
			   foreach($column_data as $column){
				    // set filter types
			   		$tbl->getColumn($column->orig_header)->setFilterType($column->filter_type);
			   		// set visibility
			   		if(!$column->visible){
			   			$tbl->getColumn($column->orig_header)->hide();
					}
					// Set hiding on phones and tablets for responsiveness
					if($tbl->isResponsive()){
						if($column->hide_on_phones){
							$tbl->getColumn($column->orig_header)->hideOnPhones();
						}
						if($column->hide_on_tablets){
							$tbl->getColumn($column->orig_header)->hideOnTablets();
						}
					}
					// if grouping enabled for this column, passing it to table class
					if($column->group_column){
						$tbl->groupByColumn($column->orig_header);
					}
					if($column->sort_column !== '0'){
						$tbl->setDefaultSortColumn($column->orig_header);
						if($column->sort_column == '1'){
							$tbl->setDefaultSortDirection('ASC');
						}elseif($column->sort_column == '2'){
							$tbl->setDefaultSortDirection('DESC');
						}
					}
	  			    if($table_data['chart']!='none'){
				   		if($column->use_in_chart){
				   			$tbl->addChartSeries($column->orig_header);
				   		}
				   		if($column->chart_horiz_axis){
				   			$tbl->setChartHorizontalAxis($column->orig_header);
						}
	 			    }
	 			    // Set ID column if specified
	 			    if($column->id_column){
	 			    	$tbl->setIdColumnKey($column->orig_header);
	 			    }
	 			    // Set front-end editor input type
	 			    $tbl->getColumn($column->orig_header)->setInputType($column->input_type);
			   }
	  		}
		   $output_str = '';
		   if(!$show_only_chart){
			   if($table_data['title']){
				   $output_str .= '<h2>'.$table_data['title'].'</h2>';
			   }
			   if(!$table_data['sorting']){
			   		$tbl->disableSorting();
			   }
			   if(!$table_data['tools']){
			   		$tbl->disableTableTools();
			   }
			   // display length
			   if($table_data['display_length'] != 0) {
			   	$tbl->setDisplayLength($table_data['display_length']);
			   } else {
			   	$tbl->disablePagination();
			   }
			   // fixed columns
			   if($table_data['fixed_columns']==='0'){
			   		$tbl->fixHeaders();
			   }elseif((int)$table_data['fixed_columns'] > 0){
			   		$tbl->fixColumns((int)$table_data['fixed_columns']);
			   }
			   $tbl->setLeftOffset(get_option('wdtLeftOffset'));
			   $tbl->setTopOffset(get_option('wdtTopOffset'));
			   
			   if(get_option('wdtInterfaceLanguage') != ''){
					$tbl->setInterfaceLanguage(get_option('wdtInterfaceLanguage'));
			   }
			   
			   $output_str .= $tbl->renderTable();
		   }

		   if($table_data['chart'] != 'none') {
		   		$tbl->setChartType(ucfirst($table_data['chart']));
		   		$tbl->setChartTitle($table_data['chart_title']);
		   		$tbl->printChart('wdt_'.$tbl->getId().'_chart');
		   		if(get_option('wdtRenderCharts')=='above'){
		   			$output_str = '<div id="wdt_'.$tbl->getId().'_chart" class="wdt_chart"></div>'.$output_str;
		   		}else{
		   			$output_str .= '<div id="wdt_'.$tbl->getId().'_chart" class="wdt_chart"></div>';
		   		}
		   }
		   // Generate the style block
	   	   $output_str .= "<style>\n";
	   	   // Table layout
		   if($table_data['fixed_layout'] || $table_data['word_wrap']) {
		   		$output_str .= ($table_data['fixed_layout'] ? "table.dataTable { table-layout: fixed }\n" : '');
		   		$output_str .= ($table_data['word_wrap'] ? "table.dataTable td { white-space: normal }\n" : '');
		   }
		   // Color and font settings
			$wpFontColorSettings = get_option('wdtFontColorSettings');
			if(!empty($wpFontColorSettings)){
				$wpFontColorSettings = unserialize($wpFontColorSettings);
				// Header background gradient
				if(!empty($wpFontColorSettings['wdtHeaderBaseColor'])){
					// 1. Getting the RGB values from chosen highlight hex value
					$highlight_rgb = array(
										hexdec(substr($wpFontColorSettings['wdtHeaderBaseColor'],1,2)), 
										hexdec(substr($wpFontColorSettings['wdtHeaderBaseColor'],3,2)), 
										hexdec(substr($wpFontColorSettings['wdtHeaderBaseColor'],5,2)) 
									); 
					// 2. Generating the RGB for darker and lighter values
					$lighter_rgb = array(); $darker_rgb = array();
					$lighter_hex = '#'; $darker_hex = '#';
					for($i=0;$i<3;$i++){
						$lighter_rgb[$i] = $highlight_rgb[$i]+25;
						if($lighter_rgb[$i] < 0 ) { $lighter_rgb[$i] = 0; }
						$hexDigit = dechex($lighter_rgb[$i]);
						if(strlen($hexDigit)==1){ $hexDigit = '0'.$hexDigit; }
						$lighter_hex .= $hexDigit;
						$darker_rgb[$i] = $highlight_rgb[$i]-25;
						if($darker_rgb[$i] < 0 ) { $darker_rgb[$i] = 0; }
						$hexDigit = dechex($darker_rgb[$i]);
						if(strlen($hexDigit)==1){ $hexDigit = '0'.$hexDigit; }
						$darker_hex .= $hexDigit;
					}
					// 3. Compiling the header and gradient style
					$output_str .= "table.dataTable thead th {\n";
					$output_str .= "background-color: ".$wpFontColorSettings['wdtHeaderBaseColor'].";\n";
					$output_str .= "background-image: -webkit-gradient(linear, 0 0, 0 100%, from(".$lighter_hex."), to(".$darker_hex.") );\n";
					$output_str .= "background-image: -webkit-linear-gradient(top, ".$lighter_hex.", ".$darker_hex.");\n";
					$output_str .= "background-image: -o-linear-gradient(top, ".$lighter_hex.", ".$darker_hex.");\n";
					$output_str .= "background-image: linear-gradient(to bottom, ".$lighter_hex.", ".$darker_hex.");\n";
					$output_str .= "background-image: -moz-linear-gradient(top, ".$lighter_hex.", ".$darker_hex.");\n";
					$output_str .= "background-repeat: repeat-x;\n";
					$output_str .= "filter: progid:dximagetransform.microsoft.gradient(  startColorstr=  '".$lighter_hex."', endColorstr=  '".$darker_hex."', GradientType=  0 );\n";
					$output_str .= "}\n";
				}
			}
			// Header active and hover color
			if(!empty($wpFontColorSettings['wdtHeaderActiveColor'])){
				$output_str .= "table.dataTable thead th.sorting_asc,\n";
				$output_str .= "table.dataTable thead th.sorting_desc,\n"; 
				$output_str .= "table.dataTable thead th.sorting:hover,\n";
				$output_str .= "table.dataTable thead th.sorting_asc:hover,\n";
				$output_str .= "table.dataTable thead th.sorting_desc:hover {\n";
				$output_str .= "background-color: ".$wpFontColorSettings['wdtHeaderActiveColor'].";\n";
				$output_str .= "background-image: none;\n";
				$output_str .= "filter: none;\n";
				$output_str .= "}\n";				
			}
			// Header font color
			if(!empty($wpFontColorSettings['wdtHeaderFontColor'])){
					$output_str .= "table.dataTable thead th {\n";
					$output_str .= "color: ".$wpFontColorSettings['wdtHeaderFontColor'].";";
					$output_str .= "}\n";
			}
			// Header border color
			if(!empty($wpFontColorSettings['wdtHeaderBorderColor'])){
				$output_str .= "table.dataTable thead th, table.dataTable thead th:first-child {\n";
				$output_str .= "border: 1px solid ".$wpFontColorSettings['wdtHeaderBorderColor'].";\n";
				$output_str .= "}\n";
			}
			// Table inner border
			if(!empty($wpFontColorSettings['wdtTableInnerBorderColor'])){
				$output_str .= "table.dataTable td {\n";
				$output_str .= "border: 1px solid ".$wpFontColorSettings['wdtTableInnerBorderColor'].";\n";
				$output_str .= "border-bottom-style: none;\n";
				$output_str .= "border-left-color: #fff;\n";
				$output_str .= "}\n";
			}
			// Table outer border
			if(!empty($wpFontColorSettings['wdtTableOuterBorderColor'])){
				$output_str .= "table.dataTable tr td:last-child {\n";
				$output_str .= "border-right-color: ".$wpFontColorSettings['wdtTableOuterBorderColor'].";\n";
				$output_str .= "}\n";
				$output_str .= "table.dataTable tr:last-child td {\n";
				$output_str .= "border-bottom: 1px solid ".$wpFontColorSettings['wdtTableOuterBorderColor'].";\n";
				$output_str .= "}\n";
				$output_str .= "table.dataTable tr:first-child td {\n";
				$output_str .= "border-top: 1px solid ".$wpFontColorSettings['wdtTableOuterBorderColor'].";\n";
				$output_str .= "}\n";	
				$output_str .= "table.dataTable tr td:first-child {\n";
				$output_str .= "border-left: 1px solid ".$wpFontColorSettings['wdtTableOuterBorderColor']." !important;\n";
				$output_str .= "}\n";
			}
			// Table font color
			if(!empty($wpFontColorSettings['wdtTableFontColor'])){
				$output_str .= "table.dataTable {\n";
				$output_str .= "color: ".$wpFontColorSettings['wdtTableFontColor'].";\n";
				$output_str .= "}\n";
			}
			// Odd rows BG
			if(!empty($wpFontColorSettings['wdtOddRowColor'])){
				$output_str .= "table.dataTable tr.odd {\n";
				$output_str .= "background-color: ".$wpFontColorSettings['wdtOddRowColor'].";\n";
				$output_str .= "}\n";
			}				
			// Even rows BG
			if(!empty($wpFontColorSettings['wdtEvenRowColor'])){
				$output_str .= "table.dataTable tr.even {\n";
				$output_str .= "background-color: ".$wpFontColorSettings['wdtEvenRowColor'].";\n";
				$output_str .= "}\n";
			}				
			// Active odd cell BG
			if(!empty($wpFontColorSettings['wdtActiveOddCellColor'])){
				$output_str .= "table.dataTable tr.odd td.sorting_1 {\n";
				$output_str .= "background-color: ".$wpFontColorSettings['wdtActiveOddCellColor'].";\n";
				$output_str .= "}\n";
			}			
			// Active even cell BG
			if(!empty($wpFontColorSettings['wdtActiveEvenCellColor'])){
				$output_str .= "table.dataTable tr.even td.sorting_1 {\n";
				$output_str .= "background-color: ".$wpFontColorSettings['wdtActiveEvenCellColor'].";\n";
				$output_str .= "}\n";
			}		
			// Hover row
			if(!empty($wpFontColorSettings['wdtHoverRowColor'])){
				$output_str .= "table.dataTable tr:hover, table.dataTable tr:hover td.sorting_1 {\n";
				$output_str .= "background-color: ".$wpFontColorSettings['wdtHoverRowColor'].";\n";
				$output_str .= "}\n";
			}	
			// Font
			if(!empty($wpFontColorSettings['wdtTableFont'])) {
				$output_str .= "table.dataTable {\n";
				$output_str .= "font-family: ".$wpFontColorSettings['wdtTableFont'].";\n";
				$output_str .= "}\n";
			}					
		   $output_str .= "</style>\n";
		   return $output_str;
		}
		
	/**
	 * Handler which returns the AJAX response
	 */
	 function wdt_get_ajax_data(){
	 	$id = $_GET['table_id'];
	   	$table_data = wdt_get_table_by_id( $id );
	   	$column_data = wdt_get_columns_by_table_id( $id );
	   	$column_headers = array();
	   	$column_types = array();
	   	$column_filtertypes = array();
	   	$column_inputtypes = array();
		   foreach($column_data as $column){
		   		$column_order[(int)$column->pos] = $column->orig_header;
		   		if($column->display_header){
			   		$column_headers[$column->orig_header] = $column->display_header;
		   		}
		   		if($column->column_type != 'autodetect'){
			   		$column_types[$column->orig_header] = $column->column_type;
		   		}else{
			   		$column_types[$column->orig_header] = 'string';
		   		}	
		   		$column_filtertypes[$column->orig_header] = $column->filter_type;
		   		$column_inputtypes[$column->orig_header] = $column->input_type;
		   }
	   	
	   	$tbl = new PHPDataTable();
		$tbl->enableServerProcessing();
		echo $tbl->buildByQuery($table_data['content'], array(),
 				array(
 					'data_types'=>$column_types,
 					'column_names'=>$column_headers,
 					'filter_types'=>$column_filtertypes,
 					'input_types'=>$column_inputtypes,
 					'column_order'=>$column_order
 					)
		);
	 	exit();
	 }
	 
	/**
	 * Handler which returns the AJAX preview
	 */
	 function wdt_get_ajax_preview(){
	 	$no_scripts = !empty($_POST['no_scripts']) ? 1 : 0;
	 	echo wdt_output_table($_POST['table_id'], $no_scripts);
	 	if(!$no_scripts){
		 	$scripts = array(
			 	PDT_JS_PATH.'jquery-datatables/jquery.dataTables.min.js',
				PDT_JS_PATH.'responsive/lodash.min.js',
				PDT_JS_PATH.'responsive/datatables.responsive.js',
			 	PDT_JS_PATH.'jquery-datatables/TableTools.min.js',
			 	PDT_JS_PATH.'php-datatables/wpdatatables.funcs.min.js',
			 	PDT_JS_PATH.'jquery-datatables/jquery.dataTables.rowGrouping.js',
			 	PDT_JS_PATH.'jquery-datatables/FixedHeader.js',
				PDT_JS_PATH.'jquery-datatables/jquery.dataTables.columnFilter.js',
				PDT_JS_PATH.'fileupload/jquery.iframe-transport.js',
				PDT_JS_PATH.'fileupload/jquery.fileupload.js',
				PDT_JS_PATH.'maskmoney/jquery.maskMoney.js',
				PDT_JS_PATH.'wpdatatables/wpdatatables.min.js'		 	
		 	);
	 	}else{
			$scripts = array(PDT_JS_PATH.'wpdatatables/wpdatatables.min.js');
	 	}
	 	foreach($scripts as $script){
	 		echo '<script type="text/javascript" src="'.$script.'"></script>';
	 	}
	 	exit();
	 }	 
	
	/**
	 * Returns system fonts
	 */
	function wdt_get_system_fonts(){
		$system_fonts = array(
			'Georgia, serif',
			'Palatino Linotype, Book Antiqua, Palatino, serif',
			'Times New Roman, Times, serif',
			'Arial, Helvetica, sans-serif',
			'Impact, Charcoal, sans-serif',
			'Lucida Sans Unicode, Lucida Grande, sans-serif',
			'Tahoma, Geneva, sans-serif',
			'Verdana, Geneva, sans-serif',
			'Courier New, Courier, monospace',
			'Lucida Console, Monaco, monospace'
		);
		return $system_fonts;
	}
	
	/**
	 * Saves the table from frontend
	 */
	 function wdt_save_table_frontend(){
	 	global $wpdb;
	 	$formdata = $_POST['formdata'];
	 	
		$table_id = $formdata['table_id'];
	 	unset($formdata['table_id']);
	 	
	 	$table_data = wdt_get_table_by_id( $table_id );
	 	$mysql_table_name = $table_data['mysql_table_name'];
	 	
	 	$columns_data = wdt_get_columns_by_table_id( $table_id );
	 	$id_key = '';
	 	$id_val = '';
	 	foreach($columns_data as $column){
	 		if($column->id_column){
	 			$id_key = $column->orig_header;
	 			$id_val = $formdata[$id_key];
			 	unset($formdata[$id_key]);
			 	break;
	 		}
	 	}
	 	$formdata = stripslashes_deep($formdata);
		if($id_val != '0'){
			$wpdb->update($mysql_table_name,
					      $formdata,
							array(
								$id_key => $id_val
								)
							);
		}else{
			$wpdb->insert($mysql_table_name,
					      $formdata
							);			
			echo $wpdb->insert_id;
		}
	 	
	 	exit();
	 }
	 
	 /**
	  * Handle the file upload
	  */
	  function wdt_upload_file(){
	  	require_once(PDT_ROOT_PATH.'lib/upload/UploadHandler.php');
	  	$uploadHandler = new UploadHandler();
	  	exit();
	  }
	  
	  /**
	   * Handle table row delete
	   */
	   function wdt_delete_table_row(){
		 	global $wpdb;

			$table_id = $_POST['table_id'];
		 	$id_key = $_POST['id_key'];
		 	$id_val = $_POST['id_val'];
		 	
		 	$table_data = wdt_get_table_by_id( $table_id );
		 	$mysql_table_name = $table_data['mysql_table_name'];
		 	
		 	$wpdb->delete($mysql_table_name, array($id_key => $id_val));
		 	
		 	exit();
	   }
		
		
	/**
	 * Handle uploaded file delete
	 */
	 function wdt_delete_uploaded_file(){
		global $wpdb;			
		$table_id = $_POST['table_id'];
	 	$id_key = $_POST['id_key'];
	 	$id_val = $_POST['id_val'];
	 	$key = $_POST['key'];
	 	$table_data = wdt_get_table_by_id( $table_id );
	 	$mysql_table_name = $table_data['mysql_table_name'];	 	
	 	// First selecting and unlinking the exiting file (if exists);
	 	$rows = $wpdb->get_results("SELECT {$key} FROM {$mysql_table_name} WHERE {$id_key} = '{$id_val}'", ARRAY_A);
	 	if(!empty($rows)){
		 	$filename = $rows[0][$key];
		 	if(!empty($filename)){
		 		$filename = urldecode($filename);
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				    $filename = str_replace(site_url(), str_replace('\\', '/', ABSPATH), $filename); 
				}else{
					$filename = str_replace(site_url(), ABSPATH, $filename);
				}
				if(file_exists($filename)){
					unlink($filename);
				}
		 	}
	 	}
	 	
	 	// Updating the value in DB
	 	$wpdb->update($mysql_table_name,
					        array($key => ''),
							array(
								$id_key => $id_val
								)
							);
		echo '';
		exit();
	 }
	
	register_activation_hook(__FILE__, 'wpdatatables_activation');
	register_deactivation_hook(__FILE__, 'wpdatatables_deactivation');	
	register_uninstall_hook(__FILE__, 'wpdatatables_uninstall');	
	
	add_shortcode( 'wpdatatable', 'wpdatatable_shortcode_handler' );
	
	// AJAX-handlers
	add_action( 'wp_ajax_get_wdtable', 'wdt_get_ajax_data' );
	add_action( 'wp_ajax_nopriv_get_wdtable', 'wdt_get_ajax_data' );
	add_action( 'wp_ajax_wdt_save_table_frontend', 'wdt_save_table_frontend' );
	add_action( 'wp_ajax_nopriv_wdt_save_table_frontend', 'wdt_save_table_frontend' );
	add_action( 'wp_ajax_wdt_upload_file', 'wdt_upload_file' );
	add_action( 'wp_ajax_nopriv_wdt_upload_file', 'wdt_upload_file' );
	add_action( 'wp_ajax_wdt_delete_table_row', 'wdt_delete_table_row' );
	add_action( 'wp_ajax_nopriv_wdt_delete_table_row', 'wdt_delete_table_row' );	
	add_action( 'wp_ajax_wdt_delete_uploaded_file', 'wdt_delete_uploaded_file' );
	add_action( 'wp_ajax_nopriv_wdt_delete_uploaded_file', 'wdt_delete_uploaded_file' );	
	
	wpdatatables_load();

?>
