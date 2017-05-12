<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appointment extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->output->set_content_type('application/json');
	}

	/*
		19. CREATE_APPOINTMENT
	*/
	public function create() {
		if ($this->input->method(TRUE) !== "POST") {
			return;
		}

		$params = array(
			"doctor_id",
			"patient_id",
			"date",
			"duration"
			);

		$data_in = get_input();

		if (! check_params($params, $data_in)) {	
			return;
		}

		$appointment = new stdClass;

		$appointment->doctor_id = intval($data_in["doctor_id"]);
		$appointment->patient_id = intval($data_in["patient_id"]);
		$appointment->date = $data_in["date"];
		$appointment->duration = $data_in["duration"];

		// sprawdzenie poprawności danych

		if (! validate_appointment($appointment)) {
			return;
		}

		$insert_id = $this->Main_model->create_appointment($appointment);
		if ($insert_id > 0) {
			$this->output->set_output(json_encode(array("id" => intval($insert_id))));
		}
	}

	/*
		20. EDIT_APPOINTMENT
	*/
	public function edit($p_appointment_id) {
		if (! ($this->input->method(TRUE) === "POST" || $this->input->method(TRUE) === "PUT")) {
			return;
		}
		$p_appointment_id = intval(xss_clean($p_appointment_id));

		$post_put = get_input();

		$appointment = $this->Main_model->get_appointment($p_appointment_id);
		if (NULL != $appointment) {
			if (validate_obj_fields($post_put, $appointment)) {
				foreach ($post_put as $key => $value) {
					$appointment->{$key} = xss_clean($value);

					if (validate_appointment($appointment)) {
						if ($this->Main_model->edit_appointment($p_appointment_id, $appointment)) {
							$this->output->set_output(json_encode($this->Main_model->get_appointment($p_appointment_id)));
						}
					}
				}
			}
		}
	}

	/*
		21. DELETE_APPOINTMENT
	*/
	public function delete($p_appointment_id) {
		if (! ($this->input->method(TRUE) !== "GET" || $this->input->method(TRUE) !== "DELETE")) {
			return;
		}
		$p_appointment_id = intval(xss_clean($p_appointment_id));

		$appointment = $this->Main_model->get_appointment($p_appointment_id);
		if (NULL != $appointment) {
			if ($this->Main_model->delete_appointment($p_appointment_id)) {
				$this->output->set_output(json_encode(array("id" => $p_appointment_id)));
			}
		}
	}

	/*
		22. READ_APPOINTMENT
	*/
	public function read($p_appointment_id) {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$p_appointment_id = intval(xss_clean($p_appointment_id));

		$appointment = $this->Main_model->get_appointment($p_appointment_id);
		if (NULL != $appointment) {
			$this->output->set_output(json_encode($appointment));
		}
	}

	/*
		23. READ_APPOINTMENTS
	*/
	public function read_all() {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$this->output->set_output(json_encode($this->Main_model->read_all_appointments()));
	}

}

/* End of file Appointment.php */
/* Location: ./application/controllers/Appointment.php */