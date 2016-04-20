<?php
require 'inc/bootstrap.php';
$db = App::getDatabase();
App::getAuth()->restrict();
$auth = App::getAuth()->checkId($db, $_GET['id']);

if(isset($_GET['id'])) {
    if($auth) {
        $user_profil = $_GET['id'];
    } else{
       App::redirect('home.php');
    }
} else {
       App::redirect('home.php');
    }

$user_id = $_SESSION['auth']->id;

$user = $db->query('SELECT * FROM users WHERE id = :id', ['id' => $user_profil])->fetch();
$genre = $db->query('SELECT * FROM sexe WHERE id_sexe = :sexe', ['sexe' => $user->id_sexe])->fetch();

$sql = 'SELECT villes.nom AS ville , villes.code_postal, villes.id_departement,
departements.id_departement, departements.nom AS departement, departements.id_region, 
regions.id_region, regions.nom AS region, users.id, users.code_postal FROM users 
LEFT JOIN villes ON users.code_postal = villes.code_postal 
LEFT JOIN departements ON villes.id_departement = departements.id_departement 
LEFT JOIN regions ON departements.id_region = regions.id_region 
WHERE users.id = :id';

$live = $db->query(''.$sql.'', ['id' => $user_profil])->fetch();
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="description" content=""/>
    <link type= "text/css" rel="stylesheet" href="css/header.css" />
    <link type= "text/css" rel="stylesheet" href="css/profil.css" />

    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/toggle_modif.js"></script>

    <title>My meetic</title>
</head>

<body>
    <?php include 'include/menu.php' ?>
    <div id="container">
        <div id="profil">
            <div id='info'>
                <div id='avatar'><img src='<?= $user->avatar; ?>' alt='avatar' /><br></div>
                <div id="legende">
                    <div id="name">
                        <div id='username'><strong><?= ucfirst($user->username). ' - ' .App::getAuth()->age($db, $user_profil); ?></strong></div>
                        <div id='ville'><strong><?= $live->ville ?></strong></div>
                    </div>

                    <div id='supplement'>
                        <div><span>Sexe : </span><strong><?= $genre->sexe; ?></strong></div>
                        <div><span>Birthday : </span><strong><?= $user->birthday; ?></strong></div>
                        <div><span>Code postal : </span><strong><?= $user->code_postal ?></strong></div>
                        <div><span>Departement : </span><strong><?= $live->departement?></strong></div>
                        <div><span>Region : </span><strong><?= $live->region?></strong></div>
                    </div>
                    <div id="send"><a href="messagerie.php?from=<?= $user_id; ?>&&to=<?= $_GET['id']; ?>"><img src='css/img/mail.png' alt='icone_enveloppe'/></a></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>