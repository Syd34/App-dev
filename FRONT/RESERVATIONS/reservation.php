<?php
session_start();
include_once '../../BACK/includes/db_connection.php';

$isLoggedIn = isset($_SESSION['username']) ? true : false;
$username = $isLoggedIn ? $_SESSION['username'] : '';

$reservations = [];
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
<body style="background-image: url('../img/reservation_bg.png');">
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
                    <?php if ($isLoggedIn) { ?>
                        <a href='#'>Welcome, <?php echo $username; ?></a>
                        <a href='../../BACK/scripts/logout.php'>Logout</a>
                    <?php } else { ?>
                        <a href='../ACCOUNT/account.html#signin-form'>Login</a>
                        <a href='../ACCOUNT/account.html#signup-form'>Create Account</a>
                    <?php } ?>
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
                <div class="timer" id="timer1">Time left: calculating...</div>
                <button onclick="showForm('1')">Reserve Table 1</button>
            </div>
            <div class="table" id="table2">
                <h2>Table 2</h2>
                <img src="../img/table.png" alt="Table 2">
                <div class="timer" id="timer2">Time left: calculating...</div>
                <button onclick="showForm('2')">Reserve Table 2</button>
            </div>
            <div class="table" id="table3">
                <h2>Table 3</h2>
                <img src="../img/table.png" alt="Table 3">
                <div class="timer" id="timer3">Time left: calculating...</div>
                <button onclick="showForm('3')">Reserve Table 3</button>
            </div>
        </div>
    </div>
    <div id="reservationForm" class="form-container">
        <form id="form" onsubmit="return reserveTable(event)">
            <h2>Reserve Table</h2>
            <label for="hours">Hours:</label>
            <input type="number" id="hours" name="hours" min="0" required>
            <label for="minutes">Minutes:</label>
            <input type="number" id="minutes" name="minutes" min="0" max="59" required>
            <div id="priceDisplay"></div>
            <button type="submit">Submit Reservation</button>
            <button type="button" onclick="hideForm()">Cancel</button>
        </form>
    </div>
    <div id="reservationMessage" class="message-container">
        <p id="messageText"></p>
        <div id="reservationPrice"></div>
        <button onclick="closeMessage()">Close</button>
    </div>
    <div id="loginMessage" class="message-container" style="display:none;">
        <p>You must be logged in to make a reservation. Please <a href="../ACCOUNT/account.html#signin-form">login</a> or <a href="../ACCOUNT/account.html#signup-form">create an account</a>.</p>
        <button onclick="closeLoginMessage()">Close</button>
    </div>
    <script>
    const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        fetch('../../BACK/scripts/fetch_reservations.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const reservations = data;
                reservations.forEach(reservation => {
                    startTimer(`timer${reservation.table_id}`, reservation.remaining_time);
                });
            })
            .catch(error => {
                console.error('Error fetching reservations:', error);
            });
    });

    function showForm(tableId) {
        if (!isLoggedIn) {
            document.getElementById('loginMessage').style.display = 'block';
            return;
        }
        document.getElementById('reservationForm').style.display = 'block';
        document.getElementById('form').dataset.table = tableId;
    }

    function hideForm() {
        document.getElementById('reservationForm').style.display = 'none';
    }

    function reserveTable(event) {
        event.preventDefault();
        const form = document.getElementById('form');
        const tableId = form.dataset.table;
        const hours = parseInt(document.getElementById('hours').value, 10);
        const minutes = parseInt(document.getElementById('minutes').value, 10);

        const totalPrice = calculatePrice(hours, minutes);

        fetch('../../BACK/scripts/reserve_table.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ tableId, hours, minutes })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('reservationForm').style.display = 'none';
                document.getElementById('messageText').innerText = 'Your reservation is successful! Please wait for a message/call through your account\'s contact number. You will receive the message 10 minutes before your turn for the table. Thank you!';
                document.getElementById('reservationMessage').style.display = 'block';
                document.getElementById('reservationPrice').innerText = `Total Price: $${totalPrice.toFixed(2)} pesos`;
                updateTimer(tableId, hours, minutes);
            } else {
                document.getElementById('messageText').innerText = 'There was an error with your reservation. Please try again.';
                document.getElementById('reservationMessage').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error during reservation:', error);
            document.getElementById('messageText').innerText = 'There was an error with your reservation. Please try again.';
            document.getElementById('reservationMessage').style.display = 'block';
        });

        return false; 
    }

    function closeMessage() {
        document.getElementById('reservationMessage').style.display = 'none';
    }

    function closeLoginMessage() {
        document.getElementById('loginMessage').style.display = 'none';
    }

    function startTimer(timerId, remainingTime) {
        const timerElement = document.getElementById(timerId);
        let totalTime = remainingTime;
        const interval = setInterval(() => {
            if (totalTime <= 0) {
                timerElement.textContent = "Time left: 0 hours 0 minutes 0 seconds";
                clearInterval(interval);
                return;
            }
            totalTime -= 1;
            const hours = Math.floor(totalTime / 3600);
            const minutes = Math.floor((totalTime % 3600) / 60);
            const seconds = totalTime % 60;
            timerElement.textContent = `Time left: ${hours} hours ${minutes} minutes ${seconds} seconds`;
        }, 1000);
    }

    function updateTimer(tableId, hours, minutes) {
        const timerElement = document.getElementById(`timer${tableId}`);
        let totalTime = hours * 3600 + minutes * 60;
        const interval = setInterval(() => {
            if (totalTime <= 0) {
                timerElement.textContent = "Time left: 0 hours 0 minutes 0 seconds";
                clearInterval(interval);
                return;
            }
            totalTime -= 1;
            const hrs = Math.floor(totalTime / 3600);
            const mins = Math.floor((totalTime % 3600) / 60);
            const secs = totalTime % 60;
            timerElement.textContent = `Time left: ${hrs} hours ${mins} minutes ${secs} seconds`;
        }, 1000);
    }

    function calculatePrice(hours, minutes) {
        const hourRate = 100; // Price per hour
        const halfHourRate = 50; // Price per 30 minutes

        const totalMinutes = hours * 60 + minutes;
        const hourCost = Math.floor(totalMinutes / 60) * hourRate;
        const remainingMinutes = totalMinutes % 60;
        const halfHourCost = Math.ceil(remainingMinutes / 30) * halfHourRate;
        const totalPrice = hourCost + halfHourCost;

        return totalPrice;
    }
    </script>

</body>
</html>
