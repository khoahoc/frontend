<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Devices extends CI_Controller
{

    public function table_devices()
    {
        $devices = $this->strapi_model->getApi($this->session->userdata('account')['jwt'], 'devices', FALSE);
        for ($i = 0; $i < count($devices); $i++) {
            $data['data'][$i] = array(
                'responsive_id' => '',
                'device_id' => $devices[$i]['id'],
                'device_name' => $devices[$i]['name'],
                'device_model_name' => $devices[$i]['device_model']['name'],
                'device_category_name' => $devices[$i]['device_category']['name'],
                'device_used_reason' => $devices[$i]['device_use']['reason'],
                'device_status'     => $devices[$i]['device_status']['name'],
            );
        }
        $table_data = $data;
        print_r(json_encode($table_data));
    }
}

/* End of file Devices.php */
