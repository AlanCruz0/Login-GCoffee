<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Correo Electrónico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Verificación de Correo Electrónico</h1>

                <p>Por favor, ingresa el código que hemos enviado a tu correo electrónico para verificar tu cuenta.</p>

                <form method="POST" action="{{ route('verification.verify') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="verification_code" class="form-label">Código de Verificación</label>
                        <input type="text" name="verification_code" id="verification_code" class="form-control" required>
                        @error('verification_code') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Verificar Código</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <p>Si no recibiste el correo, <a href="{{ route('verification.resend') }}">haz clic aquí para reenviar el correo</a>.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
