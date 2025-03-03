<?php
/**
 * PHP Import
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_PHP_Import' ) ) {
	/**
	 * PHP Import
	 */
	class CSVM_PHP_Import extends CSVM_Base_Import_Type implements CSVM_Import_Type {

		/**
		 * The CSVM_Import instance
		 *
		 * @since 1.0
		 *
		 * @var CSVM_Import
		 */
		public CSVM_Import $import;

		/**
		 * The CSVM_Run instance
		 *
		 * @since 1.0
		 *
		 * @var CSVM_Run
		 */
		public CSVM_Run $run;

		/**
		 * Construct the import object
		 *
		 * @param CSVM_Import $import The CSVM_Import object.
		 */
		public function __construct( CSVM_Import $import ) {
			$this->import = $import;

			$run            = new CSVM_Run();
			$run->import_id = $import->id;
			$run->file_path = $import->file_path;
			$run->status    = CSVM_Run::$waiting_status;
			$run->type      = 'php';

			$run->save();

			$this->run = $run;
		}

		/**
		 * Executes the import
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function execute(): void {
			$handler = new CSVM_CSV_Handler( $this->run );
			$handler->start();
		}
	}
}
