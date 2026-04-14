CREATE DATABASE IF NOT EXISTS sistema_dashboard_salud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_dashboard_salud;

CREATE TABLE IF NOT EXISTS atenciones (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  Id_Cita VARCHAR(50), Anio VARCHAR(10), Mes VARCHAR(10), Dia VARCHAR(10), Fecha_Atencion VARCHAR(30),
  Lote VARCHAR(30), Num_Pag VARCHAR(30), Num_Reg VARCHAR(30), Id_Ups VARCHAR(30), Descripcion_Ups VARCHAR(255),
  Descripcion_Sector VARCHAR(255), Descripcion_Disa VARCHAR(255), Descripcion_Red VARCHAR(255), Descripcion_MicroRed VARCHAR(255), Codigo_Unico VARCHAR(80),
  Nombre_Establecimiento VARCHAR(255), Abrev_Tipo_Doc_Paciente VARCHAR(20), Numero_Documento_Paciente VARCHAR(30), Apellido_Paterno_Paciente VARCHAR(120),
  Apellido_Materno_Paciente VARCHAR(120), Nombres_Paciente VARCHAR(180), Fecha_Nacimiento_Paciente VARCHAR(30), Genero VARCHAR(20), Id_Etnia VARCHAR(20),
  Descripcion_Etnia VARCHAR(255), Historia_Clinica VARCHAR(100), Ficha_Familiar VARCHAR(100), Id_Financiador VARCHAR(20), Descripcion_Financiador VARCHAR(255),
  Descripcion_Pais VARCHAR(120), Abrev_Tipo_Doc_Personal VARCHAR(20), Numero_Documento_Personal VARCHAR(30), Apellido_Paterno_Personal VARCHAR(120),
  Apellido_Materno_Personal VARCHAR(120), Nombres_Personal VARCHAR(180), Fecha_Nacimiento_Personal VARCHAR(30), Id_Condicion VARCHAR(20), Descripcion_Condicion VARCHAR(255),
  Id_Profesion VARCHAR(20), Descripcion_Profesion VARCHAR(255), Id_Colegio VARCHAR(20), Descripcion_Colegio VARCHAR(255), Numero_Colegiatura VARCHAR(60),
  Abrev_Tipo_Doc_Registrador VARCHAR(20), Numero_Documento_Registrador VARCHAR(30), Apellido_Paterno_Registrador VARCHAR(120), Apellido_Materno_Registrador VARCHAR(120),
  Nombres_Registrador VARCHAR(180), Fecha_Nacimiento_Registrador VARCHAR(30), Id_Condicion_Establecimiento VARCHAR(20), Id_Condicion_Servicio VARCHAR(20), Edad_Reg VARCHAR(20),
  Tipo_Edad VARCHAR(20), Anio_Actual_Paciente VARCHAR(10), Mes_Actual_Paciente VARCHAR(10), Dia_Actual_Paciente VARCHAR(10), Id_Turno VARCHAR(20), Codigo_Item VARCHAR(40),
  Descripcion_Item VARCHAR(255), Tipo_Diagnostico VARCHAR(40), Valor_Lab VARCHAR(120), Id_Correlativo VARCHAR(30), Peso VARCHAR(30), Talla VARCHAR(30), Hemoglobina VARCHAR(30),
  Perimetro_Abdominal VARCHAR(30), Perimetro_Cefalico VARCHAR(30), Descripcion_Otra_Condicion VARCHAR(255), Fecha_Ultima_Regla VARCHAR(30), Fecha_Solicitud_Hb VARCHAR(30),
  Fecha_Resultado_Hb VARCHAR(30), Fecha_Registro VARCHAR(30), Fecha_Modificacion VARCHAR(30), Intervalo VARCHAR(30),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_anio_mes (Anio, Mes),
  INDEX idx_servicio (Descripcion_Ups),
  INDEX idx_diagnostico (Descripcion_Item),
  INDEX idx_profesional (Numero_Documento_Personal),
  INDEX idx_fecha_atencion (Fecha_Atencion),
  INDEX idx_red_microred (Descripcion_Red, Descripcion_MicroRed)
);
