<?php
session_start();
include('connection.php');
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
$username = $_SESSION['username'];
$stage = $_GET['stage'];

include('header.php');
?>

<h2 class="shadow">Welcome to Quiz <?php echo $stage; ?>!</h2><br><br><br>

<?php

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Check if a question number is provided
    if (isset($_GET['question'])) {
        $quesNum = $_GET['question'];

        // Retrieve the specific question based on the question number and stage
        $getQuestion = mysqli_prepare($condb, "
            SELECT * FROM questions WHERE question_level = ? LIMIT ?, 1
        ");
        mysqli_stmt_bind_param($getQuestion, "ii", $stage, $quesNum);
        mysqli_stmt_execute($getQuestion);
        $questionResult = mysqli_stmt_get_result($getQuestion);

        if ($questionResult && mysqli_num_rows($questionResult) > 0) {
            $question = mysqli_fetch_assoc($questionResult);

            // Display the question and options
            echo '<div class="question_container">';
            echo "<h2>Question " . ($quesNum + 1) . "</h2>";
            echo "<p>" . nl2br($question['question']) . "</p>";

            // Handle user selection
            if (isset($_POST['selected_option'])) {
                $selectedOption = $_POST['selected_option'];

                $points = 0;


                // Check if the selected option is correct
                $correctOption = $question['correct_answer'];
                if ($selectedOption == $correctOption) {
                    echo "<p>Your answer is correct!</p>";
                    $_SESSION['correctCounter']++; // Increment the counter for correct answers
                    $remainingTime = $_POST['remainingTime'];
                    echo "Remaining Time: $remainingTime";
                    if ($remainingTime >= 30) {
                        $points = 10;
                    } else {
                        $points = 5;
                    }
                } else {
                    echo "<p>Your answer is wrong. Better luck next time!</p>";
                }

                $_SESSION['totalPoints'] += $points; // Add points to the total                

                // Retrieve the user's current score for the stage from the database
                $getUserScore = mysqli_prepare($condb, "SELECT level" . $stage . "_score FROM users WHERE username = ?");
                mysqli_stmt_bind_param($getUserScore, "s", $username);
                mysqli_stmt_execute($getUserScore);
                $scoreResult = mysqli_stmt_get_result($getUserScore);
                $row = mysqli_fetch_assoc($scoreResult);
                $currentScore = $row['level' . $stage . '_score'];

                // Update the score in the session only if the new score is higher than the old score
                if ($_SESSION['totalPoints'] > $currentScore) {
                    $_SESSION['level' . $stage . '_score'] = $_SESSION['totalPoints'];
                }

                // Display the time remaining message
                $countdown = 3;
                echo "<p>Redirecting to the next question in <span id='redirectTimer'>$countdown</span> seconds...</p>";

                // Proceed to the next question automatically after a specified time (e.g., 3 seconds)
                $nextQuestion = $quesNum + 1;
                echo "<script>
                    var countdown = $countdown;
                    var redirectTimer = document.getElementById('redirectTimer');
                    if(redirectTimer) {
                        var countdownInterval = setInterval(function() {
                            countdown--;
                            redirectTimer.textContent = countdown;
    
                            if (countdown === 0) {
                                clearInterval(countdownInterval);
                                window.location.href = 'stage.php?username=$username&stage=$stage&question=$nextQuestion';
                            }
                        }, 1000);
                    }

                    
                </script>";
                exit;
            }

            // Display the options with form submission
            echo "<form method='post' action='stage.php?username=$username&stage=$stage&question=$quesNum'>";
            echo "<button type='submit' name='selected_option' value='A'>A. " . $question['option_1'] . "</button>";
            echo "<input  hidden id='remainingTimeInput' name='remainingTime' type='number'>";
            echo "<button type='submit' name='selected_option' value='B'>B. " . $question['option_2'] . "</button><br>";
            echo "<button type='submit' name='selected_option' value='C'>C. " . $question['option_3'] . "</button>";
            echo "<button type='submit' name='selected_option' value='D'>D. " . $question['option_4'] . "</button><br>";
            echo "</form>";
            echo "<div id='questionTimer'></div>";
            echo "<div id='points'></div>";
            echo "</div>";
        } else {
            // No more questions to display, show the quiz completion message and score
            $correctCounter = isset($_SESSION['correctCounter']) ? $_SESSION['correctCounter'] : 0;
            $totalPoints = isset($_SESSION['totalPoints']) ? $_SESSION['totalPoints'] : 0;

            // Retrieve the user's current score for the stage from the database
            $getUserScore = mysqli_prepare($condb, "SELECT level" . $stage . "_score FROM users WHERE username = ?");
            mysqli_stmt_bind_param($getUserScore, "s", $username);
            mysqli_stmt_execute($getUserScore);
            $scoreResult = mysqli_stmt_get_result($getUserScore);
            $row = mysqli_fetch_assoc($scoreResult);
            $currentScore = isset($row['level' . $stage . '_score']) ? $row['level' . $stage . '_score'] : 0;

            // Update the score in the database only if the new score is higher than the old score
            if ($totalPoints > $currentScore) {
                $updateScore = mysqli_prepare($condb, "UPDATE users SET level" . $stage . "_score = ? WHERE username = ?");
                mysqli_stmt_bind_param($updateScore, "is", $totalPoints, $username);
                mysqli_stmt_execute($updateScore);
            }

            // Clear session variables
            $_SESSION['quesNum'] = 0;
            $_SESSION['correctCounter'] = 0;
            $_SESSION['totalPoints'] = 0;

            echo "<div class='result_container'>";
            echo "<p>Congratulations, you have completed the quiz!</p>";
            echo "<p>Your score: $correctCounter / $quesNum</p>";
            echo "<p>Total points: $totalPoints</p>";
            echo "<button type='button' onclick=\"window.location.href='stage.php?username=$username&stage=$stage&question=0'\">Play Again</button> <br>";
            echo "<button type='button' onclick=\"window.location.href='selectstage.php?username=$username'\">Play Another Stage</button>";
            echo "</div>";
        }
    } else {
        // Start the quiz from the first question
        $_SESSION['quesNum'] = 0; // Initialize the question number in the session
        $_SESSION['correctCounter'] = 0; // Initialize the counter for correct answers
        $_SESSION['totalPoints'] = 0; // Initialize the total points in the session

        echo "
            <div class='startQuiz-container'>
                <p>START YOUR JOURNEY WITH DINO!</p>
                <div class='dino'></div>
            </div>
        ";

        echo "<button type='button' class='button_display hover' onclick=\"window.location.href='stage.php?username=$username&stage=$stage&question=0'\">Start Quiz</button>";
    }
} else {
    echo "<p>An error occurred. Please try again.</p>";
}
?>

