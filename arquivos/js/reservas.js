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
});

document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".tab-btn");
  const tabContents = document.querySelectorAll(".tab-content");
  const loadingScreen = document.getElementById("fullscreen-loading");

  // Função para ocultar todas as abas
  function hideAllTabs() {
    tabContents.forEach((content) => content.classList.remove("active"));
    tabs.forEach((tab) => tab.classList.remove("active"));
  }

  // Função para mostrar o conteúdo da aba ativa com o loader
  function showTab(target) {
    hideAllTabs();
    loadingScreen.style.display = "flex";

    setTimeout(() => {
      const selectedContent = document.querySelector(target);
      selectedContent.classList.add("active");
      loadingScreen.style.display = "none";
    }, 1000);
  }

  // Adiciona o evento de clique aos botões das abas
  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      const target = this.getAttribute("data-target");
      showTab(target);
      this.classList.add("active");
    });
  });

  // Exibe a primeira aba por padrão
  if (tabs.length > 0) {
    tabs[0].classList.add("active");
    tabContents[0].classList.add("active");
  }
});
