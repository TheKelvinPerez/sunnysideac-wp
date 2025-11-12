<?php
/**
 * Debug Helper Functions
 *
 * Contains debugging utilities for development
 */

/**
 * Helper function to flatten nested arrays for Whoops display
 * Converts nested arrays into dot notation strings
 */
if ( ! function_exists( 'dd_flatten_array' ) ) {
	function dd_flatten_array( $array, $prefix = '' ) {
		$result = array();
		foreach ( $array as $key => $value ) {
			$newKey = $prefix === '' ? $key : $prefix . '.' . $key;

			if ( is_array( $value ) && ! empty( $value ) ) {
				// Recursively flatten nested arrays
				$result = array_merge( $result, dd_flatten_array( $value, $newKey ) );
			} else {
				// Convert value to string for display
				if ( is_bool( $value ) ) {
					$result[ $newKey ] = $value ? 'true' : 'false';
				} elseif ( is_null( $value ) ) {
					$result[ $newKey ] = 'null';
				} elseif ( is_string( $value ) && $value === '' ) {
					$result[ $newKey ] = '(empty string)';
				} else {
					$result[ $newKey ] = $value;
				}
			}
		}
		return $result;
	}
}

/**
 * Debug helper function - Dump and Die
 * Outputs clean, formatted data to Whoops error handler
 *
 * @param mixed ...$vars Variables to dump
 */
if ( ! function_exists( 'dd' ) ) {
	function dd( ...$vars ) {
		// Use Whoops for beautiful output if in development
		if ( $_ENV['APP_ENV'] === 'development' ) {
			$whoops  = new \Whoops\Run();
			$handler = new \Whoops\Handler\PrettyPageHandler();

			// Add each variable as a data table
			foreach ( $vars as $varIndex => $var ) {
				$varType  = gettype( $var );
				$varLabel = 'Variable #' . ( $varIndex + 1 ) . " ({$varType})";

				// Handle different data types appropriately
				if ( is_scalar( $var ) || is_null( $var ) ) {
					// For scalar values (string, int, bool, etc.), show as simple key-value
					$displayValue = $var;
					if ( is_bool( $var ) ) {
						$displayValue = $var ? 'true' : 'false';
					} elseif ( is_null( $var ) ) {
						$displayValue = 'null';
					}
					$handler->addDataTable( $varLabel, array( 'Value' => $displayValue ) );
				} elseif ( is_array( $var ) && ! empty( $var ) ) {
					// Check if it's a numeric array with complex items (arrays/objects)
					$isNumericArray  = array_keys( $var ) === range( 0, count( $var ) - 1 );
					$hasComplexItems = false;

					if ( $isNumericArray ) {
						foreach ( $var as $item ) {
							if ( is_array( $item ) || is_object( $item ) ) {
								$hasComplexItems = true;
								break;
							}
						}
					}

					// Split into separate tables only for numeric arrays with complex items
					if ( $isNumericArray && $hasComplexItems ) {
						foreach ( $var as $itemIndex => $item ) {
							$itemLabel = $varLabel . ' - Item #' . $itemIndex;
							$flattened = dd_flatten_array( (array) $item );
							$handler->addDataTable( $itemLabel, $flattened );
						}
					} else {
						// For simple arrays or associative arrays, flatten and show in one table
						$flattened = dd_flatten_array( $var );
						$handler->addDataTable( $varLabel, $flattened );
					}
				} elseif ( is_object( $var ) ) {
					// For objects, flatten and show properties
					$flattened = dd_flatten_array( (array) $var );
					$handler->addDataTable( $varLabel, $flattened );
				} else {
					// Fallback for any other type
					$handler->addDataTable( $varLabel, array( 'Value' => print_r( $var, true ) ) );
				}
			}

			$whoops->pushHandler( $handler );
			$whoops->handleException(
				new \Exception( 'Debug Dump (dd)' )
			);
		} else {
			// Fallback for production
			echo '<pre style="background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 8px; font-family: monospace; line-height: 1.5; overflow: auto;">';
			echo '<strong style="color: #4ec9b0;">Debug Dump:</strong>' . PHP_EOL . PHP_EOL;

			foreach ( $vars as $index => $var ) {
				echo '<strong style="color: #569cd6;">Variable #' . ( $index + 1 ) . ':</strong>' . PHP_EOL;
				echo json_encode( $var, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
				echo PHP_EOL . PHP_EOL;
			}

			echo '</pre>';
		}

		exit( 1 );
	}
}