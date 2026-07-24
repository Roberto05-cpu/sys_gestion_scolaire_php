const searchInput = document.getElementById("searchInput");
const livresList = document.getElementById("livresList");
const btnClear = document.getElementById("btnClear");

// Fonction pour charger et afficher les élèves filtrés
async function loadLivres() {
  const search = searchInput.value;

  try {
    const response = await fetch(
      `gestionlivres.php?ajax=true&search=${encodeURIComponent(search)}`,
    );
    const livres = await response.json();

    // Vider le tableau
    livresList.innerHTML = "";

    if (livres.length === 0) {
      livresList.innerHTML =
        '<tr><td colspan="7" style="text-align: center; padding: 20px;">Aucun livre trouvé</td></tr>';
      return;
    }

    // Remplir le tableau
    livres.forEach((livre) => {
      const row = document.createElement("tr");
      row.className = "eleve-row";
      row.innerHTML = `
                        <td>${escapeHtml(livre.titre)}</td>
                        <td>${escapeHtml(livre.auteur)}</td>
                        <td>${escapeHtml(livre.annee)}</td>
                        <td>${escapeHtml(livre.isbn)}</td>
                        <td>${escapeHtml(livre.description)}</td>
                        <td>
                            <a href="modifiereleve.php?id=${livre.id}" class="btn-edit"><i class="fa-solid fa-pen"></i></a>
                            <a href="gestioneleve.php?delete=${livre.id}" class="btn-delete" onclick="return confirm('Supprimer ce livre')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    `;
      livresList.appendChild(row);
    });
  } catch (error) {
    console.error("Erreur:", error);
    livresList.innerHTML =
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
searchInput.addEventListener("keyup", loadLivres);

// // Bouton réinitialiser
// btnClear.addEventListener("click", () => {
//   searchInput.value = "";
//   loadLivres();
// });
