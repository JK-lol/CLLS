<?php
session_start();
include('connection.php');
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
$username = $_SESSION['username'];

// Check if the previous stage is completed
$query = "SELECT score FROM users WHERE username = '$username'";
// Check if the user has completed the previous stage
$result = mysqli_query($condb, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $previousStageScore = $row['score'];

    // Set the required scores for each stage
    $stageScores = [
        1 => 0,
        2 => 75,
        3 => 175,
        4 => 255,
    ];

    // User has completed the previous stage
    include('header.php');
?>

<h3>Welcome! Please Select a Stage:</h3>

<!-- Display the buttons for the stages -->
<div class="button-container">
    <div class="top-buttons hover">
        <?php
        $i = 0;
        foreach ($stageScores as $stageNumber => $requiredScore) {
            $buttonText = "Stage " . $stageNumber;
            $buttonClass = "button-enabled";
            $disabledAttribute = "";

            if ($previousStageScore < $requiredScore) {
                $buttonClass = "button-disabled";
                $buttonText .= "<br>(Please Complete the Previous Stage)";
                $disabledAttribute = "disabled";
            }

            if ($i == 2) {
                break;
            }

            ?>
            <button class="<?php echo $buttonClass; ?>" onclick="location.href='stage.php?stage=<?php echo $stageNumber; ?>'" <?php echo $disabledAttribute; ?>>
                <img src="asset/stage<?php echo $stageNumber; ?>.png"><br><?php echo $buttonText; ?>
            </button>
            <?php
            $i++;
        }
        ?>
    </div>
    <div class="bottom-buttons hover">
        <?php
        $i = 0;
        foreach ($stageScores as $stageNumber => $requiredScore) {
            $buttonText = "Stage " . $stageNumber;
            $buttonClass = "button-enabled";
            $disabledAttribute = "";

            if ($previousStageScore < $requiredScore) {
                $buttonClass = "button-disabled";
                $buttonText .= "<br>(Please Complete the Previous Stage)";
                $disabledAttribute = "disabled";
            }

            if ($i < 2) {
                $i++;
                continue;
            }

            ?>
            <button class="<?php echo $buttonClass; ?>" onclick="location.href='stage.php?stage=<?php echo $stageNumber; ?>'" <?php echo $disabledAttribute; ?>>
                <img src="asset/stage<?php echo $stageNumber; ?>.png"><br><?php echo $buttonText; ?>
            </button>
            <?php
            $i++;
        }
        ?>
    </div>
</div>

<?php
include('footer.php');
} else {
    // Error occurred while fetching the user's data
    $errorMessage = "An error occurred while fetching the user's data. Please try again later.";
    include('error.php'); // Display an error page or a section with the error message
}

mysqli_close($condb);
?>
