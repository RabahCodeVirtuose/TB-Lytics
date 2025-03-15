<?php
require '../includes/config.php';

$new_password = 'fatyma1987'; // Remplacez par le mot de passe souhaité
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

// Exemple de mise à jour dans la base de données
$pseudo = 'toubal@ministere.com';
$stmt = $conn->prepare("UPDATE t_comptes_cmpts SET mot_de_passe = ? WHERE pseudo = ?");
$stmt->bind_param("ss", $hashed_password, $pseudo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Mot de passe mis à jour avec succès.";
} else {
    echo "Échec de la mise à jour.";
}

$stmt->close();
$conn->close();
?>
