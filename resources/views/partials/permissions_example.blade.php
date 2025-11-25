<!-- Ejemplo de uso de roles y permisos -->
@role('SuperUsuario')
    <div class="alert alert-info">Eres SuperUsuario — tienes todos los permisos.</div>
@endrole

@can('product.create')
    <a href="{{ route('products.create') }}" class="btn btn-primary">Crear producto</a>
@endcan

@can('product.index')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Ver productos</a>
@endcan
