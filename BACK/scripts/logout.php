<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../FRONT/HOME/home.php");
exit();
?>
