<?php
if($_SESSION['admin']){
    header('Location:dashboard.php');
}else {
    header('Location:login.php');
}