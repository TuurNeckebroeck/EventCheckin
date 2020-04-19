<?php
    include('database.php');
    $fail = "false";

    $nb_checkins = 10;
    if(isset($_GET['nb_checkins']) && is_int($_GET['nb_checkins'])) {
        $nb_checkins = intval($_POST['nb_checkins']);
    }

    $query = "SELECT c.id, a.name, c.nb_tickets, (SELECT SUM(c1.nb_tickets) FROM checkins c1 WHERE c1.attendee_id=c.attendee_id GROUP BY c1.attendee_id) as \"total_tickets\", c.timestamp\n"
    . "FROM checkins c\n"
    . "INNER JOIN attendees a\n"
    . "	ON a.id = c.attendee_id\n"
    . "ORDER BY c.id DESC";
    // echo "$query";

    $result = mysqli_query($connection, $query);
    if(!$result) {
        die("false");
    }

    $json = array();
    while($row = mysqli_fetch_array($result)) {
        $json[] = array(
        'id' => $row['id'],
        'name' => $row['name'],
        'nb_tickets' => $row['nb_tickets'],
        'total_tickets' => $row['total_tickets'],
        'timestamp' => $row['timestamp'],
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
?>

