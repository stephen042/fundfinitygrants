<?php
require_once 'adminLoginFunction.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fetch the logged-in admin's current data
$admin_id = $_SESSION['admin'];  // Assuming the admin's ID is stored in the session

$sql = "SELECT id, username, email FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $adminData = $result->fetch_assoc();
} else {
    // Handle case where no admin data is found
    $error_msg = "Admin data not found.";
}

// Success/Error messages
$success_msg = '';
$error_msg = '';

// Handle password reset
if (isset($_POST['reset_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validate inputs
    if (empty($old_password) || empty($new_password) || empty($confirm_new_password)) {
        $error_msg = 'All fields are required.';
    } elseif ($new_password !== $confirm_new_password) {
        $error_msg = 'New password and confirm password do not match.';
    } else {
        // Check if old password is correct
        $sql = "SELECT password FROM admins WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Verify old password
        if (password_verify($old_password, $row['password'])) {
            // Update the password
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE admins SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_password_hashed, $admin_id);

            if ($update_stmt->execute()) {
                $success_msg = 'Password updated successfully!';
            } else {
                $error_msg = 'Failed to update password. Please try again.';
            }
        } else {
            $error_msg = 'Old password is incorrect.';
        }
    }
}

// Handle email and username update
if (isset($_POST['update_email_username'])) {
    $new_email = $_POST['new_email'];
    $new_username = $_POST['new_username'];

    // Validate inputs
    if (empty($new_email) || empty($new_username)) {
        $error_msg = 'Both email and username are required.';
    } else {
        // Update email and username in the database
        $update_sql = "UPDATE admins SET email = ?, username = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $new_email, $new_username, $admin_id);

        if ($update_stmt->execute()) {
            $success_msg = 'Email and Username updated successfully!';
        } else {
            $error_msg = 'Failed to update email or username. Please try again.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php
require_once './layouts/head.php';
?>

<body>
    <?php require_once './layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="container mt-4">
            <!-- Success/Error Messages -->
            <?php if ($success_msg): ?>
                <div class="alert alert-success"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <!-- Reset Password Form -->
            <h4>Reset Password</h4>
            <form action="profile.php" method="POST" class="border shadow p-4 bg-light rounded w-50">
                <div class="mb-3">
                    <label for="old_password" class="form-label">Old Password</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
            </form>

            <!-- Update Email and Username Form -->
            <h4 class="mt-5">Update Email and Username</h4>
            <form action="profile.php" method="POST" class="border shadow p-4 bg-light rounded w-50">
                <div class="mb-3">
                    <label for="new_email" class="form-label">New Email</label>
                    <input type="email" class="form-control" id="new_email" name="new_email" value="<?php echo $adminData['email'] ?? ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="new_username" class="form-label">New Username</label>
                    <input type="text" class="form-control" id="new_username" name="new_username" value="<?php echo $adminData['username'] ?? ''; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" name="update_email_username">Update Email & Username</button>
            </form>
        </div>
    </div>

    <?php require_once './layouts/footer.php'; ?>
    <?php require_once './layouts/script.php'; ?>
</body>

</html>
