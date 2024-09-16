<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Key and Credentials</title>
</head>
<body>
    <h2>Enter API Key and Credentials to Fetch Users</h2>
    <form action="<?php echo site_url('ApiClientController/get_users'); ?>" method="post">
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
