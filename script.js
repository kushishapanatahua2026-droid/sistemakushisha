function setupContactForm() {
  const form = document.querySelector('#cita-form');
  const message = document.querySelector('#mensaje-confirmacion');

  if (!form || !message) return;

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(form);
    const name = formData.get('nombre') || 'Paciente';

    message.textContent = `Gracias, ${name}. Se abrirá tu correo para completar la solicitud.`;

    setTimeout(() => {
      form.submit();
      form.reset();
    }, 350);
  });
}

setupContactForm();
