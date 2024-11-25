<?php
include 'config.php';

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_verification'])) {
    // Collect input data
    $full_name = isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $bank_name = isset($_POST['bank_name']) ? htmlspecialchars($_POST['bank_name']) : '';
    $account_number = isset($_POST['account_number']) ? htmlspecialchars($_POST['account_number']) : '';
    $routing_number = isset($_POST['routing_number']) ? htmlspecialchars($_POST['routing_number']) : '';
    $swift_code = isset($_POST['swift_code']) ? htmlspecialchars($_POST['swift_code']) : '';
    $account_type = isset($_POST['account_type']) ? htmlspecialchars($_POST['account_type']) : '';

    // Step 1: Check for duplicate entries
    $emailCheckQuery = "SELECT COUNT(*) FROM bank_details WHERE email = ?";
    $accountCheckQuery = "SELECT COUNT(*) FROM bank_details WHERE account_number = ?";

    // Check for existing email
    if ($stmt = $conn->prepare($emailCheckQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($emailExists);
        $stmt->fetch();
        $stmt->close();
    }

    // Check for existing account number
    if ($stmt = $conn->prepare($accountCheckQuery)) {
        $stmt->bind_param("s", $account_number);
        $stmt->execute();
        $stmt->bind_result($accountExists);
        $stmt->fetch();
        $stmt->close();
    }

    // Step 2: Validate input data
    if ($emailExists > 0) {
        $errorMsg = "This email is already associated with an existing record.";
    } elseif ($accountExists > 0) {
        $errorMsg = "This account number is already associated with an existing record.";
    } elseif (empty($full_name)) {
        $errorMsg = "Full name is required.";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email address.";
    } elseif (empty($bank_name)) {
        $errorMsg = "Bank name is required.";
    } elseif (empty($account_number) || !is_numeric($account_number)) {
        $errorMsg = "Valid account number is required.";
    } elseif (empty($routing_number) || !is_numeric($routing_number)) {
        $errorMsg = "Valid routing number is required.";
    } elseif (empty($swift_code)) {
        $errorMsg = "SWIFT/BIC code is required.";
    } elseif (!in_array($account_type, ['Checking', 'Savings'])) {
        $errorMsg = "Account type must be either 'Checking' or 'Savings'.";
    } else {
        // Step 3: Insert data into the database if validation passes
        $sql = "INSERT INTO bank_details (fullname, email, bank_name, account_number, routing_number, swift_code, account_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                "sssssss",
                $full_name,
                $email,
                $bank_name,
                $account_number,
                $routing_number,
                $swift_code,
                $account_type
            );

            if ($stmt->execute()) {
                $successMsg = "Bank details have been successfully submitted!";

                try {
                    // Send email notification to the user
                    $subjectUser = "Bank Details Submission Notification";
                    $bodyUser = "Dear $full_name,<br>Your bank details have been successfully submitted.<br>Thank you!";
                    sendMailUser($full_name, $email, $subjectUser, $bodyUser);

                    // Send email notification to the admin
                    $adminEmail = "admin@example.com"; // Replace with the admin email
                    $subjectAdmin = "New Bank Details Submission";
                    $bodyAdmin = "A user with the name $full_name has submitted their bank details.<br>Email: $email<br>Bank Name: $bank_name<br>Account Number: $account_number.";
                    sendMailAdmin($full_name, $adminEmail, $subjectAdmin, $bodyAdmin);
                } catch (\Throwable $th) {
                    // Handle any exceptions related to email
                }
            } else {
                $errorMsg = "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errorMsg = "Failed to prepare the SQL statement.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="wp-content/uploads/2021/12/cropped-cfnewlogo-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="wp-content/uploads/2021/12/cropped-cfnewlogo-180x180.png" />
    <meta name="msapplication-TileImage" content="https://commonwealthfoundation.com/wp-content/uploads/2021/12/cropped-cfnewlogo-270x270.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Grants Disbursement | FundFinity Grants</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a soft shadow to the box */
        }

        .success-message em {
            font-style: italic;
            /* Make the message text italic if desired */
        }

        .error {
            background-color: #e7a28e;
            color: #b81818;
            border: 1px solid #e7a28e;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>


<body>
    <!-- Header Section -->
    <header>
        <div class="header-logo">
            <img src="../img/logo.png" alt="FundFinity Grants Logo">
        </div>
        <div class="header-title">
            <h1>Verification Form Portal</h1>
        </div>
        <nav>
            <a href="../index.html">Home</a>
            <a href="../portal.html">Track Application</a>
        </nav>
    </header>

    <main>
        <section class="eligibility-check">
            <h2>Fill In BANK ACCOUNT INFORMATION </h2>

            <?php if ($successMsg): ?>
                <p class="success-message">
                    <span style="margin-right: 10px; font-size: 18px; color: #28a745;">&#10003;</span>
                    <em><?php echo $successMsg; ?></em>
                </p>
            <?php endif; ?>
            <?php if ($errorMsg): ?>
                <p class="error">
                    <?php echo $errorMsg; ?>
                </p>
            <?php endif; ?>
        </section>

        <form action="" method="POST" class="verification-form border shadow p-5 bg-white rounded">
            <!-- Bank Name -->
            <div class="mb-3">
                <label for="ssn" class="form-label">
                    Full Name
                </label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $full_name ?? ''; ?>" aria-describedby="fullname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">
                    Email
                </label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?? ''; ?>" aria-describedby="email" required>
            </div>
            <div class="mb-3">
                <label for="bank_name" class="form-label">
                    Bank Name
                </label>
                <input type="text" class="form-control" id="bank_name" name="bank_name" value="<?php echo $bank_name ?? ''; ?>" aria-describedby="bank_name" required>
            </div>

            <!-- Bank Account Number -->
            <div class="mb-3">
                <label for="account_number" class="form-label">
                    Bank Account Number
                </label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="<?php echo $account_number ?? ''; ?>" aria-describedby="account_number" required>
            </div>

            <!-- Bank Routing Number -->
            <div class="mb-3">
                <label for="routing_number" class="form-label">
                    Bank Routing Number (for US-based transfers)
                </label>
                <input type="text" class="form-control" id="routing_number" name="routing_number" value="<?php echo $routing_number ?? ''; ?>" aria-describedby="routing_number" required>
            </div>

            <!-- SWIFT/BIC Code -->
            <div class="mb-3">
                <label for="swift_code" class="form-label">
                    SWIFT/BIC Code (for international transfers)
                </label>
                <input type="text" class="form-control" id="swift_code" name="swift_code" value="<?php echo $swift_code ?? ''; ?>" aria-describedby="swift_code" required>
            </div>

            <!-- Account Type -->
            <div class="mb-3">
                <label for="account_type" class="form-label">
                    Account Type (Checking/Savings)
                </label>
                <select class="form-select" id="account_type" name="account_type" required>
                    <option value="" <?php echo !isset($account_type) ? 'selected' : ''; ?>>Select Account Type</option>
                    <option value="Checking" <?php echo (isset($account_type) && $account_type === 'Checking') ? 'selected' : ''; ?>>Checking</option>
                    <option value="Savings" <?php echo (isset($account_type) && $account_type === 'Savings') ? 'selected' : ''; ?>>Savings</option>
                </select>
            </div>

            <hr>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" name="submit_verification">Submit</button>
        </form>

    </main>
</body>