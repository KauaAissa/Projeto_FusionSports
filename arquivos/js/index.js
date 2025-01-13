// Navbar
window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  var navbar = document.getElementById("navbar");
  if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
    navbar.classList.add("nav-scroll");
  } else {
    navbar.classList.remove("nav-scroll");
  }
}

// Transição do navbar passos
document
  .querySelector('a[href="#passos"]')
  .addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    window.scrollTo({
      top: target.offsetTop - 20,
      behavior: "smooth",
    });
  });

//Transição do navbar funcionalidades
document
  .querySelector('a[href="#funcoes"]')
  .addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    window.scrollTo({
      top: target.offsetTop - 20,
      behavior: "smooth",
    });
  });

// Transição do navbar FAQ
document
  .querySelector('a[href="#faq"]')
  .addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    window.scrollTo({
      top: target.offsetTop - 90,
      behavior: "smooth",
    });
  });

// FAQ area
document.addEventListener("DOMContentLoaded", function () {
  const faqItems = document.querySelectorAll(".faq-item");

  faqItems.forEach((item) => {
    item.querySelector(".faq-question").addEventListener("click", function () {
      item.classList.toggle("active");
    });
  });
});
//   LOADER
document.addEventListener("DOMContentLoaded", function () {
  const logoLink = document.querySelector(".text-name");
  const loadingScreen = document.getElementById("fullscreen-loading");

  if (logoLink) {
    logoLink.addEventListener("click", function (e) {
      e.preventDefault();

      // Exibe o loader
      loadingScreen.style.display = "flex";

      setTimeout(() => {
        window.location.href = this.getAttribute("href");
      }, 1000);
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const defaultBtn = document.querySelector(".default-btn");
  const loadingScreen = document.getElementById("fullscreen-loading");
  if (defaultBtn) {
    defaultBtn.addEventListener("click", function (e) {
      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        window.location.href = this.getAttribute("href");
      }, 1000);
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const cityInput = document.getElementById("city");
  const suggestionsContainer = document.createElement("div");
  suggestionsContainer.classList.add("suggestions-container");
  cityInput.parentNode.appendChild(suggestionsContainer);

  cityInput.addEventListener("input", function () {
    const query = cityInput.value.trim();
    if (query.length > 0) {
      // Realizar a requisição AJAX para buscar as cidades correspondentes
      fetch(`config/search_cities.php?query=${query}`)
        .then((response) => response.json())
        .then((data) => {
          // Limpar sugestões anteriores
          suggestionsContainer.innerHTML = "";

          if (data.length > 0) {
            // Mostrar o contêiner apenas se houver sugestões
            suggestionsContainer.classList.add("active");

            // Exibir as novas sugestões
            data.forEach((city) => {
              const suggestionItem = document.createElement("div");
              suggestionItem.textContent = city;
              suggestionItem.classList.add("suggestion-item");
              suggestionsContainer.appendChild(suggestionItem);

              // Adicionar evento de clique para preencher o input
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

// POPUP

document.addEventListener("DOMContentLoaded", function () {
  const contactForm = document.getElementById("contact-form");
  const successModal = document.getElementById("successModal");
  const closeButton = document.querySelector(".close-button");
  const loadingScreen = document.getElementById("fullscreen-loading");

  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Exibe o loader (opcional)
    loadingScreen.style.display = "flex";

    // Enviar o formulário via AJAX
    fetch("config/processar_contato.php", {
      method: "POST",
      body: new FormData(contactForm),
    })
      .then((response) => response.text())
      .then((data) => {
        loadingScreen.style.display = "none";

        successModal.style.display = "flex";

        contactForm.reset();
      })
      .catch((error) => {
        console.error("Erro ao enviar a mensagem:", error);
      });
  });

  closeButton.addEventListener("click", function () {
    successModal.style.display = "none";
  });

  window.addEventListener("click", function (e) {
    if (e.target == successModal) {
      successModal.style.display = "none";
    }
  });
});

// LOADER PESQUISA
document.addEventListener("DOMContentLoaded", function () {
  const searchForm = document.querySelector(".search-city-form form");
  const cityInput = document.getElementById("city");
  const loadingScreen = document.getElementById("fullscreen-loading");

  searchForm.addEventListener("submit", function (e) {
    if (cityInput.value.trim() === "") {
      e.preventDefault();
      alert("Por favor, insira uma cidade para pesquisar.");
      return;
    }

    loadingScreen.style.display = "flex";

    setTimeout(() => {
      searchForm.submit();
    }, 1000);
  });
});
