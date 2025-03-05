<?php
// cette page est dedier a la connexion en base de donnée 
function dbConnect()
{
    $pdo = new PDO(
        'mysql:host=localhost;dbname=bibliotheque', //serveur et le nom de la base de donnée 
        'root', //identifiant
        '', //mot de passe 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, //erreur SQL
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //fetch par defaut
        ] //tableau des options
    );
    return $pdo;
}