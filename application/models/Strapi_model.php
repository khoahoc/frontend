<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Strapi_model extends CI_Model
{
    public function getApi($jwt, $api, $json = FALSE)
    {
        // Set required HTTP headers
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $jwt
        );

        // Make the call
        $curl = curl_init($this->config->item('strapi') . $api);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data_raw = curl_exec($curl);
        curl_close($curl);
        if ($json) {
            $data_array =  json_decode($data_raw);
            return json_encode($data_array);
        } else
            return $data = json_decode($data_raw, true);
    }
}

/* End of file Strapi_model.php */
