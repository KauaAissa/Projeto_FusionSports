document.addEventListener("DOMContentLoaded", function () {
  const logoLink = document.querySelector(".text-name");
  const loadingScreen = document.getElementById("fullscreen-loading");

  if (logoLink) {
    logoLink.addEventListener("click", function (e) {
      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        window.location.href = this.getAttribute("href");
      }, 1000);
    });
  }

  // Autocompletar na página de resultados
  const cityInput = document.getElementById("city");
  const suggestionsContainer = document.querySelector(".suggestions-container");

  cityInput.addEventListener("input", function () {
    const query = cityInput.value.trim();
    if (query.length > 0) {
      fetch(`config/search_cities.php?query=${query}`)
        .then((response) => response.json())
        .then((data) => {
          suggestionsContainer.innerHTML = "";

          if (data.length > 0) {
            suggestionsContainer.classList.add("active");

            data.forEach((city) => {
              const suggestionItem = document.createElement("div");
              suggestionItem.textContent = city;
              suggestionItem.classList.add("suggestion-item");
              suggestionsContainer.appendChild(suggestionItem);

              suggestionItem.addEventListener("click", () => {
                cityInput.value = city;
                suggestionsContainer.innerHTML = "";
                suggestionsContainer.classList.remove("active");
              });
            });
          } else {
            suggestionsContainer.classList.remove("active");
          }
        })
        .catch((error) => console.error("Erro ao buscar cidades:", error));
    } else {
      suggestionsContainer.classList.remove("active");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const searchButton = document.querySelector(".btn-pesquisar");
  const searchInput = document.getElementById("city");
  const loadingScreen = document.getElementById("fullscreen-loading");

  if (searchButton) {
    searchButton.addEventListener("click", function (e) {
      e.preventDefault();

      if (searchInput.value.trim() === "") {
        alert("Por favor, insira uma cidade para pesquisar.");
        return;
      }

      loadingScreen.style.display = "flex";

      setTimeout(function () {
        searchInput.closest("form").submit();
      }, 1000);
    });
  }
});
