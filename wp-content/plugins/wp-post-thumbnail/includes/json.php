<?php

function json_encode($array) {
	if( !is_array( $array ) )
		return false;

	$associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));

	if( $associative ) {
		$construct = array();
		foreach( $array as $key => $value ) {
			if( is_numeric($key) ){
				$key = "key_$key";
			}
			$key = "'".addslashes($key)."'";

			if( is_array( $value )) {
				$value = json_encode($value);
			} else if( !is_numeric( $value ) || is_string( $value ) ) {
				$value = "'".addslashes($value)."'";
			}

			$construct[] = "$key: $value";
		}

		$result = "{ " . implode( ", ", $construct ) . " }";

	} else {
	
		$construct = array();
		foreach( $array as $value ){

			if( is_array( $value )){
				$value = json_encode($value);
			} else if( !is_numeric( $value ) || is_string( $value ) ){
				$value = "'".addslashes($value)."'";
			}

			$construct[] = $value;
		}

		$result = "[ " . implode( ", ", $construct ) . " ]";
	}

	return $result;
}