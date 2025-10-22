using System;
using System.Collections.Generic;
using System.Linq;

namespace InstitutoCodice.Core
{
    // ====================
    // ENUMERACIONES
    // ====================
    public enum EstadoEstudiante
    {
        Activo,
        Inactivo,
        Retirado,
        Egresado
    }

    public enum EstadoAsistencia
    {
        Presente,
        Ausente,
        Justificado,
        Tardanza
    }

    public enum TipoEvaluacion
    {
        Prueba,
        Tarea,
        Proyecto,
        Examen,
        Trabajo,
        Presentacion
    }

    // ====================
    // CLASE BASE
    // ====================
    public abstract class Persona
    {
        public string Rut { get; set; }
        public string Nombre { get; set; }
        public string Apellido { get; set; }
        public DateTime FechaNacimiento { get; set; }
        public string Email { get; set; }
        public string Telefono { get; set; }

        protected Persona(string rut, string nombre, string apellido)
        {
            if (string.IsNullOrWhiteSpace(rut))
                throw new ArgumentException("El RUT no puede estar vacío");
            if (string.IsNullOrWhiteSpace(nombre))
                throw new ArgumentException("El nombre no puede estar vacío");
            if (string.IsNullOrWhiteSpace(apellido))
                throw new ArgumentException("El apellido no puede estar vacío");

            Rut = rut;
            Nombre = nombre;
            Apellido = apellido;
        }

        public int ObtenerEdad()
        {
            var today = DateTime.Today;
            var age = today.Year - FechaNacimiento.Year;
            if (FechaNacimiento.Date > today.AddYears(-age)) age--;
            return age;
        }

        public bool ValidarRut()
        {
            // Validación simplificada de RUT chileno
            if (string.IsNullOrWhiteSpace(Rut)) return false;
            
            var rut = Rut.Replace(".", "").Replace("-", "").ToUpper();
            if (rut.Length < 2) return false;

            return true; // Validación básica
        }

        public override string ToString()
        {
            return $"{Nombre} {Apellido} ({Rut})";
        }
    }

    // ====================
    // ESTUDIANTE
    // ====================
    public class Estudiante : Persona
    {
        public string NumeroMatricula { get; set; }
        public DateTime FechaIngreso { get; set; }
        public EstadoEstudiante Estado { get; set; }
        public List<Asignatura> Asignaturas { get; set; }
        public List<Asistencia> Asistencias { get; set; }
        public List<Nota> Notas { get; set; }

        public Estudiante(string rut, string nombre, string apellido) 
            : base(rut, nombre, apellido)
        {
            NumeroMatricula = GenerarNumeroMatricula();
            FechaIngreso = DateTime.Now;
            Estado = EstadoEstudiante.Activo;
            Asignaturas = new List<Asignatura>();
            Asistencias = new List<Asistencia>();
            Notas = new List<Nota>();
        }

        private string GenerarNumeroMatricula()
        {
            return $"EST{DateTime.Now.Year}{new Random().Next(1000, 9999)}";
        }

        public void Matricular(Asignatura asignatura)
        {
            if (asignatura == null)
                throw new ArgumentNullException(nameof(asignatura));

            if (!Asignaturas.Contains(asignatura))
            {
                Asignaturas.Add(asignatura);
                asignatura.AgregarEstudiante(this);
            }
        }

        public double CalcularPromedioGeneral()
        {
            if (Notas.Count == 0) return 0.0;

            var notasPorAsignatura = Notas
                .GroupBy(n => n.Asignatura)
                .Select(g => g.Average(n => n.Valor * n.Ponderacion));

            return notasPorAsignatura.Any() ? notasPorAsignatura.Average() : 0.0;
        }

        public double ObtenerPorcentajeAsistencia()
        {
            if (Asistencias.Count == 0) return 0.0;

            var presentes = Asistencias.Count(a => 
                a.Estado == EstadoAsistencia.Presente || 
                a.Estado == EstadoAsistencia.Justificado);

            return (presentes / (double)Asistencias.Count) * 100;
        }

        public bool EstaActivo()
        {
            return Estado == EstadoEstudiante.Activo;
        }
    }

    // ====================
    // DOCENTE
    // ====================
    public class Docente : Persona
    {
        public string Especialidad { get; set; }
        public DateTime FechaContratacion { get; set; }
        public List<Asignatura> AsignaturasAsignadas { get; set; }

        public Docente(string rut, string nombre, string apellido) 
            : base(rut, nombre, apellido)
        {
            FechaContratacion = DateTime.Now;
            AsignaturasAsignadas = new List<Asignatura>();
        }

        public void AsignarAsignatura(Asignatura asignatura)
        {
            if (asignatura == null)
                throw new ArgumentNullException(nameof(asignatura));

            if (!AsignaturasAsignadas.Contains(asignatura))
            {
                AsignaturasAsignadas.Add(asignatura);
                asignatura.DocenteAsignado = this;
            }
        }

        public int ObtenerCargaAcademica()
        {
            return AsignaturasAsignadas.Sum(a => a.Creditos);
        }
    }

