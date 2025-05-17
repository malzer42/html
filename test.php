<?php
    $servername="localhost";
    $username = "admin";
    $password = "mfzvs2ttlmn2";
    $database = "arche";

                    // create connection
                    $connection = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if($connection->connect_error){
                      die("Connection Failed: ".$connection->connect_error);
                    }

                    // Read the data from the pastors table of the database arche
                    $sql = "select id,  first_name from pastors";
                    $results = $connection->query($sql);

                    var_dump($results);

                    if(!$results){
                      die("Requete Invalid: ".$connection->connect_error);
                    }

                    while($row = $results->fetch_assoc()){
                      echo" $row[first_name] " ;
                    }

                    ?>