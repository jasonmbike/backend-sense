<!-- Modal para editar título y descripción -->
<div class="modal fade" id="editarRegistroModal" tabindex="-1" aria-labelledby="editarRegistroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarRegistroModalLabel">Editar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Campos para mostrar y editar título y descripción -->
                <input type="hidden" id="registroId">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" class="form-control" id="titulo">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" id="descripcion"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEdicion()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
