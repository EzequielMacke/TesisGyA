<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-chart-bar"></i> Informes</h2>
                    <small>Seleccione el informe que desea generar</small>
                </div>
            </div>

            {{-- Listado de informes disponibles --}}
            <div class="informes-grid">
                <a href="{{ route('informes.presupuestos_compra.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Presupuestos de Compra</h3>
                        <p>Listado de presupuestos de compra filtrado por proveedor, estado y rango de fechas, con totales.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.cuentas_pagar.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Cuentas a Pagar</h3>
                        <p>Listado de cuentas a pagar filtrado por proveedor, estado y rango de fecha de vencimiento, con totales.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.compras.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Compras</h3>
                        <p>Listado de compras (facturas) filtrado por proveedor, estado y rango de fechas, con totales.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.libro_compras.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Libro de Compras</h3>
                        <p>Detalle de facturas y notas de crédito/débito con desglose de IVA, filtrado por proveedor, tipo de documento y fechas.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.pedidos_compra.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Pedidos de Compra</h3>
                        <p>Listado de pedidos de compra filtrado por depósito, estado y rango de fechas, con cantidad de ítems.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.inventario.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Inventario</h3>
                        <p>Stock actual de insumos filtrado por depósito y obra, con marca y unidad de medida.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.presupuestos_servicio.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Presupuestos de Servicio</h3>
                        <p>Listado de presupuestos de servicio filtrado por cliente, estado y rango de fechas, con totales.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.reclamos.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Reclamos</h3>
                        <p>Listado de reclamos filtrado por cliente, estado y rango de fechas, con observaciones.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.insumos_utilizados.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Insumos Utilizados</h3>
                        <p>Detalle de insumos consumidos en órdenes de servicio, filtrado por obra, estado y rango de fechas.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>

                <a href="{{ route('informes.orden_servicio.form') }}" class="informe-card">
                    <div class="informe-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="informe-info">
                        <h3>Órdenes de Servicio</h3>
                        <p>Listado de órdenes de servicio filtrado por cliente, estado y rango de fechas, con cantidad de ensayos.</p>
                    </div>
                    <i class="fas fa-chevron-right informe-arrow"></i>
                </a>
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

.informes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.informe-card {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    padding: 1rem 1.1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    text-decoration: none;
    color: inherit;
    transition: border-color .15s, box-shadow .15s;
}
.informe-card:hover {
    border-color: #93c5fd;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
}
.informe-icon {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.informe-info { flex: 1; min-width: 0; }
.informe-info h3 {
    margin: 0 0 0.2rem 0;
    font-size: 0.92rem;
    font-weight: 600;
    color: #1e293b;
}
.informe-info p {
    margin: 0;
    font-size: 0.78rem;
    color: #94a3b8;
    line-height: 1.3;
}
.informe-arrow { color: #cbd5e1; flex-shrink: 0; }
</style>
