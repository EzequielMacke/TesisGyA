<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* Estilos globales para el contenido principal */
.main-content {
    margin-left: 60px;
    transition: margin-left 0.3s ease;
    min-height: 100vh;
    background-color: #f8f9fa;
    padding: 20px;
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        padding: 15px;
    }
}

@media (max-width: 576px) {
    .main-content {
        margin-left: 45px;
        padding: 10px;
    }
}

/* Estilos para las cards */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

/* Estilos para las tablas */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.table th {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

.table td {
    border: none;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
    font-size: 0.9rem;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Estilos para botones */
.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #000;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

/* Estilos para alerts */
.alert {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estilos para badges */
.badge {
    padding: 0.5em 0.8em;
    font-size: 0.8rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Títulos y texto */
h1, h2, h3, h4, h5, h6 {
    color: #2c3e50;
    font-weight: 600;
}

/* Formularios */
.form-control {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-1px);
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

/* Estilos para el footer */
.footer {
    margin-left: 60px;
    transition: margin-left 0.3s ease;
    background-color: #343a40;
    color: white;
    padding: 1rem 0;
    margin-top: auto;
}

@media (max-width: 768px) {
    .footer {
        margin-left: 50px;
    }
}

/* Scrollbar personalizado para contenido */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Animaciones generales */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Espaciado consistente */
.container-fluid {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

/* Headers de sección */
.section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
