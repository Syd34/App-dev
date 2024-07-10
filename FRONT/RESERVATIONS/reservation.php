<?php
session_start();
include_once '../../BACK/includes/db_connection.php';

$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="styles_reservation.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <nav>
        <label class="logo">
            <img src="../img/Rach Billiards.png" alt="Rach Billiards">
        </label>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="material-icons">menu</i>
        </label>
        <ul>
            <li><a href="../HOME/home.php">Home</a></li>
            <li><a href="../RESERVATIONS/reservation.php" class="active">Reservations</a></li>
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

    <div class="container">
        <h1>Choose Your Table</h1>
        <div class="tables">
            <div class="table" id="table1">
                <h2>Table 1</h2>
                <img src="../img/table.png" alt="Table 1">
                <div class="timer" id="timer1">Time left: 2 hours</div>
                <button onclick="showForm('table1')">Reserve Table 1</button>
            </div>
            <div class="table" id="table2">
                <h2>Table 2</h2>
                <img src="../img/table.png" alt="Table 2">
                <div class="timer" id="timer2">Time left: 1.5 hours</div>
                <button onclick="showForm('table2')">Reserve Table 2</button>
            </div>
            <div class="table" id="table3">
                <h2>Table 3</h2>
                <img src="../img/table.png" alt="Table 3">
                <div class="timer" id="timer3">Time left: 1 hour</div>
                <button onclick="showForm('table3')">Reserve Table 3</button>
            </div>
        </div>
    </div>

    <div id="reservationForm" class="form-container">
        <form id="form" onsubmit="return reserveTable()">
            <h2>Reserve Table</h2>
            <label for="hours">Hours:</label>
            <input type="number" id="hours" name="hours" min="1" required>
            <div id="priceDisplay"></div>
            <button type="submit">Submit Reservation</button>
            <button type="button" onclick="hideForm()">Cancel</button>
        </form>
    </div>

    <div id="reservationMessage" class="message-container">
        <p id="messageText">Your reservation is successful! Please wait for a message/call through your account's contact number. You will receive the message 10 minutes before your turn for the table. Thank you!</p>
        <div id="reservationPrice"></div>
        <button onclick="closeMessage()">Close</button>
    </div>

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

        function showForm(tableId) {
            document.getElementById('reservationForm').style.display = 'block';
            document.getElementById('form').dataset.table = tableId;
        }

        function hideForm() {
            document.getElementById('reservationForm').style.display = 'none';
        }

        function reserveTable() {
            const hours = document.getElementById('hours').value;
            const price = hours * 100;
            document.getElementById('priceDisplay').innerText = `Total Price: ₱${price}`;
            document.getElementById('reservationPrice').innerText = `Total Price: ₱${price}`; 
            document.getElementById('reservationForm').style.display = 'none';
            document.getElementById('reservationMessage').style.display = 'block'; 
            return false; 
        }

        function closeMessage() {
            document.getElementById('reservationMessage').style.display = 'none'; 
        }

        function startTimer(timerId, hoursLeft) {
            const timerElement = document.getElementById(timerId);
            let totalTime = hoursLeft * 60 * 60;

            setInterval(() => {
                if (totalTime <= 0) {
                    timerElement.textContent = "Time left: 0 hours";
                    return;
                }

                totalTime -= 1;
                const hours = Math.floor(totalTime / 3600);
                const minutes = Math.floor((totalTime % 3600) / 60);
                const seconds = totalTime % 60;

                timerElement.textContent = `Time left: ${hours}:${minutes}:${seconds}`;
            }, 1000);
        }

        startTimer("timer1", 2);
        startTimer("timer2", 1.5);
        startTimer("timer3", 1);
    </script>
</body>
</html>
