<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
----------------------
Created by : Lathiif Aji Santhosho
Phone : 	082126641201
----------------------
*/

class Contact extends MY_Controller 
{
    // constructor
	public function __construct()
	{
		parent::__construct();
		
		//load models
		$this->load->model('client/M_kontak');
        
		/*-------------------------------------------------------
		 kosongkan isi parsing js dan css jika tidak digunakan,
		contoh:
		$this->parsing_js([

			]);
		--------------------------------------------------------*/
		//parsing js url
		$this->parsing_js([
		   ]);
		//parsing css url
		$this->parsing_css([
			]);

		//parsing data tanpa template
		$this->parsing([
			'title' => 'Kontak'
		]);
	}

	public function index()
	{
		// set page rules
		$this->_set_page_rule("R");
		//default notif
		$notif = $this->session->userdata('sess_notif');
		//get data
		$result = $this->M_kontak->get_all();
		$data = [
			'result' => $result
		];

		//delete session notif
		$this->session->unset_userdata('sess_notif');
		//parsing (template_content, variabel_parsing)
		$this->parsing_template('setclient/contact/index', $data);
	}

	public function edit($user_id='')
	{
		// set page rules
		$this->_set_page_rule("U");
		//default notif
		$notif = $this->session->userdata('sess_notif');
		//cek data
		if (empty($user_id)) {
			// default error
			$this->notif_msg('master/user', 'Error', 'Data tidak ditemukan !');
		}
		// get all role
		$all_role = $this->M_user->get_all_role();
		//parsing
		$data = [
			'tipe'		=> $notif['tipe'],
			'pesan' 	=> $notif['pesan'],
			'result' 	=> $this->M_user->get_by_id($user_id),
			'roles'		=> $all_role	
		];
		//delete session notif
		$this->session->unset_userdata('sess_notif');
		//parsing and view content
		$this->parsing_template('master/user/edit', $data);
	}

	// edit process
	public function edit_process() {
		// set page rules
		$this->_set_page_rule("U");
        // cek input
        $this->form_validation->set_rules('user_mail', 'User Email', 'trim|required|valid_email|max_length[50]');
		$this->form_validation->set_rules('user_name', 'User Name', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('jns_kelamin', 'Jenis Kelamin', 'trim|required|max_length[1]');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('user_st', 'Status', 'trim|required|max_length[1]');
        $this->form_validation->set_rules('role_id', 'Hak Akses', 'required');
		// check data
        if (empty($this->input->post('user_id'))) {
            //sukses notif
			$this->notif_msg('master/user', 'Error', 'Data tidak ditemukan');
		}
		$user_id = $this->input->post('user_id');
        // process
        if ($this->form_validation->run() !== FALSE) {
			//with password or no
			if (!empty($this->input->post('user_pass'))) {
				$password_key = abs(crc32($this->input->post('user_pass', true)));
				$password = $this->encrypt->encode(md5($this->input->post('user_pass', true)), $password_key);
				// parameter
				// $params = array($this->input->post('user_name'), $password, $password_key, $this->input->post('user_st'), $this->input->post('user_mail'), $this->com_user['user_id']);
				$params = array(
					'user_name'	=> $this->input->post('user_name'), 
					'user_pass'	=> $password, 
					'user_key' 	=> $password_key, 
					'user_st'	=> $this->input->post('user_st'), 
					'user_mail'	=> $this->input->post('user_mail'),
					'mdd'		=> date('Y-m-d H:i:s') 
				);
			}else{
				$params = array(
					'user_name'	=> $this->input->post('user_name'),
					'user_st'	=> $this->input->post('user_st'), 
					'user_mail'	=> $this->input->post('user_mail')
				);
			}
			$where = array(
				'user_id'	=> $user_id
			);
            // insert
            if ($this->M_user->update('com_user', $params, $where)) {
                // insert to users
                $params = array(
					'nama'			=> 	$this->input->post('nama'), 
					'alamat'		=>	$this->input->post('alamat'), 
					'jns_kelamin'	=>	$this->input->post('jns_kelamin')
				);
                $this->M_user->update('user', $params, $where);
				// insert hak akses
				$params = array(
					'role_id'		=> 	$this->input->post('role_id')
				);
                $this->M_user->update('com_role_user', $params, $where);
				//sukses notif
				$this->notif_msg('master/user/edit/'.$user_id, 'Sukses', 'Data berhasil diedit');
            } else {
				// default error
				$this->notif_msg('master/user/edit/'.$user_id, 'Error', 'Data gagal diedit');
            }
        } else {
			// default error
			$this->notif_msg('master/user/edit/'.$user_id, 'Error', 'Data gagal diedit');
        }
    }
}
