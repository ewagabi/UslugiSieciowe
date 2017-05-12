<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doctor extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->output->set_content_type('application/json');
	}

	/*
		1. CREATE_DOCTOR
	*/
	public function create() {
		if ($this->input->method(TRUE) !== "POST") {
			return;
		}

		$params = array(
			"speciality_id",
			"first_name",
			"last_name",
			"phone",
			"gender",
			"birthday",
			"email",
			"room"
			);

		$data_in = get_input();

		if (! check_params($params, $data_in)) {	
			return;
		}

		$doctor = new stdClass;

		$doctor->speciality_id = intval(xss_clean($data_in["speciality_id"]));
		$doctor->first_name = xss_clean($data_in["first_name"]);
		$doctor->last_name = xss_clean($data_in["last_name"]);
		$doctor->phone = xss_clean($data_in["phone"]);
		$doctor->gender = intval(xss_clean($data_in["gender"]));
		$doctor->birthday = xss_clean($data_in["birthday"]);
		$doctor->email = xss_clean($data_in["email"]);
		$doctor->room = xss_clean($data_in["room"]);

		// sprawdzenie poprawności danych

		if (! validate_doctor($doctor)) {
			return;
		}

		$insert_id = $this->Main_model->create_doctor($doctor);
		if ($insert_id > 0) {
			$this->output->set_output(json_encode(array("id" => intval($insert_id))));
		}
	}

	/*
		2. EDIT_DOCTOR
	*/
	public function edit($p_doctor_id) {
		if (! ($this->input->method(TRUE) === "POST" || $this->input->method(TRUE) === "PUT")) {
			return;
		}
		$p_doctor_id = intval(xss_clean($p_doctor_id));

		$post_put = get_input();

		$doctor = $this->Main_model->get_doctor($p_doctor_id);
		if (NULL != $doctor) {
			if (validate_obj_fields($post_put, $doctor)) {
				foreach ($post_put as $key => $value) {
					$doctor->{$key} = xss_clean($value);
					if (validate_doctor($doctor)) {
						if ($this->Main_model->edit_doctor($p_doctor_id, $doctor)) {
							$this->output->set_output(json_encode($this->Main_model->get_doctor($p_doctor_id)));
						}
					}
				}
			}
		}
	}

	/*
		3. DELETE_DOCTOR
	*/
	public function delete($p_doctor_id) {
		if (! ($this->input->method(TRUE) !== "GET" || $this->input->method(TRUE) !== "DELETE")) {
			return;
		}
		$p_doctor_id = intval(xss_clean($p_doctor_id));

		$doctor = $this->Main_model->get_doctor($p_doctor_id);
		if (NULL != $doctor) {
			if ($this->Main_model->delete_doctor($p_doctor_id)) {
				$this->output->set_output(json_encode(array("id" => $p_doctor_id)));
			}
		}
	}

	/*
		4. READ_DOCTOR
	*/
	public function read($p_doctor_id) {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$p_doctor_id = intval(xss_clean($p_doctor_id));

		$doctor = $this->Main_model->get_doctor($p_doctor_id);
		if (NULL != $doctor) {
			unset($doctor->id);
			$this->output->set_output(json_encode($doctor));
		}
	}

	/*
		5. READ_DOCTOR_APPOINTMENTS || 7. READ_DOCTOR_APPOINTMENTS_BY_DATE
	*/
	public function read_appointments($p_doctor_id) {
		$p_doctor_id = intval(xss_clean($p_doctor_id));
		
		if ($this->input->method(TRUE) === "GET") {
			$this->output->set_output(json_encode($this->Main_model->get_doctor_appointments($p_doctor_id)));
		} else if ($this->input->method(TRUE) === "POST") {
			$data_in = get_input();
			if (array_key_exists("date", $data_in)) {
				$date = $data_in["date"];
				if (! validate_date($date, "Y-m-d")) {
					return;
				}
				$this->output->set_output(json_encode($this->Main_model->get_doctor_appointments_by_date($p_doctor_id, $date)));
			}
		}
	}

	/*
		6. READ_DOCTOR_APPOINTMENT
	*/
	public function read_appointment($p_doctor_id, $p_appointment_id) {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$p_doctor_id = intval(xss_clean($p_doctor_id));
		$p_appointment_id = intval(xss_clean($p_appointment_id));

		$appointment = $this->Main_model->get_doctor_appointment($p_doctor_id, $p_appointment_id);
		if (NULL != $appointment) {
			$this->output->set_output(json_encode($appointment));
		}
	}

	/*
		8. READ_DOCTORS
	*/
	public function read_all() {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$this->output->set_output(json_encode($this->Main_model->read_all_doctors()));
	}

	/*
		9. READ_DOCTORS_BY_SPECIALITY
	*/
	public function read_by_speciality($p_speciality_id) {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$p_speciality_id = intval(xss_clean($p_speciality_id));
		
		$doctors = $this->Main_model->get_doctors_by_speciality($p_speciality_id);
		$this->output->set_output(json_encode($doctors));
	}

}

/* End of file Doctor.php */
/* Location: ./application/controllers/Doctor.php */