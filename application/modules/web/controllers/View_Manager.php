<?php

defined('BASEPATH') OR exit('No direct script access allowed');

interface htmlView{


     function html($htmlView,$data);

}

class View_Manager extends MX_Controller implements htmlView {

    public function __construct()
    {

        parent::__construct();

        $this->load->library('upload');

        $this->load->language('common_lang');
        $this->load->library('form_validation');

    }

    public function html($htmlView,$data = '')
    {
        try {

            $tipsArr=$this->Web_model->fetch_data('tbl_free_tips','id,title,image',array('order_by'=>array('priorities'=>'asc'),'limit'=>array('6')),false);
            $data['tips_arr']=$tipsArr;

            if (isset($htmlView) && !empty($htmlView)) {

                $this->load->view('external_header',$data);
                $this->load->view($htmlView);
                $this->load->view('external_footer');


            } else {


                return show_404();
            }
        }catch (Exception $ex){

            return $ex->getMessage();
        }


    }

    public function uploadImage($imagename = '')
    {

        try {


            if (!empty($imagename)) {
                $date = date('Y-m-d h:i:s ssss');
                $imgname = strtotime($date);
                $this->load->library('upload');
                $config['upload_path'] = './public/user_images';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['file_ext_tolower'] = TRUE;
                $config['max_size'] = 5120;
                $config['remove_spaces'] = TRUE;
                $config['detect_mime'] = TRUE;
                $config['file_name'] = $imgname . '_Checkiodds';

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload($imagename)) {
                    $data = array('upload_data' => $this->upload->data());

                    return ($data['upload_data']['orig_name']);
                } else {

                    $error = array('error' => $this->upload->display_errors());

                    return $error;
                }
            } else {

                return FALSE;
            }

        }catch (Exception $ex){

            return $ex->getTrace();
        }
    }

}