<x-laravel-ui-adminlte::adminlte-layout>

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                {{-- <a href="{{ url('/home') }}"><b>{{ config('app.name') }}</b></a> --}}
                <h1>¡Bienbenido!</h1>

                {{-- Imagen de avatar --}}
                <img src="{{ asset('storage\app\public\Orange Beige Modern Illustration Food Product Label.png') }}"
                    alt="Avatar" class="img-circle elevation-2" style="width: 100px; height: 100px;">
            </div>
            <!-- /.login-logo -->

            <!-- /.login-box-body -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Iniciar sesión</p>

                    <form method="post" action="{{ url('/login') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo"
                                class="form-control @error('email') is-invalid @enderror">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                            </div>
                            @error('email')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password" placeholder="Contraseña"
                                class="form-control @error('password') is-invalid @enderror">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">Recordar Usuario</label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 ">
                                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                            </div>
                        </div>
                    </form>
                    {{-- <div>
                        <p class="mb-1">
                            <a href="{{ route('password.request') }}">¿ Olvidaste tu Contraseña ? </a>
                        </p>
                    </div> --}}
                    {{-- <p class="mb-0">
                        <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
                    </p> --}}
                </div>
                <!-- /.login-card-body -->
            </div>

        </div>
        <!-- /.login-box -->
    </body>
</x-laravel-ui-adminlte::adminlte-layout>
