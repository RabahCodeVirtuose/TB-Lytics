<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TBL Data Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../assets/css/style1.css" rel="stylesheet" type="text/css">
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

<br>
<br>
<br>
<br>
<br>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt"></i> Connexion</h2>
            <?php
require __DIR__ . '/../includes/config.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $mot_de_passe = $_POST['mot_de_passe'];

            $stmt = $conn->prepare("SELECT mot_de_passe FROM t_comptes_cmpts WHERE pseudo = ?");
            $stmt->bind_param("s", $pseudo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($mot_de_passe, $row['mot_de_passe'])) {
                    // Connexion réussie, redirigez vers le tableau de bord
                    header("Location: ./dashboard.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Mot de passe incorrect.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Pseudo introuvable.</div>';
            }

            $stmt->close();
        }
        ?>


            <form method="POST" action="./verify_login.php">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo (e-mail)</label>
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
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <div class="text-center mt-3">
                <a href="signup.php" class="text-decoration-none" >
                    <i class="fas fa-user-plus"></i> Créer un compte
                </a>
            </div>
        </div>
    </div>
<br>
<br>
<br>
<br>
<br>
    <?php include '../includes/footer.php'; ?>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
        }
    </script>
</body>
</html>
