<?php
session_start();
// Redirige vers la page de connexion si l'utilisateur n'est pas connecté.
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$user_name = $_SESSION['user_name'] ?? '';
$is_admin  = $_SESSION['is_admin']  ?? false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mes tâches</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>


<!-- Barre de navigation -->
<nav class="navbar navbar-expand-md sticky-top px-3">
  <a class="navbar-brand" href="index.php">Task Manager</a>

  <!-- Bouton hamburger pour mobile -->
  <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Menu responsive -->
  <div class="collapse navbar-collapse" id="navMenu">
    <div class="ms-auto d-flex align-items-center gap-2">
      <?php if (isset($_SESSION['user_id'])): ?>
        <span class="text-secondary small"><?= htmlspecialchars($user_name) ?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-secondary">Déconnexion</a>
      <?php else: ?>
        <a href="login.php"    class="btn btn-sm btn-outline-secondary">Connexion</a>
        <a href="register.php" class="btn btn-sm btn-primary">Inscription</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-------------------------------------------------- Contenneur principal ----------------------------------------------->
<div class="container-fluid px-4 py-4" style="max-width:1400px;">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
      <h4 class="mb-0 fw-semibold">Mes tâches</h4>
      <small class="text-secondary" id="statTotal"></small>
    </div>
    <button class="btn btn-primary" id="btnNewTask">+ Nouvelle tâche</button>
  </div>


  <!----------------------- Section des filtres -------------------------------------->
  <div class="row g-2 mb-4">
    <!-- Barre de recherche -->
    <div class="col-12 col-sm-4 col-lg-3">
      <input type="text" class="form-control" id="searchInput" placeholder="Rechercher…" />
    </div>

    <!-- Filtre priorité -->
    <div class="col-6 col-sm-3 col-lg-2">
      <select class="form-select" id="filterPriority">
        <option value="">Priorité</option>
        <option value="haute">Haute</option>
        <option value="moyenne">Moyenne</option>
        <option value="basse">Basse</option>
      </select>
    </div>

    <!-- Filtre catégorie (rempli dynamiquement via JS) -->
    <div class="col-6 col-sm-3 col-lg-2">
      <select class="form-select" id="filterCategory">
        <option value="">Catégorie</option>
      </select>
    </div>

    <!-- Filtre statut -->
    <div class="col-6 col-sm-3 col-lg-2">
      <select class="form-select" id="filterDone">
        <option value="">Statut</option>
        <option value="0">En cours</option>
        <option value="1">Terminées</option>
      </select>
    </div>

    <!-- Bouton reset filtres -->
    <div class="col-6 col-sm-3 col-lg-1">
      <button class="btn btn-outline-secondary w-100" id="btnReset">Reset</button>
    </div>
  </div>


  <!---------------------------- Grille des tâches (remplie dynamiquement via JS) -------------------------------------->
  <div class="row g-3" id="tasksGrid"></div>

  <!-------------------------------- Pagination (gérée dynamiquement via JS) -------------------------------------------->
  <nav class="mt-4">
    <ul class="pagination justify-content-center" id="pagination"></ul>
  </nav>

</div>

<!-------------------------------- Modals (création/édition et confirmation de suppression) -------------------------------->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taskModalLabel">Nouvelle tâche</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="taskForm" novalidate>
          <input type="hidden" id="taskId" />

          <div class="mb-3">
            <label class="form-label" for="taskTitre">Titre <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="taskTitre" placeholder="Titre de la tâche" required />
            <div class="invalid-feedback">Le titre est requis.</div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="taskDescription">Description <span class="text-danger">*</span></label>
            <textarea class="form-control" id="taskDescription" rows="3" placeholder="Décrivez la tâche…" required></textarea>
            <div class="invalid-feedback">La description est requise.</div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col">
              <label class="form-label" for="taskDate">Date d'échéance <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="taskDate" required />
              <div class="invalid-feedback">La date est requise.</div>
            </div>
            <div class="col">
              <label class="form-label" for="taskPriority">Priorité <span class="text-danger">*</span></label>
              <select class="form-select" id="taskPriority" required>
                <option value="">— Choisir —</option>
                <option value="haute">Haute</option>
                <option value="moyenne">Moyenne</option>
                <option value="basse">Basse</option>
              </select>
              <div class="invalid-feedback">La priorité est requise.</div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="taskCategory">Catégorie</label>
            <select class="form-select" id="taskCategory">
              <option value="">Aucune</option>
            </select>
          </div>

          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="taskDone" />
            <label class="form-check-label" for="taskDone">Marquer comme terminée</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="submitBtn">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<!-------------------------------------------- Modal de confirmation pour la suppression -------------------------------------------->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content text-center">
      <div class="modal-body py-4">
        <p class="mb-1 fw-semibold">Supprimer cette tâche ?</p>
        <p class="text-secondary small">Cette action est irréversible.</p>
        <div class="d-flex gap-2 justify-content-center mt-3">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!----------------------------------------------- Conteneur pour les toasts de notifications -------------------------------------------->
