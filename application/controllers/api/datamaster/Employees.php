<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Employees extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('EmployeesModel', 'employees');
		$this->load->model('UsersModel', 'users');
	}

	public function index()
	{
		$this->employees->db
			->select('name')
			->join('units','units.id = employees.id_unit','left')
			->order_by('id','desc');
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
				$this->employees->db->like('fullname', $value);
			}
		}
		
		$data = $this->employees->all();
		echo json_encode(array(
			'data'	=> 	$data,
			'message'	=> 'Successfully Get Data Levels'
		));
	}

	public function get_user()
	{
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
				$this->users->db->like('username', $value);
			}
		}
		$this->users->db
			->select('employees.fullname')
			->join('employees','employees.id = users.id_employee','left')
			->order_by('users.id','DESC');
		$data = $this->users->all();
		echo json_encode(array(
			'data'	=> 	$data,
			'message'	=> 'Successfully Get Data Levels'
		));
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');
			$this->form_validation->set_rules('fullname', 'Nama', 'required');
			$this->form_validation->set_rules('nik', 'Nik', 'required|is_unique[employees.nik]');
			$this->form_validation->set_rules('birth_place', 'Tempat Lahir', 'required');
			$this->form_validation->set_rules('birth_date', 'Tanggal Lahir', 'required');
			$this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');
			$this->form_validation->set_rules('mobile', 'No Hp', 'required');
			$this->form_validation->set_rules('blood_group', 'Golongan Darah', 'required');
			$this->form_validation->set_rules('address', 'Alamat', 'required');
			$this->form_validation->set_rules('position', 'Jabatan', 'required');
			$this->form_validation->set_rules('id_cabang', 'Cabang', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'status'	=> false,
					'message'	=> validation_errors()
				));
			}
			else
			{
				$data = array(
					'marital'		=> $post['marital'],
					'nik'			=> $post['nik'],
					'id_unit'		=> array_key_exists("id_unit",$post) ? $post['id_unit'] : '',
					'id_cabang'		=> $post['id_cabang'],
					'fullname'		=> $post['fullname'],
					'birth_place'	=> $post['birth_place'],
					'birth_date'	=> $post['birth_date'],
					'gender'		=> $post['gender'],
					'mobile'		=> $post['mobile'],
					'blood_group'	=> $post['blood_group'],
					'address'		=> $post['address'],
					'position'		=> $post['position'],
					'no_rek'		=> $post['no_rek'],
					'masa_kerja'	=> $post['masa_kerja'],
					'join_date'		=> $post['join_date'],
					'no_employment'	=> $post['no_employment'],
					'bpjs_tk'		=> $post['bpjs_tk'],
					'bpjs_kesehatan'=> $post['bpjs_kesehatan'],
					'last_education'=> $post['last_education'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->employees->insert($data)){
					$employee = $this->employees->all();
					return $this->sendMessage($employee, 'Insert Employee Successfully',200);
				}else{
					return $this->sendMessage(false,'Failed Insert Employee',501);
				}

			}
		}else{
			return $this->sendMessage(false,'Request Should Post');
		}

	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');
			$this->form_validation->set_rules('nik', 'Nik', 'required');
			$this->form_validation->set_rules('fullname', 'Nama', 'required');
			$this->form_validation->set_rules('birth_place', 'Tempat Lahir', 'required');
			$this->form_validation->set_rules('birth_date', 'Tanggal Lahir', 'required');
			$this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');
			$this->form_validation->set_rules('mobile', 'No Hp', 'required');
			$this->form_validation->set_rules('blood_group', 'Golongan Darah', 'required');
			$this->form_validation->set_rules('address', 'Alamat', 'required');
			$this->form_validation->set_rules('id_cabang', 'Cabang', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'message'	=> 'Failed Insert Data Level'
				));
			}
			else
			{
				$id = $post['id'];
				$data = array(
					'id_unit'	=>  array_key_exists("id_unit",$post) ? $post['id_unit'] : '',
					'fullname'	=> $post['fullname'],
					'nik'	=> $post['nik'],
					'id_cabang'		=> $post['id_cabang'],
					'birth_place'	=> $post['birth_place'],
					'birth_date'	=> $post['birth_date'],
					'gender'	=> $post['gender'],
					'marital'	=> $post['marital'],
					'mobile'	=> $post['mobile'],
					'blood_group'	=> $post['blood_group'],
					'address'	=> $post['address'],
					'position'	=> $post['position'],
					'no_rek'	=> $post['no_rek'],
					'masa_kerja'	=> $post['masa_kerja'],
					'join_date'	=> $post['join_date'],
					'no_employment'	=> $post['no_employment'],
					'bpjs_tk'	=> $post['bpjs_tk'],
					'bpjs_kesehatan'	=> $post['bpjs_kesehatan'],
					'last_education'	=> $post['last_education'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->employees->update($data, $id)){
					return $this->sendMessage($this->employees->find($id),'Insert EMployee Successfully',200);
				}else{
					echo json_encode(array(
							'data'	=> 	false,
							'status'	=> false,
							'message'	=> 'Failed Update Data Level')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function show($id)
	{
		$data = $this->employees->db->select('employees.id, fullname,employees.id_cabang,units.id_area,employees.id_unit, nik,birth_date, birth_place,
		gender, mobile, marital,blood_group, address,position, masa_kerja,
		no_rek, last_education, bpjs_kesehatan, bpjs_tk,
		no_employment,join_date')
						->select('units.name as unit_name')
						->join('units','units.id = employees.id_unit')
						->select('cabang.cabang as cabang')
						->join('cabang','cabang.id = units.id_cabang','left')
						->select('areas.id as id_area')
						->join('areas','areas.id = cabang.id_area')
						->where('employees.id', $id)
						->get('employees')->row();

		if($data){
			echo json_encode(array(
				'data'	=> 	$data,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data Level'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> 	false,
				'message'	=> $id. ' Not Found'
			));
		}
	}
	public function delete($id)
	{
		if($this->employees->delete($id)){
			echo json_encode(array(
				'data'	=> 	true,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data Level'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> false,
				'message'	=> 'Request Error Should Method Post'
			));
		}
	}


}
