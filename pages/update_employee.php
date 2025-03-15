<?php
require '../includes/config.php';
session_start();

// Vérification de l'administrateur principal
if (!isset($_SESSION['fonction']) || $_SESSION['fonction'] !== 'Administrateur Principal') {
    header("Location: login.php");
    exit();
}

// Récupération de l'ID de l'employé à mettre à jour
if (!isset($_GET['id'])) {
    header("Location: gestion_employes.php");
    exit();
}
$id_employe = intval($_GET['id']);

// Récupération des informations de l'employé
$employe_result = $conn->query("SELECT * FROM t_employes_emplys WHERE id_employe = $id_employe");
if ($employe_result->num_rows === 0) {
    header("Location: gestion_employes.php");
    exit();
}
$employe = $employe_result->fetch_assoc();

// Récupération des structures pour le menu déroulant
$structures_result = $conn->query("SELECT id_structure, nom_structure FROM t_structures_strcts");
$structures = $structures_result->fetch_all(MYSQLI_ASSOC);
$structures_result->close();

// Gestion du dossier des fichiers
$dossier_path = "../dossiers/" . $id_employe;
$fichiers = is_dir($dossier_path) ? array_diff(scandir($dossier_path), array('.', '..')) : [];

// Traitement du formulaire de mise à jour
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $fonction = $_POST['fonction'];
    $type_contrat = $_POST['type_contrat'];
    $affectation_reelle = $_POST['affectation_reelle'];
    $conformite = $_POST['conformite'];
    $sexe = $_POST['sexe'];
    $diplome = $_POST['diplome'];
    $structure_id = intval($_POST['structure_id']);
    $date_naissance = $_POST['date_naissance'];
    $date_recrutement = $_POST['date_recrutement'];

    // Mettre à jour l'employé dans la base de données
    $stmt = $conn->prepare("UPDATE t_employes_emplys SET nom = ?, prenom = ?, fonction = ?, type_contrat = ?, affectation_réelle = ?, conformite = ?, sexe = ?, diplome = ?, structure_id = ?, date_naissance = ?, date_recrutement = ? WHERE id_employe = ?");
    $stmt->bind_param("sssssississi", $nom, $prenom, $fonction, $type_contrat, $affectation_reelle, $conformite, $sexe, $diplome, $structure_id, $date_naissance, $date_recrutement, $id_employe);

    if ($stmt->execute()) {
        $message = "Employé mis à jour avec succès.";
        
        // Gérer la suppression des fichiers
        if (isset($_POST['supprimer_fichiers'])) {
            foreach ($_POST['supprimer_fichiers'] as $file_name) {
                $file_path = $dossier_path . "/" . basename($file_name);
                if (file_exists($file_path)) {
                    unlink($file_path); // Supprimer le fichier
                }
            }
        }

        // Gérer le téléchargement des nouveaux fichiers
        if (!empty($_FILES['fichiers']['name'][0])) {
            if (!is_dir($dossier_path)) {
                mkdir($dossier_path, 0777, true);
            }

            foreach ($_FILES['fichiers']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['fichiers']['name'][$key]);
                $file_path = $dossier_path . "/" . $file_name;

                if (move_uploaded_file($tmp_name, $file_path)) {
                    $message .= " Fichier $file_name ajouté avec succès.";
                } else {
                    $message .= " Erreur lors du téléchargement du fichier $file_name.";
                }
            }
        }
        header("Location: view_employee.php?id=" . $employe['id_employe']);
    } else {
        $message = "Erreur lors de la mise à jour de l'employé.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour un Employé - TBL Data Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #D6F0EB 10%, #E0E0E0 90%);
        }
        .form-container {
            background: white;
             border: 1px solid #FEEAA1;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #18534F;
            border-color: #18534F;
        }
        .btn-primary:hover {
            background-color: #226D68;
            border-color: #226D68;
        }
        .drop-zone {
            border: 2px dashed #18534F;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            color: #18534F;
            background-color: #ECF8F6;
            cursor: pointer;
        }
        .drop-zone.dragover {
            background-color: #D6955B;
            color: white;
        }
        .form-label {
    font-weight: bold;
    color: #18534F;
    display: flex;
    align-items: center;
}

.form-label i {
    margin-right: 10px;
    color: #226D68;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #D6955B;
    transition: all 0.3s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: #18534F;
    box-shadow: 0 0 5px rgba(24, 83, 79, 0.5);
}

