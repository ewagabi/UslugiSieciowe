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

	if (NULL == $ci->Main_model->get_doctor($p_appointment->doctor_id)) {
		return FALSE;
	}

	if (NULL == $ci->Main_model->get_patient($p_appointment->patient_id)) {
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
	return json_decode(file_get_contents("php://input"), true);
}

function format_doctor($p_doctor) {
	/*
	"id": 1,
	"speciality_id": 1,
	"first_name": "John",
	"last_name": "Doe",
	"phone": "987654321",
	"gender": 1,
	"birthday": "1980-04-07",
	"email": "john.doe@example.com",
	"room": "12a"
	*/

	$doc_obj = new stdClass;

	$doc_obj->id = intval($p_doctor->id);
	$doc_obj->speciality_id = intval($p_doctor->SPECIALITY_id);
	$doc_obj->first_name = $p_doctor->first_name;
	$doc_obj->last_name = $p_doctor->last_name;
	$doc_obj->phone = $p_doctor->phone;
	$doc_obj->gender = intval($p_doctor->gender);
	$doc_obj->birthday = $p_doctor->birthday;
	$doc_obj->email = $p_doctor->email;
	$doc_obj->room = $p_doctor->room;

	return $doc_obj;
}

function format_patient($p_patient) {
	/*
	"id": 2,
	"first_name": "Anna",
	"last_name": "Kowalsky",
	"phone": "888999000",
	"gender": 0,
	"birthday": "1980-04-12",
	"email": "anna.doe@example.com"
	*/

	$pat_obj = new stdClass;

	$pat_obj->id = intval($p_patient->id);
	$pat_obj->first_name = $p_patient->first_name;
	$pat_obj->last_name = $p_patient->last_name;
	$pat_obj->phone = $p_patient->phone;
	$pat_obj->gender = intval($p_patient->gender);
	$pat_obj->birthday = $p_patient->birthday;
	$pat_obj->email = $p_patient->email;

	return $pat_obj;
}

function format_appointment($p_appointment) {
	/*
	"id": 1,
	"doctor_id": 1,
	"patient_id": 2,
	"date": "2018-08-28 09:00:00",
	"duration": "00:12:00"
	*/

	$app_obj = new stdClass;

	$app_obj->id = intval($p_appointment->id);
	$app_obj->doctor_id = intval($p_appointment->DOCTOR_id);
	$app_obj->patient_id = intval($p_appointment->PATIENT_id);
	$app_obj->date = $p_appointment->date;
	$app_obj->duration = $p_appointment->duration;

	return $app_obj;
}

function format_speciality($p_speciality) {
	/*
	"id": 1,
	"name": "Internist",
	"price_per_appointment": "150.00"
	*/

	$spec_obj = new stdClass;

	$spec_obj->id = intval($p_speciality->id);
	$spec_obj->name = $p_speciality->name;
	$spec_obj->price_per_appointment = $p_speciality->price_per_appointment;

	return $spec_obj;
}

?>