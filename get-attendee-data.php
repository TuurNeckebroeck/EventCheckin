<?php
    include('database.php');
    $fail = "false";

    if(isset($_POST['checkin_code'])) {
    # echo $_POST['name'] . ', ' . $_POST['description'];
    // $task_name = $_POST['name'];
    // $task_description = $_POST['description'];
    $checkin_code = $_POST['checkin_code'];



    //$query = "INSERT into task(name, description) VALUES ('$task_name', '$checkin_id')";
    if(is_numeric($checkin_code)) {
        $query = "SELECT a.name, COALESCE(SUM(c.nb_tickets), 0) as \"checked_in\", a.nb_tickets as \"total_tickets\"\n"
        . "FROM attendees a\n"
        . "LEFT JOIN checkins c\n"
        . "	ON a.id = c.attendee_id\n"
        . "WHERE a.checkin_code = \"$checkin_code\"\n"
        . "GROUP BY c.attendee_id\n"
        . "LIMIT 1";
    } else {
        $query = "SELECT a.name, COALESCE(SUM(c.nb_tickets), 0) as \"checked_in\", a.nb_tickets as \"total_tickets\"\n"
        . "FROM attendees a\n"
        . "LEFT JOIN checkins c\n"
        . "	ON a.id = c.attendee_id\n"
        . "WHERE a.name LIKE \"%$checkin_code%\"\n"
        . "GROUP BY c.attendee_id\n" 
        . "LIMIT 1";
    }
    
    $result = mysqli_query($connection, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die($fail);
    }

    
    // $query = "SELECT id, name, checkin_status FROM `attendees` WHERE checkin_code=\"$checkin_code\"";
    // $result = mysqli_query($connection, $query);

    // if (!$result) {
    //   die('Query Failed.');
    // }

    //https://stackoverflow.com/questions/383631/json-encode-mysql-results
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }

    echo json_encode($rows);

    } else {
        die('$fail');
    }

?>
