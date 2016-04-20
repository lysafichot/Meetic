<?php 
require_once 'inc/bootstrap.php';

$auth = App::getAuth();
$db = App::getDatabase();

if(!empty($_POST["region"])) {
	$departements = $db->query("SELECT * FROM departements WHERE id_region =' ".$_POST['region']. "'")->fetchAll();

	if(!empty($departements)) { ?>
	   <option value=''>-</option>
		<?php foreach($departements as $value) { ?>
		 <option value="<?php echo $value->id_departement; ?>"><?php echo $value->nom; ?></option> 
		<?php } 
	} 
}

if(!empty($_POST["departement"])) {
	$villes = $db->query("SELECT * FROM villes WHERE id_departement = " .$_POST['departement']. " ORDER BY nom ASC")->fetchAll();

	if(!empty($villes)) { ?>
	   <option value=''>-</option>
		<?php foreach($villes as $value) { ?>
			<option value="<?php echo $value->id; ?>"><?php echo $value->nom; ?></option> 
		<?php } 
	} 
}
?>