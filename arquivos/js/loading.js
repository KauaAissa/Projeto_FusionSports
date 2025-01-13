document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".secondary-nav a");
  const sections = document.querySelectorAll(".dashboard-section");
  const loadingScreen = document.getElementById("fullscreen-loading");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();

      tabs.forEach((t) => t.classList.remove("active"));

      this.classList.add("active");

      loadingScreen.style.display = "flex";

      sections.forEach((section) => (section.style.display = "none"));

      const target = document.querySelector(this.getAttribute("href"));

      setTimeout(() => {
        loadingScreen.style.display = "none";
        target.style.display = "block";
      }, 1000);
    });
  });

  sections[0].style.display = "block";
  tabs[0].classList.add("active");
});

// NAVBAR
document.addEventListener("DOMContentLoaded", function () {
  const navbarLinks = document.querySelectorAll(".navbar-container a");
  const loadingScreen = document.getElementById("fullscreen-loading");

  navbarLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      // Exibe o loader
      loadingScreen.style.display = "flex";

      setTimeout(() => {
        window.location.href = this.getAttribute("href");
      }, 1000);
    });
  });
});

// loading pendentes

document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".btn-confirmar, .btn-rejeitar");
  const loadingScreen = document.getElementById("fullscreen-loading");

  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const form = this.closest("form");

      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        const actionInput = document.createElement("input");
        actionInput.type = "hidden";
        actionInput.name = "acao";
        actionInput.value = button.classList.contains("btn-confirmar")
          ? "confirmar"
          : "rejeitar";
        form.appendChild(actionInput);

        form.submit();
      }, 1000);
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".btn-editar, .btn-cancelar");
  const loadingScreen = document.getElementById("fullscreen-loading");

  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const form = this.closest("form");

      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        const actionInput = document.createElement("input");
        actionInput.type = "hidden";
        actionInput.name = "acao";
        actionInput.value = button.classList.contains("btn-editar")
          ? "editar"
          : "cancelar";
        form.appendChild(actionInput);

        form.submit();
      }, 1000);
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".btn-editar");
  const modal = document.getElementById("edit-modal");
  const closeModal = document.querySelector(".close-modal");
  const form = document.getElementById("edit-form");

  // Abrir o modal com os dados da reserva ao clicar em "Editar"
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const idReserva =
        this.closest("tr").querySelector("td:first-child").innerText;
      const dataReserva =
        this.closest("tr").querySelector("td:nth-child(3)").innerText;
      const horarioInicio = this.closest("tr")
        .querySelector("td:nth-child(4)")
        .innerText.split(" - ")[0];
      const horarioFim = this.closest("tr")
        .querySelector("td:nth-child(4)")
        .innerText.split(" - ")[1];

      // Preencher os campos do modal com os dados da reserva
      document.getElementById("edit-id-reserva").value = idReserva;
      document.getElementById("edit-data").value = dataReserva;
      document.getElementById("edit-horario-inicio").value = horarioInicio;
      document.getElementById("edit-horario-fim").value = horarioFim;

      modal.style.display = "block";
    });
  });

  closeModal.addEventListener("click", function () {
    modal.style.display = "none";
  });

  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const loadingScreen = document.getElementById("fullscreen-loading");
    loadingScreen.style.display = "flex";

    setTimeout(() => {
      form.submit();
    }, 1000);
  });
});

// AJAX

document.addEventListener("DOMContentLoaded", function () {
  function verificarReservas() {
    // Realiza a requisição AJAX para verificar as reservas
    fetch("config/verificar_reservas.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log(data.message);
        } else {
          console.log(data.message);
        }
      })
      .catch((error) => {
        console.error("Erro na verificação de reservas:", error);
      });
  }

  setInterval(verificarReservas, 60000);
});

// salvar informações

document.addEventListener("DOMContentLoaded", function () {
  const formEstabelecimento = document.getElementById("form-estabelecimento");
  const loadingScreen = document.getElementById("fullscreen-loading");

  if (formEstabelecimento) {
    formEstabelecimento.addEventListener("submit", function (e) {
      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        formEstabelecimento.submit();
      }, 1000);
    });
  }
});

// Js Swicth

document.addEventListener("DOMContentLoaded", function () {
  const switches = document.querySelectorAll('.switch input[type="checkbox"]');

  switches.forEach(function (switchElement) {
    switchElement.addEventListener("change", function () {
      const day = this.name;
      const isChecked = this.checked;
      const horaAbertura = document.getElementById(`hora-abertura-${day}`);
      const horaFechamento = document.getElementById(`hora-fechamento-${day}`);

      if (!isChecked) {
        horaAbertura.disabled = true;
        horaAbertura.classList.add("disabled-input");
        horaFechamento.disabled = true;
        horaFechamento.classList.add("disabled-input");
      } else {
        horaAbertura.disabled = false;
        horaAbertura.classList.remove("disabled-input");
        horaFechamento.disabled = false;
        horaFechamento.classList.remove("disabled-input");
      }
    });
  });
});

// loader segurança

document.addEventListener("DOMContentLoaded", function () {
  const formAlterarEmail = document.getElementById("form-alterar-email");
  const formAlterarSenha = document.getElementById("form-alterar-senha");
  const loadingScreen = document.getElementById("fullscreen-loading");

  if (formAlterarEmail) {
    formAlterarEmail.addEventListener("submit", function (e) {
      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        formAlterarEmail.submit();
      }, 1000);
    });
  }

  if (formAlterarSenha) {
    formAlterarSenha.addEventListener("submit", function (e) {
      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        formAlterarSenha.submit();
      }, 1000);
    });
  }
});

// Desativa o processamento automático, deixa o upload sob controle do formulário
Dropzone.options.galeriaDropzone = {
  autoProcessQueue: false,
  uploadMultiple: false,
  addRemoveLinks: true,
  maxFiles: 5,

  init: function () {
    const dzInstance = this;

    // Função ao clicar em "Salvar Fotos"
    document
      .querySelector(".btn-salvar")
      .addEventListener("click", function (e) {
        e.preventDefault();

        dzInstance.files.forEach((file) => {
          dzInstance.processFile(file);
        });
      });

    // Callback para tratar a exibição ao adicionar arquivos
    this.on("addedfile", function (file) {
      console.log("Arquivo adicionado: ", file.name);
    });

    // Callback para remover arquivos
    this.on("removedfile", function (file) {
      console.log("Arquivo removido: ", file.name);
    });
  },
};
