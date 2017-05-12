<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->output->set_content_type('application/json');
	}

	/*
		10. CREATE_PATIENT
	*/
	public function create() {
		if ($this->input->method(TRUE) !== "POST") {
			return;
		}

		$params = array(
			"first_name",
			"last_name",
			"phone",
			"gender",
			"birthday",
			"email",
			);

		$data_in = get_input();

		if (! check_params($params, $data_in)) {			
			return;
		}

		$patient = new stdClass;

		$patient->first_name = xss_clean($data_in["first_name"]);
		$patient->last_name = xss_clean($data_in["last_name"]);
		$patient->phone = xss_clean($data_in["phone"]);
		$patient->gender = intval(xss_clean($data_in["gender"]));
		$patient->birthday = xss_clean($data_in["birthday"]);
		$patient->email = xss_clean($data_in["email"]);

		// sprawdzenie poprawności danych

		if (! validate_patient($patient)) {
			return;
		}

		$insert_id = $this->Main_model->create_patient($patient);
		if ($insert_id > 0) {
			$this->output->set_output(json_encode(array("id" => intval($insert_id))));
		}
	}

	/*
		11. EDIT_PATIENT
	*/
	public function edit($p_patient_id) {
		if (! ($this->input->method(TRUE) === "POST" || $this->input->method(TRUE) === "PUT")) {
			return;
		}
		$p_patient_id = intval(xss_clean($p_patient_id));

		$post_put = get_input();

		$patient = $this->Main_model->get_patient($p_patient_id);
		if (NULL != $patient) {
			if (validate_obj_fields($post_put, $patient)) {
				foreach ($post_put as $key => $value) {
					$patient->{$key} = xss_clean($value);
					if (validate_patient($patient)) {
						if ($this->Main_model->edit_patient($p_patient_id, $patient)) {
							$this->output->set_output(json_encode($this->Main_model->get_patient($p_patient_id)));
						}
					}
				}
			}
		}
	}

	/*
		12. DELETE_PATIENT
	*/
	public function delete($p_patient_id) {
		if (! ($this->input->method(TRUE) !== "GET" || $this->input->method(TRUE) !== "DELETE")) {
			return;
		}
		$p_patient_id = intval(xss_clean($p_patient_id));

		$patient = $this->Main_model->get_patient($p_patient_id);
		if (NULL != $patient) {
			if ($this->Main_model->delete_patient($p_patient_id)) {
				$this->output->set_output(json_encode(array("id" => intval($p_patient_id))));
			}
		}
	}

	/*
		13. READ_PATIENT
	*/
	public function read($p_patient_id) {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$p_patient_id = intval(xss_clean($p_patient_id));

		$patient = $this->Main_model->get_patient($p_patient_id);
		if (NULL != $patient) {
			$this->output->set_output(json_encode($patient));
		}
	}

	/*
		14. READ_PATIENT_APPOINTMENTS || 16. READ_PATIENT_APPOINTMENTS_BY_DATE || 17. READ_PATIENT_APPOINTMENTS_BY_SPECIALITY
	*/
	public function read_appointments($p_patient_id) {
		$p_patient_id = intval(xss_clean($p_patient_id));
		
		if ($this->input->method(TRUE) === "GET") {
			$this->output->set_output(json_encode($this->Main_model->get_patient_appointments($p_patient_id)));
		} else if ($this->input->method(TRUE) === "POST") {
			$data_in = get_input();
			if (array_key_exists("date", $data_in)) {
				$date = $data_in["date"];
				if (! validate_date($date, "Y-m-d")) {
					return;
				}
				$this->output->set_output(json_encode($this->Main_model->get_patient_appointments_by_date($p_patient_id, $date)));
			} else if (array_key_exists("speciality_id", $data_in)) {
				$speciality_id = intval(xss_clean($data_in["speciality_id"]));
				$this->output->set_output(json_encode($this->Main_model->get_patient_appointments_by_speciality($p_patient_id, $speciality_id)));
			}
		}
	}

	/*
		15. READ_PATIENT_APPOINTMENT
	*/
	public function read_appointment($p_patient_id, $p_appointment_id) {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$p_patient_id = intval(xss_clean($p_patient_id));
		$p_appointment_id = intval(xss_clean($p_appointment_id));

		$appointment = $this->Main_model->get_patient_appointment($p_patient_id, $p_appointment_id);
		if (NULL != $appointment) {
			$this->output->set_output(json_encode($appointment));
		}
	}

	/*
		18. READ_PATIENTS
	*/
	public function read_all() {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$this->output->set_output(json_encode($this->Main_model->read_all_patients()));
	}

}

/* End of file Patient.php */
/* Location: ./application/controllers/Patient.php */