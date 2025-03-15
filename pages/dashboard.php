<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TBL Data Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../assets/css/dashboardstyle.css" rel="stylesheet"  type="text/css">
    <style>
        #doudou{
    background-image: url('../img/aziz.jpg'); /* Chemin de l'image */
    background-size: cover; /* L'image couvre toute la largeur et la hauteur */
    background-position: center; /* Centre l'image */
    background-repeat: no-repeat; /* Pas de répétition */
    background-attachment: fixed; /* L'image reste fixe lors du défilement */
    margin: 0; /* Supprime les marges par défaut */
    height: 100vh; /* Assure que le body prend toute la hauteur */
}
/* Sidebar cachée par défaut */
/* Sidebar */
.sidebar {
    width: 250px;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #18534F;
    color: white;
    transition: transform 0.3s ease;
    transform: translateX(-100%); /* Cacher par défaut */
    z-index: 1000;
    padding: 0; /* Supprimer tout espace intérieur */
}

.sidebar.visible {
    transform: translateX(0); /* Afficher quand visible */
}

/* Contenu principal */
.d-flex {
    margin-left: 0; /* Pas d'espace initial */
    transition: margin-left 0.3s ease; /* Transition fluide pour le contenu */
}

.d-flex.sidebar-visible {
    margin-left: 250px; /* Ajuster selon la largeur de la sidebar */
}

/* En-tête */


/* Supprimer l'espace global */
body {
    margin: 0;
    padding: 0;
}


.card{
    opacity:0.9;
}
#poiz{
    color: #D6955B;
}



#content {
    max-width: 95%; /* Limite la largeur totale du contenu */
    margin: auto; /* Centre le contenu horizontalement */
    padding: 20px; /* Ajoute un espacement autour du contenu */
}

.row.gx-0 {
    margin: 0; /* Supprime les marges globales */
}

.card {
    background-color: #ECF8F6;
    border: 1px solid #FEEAA1;
    border-radius: 10px;
    margin : 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}
.sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #226D68;
        }


    </style>
</head>
<body id="doudou">
<?php
session_start();

// Vérification de l'utilisateur connecté
if (!isset($_SESSION['id']) || !isset($_SESSION['fonction'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Variables pour personnaliser le tableau de bord
$fonction = $_SESSION['fonction'];
$nom_utilisateur = $_SESSION['nom'];
$prenom_utilisateur = $_SESSION['prenom'];
$welcome_message = ($fonction === 'Administrateur Principal') 
    ? '<i class="bi bi-shield-lock-fill"></i> Administrateur Principal'
    : '<i class="bi bi-building"></i> Directeur Régional';?>

<!-- Bouton pour afficher/cacher la sidebar -->
<button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

<div class="d-flex">
    <!-- Barre latérale -->
    <div class="sidebar">
         <img src="../img/lst1.png" alt="TBL Logo" id="im33" style="width: 200px; height: 70px; margin-top:50px; transition: transform 0.3s ease;">

        <a href="./manage_accounts.php"><i class="fas fa-users"></i> Gérer les utilisateurs</a>
        <a href="#"><i class="fas fa-building"></i> Gérer les structures</a>
        <a href="./manage_employees.php"><i class="fas fa-file-alt"></i> Gérer les contrats</a>
        <a href="./manage_archives.php"><i class="fas fa-archive"></i> Voir les archives</a>
        <a href="#"><i class="fas fa-chart-bar"></i> Statistiques</a>
        <a href="../pages/logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
    </div>
    <!-- Contenu principal -->
    <div class="w-100">


        <!-- En-tête -->
        <div id="header" class="header d-flex justify-content-between align-items-center">
            <span class="welcome"> <?php echo $welcome_message; ?> </span>
            <span><i class="fas fa-user-circle"></i> <?php echo $prenom_utilisateur . ' ' . $nom_utilisateur; ?></span>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <!-- Contenu -->
        <div id="content" class="content">
        <h3 id="poiz">Tableau de bord</h3>
    <div class="row gx-0 gy-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card p-3">
                <h5><i class="fas fa-users"></i> Utilisateurs</h5>
                <p>Accédez à la gestion des comptes utilisateurs</p>
                <a href="./manage_accounts.php" class="btn btn-primary">Gérer</a>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card p-3">
                <h5><i class="fas fa-building"></i> Structures</h5>
                <p>Gérez les directions régionales et leur affectation.</p>
                <a href="#" class="btn btn-primary">Gérer</a>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card p-3">
                <h5><i class="fas fa-file-alt"></i> Contrats</h5>
                <p>Gérez les contrats des employés (actifs ou expirés).</p>
                <a href="./manage_employees.php" class="btn btn-primary">Gérer</a>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card p-3">
                <h5><i class="fas fa-archive"></i> Archives</h5>
                <p>Consultez les employés archivés et leurs documents.</p>
                <a href="#" class="btn btn-primary">Voir</a>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card p-3">
                <h5><i class="fas fa-chart-bar"></i> Statistiques</h5>
                <p>Obtenez des statistiques sur les employés et les structures.</p>
                <a href="#" class="btn btn-primary">Voir</a>
            </div>
        </div>
    </div>
        </div>
       <br>
       <br>
       <br>
       <br>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const container = document.querySelector('.d-flex');

    // Basculer la visibilité de la sidebar
    sidebar.classList.toggle('visible');

    // Basculer la classe pour ajuster le contenu principal
    container.classList.toggle('sidebar-visible');
}


</script>
</body>
</html>
