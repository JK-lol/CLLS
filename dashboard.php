<?php
session_start();
// If the user is not logged in, redirect to login page
include('connection.php');
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
$username = $_SESSION['username'];
?>

<?php include('header.php'); ?>

<table class="dashboard">
  <tr>
    <th>
      <button type="button" class="hover" onclick="window.location.href='video.php'">
        <img src="asset/tutorial.jpg" width="80" height="80"><br>Tutorial Video
      </button>
    </th>
    <th>
      <button type="button" class="hover" onclick="window.location.href='selectstage.php'">
        <img src="asset/stage1.png" width="80" height="80"><br>Select Stage
      </button>
    </th>
    <th>
      <button type="button" class="hover" onclick="window.location.href='leaderboard.php'">
        <img src="asset/podium.png" width="80" height="80"><br>View Leaderboard
      </button>
    </th>
  </tr>
</table>

<?php include('footer.php'); ?>
