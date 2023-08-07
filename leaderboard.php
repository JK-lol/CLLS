<?PHP
    session_start();
    include('connection.php');
    if (!isset($_SESSION['username'])) {
      header("Location: login.php");
      exit();
    }
    $username = $_SESSION['username'];
?>

<?PHP include('header.php');?>

<h3>Leaderboard</h3>

<form action="" method="POST">
<div class="selectStage">
    <label for="leaderboard">Select a stage to display leaderboard:&nbsp&nbsp&nbsp</label>
    <select name="selectedStage">
      <option value="" selected disabled>Select Stage</option>
      <option value="level1_score">Stage 1</option>
      <option value="level2_score">Stage 2</option>
      <option value="level3_score">Stage 3</option>
      <option value="level4_score">Stage 4</option>
    </select>
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="submit" value="View Leaderboard">
  </div>
</form>

<?php
if (!empty($_POST)) {
  $selectedStage = $_POST['selectedStage'];

  $getLeaderboard = mysqli_prepare($condb, "
    SELECT username, $selectedStage AS score FROM users 
    WHERE $selectedStage IS NOT NULL
    ORDER BY $selectedStage DESC LIMIT 10;
  ");

  mysqli_stmt_execute($getLeaderboard);
  mysqli_stmt_bind_result($getLeaderboard, $username, $score);

  $counter = 1;

  echo '<table class="leaderboard">';
  echo '<tr class ="leaderboard-title">
        <th style="width: 15%;">Ranking</th>
        <th style="width: 70%;">Username</th>
        <th style="width: 15%;">Score</th>
        </tr>';

  // Fetch and display the leaderboard rows
  while (mysqli_stmt_fetch($getLeaderboard)) {
    echo '<tr>';
    echo '<td>' . $counter . '</td>';
    echo '<td>' . $username . '</td>';
    echo '<td>' . $score . '</td>';
    echo '</tr>';
    $counter++;
  }
  echo '</table>';

  mysqli_stmt_close($getLeaderboard);
}
?>

<?PHP include('footer.php');?>
