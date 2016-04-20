
<?php 
require_once 'inc/bootstrap.php';

$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);
if($auth->user()){
	App::redirect('home.php');
}

if(!empty($_POST["keyword"])) {
	$city = $db->query("SELECT * FROM villes WHERE code_postal like '" . $_POST["keyword"] . "%' ORDER BY nom LIMIT 0,6")->fetchAll();

	if(!empty($city)) { ?>
		<ul> 
		<?php foreach($city as $value) { ?>
				<li class="city" ><?php echo $value->nom; ?></li>
				<li class="cp" style="display: none;"><?php echo $value->code_postal; ?></li>
		<?php } ?>
		</ul> 
	<?php } 
} 
?>