<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Type Interface
 * @Name htmlview
 * @Param $htmlView,$data
 * @Date 05/11/2017
 * @Desc used for call html view from ci and thier data at a single time
 * */

interface htmlView{


     function html($htmlView,$data);

}

class View_Manager extends MX_Controller implements htmlView {

    public function __construct()
    {

        parent::__construct();
        $this->load->library('upload');
        $this->load->model('Admin_model');
        $this->load->language('common_lang');
        $this->load->library('form_validation');

    }

    /**
     * @Name html
     * @Date 06/11/2017
     * @Param  $htmlView,$data
     * @Desc all view in home or user controller will call this function and load view
     * @Return html ci view with data array passed into views
     * */

    public function html($htmlView,$data = '')
    {

     try {
         if (isset($htmlView) && !empty($htmlView)) {

             $this->load->view('internal_header', $data);
             $this->load->view($htmlView);
             $this->load->view('internal_footer');


         } else {


             return show_404();
         }
     }catch (Exception $ex){

         return $ex->getMessage();
     }


    }

    /**
     * @Name uploadImage
     * @Date 13/11/2017
     * @Param  $imagename
     * @Desc all images add and edit from user controller will call here to upload images and return uploaded url to calling function
     * @Return image url and errors if possible
     * */

    public function uploadImage($imagename = '')
    {
        try {
            if (!empty($imagename)) {

                if ($imagename == 'tips_image') {
                    $config['upload_path'] = './public/tips_images';

                }elseif ($imagename == 'bannerimg'){

                    $config['upload_path'] = './public/banner';

                } else {
                    $config['upload_path'] = './public/user_images';
                }

                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_ext_tolower'] = TRUE;
                $config['max_size'] = 5120;
                $config['remove_spaces'] = TRUE;
                $config['detect_mime'] = TRUE;
                $randname = date('Y-m-d h:i:s ssss');
                $config['file_name'] = str_replace(':', '', str_replace('-', '', $randname)) . '_Checkiodds';

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
        }catch ( Exception $ex){

            return $ex->getMessage();
        }
    }

}