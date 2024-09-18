# CodeIgniter REST API Setup

This guide will walk you through setting up two CodeIgniter projects: the first project acts as an API Provider (exposing data via a REST API), and the second project acts as an API Consumer (fetching data from the first project's API).

---

## **Project 1: API Provider**

### Step 1: Download CodeIgniter
- Download CodeIgniter from the official site: [CodeIgniter Downloads](https://codeigniter.com/userguide3/installation/downloads.html).

### Step 2: Setup in XAMPP
- Extract the CodeIgniter files and copy them to your XAMPP `htdocs` folder, for example:
  - `C:\xampp\htdocs\6_api\funda_api_s1\application\controllers\Welcome.php`
  - `C:\xampp\htdocs\1_api`

- To access the project in the browser:
  - `http://localhost/1_api/funda/`

### Step 3: Download Required Files
Download the following REST API library files for CodeIgniter:
1. `RestController.php`
2. `Rest_controller_lang.php`
3. `Rest.php`
4. `Format.php`

You can download them from this [CodeIgniter RESTful API Tutorial](https://www.fundaofwebit.com/post/codeigniter-3-restful-api-tutorial-using-postman#google_vignette).

### Step 4: Place the Files
- Copy the `rest_controller_lang.php` file to the following folder:
  - `C:\xampp\htdocs\6_api\funda_api_s1\application\language\english\rest_controller_lang.php`
  
- Copy the `rest.php` file to the following path:
  - `C:\xampp\htdocs\6_api\funda_api_s1\application\config\rest.php`
  
- Copy the `RestController.php` and `Format.php` files to:
  - `C:\xampp\htdocs\6_api\funda_api_s1\application\libraries\RestController.php`
  - `C:\xampp\htdocs\6_api\funda_api_s1\application\libraries\Format.php`

### Step 5: Verify Composer Installation
- Open a new terminal and run `composer` to check if Composer is installed.
  
- If Composer is installed, run the following command to serve the application:
  ```bash
  php -S localhost:8000
