<?php
require '../includes/config.php';
session_start();

// Vérification de l'administrateur principal
if (!isset($_SESSION['fonction']) || $_SESSION['fonction'] !== 'Administrateur Principal') {
    header("Location: login.php");
    exit();
}

// Variable pour stocker le pseudo pour lequel l'action a été effectuée
$action_pseudo = "";
$message = "";

// Gestion des actions (activer, désactiver, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $pseudo = $_POST['pseudo'];
    $action_pseudo = $pseudo; // Stocker le pseudo pour le message ciblé

    if ($action === 'activate') {
        $stmt = $conn->prepare("UPDATE t_profils_prfls SET etat = 'A' WHERE pseudo = ?");
        $stmt->bind_param("s", $pseudo);
        if ($stmt->execute()) {
            $message = "Le compte a été activé avec succès.";
        } else {
            $message = "Erreur lors de l'activation du compte.";
        }
        $stmt->close();
    } elseif ($action === 'deactivate') {
        $stmt = $conn->prepare("UPDATE t_profils_prfls SET etat = 'D' WHERE pseudo = ?");
        $stmt->bind_param("s", $pseudo);
        if ($stmt->execute()) {
            $message = "Le compte a été désactivé avec succès.";
        } else {
            $message = "Erreur lors de la désactivation du compte.";
        }
        $stmt->close();
    } elseif ($action === 'delete') {
        // Vérification des dépendances avant suppression
        $check_stmt = $conn->prepare("SELECT COUNT(*) AS count FROM t_structures_strcts WHERE directeur_id = ?");
        $check_stmt->bind_param("s", $pseudo);
        $check_stmt->execute();
        $result = $check_stmt->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            $message = "Impossible de supprimer le compte car il est associé à une structure.";
        } else {
            // Supprimer les dépendances dans t_profils_prfls
            $delete_profil_stmt = $conn->prepare("DELETE FROM t_profils_prfls WHERE pseudo = ?");
            $delete_profil_stmt->bind_param("s", $pseudo);
            $delete_profil_stmt->execute();
            $delete_profil_stmt->close();

            // Supprimer le compte dans t_comptes_cmpts
            $delete_account_stmt = $conn->prepare("DELETE FROM t_comptes_cmpts WHERE pseudo = ?");
            $delete_account_stmt->bind_param("s", $pseudo);
            if ($delete_account_stmt->execute()) {
                $message = "Le compte a été supprimé avec succès.";
            } else {
                $message = "Erreur lors de la suppression du compte.";
            }
            $delete_account_stmt->close();
        }
        $check_stmt->close();
    }
}
?>
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
 /* Tableau */
 

        table {
            background-color: #FEEAA1;
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: #18534F;
            color: white;
        }
        td {
            background-color: white;
            color: #18534F;
        }
        tr:hover td {
            background-color: #D6955B;
            color: white;
        }
        .message {
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .hidden {
            display: none;
        }
        .sidebar-hidden {
            margin-left: 0;
        }
        .card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.card-title {
    color: #18534F;
    font-weight: bold;
}

.card-text {
    color: #226D68;
}

.btn-sm {
    margin-right: 5px;
}

/* Style de base de la sidebar */
.sidebar {
    width: 250px;
    height: 100%;
    background-color: #18534F;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    z-index: 1000;
    transition: transform 0.3s ease;
}

/* Masquer la sidebar */
.sidebar.hidden {
    transform: translateX(-100%);
}

/* Style pour le contenu principal et l'en-tête */
#content,
#header {
    transition: margin-left 0.3s ease;
    margin-left: 250px; /* Espace réservé pour la sidebar */
}

/* Quand la sidebar est cachée */
.sidebar-hidden #content,
.sidebar-hidden #header {
    margin-left: 0;
}
#im33:hover {
        transform: scale(1.05); /* Zoom léger au survol */
    }

#ergreg{
    color:#D6955B;
}






/* Boutons personnalisés avec la palette de couleurs */
.btn-custom-green {
    background-color: #18534F;
    color: white;
    border: none;
}

.btn-custom-green:hover {
    background-color: #226D68;
    color: white;
}

.btn-custom-yellow {
    background-color: #FEEAA1;
    color: #18534F;
    border: none;
}

.btn-custom-yellow:hover {
    background-color: #D6955B;
    color: white;
}

.btn-custom-red {
    background-color: #D6955B;
    color: white;
    border: none;
}

