<?php 

/**
 * Function check_params
 * Sprawdza czy parametr (lub tablica parametrów) znajduje się w tablicy POST		
 */
 
function check_params($p_params, $p_post) {
	if (! is_array($p_params)) {

		// jeśli $p_params nie jest tablica to zamieniam go na nią

		$p_params = array($p_params);
	}

	foreach ($p_params as $param) {
		if (! array_key_exists($param, $p_post)) {
		
			// jeśli aktualny $param nie istnieje w tablicy POST to zwracam FALSE
		
			return FALSE;
		}
	}
	return TRUE;
}

function validate_date($p_date, $p_format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($p_format, $p_date);
    return $d && $d->format($p_format) == $p_date;
}

function validate_email($p_email) {
	return filter_var($p_email, FILTER_VALIDATE_EMAIL);
}

function validate_doctor($p_doctor) {
	$ci =& get_instance();

	if (! validate_email($p_doctor->email)) {
		return FALSE;
	}

	if (! validate_date($p_doctor->birthday, "Y-m-d")) {
		return FALSE;
	}

	if (NULL == $ci->Main_model->get_speciality($p_doctor->speciality_id)) {
		return FALSE;
	}

	if ($p_doctor->gender < 0 || $p_doctor->gender > 1) {
		return FALSE;
	}
	return TRUE;
}

function validate_patient($p_patient) {
	$ci =& get_instance();

	if (! validate_email($p_patient->email)) {
		return FALSE;
	}

	if (! validate_date($p_patient->birthday, "Y-m-d")) {
		return FALSE;
	}

	if ($p_patient->gender < 0 || $p_patient->gender > 1) {
		return FALSE;
	}
	return TRUE;
}

function validate_appointment($p_appointment) {
	$ci =& get_instance();


	if (! validate_date($p_appointment->date, "Y-m-d H:i:s")) {
		return FALSE;
	}

	if (NULL == $ci->Main_model->get_doctor($p_appointment->DOCTOR_id)) {
		return FALSE;
	}

	if (NULL == $ci->Main_model->get_patient($p_appointment->PATIENT_id)) {
		return FALSE;
	}

	if (! validate_duration($p_appointment->duration)) {
		return FALSE;
	}

	return TRUE;
}

function validate_speciality($p_speciality) {
	if (! validate_price($p_speciality->price_per_appointment)) {
		return FALSE;
	}

	return TRUE;
}

function validate_obj_fields($p_fields_arr, $p_obj) {
	$p_fields_arr = array_keys($p_fields_arr);
	$vars = get_object_vars($p_obj);
	if (NULL == $vars) {
		return FALSE;
	}
	if (! is_array($p_fields_arr)) {
		return FALSE;
	}
	foreach ($p_fields_arr as $field) {
		if (! array_key_exists($field, $vars)) {
			return FALSE;
		}
	}
	return TRUE;
}

function validate_duration($p_duration) {
	$pattern = '#^\d\d:\d\d:\d\d$#';	// "00:50:00"
	if (preg_match_all($pattern, $p_duration, $matches)) {
		$vals = explode(':', $p_duration);
		if (intval($vals[0]) > 23 || intval($vals[1]) > 59 || intval($vals[2]) > 59) {
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validate_price($p_price) {
	$pattern = '#^\d{1,3}\.\d\d$#';		// 150.00
	if (preg_match_all($pattern, $p_price, $matches)) {
		return TRUE;
	}
	return FALSE;
}

function get_input() {
	$ci =& get_instance();

	switch($ci->input->method(TRUE)) {
		case 'GET':
			return $ci->input->get();

		case 'POST':
			return $ci->input->post();

		case 'PUT':
		case 'DELETE':
			return $ci->input->input_stream();
	}
	return array();
}

?>