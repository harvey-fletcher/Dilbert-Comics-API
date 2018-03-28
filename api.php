<?php
    /**

        This file can be reached from the outside world and it returns the details of the comic for the requested day.

    **/

    //Include the database connection
    include_once('database_connection.php');

    if(isset($_GET['action'])){
          $req = $_GET;
        $_GET['action']($conn, $req);
    } else {
        echo json_encode(array("status"=>400, "info"=>"You have not provided an action"), JSON_FORCE_OBJECT);
        die();
    }

    function ordered($conn, $req){
        //Ensure the user has specified a date
        if(!isset($_GET['date'])){
            echo '{"status":"400","info":"You need to specify a date in format yyyy-mm-dd in your get request."}';
            die();
        } else {
            $date = $_GET['date'];
        }

        //Try and get the comic for that date from our database
        $query = "SELECT * FROM comics WHERE date='". $date ."'";
        $result = mysqli_query($conn, $query);

        //If there is a result, send it back as a JSONObject to the client, otherwise, try to download it from the dilbert site.
        if(mysqli_num_rows($result) == 1){
            $comic = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $comic['status'] = 200;
            echo json_encode($comic, JSON_FORCE_OBJECT);
        } else if($date == date('Y-m-d')){
            $comic = array("status" => 204, "info" => "Server completed the request, but no data was returned, please try again later.");
            echo json_encode($comic, JSON_FORCE_OBJECT);
        } else {
            $comic = array("status" => 404, "info" => "There was no image available.");
            echo json_encode($comic, JSON_FORCE_OBJECT);
        } 
    }

    function random($conn, $req){
        $query = "SELECT * FROM comics WHERE id = (SELECT CEIL(RAND() * (SELECT MAX(id) FROM comics)) AS id) LIMIT 1";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) < 1){
            random($conn, $req);
        }

        $comic = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $comic["status"] = 200;
        echo json_encode($comic, JSON_FORCE_OBJECT);
    }

?>

