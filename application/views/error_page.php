<!DOCTYPE html>
<html>
<head>
    <title>Error Page</title>
</head>
<body>
    <h1>An Error Occurred</h1>
    <p style="color: red;">
        <?php echo $this->session->flashdata('error'); ?>
    </p>
    <p>Please try again later or contact support.</p>
</body>
</html>
