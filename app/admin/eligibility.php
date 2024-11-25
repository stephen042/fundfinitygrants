<?php
require_once 'adminLoginFunction.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Success/Error messages
$success_msg = '';
$error_msg = '';

// Insert new code
if (isset($_POST['add_eligibility_code'])) {
    $code = $_POST['code'];
    
    if (empty($code)) {
        $error_msg = "Code cannot be empty!";
    } else {
        // Check if the code already exists
        $check_code = "SELECT * FROM validcodes WHERE code = ?";
        $stmt = $conn->prepare($check_code);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_msg = "Code already exists!";
        } else {
            // Insert the new eligibility code
            $insert_code = "INSERT INTO validcodes (code) VALUES (?)";
            $stmt = $conn->prepare($insert_code);
            $stmt->bind_param("s", $code);
            
            if ($stmt->execute()) {
                $success_msg = "Eligibility code added successfully!";
            } else {
                $error_msg = "Failed to add code. Please try again.";
            }
        }
    }
}

// Delete eligibility code
if (isset($_POST['delete_code'])) {
    $id = $_POST['id'];
    
    // Delete the selected code
    $delete_code = "DELETE FROM validcodes WHERE id = ?";
    $stmt = $conn->prepare($delete_code);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success_msg = "Eligibility code deleted successfully!";
    } else {
        $error_msg = "Failed to delete code. Please try again.";
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
            <h4>Create Eligibility Codes</h4>
            
            <!-- Success/Error Messages -->
            <?php if ($success_msg): ?>
                <div class="alert alert-success"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <!-- Form to Add New Code -->
            <form action="eligibility.php" method="POST" class="eligibility-form border shadow p-4 bg-light rounded w-50">
                <div class="mb-3">
                    <label for="code" class="form-label">Code</label>
                    <input type="text" class="form-control" id="code" name="code" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_eligibility_code">Add Code</button>
            </form>

            <!-- Data Tables -->
            <div class="table-container mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5>All Eligibility Codes</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch all records from the 'validcodes' table
                                $sql = "SELECT * FROM validcodes";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['code']; ?></td>
                                            <td><?php echo $row['created_at']; ?></td>
                                            <td>
                                                <form action="eligibility.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" name="delete_code">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require_once './layouts/footer.php'; ?>
    <?php require_once './layouts/script.php'; ?>
</body>

</html>
