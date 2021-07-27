<?php
include 'functions.php';
$pdo = pdo_connect();

//Penanganan CWE-352
session_start();
function gentoken() {
    return bin2hex(rand(100000, 999999));
}

function bikintoken() {
    $token = gentoken();
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();
    return $token;
}

function bikintag() {
    $tokennya = bikintoken();
    return '<input type="hidden" name="csrf_token" value="' . $tokennya . '">';
}

function cek_token() {
    if (!isset($_POST['csrf_token'])) {
        return false;
    }
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return ($_POST['csrf_token'] === $_SESSION['csrf_token']);
}

if (cek_token()) {
    if (!empty($_POST)) {
        //Penanganan CWE-790
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        //$name = $_POST['name'];

        //Penanganan CWE-20
        if ((!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) === false )) {
            $email = $_POST['email'];
            if ((!filter_input(INPUT_POST, "phone", FILTER_VALIDATE_INT) === false )) {
                $phone = $_POST['phone'];
                $title = $_POST['title'];
                $created = date('Y-m-d H:i:s');
                // Insert new record into the contacts table
                $stmt = $pdo->prepare('INSERT INTO contacts VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$id, $name, $email, $phone, $title, $created]);
                header("location:index.php");
            } else {
                echo("Phone is not valid");
            }
        } else {
            echo("Email is not valid");
        }
    }
}  else {
    echo "Request tidak valid. jangan coba di serang.<br>";
    echo "Muhammad Novrizal Ghiffari | III RPLK";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?=style_script()?>
    <title>Document</title>
</head>
<body>

<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="card">
        <div class="card-body">
        <h5 class="card-title">Create contact</h5>
                    <form action="create.php" method="post">
                        <input class="form-control form-control-sm" placeholder="Type name" type="text" name="name" id="name" required><br>
                        <input class="form-control form-control-sm" placeholder="Email" type="text" name="email" id="email" required><br>
                        <input class="form-control form-control-sm" placeholder="Phone number" type="text" name="phone" id="phone" required><br>
                        <input class="form-control form-control-sm" placeholder="Title" type="text" name="title" id="title" required><br>
                        <?php echo bikintag(); ?>
                        <input class="btn btn-primary btn-sm" type="submit" value="Create">
                        <a href="index.php" type="button" class="btn btn-warning btn-sm">Cancel</a>
                    </form>
                </div>
                <div class="col-md-7 col-sm-12 col-xs-12">
                
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
