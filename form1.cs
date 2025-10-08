using System;
using System.Text.RegularExpressions;
using System.Windows.Forms;

namespace SistemaRegistroEmpleados
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
            ConfigurarInterfaz();
        }

        private void ConfigurarInterfaz()
        {
            // Configuración del formulario principal
            this.Text = "Sistema de Registro de Empleados";
            this.Size = new System.Drawing.Size(500, 450);
            this.StartPosition = FormStartPosition.CenterScreen;
            this.FormBorderStyle = FormBorderStyle.FixedSingle;
            this.MaximizeBox = false;

            // GroupBox principal para organizar los controles
            GroupBox groupDatos = new GroupBox();
            groupDatos.Text = "Datos del Empleado";
            groupDatos.Location = new System.Drawing.Point(20, 20);
            groupDatos.Size = new System.Drawing.Size(440, 280);
            groupDatos.Font = new System.Drawing.Font("Segoe UI", 10F, System.Drawing.FontStyle.Bold);

            // Label y TextBox - Nombre Completo
            Label lblNombre = new Label();
            lblNombre.Text = "Nombre Completo:";
            lblNombre.Location = new System.Drawing.Point(20, 35);
            lblNombre.Size = new System.Drawing.Size(150, 25);
            lblNombre.Font = new System.Drawing.Font("Segoe UI", 9F);

            TextBox txtNombre = new TextBox();
            txtNombre.Name = "txtNombre";
            txtNombre.Location = new System.Drawing.Point(180, 33);
            txtNombre.Size = new System.Drawing.Size(240, 25);
            txtNombre.Font = new System.Drawing.Font("Segoe UI", 9F);

            // Label y TextBox - Edad
            Label lblEdad = new Label();
            lblEdad.Text = "Edad:";
            lblEdad.Location = new System.Drawing.Point(20, 75);
            lblEdad.Size = new System.Drawing.Size(150, 25);
            lblEdad.Font = new System.Drawing.Font("Segoe UI", 9F);

            TextBox txtEdad = new TextBox();
            txtEdad.Name = "txtEdad";
            txtEdad.Location = new System.Drawing.Point(180, 73);
            txtEdad.Size = new System.Drawing.Size(100, 25);
            txtEdad.Font = new System.Drawing.Font("Segoe UI", 9F);

            // Label y TextBox - Correo Electrónico
            Label lblCorreo = new Label();
            lblCorreo.Text = "Correo Electrónico:";
            lblCorreo.Location = new System.Drawing.Point(20, 115);
            lblCorreo.Size = new System.Drawing.Size(150, 25);
            lblCorreo.Font = new System.Drawing.Font("Segoe UI", 9F);

            TextBox txtCorreo = new TextBox();
            txtCorreo.Name = "txtCorreo";
            txtCorreo.Location = new System.Drawing.Point(180, 113);
            txtCorreo.Size = new System.Drawing.Size(240, 25);
            txtCorreo.Font = new System.Drawing.Font("Segoe UI", 9F);

            // Label y ComboBox - Cargo
            Label lblCargo = new Label();
            lblCargo.Text = "Cargo:";
            lblCargo.Location = new System.Drawing.Point(20, 155);
            lblCargo.Size = new System.Drawing.Size(150, 25);
            lblCargo.Font = new System.Drawing.Font("Segoe UI", 9F);

            ComboBox cmbCargo = new ComboBox();
            cmbCargo.Name = "cmbCargo";
            cmbCargo.Location = new System.Drawing.Point(180, 153);
            cmbCargo.Size = new System.Drawing.Size(240, 25);
            cmbCargo.DropDownStyle = ComboBoxStyle.DropDownList;
            cmbCargo.Font = new System.Drawing.Font("Segoe UI", 9F);

            // Agregar opciones al ComboBox
            cmbCargo.Items.AddRange(new object[] {
                "Seleccione un cargo...",
                "Gerente General",
                "Supervisor",
                "Analista",
                "Asistente Administrativo",
                "Desarrollador",
                "Contador",
                "Recursos Humanos",
                "Vendedor",
                "Operario"
            });
            cmbCargo.SelectedIndex = 0;

            // Label para mensajes de error/éxito
            Label lblMensaje = new Label();
            lblMensaje.Name = "lblMensaje";
            lblMensaje.Location = new System.Drawing.Point(20, 195);
            lblMensaje.Size = new System.Drawing.Size(400, 60);
            lblMensaje.Font = new System.Drawing.Font("Segoe UI", 9F, System.Drawing.FontStyle.Italic);
            lblMensaje.ForeColor = System.Drawing.Color.Red;
            lblMensaje.Text = "";

            // Botón Guardar
            Button btnGuardar = new Button();
            btnGuardar.Name = "btnGuardar";
            btnGuardar.Text = "Guardar";
            btnGuardar.Location = new System.Drawing.Point(150, 320);
            btnGuardar.Size = new System.Drawing.Size(120, 40);
            btnGuardar.Font = new System.Drawing.Font("Segoe UI", 10F, System.Drawing.FontStyle.Bold);
            btnGuardar.BackColor = System.Drawing.Color.FromArgb(0, 120, 215);
            btnGuardar.ForeColor = System.Drawing.Color.White;
            btnGuardar.FlatStyle = FlatStyle.Flat;
            btnGuardar.Cursor = Cursors.Hand;
            btnGuardar.Click += (sender, e) => BtnGuardar_Click(sender, e, txtNombre, txtEdad, txtCorreo, cmbCargo, lblMensaje);

            // Botón Limpiar
            Button btnLimpiar = new Button();
            btnLimpiar.Name = "btnLimpiar";
            btnLimpiar.Text = "Limpiar";
            btnLimpiar.Location = new System.Drawing.Point(280, 320);
            btnLimpiar.Size = new System.Drawing.Size(120, 40);
            btnLimpiar.Font = new System.Drawing.Font("Segoe UI", 10F, System.Drawing.FontStyle.Bold);
            btnLimpiar.BackColor = System.Drawing.Color.FromArgb(100, 100, 100);
            btnLimpiar.ForeColor = System.Drawing.Color.White;
            btnLimpiar.FlatStyle = FlatStyle.Flat;
            btnLimpiar.Cursor = Cursors.Hand;
            btnLimpiar.Click += (sender, e) => BtnLimpiar_Click(sender, e, txtNombre, txtEdad, txtCorreo, cmbCargo, lblMensaje);

            // Agregar controles al GroupBox
            groupDatos.Controls.Add(lblNombre);
            groupDatos.Controls.Add(txtNombre);
            groupDatos.Controls.Add(lblEdad);
            groupDatos.Controls.Add(txtEdad);
            groupDatos.Controls.Add(lblCorreo);
            groupDatos.Controls.Add(txtCorreo);
            groupDatos.Controls.Add(lblCargo);
            groupDatos.Controls.Add(cmbCargo);
            groupDatos.Controls.Add(lblMensaje);

            // Agregar controles al formulario
            this.Controls.Add(groupDatos);
            this.Controls.Add(btnGuardar);
            this.Controls.Add(btnLimpiar);
        }

        // Método para validar el nombre
        private bool ValidarNombre(string nombre, Label lblMensaje)
        {
            if (string.IsNullOrWhiteSpace(nombre))
            {
                lblMensaje.Text = "❌ Error: El campo Nombre no puede estar vacío.";
                lblMensaje.ForeColor = System.Drawing.Color.Red;
                return false;
            }
            return true;
        }

        // Método para validar la edad
        private bool ValidarEdad(string edad, Label lblMensaje)
        {
            if (!int.TryParse(edad, out int edadNumerica))
            {
                lblMensaje.Text = "❌ Error: La Edad debe ser un número válido.";
                lblMensaje.ForeColor = System.Drawing.Color.Red;
                return false;
            }

            if (edadNumerica < 18 || edadNumerica > 65)
            {
                lblMensaje.Text = "❌ Error: La Edad debe estar entre 18 y 65 años.";
                lblMensaje.ForeColor = System.Drawing.Color.Red;
                return false;
            }
            return true;
        }

        // Método para validar el correo electrónico
        private bool ValidarCorreo(string correo, Label lblMensaje)
        {
            if (string.IsNullOrWhiteSpace(correo))
            {
                lblMensaje.Text = "❌ Error: El campo Correo Electrónico no puede estar vacío.";
                lblMensaje.ForeColor = System.Drawing.Color.Red;
                return false;
            }

            // Expresión regular para validar formato de correo
            string patron = @"^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$";
            if (!Regex.IsMatch(correo, patron))
            {
                lblMensaje.Text = "❌ Error: El formato del correo es inválido. Ejemplo: usuario@empresa.com";
                lblMensaje.ForeColor = System.Drawing.Color.Red;
                return false;
            }
            return true;
        }

        // Método para validar el cargo
        private bool ValidarCargo(ComboBox cmbCargo, Label lblMensaje)
        {
            if (cmbCargo.SelectedIndex == 0)
            {
                lblMensaje.Text = "❌ Error: Debe seleccionar un cargo válido.";
                lblMensaje.ForeColor = System.Drawing.Color.Red;
                return false;
            }
            return true;
        }

        // Evento del botón Guardar
        private void BtnGuardar_Click(object sender, EventArgs e, TextBox txtNombre,
            TextBox txtEdad, TextBox txtCorreo, ComboBox cmbCargo, Label lblMensaje)
        {
            // Limpiar mensaje previo
            lblMensaje.Text = "";

            // Validar todos los campos
            if (!ValidarNombre(txtNombre.Text, lblMensaje)) return;
            if (!ValidarEdad(txtEdad.Text, lblMensaje)) return;
            if (!ValidarCorreo(txtCorreo.Text, lblMensaje)) return;
            if (!ValidarCargo(cmbCargo, lblMensaje)) return;

            // Si todas las validaciones son correctas
            lblMensaje.Text = "✅ Empleado registrado exitosamente.";
            lblMensaje.ForeColor = System.Drawing.Color.Green;

            // Aquí se podría agregar código para guardar en base de datos
            MessageBox.Show(
                $"Empleado registrado correctamente:\n\n" +
                $"Nombre: {txtNombre.Text}\n" +
                $"Edad: {txtEdad.Text} años\n" +
                $"Correo: {txtCorreo.Text}\n" +
                $"Cargo: {cmbCargo.SelectedItem}",
                "Registro Exitoso",
                MessageBoxButtons.OK,
                MessageBoxIcon.Information
            );
        }

        // Evento del botón Limpiar
        private void BtnLimpiar_Click(object sender, EventArgs e, TextBox txtNombre,
            TextBox txtEdad, TextBox txtCorreo, ComboBox cmbCargo, Label lblMensaje)
        {
            txtNombre.Clear();
            txtEdad.Clear();
            txtCorreo.Clear();
            cmbCargo.SelectedIndex = 0;
            lblMensaje.Text = "";
            txtNombre.Focus();
        }
    }
}
