<?php
require 'inc/bootstrap.php';
$db = App::getDatabase();
App::getAuth()->restrict();
$user_id = $_SESSION['auth']->id;

if(isset($_GET['from']) && isset($_GET['to']) && isset($_POST['send'])) {
	$from = intval($_GET['from']);
	$to = intval($_GET['to']);
	$message = htmlspecialchars($_POST['send']);

	$db->query("INSERT INTO messagerie SET id_from = ?, id_to = ?, message = ?, date = NOW()", [
	        $from,
	        $to,
	        $message,
	    	]);	
}

$recu = $db->query('SELECT messagerie.id_from, messagerie.id_to, messagerie.message, messagerie.date, messagerie.status, users.username, users.id, users.avatar FROM messagerie 
					LEFT JOIN users ON  messagerie.id_from = users.id WHERE messagerie.id_to = '.$user_id.' AND messagerie.status = 1')->fetchAll();

$supprimer = $db->query('SELECT messagerie.id_from, messagerie.id_to, messagerie.message, messagerie.date, messagerie.status, users.username, users.id, users.avatar FROM messagerie 					LEFT JOIN users ON  messagerie.id_from = users.id WHERE messagerie.id_to = '.$user_id.' AND messagerie.status = 0')->fetchAll();

$envoyer = $db->query('SELECT messagerie.id_from, messagerie.id_to, messagerie.message, messagerie.date, messagerie.status, users.username, users.id, users.avatar FROM messagerie 
					LEFT JOIN users ON  messagerie.id_to = users.id WHERE messagerie.id_from = '.$user_id)->fetchAll();

if(isset($_POST['delete'])) {
	$db->query('UPDATE messagerie SET status = 0 WHERE date = ?', [$_POST['date']]);
}


?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="description" content=""/>
    <link type= "text/css" rel="stylesheet" href="css/header.css" />
    <link type= "text/css" rel="stylesheet" href="css/messagerie.css" />
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/message.js"></script>

    <title>My meetic</title>
</head>

<body>
    <?php include 'include/menu.php' ?>
    <div id='container'>
	    <div id='messagerie'>
		    <div id='boite'>
			    <div id='reception'>
			    	<h2>Boite de reception</h2>
					<div id='barre'><span id='recus'>Recus | </span><span id='envoye'>Envoyes | </span><span id='supprime'>Supprimes</span></div>
			    </div>
			    
			    <div id='recu'>
			    	<?php foreach($recu as $value) { ?>
			    	<div class='sms'>
				    	<a href='messagerie.php?from=<?= $user_id; ?>&&to=<?= $value->id_from; ?>'><span class='avatar'><img src='<?= $value->avatar; ?>' alt='avatar_from'/></span></a>
				    	<div class='info'><p><?= $value->username; ?></p><p><?= $value->message; ?></p><p><?= $value->date; ?></p></div>
				    	<div class='del'>
				    		<form method='POST' action="#" name="sup" class='sup'>
				    			<input type='submit' name='delete' class='delete' value='Delete'/>
				    			<input type='hidden' name='date' value='<?= $value->date; ?>'/>
				    		</form>
				    	</div>
			    	</div>				    	
			    	<?php } ?>
			    </div>
			    <div id='supprimer'>
			    	<?php foreach($supprimer as $value) { ?>
			    	<div class='sms'>
				    	<span class='avatar'><img src='<?= $value->avatar; ?>' alt='avatar'></span>
				    	<div class='info'><p><?= $value->username; ?></p><p><?= $value->message; ?></p><p><?= $value->date; ?></p></div>
			    	</div>				    	
			    	<?php } ?>
			    </div>
			    <div id='envoyer'>
			    	<?php foreach($envoyer as $value) { ?>
			    	<div class='sms'>
				    	<span class='avatar'><img src='<?= $_SESSION['auth']->avatar; ?>' alt='avatar'></span>
				    	<div class='info'><p><?= $_SESSION['auth']->username; ?> to  <?=$value->username; ?></p><p><?= $value->message; ?></p><p><?= $value->date; ?></p></div>
			    	</div>				    	
			    	<?php } ?>
			    </div>
		    </div>
			

			<div id='message'>
				<div class='contact'>
					
				</div>
				<div id='write'>
					<form method='POST' action="#" name="sms" id='sms'>
						<textarea name='send'>
							
						</textarea>
						<input type='submit' name='valid_write' id='valid_write' />
					</form>
				</div>
			</div>

	    </div>
	</div>
</body>
</html>