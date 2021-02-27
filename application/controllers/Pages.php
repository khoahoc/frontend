<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{

    public function view_login()
    {
        if ($this->session->userdata('account'))
            redirect('admin/dashboard');

        $data = '';
        $this->load->view('login/login', $data, FALSE);
    }

    public function view_admin($page = 'dashboard')
    {
        if (!$this->session->userdata('account')) {
            $this->message_model->pushAlertMessage('warning', 'Please try to login again!');
            redirect('login');
        }

        // print_r($_SESSION);
        // die();

        // Set required HTTP headers
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->session->userdata('account')['jwt']
        );

        // Make the call
        $curl = curl_init($this->config->item('strapi') . 'users/me');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data_raw = curl_exec($curl);
        curl_close($curl);
        $data['me'] = json_decode($data_raw, true);

        $data['session_account'] = $this->session->userdata('account');
        $this->load->view('admin/' . $page, $data, FALSE);
    }

    public function view_home()
    {
        $data = '';
        $this->load->view('home/home', $data, FALSE);
    }
}

/* End of file Pages.php */
