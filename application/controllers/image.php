<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends CI_Controller {

	public function analyze()
	{
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '2000';
		$config['max_height']  = '2000';

        $this->load->helper('form'); 
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('welcome_message', $error);
		}
		else
		{
            $this->load->model('Image_model');

			$data = array('upload_data' => $this->upload->data());
            $this->Image_model->init($data["upload_data"]["full_path"]);
            
            $data["upload_data"]["average_color"] = $this->Image_model->get_average_color();
            $this->Image_model->create_hsb_histogram();
            $data["upload_data"]["top_colors"] = $this->Image_model->analyze_color_histogram();
			$this->load->view('image_uploaded', $data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */