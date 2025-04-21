<?php
require_once 'vendor/autoload.php'; // Assure-toi que Google Client est bien installé via Composer

// Connexion à la base de données
$host = 'localhost';
$db = 'yool_db'; // Nom de ta base
$user = 'root';
$pass = ''; // Mot de passe selon ta config
$conn = new mysqli($host, $user, $pass, $db);

// Vérifie la connexion
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Google Client ID (remplace par ton vrai ID)
$client = new Google_Client(['client_id' => '171828142878-cki84b0o0pc94vm2oged7jabg3f7thgc.apps.googleusercontent.com']);

// Récupérer les données envoyées en JSON
$data = json_decode(file_get_contents('php://input'));

if (isset($data->credential)) {
    $payload = $client->verifyIdToken($data->credential);

    if ($payload) {
        $email = $payload['email'];
        $name = $payload['name'];

        // Vérifie si l'utilisateur existe
        $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // S'il n'existe pas, on le crée
        if ($stmt->num_rows == 0) {
            $stmtInsert = $conn->prepare("INSERT INTO admin (nom, email) VALUES (?, ?)");
            $stmtInsert->bind_param("ss", $name, $email);
            $stmtInsert->execute();
            $stmtInsert->close();
        }

        $stmt->close();

        // Démarre la session
        session_start();
        $_SESSION['authenticated'] = true;
        $_SESSION['email'] = $email;

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Token verification failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No token provided']);
}
?>
