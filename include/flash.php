<?php if(Session::getInstance()->hasFlashes()): ?>
	<?php foreach(Session::getInstance()->getFlashes() as $type => $message): ?>
		<div class="alert alert-<?= $type; ?>">
			<?= $message; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php if(!empty($errors)): ?>
	<div class="alert alert-danger">
		<p>Vous n'avez pas rempli le formulaire correctement</p>
		<ul>
			<?php foreach($errors as $error): ?>
				<li><?= $error; ?></li>
				<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>