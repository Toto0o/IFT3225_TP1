<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$error = '';


// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse courriel invalide.';
    } else {
      // Connexion à la base de données et vérification des identifiants
        include_once 'api-rest/config/database.php';
        $database = new Database();
        $db = $database->getConnection();

        if ($db) {
            $stmt = $db->prepare("SELECT id, username, password, admin FROM users WHERE Username = ? LIMIT 1");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
              // Récupération de la ligne de l'utilisateur et vérification du mot de passe
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id']   = $row['id'];
                    $_SESSION['user_name'] = $row['username'];
                    $_SESSION['is_admin']  = $row['admin'];
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Mot de passe incorrect.';
                }
            } else {
                $error = 'Aucun compte trouvé avec ce courriel.';
            }
        } else {
            $error = 'Erreur de connexion à la base de données.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion — Gestionnaire des tâches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="card p-4" style="width:100%; max-width:400px;">
    <h4 class="mb-1 fw-semibold">Connexion</h4>
    <p class="text-secondary small mb-4">Accédez à votre gestionnaire de tâches</p>
    <?php if ($error): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" id="loginForm" novalidate>
      <div class="mb-3">
        <label class="form-label" for="email">Courriel</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               placeholder="vous@exemple.com" required />
        <div class="invalid-feedback">Adresse courriel invalide.</div>
      </div>
      <div class="mb-4">
        <label class="form-label" for="password">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password"
               placeholder="••••••••" required />
        <div class="invalid-feedback">Le mot de passe est requis.</div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
    <p class="text-center small mt-3 mb-0">
      Pas de compte ? <a href="register.php">S'inscrire</a>
    </p>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
  const email    = document.getElementById('email');
  const password = document.getElementById('password');
  let valid = true;
  if (!email.value || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    email.classList.add('is-invalid'); valid = false;
  } else email.classList.remove('is-invalid');
  if (!password.value) {
    password.classList.add('is-invalid'); valid = false;
  } else password.classList.remove('is-invalid');
  if (!valid) e.preventDefault();
});
</script>
</body>
</html>