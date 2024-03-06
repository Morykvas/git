<?php 
    # вихід з акаунта користувача, закриває сесію з профілем юзера який виходить зі свого акаунта

    session_start();
    unset($_SESSION['profile']);
    header('Location: ../index.php');