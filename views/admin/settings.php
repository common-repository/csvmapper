<?php
/**
 * The settings component of the main settings view
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>


<h1>CSV Mapper - Settings</h1>
<?php new CSVM_View( 'partials/admin/settings-form' ); ?>
