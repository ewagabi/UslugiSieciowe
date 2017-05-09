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

		if (! check_params($params, $this->input->post())) {	
			return;
		}

		$doctor = new stdClass;

		$doctor->speciality_id = intval($this->input->post("speciality_id", TRUE));
		$doctor->first_name = $this->input->post("first_name", TRUE);
		$doctor->last_name = $this->input->post("last_name", TRUE);
		$doctor->phone = $this->input->post("phone", TRUE);
		$doctor->gender = intval($this->input->post("gender", TRUE));
		$doctor->birthday = $this->input->post("birthday", TRUE);
		$doctor->email = $this->input->post("email", TRUE);
		$doctor->room = $this->input->post("room", TRUE);

		// sprawdzenie poprawności danych

		if (! validate_doctor($doctor)) {
			return;
		}

		$insert_id = $this->Main_model->create_doctor($doctor);
		if ($insert_id > 0) {
			$this->output->set_output(json_encode(array("id" => $insert_id)));
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
							$doctor->id = $p_doctor_id;
							$this->output->set_output(json_encode($doctor));
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