<?php
session_start();
include '../database/config.php';

// Fetch all users
$usersQuery = mysqli_query($con, "SELECT * FROM users");

// Add User
if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    mysqli_query($con, "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");
    header("Location: admin_users.php");
    exit();
}

// Update User
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = $_POST['role'];

    mysqli_query($con, "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id");
    header("Location: admin_users.php");
    exit();
}

// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM users WHERE id = $id");
    header("Location: admin_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card { 
            border: none; 
            border-radius: 10px; 
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-custom { 
            background-color: #05a39c; 
            color: white; 
            border: none;
            transition: 0.3s ease-in-out;
        }
        .btn-custom:hover { background-color: #03857f; }
        .table thead {
            background-color: #05a39c;
            color: white;
        }
        .form-control:focus { 
            border-color: #05a39c; 
            box-shadow: 0 0 5px rgba(5, 163, 156, 0.5); 
        }
        .modal-header {
            background-color: #05a39c;
            color: white;
        }
        .error { color: #dc3545; font-size: 0.875em; }
    </style>
</head>
<body>

<?php include 'admin_navbar.php'; ?>
<br>
<br>
<div class="container mt-5">
    <h2 class="text-center mb-4">Manage Users</h2>

    <!-- Add User Button -->
    <button class="btn btn-custom mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus"></i> Add User
    </button>

    <div class="card">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($usersQuery)) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn"
                                data-id="<?php echo $user['id']; ?>"
                                data-name="<?php echo $user['name']; ?>"
                                data-email="<?php echo $user['email']; ?>"
                                data-role="<?php echo $user['role']; ?>"
                                data-bs-toggle="modal" data-bs-target="#editUserModal">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <a href="admin_users.php?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="">Select Role</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_user" class="btn btn-custom">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="edit_user" class="btn btn-custom">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<!-- jQuery Validation -->
<script>
$(document).ready(function () {
    $("#addUserForm").validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            password: { required: true, minlength: 6 },
            role: { required: true }
        },
        messages: {
            name: { required: "Enter a name", minlength: "At least 3 characters" },
            email: { required: "Enter an email", email: "Enter a valid email" },
            password: { required: "Enter a password", minlength: "At least 6 characters" },
            role: { required: "Select a role" }
        },
        errorClass: "text-danger",
        errorPlacement: function (error, element) {
            error.insertAfter(element); // Ensure error messages are placed after input fields
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        }
    });

    $(".edit-btn").click(function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let email = $(this).data("email");
        let role = $(this).data("role");

        $("#editUserModal input[name='id']").val(id);
        $("#editUserModal input[name='name']").val(name);
        $("#editUserModal input[name='email']").val(email);
        $("#editUserModal select[name='role']").val(role);
    });
});



$("#editUserForm").validate({
    rules: {
        name: { required: true, minlength: 3 },
        email: { required: true, email: true },
        role: { required: true }
    },
    messages: {
        name: { required: "Enter a name", minlength: "At least 3 characters" },
        email: { required: "Enter an email", email: "Enter a valid email" },
        role: { required: "Select a role" }
    },
    errorClass: "text-danger",
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    highlight: function (element) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
        $(element).removeClass("is-invalid");
    }
});

</script>

<footer class="bg-light text-center py-4 border-top">
    <p>&copy; 2025 BuildBond</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
