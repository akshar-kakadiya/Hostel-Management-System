<?php
session_start();
session_destroy();
header("Location: s-login.php");
exit();
?>
