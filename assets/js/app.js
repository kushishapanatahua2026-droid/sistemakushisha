(() => {
  let table;
  let charts = [];

  const filterFields = [
    { key: 'anio', label: 'Año' },
    { key: 'mes', label: 'Mes' },
    { key: 'profesional', label: 'Profesional' },
    { key: 'servicio', label: 'Servicio' },
    { key: 'diagnostico', label: 'Diagnóstico' },
    { key: 'establecimiento', label: 'Establecimiento' },
    { key: 'red', label: 'Red' },
    { key: 'microred', label: 'Microred' }
  ];

  function initSidebar() {
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        const page = link.dataset.page;
        document.querySelectorAll('.page-section').forEach(s => s.classList.add('d-none'));
        document.getElementById(`page-${page}`).classList.remove('d-none');
      });
    });
  }

  function initTable() {
    table = $('#tablaRegistros').DataTable({
      ajax: '../ajax/get_rows.php',
      columns: [
        { data: 'id' }, { data: 'anio' }, { data: 'mes' }, { data: 'fecha' },
        { data: 'profesional' }, { data: 'servicio' }, { data: 'diagnostico' },
        { data: 'establecimiento' }, { data: 'red' }, { data: 'microred' },
        { data: 'categoria' }, { data: 'estado' }
      ],
      pageLength: 25,
      responsive: true,
      dom: 'Bfrtip',
      buttons: ['excelHtml5', 'pdfHtml5', 'csvHtml5'],
      order: [[0, 'desc']],
      language: { url: 'https://cdn.datatables.net/plug-ins/1.13.11/i18n/es-ES.json' }
    });
  }

  function bindUpload() {
    const form = document.getElementById('uploadForm');
    const alert = document.getElementById('uploadAlert');

    form.addEventListener('submit', async e => {
      e.preventDefault();
      const fd = new FormData(form);
      alert.innerHTML = '<div class="alert alert-info">Importando, espere...</div>';

      try {
        const res = await fetch('../ajax/import_excel.php', { method: 'POST', body: fd });
        const data = await res.json();
        if (!data.ok) throw new Error(data.message || 'Error al importar');

        alert.innerHTML = `<div class="alert alert-success">${data.data.message}. Filas insertadas: ${data.data.inserted}</div>`;
        table.ajax.reload();
      } catch (err) {
        alert.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
      }
    });
  }

  async function loadFilters() {
    const container = document.getElementById('filtersContainer');
    const res = await fetch('../ajax/get_filters.php');
    const json = await res.json();
    const data = json.data || {};

    container.innerHTML = filterFields.map(f => {
      const options = (data[f.key] || []).map(v => `<option value="${String(v)}">${String(v)}</option>`).join('');
      return `
        <div class="col-md-6">
          <label class="form-label">${f.label}</label>
          <select id="filter_${f.key}" class="form-select" multiple size="4">${options}</select>
        </div>
      `;
    }).join('');
  }

  function selectedValues(id) {
    const select = document.getElementById(id);
    return Array.from(select.selectedOptions).map(o => o.value);
  }

  function clearDashboard() {
    charts.forEach(c => c.destroy());
    charts = [];
    document.getElementById('kpiRow').innerHTML = '';
    document.getElementById('chartsRow').innerHTML = '';
  }

  function renderDashboard(data) {
    clearDashboard();

    const kpiRow = document.getElementById('kpiRow');
    const chartsRow = document.getElementById('chartsRow');

    if (typeof data.kpis.total_registros !== 'undefined') {
      kpiRow.innerHTML += `
        <div class="col-md-3">
          <div class="card text-bg-primary shadow-sm">
            <div class="card-body">
              <h6 class="card-title">Total de registros</h6>
              <h2>${data.kpis.total_registros}</h2>
            </div>
          </div>
        </div>`;
    }

    Object.entries(data.charts || {}).forEach(([key, info], i) => {
      const chartId = `chart_${key}_${i}`;
      const tableRows = (info.table || []).slice(0, 20).map(r => `<tr><td>${r.etiqueta ?? ''}</td><td>${r.total ?? 0}</td></tr>`).join('');
      chartsRow.innerHTML += `
        <div class="col-lg-6">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h6>${info.title}</h6>
              <canvas id="${chartId}" height="120"></canvas>
              <div class="table-responsive mt-3 small-table">
                <table class="table table-sm table-bordered"><thead><tr><th>Etiqueta</th><th>Total</th></tr></thead><tbody>${tableRows}</tbody></table>
              </div>
            </div>
          </div>
        </div>`;

      const ctx = document.getElementById(chartId);
      charts.push(new Chart(ctx, {
        type: info.type || 'bar',
        data: {
          labels: info.labels || [],
          datasets: [{ label: info.title, data: info.values || [] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      }));
    });
  }

  function bindReports() {
    document.getElementById('btnGenerar').addEventListener('click', async () => {
      const indicators = selectedValues('indicadores');
      if (!indicators.length) {
        alert('Seleccione al menos un indicador.');
        return;
      }

      const filters = {};
      filterFields.forEach(f => {
        const vals = selectedValues(`filter_${f.key}`);
        if (vals.length) filters[f.key] = vals;
      });

      const res = await fetch('../ajax/get_dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ indicators, filters })
      });
      const data = await res.json();

      if (!data.ok) {
        alert(data.message || 'Error al generar dashboard');
        return;
      }

      renderDashboard(data.data || {});
    });

    document.getElementById('btnLimpiar').addEventListener('click', () => {
      document.querySelectorAll('#indicadores option, #filtersContainer option').forEach(o => { o.selected = false; });
      clearDashboard();
    });
  }

  async function init() {
    initSidebar();
    initTable();
    bindUpload();
    await loadFilters();
    bindReports();
  }

  window.addEventListener('DOMContentLoaded', init);
})();
