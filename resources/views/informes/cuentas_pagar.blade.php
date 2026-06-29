<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Cuentas a Pagar</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-money-check-alt"></i> Informe de Cuentas a Pagar</h2>
                    <small>Defina los parámetros y genere el informe en PDF</small>
                </div>
                <a href="{{ route('informes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Informes
                </a>
            </div>

            {{-- Parámetros --}}
            <div class="card">
                <div class="card-body py-3 px-3">
                    <form method="GET" action="{{ route('informes.cuentas_pagar.pdf') }}" target="_blank">
                        <div class="toolbar-grid">
                            <div class="toolbar-item">
                                <label class="form-label">Proveedor</label>
                                <select name="proveedor_id" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Estado</label>
                                <select name="estado_id" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Fecha Vencimiento Desde</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_desde">
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Fecha Vencimiento Hasta</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_hasta">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-file-pdf me-1"></i>Generar PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')
</body>
</html>

<style>
.content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}
.page-header h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1e293b; }
.page-header h2 i { color: #94a3b8; margin-right: 0.4rem; }
.page-header small { color: #94a3b8; font-size: 0.8rem; }

.card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: none;
}

.toolbar-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0.65rem;
    align-items: end;
}
.toolbar-item .form-label {
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
}
.toolbar-actions button { width: 100%; }

@media (max-width: 900px) {
    .page-header { flex-direction: column; align-items: flex-start; }
}
</style>
