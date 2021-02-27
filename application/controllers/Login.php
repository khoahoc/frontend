<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    private function strapiVerifyAccount($email, $password)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->config->item('strapi') . 'auth/local',
            CURLOPT_USERAGENT => 'Front-end SudoSys',
            CURLOPT_POST => 1,
            CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm SSL
            CURLOPT_POSTFIELDS => http_build_query(array(
                'identifier' => $email,
                'password' => $password
            ))
        ));
        $resp = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($resp, true);
        if (isset($data['jwt']) && $data['jwt'] != NULL) {

            $session = array(
                'account' => $data
            );
            $this->session->set_userdata($session);

            $this->message_model->pushAlertMessage('success', "You have successfully logged in to system. Now you can start to explore!");
            redirect('admin/dashboard');
        } elseif ($data == '') {
            $this->message_model->pushAlertMessage('warning', "Backend currently not available! Please try again later.");
        } else
            $this->message_model->pushAlertMessage('warning', $data['message'][0]['messages'][0]['message']);
        redirect('login');
    }

    public function verify()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // User input
            $email = $this->input->post('login-email');
            $password = $this->input->post('login-password');

            // Checking
            $this->form_validation->set_rules('login-email', 'Email', 'trim|required|min_length[5]|max_length[50]|valid_email');
            $this->form_validation->set_rules('login-password', 'Password', 'trim|required|min_length[5]|max_length[255]');

            // Handle
            if ($this->form_validation->run() == TRUE) {
                $this->strapiVerifyAccount($email, $password);
                $this->message_model->pushAlertMessage('success', 'Welcome back Mr. Browser');
                redirect('admin/dashboard');
            } else {
                $this->message_model->pushAlertMessage('warning', validation_errors());
                redirect('login');
            }
        } else {
            $this->message_model->pushAlertMessage('warning', 'We doesn\'t support GET method for this URL! 🤞');
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('account');
        $this->message_model->pushAlertMessage('success', "Log out system successfully!");
        redirect('login');
    }
}

/* End of file Login.php */