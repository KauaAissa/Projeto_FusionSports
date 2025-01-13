document.addEventListener("DOMContentLoaded", function () {
  const sections = document.querySelectorAll(".dashboard-section");
  const navLinks = document.querySelectorAll(".secondary-nav ul li a");

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));

      // Remover a classe 'active' de todas as seções e esconder todas
      sections.forEach((section) => {
        section.classList.remove("active");
        section.classList.add("hidden");
      });

      // Exibir a seção clicada
      target.classList.remove("hidden");
      target.classList.add("active");
    });
  });
});

// semana
document.addEventListener("DOMContentLoaded", function () {
  const horaInputs = document.querySelectorAll(".hora-input");

  horaInputs.forEach((input) => {
    input.addEventListener("input", function (e) {
      let value = this.value.replace(/\D/g, ""); // Remove qualquer caractere que não seja número
      if (value.length >= 3) {
        value = value.replace(/(\d{2})(\d)/, "$1:$2"); // Adiciona o ':'
      }
      this.value = value;
    });

    input.addEventListener("blur", function (e) {
      // Preenche com "00:00" se o campo estiver incompleto ao perder o foco
      if (this.value.length < 5) {
        this.value = "00:00";
      }
    });
  });
});
