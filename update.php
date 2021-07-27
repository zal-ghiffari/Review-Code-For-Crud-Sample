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
    if (isset($_GET['id'])) {
        if (!empty($_POST)) {
            //Penanganan CWE-790
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
            //$name = $_POST['name'];
            //penanganan CWE-20
            if ((!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) === false )) {
                $email = $_POST['email'];
                if ((!filter_input(INPUT_POST, "phone", FILTER_VALIDATE_INT) === false )) {
                    $phone = $_POST['phone'];
                    $title = $_POST['title'];
                    // Insert new record into the contacts table
                    $stmt = $pdo->prepare('UPDATE contacts SET name = ?, email = ?, phone = ?, title = ? WHERE id = ?');
                    $stmt->execute([$name, $email, $phone, $title, $_GET['id']]);
                    header("location:index.php");
                } else {
                    echo("Phone is not valid");
                }
            } else {
                echo("Email is not valid");
            }
        }
    
        $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$contact) {
            die ('Contact doesn\'t exist!');
        }
    } else {
        die ('No ID specified!');
    }
} else {
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
            <h5 class="card-title">Update contact # <?=$contact['id']?></h5>                    
                
                
                <form action="update.php?id=<?=$contact['id']?>" method="post">
                    <input class="form-control form-control-sm" placeholder="Type name" type="text" name="name" value="<?=$contact['name']?>" id="name" required><br>
                    <input class="form-control form-control-sm" placeholder="Email" type="text" name="email" value="<?=$contact['email']?>" id="email" required><br>
                    <input class="form-control form-control-sm" placeholder="Phone number" type="text" name="phone" value="<?=$contact['phone']?>" id="phone"><br>
                    <input class="form-control form-control-sm" placeholder="Title" type="text" name="title" value="<?=$contact['title']?>" id="title"><br>
                    <?php echo bikintag(); ?>
                    <input class="btn btn-primary btn-sm" type="submit" value="Update">
                    <a href="index.php" type="button" class="btn btn-warning btn-sm">Cancel</a>
                    
                </form>
            </div>
        </div>
        </div>
        <div class="col-md-7 col-sm-12 col-xs-12">
        </div>        
    </div>
</div>
</body>
</html>