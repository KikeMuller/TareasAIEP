using System;
using Xunit;
using InstitutoCodice.Core;

namespace InstitutoCodice.Tests
{
    /// <summary>
    /// Pruebas unitarias para el sistema de gestión del Instituto Códice
    /// Framework: xUnit
    /// </summary>
    public class SistemaGestionTests
    {
        // ====================
        // PRUEBA 1: Agregar Estudiante y Validaciones
        // ====================
        [Fact]
        public void AgregarEstudiante_DebeCrearEstudianteConDatosValidos()
        {
            // Arrange (Preparar)
            string rut = "12345678-9";
            string nombre = "Juan";
            string apellido = "Pérez";

            // Act (Actuar)
            var estudiante = new Estudiante(rut, nombre, apellido);

            // Assert (Verificar)
            Assert.NotNull(estudiante);
            Assert.Equal(rut, estudiante.Rut);
            Assert.Equal(nombre, estudiante.Nombre);
            Assert.Equal(apellido, estudiante.Apellido);
            Assert.Equal(EstadoEstudiante.Activo, estudiante.Estado);
            Assert.NotNull(estudiante.NumeroMatricula);
            Assert.True(estudiante.EstaActivo());
        }

        [Fact]
        public void AgregarEstudiante_DebeLanzarExcepcionConRutVacio()
        {
            // Arrange
            string rutVacio = "";
            string nombre = "Juan";
            string apellido = "Pérez";

            // Act & Assert
            Assert.Throws<ArgumentException>(() => 
                new Estudiante(rutVacio, nombre, apellido));
        }

        [Fact]
        public void AgregarEstudiante_DebeLanzarExcepcionConNombreVacio()
        {
            // Arrange
            string rut = "12345678-9";
            string nombreVacio = "";
            string apellido = "Pérez";

            // Act & Assert
            Assert.Throws<ArgumentException>(() => 
                new Estudiante(rut, nombreVacio, apellido));
        }

        // ====================
        // PRUEBA 2: Registrar Asistencia
        // ====================
        [Fact]
        public void RegistrarAsistencia_DebeMarcarEstudianteComoPresente()
        {
            // Arrange
            var estudiante = new Estudiante("12345678-9", "María", "González");
            var asignatura = new Asignatura("MAT101", "Matemáticas I", "1° Medio");
            var fecha = new DateTime(2025, 10, 21);

            // Act
            var asistencia = new Asistencia(estudiante, asignatura, fecha);
            asistencia.MarcarPresente();

            // Assert
            Assert.Equal(EstadoAsistencia.Presente, asistencia.Estado);
            Assert.Equal(EstadoAsistencia.Presente, asistencia.ObtenerEstado());
            Assert.Equal(estudiante, asistencia.Estudiante);
            Assert.Equal(asignatura, asistencia.Asignatura);
            Assert.Equal(fecha, asistencia.Fecha);
        }

        [Fact]
        public void RegistrarAsistencia_DebePermitirJustificacionConObservacion()
        {
            // Arrange
            var estudiante = new Estudiante("98765432-1", "Carlos", "Rodríguez");
            var asignatura = new Asignatura("FIS201", "Física II", "2° Medio");
            var asistencia = new Asistencia(estudiante, asignatura, DateTime.Now);
            string motivo = "Certificado médico";

            // Act
            asistencia.Justificar(motivo);

            // Assert
            Assert.Equal(EstadoAsistencia.Justificado, asistencia.Estado);
            Assert.Equal(motivo, asistencia.Observacion);
        }

        [Fact]
        public void CalcularPorcentajeAsistencia_DebeRetornarPorcentajeCorrecto()
        {
            // Arrange
            var estudiante = new Estudiante("11111111-1", "Ana", "Silva");
            var asignatura = new Asignatura("HIS301", "Historia", "3° Medio");

            // Crear 10 asistencias: 7 presentes, 2 ausentes, 1 justificado
            for (int i = 0; i < 7; i++)
            {
                var asist = new Asistencia(estudiante, asignatura, DateTime.Now.AddDays(-i));
                asist.MarcarPresente();
                estudiante.Asistencias.Add(asist);
            }
            for (int i = 0; i < 2; i++)
            {
                var asist = new Asistencia(estudiante, asignatura, DateTime.Now.AddDays(-i - 7));
                asist.MarcarAusente();
                estudiante.Asistencias.Add(asist);
            }
            var asistJustificada = new Asistencia(estudiante, asignatura, DateTime.Now.AddDays(-9));
            asistJustificada.Justificar("Médico");
            estudiante.Asistencias.Add(asistJustificada);

            // Act
            double porcentaje = estudiante.ObtenerPorcentajeAsistencia();

            // Assert
            // 7 presentes + 1 justificado = 8 de 10 = 80%
            Assert.Equal(80.0, porcentaje);
        }

