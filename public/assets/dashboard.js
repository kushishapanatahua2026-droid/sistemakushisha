async function loadDashboard() {
  const res = await fetch('api/dashboard_data.php');
  const data = await res.json();

  document.getElementById('kpiTotal').textContent = data.kpis.total_registros;
  document.getElementById('kpiServicios').textContent = data.kpis.total_servicios;
  document.getElementById('kpiProfesionales').textContent = data.kpis.total_profesionales;
  document.getElementById('kpiDiagnosticos').textContent = data.kpis.total_diagnosticos;

  new Chart(document.getElementById('chartTopDiagnosticos'), {
    type: 'bar',
    data: { labels: data.top_diagnosticos.labels, datasets: [{ label: 'Top 10 Diagnósticos', data: data.top_diagnosticos.values }] }
  });

  new Chart(document.getElementById('chartTipoDiag'), {
    type: 'pie',
    data: { labels: data.tipo_diagnostico.labels, datasets: [{ data: data.tipo_diagnostico.values }] }
  });

  new Chart(document.getElementById('chartPorFecha'), {
    type: 'line',
    data: { labels: data.por_fecha.labels, datasets: [{ label: 'Atenciones por Fecha', data: data.por_fecha.values }] }
  });
}

loadDashboard();
