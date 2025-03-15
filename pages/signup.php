<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - TBL Data Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../assets/css/style1.css" rel="stylesheet" />
    <style>
        #doudou1{
    background-image: url('../img/aziz.jpg'); /* Chemin de l'image */
    background-size: cover; /* L'image couvre toute la largeur et la hauteur */
    background-position: center; /* Centre l'image */
    background-repeat: no-repeat; /* Pas de répétition */
    background-attachment: fixed; /* L'image reste fixe lors du défilement */
    margin: 0; /* Supprime les marges par défaut */
    height: 100vh; /* Assure que le body prend toute la hauteur */
}
 
    </style>
</head>
<body id="doudou1">
<?php include '../includes/header1.php'; ?>

    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Créer un compte</h2>
            <?php
            require __DIR__ . '/../includes/config.php';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Récupérer les données du formulaire
                $pseudo = htmlspecialchars($_POST['pseudo']);
                $mot_de_passe = $_POST['mot_de_passe'];
                $mot_de_passe_confirm = $_POST['mot_de_passe_confirm'];
                $nom = htmlspecialchars($_POST['nom']);
                $prenom = htmlspecialchars($_POST['prenom']);
                $date_naissance = $_POST['date_naissance'];

                // Validation des données
                $errors = [];
                if (empty($pseudo) || !filter_var($pseudo, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Le pseudo doit être une adresse e-mail valide.";
                }
                if (empty($mot_de_passe)) {
                    $errors[] = "Le mot de passe est requis.";
                }
                if ($mot_de_passe !== $mot_de_passe_confirm) {
                    $errors[] = "Les mots de passe ne correspondent pas.";
                }
                if (empty($nom) || empty($prenom)) {
                    $errors[] = "Le nom et le prénom sont requis.";
                }
                if (empty($date_naissance)) {
                    $errors[] = "La date de naissance est requise.";
                }

                // Affichage des erreurs
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">';
                    foreach ($errors as $error) {
                        echo "<p>$error</p>";
                    }
                    echo '</div>';
                } else {
                    try {
                        // Hashage du mot de passe
                        $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);

                        // Insérer les données dans les tables t_comptes_cmpts et t_profils_prfls
                        $stmt = $conn->prepare("INSERT INTO t_comptes_cmpts (pseudo, mot_de_passe) VALUES (?, ?)");
                        $stmt->bind_param("ss", $pseudo, $hashed_password);
                        $stmt->execute();

                        $stmt = $conn->prepare("INSERT INTO t_profils_prfls (pseudo, nom, prenom, date_naissance, fonction, etat) VALUES (?, ?, ?, ?, ?, 'D')");
                        $fonction = "Directeur Régional"; // Fonction par défaut
                        $stmt->bind_param("sssss", $pseudo, $nom, $prenom, $date_naissance, $fonction);
                        $stmt->execute();

                        echo '<div class="alert alert-success">Compte créé avec succès. En attente de validation par l\'administrateur.</div>';

                        $stmt->close();
                    } catch (mysqli_sql_exception $e) {
                        if ($conn->errno === 1062) { // Duplicate entry
                            echo '<div class="alert alert-danger">Ce pseudo est déjà utilisé. Veuillez en choisir un autre.</div>';
                        } else {
                            echo '<div class="alert alert-danger">Une erreur est survenue lors de la création du compte. Veuillez réessayer plus tard.</div>';
                        }
                    }
                }
            }
            ?>

            <form method="POST" action="signup.php">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="pseudo" name="pseudo" required>
                </div>
                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('mot_de_passe')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="mot_de_passe_confirm" class="form-label">Confirmer le mot de passe</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="mot_de_passe_confirm" name="mot_de_passe_confirm" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('mot_de_passe_confirm')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="mb-3">
                    <label for="date_naissance" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
        }
    </script>
</body>
</html>
