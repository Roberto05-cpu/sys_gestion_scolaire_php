const searchInput = document.getElementById("searchInput");
const enseignantsList = document.getElementById("enseignantsList");
const btnClear = document.getElementById("btnClear");

// Fonction pour charger et afficher les enseignants filtrés
async function loadEnseignants() {
  const search = searchInput.value;

  try {
    const response = await fetch(
      `gestionprof.php?ajax=true&search=${encodeURIComponent(search)}`,
    );
    const enseignants = await response.json();

    // Vider le tableau
    enseignantsList.innerHTML = "";

    if (enseignants.length === 0) {
      enseignantsList.innerHTML =
        '<tr><td colspan="7" style="text-align: center; padding: 20px;">Aucun enseignant trouvé</td></tr>';
      return;
    }

    // Remplir le tableau
    enseignants.forEach((ens) => {
      const row = document.createElement("tr");
      row.className = "eleve-row";
      row.innerHTML = `
                        <td>${escapeHtml(ens.matricule)}</td>
                        <td>${escapeHtml(ens.nom)} ${escapeHtml(ens.prenom)}</td>
                        <td>${escapeHtml(ens.email)}</td>
                        <td>${escapeHtml(ens.specialite)}</td>
                        <td>${escapeHtml(ens.sexe)}</td>
                        <td>${escapeHtml(ens.telephone)}</td>
                        <td>
                            <a href="modifiereleve.php?id=${ens.user_id}" class="btn-edit"><i class="fa-solid fa-pen"></i></a>
                            <a href="gestioneleve.php?delete=${ens.user_id}" class="btn-delete" onclick="return confirm('Supprimer ce livre')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    `;
      enseignantsList.appendChild(row);
    });
  } catch (error) {
    console.error("Erreur:", error);
    enseignantsList.innerHTML =
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
searchInput.addEventListener("keyup", loadEnseignants);

// // Bouton réinitialiser
// btnClear.addEventListener("click", () => {
//   searchInput.value = "";
//   loadLivres();
// });
