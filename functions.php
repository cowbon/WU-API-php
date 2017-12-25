<?php
	function delete_in_array( &$array, $index ) {
		if( isset ( $array[ $index ] ) ) {
			unset( $array[ $index ] );
			$array = array_values( $array );
		}
	}

	function add_after_array( &$array, $index ) {
		$temp = array(
			"country_start" => NULL,
			"city_start" => NULL,
			"country_end" => NULL,
			"city_end" => NULL,
			"date_start" => NULL,
			"date_end"=> NULL,
		);
		array_splice($array, $index, 0, array($temp) );
	}

	function check_array( &$array ){
		$var_array = array(
			"country_start" => "departure country",
			"city_start" => "departure city",
			"country_end" => "arrival country",
			"city_end" => "arrival city",
			"date_start" => "departure date",
			"date_end"=> "arrival date",
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
		$end = date_create_from_format('Y-m-d', end( $array ) [ 'date_end' ] );
		$start = date_create_from_format('Y-m-d', $array[0][ 'date_start' ] );
		
		if( empty( $message ) ){
			return array ( true, NULL );
		}
		else{
			return array ( false, $message );
		}
	}

	function input_to_week( &$item ){
		$week = array();
		foreach ( array_keys( $array ) as $index ){
			;
		}
	}
?>
