<?php
include 'config.php';

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_verification'])) {
    // Collect input data
    $full_name = isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $ssn = isset($_POST['ssn']) ? htmlspecialchars($_POST['ssn']) : '';
    $validId = $_FILES['valid_id'];
    $birthCertificate = $_FILES['birth_certificate'];
    $proofAddress = $_FILES['proof_address'];

    // Step 2: Check if email or username already exists in the database
    $emailCheckQuery = "SELECT COUNT(*) FROM verification_data WHERE email = ?";
    $usernameCheckQuery = "SELECT COUNT(*) FROM verification_data WHERE full_name = ?";  // Assuming 'full_name' is the 'username'

    // Check for existing email
    if ($stmt = $conn->prepare($emailCheckQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($emailExists);
        $stmt->fetch();
        $stmt->close();
    }

    // Check for existing username (full_name)
    if ($stmt = $conn->prepare($usernameCheckQuery)) {
        $stmt->bind_param("s", $full_name);
        $stmt->execute();
        $stmt->bind_result($usernameExists);
        $stmt->fetch();
        $stmt->close();
    }

    // Step 3: If email or username already exists, display an error message
    if ($emailExists > 0) {
        $errorMsg = "You have already submitted Verification form.";
    } elseif ($usernameExists > 0) {
        $errorMsg = "You have already submitted Verification form.  ";
    }

    // Input validation
    if (empty($full_name)) {
        $errorMsg = "Full name is required.";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email address. Please provide a valid email.";
    } elseif (strlen($ssn) != 9) {
        $errorMsg = "Invalid Social Security Number. Please provide a valid number. (9 digits)";
        if (empty($ssn) || !is_numeric($ssn)) {
            $errorMsg = "Invalid Social Security Number. Please provide a valid number.";
        }
    } elseif (empty($validId['name']) || empty($birthCertificate['name']) || empty($proofAddress['name'])) {
        $errorMsg = "All required documents (ID, birth certificate, and proof of address) must be uploaded.";
    } else {
        // File upload validation
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadsDir = "uploads/";

        // Ensure the uploads directory exists
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        // Process each file upload
        $filePaths = [];
        foreach (['valid_id', 'birth_certificate', 'proof_address'] as $fileField) {
            $fileName = $_FILES[$fileField]['name'];
            $fileTmp = $_FILES[$fileField]['tmp_name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedExtensions)) {
                $errorMsg = "Only image files (JPG, PNG, GIF) are allowed.";
                break;
            }

            // Generate unique file name and move file
            $newFileName = uniqid() . "_" . basename($fileName);
            $filePath = $uploadsDir . $newFileName;
            if (!move_uploaded_file($fileTmp, $filePath)) {
                $errorMsg = "Failed to upload file: $fileName.";
                break;
            }
            $filePaths[$fileField] = $filePath;
        }
    }

    // Insert into database if no errors
    if (empty($errorMsg)) {
        $sql = "INSERT INTO verification_data (full_name, email , ssn, valid_id_path, birth_certificate_path, proof_address_path) 
                VALUES (?, ? ,?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                "ssisss",
                $full_name,
                $email,
                $ssn,
                $filePaths['valid_id'],
                $filePaths['birth_certificate'],
                $filePaths['proof_address']
            );

            if ($stmt->execute()) {
                $successMsg = "Your grant Verification form has been successfully submitted!";

                try {
                    // mail parameters for user $fullnameUser, $emailUser, $subjectUser, $bodyUser
                    $fullnameUser = $full_name;
                    $emailUser = $email;
                    $subjectUser = "Grant Verification form Notification";
                    $bodyUser = "Your Grant Verification form have been successfully submitted and its been processed. <br><br> We will get back to you soon.<br><br> Thank you!";

                    // Call the function to send the email
                    sendMailUser($fullnameUser, $emailUser, $subjectUser, $bodyUser);


                    // mail parameters for Admin $fullnameUser, $emailUser, $subjectUser, $bodyUser
                    $fullnameUser = $full_name;
                    $emailUser = $email;
                    $subjectUser = "Grant Verification form Notification From User";
                    $bodyUser = "A user with the fullname $fullnameUser has submitted a grant Verification form. <br><br>Please check there application status to get back to them soon <br><br> Thank you!";

                    // Call the function to send the email
                    sendMailAdmin($fullnameUser, $emailUser, $subjectUser, $bodyUser);
                } catch (\Throwable $th) {
                    //throw $th;
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
    <title>Verification Form | FundFinity Grants</title>
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
            <h2>Fill Your Verification Form </h2>

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

            <form action="" method="POST" class="verification-form border shadow p-5 bg-white rounded" enctype="multipart/form-data">
                <!-- Social Security Number -->
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
                    <label for="ssn" class="form-label">
                        Social Security Number or Equivalent (for US-based applicants)
                    </label>
                    <input type="number" class="form-control" id="ssn" name="ssn" value="<?php echo $ssn ?? ''; ?>" aria-describedby="ssn" required>
                </div>

                <!-- Valid ID Card or License -->
                <div class="mb-3">
                    <label for="valid_id" class="form-label">
                        Valid ID Card or License
                    </label>
                    <input type="file" class="form-control" id="valid_id" name="valid_id" accept="image/*" required>
                </div>

                <!-- Birth Certificate -->
                <div class="mb-3">
                    <label for="birth_certificate" class="form-label">
                        Birth Certificate
                    </label>
                    <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" accept="image/*" required>
                </div>

                <!-- Proof of Address -->
                <div class="mb-3">
                    <label for="proof_address" class="form-label">
                        Proof of Address (Utility Bill, Bank Statement, etc.)
                    </label>
                    <input type="file" class="form-control" id="proof_address" name="proof_address" accept="image/*" required>
                </div>

                <hr>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" name="submit_verification">Submit</button>
            </form>


        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>