<?php
session_start();
$errorMsg = isset($_SESSION['errorMsg']) ? $_SESSION['errorMsg'] : '';
unset($_SESSION['errorMsg']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="wp-content/uploads/2021/12/cropped-cfnewlogo-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="wp-content/uploads/2021/12/cropped-cfnewlogo-180x180.png" />
    <meta name="msapplication-TileImage" content="https://commonwealthfoundation.com/wp-content/uploads/2021/12/cropped-cfnewlogo-270x270.png" />
    <title>Admin Login | FundFinity Grants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Admin Login</h2>

        <!-- Error message -->
        <?php if (!empty($errorMsg)) : ?>
            <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>

        <form method="POST" action="adminLoginFunction.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>

</html>
