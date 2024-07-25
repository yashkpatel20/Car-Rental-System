<?php 
    require("admin/includes/essentials.php");

    session_start();
    session_destroy();
    redirect("index.php");
?>