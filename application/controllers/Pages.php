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

    private function getAssets($page)
    {
        switch ($page) {
            case 'dashboard':
                $data['assets'] = array(
                    'cssesVendor' => [
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/vendors.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/charts/apexcharts.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/extensions/toastr.min.css">'
                    ],
                    'csses' => [
                        '<link rel="stylesheet" type="text/css" href="/app-assets/css/core/menu/menu-types/vertical-menu.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/css/pages/dashboard-ecommerce.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/charts/chart-apex.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/extensions/ext-component-toastr.min.css">'
                    ],
                    'jsesVendor' => [
                        '<script src="/app-assets/vendors/js/charts/apexcharts.min.js"></script>',
                        '<script src="/app-assets/vendors/js/extensions/toastr.min.js"></script>',
                    ],
                    'jses' => [
                        # '<script src="../../../app-assets/js/scripts/pages/dashboard-ecommerce.min.js"></script>', // Notification & Data Graphic
                    ]
                );
                break;
            case 'devices':
                $data['assets'] = array(
                    'cssesVendor' => [
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/vendors.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">',
                        '<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">'
                    ],
                    'csses' => [
                        '<link rel="stylesheet" type="text/css" href="/app-assets/css/core/menu/menu-types/vertical-menu.min.css">',
                    ],
                    'jsesVendor' => [
                        '<script src="/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/jszip.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>',
                        '<script src="/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>',
                        '<script src="/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>',
                    ],
                    'jses' => [
                        // '<script src="/app-assets/js/scripts/tables/table-datatables-basic.min.js"></script>'


                    ],

                );
                break;
            default:
                $data['assets'] = array(
                    'csses' => ['null'],
                    'jses' => ['null']
                );
                break;
        }
        return $data['assets'];
    }

    private function checkSession()
    {
        if (!$this->session->userdata('account')) {
            $this->message_model->pushAlertMessage('warning', 'Session expired! Please try to login again!');
            redirect('login');
        }
    }


    public function view_admin($page = 'dashboard')
    {
        // Check session
        $this->checkSession();
        // Grant variable when session existed
        $data['session_account'] = $this->session->userdata('account');

        // Get current user information
        $data['me'] = $this->strapi_model->getApi($this->session->userdata('account')['jwt'], 'users/me');
        // Get page CSS & JS
        $data['assets'] = $this->getAssets($page);
        // Get current page
        $data['page'] = $page;

        // debug
        // print_r($data['assets']['devices']);

        // Load view
        $this->load
            ->view('admin/template/top', $data)
            ->view('admin/' . $page, $data)
            ->view('admin/template/bot', $data);
    }

    public function view_home()
    {
        $data = '';
        $this->load->view('home/home', $data, FALSE);
    }
}

/* End of file Pages.php */
