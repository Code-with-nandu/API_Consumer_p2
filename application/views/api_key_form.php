<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Key and Credentials</title>
    <style>
        body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    /* background-image: url('your-background-image.jpg'); */
     /* Replace with your image path */
    background-image: url('<?php echo base_url('assets/image/images.jpg'); ?>');
    background-size: cover;
    background-position: center;
    color: white; /* Change text color for contrast */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
}

form {
    background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background for the form */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
}

label {
    display: block;
    margin: 10px 0 5px;
}

input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    background-color: #fff; /* White background for inputs */
    color: #333; /* Dark text for inputs */
}

button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #28a745; /* Green background */
    color: white; /* White text */
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #218838; /* Darker green on hover */
}

p {
    text-align: center;
    color: red; /* Error message color */
}

    </style>
</head>

<body>

    <form action="<?php echo site_url('ApiClientController/authenticate'); ?>" method="post">
    <h2>Enter API Key and Credentials to Fetch Users</h2>
        <label for="api_key">API Key:</label>
        <input type="text" id="api_key" name="api_key" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Fetch Users</button>
    </form>

    <?php if($this->session->flashdata('error')): ?>
        <p style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>
</body>
</html>
