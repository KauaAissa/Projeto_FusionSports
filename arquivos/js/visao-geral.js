document.addEventListener("DOMContentLoaded", function () {
  const logoLink = document.querySelector(".text-name");
  const loadingScreen = document.getElementById("fullscreen-loading");
  const semanaAtual = document.getElementById("semana-atual");
  const btnSemanaAnterior = document.getElementById("semana-anterior");
  const btnSemanaProxima = document.getElementById("semana-proxima");
  const dataAtual = new Date();
  const diasSemana = [
    "domingo",
    "segunda",
    "terca",
    "quarta",
    "quinta",
    "sexta",
    "sabado",
  ];
  const currentDate = new Date();
  let reservasExistentes = [];
  const modal = document.getElementById("loginModal");
  const closeModal = document.querySelector(".close-modal");
  const cancelarBtn = document.getElementById("cancelar-btn");
  const okBtn = document.getElementById("ok-btn");
  const loader = document.getElementById("fullscreen-loading");
  const idEstabelecimento =
    document.getElementById("id_estabelecimento")?.value || 1;

  // Função para abrir o modal
  function abrirModal() {
    modal.style.display = "flex";
  }

  // Função para fechar o modal
  function fecharModal() {
    modal.style.display = "none";
  }

  // Evento de clique em "Cancelar" e "X" para fechar o modal
  cancelarBtn.addEventListener("click", fecharModal);
  closeModal.addEventListener("click", fecharModal);

  // Evento de clique em "OK" para exibir o loader e redirecionar
  okBtn.addEventListener("click", function () {
    loader.style.display = "flex";
    setTimeout(() => {
      window.location.href = "login-cadastro-usuario.php";
    }, 1000); // Atraso de 1 segundo
  });

  // Função para formatar e atualizar a data da semana exibida
  function atualizarSemana() {
    const inicioSemana = new Date(currentDate);
    const fimSemana = new Date(currentDate);
    inicioSemana.setDate(inicioSemana.getDate() - inicioSemana.getDay());
    fimSemana.setDate(inicioSemana.getDate() + 6);

    semanaAtual.textContent = `Semana de ${inicioSemana.toLocaleDateString()} a ${fimSemana.toLocaleDateString()}`;
    btnSemanaAnterior.disabled = currentDate <= dataAtual;
  }

  btnSemanaAnterior.addEventListener("click", () => {
    currentDate.setDate(currentDate.getDate() - 7);
    atualizarSemana();
    carregarReservas();
  });

  btnSemanaProxima.addEventListener("click", () => {
    currentDate.setDate(currentDate.getDate() + 7);
    atualizarSemana();
    carregarReservas();
  });

  function exibirHorariosDisponiveis() {
    const hoje = new Date();
    const dataHoje = hoje.toISOString().split("T")[0];

    diasSemana.forEach((dia, index) => {
      const diaElemento = document.getElementById(dia);
      if (!diaElemento) return;

      const horariosContainer = diaElemento.querySelector(".horarios");
      horariosContainer.innerHTML = "";

      const [abertura, fechamento] = horariosFuncionamento[dia] || [null, null];

      if (abertura && fechamento) {
        let horaAtual = new Date(`1970-01-01T${abertura}`);
        const horaFechamento = new Date(`1970-01-01T${fechamento}`);
        const dataSelecionada = new Date(currentDate);
        dataSelecionada.setDate(
          currentDate.getDate() - currentDate.getDay() + index
        );
        const dataReserva = dataSelecionada.toISOString().split("T")[0];

        while (horaAtual < horaFechamento) {
          const horaInicio = horaAtual.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
          });
          horaAtual.setHours(horaAtual.getHours() + 1);
          const horaFim = horaAtual.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
          });

          const horarioElemento = document.createElement("div");
          horarioElemento.textContent = `${horaInicio} - ${horaFim}`;
          horarioElemento.dataset.dataReserva = dataReserva;
          horarioElemento.dataset.horarioInicio = horaInicio;
          horarioElemento.dataset.horarioFim = horaFim;

          if (dataReserva < dataHoje) {
            horarioElemento.classList.add("horario-passado");
            horarioElemento.style.pointerEvents = "none";
          } else if (
            dataReserva === dataHoje &&
            horaInicio <
              hoje.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit",
              })
          ) {
            horarioElemento.classList.add("horario-passado");
            horarioElemento.style.pointerEvents = "none";
          } else {
            const reservaExistente = reservasExistentes.find(
              (reserva) =>
                reserva.horario_inicio === `${horaInicio}:00` &&
                reserva.horario_fim === `${horaFim}:00` &&
                reserva.data_reserva === dataReserva
            );

            if (reservaExistente) {
              if (reservaExistente.status === "pendente") {
                horarioElemento.classList.add("horario-pendente");
              } else if (reservaExistente.status === "confirmada") {
                horarioElemento.classList.add("horario-confirmado");
              }
            } else {
              horarioElemento.classList.add("horario-disponivel");
              horarioElemento.addEventListener("click", abrirModal);
            }
          }

          horariosContainer.appendChild(horarioElemento);
        }
      } else {
        horariosContainer.textContent = "Fechado";
      }
    });
  }

  function carregarReservas() {
    const inicioSemana = new Date(currentDate);
    const fimSemana = new Date(currentDate);

    inicioSemana.setDate(inicioSemana.getDate() - inicioSemana.getDay());
    fimSemana.setDate(inicioSemana.getDate() + 6);

    const dataInicio = inicioSemana.toISOString().split("T")[0];
    const dataFim = fimSemana.toISOString().split("T")[0];

    fetch(
      `config/carregar_reservas.php?id_estabelecimento=${idEstabelecimento}&data_inicio=${dataInicio}&data_fim=${dataFim}`
    )
      .then((response) => response.json())
      .then((data) => {
        reservasExistentes = data.map((reserva) => ({
          horario_inicio: reserva.horario_inicio,
          horario_fim: reserva.horario_fim,
          data_reserva: reserva.data_reserva,
          status: reserva.status,
        }));
        exibirHorariosDisponiveis();
      })
      .catch(console.error);
  }

  atualizarSemana();
  carregarReservas();
});

document.addEventListener("DOMContentLoaded", function () {
  const btnLogin = document.querySelector(".btn-login");
  const fullscreenLoader = document.getElementById("fullscreen-loading");

  if (btnLogin) {
    btnLogin.addEventListener("click", function (e) {
      e.preventDefault();

      if (fullscreenLoader) {
        fullscreenLoader.style.display = "flex";
      }

      setTimeout(() => {
        window.location.href = this.getAttribute("href");
      }, 1000);
    });
  } else {
    console.error("Botão de login não encontrado!");
  }
});
