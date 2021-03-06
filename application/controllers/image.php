<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends CI_Controller {
    public function images($artist_id, $year, $quarter)
    {
        $this->load->model('Image_model', '', TRUE);
        echo $this->Image_model->get_image_info_for_artist_year_and_quarter($artist_id, $year, $quarter);
    }

    public function image_view($id)
    {
        $this->load->model('Image_model', '', TRUE);
        $data = array("image_data" => $this->Image_model->get_image_info_for_id($id));
        $this->load->view('image_view', $data);
    }

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
    
    public function process_all()
    {
        $config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '2000';
		$config['max_height']  = '2000';
    
        $this->load->helper('form'); 
		$this->load->library('upload', $config);
        
        $this->load->model('Image_model', '', TRUE);
        $this->Image_model->process_all_entries();
        $this->load->view('welcome_message', "DONE");
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */