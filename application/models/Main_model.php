<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {

	private $doctor_table;
	private $appointment_table;
	private $patient_table;
	private $speciality_table;

	public function __construct() {
		parent::__construct();
		
		$this->doctor_table = "DOCTOR";
		$this->appointment_table = "APPOINTMENT";
		$this->patient_table = "PATIENT";
		$this->speciality_table = "SPECIALITY";
	}

	/*
		DOCTOR
	*/

	public function get_doctor($p_doctor_id) {
		$this->db->select("
			id,
			SPECIALITY_id,
			first_name,
			last_name,
			phone,
			gender,
			birthday,
			email,
			room
			");
		$doctor = $this->db->where("id", $p_doctor_id)->get($this->doctor_table)->row();
		if (NULL != $doctor) {
			$doctor = format_doctor($doctor);
		}
		return $doctor;
	}

	public function create_doctor($p_doctor) {
		$this->db->insert($this->doctor_table, $p_doctor);
		return $this->db->insert_id();
	}

	public function edit_doctor($p_doctor_id, $doctor) {
		$this->db->where("id", $p_doctor_id)->update($this->doctor_table, $doctor);
		return true;
	}

	public function delete_doctor($p_doctor_id) {
		$this->db->where("id", $p_doctor_id)->delete($this->doctor_table);
		return $this->db->affected_rows() == 1;
	}

	public function read_all_doctors() {
		$arr = $this->db->get($this->doctor_table)->result();
		foreach ($arr as &$doctor) {
			$doctor = format_doctor($doctor);
		}
		return $arr; 
	}

	public function get_doctor_appointments($p_doctor_id) {
		$arr = $this->db->where("DOCTOR_id", $p_doctor_id)
			->get($this->appointment_table)
			->result();

		foreach ($arr as &$app) {
			$app = format_appointment($app);
		}
		return $arr;
	}

	public function get_doctor_appointments_by_date($p_doctor_id, $date) {
		$arr = $this->db->where("DOCTOR_id", $p_doctor_id)
			->where("DATE(date) = \"$date\"")
			->get($this->appointment_table)
			->result();

		foreach ($arr as &$app) {
			$app = format_appointment($app);
		}
		return $arr;
	}

	public function get_doctor_appointment($p_doctor_id, $p_appointment_id) {
		$app = $this->db->where("id", $p_appointment_id)
			->where("DOCTOR_id", $p_doctor_id)
			->get($this->appointment_table)
			->row();
		if (NULL != $app) {
			$app = format_appointment($app);
		}
		return $app;
	}

	public function get_doctors_by_speciality($p_speciality_id) {
		$arr = $this->db->where("SPECIALITY_id", $p_speciality_id)
			->get($this->doctor_table)
			->result();

		foreach ($arr as &$doctor) {
			$doctor = format_doctor($doctor);
		}
		return $arr;
	}

	/*
		PATIENT
	*/

	public function get_patient($p_patient_id) {
		$this->db->select("
			id,
			first_name,
			last_name,
			phone,
			gender,
			birthday,
			email,
			");
		$patient = $this->db->where("id", $p_patient_id)->get($this->patient_table)->row();
		if (NULL != $patient) {
			$patient = format_patient($patient);
		}
		return $patient;
	}

	public function create_patient($p_patient) {
		$this->db->insert($this->patient_table, $p_patient);
		return $this->db->insert_id();
	}

	public function edit_patient($p_patient_id, $patient) {
		$this->db->where("id", $p_patient_id)->update($this->patient_table, $patient);
		// if ($this->db->affected_rows() == 1 || 
		return true;
	}

	public function delete_patient($p_patient_id) {
		$this->db->where("id", $p_patient_id)->delete($this->patient_table);
		return $this->db->affected_rows() == 1;
	}

	public function read_all_patients() {
		$arr = $this->db->get($this->patient_table)
			->result();

		foreach ($arr as &$patient) {
			$patient = format_patient($patient);
		}
		return $arr;
	}

	public function get_patient_appointments($p_patient_id) {
		$arr = $this->db->where("PATIENT_id", $p_patient_id)
			->get($this->appointment_table)
			->result();

		foreach ($arr as &$app) {
			$app = format_appointment($app);
		}
		return $arr;
	}

	public function get_patient_appointments_by_date($p_patient_id, $date) {
		$arr = $this->db->where("PATIENT_id", $p_patient_id)
			->where("DATE(date) = \"$date\"")
			->get($this->appointment_table)
			->result();

		foreach ($arr as &$app) {
			$app = format_appointment($app);
		}
		return $arr;
	}

	public function get_patient_appointments_by_speciality($p_patient_id, $p_speciality_id) {
		$arr = $this->db->select("
				$this->appointment_table.id, DOCTOR_id, PATIENT_id, date, duration 
			")
			->where("PATIENT_id", $p_patient_id)
			->where("SPECIALITY_id", $p_speciality_id)
			->where($this->doctor_table.".SPECIALITY_id", $p_speciality_id)
			->join($this->doctor_table, "DOCTOR_id = ".$this->doctor_table.".id")
			->get($this->appointment_table)
			->result();

		foreach ($arr as &$app) {
			$app = format_appointment($app);
		}
		return $arr;
	}

	public function get_patient_appointment($p_patient_id, $p_appointment_id) {
		$app = $this->db->where("id", $p_appointment_id)
			->where("patient_id", $p_patient_id)
			->get($this->appointment_table)
			->row();

		if (NULL != $app) {
			$app = format_appointment($app);
		}
		return $app;
	}


	/*
		appointment
	*/

	public function create_appointment($p_appointment) {
		$this->db->insert($this->appointment_table, $p_appointment);
		return $this->db->insert_id();
	}

	public function edit_appointment($p_appointment_id, $appointment) {
		$this->db->where("id", $p_appointment_id)->update($this->appointment_table, $appointment);
		return true;
	}

	public function delete_appointment($p_appointment_id) {
		$this->db->where("id", $p_appointment_id)->delete($this->appointment_table);
		return $this->db->affected_rows() == 1;
	}

	public function get_appointment($p_appointment_id) {
		$app = $this->db->where("id", $p_appointment_id)->get($this->appointment_table)->row();
		if (NULL != $app) {
			$app = format_appointment($app);
		}
		return $app;
	}

	public function read_all_appointments() {
		$arr = $this->db->get($this->appointment_table)
			->result();

		foreach ($arr as &$app) {
			$app = format_appointment($app);
		}
		return $arr;
	}


	/*
		speciality
	*/

	public function edit_speciality($p_speciality_id, $speciality) {
		$this->db->where("id", $p_speciality_id)->update($this->speciality_table, $speciality);
		return true;
	}

	public function delete_speciality($p_speciality_id) {
		$this->db->where("id", $p_speciality_id)->delete($this->speciality_table);
		return $this->db->affected_rows() == 1;
	}

	public function get_speciality($p_speciality_id) {
		$spec = $this->db->where("id", $p_speciality_id)->get($this->speciality_table)->row();
		if (NULL != $spec) {
			$spec = format_speciality($spec);
		}
		return $spec;
	}

	public function read_all_specialities() {
		$arr = $this->db->get($this->speciality_table)
			->result();

		foreach ($arr as &$spec) {
			$spec = format_speciality($spec);
		}
		return $arr;
	}
}

/* End of file Main_model.php */
/* Location: ./application/models/Main_model.php */