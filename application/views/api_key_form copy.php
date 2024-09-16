<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter API Key</title>
</head>
<body>

    <h1>Enter API Key to Fetch Users</h1>

    <form action="<?php echo base_url('client/get_users'); ?>" method="post">
        <label for="api_key">API Key:</label>
        <input type="text" id="api_key" name="api_key" required>
        <button type="submit">Submit</button>
    </form>

    <!-- Display any error messages -->
    <?php if ($this->session->flashdata('error')): ?>
        <p style="color:red;"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

</body>
</html>
