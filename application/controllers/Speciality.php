<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Speciality extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->output->set_content_type('application/json');
	}

	/*
		24. EDIT_SPECIALITY
	*/
	public function edit($p_speciality_id) {
		if (! ($this->input->method(TRUE) === "POST" || $this->input->method(TRUE) === "PUT")) {
			return;
		}
		$p_speciality_id = intval(xss_clean($p_speciality_id));

		$post_put = get_input();

		$speciality = $this->Main_model->get_speciality($p_speciality_id);
		if (NULL != $speciality) {
			if (validate_obj_fields($post_put, $speciality)) {
				foreach ($post_put as $key => $value) {
					$speciality->{$key} = xss_clean($value);

					if (validate_speciality($speciality)) {
						if ($this->Main_model->edit_speciality($p_speciality_id, $speciality)) {
							$this->output->set_output(json_encode($speciality));
						}
					}
				}
			}
		}
	}

	/*
		25. READ_SPECIALITY
	*/
	public function read($p_speciality_id) {
		if ($this->input->method(TRUE) !== "GET") {
			return;
		}
		$p_speciality_id = intval(xss_clean($p_speciality_id));

		$speciality = $this->Main_model->get_speciality($p_speciality_id);
		if (NULL != $speciality) {
			$this->output->set_output(json_encode($speciality));
		}
	}

	/*
		26. READ_SPECIALITIES
	*/
	public function read_all() {
		$this->output->set_output(json_encode($this->Main_model->read_all_specialities()));
	}

}

/* End of file Speciality.php */
/* Location: ./application/controllers/Speciality.php */