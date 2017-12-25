<?php
	function delete_in_array( &$array, $index ) {
		if( isset ( $array[ $index ] ) ) {
			unset( $array[ $index ] );
			$array = array_values( $array );
		}
	}

	function check_array( &$array ){
		$var_array = array(
			"country_start" => "departure country",
			"city_start" => "departure city",
			"country_end" => "arrival country",
			"city_end" => "arrival city",
		);
		$message = array();
		foreach ( array_keys( $array ) as $index ){
			//print_r( $array[$index] );
			foreach ( array_keys( $array[$index] ) as $info ){
				if( empty( $array[$index][$info] ) ){
					array_push ( $message , ( $index + 1 ) . "'s " . $var_array[$info] );
				}
			}
		}
		if( empty( $message ) ){
			return array ( true, NULL );
		}
		else{
			return array ( false, $message );
		}
	}
?>
