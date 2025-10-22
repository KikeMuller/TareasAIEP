using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows.Forms;
using InstitutoCodice.Core;

namespace InstitutoCodice.Desktop
{
    public partial class FormPrincipal : Form
    {
        // Listas para almacenar datos en memoria
        private List<Estudiante> estudiantes = new List<Estudiante>();
private List<Asignatura> asignaturas = new List<Asignatura>();
private List<Docente> docentes = new List<Docente>();
private BindingSource asignaturasBinding = new BindingSource();
private BindingSource estudiantesBinding = new BindingSource();

        public FormPrincipal()
        {
            InicializarDatos();     // ensure lists exist
            InitializeComponent();
            CargarDatosIniciales();
        }

        private void InitializeComponent()
        {
            this.SuspendLayout();
            
            // Configuración del formulario principal
            this.ClientSize = new System.Drawing.Size(1000, 700);
            this.Text = "Sistema de Gestión - Instituto Códice";
            this.StartPosition = FormStartPosition.CenterScreen;
            
            // Panel superior con título
            var panelTitulo = new Panel
            {
                Dock = DockStyle.Top,
                Height = 80,
                BackColor = System.Drawing.Color.FromArgb(41, 128, 185)
            };
            
            var lblTitulo = new Label
            {
                Text = "INSTITUTO CÓDICE\nSistema de Gestión Académica",
                ForeColor = System.Drawing.Color.White,
                Font = new System.Drawing.Font("Segoe UI", 16F, System.Drawing.FontStyle.Bold),
                TextAlign = System.Drawing.ContentAlignment.MiddleCenter,
                Dock = DockStyle.Fill
            };
            panelTitulo.Controls.Add(lblTitulo);
            this.Controls.Add(panelTitulo);
            
            // TabControl principal
            var tabControl = new TabControl
            {
                Dock = DockStyle.Fill,
                Font = new System.Drawing.Font("Segoe UI", 10F)
            };
            
            // Tab 1: Gestión de Estudiantes
            var tabEstudiantes = new TabPage("Estudiantes");
            CrearTabEstudiantes(tabEstudiantes);
            tabControl.TabPages.Add(tabEstudiantes);
            
            // Tab 2: Registro de Asistencia
            var tabAsistencia = new TabPage("Asistencia");
            CrearTabAsistencia(tabAsistencia);
            tabControl.TabPages.Add(tabAsistencia);
            
            // Tab 3: Registro de Notas
            var tabNotas = new TabPage("Notas");
            CrearTabNotas(tabNotas);
            tabControl.TabPages.Add(tabNotas);
            
            // Tab 4: Reportes
            var tabReportes = new TabPage("Reportes");
            CrearTabReportes(tabReportes);
            tabControl.TabPages.Add(tabReportes);
            
            this.Controls.Add(tabControl);
            
            // Panel inferior con información
            var panelFooter = new Panel
            {
                Dock = DockStyle.Bottom,
                Height = 30,
                BackColor = System.Drawing.Color.FromArgb(44, 62, 80)
            };
            
            var lblFooter = new Label
            {
                Text = "© 2025 Instituto Códice - Sistema v1.0",
                ForeColor = System.Drawing.Color.White,
                TextAlign = System.Drawing.ContentAlignment.MiddleCenter,
                Dock = DockStyle.Fill
            };
            panelFooter.Controls.Add(lblFooter);
            this.Controls.Add(panelFooter);
            
            this.ResumeLayout(false);
        }

        private void InicializarDatos()
        {
            estudiantes = new List<Estudiante>();
            asignaturas = new List<Asignatura>();
            docentes = new List<Docente>();
        }