<div class="toast-container-fixed" id="toastContainer"></div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>


// URL des endpoints de l'API
const API = {
  tasksRead:   'api-rest/tuiles/read.php',
  tasksCreate: 'api-rest/tuiles/create.php',
  tasksUpdate: 'api-rest/tuiles/update.php',
  tasksDelete: 'api-rest/tuiles/delete.php',
  categories:  'api-rest/categories/read.php',
};

// État global de l'application
let state = {
  tasks: [], page: 1, perPage: 15, totalPages: 1, totalTasks: 0,
  filters: { search: '', priority: '', category: '', done: '' },
  deleteId: null,
};


// Initialisation des modals Bootstrap
const taskModalBS    = new bootstrap.Modal(document.getElementById('taskModal'));
const confirmModalBS = new bootstrap.Modal(document.getElementById('confirmModal'));


// Chargement des catégories pour les filtres et le formulaire
async function loadCategories() {
  try {
    const res  = await fetch(API.categories);
    const data = await res.json();
    const cats = data.records || [];

    document.getElementById('taskCategory').innerHTML =
      '<option value="">Aucune</option>' +
      cats.map(c => `<option value="${c.id}">${escHtml(c.nom)}</option>`).join('');

    document.getElementById('filterCategory').innerHTML =
      '<option value="">Catégorie</option>' +
      cats.map(c => `<option value="${escHtml(c.nom)}">${escHtml(c.nom)}</option>`).join('');

  } catch (e) {
    console.error('Erreur chargement catégories', e);
  }
}


// Chargement des tâches avec application des filtres, pagination, et mise à jour des statistiques
async function loadTasks() {
  try {
    const res  = await fetch(API.tasksRead);
    const data = await res.json();
    let tasks = data.records || [];

    if (state.filters.search) {
      const q = state.filters.search.toLowerCase();
      tasks = tasks.filter(t => t.titre.toLowerCase().includes(q) || t.description.toLowerCase().includes(q));
    }
    if (state.filters.priority) tasks = tasks.filter(t => t.priorite === state.filters.priority);
    if (state.filters.category) tasks = tasks.filter(t => t.categorie === state.filters.category);
    if (state.filters.done !== '') tasks = tasks.filter(t => String(t.realise == 1 ? 1 : 0) === state.filters.done);

    state.totalTasks = tasks.length;
    state.totalPages = Math.max(1, Math.ceil(tasks.length / state.perPage));
    state.tasks = tasks.slice((state.page - 1) * state.perPage, state.page * state.perPage);

    const done = (data.records || []).filter(t => t.realise == 1).length;
    document.getElementById('statTotal').textContent =
      `${state.totalTasks} tâche${state.totalTasks !== 1 ? 's' : ''} · ${done} terminée(s)`;

  } catch (e) {
    document.getElementById('tasksGrid').innerHTML =
      `<div class="col-12"><div class="empty-state"><p class="text-danger">Erreur de connexion à l'API.</p></div></div>`;
    return;
  }

  renderTasks();
  renderPagination();
}


