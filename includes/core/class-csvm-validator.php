<?php
/**
 * Validate the provided data
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CSVM_Validator' ) ) {
	/**
	 * Validate the provided data
	 */
	class CSVM_Validator {
		/**
		 * The value passed to the constructor
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		private string $value;

		/**
		 * The error name
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		private string $error;

		/**
		 * The validation rules
		 *
		 * @since 1.0
		 *
		 * @var array|string[]
		 */
		private array $options;

		/**
		 * The status property of the validation
		 *
		 * @since 1.0
		 *
		 * @var bool
		 */
		private bool $returnable;

		/**
		 * The name of the field that's being validated
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		private string $field;

		/**
		 * Begin validation
		 *
		 * @since 1.0
		 *
		 * @param string $field The name of the field.
		 * @param mixed  $value The value for the field.
		 * @param mixed  $options The options.
		 * @return void
		 */
		public function __construct( string $field, mixed $value, mixed $options ) {
			$this->field      = $field;
			$this->value      = $value;
			$this->options    = explode( '|', $options );
			$this->returnable = true;

			$this->start();
		}

		/**
		 * Starts the validation process
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function start(): void {
			foreach ( $this->options as $option ) {
				$this->individual_validation( $option );

				if ( false === $this->returnable ) {
					break;
				}
			}
		}

		/**
		 * Returns the status of the validation
		 *
		 * @since 1.0
		 *
		 * @return bool
		 */
		public function result(): bool {
			return $this->returnable;
		}

		/**
		 * Returns the error
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		public function get_error(): string {
			if ( ! empty( $this->error ) ) {
				return $this->error;
			}

			return 'No error';
		}

		/**
		 * Validates the individual value
		 *
		 * @since 1.0
		 *
		 * @param string $option The rule name.
		 *
		 * @return void
		 */
		private function individual_validation( string $option ): void {
			switch ( $option ) {
				case 'required':
					$this->required( $this->value );
					return;
				case 'string':
					$this->string( $this->value );
					return;
				case 'integer':
					$this->integer( $this->value );
					return;
				case 'numeric':
					$this->numeric( $this->value );
					return;
				case 'float':
					$this->float( $this->value );
					return;
				case 'array':
					$this->array( $this->value );
					return;
				case 'date':
					$this->date( $this->value );
					return;
			}

			if ( str_contains( $option, 'min:' ) ) {
				$limit = ( explode( ':', $option ) )[1];

				$this->minimum( $this->value, $limit );
			}

			if ( str_contains( $option, 'max:' ) ) {
				$limit = ( explode( ':', $option ) )[1];

				$this->maximum( $this->value, $limit );
			}
		}

		/**
		 * Checks if the value given is not empty
		 *
		 * @since 1.0
		 *
		 * @param mixed $value The value of the field.
		 *
		 * @return void
		 */
		private function required( mixed $value ): void {
			if ( 0 != $value ) {
				if ( empty( $value ) ) {
					$this->trigger_error( 'is required' );
				}
			}
		}

		/**
		 * Checks if the given value is a string
		 *
		 * @since 1.0
		 *
		 * @param mixed $value The value of the field.
		 *
		 * @return void
		 */
		private function string( mixed $value ): void {
			if ( ! is_string( $value ) ) {
				$this->trigger_error( 'must be a string' );
			}
		}

		/**
		 * Checks if the given value is an integer
		 *
		 * @since 1.0
		 *
		 * @param mixed $value The value of the field.
		 *
		 * @return void
		 */
		private function integer( mixed $value ): void {
			if ( ! is_integer( $value ) ) {
				$this->trigger_error( 'must be an integer' );
			}
		}

		/**
		 * Checks if the given value is a number
		 *
		 * @since 1.0
		 *
		 * @param mixed $value The value of the field.
		 *
		 * @return void
		 */
		private function numeric( mixed $value ): void {
			if ( ! is_numeric( $value ) ) {
				$this->trigger_error( 'must be a number' );
			}
		}

		/**
		 * Checks if the given number is a float
		 *
		 * @since 1.0
		 *
		 * @param mixed $value The value of the field.
		 *
		 * @return void
		 */
		private function float( mixed $value ): void {
			if ( ! is_float( $value ) ) {
				$this->trigger_error( 'must be a float' );
			}
		}

		/**
		 * Checks if the given number is an array
		 *
		 * @since 1.0
		 *
		 * @param mixed $value The value of the field.
		 *
		 * @return void
		 */
		private function array( mixed $value ): void {
			if ( ! is_array( $value ) ) {
				$this->trigger_error( 'must be an array' );
			}
		}

		/**
		 * Checks if the given number is a date
		 *
		 * @since 1.0
		 *
		 * @param mixed $value The value of the field.
		 *
		 * @return void
		 */
		private function date( mixed $value ): void {
			if ( ! strtotime( $value ) ) {
				$this->trigger_error( 'must be a date' );
			}
		}

		/**
		 * Checks if the given value has a minimum of characters
		 *
		 * @since 1.0
		 *
		 * @param string $value The value of the field.
		 * @param int    $limit The minimum limit.
		 *
		 * @return void
		 */
		private function minimum( string $value, int $limit ): void {
			if ( strlen( $value ) < $limit ) {
				$this->trigger_error( 'must have a minimum of ' . $limit . ' characters' );
			}
		}

		/**
		 * Checks if the given value has a maximum of characters
		 *
		 * @since 1.0
		 *
		 * @param string $value The value of the field.
		 * @param int    $limit The maximum limit.
		 *
		 * @return void
		 */
		private function maximum( string $value, int $limit ): void {
			if ( strlen( $value ) > $limit ) {
				$this->trigger_error( 'must have a maximum of ' . $limit . ' characters' );
			}
		}

		/**
		 * Triggers an error
		 *
		 * @since 1.0
		 *
		 * @param string $contents The error message.
		 *
		 * @return void
		 */
		private function trigger_error( string $contents ): void {
			$this->returnable = false;
			// translators: The field and the error message.
			$this->error = printf( esc_html__( 'Error: %1$s %2$s', 'csvmapper' ), esc_html( $this->field ), esc_html( $contents ) );
		}
	}
}