<?php include('footer.php'); ?>

<script>
    var countdownInterval; // Declare the countdownInterval variable outside the function for global access
    var remainingTime = 60;
    const remainingTimeInput = document.getElementById("remainingTimeInput")

    function countdown() {
        var questionTimer = document.getElementById('questionTimer');
        // questionTimer.innerText = 60 + " seconds remaining";
        if (questionTimer) {
            countdownInterval = setInterval(function() {
                if (remainingTime > 0) {
                    remainingTime--;

                    if (remainingTimeInput) remainingTimeInput.value = remainingTime
                    console.log(remainingTime)
                    if (remainingTimeInput) console.log(remainingTimeInput.value)
                    // Update the remaining time displayed on the page
                    if (questionTimer) questionTimer.innerText = remainingTime + " seconds remaining";
                } else {
                    questionTimer.textContent = "Finished";
                    clearInterval(countdownInterval);

                    // Redirect to the next question
                    var username = "<?php echo $username; ?>";
                    var stage = "<?php echo $stage; ?>";
                    var nextQuestion = "<?php echo ($quesNum + 1); ?>";
                    window.location.href = "stage.php?username=" + username + "&stage=" + stage + "&question=" + nextQuestion;
                }
            }, 1000);
        }

    }

    window.onload = function() {
        // remainingTime = <?php /* echo $remainingTime; */ ?>;
        countdown();
    };
</script>


</body>

</html>