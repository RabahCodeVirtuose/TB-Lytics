<?php
session_start();
require __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de connexion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
 
        .container {
            margin-top: 100px;
            max-width: 500px;
        }
        .alert {
            border-radius: 10px;
            padding: 15px;
        }
    </style>
</head>
<body  id="doudou1">
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $mot_de_passe = $_POST['mot_de_passe'];

            // Correction de la requête SQL avec alias pour éviter l'ambiguïté
            $stmt = $conn->prepare("
                SELECT 
                    t_comptes_cmpts.pseudo, 
                    t_comptes_cmpts.mot_de_passe, 
                    t_profils_prfls.fonction,
                    t_profils_prfls.nom,
                    t_profils_prfls.prenom, 
                    t_profils_prfls.etat 
                FROM 
                    t_comptes_cmpts 
                INNER JOIN 
                    t_profils_prfls 
                ON 
                    t_comptes_cmpts.pseudo = t_profils_prfls.pseudo 
                WHERE 
                    t_comptes_cmpts.pseudo = ?
            ");
            $stmt->bind_param("s", $pseudo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Vérifier le mot de passe
                if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                    // Vérifier si le compte est activé
                    if ($user['etat'] === 'A') {
                        // Créer la session et rediriger
                        $_SESSION['id'] = $pseudo;
                        $_SESSION['fonction'] = $user['fonction'];
                        $_SESSION['nom'] = $user['pseudo'];
                        $_SESSION['etat'] = $user['etat'];
                        $_SESSION['nom'] = $user['nom'];
                        $_SESSION['prenom'] = $user['prenom'];

                        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Connexion réussie ! Vous allez être redirigé...</div>';
                        header("refresh:2;url=../pages/dashboard.php");
                        exit();
                    } else {
                        echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Votre compte est désactivé. Contactez l\'administrateur pour plus d\'informations.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Mot de passe incorrect. Veuillez réessayer.</div>';
                }
            } else {
                echo '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Pseudo introuvable. Veuillez vérifier vos informations.</div>';
            }

            $stmt->close();
        } else {
            echo '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Requête invalide.</div>';
        }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
