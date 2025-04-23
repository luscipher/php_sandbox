<?php

$is_invalid = false;

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf("SELECT * FROM user
                            WHERE email = '%s'",
                            $mysqli->real_escape_string($_POST["email"]));
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if($user){
        if(password_verify($_POST["password"], $user["password_hash"])){
            session_start();
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];

            header("Location: index.php");
            exit;
        }
    }
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta charset="utf-8">

    </head>
    <body>
        <h1>Login</h1>

        <?php if ($is_invalid): ?>
            <em>Invalid login</em>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="email">email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
           
            <button>Login</button>
        </form>
    </body>
</html>