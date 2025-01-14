<?php

        $hostname = 'localhost';
        $dbname ='universal_bdd';
        $dbuser = 'root';
        $dbpass = '';
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $dbuser, $dbpass);