        private void CargarDatosIniciales()
        {
            // Crear asignaturas de ejemplo
            asignaturas.Add(new Asignatura("MAT101", "Matemáticas I", "1° Medio"));
            asignaturas.Add(new Asignatura("LEN101", "Lenguaje", "1° Medio"));
            asignaturas.Add(new Asignatura("HIS201", "Historia", "2° Medio"));
            asignaturas.Add(new Asignatura("FIS301", "Física", "3° Medio"));

            // Crear docentes de ejemplo
            var docente1 = new Docente("12345678-9", "Roberto", "González");
            docente1.Especialidad = "Matemáticas";
            docente1.AsignarAsignatura(asignaturas[0]);
            docentes.Add(docente1);

            var docente2 = new Docente("98765432-1", "María", "Silva");
            docente2.Especialidad = "Lenguaje";
            docente2.AsignarAsignatura(asignaturas[1]);
            docentes.Add(docente2);
        }

        // ====================
        // TAB ESTUDIANTES
        // ====================
        private void CrearTabEstudiantes(TabPage tab)
        {
            var panel = new Panel { Dock = DockStyle.Fill, Padding = new Padding(20) };
            
            // Controles de entrada
            var lblRut = new Label { Text = "RUT:", Location = new System.Drawing.Point(20, 20), AutoSize = true };
            var txtRut = new TextBox { Location = new System.Drawing.Point(120, 17), Width = 150 };
            
            var lblNombre = new Label { Text = "Nombre:", Location = new System.Drawing.Point(20, 55), AutoSize = true };
            var txtNombre = new TextBox { Location = new System.Drawing.Point(120, 52), Width = 200 };
            
            var lblApellido = new Label { Text = "Apellido:", Location = new System.Drawing.Point(20, 90), AutoSize = true };
            var txtApellido = new TextBox { Location = new System.Drawing.Point(120, 87), Width = 200 };
            
            var lblEmail = new Label { Text = "Email:", Location = new System.Drawing.Point(20, 125), AutoSize = true };
            var txtEmail = new TextBox { Location = new System.Drawing.Point(120, 122), Width = 250 };
            
            var btnAgregar = new Button 
            { 
                Text = "Agregar Estudiante", 
                Location = new System.Drawing.Point(120, 160),
                Width = 150,
                Height = 35,
                BackColor = System.Drawing.Color.FromArgb(46, 204, 113),
                ForeColor = System.Drawing.Color.White,
                FlatStyle = FlatStyle.Flat
            };
            
            // DataGridView para mostrar estudiantes
            var dgvEstudiantes = new DataGridView
            {
                Location = new System.Drawing.Point(20, 210),
                Size = new System.Drawing.Size(900, 350),
                AutoGenerateColumns = false,
                AllowUserToAddRows = false,
                ReadOnly = true,
                SelectionMode = DataGridViewSelectionMode.FullRowSelect
            };
            
            dgvEstudiantes.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "RUT", 
                DataPropertyName = "Rut", 
                Width = 120 
            });
            dgvEstudiantes.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Nombre Completo", 
                DataPropertyName = "Nombre", 
                Width = 200 
            });
            dgvEstudiantes.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Matrícula", 
                DataPropertyName = "NumeroMatricula", 
                Width = 120 
            });
            dgvEstudiantes.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Email", 
                DataPropertyName = "Email", 
                Width = 200 
            });
            dgvEstudiantes.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Estado", 
                DataPropertyName = "Estado", 
                Width = 100 
            });
            
            // Evento del botón agregar
            btnAgregar.Click += (sender, e) =>
            {
                try
                {
                    if (string.IsNullOrWhiteSpace(txtRut.Text) || 
                        string.IsNullOrWhiteSpace(txtNombre.Text) || 
                        string.IsNullOrWhiteSpace(txtApellido.Text))
                    {
                        MessageBox.Show("Por favor complete los campos RUT, Nombre y Apellido.", 
                                      "Validación", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                        return;
                    }

                    var estudiante = new Estudiante(txtRut.Text, txtNombre.Text, txtApellido.Text);
                    estudiante.Email = txtEmail.Text;
                    estudiantes.Add(estudiante);
                    
                    ActualizarGridEstudiantes(dgvEstudiantes);
                    
                    MessageBox.Show($"Estudiante {estudiante.Nombre} {estudiante.Apellido} agregado correctamente.\n" +
                                  $"Matrícula: {estudiante.NumeroMatricula}", 
                                  "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information);
                    
                    // Limpiar campos
                    txtRut.Clear();
                    txtNombre.Clear();
                    txtApellido.Clear();
                    txtEmail.Clear();
                    txtRut.Focus();
                }
                catch (Exception ex)
                {
                    MessageBox.Show($"Error al agregar estudiante: {ex.Message}", 
                                  "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            };
            
            panel.Controls.AddRange(new Control[] 
            { 
                lblRut, txtRut, lblNombre, txtNombre, lblApellido, txtApellido,
                lblEmail, txtEmail, btnAgregar, dgvEstudiantes 
            });
            
            tab.Controls.Add(panel);
        }

        private void ActualizarGridEstudiantes(DataGridView dgv)
        {
            dgv.DataSource = null;
            dgv.DataSource = estudiantes.Select(e => new
            {
                e.Rut,
                Nombre = $"{e.Nombre} {e.Apellido}",
                e.NumeroMatricula,
                e.Email,
                e.Estado
            }).ToList();
        }

        // ====================
        // TAB ASISTENCIA
        // ====================
        private void CrearTabAsistencia(TabPage tab)
        {
            var panel = new Panel { Dock = DockStyle.Fill, Padding = new Padding(20) };
            
            var lblAsignatura = new Label 
            { 
                Text = "Asignatura:", 
                Location = new System.Drawing.Point(20, 20), 
                AutoSize = true 
            };
            
            var cboAsignatura = new ComboBox
            {
                Location = new System.Drawing.Point(120, 17),
                Width = 300,
                DropDownStyle = ComboBoxStyle.DropDownList
            };
            cboAsignatura.DataSource = (asignaturas ?? new List<Asignatura>()).ToList();
            cboAsignatura.DisplayMember = "Nombre";
            
            var lblEstudiante = new Label 
            { 
                Text = "Estudiante:", 
                Location = new System.Drawing.Point(20, 55), 
                AutoSize = true 
            };
            
            var cboEstudiante = new ComboBox
            {
                Location = new System.Drawing.Point(120, 52),
                Width = 300,
                DropDownStyle = ComboBoxStyle.DropDownList
            };
            
            var lblFecha = new Label 
            { 
                Text = "Fecha:", 
                Location = new System.Drawing.Point(20, 90), 
                AutoSize = true 
            };
            
            var dtpFecha = new DateTimePicker
            {
                Location = new System.Drawing.Point(120, 87),
                Width = 200
            };
            
            var lblEstado = new Label 
            { 
                Text = "Estado:", 
                Location = new System.Drawing.Point(20, 125), 
                AutoSize = true 
            };
            
            var cboEstado = new ComboBox
            {
                Location = new System.Drawing.Point(120, 122),
                Width = 150,
                DropDownStyle = ComboBoxStyle.DropDownList
            };
            cboEstado.Items.AddRange(new object[] { "Presente", "Ausente", "Justificado", "Tardanza" });
            cboEstado.SelectedIndex = 0;
            
            var btnRegistrar = new Button
            {
                Text = "Registrar Asistencia",
                Location = new System.Drawing.Point(120, 165),
                Width = 170,
                Height = 35,
                BackColor = System.Drawing.Color.FromArgb(52, 152, 219),
                ForeColor = System.Drawing.Color.White,
                FlatStyle = FlatStyle.Flat
            };
            
            var dgvAsistencias = new DataGridView
            {
                Location = new System.Drawing.Point(20, 220),
                Size = new System.Drawing.Size(900, 340),
                AutoGenerateColumns = false,
                AllowUserToAddRows = false,
                ReadOnly = true,
                SelectionMode = DataGridViewSelectionMode.FullRowSelect
            };
            
            dgvAsistencias.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Fecha", 
                DataPropertyName = "Fecha", 
                Width = 120 
            });
            dgvAsistencias.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Estudiante", 
                DataPropertyName = "Estudiante", 
                Width = 250 
            });
            dgvAsistencias.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Asignatura", 
                DataPropertyName = "Asignatura", 
                Width = 200 
            });
            dgvAsistencias.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Estado", 
                DataPropertyName = "Estado", 
                Width = 120 
            });
            dgvAsistencias.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Observación", 
                DataPropertyName = "Observacion", 
                Width = 200 
            });
            
            // Actualizar lista de estudiantes cuando cambie la asignatura
            cboAsignatura.SelectedIndexChanged += (sender, e) =>
            {
                if (cboAsignatura.SelectedItem is Asignatura asig)
                {
                    cboEstudiante.DataSource = estudiantes;
                    cboEstudiante.DisplayMember = "Nombre";
                }
            };
            
            btnRegistrar.Click += (sender, e) =>
            {
                try
                {
                    if (cboAsignatura.SelectedItem == null || cboEstudiante.SelectedItem == null)
                    {
                        MessageBox.Show("Seleccione asignatura y estudiante.", "Validación", 
                                      MessageBoxButtons.OK, MessageBoxIcon.Warning);
                        return;
                    }

                    var asignatura = (Asignatura)cboAsignatura.SelectedItem;
                    var estudiante = (Estudiante)cboEstudiante.SelectedItem;
                    
                    var asistencia = new Asistencia(estudiante, asignatura, dtpFecha.Value);
                    
                    switch (cboEstado.SelectedItem.ToString())
                    {
                        case "Presente":
                            asistencia.MarcarPresente();
                            break;
                        case "Ausente":
                            asistencia.MarcarAusente();
                            break;
                        case "Justificado":
                            asistencia.Justificar("Justificado por sistema");
                            break;
                        case "Tardanza":
                            asistencia.Estado = EstadoAsistencia.Tardanza;
                            break;
                    }
                    
                    estudiante.Asistencias.Add(asistencia);
                    asignatura.Asistencias.Add(asistencia);
                    
                    ActualizarGridAsistencias(dgvAsistencias);
                    
                    MessageBox.Show("Asistencia registrada correctamente.", "Éxito", 
                                  MessageBoxButtons.OK, MessageBoxIcon.Information);
                }
                catch (Exception ex)
                {
                    MessageBox.Show($"Error: {ex.Message}", "Error", 
                                  MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            };
            
            panel.Controls.AddRange(new Control[]
            {
                lblAsignatura, cboAsignatura, lblEstudiante, cboEstudiante,
                lblFecha, dtpFecha, lblEstado, cboEstado, btnRegistrar, dgvAsistencias
            });
            
            tab.Controls.Add(panel);
        }

        private void ActualizarGridAsistencias(DataGridView dgv)
        {
            var todasAsistencias = estudiantes
                .SelectMany(e => e.Asistencias)
                .Select(a => new
                {
                    Fecha = a.Fecha.ToShortDateString(),
                    Estudiante = $"{a.Estudiante.Nombre} {a.Estudiante.Apellido}",
                    Asignatura = a.Asignatura.Nombre,
                    Estado = a.Estado.ToString(),
                    Observacion = a.Observacion ?? ""
                })
                .OrderByDescending(a => a.Fecha)
                .ToList();
            
            dgv.DataSource = todasAsistencias;
        }

        // ====================
        // TAB NOTAS
        // ====================
        private void CrearTabNotas(TabPage tab)
        {
            var panel = new Panel { Dock = DockStyle.Fill, Padding = new Padding(20) };
            
            var lblAsignatura = new Label 
            { 
                Text = "Asignatura:", 
                Location = new System.Drawing.Point(20, 20), 
                AutoSize = true 
            };
            
            var cboAsignatura = new ComboBox
            {
                Location = new System.Drawing.Point(140, 17),
                Width = 300,
                DropDownStyle = ComboBoxStyle.DropDownList
            };
            cboAsignatura.DataSource = asignaturasBinding;
cboAsignatura.DisplayMember = "Nombre";
            
            var lblEstudiante = new Label 
            { 
                Text = "Estudiante:", 
                Location = new System.Drawing.Point(20, 55), 
                AutoSize = true 
            };
            
            var cboEstudiante = new ComboBox
            {
                Location = new System.Drawing.Point(140, 52),
                Width = 300,
                DropDownStyle = ComboBoxStyle.DropDownList
            };
            cboEstudiante.DataSource = estudiantesBinding;
cboEstudiante.DisplayMember = "Nombre";
            
            var lblNota = new Label 
            { 
                Text = "Nota (1.0-7.0):", 
                Location = new System.Drawing.Point(20, 90), 
                AutoSize = true 
            };
            
            var numNota = new NumericUpDown
            {
                Location = new System.Drawing.Point(140, 87),
                Width = 100,
                Minimum = 1.0M,
                Maximum = 7.0M,
                DecimalPlaces = 1,
                Increment = 0.1M,
                Value = 4.0M
            };
            
            var lblTipo = new Label 
            { 
                Text = "Tipo Evaluación:", 
                Location = new System.Drawing.Point(20, 125), 
                AutoSize = true 
            };
            
            var cboTipo = new ComboBox
            {
                Location = new System.Drawing.Point(140, 122),
                Width = 150,
                DropDownStyle = ComboBoxStyle.DropDownList
            };
            cboTipo.Items.AddRange(new object[] { "Prueba", "Tarea", "Proyecto", "Examen", "Trabajo", "Presentacion" });
            cboTipo.SelectedIndex = 0;
            
            var btnRegistrarNota = new Button
            {
                Text = "Registrar Nota",
                Location = new System.Drawing.Point(140, 165),
                Width = 150,
                Height = 35,
                BackColor = System.Drawing.Color.FromArgb(155, 89, 182),
                ForeColor = System.Drawing.Color.White,
                FlatStyle = FlatStyle.Flat
            };
            
            var dgvNotas = new DataGridView
            {
                Location = new System.Drawing.Point(20, 220),
                Size = new System.Drawing.Size(900, 340),
                AutoGenerateColumns = false,
                AllowUserToAddRows = false,
                ReadOnly = true,
                SelectionMode = DataGridViewSelectionMode.FullRowSelect
            };
            
            dgvNotas.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Estudiante", 
                DataPropertyName = "Estudiante", 
                Width = 200 
            });
            dgvNotas.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Asignatura", 
                DataPropertyName = "Asignatura", 
                Width = 180 
            });
            dgvNotas.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Nota", 
                DataPropertyName = "Nota", 
                Width = 80 
            });
            dgvNotas.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Tipo", 
                DataPropertyName = "Tipo", 
                Width = 120 
            });
            dgvNotas.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Fecha", 
                DataPropertyName = "Fecha", 
                Width = 100 
            });
            dgvNotas.Columns.Add(new DataGridViewTextBoxColumn 
            { 
                HeaderText = "Estado", 
                DataPropertyName = "Estado", 
                Width = 100 
            });
            
            btnRegistrarNota.Click += (sender, e) =>
            {
                try
                {
                    if (cboAsignatura.SelectedItem == null || cboEstudiante.SelectedItem == null)
                    {
                        MessageBox.Show("Seleccione asignatura y estudiante.", "Validación",
                                      MessageBoxButtons.OK, MessageBoxIcon.Warning);
                        return;
                    }

                    var asignatura = (Asignatura)cboAsignatura.SelectedItem;
                    var estudiante = (Estudiante)cboEstudiante.SelectedItem;
                    var valor = (double)numNota.Value;
                    
                    TipoEvaluacion tipo = cboTipo.SelectedItem.ToString() switch
                    {
                        "Prueba" => TipoEvaluacion.Prueba,
                        "Tarea" => TipoEvaluacion.Tarea,
                        "Proyecto" => TipoEvaluacion.Proyecto,
                        "Examen" => TipoEvaluacion.Examen,
                        "Trabajo" => TipoEvaluacion.Trabajo,
                        _ => TipoEvaluacion.Presentacion
                    };
                    
                    var nota = new Nota(estudiante, asignatura, valor, tipo);
                    estudiante.Notas.Add(nota);
                    asignatura.Notas.Add(nota);
                    
                    ActualizarGridNotas(dgvNotas);
                    
                    string estado = nota.EstaAprobado() ? "APROBADO" : "REPROBADO";
                    MessageBox.Show($"Nota registrada: {valor:F1}\nEstado: {estado}", "Éxito",
                                  MessageBoxButtons.OK, MessageBoxIcon.Information);
                }
                catch (Exception ex)
                {
                    MessageBox.Show($"Error: {ex.Message}", "Error",
                                  MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            };
            
            panel.Controls.AddRange(new Control[]
            {
                lblAsignatura, cboAsignatura, lblEstudiante, cboEstudiante,
                lblNota, numNota, lblTipo, cboTipo, btnRegistrarNota, dgvNotas
            });
            
            tab.Controls.Add(panel);
        }

        private void ActualizarGridNotas(DataGridView dgv)
        {
            var todasNotas = estudiantes
                .SelectMany(e => e.Notas)
                .Select(n => new
                {
                    Estudiante = $"{n.Estudiante.Nombre} {n.Estudiante.Apellido}",
                    Asignatura = n.Asignatura.Nombre,
                    Nota = n.Valor.ToString("F1"),
                    Tipo = n.TipoEvaluacion.ToString(),
                    Fecha = n.Fecha.ToShortDateString(),
                    Estado = n.EstaAprobado() ? "Aprobado" : "Reprobado"
                })
                .OrderByDescending(n => n.Fecha)
                .ToList();
            
            dgv.DataSource = todasNotas;
        }

        // ====================
        // TAB REPORTES
        // ====================
        private void CrearTabReportes(TabPage tab)
        {
            var panel = new Panel { Dock = DockStyle.Fill, Padding = new Padding(20) };
            
            var lblTitulo = new Label
            {
                Text = "Generación de Reportes",
                Location = new System.Drawing.Point(20, 20),
                Font = new System.Drawing.Font("Segoe UI", 14F, System.Drawing.FontStyle.Bold),
                AutoSize = true
            };
            
            var btnReporteEstudiantes = new Button
            {
                Text = "Reporte de Estudiantes",
                Location = new System.Drawing.Point(20, 70),
                Width = 250,
                Height = 50,
                BackColor = System.Drawing.Color.FromArgb(41, 128, 185),
                ForeColor = System.Drawing.Color.White,
                FlatStyle = FlatStyle.Flat
            };
            
            var btnReporteAsistencia = new Button
            {
                Text = "Reporte de Asistencia",
                Location = new System.Drawing.Point(290, 70),
                Width = 250,
                Height = 50,
                BackColor = System.Drawing.Color.FromArgb(52, 152, 219),
                ForeColor = System.Drawing.Color.White,
                FlatStyle = FlatStyle.Flat
            };
            
            var btnReporteNotas = new Button
            {
                Text = "Reporte de Notas",
                Location = new System.Drawing.Point(560, 70),
                Width = 250,
                Height = 50,
                BackColor = System.Drawing.Color.FromArgb(155, 89, 182),
                ForeColor = System.Drawing.Color.White,
                FlatStyle = FlatStyle.Flat
            };
            
            var txtReporte = new TextBox
            {
                Location = new System.Drawing.Point(20, 140),
                Size = new System.Drawing.Size(900, 420),
                Multiline = true,
                ScrollBars = ScrollBars.Vertical,
                Font = new System.Drawing.Font("Courier New", 9F),
                ReadOnly = true
            };
            
            btnReporteEstudiantes.Click += (sender, e) =>
            {
                txtReporte.Text = GenerarReporteEstudiantes();
            };
            
            btnReporteAsistencia.Click += (sender, e) =>
            {
                txtReporte.Text = GenerarReporteAsistencia();
            };
            
            btnReporteNotas.Click += (sender, e) =>
            {
                txtReporte.Text = GenerarReporteNotas();
            };
            
            panel.Controls.AddRange(new Control[]
            {
                lblTitulo, btnReporteEstudiantes, btnReporteAsistencia, 
                btnReporteNotas, txtReporte
            });
            
            tab.Controls.Add(panel);
        }

        private string GenerarReporteEstudiantes()
        {
            var reporte = "═══════════════════════════════════════════════════════\n";
            reporte += "       REPORTE DE ESTUDIANTES - INSTITUTO CÓDICE\n";
            reporte += $"       Fecha: {DateTime.Now:dd/MM/yyyy HH:mm}\n";
            reporte += "═══════════════════════════════════════════════════════\n\n";
            reporte += $"Total de Estudiantes: {estudiantes.Count}\n\n";
            
            foreach (var estudiante in estudiantes)
            {
                reporte += $"RUT: {estudiante.Rut}\n";
                reporte += $"Nombre: {estudiante.Nombre} {estudiante.Apellido}\n";
                reporte += $"Matrícula: {estudiante.NumeroMatricula}\n";
                reporte += $"Estado: {estudiante.Estado}\n";
                reporte += $"Asignaturas matriculadas: {estudiante.Asignaturas.Count}\n";
                reporte += $"Asistencia: {estudiante.ObtenerPorcentajeAsistencia():F1}%\n";
                reporte += $"Promedio General: {estudiante.CalcularPromedioGeneral():F2}\n";
                reporte += "───────────────────────────────────────────────────────\n\n";
            }
            
            return reporte;
        }

        private string GenerarReporteAsistencia()
        {
            var reporte = "═══════════════════════════════════════════════════════\n";
            reporte += "       REPORTE DE ASISTENCIA - INSTITUTO CÓDICE\n";
            reporte += $"       Fecha: {DateTime.Now:dd/MM/yyyy HH:mm}\n";
            reporte += "═══════════════════════════════════════════════════════\n\n";
            
            var totalAsistencias = estudiantes.SelectMany(e => e.Asistencias).Count();
            reporte += $"Total de registros: {totalAsistencias}\n\n";
            
            foreach (var asignatura in asignaturas.Where(a => a.Asistencias.Any()))
            {
                reporte += $"ASIGNATURA: {asignatura.Nombre} ({asignatura.Codigo})\n";
                reporte += $"Asistencia promedio: {asignatura.ObtenerAsistenciaPromedio():F1}%\n\n";
                
                foreach (var asist in asignatura.Asistencias.OrderByDescending(a => a.Fecha))
                {
                    reporte += $"  • {asist.Fecha:dd/MM/yyyy} - ";
                    reporte += $"{asist.Estudiante.Nombre} {asist.Estudiante.Apellido}: ";
                    reporte += $"{asist.Estado}\n";
                }
                reporte += "\n";
            }
            
            return reporte;
        }

        private string GenerarReporteNotas()
        {
            var reporte = "═══════════════════════════════════════════════════════\n";
            reporte += "          REPORTE DE NOTAS - INSTITUTO CÓDICE\n";
            reporte += $"          Fecha: {DateTime.Now:dd/MM/yyyy HH:mm}\n";
            reporte += "═══════════════════════════════════════════════════════\n\n";
            
            var totalNotas = estudiantes.SelectMany(e => e.Notas).Count();
            reporte += $"Total de evaluaciones: {totalNotas}\n\n";
            
            foreach (var estudiante in estudiantes.Where(e => e.Notas.Any()))
            {
                reporte += $"ESTUDIANTE: {estudiante.Nombre} {estudiante.Apellido}\n";
                reporte += $"RUT: {estudiante.Rut}\n";
                reporte += $"Promedio General: {estudiante.CalcularPromedioGeneral():F2}\n\n";
                
                var notasPorAsignatura = estudiante.Notas.GroupBy(n => n.Asignatura);
                
                foreach (var grupo in notasPorAsignatura)
                {
                    reporte += $"  {grupo.Key.Nombre}:\n";
                    foreach (var nota in grupo.OrderByDescending(n => n.Fecha))
                    {
                        reporte += $"    • {nota.TipoEvaluacion}: {nota.Valor:F1} ";
                        reporte += $"({(nota.EstaAprobado() ? "Aprobado" : "Reprobado")}) ";
                        reporte += $"- {nota.Fecha:dd/MM/yyyy}\n";
                    }
                    double promedio = grupo.Average(n => n.Valor);
                    reporte += $"    Promedio: {promedio:F2}\n\n";
                }
                reporte += "───────────────────────────────────────────────────────\n\n";
            }
            
            return reporte;
        }
    }

    // Clase Program para ejecutar la aplicación
    static class Program
    {
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new FormPrincipal());
        }
    }
}
