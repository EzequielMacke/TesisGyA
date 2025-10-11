<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Presupuesto de Servicio</title>
    @include('partials.head')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        .main-content {
            margin-left: 60px;
            min-height: 100vh;
            background-color: #f4f6f9;
            transition: margin-left 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            box-sizing: border-box;
            width: auto;
            max-width: 100vw;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 50px;
                padding: 10px;
            }
        }
        .sidebar-nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 60px;
            transition: width 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            z-index: 10000;
        }
        .sidebar-nav:hover {
            width: 280px;
            box-shadow: 2px 0 16px rgba(0,0,0,0.07);
        }
        .sidebar-nav:hover ~ .main-content {
            margin-left: 280px;
        }
        @media (max-width: 768px) {
            .sidebar-nav:hover {
                width: 250px;
            }
            .sidebar-nav:hover ~ .main-content {
                margin-left: 250px;
            }
        }
        .content-wrapper {
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            overflow-x: auto;
        }
        .page-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .section-card {
            margin-bottom: 30px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .section-header {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .section-body {
            padding: 25px;
        }
        .file-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .file-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            transition: transform 0.2s;
        }
        .file-item:hover {
            transform: scale(1.02);
        }
        .file-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .file-item .file-info {
            padding: 10px;
            font-size: 0.9rem;
        }
        .file-item .file-info a {
            color: #007bff;
            text-decoration: none;
        }
        .file-item .file-info a:hover {
            text-decoration: underline;
        }
        .precios-table, .resumen-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .precios-table th, .precios-table td, .resumen-table th, .resumen-table td {
            border: 1px solid #e9ecef;
            padding: 12px;
        }
        .precios-table th, .resumen-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .precios-table .ensayo-name {
            text-align: left;
        }
        .resumen-table .concepto {
            text-align: left;
        }
        .resumen-table .monto {
            text-align: right;
        }
        .resumen-table .total-row {
            background: #e3f2fd;
            font-weight: bold;
            color: #1976d2;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="text-primary mb-0"><i class="fas fa-eye me-3"></i>Ver Presupuesto de Servicio</h1>
                    <a href="{{ route('presupuesto_servicio.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <!-- Sección 1: Información del Cliente -->
            <div class="section-card">
                <h5 class="section-header"><i class="fas fa-user me-2"></i>Información del Cliente</h5>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Razón Social</label>
                            <p class="form-control-plaintext">{{ $presupuesto->cliente->razon_social }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RUC</label>
                            <p class="form-control-plaintext">{{ $presupuesto->cliente->ruc }}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <p class="form-control-plaintext">{{ $presupuesto->cliente->direccion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Información de la Obra -->
            <div class="section-card">
                <h5 class="section-header"><i class="fas fa-building me-2"></i>Información de la Obra</h5>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Descripción</label>
                            <p class="form-control-plaintext">{{ $presupuesto->obra->descripcion }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ubicación</label>
                            <p class="form-control-plaintext">{{ $presupuesto->obra->ubicacion }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Metros Cuadrados</label>
                            <p class="form-control-plaintext">{{ $presupuesto->obra->metros_cuadrados }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Información de la Visita Previa -->
            <div class="section-card">
                <h5 class="section-header"><i class="fas fa-search me-2"></i>Información de la Visita Previa</h5>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Visita</label>
                            <p class="form-control-plaintext">{{ $presupuesto->visitaPrevia->fecha_visita }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <p class="form-control-plaintext">{{ $presupuesto->visitaPrevia->estado->descripcion }}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Observación</label>
                            <p class="form-control-plaintext">{{ $presupuesto->visitaPrevia->observacion }}</p>
                        </div>
                    </div>
                    <div class="row g-4 mt-3">
                        <div class="col-lg-6">
                            <h6 class="mb-3"><i class="fas fa-camera me-2"></i>Fotos de la Visita</h6>
                            <div class="file-gallery" id="fotos-visita">
                                @if($presupuesto->visitaPrevia->fotos && $presupuesto->visitaPrevia->fotos->count() > 0)
                                    @foreach($presupuesto->visitaPrevia->fotos as $foto)
                                        <div class="file-item">
                                            <img src="/storage/{{ $foto->ruta_foto }}" alt="Foto">
                                            <div class="file-info">
                                                <a href="/storage/{{ $foto->ruta_foto }}" target="_blank">Ver imagen</a>
                                                <br><small>{{ $foto->fecha }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">No hay fotos disponibles.</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="mb-3"><i class="fas fa-file-alt me-2"></i>Planos de la Obra</h6>
                            <div class="file-gallery" id="planos-visita">
                                @if($presupuesto->visitaPrevia->planos && $presupuesto->visitaPrevia->planos->count() > 0)
                                    @foreach($presupuesto->visitaPrevia->planos as $plano)
                                        <div class="file-item">
                                            @if(strtolower(pathinfo($plano->ruta_plano, PATHINFO_EXTENSION)) === 'pdf')
                                                <div style="height: 150px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                </div>
                                            @else
                                                <img src="/storage/{{ $plano->ruta_plano }}" alt="Plano">
                                            @endif
                                            <div class="file-info">
                                                <a href="/storage/{{ $plano->ruta_plano }}" target="_blank">Ver archivo</a>
                                                <br><small>{{ $plano->fecha }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">No hay planos disponibles.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Detalles del Presupuesto -->
            <div class="section-card">
                <h5 class="section-header"><i class="fas fa-list me-2"></i>Detalles del Presupuesto</h5>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Número</label>
                            <p class="form-control-plaintext">{{ $presupuesto->numero_presupuesto }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha</label>
                            <p class="form-control-plaintext">{{ $presupuesto->fecha }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Validez (días)</label>
                            <p class="form-control-plaintext">{{ $presupuesto->validez }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Anticipo (%)</label>
                            <p class="form-control-plaintext">{{ $presupuesto->anticipo }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Monto</label>
                            <p class="form-control-plaintext">Gs {{ number_format($presupuesto->monto, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Usuario</label>
                            <p class="form-control-plaintext">{{ $presupuesto->usuario->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Observación</label>
                            <p class="form-control-plaintext">{{ $presupuesto->observacion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 5: Detalles de Ensayos -->
            <div class="section-card">
                <h5 class="section-header"><i class="fas fa-flask me-2"></i>Detalles de Ensayos</h5>
                <div class="section-body">
                    @php
                        $detallesPorServicio = $presupuesto->detalles->groupBy(function($detalle) {
                            return $detalle->ensayo->servicio->descripcion;
                        });
                    @endphp
                    @foreach($detallesPorServicio as $servicio => $detalles)
                        <h6 class="text-primary mb-3">{{ $servicio }}</h6>
                        <table class="precios-table">
                            <thead>
                                <tr>
                                    <th>Ensayo</th>
                                    <th>Precio Unitario</th>
                                    <th>Cantidad</th>
                                    <th>Impuesto</th>
                                    <th>IVA Monto</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detalles as $detalle)
                                    @php
                                        $subtotal = round($detalle->precio_unitario * $detalle->cantidad);
                                        $ivaMonto = 0;
                                        if ($detalle->impuesto_id == 2) {
                                            $ivaMonto = round($subtotal / 11);
                                        } elseif ($detalle->impuesto_id == 3) {
                                            $ivaMonto = round($subtotal / 21);
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ensayo-name">{{ $detalle->ensayo->descripcion }}</td>
                                        <td>Gs {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>{{ $detalle->impuesto->descripcion }} ({{ ($detalle->impuesto->calculo * 100) }}%)</td>
                                        <td>Gs {{ number_format($ivaMonto, 0, ',', '.') }}</td>
                                        <td>Gs {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>

            <!-- Sección 6: Resumen de Precios -->
            <div class="section-card">
                <h5 class="section-header"><i class="fas fa-chart-line me-2"></i>Resumen de Precios</h5>
                <div class="section-body">
                    <table class="resumen-table">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalEnsayos = 0;
                                $totalImpuestos = 0;
                                $impuestosPorTipo = [];
                                foreach ($presupuesto->detalles as $detalle) {
                                    $subtotal = round($detalle->precio_unitario * $detalle->cantidad);
                                    $ivaMonto = 0;
                                    if ($detalle->impuesto_id == 2) {
                                        $ivaMonto = round($subtotal / 11);
                                    } elseif ($detalle->impuesto_id == 3) {
                                        $ivaMonto = round($subtotal / 21);
                                    }
                                    $totalEnsayos += $subtotal;
                                    $totalImpuestos += $ivaMonto;
                                    $tipo = $detalle->impuesto->descripcion;
                                    if (!isset($impuestosPorTipo[$tipo])) {
                                        $impuestosPorTipo[$tipo] = 0;
                                    }
                                    $impuestosPorTipo[$tipo] += $ivaMonto;
                                }
                                $totalGeneral = $totalEnsayos + $totalImpuestos;
                                $montoAnticipo = round($totalGeneral * $presupuesto->anticipo / 100);
                            @endphp
                            @foreach($detallesPorServicio as $servicio => $detalles)
                                @php
                                    $subtotalServicio = 0;
                                    foreach ($detalles as $detalle) {
                                        $subtotalServicio += round($detalle->precio_unitario * $detalle->cantidad);
                                    }
                                @endphp
                                <tr>
                                    <td class="concepto">{{ $servicio }}</td>
                                    <td class="monto">Gs {{ number_format($subtotalServicio, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td class="concepto"><strong>Total Servicios</strong></td>
                                <td class="monto">Gs {{ number_format($totalEnsayos, 0, ',', '.') }}</td>
                            </tr>
                            @foreach($impuestosPorTipo as $tipo => $monto)
                                <tr>
                                    <td class="concepto">{{ $tipo }}</td>
                                    <td class="monto">Gs {{ number_format($monto, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td class="concepto"><strong>Monto</strong></td>
                                <td class="monto">Gs {{ number_format($totalGeneral, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="concepto">Anticipo</td>
                                <td class="monto">Gs {{ number_format($montoAnticipo, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
