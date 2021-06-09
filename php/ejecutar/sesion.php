<?php
session_start();
$_SESSION['user_id'] = $_POST['id'];
$_SESSION['user_name'] = $_POST['nombre'];