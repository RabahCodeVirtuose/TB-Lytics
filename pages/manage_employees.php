<?php
require '../includes/config.php';
session_start();

// Vérification de l'administrateur principal
if (!isset($_SESSION['fonction']) || $_SESSION['fonction'] !== 'Administrateur Principal') {
    header("Location: login.php");
    exit();
}

// Gestion des actions (archiver un employé)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_employe']) && $_POST['action'] === 'archiver') {
    $id_employe = intval($_POST['id_employe']);
    $stmt = $conn->prepare("UPDATE t_employes_emplys SET archivé = 1 WHERE id_employe = ?");
    $stmt->bind_param("i", $id_employe);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_employees.php"); // Actualiser la page
    exit();
}

// Récupération des données pour les compteurs
$total_employes = $conn->query("SELECT COUNT(*) AS total FROM t_employes_emplys")->fetch_assoc()['total'];
$actifs = $conn->query("SELECT COUNT(*) AS total FROM t_employes_emplys WHERE archivé = 0")->fetch_assoc()['total'];
$archives = $conn->query("SELECT COUNT(*) AS total FROM t_employes_emplys WHERE archivé = 1")->fetch_assoc()['total'];

// Récupération des employés actifs avec tris et filtres
$where_clauses = ["archivé = 0"];
$params = [];
$param_types = "";

if (!empty($_GET['search'])) {
    $where_clauses[] = "(
        nom LIKE CONCAT('%', ?, '%') OR 
        prenom LIKE CONCAT('%', ?, '%') OR 
        fonction LIKE CONCAT('%', ?, '%') OR 
        affectation_réelle LIKE CONCAT('%', ?, '%') OR 
        diplome LIKE CONCAT('%', ?, '%')
    )";
    $params[] = $_GET['search'];
    $params[] = $_GET['search'];
    $params[] = $_GET['search'];
    $params[] = $_GET['search'];
    $params[] = $_GET['search'];
    $param_types .= "sssss";
}
if (isset($_GET['sexe']) && $_GET['sexe'] !== '') {
    $where_clauses[] = "sexe = ?";
    $params[] = $_GET['sexe']; // 'M' pour masculin, 'F' pour féminin
    $param_types .= "s"; // 's' pour une valeur de type string
}

if (!empty($_GET['contrat'])) {
    $where_clauses[] = "type_contrat = ?";
    $params[] = $_GET['contrat'];
    $param_types .= "s";
}

if (!empty($_GET['structure'])) {
    $where_clauses[] = "structure_id = ?";
    $params[] = intval($_GET['structure']);
    $param_types .= "i";
}

if (isset($_GET['conformite']) && $_GET['conformite'] !== '') {
    $where_clauses[] = "conformite = ?";
    $params[] = intval($_GET['conformite']); // 0 pour non conforme, 1 pour conforme
    $param_types .= "i";
}

$where_sql = implode(" AND ", $where_clauses);
$sql = "SELECT id_employe, nom, prenom, fonction, affectation_réelle,diplome FROM t_employes_emplys WHERE $where_sql";
$stmt = $conn->prepare($sql);

if ($param_types) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$employes = $result->fetch_all(MYSQLI_ASSOC);
$result_count = count($employes);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés - TBL Data Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../assets/css/dashboardstyle.css" rel="stylesheet"  type="text/css">
    
    <style>
         #doudou {
            background: linear-gradient(135deg, #D6F0EB 20%, #E8E8E8 80%);
            background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    height: 100vh;
}
        /* Styles de la sidebar */
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

    

        /* Styles existants */
        .card {
            background-color: #ECF8F6;
            border-radius: 8px;
            border: 1px solid #FEEAA1;

            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background-color: #18534F;
            border-color: #18534F;
        }
        .btn-primary:hover {
            background-color: #226D68;
            border-color: #226D68;
        }
        .btn-danger {
            background-color: #D6955B;
            border-color: #D6955B;
        }
        .counter {
    background: linear-gradient(135deg, #FEEAA1, #f9d976);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    font-size: 1.2rem;
    font-weight: bold;
    color: #18534F;
    transition: all 0.3s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);
}

