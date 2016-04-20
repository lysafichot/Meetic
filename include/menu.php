<div id="menu">
    <header>
        <div id='titre'>
            <h1>Soul mate</h1>
            <img src="css/img/logo.png" alt="logo">
        </div>
    </header>
    <div class='empty'></div>
        <?php if(Session::getInstance()->hasFlashes()): ?>
            <?php foreach(Session::getInstance()->getFlashes() as $type => $message): ?>
                <div class="alert alert-<?= $type; ?>">
                    <?= $message; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <nav> 
        <div class="menu"><a href="home.php">Home</a></div>
        <div class="menu"><a href="profil.php?id=<?php echo $user_id; ?>">My profil</a></div>
        <div class="menu"><a href="messagerie.php?id=<?php echo $user_id; ?>">My emails</a></div>
        <div class="menu"><a href="account.php">My account</a></div>
        <div class="menu">
            <?php if (isset($_SESSION['auth'])): ?>
               <a href="logout.php">Se déconnecter</a>
            <?php endif; ?>
        </div>
    </nav>
</div>
