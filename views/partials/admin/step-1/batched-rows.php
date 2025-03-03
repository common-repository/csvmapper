<?php
/**
 * Partial for the batched rows option
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

?>
<div class="csvm-d-none" id="csvm-batched-rows-wrap">
	<div class="csvm-form-group">
		<label for="csvm-batched-rows"><?php echo esc_html__( 'Batched Rows', 'csvmapper' ); ?></label>
		<select name="csvm-batched-rows" id="csvm-batched-rows">
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="500">500</option>
		</select>
	</div>
</div>
