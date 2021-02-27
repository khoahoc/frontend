<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sshconfig extends CI_Controller
{
    private function sshConfigTemplate($hostname = 'null', $wan_ip = '1.1.1.1', $username = 'root', $port = '22')
    {
        return "
        Host $hostname 
        Hostname $wan_ip 
        User $username 
        Port $port
        ";
    }

    public function download()
    {
        if ($this->session->userdata('account'))
            $token = $this->session->userdata('account')['jwt'];
        else {
            $this->message_model->pushAlertMessage('warning', 'Please login again!');
            redirect('login');
        }

        // Set required HTTP headers
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        );

        // print_r($token);

        // Make the call
        $curl = curl_init($this->config->item('strapi') . 'servers');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $servers_raw = curl_exec($curl);
        curl_close($curl);
        $servers = json_decode($servers_raw, true);
        // print_r($servers);
        // // print_r($servers_raw);
        // die();
        foreach ($servers as $server) {
            echo $this->sshConfigTemplate($server['hostname'], $server['wan_ip'], $server['username'], $server['ssh_wan_port']);
        }
        header("Content-type: text/none");
        header("Content-disposition: inline; filename=ssh_config");
    }
}


/* End of file Sshconfig.php */
