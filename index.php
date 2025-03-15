<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TB_Lytics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link href="./assets/css/style1.css" rel="stylesheet" type="text/css">
    <style>
        #doudou{
    background-image: url('./img/aziz.jpg'); /* Chemin de l'image */
    background-size: cover; /* L'image couvre toute la largeur et la hauteur */
    background-position: center; /* Centre l'image */
    background-repeat: no-repeat; /* Pas de répétition */
    background-attachment: fixed; /* L'image reste fixe lors du défilement */
    margin: 0; /* Supprime les marges par défaut */
    height: 100vh; /* Assure que le body prend toute la hauteur */
}
    </style>
</head>
<body id="doudou" >
    <!-- En-tête -->
    <?php include './includes/header.php'; ?>

    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Section principale -->
   <!-- Section principale -->
<main id="bingbing">
    <div class="container text-center">
        <div class="card shadow-lg p-4 custom-card" style="max-width: 800px; margin: auto; opacity:0.9;">
            <h2 class="mb-4"><img src="./img/lst3.png" alt="TBL Logo" class="me-3" id="im23445" style="width: 250px; height: 90px; margin: -25px;"></h2>
            <p class="mb-4">Donnez du sens à vos chiffres : simplifiez la gestion, optimisez vos performances</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="./pages/login.php" class="btn btn-primary btn-lg" id="setif19"><i class="fas fa-sign-in-alt"></i> Se connecter</a>
                <a href="./pages/signup.php" class="btn btn-warning btn-lg" style="color: #18534F;"><i class="fas fa-user-plus"></i> Créer un compte</a>
            </div>
        </div>
    </div>
</main>

<!-- Section À propos -->
<section id="about" class="container about-section text-center" style="max-width: 800px; margin: auto;  opacity:0.9;">
    <h3 class="mb-3">À propos</h3>
    <p><strong>TB Lytics</strong>, créé par <strong>Rabah TOUBAL</strong>, étudiant et passionné d’informatique, a été conçu pour aider les administrateurs et les directeurs à organiser, gérer et analyser leurs données avec rapidité et efficacité</p>

</section>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Pied de page -->
    <?php include './includes/footer1.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
