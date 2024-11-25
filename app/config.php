<?php 
include 'db_connection.php';


$sql = "SELECT code FROM validcodes";
$result = $conn->query($sql);  // Using MySQLi query method

$validcodes = $result->fetch_assoc();  // Correct for MySQLi

// mail function for user 
function sendMailUser($fullnameUser, $emailUser, $subjectUser, $bodyUser) {
    // Email subject
    $subject = "$subjectUser";

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@example.com" . "\r\n";

    // HTML email template
    $message = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
            }
            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background: #ffffff;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .email-header {
                background: #007bff;
                color: #ffffff;
                padding: 10px 20px;
                border-radius: 5px 5px 0 0;
                text-align: center;
            }
            .email-body {
                padding: 20px;
                font-size: 16px;
            }
            .email-footer {
                text-align: center;
                margin-top: 20px;
                font-size: 14px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <h2>Welcome to FundFinityGrants!</h2>
            </div>
            <div class='email-body'>
                <p>Hi <strong>$fullnameUser</strong>,</p>
                <p>$bodyUser</p>
                <p>The Team</p>
            </div>
            <div class='email-footer'>
                &copy; " . date('Y') . " FundFinityGrant. All rights reserved.
            </div>
        </div>
    </body>
    </html>";

    try {
        mail($emailUser, $subject, $message, $headers);
    } catch (\Throwable $th) {
        //throw $th;
    }
}

// mail function
function sendMailAdmin($fullnameUser, $emailUser, $subjectUser, $bodyUser) {
    // Email subject
    $subject = "$subjectUser";

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@example.com" . "\r\n";

    // HTML email template
    $message = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
            }
            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background: #ffffff;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .email-header {
                background: #007bff;
                color: #ffffff;
                padding: 10px 20px;
                border-radius: 5px 5px 0 0;
                text-align: center;
            }
            .email-body {
                padding: 20px;
                font-size: 16px;
            }
            .email-footer {
                text-align: center;
                margin-top: 20px;
                font-size: 14px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <h2>Welcome to FundFinityGrants!</h2>
            </div>
            <div class='email-body'>
                <p>Hi <strong>Admin</strong>,</p>
                <p>$bodyUser</p>
                <p>The Team</p>
            </div>
            <div class='email-footer'>
                &copy; " . date('Y') . " FundFinityGrant. All rights reserved.
            </div>
        </div>
    </body>
    </html>";

    try {
        mail($emailUser, $subject, $message, $headers);
    } catch (\Throwable $th) {
        //throw $th;
    }
}
?>