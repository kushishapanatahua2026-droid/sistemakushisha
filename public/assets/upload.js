$(function () {
  $('#tablaAtenciones').DataTable({
    ajax: 'api/atenciones_list.php',
    processing: true,
    serverSide: false,
    pageLength: 10,
    dom: 'Bfrtip',
    buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5'],
    columns: [
      { data: 'Id_Cita' },
      { data: 'Fecha_Atencion' },
      { data: 'paciente' },
      { data: 'profesional' },
      { data: 'Descripcion_Ups' },
      { data: 'Descripcion_Item' },
      { data: 'Tipo_Diagnostico' }
    ]
  });
});
