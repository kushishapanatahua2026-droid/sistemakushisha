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

function setupRevealEffects() {
  const elements = document.querySelectorAll('.reveal');
  if (!elements.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 },
  );

  elements.forEach((element) => observer.observe(element));
}

setupContactForm();
setupRevealEffects();