// Rendu de la grille des tâches en HTML
function renderTasks() {
  const grid = document.getElementById('tasksGrid');
  if (!state.tasks.length) {
    grid.innerHTML = `<div class="col-12"><div class="empty-state"><p>Aucune tâche trouvée.</p></div></div>`;
    return;
  }
  grid.innerHTML = state.tasks.map(task => `
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
      <div class="card h-100 task-card ${task.realise ? 'is-done' : ''}" data-priority="${task.priorite}">
        <div class="card-body d-flex flex-column gap-2">

          <div class="d-flex align-items-start justify-content-between gap-2">
            <div class="d-flex align-items-start gap-2">
              <input type="checkbox" class="form-check-input mt-1"
                     ${task.realise ? 'checked' : ''}
                     onchange="toggleDone(${task.id}, this.checked)" />
              <span class="task-title fw-semibold">${escHtml(task.titre)}</span>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
              <button class="btn btn-sm btn-outline-secondary" onclick="openEditModal(${task.id})">✏️</button>
              <button class="btn btn-sm btn-outline-danger"    onclick="askDelete(${task.id})">🗑️</button>
            </div>
          </div>

          ${task.description ? `<p class="text-secondary small mb-0" style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">${escHtml(task.description)}</p>` : ''}

          <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mt-auto pt-1">
            <div class="d-flex gap-1">
              <span class="badge ${prioBadgeClass(task.priorite)}">${task.priorite}</span>
              ${task.categorie ? `<span class="badge bg-secondary">${escHtml(task.categorie)}</span>` : ''}
              ${task.realise   ? `<span class="badge bg-success">✓ Terminée</span>` : ''}
            </div>
            <small class="text-secondary">${formatDate(task.date)}</small>
          </div>

        </div>
      </div>
    </div>
  `).join('');
}


// Rendu de la pagination en bas de la page
function renderPagination() {
  const ul = document.getElementById('pagination');
  if (state.totalPages <= 1) { ul.innerHTML = ''; return; }
  let html = `<li class="page-item ${state.page===1?'disabled':''}">
    <button class="page-link" onclick="goPage(${state.page-1})">&laquo;</button></li>`;
  for (let p = 1; p <= state.totalPages; p++) {
    if (p===1||p===state.totalPages||(p>=state.page-2&&p<=state.page+2)) {
      html += `<li class="page-item ${p===state.page?'active':''}">
        <button class="page-link" onclick="goPage(${p})">${p}</button></li>`;
    } else if (p===state.page-3||p===state.page+3) {
      html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
    }
  }
  html += `<li class="page-item ${state.page===state.totalPages?'disabled':''}">
    <button class="page-link" onclick="goPage(${state.page+1})">&raquo;</button></li>`;
  ul.innerHTML = html;
}


// Changement de page avec préservation des filtres
function goPage(p) {
  if (p < 1 || p > state.totalPages) return;
  state.page = p; loadTasks();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById('btnNewTask').addEventListener('click', () => {
  document.getElementById('taskModalLabel').textContent = 'Nouvelle tâche';
  document.getElementById('submitBtn').textContent = 'Créer';
  document.getElementById('taskForm').reset();
  document.getElementById('taskId').value = '';
  document.querySelectorAll('#taskForm .form-control, #taskForm .form-select')
    .forEach(f => f.classList.remove('is-invalid', 'is-valid'));
  taskModalBS.show();
});

function openEditModal(id) {
  const task = state.tasks.find(t => t.id === id || t.ID === id);
  if (!task) return;
  document.getElementById('taskModalLabel').textContent = 'Modifier la tâche';
  document.getElementById('submitBtn').textContent = 'Enregistrer';
  document.getElementById('taskId').value          = task.id || task.ID;
  document.getElementById('taskTitre').value       = task.titre;
  document.getElementById('taskDescription').value = task.description;
  document.getElementById('taskDate').value        = task.date;
  document.getElementById('taskPriority').value    = task.priorite;
  document.getElementById('taskDone').checked      = task.realise == 1;
  // Le <select> des catégories utilise l'ID (value="id"), pas le nom.
  document.getElementById('taskCategory').value    = task.categorie_id || '';
  document.querySelectorAll('#taskForm .form-control, #taskForm .form-select')
    .forEach(f => f.classList.remove('is-invalid', 'is-valid'));
  taskModalBS.show();
}

document.getElementById('submitBtn').addEventListener('click', async () => {
  const titre       = document.getElementById('taskTitre');
  const description = document.getElementById('taskDescription');
  const date        = document.getElementById('taskDate');
  const priority    = document.getElementById('taskPriority');
  let valid = true;
  [titre, description, date, priority].forEach(f => {
    if (!f.value.trim()) { f.classList.add('is-invalid'); valid = false; }
    else f.classList.remove('is-invalid');
  });
  if (!valid) return;

  const isEdit = !!document.getElementById('taskId').value;
  const payload = {
    id:          document.getElementById('taskId').value,
    titre:       titre.value.trim(),
    description: description.value.trim(),
    date:        date.value,
    priorite:    priority.value,
    realise:     document.getElementById('taskDone').checked ? 1 : 0,
    // L'API attend "categorie_id" (clé et valeur numérique).
    categorie_id: document.getElementById('taskCategory').value
      ? parseInt(document.getElementById('taskCategory').value, 10)
      : null,
  };

  try {
    const endpoint = isEdit ? API.tasksUpdate : API.tasksCreate;
    const method   = isEdit ? 'PUT' : 'POST';
    const res  = await fetch(endpoint, {
      method:  method,
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(payload),
    });
    const data = await res.json();
    if (res.ok) {
      showToast(isEdit ? 'Tâche modifiée.' : 'Tâche créée.', 'success');
      taskModalBS.hide();
      loadTasks();
    } else {
      showToast(data.message || 'Erreur.', 'danger');
    }
  } catch (e) {
    showToast('Erreur réseau.', 'danger');
  }
});


// Affiche la modal de confirmation avant de supprimer une tâche
function askDelete(id) { state.deleteId = id; confirmModalBS.show(); }

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
  confirmModalBS.hide();
  try {
    const res  = await fetch(API.tasksDelete, {
      method:  'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ id: state.deleteId }),
    });
    const data = await res.json();
    if (res.ok) {
      showToast('Tâche supprimée.', 'success');
    } else {
      showToast(data.message || 'Erreur lors de la suppression.', 'danger');
    }
  } catch (e) {
    showToast('Erreur réseau.', 'danger');
  }
  state.deleteId = null;
  loadTasks();
});


// Permet de marquer une tâche comme terminée ou réouverte directement depuis la checkbox de la carte, sans ouvrir la modal d'édition.
async function toggleDone(id, checked) {
  try {
    await fetch(API.tasksUpdate, {
      method:  'PUT',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ id, realise: checked ? 1 : 0 }),
    });
  } catch (e) { showToast('Erreur réseau.', 'danger'); return; }
  showToast(checked ? 'Tâche terminée !' : 'Tâche réouverte.', 'success');
  loadTasks();
}

let searchTimer;
document.getElementById('searchInput').addEventListener('input', function() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => { state.filters.search = this.value; state.page = 1; loadTasks(); }, 350);
});
document.getElementById('filterPriority').addEventListener('change', function() { state.filters.priority = this.value; state.page = 1; loadTasks(); });
document.getElementById('filterCategory').addEventListener('change', function() { state.filters.category = this.value; state.page = 1; loadTasks(); });
document.getElementById('filterDone').addEventListener('change',     function() { state.filters.done     = this.value; state.page = 1; loadTasks(); });
document.getElementById('btnReset').addEventListener('click', () => {
  state.filters = { search: '', priority: '', category: '', done: '' }; state.page = 1;
  ['searchInput','filterPriority','filterCategory','filterDone'].forEach(id => document.getElementById(id).value = '');
  loadTasks();
});


// Affiche une notification temporaire (toast) en bas de l'écran
function showToast(message, type = 'success') {
  const id = 'toast-' + Date.now();
  document.getElementById('toastContainer').insertAdjacentHTML('beforeend', `
    <div id="${id}" class="toast show mb-2" role="alert">
      <div class="toast-body">${escHtml(message)}</div>
    </div>`);
  setTimeout(() => document.getElementById(id)?.remove(), 3000);
}

// Utilitaires
function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
function formatDate(s) {
  if (!s) return '—';
  return new Date(s + 'T00:00:00').toLocaleDateString('fr-CA', { year:'numeric', month:'short', day:'numeric' });
}
function prioBadgeClass(p) {
  return { haute: 'bg-danger', moyenne: 'bg-warning text-dark', basse: 'bg-success' }[p] || 'bg-secondary';
}


setInterval(loadTasks, 15000);

// INIT
loadCategories();
loadTasks();
</script>
</body>
</html>