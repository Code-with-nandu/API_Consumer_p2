
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AuthHook
{
    public function checkAuth()
    {
        $CI =& get_instance();
        $CI->load->library('session');
        
        // Check if the session has the authenticated credentials
        if (!$CI->session->userdata('authenticated')) {
            redirect('client/error_page');
        }
    }
}
