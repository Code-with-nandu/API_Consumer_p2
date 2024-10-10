<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <style>
        /* General Page Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f7f7f7;
            color: #333;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Button Styling */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .view-btn {
            background-color: #4CAF50;
        }

        .update-btn {
            background-color: #FFA500;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .btn:hover {
            opacity: 0.85;
        }

        /* Link Styling inside buttons */
        .btn a {
            color: white;
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            table, th, td {
                font-size: 14px;
            }
        }
        .button-container {
        text-align: right; /* Align button container to the right */
        margin-top: 20px; /* Add some space above the button */
    }
        .add-employee-button {
        background-color: #4CAF50; /* Green background */
        border: none; /* Remove border */
        color: white; /* White text */
        padding: 15px 32px; /* Top/bottom and left/right padding */
        text-align: center; /* Center the text */
        text-decoration: none; /* Remove underline from link */
        display: inline-block; /* Make it behave like a button */
        font-size: 16px; /* Increase font size */
        margin: 4px 2px; /* Add some margin */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 4px; /* Rounded corners */
        transition: background-color 0.3s; /* Smooth transition */
    }

    .add-employee-button:hover {
        background-color: #45a049; /* Darker green on hover */
    }

    .add-employee-button a {
        color: white; /* White text for link */
        text-decoration: none; /* Remove underline */
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>List of Users</h1>

        <h1 class="button-container">
            <button class="add-employee-button">
                <a href="<?php echo base_url().'client/store'; ?>">Add Employee</a>
            </button>
        </h1>
        
        <!-- Check if there are users to display -->
        <?php if (!empty($users)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Details</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sort users in descending order based on ID -->
                    <?php 
                    // Sort users array in descending order
                    usort($users, function($a, $b) {
                        return $b['id'] - $a['id'];
                    });
                    ?>
                    <!-- Loop through users and display each one -->
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <a href="<?php echo base_url().'get-employee/'.$user['id']; ?>" class="btn view-btn">View</a>
                            </td>
                            <td>
                                <a href="<?php echo base_url().'load-update-form/'.$user['id']; ?>" class="btn update-btn">Update</a>
                            </td>
                            <td>
                                <a href="<?php echo base_url().'delete_employee/'.$user['id']; ?>" class="btn delete-btn">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p style="text-align: center; font-size: 1.2rem;">No users found.</p>
        <?php endif; ?>
    </div>
</body>


</html>
