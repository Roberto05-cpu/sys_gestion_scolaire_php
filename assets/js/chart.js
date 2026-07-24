try {
  // Graphique par niveau
  const ctxNiveau = document.getElementById("niveauChart");
  if (ctxNiveau && ctxNiveau.dataset.chartniv) {
    const charDataNiv = JSON.parse(ctxNiveau.dataset.chartniv);
    console.log("Données niveau:", charDataNiv);
    new Chart(ctxNiveau, {
      type: "doughnut",
      data: {
        labels: charDataNiv.labelsNiv,
        datasets: [
          {
            data: charDataNiv.dataNiv,
            backgroundColor: [
              "#1a4d8f",
              "#10b981",
              "#f59e0b",
              "#ef4444",
              "#8b5cf6",
              "#ec4899",
              "#14b8a6",
            ],
            borderWidth: 2,
          },
        ],
      },
    });
  } else {
    console.error("Canvas niveau ou données manquantes");
  }
} catch (error) {
  console.error("Erreur graphique niveau:", error);
}

try {
  // Graphique par genre
  const ctxGenre = document.getElementById("genreChart");
  if (ctxGenre && ctxGenre.dataset.chart) {
    const charData = JSON.parse(ctxGenre.dataset.chart);
    console.log("Données genre:", charData);
    new Chart(ctxGenre, {
      type: "doughnut",
      data: {
        labels: charData.labels,
        datasets: [
          {
            data: charData.data,
            backgroundColor: ["#1a4d8f", "#ec4899"],
            borderWidth: 2,
          },
        ],
      },
    });
  } else {
    console.error("Canvas genre ou données manquantes");
  }
} catch (error) {
  console.error("Erreur graphique genre:", error);
}


