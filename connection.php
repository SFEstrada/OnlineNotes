<?php

    $link = mysqli_connect("localhost", "testnotes", "testnotes", "mynotes");

    if (mysqli_connect_error()){
        die("Database connection error");
    }