.counter:hover {
    transform: scale(1.05);
    box-shadow: 4px 6px 15px rgba(0, 0, 0, 0.2);
}

.counter i {
    font-size: 1.8rem;
    color: #18534F;
}

.btn-add {
    background: linear-gradient(135deg, #18534F, #226D68); /* Dégradé de la palette */
    color: #ECF8F6; /* Texte clair */
    font-weight: bold;
    padding: 12px 15px;
    border-radius: 10px;
    border: none;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-add:hover {
    background: linear-gradient(135deg, #226D68, #18534F); /* Inversion du dégradé au hover */
    color: #ECF8F6; /* Texte clair */

    transform: scale(1.05);
    box-shadow: 4px 6px 12px rgba(0, 0, 0, 0.2);
}

.btn-add i {
    color: #FEEAA1; /* Icône en doré */
}

.btn-add:hover i {
    color: #D6955B; /* Changement d’icône au hover */
}

        #content {
            margin-top: 20px;
        }
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Empêche le débordement horizontal */
        }
        
        #im33:hover {
        transform: scale(1.05); /* Zoom léger au survol */
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

        .form-container {
    max-width: 1000px; /* Réduit la largeur pour éviter qu'elle prenne toute la page */
    margin: 0 auto; /* Centre le formulaire */
    margin-top:10px;
    margin-bottom:10px;

    background: #ECF8F6; /* Fond légèrement teinté */
    padding: 20px;
    border-radius: 12px;
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1); /* Effet de relief */
}

.input-group-text {
    border-radius: 8px 0 0 8px; /* Arrondi uniquement à gauche */
}

.form-control, .form-select {
    border-radius: 0 8px 8px 0; /* Arrondi uniquement à droite */
}

