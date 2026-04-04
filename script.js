const form = document.querySelector('#cita-form');
const mensaje = document.querySelector('#mensaje');

form.addEventListener('submit', (event) => {
  event.preventDefault();
  const data = new FormData(form);
  const nombre = data.get('nombre');

  mensaje.textContent = `Gracias, ${nombre}. Tu solicitud fue enviada. Te contactaremos pronto.`;
  form.reset();
});
