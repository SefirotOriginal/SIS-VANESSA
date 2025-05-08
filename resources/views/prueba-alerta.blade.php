<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba SweetAlert</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">

    <button onclick="mostrarAlerta()" style="padding: 10px 20px; font-size: 16px;">
        Mostrar Alerta
    </button>

    <script>
        function mostrarAlerta() {
            Swal.fire({
                title: '¡Éxito!',
                text: 'SweetAlert está funcionando 🎉',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        }
    </script>
</body>
</html>
