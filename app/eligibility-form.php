<?php
include 'config.php';
// Check if form is submitted and validate code
$validCode = false; // Set to true if code is valid
$errorMsg = '';
$successMsg = '';

if (isset($_POST['check_eligibility'])) {
    $licenseCode = $_POST['license_code'];

    // Check if code is valid
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        while ($validcodes = $result->fetch_assoc()) {
            if ($validcodes['code'] == $licenseCode) {
                $validCode = true;
            } else {
                $errorMsg = "Invalid code. Please contact your agent for assistance.";
            }
        }
    }
}

if (isset($_POST['submit_grant'])) {
    // Step 1: Sanitize and validate the input data
    $full_name = htmlspecialchars($_POST['full_name']);
    $dob = $_POST['dob'];  // The date input should already be in a valid format (YYYY-MM-DD)
    $gender = $_POST['gender'];
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars($_POST['address']);
    $monthly_income = $_POST['monthly_income'];
    $household_income = $_POST['household_income'];
    $employment_status = $_POST['employment_status'];
    $income_source = htmlspecialchars($_POST['income_source']);
    $has_debts = $_POST['has_debts'];
    $debt_details = isset($_POST['debt_details']) ? htmlspecialchars($_POST['debt_details']) : NULL;
    $reason = htmlspecialchars($_POST['reason']);
    $grant_amount = $_POST['grant_amount'];
    $grant_purpose = htmlspecialchars($_POST['grant_purpose']);
    $other_grants = $_POST['other_grants'];
    $grant_details = isset($_POST['grant_details']) ? htmlspecialchars($_POST['grant_details']) : NULL;
    $resident = $_POST['resident'];
    $disadvantaged = $_POST['disadvantaged'];
    $category_details = isset($_POST['category_details']) ? htmlspecialchars($_POST['category_details']) : NULL;
    $criminal_record = $_POST['criminal_record'];
    $supporting_documents = $_POST['supporting_documents'];
    $declaration = isset($_POST['declaration']) ? 1 : 0;  // If checkbox is checked, set to 1 (true)

    // Step 2: Check if email or username already exists in the database
    $emailCheckQuery = "SELECT COUNT(*) FROM grants WHERE email = ?";
    $usernameCheckQuery = "SELECT COUNT(*) FROM grants WHERE full_name = ?";  // Assuming 'full_name' is the 'username'

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
        $errorMsg = "You have already applied for a grant.";
    } elseif ($usernameExists > 0) {
        $errorMsg = "You have already applied for a grant.";
    } else {
        // Step 4: Prepare the SQL query to insert data into the 'grants' table
        $sql = "INSERT INTO grants (
                    full_name, dob, gender, contact_number, email, address, 
                    monthly_income, household_income, employment_status, 
                    income_source, has_debts, debt_details, reason, grant_amount, 
                    grant_purpose, other_grants, grant_details, resident, 
                    disadvantaged, category_details, criminal_record, 
                    supporting_documents, declaration
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Step 5: Prepare the statement using MySQLi
        if ($stmt = $conn->prepare($sql)) {
            // Step 6: Bind the parameters to the SQL statement
            $stmt->bind_param(
                "ssssssddsssssssssssssss",  // Adjusted types for all 23 fields
                $full_name,
                $dob,
                $gender,
                $contact_number,
                $email,
                $address,
                $monthly_income,
                $household_income,
                $employment_status,
                $income_source,
                $has_debts,
                $debt_details,
                $reason,
                $grant_amount,
                $grant_purpose,
                $other_grants,
                $grant_details,
                $resident,
                $disadvantaged,
                $category_details,
                $criminal_record,
                $supporting_documents,
                $declaration
            );

            // Step 7: Execute the statement
            if ($stmt->execute()) {
                $successMsg = "Your grant application has been successfully submitted!";

                try {
                    // mail parameters for user $fullnameUser, $emailUser, $subjectUser, $bodyUser
                    $fullnameUser = $full_name;
                    $emailUser = $email;
                    $subjectUser = "Grant Application Notification";
                    $bodyUser = "Your Grant Eligibility form have been successfully submitted and its been processed. <br><br> We will get back to you soon.<br><br> Thank you!";

                    // Call the function to send the email
                    sendMailUser($fullnameUser, $emailUser, $subjectUser, $bodyUser);


                    // mail parameters for Admin $fullnameUser, $emailUser, $subjectUser, $bodyUser
                    $fullnameUser = $full_name;
                    $emailUser = $email;
                    $subjectUser = "Grant Application Notification From User";
                    $bodyUser = "A user with the fullname $fullnameUser has submitted a grant application. <br><br>Please check there application status to get back to them soon <br><br> Thank you!";

                    // Call the function to send the email
                    sendMailAdmin($fullnameUser, $emailUser, $subjectUser, $bodyUser);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            } else {
                echo "Error executing the query: " . $stmt->error;
            }

            // Step 8: Close the statement
            $stmt->close();
        } else {
            echo "Error preparing the query: " . $conn->error;
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
    <title>Eligibility Check | FundFinity Grants</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
            <h1>FundFinity Grant Application Portal</h1>
        </div>
        <nav>
            <a href="../index.html">Home</a>
            <a href="../portal.html">Track Application</a>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main>
        <section class="eligibility-check">
            <h2>Check Your Eligibility</h2>

            <?php if ($successMsg): ?>
                <p class="success-message">
                    <span style="margin-right: 10px; font-size: 18px; color: #28a745;">&#10003;</span>
                    <em><?php echo $successMsg; ?></em>
                </p>
            <?php endif; ?>


            <?php if (!$validCode): ?>
                <!-- Code Input Form -->
                <form action="" method="POST" class="license-code-form">
                    <label for="license_code">Enter License Code:</label>
                    <input type="text" id="license_code" name="license_code" value="<?php echo isset($_POST['license_code']) ? $_POST['license_code'] : ''; ?>" required>
                    <button type="submit" name="check_eligibility">Check Eligibility</button>
                    <?php if ($errorMsg): ?>
                        <p class="error"><em><?php echo $errorMsg; ?></em></p>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <!-- Eligibility Form -->
                <form action="" method="POST" class="eligibility-form border shadow p-4 bg-light rounded">
                    <!-- Section 1: Personal Information -->
                    <h3 class="mb-3">Section 1: Personal Information</h3>
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name:</label>
                        <input type="text" class="form-control" id="fullName" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth:</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender:</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="contactNumber" class="form-label">Contact Number:</label>
                        <input type="tel" class="form-control" id="contactNumber" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Residential Address:</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>

                    <!-- Section 2: Financial Information -->
                    <h3 class="mb-3">Section 2: Financial Information</h3>
                    <div class="mb-3">
                        <label for="monthlyIncome" class="form-label">Monthly Income:</label>
                        <input type="number" class="form-control" id="monthlyIncome" name="monthly_income" required>
                    </div>
                    <div class="mb-3">
                        <label for="householdIncome" class="form-label">Total Household Income:</label>
                        <input type="number" class="form-control" id="householdIncome" name="household_income" required>
                    </div>
                    <div class="mb-3">
                        <label for="employmentStatus" class="form-label">Employment Status:</label>
                        <select class="form-select" id="employmentStatus" name="employment_status" required>
                            <option value="">Select</option>
                            <option value="Employed">Employed</option>
                            <option value="Self-employed">Self-employed</option>
                            <option value="Unemployed">Unemployed</option>
                            <option value="Student">Student</option>
                            <option value="Retired">Retired</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="incomeSource" class="form-label">Primary Income Source:</label>
                        <input type="text" class="form-control" id="incomeSource" name="income_source" required>
                    </div>
                    <div class="mb-3">
                        <label for="hasDebts" class="form-label">Outstanding Loans or Debts:</label>
                        <select class="form-select" id="hasDebts" name="has_debts" required onchange="toggleDebtDetails(this)">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3" id="debtDetailsContainer" style="display:none;">
                        <textarea class="form-control" id="debtDetails" name="debt_details" rows="3" placeholder="If Yes, provide details."></textarea>
                    </div>

                    <!-- Section 3: Grant Requirements -->
                    <h3 class="mb-3">Section 3: Grant Requirements</h3>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Applying:</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="grantAmount" class="form-label">Requested Grant Amount (USD):</label>
                        <input type="number" class="form-control" id="grantAmount" name="grant_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="grantPurpose" class="form-label">Purpose of the Grant:</label>
                        <input type="text" class="form-control" id="grantPurpose" name="grant_purpose" required>
                    </div>
                    <div class="mb-3">
                        <label for="otherGrants" class="form-label">Other Grants Applied or Received:</label>
                        <select class="form-select" id="otherGrants" name="other_grants" required onchange="toggleGrantDetails(this)">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3" id="grantDetailsContainer" style="display:none;">
                        <textarea class="form-control" id="grantDetails" name="grant_details" rows="3" placeholder="If Yes, provide details."></textarea>
                    </div>

                    <!-- Section 4: Eligibility Criteria -->
                    <h3 class="mb-3">Section 4: Eligibility Criteria</h3>
                    <div class="mb-3">
                        <label for="resident" class="form-label">Are you currently a resident of [Country/Region]?</label>
                        <select class="form-select" id="resident" name="resident" required>
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="disadvantaged" class="form-label">Do you belong to a disadvantaged or special category?</label>
                        <select class="form-select" id="disadvantaged" name="disadvantaged" required onchange="toggleCategoryDetails(this)">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3" id="categoryDetailsContainer" style="display:none;">
                        <textarea class="form-control" id="categoryDetails" name="category_details" rows="3" placeholder="If Yes, specify."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="criminalRecord" class="form-label">Do you have any criminal records?</label>
                        <select class="form-select" id="criminalRecord" name="criminal_record" required>
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="supportingDocuments" class="form-label">Are you willing to provide supporting documents upon request?</label>
                        <select class="form-select" id="supportingDocuments" name="supporting_documents" required>
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- Section 5: Declaration -->
                    <h3 class="mb-3">Section 5: Declaration</h3>
                    <p>I declare that all the information provided is true to the best of my knowledge. I understand that any false information may result in disqualification from grant eligibility.</p>
                    <div class="mb-3">
                        <input type="checkbox" id="declaration" name="declaration" required>
                        <label for="declaration">I Agree</label>
                    </div>

                    <button type="submit" name="submit_grant" class="btn btn-primary">Submit Application</button>
                </form>

            <?php endif; ?>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function toggleDebtDetails(select) {
            document.getElementById('debt-details').style.display = select.value === 'Yes' ? 'block' : 'none';
        }

        function toggleGrantDetails(select) {
            document.getElementById('grant-details').style.display = select.value === 'Yes' ? 'block' : 'none';
        }

        function toggleCategoryDetails(select) {
            document.getElementById('category-details').style.display = select.value === 'Yes' ? 'block' : 'none';
        }
    </script>
</body>

</html>