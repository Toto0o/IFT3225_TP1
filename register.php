<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
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

    <div id="serverMessage"></div>

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
<script src="assets/api.js"></script>
<script src="assets/checks.js"></script>
<script>

  function showMessage(message, type = 'danger') {
    const container = document.getElementById('serverMessage');
    container.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
  }

document.getElementById('registerForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const email    = document.getElementById('email');
  const password = document.getElementById('password');
  const confirm  = document.getElementById('confirm_password');
  let valid = true;
  if (!email.value || !validate_username(email.value)) {
    email.classList.add('is-invalid'); valid = false;
  } else email.classList.remove('is-invalid');
  if (!validate_password(password.value)) {
    password.classList.add('is-invalid'); valid = false;
  } else password.classList.remove('is-invalid');
  if (!confirm.value || !compare_password(password.value, confirm.value)) {
    confirm.classList.add('is-invalid'); valid = false;
  } else confirm.classList.remove('is-invalid');
  if (!valid) {
    showMessage('Veuillez corriger les erreurs dans le formulaire.');
  } else {
    try {
      const response = await apiRequest('/users', 'POST', {
        username: email.value,
        password: password.value
      });
      if (response.ok) {
        showMessage('Compte créé avec succès ! Redirection...', 'success');
        setTimeout(() => window.location.href = 'login.php', 2000);
      } else {
        const data = await response.json();
        showMessage(data.message || 'Erreur lors de la création du compte.');
      }
    } catch (error) {
      showMessage('Erreur de connexion au serveur.');
    }
  }
});
</script>
</body>
</html>