.btn-filter {
    background: linear-gradient(135deg, #18534F, #226D68);
    color: white;
    font-weight: bold;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-filter:hover {
    background: linear-gradient(135deg, #226D68, #18534F);
    transform: scale(1.05);
    color:white;
    box-shadow: 4px 6px 12px rgba(0, 0, 0, 0.2);
}

    </style>
</head>
<body id="doudou">
<?php

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
        <a href="./dashboard.php"> <img src="../img/lst1.png" alt="TBL Logo" id="im33" style="width: 200px; height: 70px; margin-top: 43px; transition: transform 0.3s ease;"></a>
            <a href="./manage_accounts.php" class="boub"><i class="fas fa-users"></i> Gérer les utilisateurs</a>
            <a href="#"  class="boub"><i class="fas fa-building"></i> Gérer les structures</a>
            <a href="./manage_employees.php" class="boub"><i class="fas fa-file-alt"></i> Gérer les contrats</a>
            <a href="#"  class="boub"><i class="fas fa-archive"></i> Voir les archives</a>
            <a href="#"  class="boub"><i class="fas fa-chart-bar"></i> Statistiques</a>
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
        <br>
        <div class="container mt-4">
    <h1 class="text-center mb-4 fw-bold" style="color: #D6955B;">Gestion des Employés</h1>

<!-- Compteurs améliorés -->
<div class="row text-center mb-4">
    <div class="col-md-4">
        <div class="counter shadow-sm">
            <i class="bi bi-people-fill fs-4 me-3"></i>
            <span>Total Employés: <?php echo $total_employes; ?></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="counter shadow-sm">
            <i class="bi bi-person-check-fill fs-4 me-3 text-success"></i>
            <span>Actifs: <?php echo $actifs; ?></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="counter shadow-sm">
            <i class="bi bi-archive-fill fs-4 me-3 text-danger"></i>
            <span>Archivés: <?php echo $archives; ?></span>
        </div>
    </div>
</div>

<!-- Bouton d'ajout en bas -->
<div class="mt-4 text-center">
    <a href="add_employee.php" class="btn btn-add d-inline-flex align-items-center justify-content-center px-4 py-2">
        <i class="bi bi-person-plus-fill fs-5 me-2"></i> Ajouter un Employé
    </a>
</div>


</div>

<!-- Formulaire de recherche et filtres centré -->
<div class="form-container shadow-sm rounded">
    <form method="GET">
        <div class="row g-3">
            <!-- Champ de recherche -->
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un employé" 
                        value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
                </div>
            </div>

            <!-- Sélection du sexe -->
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-secondary text-white"><i class="bi bi-gender-ambiguous"></i></span>
                    <select name="sexe" class="form-select">
                        <option value="">Tous les sexes</option>
                        <option value="M" <?php if (($_GET['sexe'] ?? '') === 'M') echo 'selected'; ?>>Masculin</option>
                        <option value="F" <?php if (($_GET['sexe'] ?? '') === 'F') echo 'selected'; ?>>Féminin</option>
                    </select>
                </div>
            </div>

            <!-- Type de contrat -->
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-info text-white"><i class="bi bi-file-earmark-text"></i></span>
                    <select name="contrat" class="form-select">
                        <option value="">Tous les contrats</option>
                        <option value="CDI" <?php if (($_GET['contrat'] ?? '') === 'CDI') echo 'selected'; ?>>CDI</option>
                        <option value="CDD" <?php if (($_GET['contrat'] ?? '') === 'CDD') echo 'selected'; ?>>CDD</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <!-- Structure -->
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-warning text-dark"><i class="bi bi-buildings"></i></span>
                    <select name="structure" class="form-select">
                        <option value="">Toutes les structures</option>
                        <?php
                        $structures = $conn->query("SELECT id_structure, nom_structure FROM t_structures_strcts")->fetch_all(MYSQLI_ASSOC);
                        foreach ($structures as $structure) {
                            $selected = (($_GET['structure'] ?? '') == $structure['id_structure']) ? 'selected' : '';
                            echo "<option value=\"{$structure['id_structure']}\" $selected>{$structure['nom_structure']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Conformité -->
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-danger text-white"><i class="bi bi-shield-check"></i></span>
                    <select name="conformite" class="form-select">
                        <option value="">Toutes les conformités</option>
                        <option value="1" <?php if (($_GET['conformite'] ?? '') === '1') echo 'selected'; ?>>Conforme</option>
                        <option value="0" <?php if (($_GET['conformite'] ?? '') === '0') echo 'selected'; ?>>Non conforme</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Bouton de validation -->
        <div class="mt-3 text-center">
            <button type="submit" class="btn btn-filter px-4">
                <i class="bi bi-funnel"></i> Appliquer les filtres
            </button>
        </div>
    </form>
</div>



    <div class="col-md-3">
    <div class="counter p-3 shadow-sm d-flex align-items-center justify-content-center">
        <i class="bi bi-search fs-3 me-2 text-primary"></i>
        <span class="fw-bold">Nombre de résultat(s): <?php echo $result_count; ?></span>
    </div>
</div>

    <!-- Liste des employés actifs -->
    <div id="content" class="row g-4">
        <?php foreach ($employes as $employe): ?>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">Nom: <?php echo $employe['nom']; ?></h5>
                        <h5 class="card-title text-secondary fw-bold">Prénom: <?php echo $employe['prenom']; ?></h5>
                        <p class="card-text">
                            <strong>Fonction:</strong> <?php echo $employe['fonction']; ?><br>
                            <strong>Affectation réelle:</strong> <?php echo $employe['affectation_réelle'] ?? 'Non spécifiée'; ?>
                        </p>
                        <div class="d-flex justify-content-between">
                            <a href="view_employee.php?id=<?php echo $employe['id_employe']; ?>" class="btn btn-primary btn-sm">Consulter</a>
                            <form method="POST">
                                <input type="hidden" name="id_employe" value="<?php echo $employe['id_employe']; ?>">
                                <button type="submit" name="action" value="archiver" class="btn btn-danger btn-sm">Archiver</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

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