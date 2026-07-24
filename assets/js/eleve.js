const searchInput = document.getElementById("searchInput");
const classeSelect = document.getElementById("classeSelect");
const elevesList = document.getElementById("elevesList");
const btnClear = document.getElementById("btnClear");

// Fonction pour charger et afficher les élèves filtrés
async function loadEleves() {
  const classe_id = classeSelect.value;
  const search = searchInput.value;

  try {
    const response = await fetch(
      `gestioneleve.php?ajax=true&classe_id=${encodeURIComponent(classe_id)}&search=${encodeURIComponent(search)}`,
    );
    const eleves = await response.json();

    // Vider le tableau
    elevesList.innerHTML = "";

    if (eleves.length === 0) {
      elevesList.innerHTML =
        '<tr><td colspan="7" style="text-align: center; padding: 20px;">Aucun élève trouvé</td></tr>';
      return;
    }

    // Remplir le tableau
    eleves.forEach((eleve) => {
      const row = document.createElement("tr");
      row.className = "eleve-row";
      row.innerHTML = `
                        <td>${escapeHtml(eleve.matricule)}</td>
                        <td>${escapeHtml(eleve.nom)} ${escapeHtml(eleve.prenom)}</td>
                        <td>${escapeHtml(eleve.email)}</td>
                        <td>${escapeHtml(eleve.niveau)} ${escapeHtml(eleve.classe_nom)}</td>
                        <td>${escapeHtml(eleve.sexe)}</td>
                        <td>${escapeHtml(eleve.date_naissance)}</td>
                        <td>
                            <a href="modifiereleve.php?id=${eleve.user_id}" class="btn-edit"><i class="fa-solid fa-pen"></i></a>
                            <a href="gestioneleve.php?delete=${eleve.user_id}" class="btn-delete" onclick="return confirm('Supprimer cet élève')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    `;
      elevesList.appendChild(row);
    });
  } catch (error) {
    console.error("Erreur:", error);
    elevesList.innerHTML =
      '<tr><td colspan="7" style="text-align: center; color: red;">Erreur lors du chargement</td></tr>';
  }
}

// Fonction pour échapper les caractères HTML
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Écouteurs d'événements
classeSelect.addEventListener("change", loadEleves);
searchInput.addEventListener("keyup", loadEleves);

// Bouton réinitialiser
btnClear.addEventListener("click", () => {
  classeSelect.value = "";
  searchInput.value = "";
  loadEleves();
});
