<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { color: #ff7f50; }
        a { text-decoration: none; padding: 10px 20px; background: red; color: white; border-radius: 5px; }
        
        /* Popup Styles */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
            border-radius: 10px;
            text-align: center;
            z-index: 1000;
        }
        .popup button {
            background: #ff7f50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Welcome Popup -->
    <div id="welcomePopup" class="popup">
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
        <p>You have successfully logged in.</p>
        <button onclick="closePopup()">OK</button>
    </div>

    <h1>Welcome, <?php echo $_SESSION["username"]; ?>!</h1>
    <p>This is the main page after login.</p>
    <a href="logout.php">Logout</a>

    <script>
        // Show welcome popup when the page loads
        window.onload = function() {
            document.getElementById("welcomePopup").style.display = "block";
        };

        // Close popup when clicking "OK"
        function closePopup() {
            document.getElementById("welcomePopup").style.display = "none";
        }
    </script>

</body>
</html>
