<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

define( 'WP_ROCKET_ADVANCED_CACHE', true );
$rocket_cache_path = '/Users/cbessee/Sites/mesaportal.dev/wp-content/cache/wp-rocket/';
$rocket_config_path = '/Users/cbessee/Sites/mesaportal.dev/wp-content/wp-rocket-config/';

if ( file_exists( '/Users/cbessee/Sites/mesaportal.dev/wp-content/plugins/wp-rocket/inc/front/process.php' ) ) {
	include( '/Users/cbessee/Sites/mesaportal.dev/wp-content/plugins/wp-rocket/inc/front/process.php' );
} else {
	define( 'WP_ROCKET_ADVANCED_CACHE_PROBLEM', true );
}