<?php

  include('database.php');

  $query = "SELECT * FROM `attendees` WHERE event_id=1 ORDER BY	checkin_status ASC, name ASC";
  $result = mysqli_query($connection, $query);
  if(!$result) {
    die('Query Failed'. mysqli_error($connection));
  }

  $json = array();
  while($row = mysqli_fetch_array($result)) {
    $json[] = array(
      'name' => $row['name'],
      'checkin_status' => $row['checkin_status'],
      'id' => $row['id']
    );
  }
  $jsonstring = json_encode($json);
  echo $jsonstring;
?>
