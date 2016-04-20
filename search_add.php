<?php 
require_once 'inc/bootstrap.php';

$auth = App::getAuth();
$db = App::getDatabase();


if(!empty($_POST["keyword"])) {
	$city = $db->query("SELECT * FROM villes WHERE nom like '" . $_POST["keyword"] . "%' ORDER BY nom LIMIT 0,6")->fetchAll();

	if(!empty($city)) { ?>
		<ul> 
		<?php foreach($city as $value) { ?>
			<li class="city"><?php echo $value->nom; ?></li>
		<?php } ?>
		</ul> 
	<?php } 
}
?>