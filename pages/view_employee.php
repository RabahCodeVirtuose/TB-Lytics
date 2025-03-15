<?php
require '../includes/config.php';
session_start();

// Vérification de l'administrateur principal
if (!isset($_SESSION['fonction']) || $_SESSION['fonction'] !== 'Administrateur Principal') {
    header("Location: login.php");
    exit();
}

// Vérification de l'ID de l'employé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Employé non spécifié ou invalide.";
    exit();
}

$id_employe = intval($_GET['id']);

// Récupération des informations sur l'employé
$stmt = $conn->prepare("SELECT * FROM t_employes_emplys WHERE id_employe = ?");
$stmt->bind_param("i", $id_employe);
$stmt->execute();
$result = $stmt->get_result();
$employe = $result->fetch_assoc();
$stmt->close();

// Vérification si l'employé existe
if (!$employe) {
    echo "Employé introuvable.";
    exit();
}

// Récupération des fichiers PDF
$dossier_path = "../dossiers/" . $id_employe;
$fichiers = [];
if (is_dir($dossier_path)) {
    $fichiers = array_diff(scandir($dossier_path), ['.', '..']); 
}

//Récupération de la structure
$structure_id = $employe['structure_id']; // Récupération de l'ID

// Exécute la requête
$structure_result = $conn->query("SELECT nom_structure FROM `t_structures_strcts` WHERE id_structure = $structure_id");

// Récupère UNE SEULE ligne
$structure = $structure_result->fetch_assoc();

$structure_result->close();


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employé: <?php echo htmlspecialchars($employe['nom'] . ' ' . $employe['prenom']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
      body {
    background: linear-gradient(135deg, #D6F0EB 10%, #E0E0E0 90%);
    font-family: Arial, sans-serif;
    margin: 0;
    height: 100vh;
}

.container {
    max-width: 800px;
    margin: auto;
}

/* Carte employé */
.card {
    
    background: white;
    border: 1px solid #FEEAA1;

    border-radius: 12px;
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.15);
    padding: 25px;
    margin-top: 20px;
    transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
}

/* Effet léger au survol */
.card:hover {
    box-shadow: 4px 8px 15px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
}

/* Titre de la carte */
.card-title {
    color: #18534F;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 1.4rem;
    text-align: center;
}

/* Texte des détails */
.card-text {
    font-size: 1rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    font-weight: normal;
}

/* Icônes */
.card-text i {
    color: #18534F;
    margin-right: 10px;
    font-size: 1.2rem;
}

/* Boutons avec animation fluide */
.btn-primary, .btn-warning {
    border-radius: 8px;
    padding: 10px 15px;
    margin:5px;
    font-weight: normal;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

/* Effet de survol avec animation fluide */
.btn-primary {
    background: linear-gradient(135deg, #18534F, #226D68);
    border: none;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #226D68, #18534F);
    transform: scale(1.08);
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
}

/* Bouton Modifier */
.btn-warning {
    background: linear-gradient(135deg, #FEEAA1, #D6955B);
    border: none;
    color: black;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #D6955B, #FEEAA1);
    transform: scale(1.08);
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
}

/* Effets sur les liens PDF */
.pdf-list {
    list-style: none;
    padding: 0;
}

.pdf-list li {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

/* Style des liens PDF */
.pdf-list a {
    text-decoration: none;
    font-weight: normal;
    color: #18534F;
    display: flex;
    align-items: center;
    font-size: 1rem;
    transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
}

/* Icônes PDF */
.pdf-list a i {
    color: #D6955B;
    margin-right: 8px;
    font-size: 1.3rem;
}

/* Effet de survol sur les liens PDF */
.pdf-list a:hover {
    text-decoration: underline;
    color: #226D68;
    transform: translateX(5px);
}

    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center text-dark fw-bold">Détails de l'Employé</h1>
    <div class="card">
        <h2 class="card-title">
            <i class="bi bi-person-badge-fill"></i> 
            <?php echo htmlspecialchars($employe['nom'] . ' ' . $employe['prenom']); ?>
        </h2>
        <hr>

        <div class="card-text"><i class="bi bi-hash"></i> <strong>ID :</strong> <?php echo $employe['id_employe']; ?></div>
    <div class="card-text"><i class="bi bi-person"></i> <strong>Nom :</strong> <?php echo htmlspecialchars($employe['nom']); ?></div>
    <div class="card-text"><i class="bi bi-person-badge"></i> <strong>Prénom :</strong> <?php echo htmlspecialchars($employe['prenom']); ?></div>
    <div class="card-text"><i class="bi bi-gender-ambiguous"></i> <strong>Sexe :</strong> <?php echo $employe['sexe'] === 'M' ? 'Masculin' : 'Féminin'; ?></div>
    <div class="card-text"><i class="bi bi-calendar"></i> <strong>Date de naissance :</strong> <?php echo $employe['date_naissance']; ?></div>
    <div class="card-text"><i class="bi bi-calendar-check"></i> <strong>Date de recrutement :</strong> <?php echo $employe['date_recrutement']; ?></div>
    <div class="card-text"><i class="bi bi-mortarboard"></i> <strong>Diplôme :</strong> <?php echo htmlspecialchars($employe['diplome']); ?></div>
    <div class="card-text"><i class="bi bi-briefcase"></i> <strong>Fonction :</strong> <?php echo htmlspecialchars($employe['fonction']); ?></div>
    <div class="card-text"><i class="bi bi-file-earmark-text"></i> <strong>Type de Contrat :</strong> <?php echo $employe['type_contrat']; ?></div>
    <div class="card-text"><i class="bi bi-building"></i> <strong>Structure :</strong><?php echo htmlspecialchars($structure['nom_structure'] ?? 'Non défini'); ?></div>
    <div class="card-text"><i class="bi bi-map"></i> <strong>Affectation réelle :</strong> <?php echo htmlspecialchars($employe['affectation_réelle']); ?></div>
    <div class="card-text"><i class="bi bi-shield-check"></i> <strong>Conformité :</strong> <?php echo $employe['conformite'] ? '✅ Conforme' : '❌ Non conforme'; ?></div>
    <div class="card-text"><i class="bi bi-archive-fill"></i> <strong>Archivé :</strong> <?php echo $employe['archivé'] ? '📂 Oui' : '📁 Non'; ?></div>
        

        <hr>

        <h4 class="card-title text-center"><i class="bi bi-file-earmark-pdf"></i> Fichiers PDF</h4>
        <?php if (!empty($fichiers)): ?>
            <ul class="pdf-list">
                <?php foreach ($fichiers as $fichier): ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($dossier_path . '/' . $fichier); ?>" target="_blank">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> <?php echo htmlspecialchars($fichier); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-center">Aucun fichier PDF disponible pour cet employé.</p>
        <?php endif; ?>
        
        <hr>

        <div class="text-center">
            <a href="update_employee.php?id=<?php echo $employe['id_employe']; ?>" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Mettre à jour
            </a>
            <a href="manage_employees.php" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

</body>
</html>
