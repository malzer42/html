<?php
session_start();
session_destroy();
header("Location: arche_admins_login.php");
exit;
