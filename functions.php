<?php

//NOTE: SILAHKAN DISESUAIKAN DENGAN SISTEM ANDA JIKA INGIN MELAKUKAN DYNAMIC TESTING (CSRF, dll)

function pdo_connect(){
    $DATABASE_HOST = 'localhost';
    //Perbaikan CWE-285 - Namun sebelumnya harus membuat user dulu dengan grant seperti berikut
    //GRANT all ON simplecrud.* TO ‘uas’@'localhost’;
    $DATABASE_USER = 'uas';
    //$DATABASE_USER = 'root';
    //Sengaja di comment, ini merupakan code asli
    //$DATABASE_PASS = '';
    //Berikut code perbaikan CWE-512
    $DATABASE_PASS = 'bajdWQIHE@812304912j[aw/21DAkmdm32]/*&(09124sadlADLOER'
    $DATABASE_NAME = 'simplecrud';
    //Berikut perbaikan untuk CWE-200
    //Namun sebelumnya pastikan untuk membuat env variable baru dengan nama MYSQL_SECURE_PASSWORD pada sistem anda
    //yang berisikan password database tersebut
    $DATABASE_PASS = getenv('MYSQL_SECURE_PASSWORD');
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	die ('Failed to connect to database!');
    }
}

function style_script(){
    return '
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>   
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>';
}
