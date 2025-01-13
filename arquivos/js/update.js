document.addEventListener("DOMContentLoaded", function () {
  // Função para verificar e atualizar reservas
  function verificarReservas() {
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
