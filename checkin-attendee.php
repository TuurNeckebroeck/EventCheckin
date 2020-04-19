<?php

  include('database.php');

if(isset($_POST['checkin_code'])) {
  # echo $_POST['name'] . ', ' . $_POST['description'];
  // $task_name = $_POST['name'];
  // $task_description = $_POST['description'];
  $checkin_code = $_POST['checkin_code'];

  //INSERT INTO `checkins` (`id`, `attendee_id`, `nb_tickets`, `timestamp`) VALUES (NULL, '3', '1', CURRENT_TIMESTAMP);
  if(is_numeric($checkin_code)) {
    // checkin code is effectief checkin code
    $query = "INSERT INTO `checkins` (`attendee_id`, `nb_tickets`, `timestamp`)".
      "SELECT attendees.id, '1', CURRENT_TIMESTAMP FROM attendees WHERE attendees.checkin_code = \"$checkin_code\"";
  } else {
    // checkin code is naam
    $query = "INSERT INTO `checkins` (`attendee_id`, `nb_tickets`, `timestamp`)".
    "SELECT attendees.id, '1', CURRENT_TIMESTAMP FROM attendees WHERE attendees.name LIKE \"%$checkin_code%\" LIMIT 1";
  }
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die('Query Failed.');
  }
  
  // $query = "SELECT id, name, checkin_status FROM `attendees` WHERE checkin_code=\"$checkin_code\"";
  // $result = mysqli_query($connection, $query);

  // if (!$result) {
  //   die('Query Failed.');
  // }

  echo "Checked in $result";  

} else {
    //echo "Error";
    echo implode(" ",$_POST);
}

?>
