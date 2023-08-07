<?PHP
    $password_SQL = "";
    $database_name = "child_language";
    $condb = mysqli_connect("localhost", "root", $password_SQL, $database_name);

    // Check the connection
    if (!$condb) {
        die("Database connection failed: " . mysqli_connect_error());
    }
?>