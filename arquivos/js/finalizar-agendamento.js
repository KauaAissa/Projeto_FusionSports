const currentDate = new Date();

document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modal-pagamento");
  const closeModal = document.querySelector(".close-modal");
  const semanaAtual = document.getElementById("semana-atual");
  const btnSemanaAnterior = document.getElementById("semana-anterior");
  const btnSemanaProxima = document.getElementById("semana-proxima");
  const dataAtual = new Date();
  let reservasExistentes = [];

  function exibirHorariosDisponiveis() {
    const diasSemana = [
      "domingo",
      "segunda",
      "terca",
      "quarta",
      "quinta",
      "sexta",
      "sabado",
    ];
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

        // Calcula a data correspondente ao dia atual do loop
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

          // Bloqueia dias antes de hoje
          if (dataReserva < dataHoje) {
            horarioElemento.classList.add("horario-passado");
            horarioElemento.setAttribute(
              "title",
              "Horário indisponível (passado)"
            );
            horarioElemento.style.pointerEvents = "none";
            horariosContainer.appendChild(horarioElemento);
            continue;
          }

          // Bloqueia horários de hoje que já passaram
          if (dataReserva === dataHoje) {
            const horaAtualCompleta = hoje.toLocaleTimeString([], {
              hour: "2-digit",
              minute: "2-digit",
            });
            if (horaInicio < horaAtualCompleta) {
              horarioElemento.classList.add("horario-passado");
              horarioElemento.setAttribute(
                "title",
                "Horário indisponível (passado)"
              );
              horarioElemento.style.pointerEvents = "none";
              horariosContainer.appendChild(horarioElemento);
              continue;
            }
          }

          // Verifica se o horário está reservado
          const reservaExistente = reservasExistentes.find(
            (reserva) =>
              reserva.horario_inicio === `${horaInicio}:00` &&
              reserva.horario_fim === `${horaFim}:00` &&
              reserva.data_reserva === dataReserva
          );

          if (reservaExistente) {
            if (reservaExistente.status === "pendente") {
              horarioElemento.classList.add("horario-pendente");
              horarioElemento.setAttribute(
                "title",
                "Horário reservado (pendente)"
              );
            } else if (reservaExistente.status === "confirmada") {
              horarioElemento.classList.add("horario-confirmado");
              horarioElemento.setAttribute(
                "title",
                "Horário reservado (confirmado)"
              );
            }
          } else {
            horarioElemento.classList.add("horario-disponivel");
            horarioElemento.addEventListener("click", function () {
              confirmarHorario(
                this.dataset.horarioInicio,
                this.dataset.horarioFim,
                this.dataset.dataReserva
              );
            });
          }

          horariosContainer.appendChild(horarioElemento);
        }
      } else {
        horariosContainer.textContent = "Fechado";
      }
    });
  }

  function confirmarHorario(horarioInicio, horarioFim, dataReserva) {
    const modal = document.getElementById("modal-pagamento");
    modal.style.display = "flex";

    // Obtém os IDs dinâmicos do HTML
    const idEstabelecimento =
      document.getElementById("id_estabelecimento").value;
    const idCliente = document.getElementById("id_cliente").value;

    // Valida os IDs
    if (!idEstabelecimento || !idCliente) {
      alert("Erro: IDs do cliente ou estabelecimento não encontrados.");
      return;
    }

    // Preenche os campos ocultos no formulário
    document.getElementById("data_reserva").value = dataReserva;
    document.getElementById("horario_inicio").value = horarioInicio;
    document.getElementById("horario_fim").value = horarioFim;

    document.getElementById("form-pagamento").onsubmit = async function (e) {
      e.preventDefault();

      const metodoPagamento = document.querySelector(
        'input[name="metodo"]:checked'
      ).value;

      if (metodoPagamento === "paypal") {
        // Fluxo para pagamento via PayPal
        try {
          const response = await fetch("config/criar_pagamento.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              id_estabelecimento: idEstabelecimento,
              id_cliente: idCliente,
              data_reserva: dataReserva,
              horario_inicio: horarioInicio,
              horario_fim: horarioFim,
              metodo_pagamento: metodoPagamento,
            }),
          });

          const data = await response.json();

          if (data.success && data.redirect_url) {
            // Redireciona o usuário para o PayPal
            window.location.href = data.redirect_url;
          } else {
            alert("Erro ao iniciar pagamento via PayPal: " + data.message);
          }
        } catch (error) {
          console.error("Erro ao processar pagamento via PayPal:", error);
          alert("Erro ao processar pagamento via PayPal.");
        }
      } else if (metodoPagamento === "dinheiro") {
        // Fluxo para pagamento em dinheiro
        const formData = new FormData();
        formData.append("id_estabelecimento", idEstabelecimento);
        formData.append("id_cliente", idCliente);
        formData.append("data_reserva", dataReserva);
        formData.append("horario_inicio", horarioInicio);
        formData.append("horario_fim", horarioFim);
        formData.append("metodo_pagamento", metodoPagamento);

        try {
          const response = await fetch("config/inserir_reserva.php", {
            method: "POST",
            body: formData,
          });

          const data = await response.text();

          if (data === "success") {
            alert("Reserva criada com sucesso!");
            modal.style.display = "none";
            carregarReservas();
          } else {
            alert("Erro ao criar reserva: " + data);
          }
        } catch (error) {
          console.error("Erro ao processar pagamento em dinheiro:", error);
          alert("Erro ao processar a reserva.");
        }
      }
    };
  }

  if (closeModal) {
    closeModal.addEventListener("click", function () {
      modal.style.display = "none";
    });
  }

  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  function atualizarSemana() {
    const inicioSemana = new Date(currentDate);
    const fimSemana = new Date(currentDate);
    inicioSemana.setDate(inicioSemana.getDate() - inicioSemana.getDay());
    fimSemana.setDate(inicioSemana.getDate() + 6);

    semanaAtual.textContent = `Semana de ${inicioSemana.toLocaleDateString()} a ${fimSemana.toLocaleDateString()}`;
    btnSemanaAnterior.disabled = currentDate <= dataAtual;
  }

  function carregarReservas() {
    const idEstabelecimento = 1;
    const inicioSemana = new Date(currentDate);
    const fimSemana = new Date(currentDate);

    inicioSemana.setDate(inicioSemana.getDate() - inicioSemana.getDay());
    fimSemana.setDate(inicioSemana.getDate() + 6);

    const dataInicio = inicioSemana.toISOString().split("T")[0];
    const dataFim = fimSemana.toISOString().split("T")[0];

    console.log(
      `Carregando reservas para: id_estabelecimento=${idEstabelecimento}, data_inicio=${dataInicio}, data_fim=${dataFim}`
    );

    fetch(
      `config/carregar_reservas.php?id_estabelecimento=${idEstabelecimento}&data_inicio=${dataInicio}&data_fim=${dataFim}`
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erro ao carregar reservas: " + response.status);
        }
        return response.json();
      })
      .then((data) => {
        console.log("Reservas recebidas:", data);
        reservasExistentes = data.map((reserva) => ({
          horario_inicio: reserva.horario_inicio,
          horario_fim: reserva.horario_fim,
          data_reserva: reserva.data_reserva,
          status: reserva.status,
        }));
        exibirHorariosDisponiveis();
      })
      .catch((error) => {
        console.error("Erro ao carregar reservas:", error);
      });
  }

  atualizarSemana();

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

  carregarReservas();
});