.btn-primary {
    background: linear-gradient(135deg, #18534F, #226D68);
    border: none;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #226D68, #18534F);
    transform: scale(1.08);
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
}

.drop-zone {
    border: 2px dashed #18534F;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    color: #18534F;
    background-color: #ECF8F6;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

.drop-zone.dragover {
    background-color: #D6955B;
    color: white;
}

.pdf-list li {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

.pdf-list a {
    text-decoration: none;
    font-weight: normal;
    color: #18534F;
    display: flex;
    align-items: center;
    font-size: 1rem;
    transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
}

.pdf-list a i {
    color: #D6955B;
    margin-right: 8px;
    font-size: 1.3rem;
}

.pdf-list a:hover {
    text-decoration: underline;
    color: #226D68;
    transform: translateX(5px);
}
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center" style="color: #18534F;">Mettre à jour un Employé</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center mt-3">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
            <form method="POST" enctype="multipart/form-data" class="form-container">
    <div class="row g-3">
        <!-- Première colonne -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nom" class="form-label"><i class="bi bi-person"></i> Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($employe['nom']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label"><i class="bi bi-person-badge"></i> Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($employe['prenom']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="fonction" class="form-label"><i class="bi bi-briefcase"></i> Fonction</label>
                <input type="text" class="form-control" id="fonction" name="fonction" value="<?php echo htmlspecialchars($employe['fonction']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="type_contrat" class="form-label"><i class="bi bi-file-earmark-text"></i> Type de Contrat</label>
                <select class="form-select" id="type_contrat" name="type_contrat" required>
                    <option value="CDI" <?php echo $employe['type_contrat'] === 'CDI' ? 'selected' : ''; ?>>CDI</option>
                    <option value="CDD" <?php echo $employe['type_contrat'] === 'CDD' ? 'selected' : ''; ?>>CDD</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_naissance" class="form-label"><i class="bi bi-calendar"></i> Date de Naissance</label>
                <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($employe['date_naissance']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="date_recrutement" class="form-label"><i class="bi bi-calendar-check"></i> Date de Recrutement</label>
                <input type="date" class="form-control" id="date_recrutement" name="date_recrutement" value="<?php echo htmlspecialchars($employe['date_recrutement']); ?>" required>
            </div>
        </div>

        <!-- Deuxième colonne -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="affectation_reelle" class="form-label"><i class="bi bi-map"></i> Affectation Réelle</label>
                <input type="text" class="form-control" id="affectation_reelle" name="affectation_reelle" value="<?php echo htmlspecialchars($employe['affectation_réelle']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="conformite" class="form-label"><i class="bi bi-shield-check"></i> Conformité</label>
                <select class="form-select" id="conformite" name="conformite" required>
                    <option value="1" <?php echo $employe['conformite'] == 1 ? 'selected' : ''; ?>>✅ Conforme</option>
                    <option value="0" <?php echo $employe['conformite'] == 0 ? 'selected' : ''; ?>>❌ Non Conforme</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="sexe" class="form-label"><i class="bi bi-gender-ambiguous"></i> Sexe</label>
                <select class="form-select" id="sexe" name="sexe" required>
                    <option value="M" <?php echo $employe['sexe'] === 'M' ? 'selected' : ''; ?>>Homme</option>
                    <option value="F" <?php echo $employe['sexe'] === 'F' ? 'selected' : ''; ?>>Femme</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="diplome" class="form-label"><i class="bi bi-mortarboard"></i> Diplôme</label>
                <input type="text" class="form-control" id="diplome" name="diplome" value="<?php echo htmlspecialchars($employe['diplome']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="structure_id" class="form-label"><i class="bi bi-building"></i> Structure</label>
                <select class="form-select" id="structure_id" name="structure_id" required>
                    <?php foreach ($structures as $structure): ?>
                        <option value="<?php echo $structure['id_structure']; ?>" <?php echo $structure['id_structure'] == $employe['structure_id'] ? 'selected' : ''; ?>>
                            <?php echo $structure['nom_structure']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Affichage des fichiers existants -->
    <div class="mb-3">
        <label class="form-label"><i class="bi bi-file-earmark-pdf"></i> Fichiers associés</label>
        <?php if (!empty($fichiers)): ?>
            <p style="color: #D6955B; font-weight: bold;">📌 Coche les fichiers à supprimer :</p>
            <ul class="list-group">
                <?php foreach ($fichiers as $file_name): ?>
                    <li class="list-group-item d-flex align-items-center">
                        <input class="me-2" type="checkbox" name="supprimer_fichiers[]" value="<?php echo htmlspecialchars($file_name); ?>">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i> <?php echo htmlspecialchars($file_name); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun fichier associé.</p>
        <?php endif; ?>
    </div>

    <!-- Champ pour les nouveaux fichiers -->
    <div class="mb-3">
        <label class="form-label"><i class="bi bi-cloud-upload"></i> Ajouter des fichiers PDF</label>
        <div class="drop-zone">
            Glissez-déposez vos fichiers ici ou cliquez pour télécharger
        </div>
        <input type="file" id="fichiers" name="fichiers[]" multiple hidden>
        <ul id="file-list" class="mt-2"></ul>
    </div>

    <div class="mt-4 text-center">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle"></i> Mettre à jour</button>
        <a href="view_employee.php?id=<?php echo $employe['id_employe']; ?>" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
    </div>
</form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
  const dropZone = document.querySelector('.drop-zone');
const fileInput = document.getElementById('fichiers');
const fileList = document.getElementById('file-list');

// Stockage temporaire des fichiers sélectionnés (évite duplication)
let selectedFiles = [];

// Charger les fichiers déjà présents sur le serveur (si disponibles)
document.querySelectorAll('.existing-file').forEach(fileItem => {
    const fileName = fileItem.dataset.filename;
    selectedFiles.push({ name: fileName, isServerFile: true }); // Marquer comme fichier serveur
});

// ✅ Événement: Clic sur la zone de dépôt
dropZone.addEventListener('click', () => fileInput.click());

// ✅ Événement: Drag & Drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');

    const droppedFiles = Array.from(e.dataTransfer.files);
    addFiles(droppedFiles);
});

// ✅ Événement: Sélection de fichiers via `input`
fileInput.addEventListener('change', () => {
    const newFiles = Array.from(fileInput.files);
    addFiles(newFiles);
});

// ✅ Fonction pour ajouter et fusionner les fichiers sans duplication
function addFiles(newFiles) {
    newFiles.forEach(file => {
        if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            selectedFiles.push({ file, name: file.name, isServerFile: false });
        }
    });

    updateFileInput();
    updateFileList();
}

// ✅ Fonction pour mettre à jour `fileInput.files`
function updateFileInput() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(fileObj => {
        if (!fileObj.isServerFile) { // Ne pas ajouter les fichiers serveur à `fileInput`
            dataTransfer.items.add(fileObj.file);
        }
    });
    fileInput.files = dataTransfer.files;
}

// ✅ Fonction pour mettre à jour l'affichage des fichiers
function updateFileList() {
    fileList.innerHTML = ''; // Réinitialise l'affichage

    selectedFiles.forEach((fileObj, index) => {
        const li = document.createElement('li');
        li.classList.add('d-flex', 'align-items-center', 'justify-content-between', 'p-2', 'mb-2', 'border', 'rounded', 'shadow-sm', 'bg-light');

        // Icône du fichier
        const icon = document.createElement('i');
        icon.classList.add('bi', 'bi-file-earmark-text', 'text-primary', 'fs-5', 'me-2');

        // Nom du fichier
        const fileName = document.createElement('span');
        fileName.textContent = fileObj.name;
        fileName.classList.add('text-dark', 'text-truncate');

        // Bouton de suppression
        const deleteBtn = document.createElement('button');
        deleteBtn.innerHTML = '<i class="bi bi-x-circle"></i>';
        deleteBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm', 'rounded-circle', 'ms-2');
        deleteBtn.style.border = 'none';

        deleteBtn.addEventListener('click', () => {
            removeFile(index);
        });

        // Ajouter les éléments
        li.appendChild(icon);
        li.appendChild(fileName);
        li.appendChild(deleteBtn);
        fileList.appendChild(li);
    });
}

// ✅ Fonction pour supprimer un fichier spécifique
function removeFile(indexToRemove) {
    const fileToRemove = selectedFiles[indexToRemove];

    if (fileToRemove.isServerFile) {
        // Si c'est un fichier serveur, cocher la case "supprimer_fichiers[]"
        document.querySelector(`input[name="supprimer_fichiers[]"][value="${fileToRemove.name}"]`).checked = true;
    } else {
        // Sinon, l'enlever de la liste des fichiers sélectionnés
        selectedFiles.splice(indexToRemove, 1);
        updateFileInput();
    }

    updateFileList();
}


    </script>
</body>
</html>