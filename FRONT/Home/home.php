<?php
session_start();
include_once '../BACK/includes/db_connection.php';

$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rach Billiard Hall - Home</title>
    <link rel="icon" href="../img/Rach Billiards.png">
    <link rel="stylesheet" href="styles_home.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body style="background-image: url('../img/home_bg.png');">
    <header>
        <nav>
            <label class="logo">
                <img src="../img/Rach Billiards.png" alt="Rach Billiards">
            </label>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <i class="material-icons">menu</i>
            </label>
            <ul>
                <li><a href="../HOME/home.php" class="active">Home</a></li>
                <li><a href="../RESERVATIONS/reservation.php">Reservations</a></li>
                <li class="account-dropdown">
                    <a href="#" id="accountButton"><?php echo $isLoggedIn ? 'Profile' : 'Account'; ?></a>
                    <div class="dropdown-content" id="accountDropdown">
                        <?php
                        if ($isLoggedIn) {
                            echo "<a href='#'>Welcome, $username</a>";
                            echo "<a href='../../BACK/scripts/logout.php'>Logout</a>";
                        } else {
                            echo "<a href='../ACCOUNT/account.html#signin-form'>Login</a>";
                            echo "<a href='../ACCOUNT/account.html#signup-form'>Create Account</a>";
                        }
                        ?>
                    </div>
                </li>
            </ul>
        </nav>

        <div class="header-content">
            <h1>Welcome to Rach Billiard Hall</h1>
            <p>Your ultimate billiards and arcade experience!</p>
        </div>
    </header>

    <section class="info-section">
        <div class="info">
            <h2>Location</h2>
            <p>We are situated at 998 Del Monte Avenue, San Francisco Del Monte, Brgy. Damayan, Quezon City, Philippines. We are conveniently located near Roosevelt Avenue, directly across from Jollibee Del Monte.</p>
        </div>

        <div class="info">
            <h2>Operating Hours</h2>
            <p>Our establishment operates from 1:00 PM to 3:00 AM daily.</p>
        </div>

        <div class="info">
            <h2>Contact Information</h2>
            <p>For inquiries, please visit our Facebook page at <a href="https://www.facebook.com/RACHBILLIARDHALL">RACH BILLIARD HALL</a>.</p>
        </div>

        <div class="info">
            <h2>Pricing</h2>
            <p>Our rates are 100 pesos per hour of play.</p>
        </div>

        <div class="info">
            <h2>Facilities</h2>
            <p>We provide three tables across our second and third floors for our customers. Additionally, we have an arcade area available for customers to enjoy while waiting for their turn.</p>
        </div>

        <div class="info">
            <h2>House Rules</h2>
            <ol>
                <li>Reservations are required; there is no open play time. Customers must arrive promptly for their reservation, as failure to show up within 15 minutes of the scheduled time will result in automatic cancellation and the reservation may be assigned to other waiting customers.</li>
                <li>Outside alcoholic beverages are strictly prohibited; however, outside food is allowed.</li>
                <li>Customers under the legal age of 18 are not permitted 6:00 PM onwards.</li>
                <li>Drinks and cigarettes are not permitted on the pool tables.</li>
                <li>We maintain a clean environment and strictly prohibit spitting or littering.</li>
            </ol>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Rach Billiard Hall. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let accountButton = document.getElementById('accountButton');
            let accountDropdown = document.getElementById('accountDropdown');

            accountButton.addEventListener('click', function(event) {
                event.preventDefault();
                accountDropdown.classList.toggle('show');
            });

            window.addEventListener('click', function(event) {
                if (!event.target.matches('#accountButton')) {
                    if (accountDropdown.classList.contains('show')) {
                        accountDropdown.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>
</html>
