<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sample extends CI_Controller {

	public function index()
	{
		$data['main_content'] = 'student/home';
		$this->load->view('includes/template');
	}
}
