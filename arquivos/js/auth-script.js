document.addEventListener("DOMContentLoaded", function () {
  const nextBtns = document.querySelectorAll(".next-btn");
  const prevBtns = document.querySelectorAll(".prev-btn");
  const formSteps = document.querySelectorAll(".form-step");
  const loadingContainer = document.querySelector("#fullscreen-loading");
  const redirectLinks = document.querySelectorAll(".redirect-link");
  let currentStep = 0;

  // Função para exibir a etapa atual do formulário
  function showStep(step) {
    formSteps[step].classList.add("active");
  }

  // Função para ocultar a etapa atual do formulário
  function hideStep(step) {
    formSteps[step].classList.remove("active");
  }

  // Função para simular o carregamento
  function showLoading(callback) {
    loadingContainer.style.display = "flex";
    setTimeout(() => {
      loadingContainer.style.display = "none";
      callback();
    }, 1500);
  }

  // Navegação entre as etapas do formulário (cadastro)
  nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      hideStep(currentStep);
      showLoading(() => {
        currentStep++;
        showStep(currentStep);
      });
    });
  });

  prevBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      hideStep(currentStep);
      showLoading(() => {
        currentStep--;
        showStep(currentStep);
      });
    });
  });

  // Loading para redirecionamento entre páginas (login <-> cadastro)
  redirectLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const target = e.target.href;

      loadingContainer.style.display = "flex";

      setTimeout(() => {
        window.location.href = target;
      }, 1500);
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

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        window.location.href = this.getAttribute("href");
      }, 1000);
    });
  }
});

// loader ao finalizar o cadastro
document.addEventListener("DOMContentLoaded", function () {
  const submitBtn = document.querySelector(
    '#contact-form input[type="submit"]'
  );
  const loadingScreen = document.getElementById("fullscreen-loading");

  if (submitBtn) {
    submitBtn.addEventListener("click", function (e) {
      e.preventDefault();

      loadingScreen.style.display = "flex";

      setTimeout(() => {
        document.querySelector("#contact-form").submit();
      }, 1500);
    });
  }
});

// verificação das senhas
document.addEventListener("DOMContentLoaded", function () {
  const nextBtns = document.querySelectorAll(".next-btn");
  const password = document.querySelector('input[name="password"]');
  const confirmPassword = document.querySelector(
    'input[name="confirm_password"]'
  );

  nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (password.value !== confirmPassword.value) {
        alert("As senhas não coincidem. Por favor, verifique.");
      } else {
        hideStep(currentStep);
        showLoading(() => {
          currentStep++;
          showStep(currentStep);
        });
      }
    });
  });
});

// validacao
document.addEventListener("DOMContentLoaded", function () {
  const nextBtns = document.querySelectorAll(".next-btn");
  const prevBtns = document.querySelectorAll(".prev-btn");
  const formSteps = document.querySelectorAll(".form-step");
  const form = document.getElementById("contact-form");
  const loadingContainer = document.querySelector("#fullscreen-loading");
  const redirectLinks = document.querySelectorAll(".redirect-link");
  let currentStep = 0;

  // Função para exibir a etapa atual do formulário
  function showStep(step) {
    formSteps[step].classList.add("active");
  }

  // Função para ocultar a etapa atual do formulário
  function hideStep(step) {
    formSteps[step].classList.remove("active");
  }

  // Função para verificar se todos os campos da etapa atual estão preenchidos
  function isStepValid(step) {
    const inputs = formSteps[step].querySelectorAll("input[required]");
    for (let input of inputs) {
      if (!input.value.trim()) {
        input.classList.add("error");
        return false;
      } else {
        input.classList.remove("error");
      }
    }
    return true;
  }

  // Navegação entre as etapas do formulário (cadastro)
  nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (isStepValid(currentStep)) {
        hideStep(currentStep);
        showLoading(() => {
          currentStep++;
          showStep(currentStep);
        });
      }
    });
  });

  prevBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      hideStep(currentStep);
      showLoading(() => {
        currentStep--;
        showStep(currentStep);
      });
    });
  });

  // Adicionar verificação ao enviar o formulário completo
  form.addEventListener("submit", function (e) {
    if (!isStepValid(currentStep)) {
      e.preventDefault();
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const nextBtns = document.querySelectorAll(".next-btn");
  const prevBtns = document.querySelectorAll(".prev-btn");
  const formSteps = document.querySelectorAll(".form-step");
  const form = document.getElementById("contact-form");
  const loadingContainer = document.querySelector("#fullscreen-loading");
  const redirectLinks = document.querySelectorAll(".redirect-link");
  let currentStep = 0;

  // Função para exibir a etapa atual do formulário
  function showStep(step) {
    formSteps[step].classList.add("active");
  }

  // Função para ocultar a etapa atual do formulário
  function hideStep(step) {
    formSteps[step].classList.remove("active");
  }

  // Função para verificar se todos os campos da etapa atual estão preenchidos
  function isStepValid(step) {
    const inputs = formSteps[step].querySelectorAll("input[required]");
    let missingFields = [];
    for (let input of inputs) {
      if (!input.value.trim()) {
        input.classList.add("error");
        missingFields.push(input.placeholder);
      } else {
        input.classList.remove("error");
      }
    }

    if (missingFields.length > 0) {
      alert(
        "Por favor, preencha os seguintes campos:\n" + missingFields.join("\n")
      );
      return false;
    }

    return true;
  }

  // Navegação entre as etapas do formulário (cadastro)
  nextBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (isStepValid(currentStep)) {
        hideStep(currentStep);
        showLoading(() => {
          currentStep++;
          showStep(currentStep);
        });
      }
    });
  });

  prevBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      hideStep(currentStep);
      showLoading(() => {
        currentStep--;
        showStep(currentStep);
      });
    });
  });

  // Adicionar verificação ao enviar o formulário completo
  form.addEventListener("submit", function (e) {
    if (!isStepValid(currentStep)) {
      e.preventDefault();
    }
  });
});
