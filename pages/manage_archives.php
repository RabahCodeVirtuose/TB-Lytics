<?php
require '../includes/config.php';
session_start();

// Vérification de l'administrateur principal
if (!isset($_SESSION['fonction']) || $_SESSION['fonction'] !== 'Administrateur Principal') {
    header("Location: login.php");
    exit();
}

// Suppression définitive d'un employé et de ses fichiers
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_employe'])) {
    $id_employe = intval($_POST['id_employe']);
    
    if ($_POST['action'] === 'supprimer') {
        // Supprimer le dossier contenant les fichiers de l'employé
        $dossier_path = "../dossiers/" . $id_employe;
        if (is_dir($dossier_path)) {
            array_map('unlink', glob("$dossier_path/*")); // Supprime les fichiers
            rmdir($dossier_path); // Supprime le dossier
        }
        
        // Supprimer l'employé de la base
        $stmt = $conn->prepare("DELETE FROM t_employes_emplys WHERE id_employe = ?");
        $stmt->bind_param("i", $id_employe);
        $stmt->execute();
        $stmt->close();
    }
    
    // Restaurer un employé
    if ($_POST['action'] === 'restaurer') {
        $stmt = $conn->prepare("UPDATE t_employes_emplys SET archivé = 0 WHERE id_employe = ?");
        $stmt->bind_param("i", $id_employe);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: manage_archives.php");
    exit();
}

// Récupérer les employés archivés
$sql = "SELECT id_employe, nom, prenom, fonction, affectation_réelle FROM t_employes_emplys WHERE archivé = 1";
$result = $conn->query($sql);
$employes_archives = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archives Employés - TBL Data Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #D6F0EB, #E8E8E8);
        }
        .card {
            background-color: #ECF8F6;
            border-radius: 8px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .btn-danger, .btn-success {
            transition: all 0.3s ease-in-out;
        }
        .btn-danger:hover, .btn-success:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-danger">Employés Archivés</h1>
        <div class="row g-4">
            <?php foreach ($employes_archives as $employe): ?>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5 class="fw-bold text-primary">Nom: <?php echo htmlspecialchars($employe['nom']); ?></h5>
                        <h6 class="fw-bold text-secondary">Prénom: <?php echo htmlspecialchars($employe['prenom']); ?></h6>
                        <p><strong>Fonction:</strong> <?php echo htmlspecialchars($employe['fonction']); ?></p>
                        <p><strong>Affectation:</strong> <?php echo htmlspecialchars($employe['affectation_réelle']); ?></p>
                        <div class="d-flex justify-content-between">
                            <form method="POST">
                                <input type="hidden" name="id_employe" value="<?php echo $employe['id_employe']; ?>">
                                <button type="submit" name="action" value="restaurer" class="btn btn-success">Restaurer</button>
                            </form>
                            <form method="POST" onsubmit="return confirm('Supprimer définitivement cet employé ?');">
                                <input type="hidden" name="id_employe" value="<?php echo $employe['id_employe']; ?>">
                                <button type="submit" name="action" value="supprimer" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