        // ====================
        // PRUEBA 3: Calcular Promedio de Notas
        // ====================
        [Fact]
        public void CalcularPromedio_DebeRetornarPromedioCorrectoDeTresNotas()
        {
            // Arrange
            var estudiante = new Estudiante("22222222-2", "Pedro", "Morales");
            var asignatura = new Asignatura("QUI401", "Química", "4° Medio");

            // Act - Agregar 3 notas
            var nota1 = new Nota(estudiante, asignatura, 6.5, TipoEvaluacion.Prueba, 1.0);
            var nota2 = new Nota(estudiante, asignatura, 5.8, TipoEvaluacion.Tarea, 1.0);
            var nota3 = new Nota(estudiante, asignatura, 6.2, TipoEvaluacion.Proyecto, 1.0);
            
            estudiante.Notas.Add(nota1);
            estudiante.Notas.Add(nota2);
            estudiante.Notas.Add(nota3);
            asignatura.Notas.Add(nota1);
            asignatura.Notas.Add(nota2);
            asignatura.Notas.Add(nota3);

            double promedioEstudiante = estudiante.CalcularPromedioGeneral();
            double promedioAsignatura = asignatura.CalcularPromedioAsignatura();

            // Assert
            double promedioEsperado = (6.5 + 5.8 + 6.2) / 3.0;
            Assert.Equal(promedioEsperado, promedioAsignatura, 2); // 2 decimales de precisión
            Assert.Equal(promedioEsperado, promedioEstudiante, 2);
        }

        [Fact]
        public void ValidarNota_DebeValidarRangoDeNotasChilenas()
        {
            // Arrange
            var estudiante = new Estudiante("33333333-3", "Laura", "Fernández");
            var asignatura = new Asignatura("LEN101", "Lenguaje", "1° Medio");

            // Act & Assert - Nota válida
            var notaValida = new Nota(estudiante, asignatura, 5.5);
            Assert.True(notaValida.ValidarNota());

            // Nota en límite inferior
            var notaMinima = new Nota(estudiante, asignatura, 1.0);
            Assert.True(notaMinima.ValidarNota());

            // Nota en límite superior
            var notaMaxima = new Nota(estudiante, asignatura, 7.0);
            Assert.True(notaMaxima.ValidarNota());

            // Nota inválida - menor al rango
            Assert.Throws<ArgumentException>(() => 
                new Nota(estudiante, asignatura, 0.5));

            // Nota inválida - mayor al rango
            Assert.Throws<ArgumentException>(() => 
                new Nota(estudiante, asignatura, 7.5));
        }

        [Fact]
        public void EstaAprobado_DebeRetornarTrueSiNotaEsMayorOIgualA4()
        {
            // Arrange
            var estudiante = new Estudiante("44444444-4", "Diego", "Castro");
            var asignatura = new Asignatura("BIO201", "Biología", "2° Medio");

            // Act
            var notaAprobada = new Nota(estudiante, asignatura, 4.0);
            var notaAprobadaAlta = new Nota(estudiante, asignatura, 6.5);
            var notaReprobada = new Nota(estudiante, asignatura, 3.9);

            // Assert
            Assert.True(notaAprobada.EstaAprobado());
            Assert.True(notaAprobadaAlta.EstaAprobado());
            Assert.False(notaReprobada.EstaAprobado());
        }

        // ====================
        // PRUEBAS ADICIONALES
        // ====================
        [Fact]
        public void MatricularEstudiante_DebeAgregarEstudianteAAsignatura()
        {
            // Arrange
            var estudiante = new Estudiante("55555555-5", "Sofía", "Ramírez");
            var asignatura = new Asignatura("ING301", "Inglés", "3° Medio");

            // Act
            estudiante.Matricular(asignatura);

            // Assert
            Assert.Contains(asignatura, estudiante.Asignaturas);
            Assert.Contains(estudiante, asignatura.Estudiantes);
        }

        [Fact]
        public void AsignarDocente_DebeVincularDocenteConAsignatura()
        {
            // Arrange
            var docente = new Docente("66666666-6", "Roberto", "Vargas");
            docente.Especialidad = "Matemáticas";
            var asignatura = new Asignatura("MAT401", "Matemáticas IV", "4° Medio", 6);

            // Act
            docente.AsignarAsignatura(asignatura);

            // Assert
            Assert.Contains(asignatura, docente.AsignaturasAsignadas);
            Assert.Equal(docente, asignatura.DocenteAsignado);
            Assert.Equal(6, docente.ObtenerCargaAcademica());
        }

        [Fact]
        public void CalcularAporte_DebeRetornarValorPonderadoDeLaNota()
        {
            // Arrange
            var estudiante = new Estudiante("77777777-7", "Valentina", "Torres");
            var asignatura = new Asignatura("ART101", "Artes", "1° Medio");

            // Act
            var nota = new Nota(estudiante, asignatura, 6.0, TipoEvaluacion.Proyecto, 0.3);
            double aporte = nota.CalcularAporte();

            // Assert
            Assert.Equal(1.8, aporte, 2); // 6.0 * 0.3 = 1.8
        }

        [Fact]
        public void ObtenerEdad_DebeCalcularEdadCorrectamente()
        {
            // Arrange
            var estudiante = new Estudiante("88888888-8", "Matías", "Soto");
            estudiante.FechaNacimiento = new DateTime(2008, 5, 15);

            // Act
            int edad = estudiante.ObtenerEdad();

            // Assert
            // En octubre 2025, una persona nacida en mayo 2008 tiene 17 años
            Assert.Equal(17, edad);
        }

        [Fact]
        public void ToString_DebeRetornarFormatoEsperado()
        {
            // Arrange
            var estudiante = new Estudiante("99999999-9", "Isabella", "Núñez");

            // Act
            string resultado = estudiante.ToString();

            // Assert
            Assert.Equal("Isabella Núñez (99999999-9)", resultado);
        }
    }
}
