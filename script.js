const form = document.querySelector('#cita-form');
const confirmation = document.querySelector('#mensaje-confirmacion');

form.addEventListener('submit', (event) => {
  event.preventDefault();

  const data = new FormData(form);
  const name = data.get('nombre');

  confirmation.textContent = `Gracias, ${name}. Se abrirá tu correo para finalizar el envío de la cita.`;

  setTimeout(() => {
    form.submit();
    form.reset();
  }, 400);
});