.btn-custom-red:hover {
    background-color: #18534F;
    color: white;
}

/* Style des cartes */
.card {
    background-color: #ECF8F6;
    border: 1px solid #FEEAA1;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.card-title {
    color: #18534F;
    font-weight: bold;
    
}

.card-text {
    color: #226D68;
}
.boub {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .boub:hover {
            background-color: #226D68;
        }
    
    </style>
</head>
<body id="doudou">
<?php
// Variables pour personnaliser le tableau de bord
$fonction = $_SESSION['fonction'];
$nom_utilisateur = $_SESSION['nom'];
$prenom_utilisateur = $_SESSION['prenom'];
$welcome_message = ($fonction === 'Administrateur Principal') 
    ? '<i class="bi bi-shield-lock-fill"></i> Administrateur Principal'
    : '<i class="bi bi-building"></i> Directeur Régional';?>

<!-- Bouton pour afficher/cacher la sidebar -->
<button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

<div class="d-flex sidebar-hidden">
    <!-- Barre latérale -->
    <div class="sidebar hidden">
        <a href="./dashboard.php"> <img src="../img/lst1.png" alt="TBL Logo" id="im33" style="width: 200px; height: 70px; transition: transform 0.3s ease;"></a>
   

        <a href="./manage_accounts.php" class="boub"><i class="fas fa-users"></i> Gérer les utilisateurs</a>
        <a href="#" class="boub"><i class="fas fa-building"></i> Gérer les structures</a>
        <a href="./manage_employees.php" class="boub"><i class="fas fa-file-alt"></i> Gérer les contrats</a>
        <a href="#" class="boub"><i class="fas fa-archive"></i> Voir les archives</a>
        <a href="#" class="boub"><i class="fas fa-chart-bar"></i> Statistiques</a>
        <a href="../pages/logout.php" class="boub text-danger"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
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
        <!-- Contenu -->
     <!-- Contenu -->
     <div id="content" class="content">
    <h2 id="ergreg" class="mb-4">Gestion des Comptes</h2>
    <div class="row g-3">
        <?php
        // Récupération des comptes
        $result = $conn->query("
            SELECT p.pseudo, p.nom, p.prenom, p.fonction, p.etat
            FROM t_profils_prfls p
        ");
        while ($row = $result->fetch_assoc()) {
            $highlight = $row['pseudo'] === $action_pseudo ? "border-success" : ""; // Mettre en surbrillance la carte
            echo "<div class='col-md-4'>
                    <div class='card $highlight' style='opacity:0.9;'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$row['nom']} {$row['prenom']}</h5>
                            <p class='card-text'>
                                <strong>Pseudo :</strong> {$row['pseudo']}<br>
                                <strong>Fonction :</strong> {$row['fonction']}<br>
                                <strong>État :</strong> " . ($row['etat'] === 'A' ? 'Activé' : 'Désactivé') . "
                            </p>
                            <div class='d-flex justify-content-between flex-wrap'>
                                <form method='POST'>
                                    <input type='hidden' name='pseudo' value='{$row['pseudo']}'>
                                    <button type='submit' name='action' value='activate' class='btn btn-custom-green btn-sm'>Activer</button>
                                    <button type='submit' name='action' value='deactivate' class='btn btn-custom-yellow btn-sm'>Désactiver</button>
                                    <button type='submit' name='action' value='delete' class='btn btn-custom-red btn-sm'>Supprimer</button>
                                </form>
                            </div>";
            // Afficher le message pour la carte affectée
            if ($row['pseudo'] === $action_pseudo && $message) {
                echo "<div id='alert-message' class='mt-2 text-success'>$message</div>";
            }
            echo "    </div>
                    </div>
                </div>";
        }
        ?>
    </div>
</div>

        

    <br>
        <br>
        <br>
        <br>
        <br>
        <br>
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

    // Bascule la visibilité de la sidebar
    sidebar.classList.toggle('hidden');

    // Ajuste la mise en page en ajoutant/retirant la classe "sidebar-hidden"
    container.classList.toggle('sidebar-hidden');
}

   
    // Faire disparaître le message après 5 secondes
    setTimeout(() => {
        const alert = document.getElementById('alert-message');
        if (alert) {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
        document.querySelectorAll(".border-success").forEach(row => row.classList.remove("border-success"));
    }, 5000);

</script>
</body>
</html>




