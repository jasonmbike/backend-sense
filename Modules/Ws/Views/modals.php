<div class="modal fade bs-example-modal-lg" id="modal-cacv" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" ></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <form id="formulario-cacv" autocomplete="off">
                  <input type="hidden" id="id" name="id">
                  <input type="hidden" id="idcliente" name="idcliente">
                  <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6" id="div-dinamico">
                        <div class="form-group">
                            <label id="label-dinamico">dinamico </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="form-tipo-dinamico" name="form-tipo-dinamico" data-tipo="tipodinamico" placeholder="Ingrese 'dinamico' " value="" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6" id="div-codigo">
                        <div class="form-group">
                            <label>Código</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="form-tipo-codigo" name="form-tipo-codigo" data-tipo="tipocodigo" placeholder="Ingrese código" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="form-group" style="margin-top: 1.8rem;">
                            <label>Cliente</label>
                            <select class="form-control filtroclientemodal tipo selectpicker" id="form-filtro-cliente-modal"  data-size="20" name="tipoclientemodal" data-max-options="false"  data-live-search="true" title="Seleccione cliente">
                            </select>
                        </div>
                    </div>
                    
                
                    
                    
                    
                    

                </div>
                  
              <div class="modal-footer modal-footer-full">
                  <button type="submit" class="btn btn-block btn-primary modal-submit ">ENVIAR</button>
              </div>

              
              </form>
              
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>