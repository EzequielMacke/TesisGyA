<!-- filepath: c:\laragon\www\TesisGyA\resources\views\solicitud_servicio\edit.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Solicitud de Servicio</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-alt"></i> Editar Solicitud de Servicio</h2>
                    <small>Modifique los datos de la solicitud #{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}</small>
                </div>
                <a href="{{ route('solicitud_servicio.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            {{-- Alerts --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('solicitud_servicio.update', $solicitud->id) }}" id="solicitudForm">
                @csrf
                @method('PUT')

                {{-- Datos de la Solicitud --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Datos de la Solicitud</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select name="cliente_id" id="cliente_id" class="form-select form-select-sm select2">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $solicitud->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="info-cliente" class="detail-box mt-2" style="display:none;"></div>
                            </div>
                            <div>
                                <label for="obra_id" class="form-label">Obra *</label>
                                <select name="obra_id" id="obra_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Seleccione una obra</option>
                                </select>
                                <div id="info-obra" class="detail-box mt-2" style="display:none;"></div>
                            </div>
                            <div>
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" value="{{ old('fecha', \Carbon\Carbon::parse($solicitud->fecha)->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación...">{{ old('observacion', $solicitud->observacion) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Servicios --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-tools me-2"></i>Servicios a Solicitar</span>
                        <span class="results-count">{{ $servicios->count() }} disponible(s)</span>
                    </div>
                    <div class="card-body">
                        <div class="servicios-grid">
                            @foreach($servicios as $servicio)
                                <label class="servicio-check" for="servicio_{{ $servicio->id }}">
                                    <input class="form-check-input" type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                        id="servicio_{{ $servicio->id }}"
                                        {{ in_array($servicio->id, old('servicios', $servicios_seleccionados)) ? 'checked' : '' }}>
                                    <span><i class="fas fa-cogs me-1 text-muted"></i>{{ $servicio->descripcion }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('solicitud_servicio.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>

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

/* ── Cabecera ── */
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

/* ── Formulario ── */
#solicitudForm {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* ── Cards ── */
.card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: none;
}
.card-header-section {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    font-weight: 600; font-size: 0.85rem; color: #1e293b;
}
.results-count { font-weight: 400; font-size: 0.78rem; color: #94a3b8; }

/* ── Datos de la solicitud ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.form-grid .form-label,
.card-body > .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    color: #374151;
}
.detail-row { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.25rem; }
.detail-row:last-child { margin-bottom: 0; }
.detail-row i { color: #94a3b8; width: 14px; text-align: center; }

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

@media (max-width: 900px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid { grid-template-columns: 1fr; }
}

/* ── Servicios ── */
.servicios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.6rem;
}
.servicio-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    margin-bottom: 0;
}
.servicio-check:hover { background: #eff6ff; border-color: #bfdbfe; }
.servicio-check.checked { background: #eff6ff; border-color: #2563eb; color: #1e293b; }
.servicio-check input { margin: 0; cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

    // Resaltar tarjetas de servicio seleccionadas
    document.querySelectorAll('.servicio-check').forEach(function (label) {
        const checkbox = label.querySelector('input[type="checkbox"]');
        const sync = () => label.classList.toggle('checked', checkbox.checked);
        sync();
        checkbox.addEventListener('change', sync);
    });

    var targetObraId = '{{ old('obra_id', $solicitud->obra_id) }}';
    var solicitudObraId = {{ $solicitud->obra_id }};
    var solicitudClienteId = {{ $solicitud->cliente_id }};
    var solicitudObraDescripcion = @json($solicitud->obra->descripcion ?? '');

    // Al seleccionar cliente, cargar obras activas y mostrar info del cliente
    $('#cliente_id').on('change', function() {
        var clienteId = $(this).val();
        $('#obra_id').prop('disabled', true).empty().append('<option value="">Seleccione una obra</option>');
        $('#info-cliente').hide().html('');
        $('#info-obra').hide().html('');
        if (clienteId) {
            $.getJSON('{{ url("api/obras") }}/' + clienteId, function(obras) {
                if (clienteId == solicitudClienteId && !obras.some(o => o.id == solicitudObraId)) {
                    obras.push({ id: solicitudObraId, descripcion: solicitudObraDescripcion });
                }
                if (obras.length > 0) {
                    $('#obra_id').append(
                        obras.map(o => `<option value="${o.id}">${o.descripcion}</option>`)
                    );
                    $('#obra_id').prop('disabled', false);
                    if (obras.some(o => o.id == targetObraId)) {
                        $('#obra_id').val(targetObraId).trigger('change');
                    }
                }
            });

            @php
                $clientesArr = [];
                foreach($clientes as $c) {
                    $clientesArr[$c->id] = [
                        'razon_social' => $c->razon_social,
                        'ruc' => $c->ruc,
                        'telefono' => $c->telefono,
                        'email' => $c->email
                    ];
                }
            @endphp
            var clientesData = @json($clientesArr);
            var data = clientesData[clienteId];
            if (data) {
                $('#info-cliente').html(
                    `<div class="detail-row"><i class="fas fa-id-card"></i><span><strong>RUC:</strong> ${data.ruc}</span></div>
                     <div class="detail-row"><i class="fas fa-phone"></i><span><strong>Teléfono:</strong> ${data.telefono ?? '-'}</span></div>
                     <div class="detail-row"><i class="fas fa-envelope"></i><span><strong>Email:</strong> ${data.email ?? '-'}</span></div>`
                ).show();
            }
        }
    });

    // Al seleccionar obra, mostrar info de la obra
    $('#obra_id').on('change', function() {
        var obraId = $(this).val();
        $('#info-obra').hide().html('');
        if (obraId) {
            $.getJSON('{{ url("api/obra") }}/' + obraId, function(data) {
                $('#info-obra').html(
                    `<div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Ubicación:</strong> ${data.ubicacion}</span></div>
                     <div class="detail-row"><i class="fas fa-sticky-note"></i><span><strong>Observación:</strong> ${data.observacion ? data.observacion : 'No tiene observación'}</span></div>`
                ).show();
            });
        }
    });

    // Cargar datos iniciales del cliente y la obra de la solicitud
    $('#cliente_id').val('{{ old('cliente_id', $solicitud->cliente_id) }}').trigger('change');
});
</script>
