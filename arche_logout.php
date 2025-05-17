<?php
session_start();
session_destroy();
header("Location: arche_login.php");
exit;
