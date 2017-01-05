<?php

class FrmSigUpdate extends FrmAddon {

	public $plugin_file;
	public $plugin_name = 'Signature';
	public $version = '1.07.04';

	public function __construct() {
		$this->plugin_file = dirname( dirname( __FILE__ ) ) . '/signature.php';
		parent::__construct();
	}

	public static function load_hooks() {
		add_filter( 'frm_include_addon_page', '__return_true' );
		new FrmSigUpdate();
	}

}