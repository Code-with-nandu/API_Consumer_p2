<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiClientController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Method to display the API key input form
    public function api_key_form()
    {
        $this->load->view('api_key_form');
    }

    public function get_users()
    {
        // API URL of the First Project (API Provider)
        $url = "http://localhost/1_api/API_Provider_p2/index.php/ApiDemo/users";

        // API Key provided by the user through a form
        $api_key = $this->input->post('api_key'); // Getting API key from form input

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: ' . $api_key // Include API key in the header
        ));

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Redirect to the error page with an error message
            $this->session->set_flashdata('error', "Request Error: " . $error);
            redirect('client/error_page');
        } else {
            curl_close($ch);

            // Decode the API response
            $api_response = json_decode($response, true);

            if ($api_response && isset($api_response['status']) && !$api_response['status']) {
                // If API response indicates failure, redirect to error page
                $this->session->set_flashdata('error', $api_response['error'] ?? "Invalid API key");
                redirect('client/error_page');
            } elseif ($api_response) {
                // Pass the API response to the view
                $data['users'] = $api_response;
                $data['error'] = null;
                $this->load->view('users_view', $data);
            } else {
                // If the API response is invalid, redirect to error page
                $this->session->set_flashdata('error', "Invalid API response");
                redirect('client/error_page');
            }
        }
    }

    // Method for loading the error page
    public function error_page()
    {
        // Load the error page view
        $this->load->view('error_page');
    }
}
