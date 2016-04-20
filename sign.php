<?php
require_once 'inc/bootstrap.php';

$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);

if($auth->user()){
	App::redirect('home.php');
}

$sexe_select = $db->query('SELECT id_sexe, sexe FROM sexe')->fetchAll();


if(!empty($_POST)) {
	$validator = new Validator($_POST);

	$validator->isDate('birthday', "La date n'est pas au bon format");
	if($validator->isValid()) {
		$validator->isMajeur('birthday', 'Vous etes mineur !!!');
	}

	$validator->isCodep('code_postal', $db, 'villes', "Code postal invalide");

	$validator->isAlpha('username', "Votre pseudo n'est pas valide (alphanumérique)");
	if($validator->isValid()) {
		$validator->isUniq('username', $db, 'users', 'Ce pseudo est déjà pris');
	}

	$validator->isEmail('email', "Votre email n'est pas valide");
	if($validator->isValid()) {
		$validator->isUniq('email', $db, 'users', 'Cet email est déjà utilisé pour un autre compte');
	}

	$validator->isConfirmed('password', 'Vous devez rentrer un mot de passe valide');

	$uploaddir = 'css/img/avatar/';

	if(!empty($_FILES['avatar']['name'])) {
		$validator->isSize('avatar', 'You picture is too oversized');
		$validator->isExtension('avatar', 'You must upload a type of picture with png, gif, jpg, jpeg, please.');
		$uploadfile = basename($_FILES['avatar']['name']);
		$uploadfile = strtr($uploadfile, 
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); 
		$avatar = $uploaddir.$uploadfile;
	}
	else {
		$avatar = $uploaddir.'default.png';
	}

	if($validator->isValid()) { 

		App::getAuth()->register($db, htmlspecialchars($_POST['firstname']), htmlspecialchars($_POST['lastname']), $_POST['sexe'], htmlspecialchars($_POST['birthday']), htmlspecialchars($_POST['code_postal']), htmlspecialchars($avatar),
			htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['email']));

		Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte');

		App::redirect('login.php'); 
	} else {
		$errors = $validator->getErrors();
	}
} 
?>

<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
	<meta name="description" content=""/>
	<link type= "text/css" rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-2.1.4.js"></script>
	<script type="text/javascript" src="js/autocomplete.js"></script>
	<script type="text/javascript" src="js/filereader.js"></script>

	<title>My meetic</title>
</head>

<body>
	<header>
		<div id='logo'>
			<h1>Soul Mate</h1>
		</div>
		<div id='log_in'>
			<span>Already a member ? </span>
			<a href="login.php">Log In</a>
		</div>
	</header>

	<div class='empty'></div>

	<div id='signlog_outer'>
		<div id='sign_container'>
			<h2>Sign in</h2>
			<?php include 'include/flash.php' ?>

			<form enctype="multipart/form-data" method='POST' action="#" name="sign" id='sign'>
				<div id="data">
					<div id="sign_profil">
						<div class="button"><label for="firstname">Firstname : </label><input type="text" name="firstname" id="firstname" value="<?php  App::recall('firstname'); ?>"><br></div>
						<div class="button"><label for="lastname">Lastname : </label><input type="text" name="lastname" id="lastname" value="<?php App::recall('lastname'); ?>"><br></div>
						<div class="button">
							<label for="sexe">Sexe : </label>
							<select name="sexe" id="sexe">
								<?php foreach($sexe_select as $value) { ?>
								<option value="<?php echo $value->id_sexe; ?>"><?php echo $value->sexe; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="button"><label for="birthday">Birthday : </label><input type="text" name="birthday" id="birthday" placeHolder="YYYY/MM/dd" value="<?php App::recall('birthday'); ?>"/><br></div>
	
						<div class="button"><label for="search_cp">Code postal : </label><input type="text" name="code_postal" id="search_cp" maxlength="5" value="<?php App::recall('code_postal'); ?>" /></div>
						<div id="list_city"></div>
						<div class="button"><label for="search_city">City : </label><input type="text" name="city" id="search_city" value="<?php  App::recall('city'); ?>"/></div>
						
						<div class="button"><label for="avatar">Avatar : </label><input type="file" name="avatar" id="avatar" accept="image/*"><br></div>
					</div>
					<div id="sign_account">
						<div class="button"><label for="username">User : </label><input type="text" name="username" id="username" value="<?php  App::recall('username'); ?>"><br></div>
						<div class="button"><label for="email">Email : </label><input type="email" name="email" id="email" value="<?php  App::recall('email'); ?>"><br></div>
						<div class="button"><label for="password">Password : </label><input type="password" name="password" id="password" value=""><br></div>
						<div class="button"><label for="password_confirm">Password confirm : </label><input type="password" name="password_confirm" id="password_confirm" value=""><br></div>
						<div id="img"></div>
					</div>
				</div>
				<input id= "valid_sign" type="submit" name="valid_sign" value="Sign up !">
			</form>	
		</div>
	</div>
	<div class="empty"></div>
	<footer></footer>
</body>
</html>