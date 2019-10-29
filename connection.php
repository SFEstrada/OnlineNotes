<?php

    $link = mysqli_connect("localhost", "sestrada", "sestrada", "mynotes");

    if (mysqli_connect_error()){
        die("Database connection error");
    }
