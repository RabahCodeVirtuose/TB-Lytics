<?php
require '../includes/config.php';
session_start();

// Vérification de l'administrateur principal
if (!isset($_SESSION['fonction']) || $_SESSION['fonction'] !== 'Administrateur Principal') {
    header("Location: login.php");
    exit();
}

// Récupération des structures pour le menu déroulant
$structures_result = $conn->query("SELECT id_structure, nom_structure FROM t_structures_strcts");
$structures = $structures_result->fetch_all(MYSQLI_ASSOC);
$structures_result->close();

// Traitement du formulaire d'ajout
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $fonction = $_POST['fonction'];
    $type_contrat = $_POST['type_contrat'];
    $affectation_réelle = $_POST['affectation_reelle'];
    $conformite = $_POST['conformite'];
    $sexe = $_POST['sexe'];
    $diplome = $_POST['diplome'];
    $structure_id = intval($_POST['structure_id']);
    $date_naissance = $_POST['date_naissance'];
    $date_recrutement = $_POST['date_recrutement'];

    // Insérer l'employé dans la base de données
    $stmt = $conn->prepare("INSERT INTO t_employes_emplys (nom, prenom, fonction, type_contrat, affectation_réelle, conformite, sexe, diplome, structure_id, date_naissance, date_recrutement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssisss", $nom, $prenom, $fonction, $type_contrat, $affectation_réelle, $conformite, $sexe, $diplome, $structure_id, $date_naissance, $date_recrutement);

    if ($stmt->execute()) {
        $id_employe = $stmt->insert_id;

        // Créer le dossier pour l'employé
        $dossier_path = "../dossiers/" . $id_employe;
        if (!is_dir($dossier_path)) {
            mkdir($dossier_path, 0777, true);
        }

        // Gérer le téléchargement des fichiers
        if (!empty($_FILES['fichiers']['name'][0])) {
            foreach ($_FILES['fichiers']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['fichiers']['name'][$key]);
                $file_path = $dossier_path . "/" . $file_name;

                if (move_uploaded_file($tmp_name, $file_path)) {
                    $message = "Employé ajouté avec succès avec ses fichiers.";
                } else {
                    $message = "Erreur lors du téléchargement des fichiers.";
                }
            }
        } else {
            $message = "Employé ajouté avec succès.";
        }
    } else {
        $message = "Erreur lors de l'ajout de l'employé.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Employé - TBL Data Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ECF8F6;
        }
        .form-container {
            background-color: #FEEAA1;
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center" style="color: #18534F;">Ajouter un Employé</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center mt-3">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" enctype="multipart/form-data" class="form-container">
                    <!-- Champs du formulaire -->
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="fonction" class="form-label">Fonction</label>
                        <input type="text" class="form-control" id="fonction" name="fonction" required>
                    </div>
                    <div class="mb-3">
                        <label for="type_contrat" class="form-label">Type de Contrat</label>
                        <select class="form-select" id="type_contrat" name="type_contrat" required>
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date_naissance" class="form-label">Date de Naissance</label>
                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_recrutement" class="form-label">Date de Recrutement</label>
                        <input type="date" class="form-control" id="date_recrutement" name="date_recrutement" required>
                    </div>
                    <div class="mb-3">
                        <label for="affectation_reelle" class="form-label">Affectation Réelle</label>
                        <input type="text" class="form-control" id="affectation_reelle" name="affectation_reelle" required>
                    </div>
                    <div class="mb-3">
                        <label for="conformite" class="form-label">Conformité</label>
                        <select class="form-select" id="conformite" name="conformite" required>
                            <option value="1">Conforme</option>
                            <option value="0">Non Conforme</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sexe" class="form-label">Sexe</label>
                        <select class="form-select" id="sexe" name="sexe" required>
                            <option value="M">Homme</option>
                            <option value="F">Femme</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="diplome" class="form-label">Diplôme</label>
                        <input type="text" class="form-control" id="diplome" name="diplome" required>
                    </div>
                    <div class="mb-3">
                        <label for="structure_id" class="form-label">Structure</label>
                        <select class="form-select" id="structure_id" name="structure_id" required>
                            <?php foreach ($structures as $structure): ?>
                                <option value="<?php echo $structure['id_structure']; ?>">
                                    <?php echo $structure['nom_structure']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Champ pour les fichiers -->
                    <div class="mb-3">
                        <label class="form-label">Fichiers PDF</label>
                        <div class="drop-zone">
                            Glissez-déposez vos fichiers ici ou cliquez pour télécharger
                        </div>
                        <input type="file" id="fichiers" name="fichiers[]" multiple hidden>
                        <ul id="file-list" class="mt-2"></ul>
                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- (PHP code remains the same as your current implementation, no changes needed) -->

<script>
    const dropZone = document.querySelector('.drop-zone');
    const fileInput = document.getElementById('fichiers');
    const fileList = document.getElementById('file-list');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        const droppedFiles = e.dataTransfer.files;
        if (droppedFiles.length > 0) {
            mergeFiles(droppedFiles);
            updateFileList();
        }
    });

    fileInput.addEventListener('change', () => {
        updateFileList();
    });

    const mergeFiles = (newFiles) => {
        const dataTransfer = new DataTransfer();
        // Ajouter les fichiers déjà sélectionnés
        Array.from(fileInput.files).forEach(file => dataTransfer.items.add(file));
        // Ajouter les nouveaux fichiers
        Array.from(newFiles).forEach(file => dataTransfer.items.add(file));
        // Mettre à jour la liste des fichiers
        fileInput.files = dataTransfer.files;
    };

    function updateFileList() {
        fileList.innerHTML = '';
        Array.from(fileInput.files).forEach(file => {
            const li = document.createElement('li');
            li.textContent = file.name;
            fileList.appendChild(li);
        });
    }
</script>

</body>
</html>
