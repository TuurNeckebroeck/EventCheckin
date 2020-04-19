<?php

$connection = mysqli_connect(
  'mysql_host', 'username', 'password', 'db_name'
);

// for testing connection
#if($connection) {
#  echo 'database is connected';
#}

?>
