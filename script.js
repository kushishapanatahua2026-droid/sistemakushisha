async function loadIncludes() {
  const headerTarget = document.querySelector('#header-include');
  const footerTarget = document.querySelector('#footer-include');

  if (headerTarget) {
    const response = await fetch('includes/header.html');
    headerTarget.innerHTML = await response.text();

    const page = document.body.dataset.page;
    if (page) {
      const activeLink = headerTarget.querySelector(`[data-page="${page}"]`);
      if (activeLink) activeLink.classList.add('active');
    }
  }

  if (footerTarget) {
    const response = await fetch('includes/footer.html');
    footerTarget.innerHTML = await response.text();
  }
}

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

loadIncludes().then(setupContactForm).catch(() => {
  setupContactForm();
});
