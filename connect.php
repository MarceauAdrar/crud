<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=adrar', "root", "");
} catch (PDOException $e) {
    throw $e;
}
