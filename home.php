<?php
require 'inc/bootstrap.php';
$db = App::getDatabase();
App::getAuth()->restrict();

$user_id = $_SESSION['auth']->id;

$sexe_select = $db->query('SELECT id_sexe, sexe FROM sexe')->fetchAll();

$sql= 'SELECT users.id AS id, users.id_sexe AS id_sexe, users.avatar AS avatar, users.username AS username, users.birthday AS birthday, users.code_postal AS cp, 
        villes.code_postal, villes.nom AS ville, departements.id_departement, villes.id_departement, departements.id_region, regions.id_region 
        FROM users LEFT JOIN villes ON users.code_postal = villes.code_postal 
        LEFT JOIN departements ON villes.id_departement = departements.id_departement 
        LEFT JOIN regions ON departements.id_region = regions.id_region 
        WHERE 42=42';

$regions = $db->query('SELECT id_region, nom FROM regions')->fetchAll();
$departements = $db->query('SELECT id_departement, nom FROM departements')->fetchAll(); 
$villes = $db->query('SELECT id, nom FROM villes ORDER BY nom ASC')->fetchAll(); 


if(isset($_POST['valid_search']) && !empty($_POST['valid_search'])) {
    if(!empty($_POST['sexe'])) {
        $sql.= ' AND id_sexe =' .$_POST['sexe'];
    }

    if(!empty($_POST['age'])) {
        if($_POST['age'] == '18-25') {
            $sql .= ' AND birthday > (SUBDATE(NOW(), INTERVAL 25 YEAR))';
        }
        if($_POST['age'] == '25-35') {
            $sql .= ' AND birthday BETWEEN (SUBDATE(NOW(), INTERVAL 35 YEAR)) AND (SUBDATE(NOW(), INTERVAL 25 YEAR))';
        }
        if($_POST['age'] == '35-45') {
            $sql .= ' AND birthday BETWEEN (SUBDATE(NOW(), INTERVAL 45 YEAR)) AND (SUBDATE(NOW(), INTERVAL 35 YEAR))';
        } 
        if($_POST['age'] == '+45') {
            $sql .= ' AND birthday < (SUBDATE(NOW(), INTERVAL 45 YEAR))';
        } 
    }

    if(!empty($_POST['regions'])) {
        $sql .= ' AND regions.id_region = "'.$_POST['regions'].'"';
    }
    if(!empty($_POST['departements'])) {
        $sql .= ' AND departements.id_departement = "'.$_POST['departements'].'"';
    }
   
    if(!empty($_POST['villes'])) {
        $sql .= ' AND (villes.id = "'.$_POST['villes'].'"';
    }

    if(!empty($_POST['ville0'])) {
         $sql .= " OR villes.nom = '".$_POST["ville0"]."'"; 
    }
    else if(!empty($_POST['villes']) && empty($_POST['ville0'])) {
        $sql .= ')';
        }

    if(!empty($_POST['ville1']) && !empty($_POST['ville0'])) {
         $sql .= " OR villes.nom = '".$_POST["ville1"]."')"; 
    }
    else if(empty($_POST['ville1']) && !empty($_POST['ville0'])) {
        $sql .= ')';
        }
}


$query = $db->query($sql);
$profil = $query->fetchAll();

?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="description" content=""/>
    <link type= "text/css" rel="stylesheet" href="css/header.css" />
    <link type= "text/css" rel="stylesheet" href="css/home.css" />

    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/toggle_modif.js"></script>
    <script type="text/javascript" src="js/search.js"></script>
    <script type="text/javascript" src="js/add_villes.js"></script>

    <title>My meetic</title>
</head>

<body>
    <?php include 'include/menu.php' ?>

    <div id="container">
        <div id="image"></div>
        <div id="search">
            <form action='#' method='POST' name='search'>
                <select name="sexe" id="sexe">
                    <option value="">SEXE</option>
                        <?php foreach($sexe_select as $value) { ?>
                    <option value="<?php echo $value->id_sexe; ?>"><?php echo $value->sexe; ?></option>
                        <?php } ?>
                </select>
    
                <select name='age' id='age'>
                    <option value=''>AGE</option>
                    <option value='18-25'>18-25ans</option>
                    <option value='25-35'>25-35ans</option>
                    <option value='35-45'>35-45ans</option>
                    <option value='+45'>+ 45ans</option>
                </select>

                <div id='where'>
                    <div class='live'>
                       
                        <select name="regions" class="regions">
                        <option value=''>REGIONS</option>
                        <?php foreach($regions as $value) { ?>
                            <option value="<?php echo $value->id_region; ?>"><?php echo $value->nom; ?></option>
                        <?php } ?>
                        </select>

                        <select name="departements" class="departements">
                        <option value=''>DEPARTEMENTS</option>
                        <?php foreach($departements as $value) { ?>
                            <option value="<?php echo $value->id_departement; ?>"><?php echo $value->nom; ?></option>
                        <?php } ?>
                        </select>

                        <select name="villes" class="villes">
                        <option class='villes' value=''>VILLES</option>
                        <?php foreach($villes as $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->nom; ?></option>
                        <?php } ?>
                        </select>
                    </div>

                </div> 
                
                <button id='add'>ADD</button>
                <input id= "valid-search" type="submit" name="valid_search" value="Search !">

            </form> 
            <div id='liste'></div>
        </div>

        <div id='result'>
        <?php foreach($profil as $value) { ?>
        <div class='profil'>
            <div class='info'>
                <a href="profil.php?id=<?= $value->id; ?>"><img src='<?= $value->avatar; ?>' alt='avatar'/></a>
                <span><?php echo ucfirst($value->username); ?></span>
                <span><?= App::getAuth()->age($db, $value->id); ?></span>
                <span><?= $value->ville; ?></span>

            </div> 
        </div>  
        <?php } ?>
        </div>  
    </div>
</body>
</html>