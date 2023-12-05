<div class="modal fade bs-example-modal-lg" id="modal-cacv" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" ></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="formulario-cacv" autocomplete="off">
                  <input type="hidden" id="id" name="id">
                  <input type="hidden" id="idcliente" name="idcliente">
                  <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6" id="div-dinamico">
                        <div class="form-group">
                            <label id="label-nombre">Nombre</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="form-tipo-nombre" name="form-tipo-nombre" data-tipo="tiponombre"  value="" onkeyup="javascript:this.value=this.value.toUpperCase();" disabled>
                            </div>
                        </div>
                    </div>

                    

                    <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="form-group" >
                            <label>Estado</label>
                            <select class="form-select" id="form-filtro-estado-modal"   name="tipoestadomodal"  title="Seleccione estado">
                            </select>
                        </div>
                    </div>
                    
                
                    
                    
                    
                    

                </div>
                  
              <div class="modal-footer modal-footer-full" style="margin-top: 30px;">
                  <button type="submit" class="btn btn-block btn-primary modal-submit ">ENVIAR</button>
              </div>

              
              </form>
              
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>