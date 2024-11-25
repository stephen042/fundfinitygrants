<?php
require_once 'adminLoginFunction.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
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
            <!-- Cards -->
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <?php
                            // Count total Grants people
                            $sql = "SELECT COUNT(*) AS total_people FROM grants";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $total_people = $row['total_people'];
                            }
                            ?>
                            <i class="card-icon fas fa-users"></i>
                            <div class="ms-3">
                                <h5 class="text-white">Total Grants Eligibility Applicants</h5>
                                <h3><?= $total_people ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Amount -->
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <?php
                            // Count total Grants people
                            $sql = "SELECT COUNT(*) AS total_people FROM verification_data";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $total_people_verification = $row['total_people'];
                            }
                            ?>
                            <i class="card-icon fas fa-check-circle"></i>
                            <div class="ms-3">
                                <h5 class="text-white">Total Verification Form</h5>
                                <h3><?= $total_people_verification ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Amount -->
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <?php
                            // Count total Grants people
                            $sql = "SELECT COUNT(*) AS total_people FROM bank_details";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $total_people_bank = $row['total_people'];
                            }
                            ?>
                            <i class="card-icon fa fa-university" aria-hidden="true"></i>
                            <div class="ms-3">
                                <h5 class="text-white">Total Grant Disbursement</h5>
                                <h3><?= $total_people_bank ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <li role="separator" class="dropdown-divider"></li>
            <hr style="border:5px solid grey;">

            <!-- Data Tables -->
            <div class="table-container mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5>All Grants Eligibility Applicants</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Application Date</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Date of Birth</th>
                                    <th>Gender</th>
                                    <th>Contact Number</th>
                                    <th>Monthly Income</th>
                                    <th>Household Income</th>
                                    <th>Employment Status</th>
                                    <th>Grant Amount</th>
                                    <th>Grant Purpose</th>
                                    <th>Primary Income Source</th>
                                    <th>Outstanding Loans or Debts</th>
                                    <th>Reason for Applying</th>
                                    <th>Other Grants Applied</th>
                                    <th>Criminal Record</th>
                                    <th>Supporting Documents</th>
                                    <th>Disadvantaged/Special Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch all records from the 'grants' table
                                $sql = "SELECT * FROM grants";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['id'] ?></td>
                                            <td><?php echo date('Y M d h:i a', strtotime($row['created_at'])) ?></td>
                                            <td><?php echo $row['full_name'] ?></td>
                                            <td><?php echo $row['email'] ?></td>
                                            <td><?php echo $row['dob'] ?></td>
                                            <td><?php echo $row['gender'] ?></td>
                                            <td><?php echo $row['contact_number'] ?></td>
                                            <td><?php echo $row['monthly_income'] ?></td>
                                            <td><?php echo $row['household_income'] ?></td>
                                            <td><?php echo $row['employment_status'] ?></td>
                                            <td><?php echo $row['grant_amount'] ?></td>
                                            <td><?php echo $row['grant_purpose'] ?></td>
                                            <td><?php echo $row['income_source'] ?></td>
                                            <td><?php echo $row['has_debts'] ?></td>
                                            <td><?php echo $row['reason'] ?></td>
                                            <td><?php echo $row['other_grants'] ?></td>
                                            <td><?php echo $row['criminal_record'] ?></td>
                                            <td><?php echo $row['supporting_documents'] ?></td>
                                            <td><?php echo $row['disadvantaged'] ?></td>
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

            <hr style="border:3px solid grey;">
            <!-- Data Tables -->
            <div class="table-container mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5>All Verification Data</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Application Date</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>SSN</th>
                                    <th>Valid ID</th>
                                    <th>Birth Certificate</th>
                                    <th>Proof of Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch all records from the 'verification_data' table
                                $sql = "SELECT * FROM verification_data";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['id'] ?></td>
                                            <td><?php echo date('Y M d h:i a', strtotime($row['created_at'])) ?></td>
                                            <td><?php echo $row['full_name'] ?></td>
                                            <td><?php echo $row['email'] ?></td>
                                            <td><?php echo $row['ssn'] ?></td>
                                            <td>
                                                <a href="../<?php echo $row['valid_id_path'] ?>" target="_blank" class="btn btn-sm btn-info">View ID</a>
                                            </td>
                                            <td>
                                                <a href="../<?php echo $row['birth_certificate_path'] ?>" target="_blank" class="btn btn-sm btn-info">View Birth Certificate</a>
                                            </td>
                                            <td>
                                                <a href="../<?php echo $row['proof_address_path'] ?>" target="_blank" class="btn btn-sm btn-info w-100">View Proof of Address</a>
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
            <hr style="border:3px solid grey;">

            <!-- Data Tables -->
            <div class="table-container mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5>All Bank Details For Grant Disbursement</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>Routing Number</th>
                                    <th>SWIFT/BIC Code</th>
                                    <th>Account Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch all records from the 'bank_details' table
                                $sql = "SELECT * FROM bank_details";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['fullname']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['bank_name']; ?></td>
                                            <td><?php echo $row['account_number']; ?></td>
                                            <td><?php echo $row['routing_number']; ?></td>
                                            <td><?php echo $row['swift_code']; ?></td>
                                            <td><?php echo $row['account_type']; ?></td>
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