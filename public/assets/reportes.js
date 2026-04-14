let tablaReporte;
let chartPie;
let chartBar;

function buildQuery(params) {
  const search = new URLSearchParams();
  Object.entries(params).forEach(([k, v]) => { if (v) search.append(k, v); });
  return search.toString();
}

function drawCharts(data) {
  chartPie?.destroy();
  chartBar?.destroy();

  chartPie = new Chart(document.getElementById('chartReportePie'), {
    type: 'pie',
    data: { labels: data.tipo_diagnostico.labels, datasets: [{ data: data.tipo_diagnostico.values }] }
  });

  chartBar = new Chart(document.getElementById('chartReporteBar'), {
    type: 'bar',
    data: { labels: data.servicios.labels, datasets: [{ label: 'Cantidad por Servicio', data: data.servicios.values }] }
  });
}

function loadReporte(filters = {}) {
  const qs = buildQuery(filters);
  const url = `api/reporte_data.php?${qs}`;

  if (tablaReporte) {
    tablaReporte.ajax.url(url).load();
  } else {
    tablaReporte = $('#tablaReporte').DataTable({
      ajax: { url, dataSrc: 'table' },
      dom: 'Bfrtip',
      buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5'],
      columns: [
        { title: 'Fecha', data: 'Fecha_Atencion' },
        { title: 'Paciente', data: 'paciente' },
        { title: 'Profesional', data: 'profesional' },
        { title: 'Servicio', data: 'Descripcion_Ups' },
        { title: 'Diagnóstico', data: 'Descripcion_Item' },
        { title: 'Tipo', data: 'Tipo_Diagnostico' }
      ]
    });
  }

  fetch(url)
    .then(r => r.json())
    .then(drawCharts);
}

$('#filtrosForm').on('submit', function (e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this).entries());
  loadReporte(data);
});

$('#btnLimpiar').on('click', function () {
  $('#filtrosForm')[0].reset();
  loadReporte({});
});

loadReporte({});
