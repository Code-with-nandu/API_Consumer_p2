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
    public function register() {
        // Load registration form view
        $this->load->view('register_view');
    }
    public function registerUser() {
        // Define the API endpoint for registration
        // $apiUrl = "http://localhost/1_api/API_Provider_p2/index.php/api/Auth_Controller/register";
        $apiUrl = "https://krishnendudalui.in.net/API_Provider_p2/index.php/api/Auth_Controller/register";

        // Data to send in the POST request
        $postData = [
            'name'     => $this->input->post('name'),
            'email'    => $this->input->post('email'),
            'password' => $this->input->post('password')
        ];

        // Initialize cURL request
        $ch = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Handle cURL errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            $this->session->set_flashdata('error', 'cURL Error: ' . $error_msg);
        } elseif ($httpCode != 200) {
            $this->session->set_flashdata('error', 'HTTP Error: ' . $httpCode);
        } else {
            // Decode the API response
            $responseData = json_decode($response, true);

            if ($responseData['status'] === true) {
                $this->session->set_flashdata('success', 'Successfully registered!');
            } else {
                $this->session->set_flashdata('error', $responseData['message']);
            }
        }

        // Close cURL
        curl_close($ch);

        // Redirect or load view after response
        redirect(base_url('ApiClientController/register'));
    }
  
    public function showLoginForm() {
        // Load the login form view
        $this->load->view('login_form');
    }

    
        public function login() {
            // Get form inputs from user
            $email = $this->input->post('email');
            $password = $this->input->post('password');
    
            // Prepare data to send to the First Project (API Provider)
            $loginData = array(
                'email' => $email,
                'password' => $password
            );
    
            // Convert the data array to JSON format
            $jsonLoginData = json_encode($loginData);
    
            // Initialize cURL for sending POST request
            $ch = curl_init();
    
            // Set the URL of the First Project's login endpoint
            // curl_setopt($ch, CURLOPT_URL, "http://localhost/1_api/API_Provider_p2/index.php/api/Auth_Controller/login");
            curl_setopt($ch, CURLOPT_URL, "https://krishnendudalui.in.net/API_Provider_p2/index.php/api/Auth_Controller/login");

            // Set options for cURL request
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonLoginData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json', // Set content type to JSON
                'Accept: application/json'
            ));
    
            // Execute cURL request and get the response
            $response = curl_exec($ch);
    
            // Close the cURL session
            curl_close($ch);
    
            // Decode the JSON response from the API
            $responseData = json_decode($response, true);
    
            // Check if the login was successful based on the API's response
            if ($responseData['status']) {
                // If login is successful, store the token in the session
                $token = $responseData['token'];
                $this->session->set_userdata('token', $token);
    
                // Set a success message and redirect to a dashboard or home page
                $this->session->set_flashdata('success', 'Login successful! Token stored.');
                redirect('ApiClientController/get_users');
            } else {
                // If login failed, show the error message from the API response
                $this->session->set_flashdata('error', $responseData['message']);
                redirect('ApiClientController/showLoginForm');
            }
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
        // $url = "http://localhost/1_api/API_Provider_p2/index.php/ApiDemo/users";
        $url = "https://krishnendudalui.in.net/API_Provider_p2/index.php/ApiDemo/users";

     

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
        // $url = 'http://localhost/1_api/API_Provider_p2/index.php/api/store'; // API endpoint
        $url = 'https://krishnendudalui.in.net/API_Provider_p2/index.php/api/store'; // API endpoint

        

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

    // Method to fetch employee details by ID
    public function getEmployeeById($id)
    {
        // Check if API credentials are stored in session
        if (!$this->session->userdata('api_key') || !$this->session->userdata('username') || !$this->session->userdata('password')) {
            $this->session->set_flashdata('error', 'Please authenticate first');
            redirect('ApiClientController/api_key_form');
        }

        // API URL of the First Project (API Provider)
        // $api_url = 'http://localhost/1_api/API_Provider_p2/index.php/api/find/' . $id;

        $api_url = 'https://krishnendudalui.in.net/API_Provider_p2/index.php/api/find/' . $id;
        

        // Retrieve authentication data from session
        $api_key = $this->session->userdata('api_key');
        $username = $this->session->userdata('username');
        $password = $this->session->userdata('password');

        // Initialize cURL
        $ch = curl_init($api_url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: ' . $api_key // Include API key in the header
        ));

        // Set Basic Authentication credentials
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password); // Basic Auth
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Handle failure case and load error view
            $data['error'] = 'Request Error: ' . $error;
            $this->load->view('error_view', $data);
        } else {
            curl_close($ch);

            // Decode the JSON response
            $data['employee'] = json_decode($response, true);

            // Check if the employee data is found
            if (!empty($data['employee'])) {
                // Load the view to display the employee details
                $this->load->view('employee_view', $data);
            } else {
                // Handle employee not found
                $data['error'] = 'Employee not found.';
                $this->load->view('error_view', $data);
            }
        }
    }  public function load_update_form()
    {
        $this->load->view('update_employee_view'); // Load the form view
    }

    // Function to handle form submission and update employee via API
    public function update_employee()
    {
        // Get form data
        $id = $this->input->post('id');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');

        // Prepare data to send to the API Provider     
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'email' => $email
        );

        // API endpoint (URL of the First Project's API)
        // $url = "http://localhost/1_api/API_Provider/index.php/api/update/" . $id;

        $url = "https://krishnendudalui.in.net/API_Provider_p2/index.php/api/update/" . $id;

        // Initialize cURL
        $ch = curl_init($url);

        // Convert data array to JSON
        $json_data = json_encode($data);

        // Set cURL options for a PUT request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Load error view if there is an issue
            $data['message'] = $error;
            $this->load->view('error_view', $data);
        } else {
            curl_close($ch);

            // Decode the API response
            $api_response = json_decode($response, true);

            // Check the response status from the API
            if ($api_response['status'] === true) {
                $this->session->set_flashdata('success', $api_response['message']);
                redirect('client/get_users'); // Redirect to the URL you specified
            } else {
                $this->session->set_flashdata('error', $api_response['message']);
                redirect('client/error'); // Redirect to an error page
            }
        }
    }



    public function delete_employee($id)
    {
        // API endpoint (URL of the First Project's API)
        // $url = "http://localhost/1_api/API_Provider/index.php/api/delete/" . $id;
        $url = "https://krishnendudalui.in.net/API_Provider_p2/index.php/api/delete/" . $id;



        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options for a DELETE request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Redirect to an error page with an error message
            $this->session->set_flashdata('error', $error);
            redirect('client/error');
        } else {
            curl_close($ch);

            // Decode the API response
            $api_response = json_decode($response, true);

            // Check the response status from the API
            if ($api_response['status'] === true) {
                $this->session->set_flashdata('success', $api_response['message']);
                redirect('client/get_users'); // Redirect to the URL you specified
            } else {
                $this->session->set_flashdata('error', $api_response['message']);
                redirect('client/error'); // Redirect to an error page
            }
        }
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
