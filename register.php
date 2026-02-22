<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($password) || empty($confirm)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse courriel invalide.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($password !== $confirm) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {
        include_once 'api-rest/config/database.php';
        $database = new Database();
        $db = $database->getConnection();

        if ($db) {
            $check = $db->prepare("SELECT id FROM users WHERE Username = ? LIMIT 1");
            $check->bindParam(1, $email);
            $check->execute();

            if ($check->rowCount() > 0) {
                $error = 'Ce courriel est déjà utilisé.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users SET Username=:username, Password=:password, Admin=0");
                $stmt->bindParam(':username', $email);
                $stmt->bindParam(':password', $hashed);

                if ($stmt->execute()) {
                    $success = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
                } else {
                    $error = 'Impossible de créer le compte.';
                }
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
  <title>Inscription — Task Manager</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

  <div class="card p-4" style="width:100%; max-width:420px;">
    <h4 class="mb-1 fw-semibold">Créer un compte</h4>
    <p class="text-secondary small mb-4">Rejoignez Task Manager</p>

    <?php if ($error):   ?><div class="alert alert-danger  py-2"><?= htmlspecialchars($error)   ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success py-2"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" id="registerForm" novalidate>
      <div class="mb-3">
        <label class="form-label" for="email">Courriel</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               placeholder="vous@exemple.com" required />
        <div class="invalid-feedback">Adresse courriel invalide.</div>
      </div>
      <div class="row g-2 mb-4">
        <div class="col">
          <label class="form-label" for="password">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password"
                 placeholder="Min. 6 caractères" required />
          <div class="invalid-feedback">Au moins 6 caractères.</div>
        </div>
        <div class="col">
          <label class="form-label" for="confirm_password">Confirmer</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                 placeholder="Répétez" required />
          <div class="invalid-feedback">Ne correspond pas.</div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Créer mon compte</button>
    </form>

    <p class="text-center small mt-3 mb-0">
      Déjà un compte ? <a href="login.php">Se connecter</a>
    </p>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
  const email    = document.getElementById('email');
  const password = document.getElementById('password');
  const confirm  = document.getElementById('confirm_password');
  let valid = true;
  if (!email.value || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    email.classList.add('is-invalid'); valid = false;
  } else email.classList.remove('is-invalid');
  if (password.value.length < 6) {
    password.classList.add('is-invalid'); valid = false;
  } else password.classList.remove('is-invalid');
  if (!confirm.value || confirm.value !== password.value) {
    confirm.classList.add('is-invalid'); valid = false;
  } else confirm.classList.remove('is-invalid');
  if (!valid) e.preventDefault();
});
</script>
</body>
</html>