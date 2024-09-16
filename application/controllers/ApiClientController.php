<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiClientController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('curl'); // Load cURL library
        $this->load->helper('url');
        $this->load->library('session');
    }

    // Method to display the API key input form
    public function api_key_form()
    {
        $this->load->view('api_key_form');
    }

    // Method to store API credentials in session (one-time authentication)
    public function authenticate()
    {
        // Get API key, username, and password from the form
        $api_key = $this->input->post('api_key');
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Store credentials in session
        $this->session->set_userdata('api_key', $api_key);
        $this->session->set_userdata('username', $username);
        $this->session->set_userdata('password', $password);

        // Redirect to the users list after authentication
        redirect('ApiClientController/get_users');
    }

    // Method to fetch users (uses session-stored credentials)
    public function get_users()
    {
        // Check if API credentials are stored in session
        if (!$this->session->userdata('api_key') || !$this->session->userdata('username') || !$this->session->userdata('password')) {
            $this->session->set_flashdata('error', 'Please authenticate first');
            redirect('ApiClientController/api_key_form');
        }

        // API URL of the First Project (API Provider)
        $url = "http://localhost/1_api/API_Provider_p2/index.php/ApiDemo/users";

        // Retrieve API credentials from session
        $api_key = $this->session->userdata('api_key');
        $username = $this->session->userdata('username');
        $password = $this->session->userdata('password');

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: ' . $api_key // Include API key in the header
        ));

        // Set Basic Authentication credentials
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password); // Set username and password for Basic Auth
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Enable Basic Auth

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Redirect to the error page with an error message
            $this->session->set_flashdata('error', "Request Error: " . $error);
            redirect('ApiClientController/error_page');
        } else {
            curl_close($ch);

            // Decode the API response
            $api_response = json_decode($response, true);

            if ($api_response && isset($api_response['status']) && !$api_response['status']) {
                // If API response indicates failure, redirect to error page
                $this->session->set_flashdata('error', $api_response['error'] ?? "Invalid API key or credentials");
                redirect('ApiClientController/error_page');
            } elseif ($api_response) {
                // Pass the API response to the view
                $data['users'] = $api_response;
                $this->load->view('users_view', $data);
            } else {
                // If the API response is invalid, redirect to error page
                $this->session->set_flashdata('error', "Invalid API response");
                redirect('ApiClientController/error_page');
            }
        }
    }

    // Method to store an employee (uses session-stored credentials)
    public function storeEmployee()
    {
        // Check if API credentials are stored in session
        if (!$this->session->userdata('api_key') || !$this->session->userdata('username') || !$this->session->userdata('password')) {
            $this->session->set_flashdata('error', 'Please authenticate first');
            redirect('ApiClientController/api_key_form');
        }

        // Data to be sent to the API
        $data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'phone'      => $this->input->post('phone'),
            'email'      => $this->input->post('email'),
        ];

        // cURL configuration for POST request to the API
        $url = 'http://localhost/1_api/API_Provider_p2/index.php/api/store'; // API endpoint

        // Retrieve API credentials from session
        $api_key = $this->session->userdata('api_key');
        $username = $this->session->userdata('username');
        $password = $this->session->userdata('password');

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options for POST request with data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Send data

        // Set cURL headers for API key and Basic Authentication
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-API-KEY: ' . $api_key, // Include API key in the header
            'Content-Type: application/x-www-form-urlencoded' // Set content type
        ));

        // Set Basic Authentication credentials
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password); // Set username and password for Basic Auth
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Enable Basic Auth

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Redirect to the error page with an error message
            $this->session->set_flashdata('error', "Request Error: " . $error);
            redirect('ApiClientController/error_page');
        } else {
            curl_close($ch);

            // Decode the API response
            $result = json_decode($response);

            // Check the response status and set flash messages accordingly
            if ($result && isset($result->status) && $result->status) {
                $this->session->set_flashdata('success', $result->message);
            } else {
                $this->session->set_flashdata('error', $result->message ?? "Unauthorized");
            }
        }

        // Redirect to the form page
        redirect('ApiClientController/get_users');
    }

    // Method for loading the error page
    public function error_page()
    {
        // Load the error page view
        $this->load->view('error_page');
    }

    // Method for displaying the employee form
    public function form()
    {
        $this->load->view('employee_form');
    }
}