    // ====================
    // ASIGNATURA
    // ====================
    public class Asignatura
    {
        public string Codigo { get; set; }
        public string Nombre { get; set; }
        public string Nivel { get; set; }
        public int Creditos { get; set; }
        public Docente DocenteAsignado { get; set; }
        public List<Estudiante> Estudiantes { get; set; }
        public List<Asistencia> Asistencias { get; set; }
        public List<Nota> Notas { get; set; }

        public Asignatura(string codigo, string nombre, string nivel, int creditos = 4)
        {
            if (string.IsNullOrWhiteSpace(codigo))
                throw new ArgumentException("El código no puede estar vacío");
            if (string.IsNullOrWhiteSpace(nombre))
                throw new ArgumentException("El nombre no puede estar vacío");

            Codigo = codigo;
            Nombre = nombre;
            Nivel = nivel;
            Creditos = creditos;
            Estudiantes = new List<Estudiante>();
            Asistencias = new List<Asistencia>();
            Notas = new List<Nota>();
        }

        public void AgregarEstudiante(Estudiante estudiante)
        {
            if (estudiante == null)
                throw new ArgumentNullException(nameof(estudiante));

            if (!Estudiantes.Contains(estudiante))
            {
                Estudiantes.Add(estudiante);
            }
        }

        public void EliminarEstudiante(Estudiante estudiante)
        {
            Estudiantes.Remove(estudiante);
        }

        public List<Estudiante> ObtenerEstudiantes()
        {
            return Estudiantes;
        }

        public double CalcularPromedioAsignatura()
        {
            if (Notas.Count == 0) return 0.0;
            return Notas.Average(n => n.Valor);
        }

        public double ObtenerAsistenciaPromedio()
        {
            if (Asistencias.Count == 0) return 0.0;

            var presentes = Asistencias.Count(a => 
                a.Estado == EstadoAsistencia.Presente);

            return (presentes / (double)Asistencias.Count) * 100;
        }
    }

    // ====================
    // ASISTENCIA
    // ====================
    public class Asistencia
    {
        public int Id { get; set; }
        public DateTime Fecha { get; set; }
        public EstadoAsistencia Estado { get; set; }
        public string Observacion { get; set; }
        public Estudiante Estudiante { get; set; }
        public Asignatura Asignatura { get; set; }

        private static int _nextId = 1;

        public Asistencia(Estudiante estudiante, Asignatura asignatura, DateTime fecha)
        {
            if (estudiante == null)
                throw new ArgumentNullException(nameof(estudiante));
            if (asignatura == null)
                throw new ArgumentNullException(nameof(asignatura));

            Id = _nextId++;
            Estudiante = estudiante;
            Asignatura = asignatura;
            Fecha = fecha;
            Estado = EstadoAsistencia.Ausente; // Por defecto ausente
        }

        public void MarcarPresente()
        {
            Estado = EstadoAsistencia.Presente;
        }

        public void MarcarAusente()
        {
            Estado = EstadoAsistencia.Ausente;
        }

        public void Justificar(string motivo)
        {
            Estado = EstadoAsistencia.Justificado;
            Observacion = motivo;
        }

        public EstadoAsistencia ObtenerEstado()
        {
            return Estado;
        }
    }

    // ====================
    // NOTA
    // ====================
    public class Nota
    {
        public int Id { get; set; }
        public double Valor { get; set; }
        public DateTime Fecha { get; set; }
        public TipoEvaluacion TipoEvaluacion { get; set; }
        public double Ponderacion { get; set; }
        public Estudiante Estudiante { get; set; }
        public Asignatura Asignatura { get; set; }

        private static int _nextId = 1;
        private const double NOTA_MINIMA = 1.0;
        private const double NOTA_MAXIMA = 7.0;
        private const double NOTA_APROBACION = 4.0;

        public Nota(Estudiante estudiante, Asignatura asignatura, double valor, 
                    TipoEvaluacion tipo = TipoEvaluacion.Prueba, double ponderacion = 1.0)
        {
            if (estudiante == null)
                throw new ArgumentNullException(nameof(estudiante));
            if (asignatura == null)
                throw new ArgumentNullException(nameof(asignatura));

            Id = _nextId++;
            Estudiante = estudiante;
            Asignatura = asignatura;
            Valor = valor;
            TipoEvaluacion = tipo;
            Ponderacion = ponderacion;
            Fecha = DateTime.Now;

            if (!ValidarNota())
                throw new ArgumentException($"La nota debe estar entre {NOTA_MINIMA} y {NOTA_MAXIMA}");
        }

        public bool ValidarNota()
        {
            return Valor >= NOTA_MINIMA && Valor <= NOTA_MAXIMA;
        }

        public bool EstaAprobado()
        {
            return Valor >= NOTA_APROBACION;
        }

        public double CalcularAporte()
        {
            return Valor * Ponderacion;
        }

        public override string ToString()
        {
            return $"{TipoEvaluacion}: {Valor:F1} ({(EstaAprobado() ? "Aprobado" : "Reprobado")})";
        }
    }
}
