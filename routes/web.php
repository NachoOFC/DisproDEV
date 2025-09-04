<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes

|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();
Route::get('/test-email', function () {
    Mail::raw('Este es un correo de prueba.', function ($message) {
        $message->to('addmen19@gmail.com')->subject('Correo de prueba');
    });
    return 'Correo de prueba enviado!';
});
Route::get('/clear-config-cache', function () {
    $output = Artisan::call('config:clear');
    return 'Configuración de caché limpiada.';
});
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->userable instanceof \App\CompassRole) {
            return redirect()->to('/alogis/');
        } else {
            return redirect()->to('/cliente/');
        }
    } else {
        return redirect()->route('login');
    }
});

Route::get('/home', function () {
    if (Auth::check()) {
        if (Auth::user()->userable instanceof \App\CompassRole) {
            return redirect()->to('/alogis/');
        } else {
            return redirect()->to('/cliente/');
        }
    } else {
        return redirect()->route('login');
    }
});

Route::group(['middleware' => 'auth'], function () {

    // Notificaciones
    Route::get('/notificaciones', 'ShowNotification')->name('notifications');
    Route::post('/notificaciones', 'SearchNotification')->name('SearchNotification');

    // Global CRUDS
    Route::resource('empresas', 'EmpresaController')->except([
        'show'
    ]);
    Route::resource('centros', 'CentroController')->except([
        'show'
    ]);
    Route::group(['prefix' => 'pedidos'], function () {
        Route::get('lista', 'EmpresaController@indexRequerimientos')->name('pedidos.indexEmpresa');
        Route::get('empresa/{empresa?}/{estado?}', 'CentroController@indexRequerimientos')->name('pedidos.indexCentro');
        Route::get('centro/{centro}/{year?}', 'RequerimientoController@centroIndex')->name('pedidos.centro');
        Route::get('lista/{centro}/estado/{estado?}', 'RequerimientoController@showCentro')->name('pedidos.centroIndex');
        Route::get('descargar/{requerimiento}', 'RequerimientoController@descargarGuia')->name('pedidos.descargar');
        Route::get('{requerimiento}', 'RequerimientoController@show')->name('pedidos.show');

        Route::group(['prefix' => 'liberia', 'middleware' => ['type:\App\Centro']], function () {
            Route::get('index', 'UserController@libreriaIndex')->name('libreria.index');
            Route::put('{requerimiento}', 'UserController@libreriaEdit')->name('libreria.editar');
        });

        Route::get(
            "pdf/{guiaDespacho}",
            "GuiaDespachoController@pdf"
        )->name("guiaDespacho.pdf");
    });

    Route::post('habilitar/centro/{centro}', 'CentroController@habilitar')->name('centros.habilitar');
    Route::get('estado/centro/{centro}', 'CentroController@habilitarForm')->name('centros.habilitar.get');

    Route::get(
        "observaciones/{requerimiento}",
        "RechazoController@show"
    )->name("rechazos.show");

    Route::post(
        "observaciones/{requerimiento}",
        "RechazoController@showExport"
    )->name("rechazos.export");

    // Rutas del Cliente
    Route::group(['prefix' => 'cliente', 'middleware' => ['cliente']], function () {

        Route::get('/', 'HomeController@index')->name('cliente.home');

        Route::group(['prefix' => 'pedidos'], function () {

            Route::group(['middleware' => ['type:\App\Centro']], function () {
                Route::middleware("create")->group(function () {
                    Route::get('crear', 'RequerimientoController@create')->name('requerimientos.create');
                    Route::get('formato', 'RequerimientoController@descargaFormato')->name('requerimientos.formato');
                    Route::post('store', 'RequerimientoController@store')->name('requerimientos.store');
                });

                Route::get(
                    'recepcion/{requerimiento}',
                    'RequerimientoController@recepcionView'
                )->name('pedidos.recepcion');

                Route::post(
                    'recepcion/{requerimiento}',
                    'RequerimientoController@recepcion'
                )->name('pedidos.recepcion.post');
            });



            Route::group(['middleware' => ['type:\App\Empresa']], function () {
                Route::middleware("validar")->group(function () {
                    Route::get('validar-pedidos', 'RequerimientoController@validarPedidos')->name('pedidos.validar');
                    Route::get('validar-nivel-2', 'RequerimientoController@validarNivel2')->name('pedidos.validarNivel2');
                    Route::post('aceptarNivel2', 'RequerimientoController@aceptarNivel2')->name('pedidos.aceptarNivel2');
                    Route::post('aceptar', 'RequerimientoController@aceptar')->name('pedidos.aceptar');
                    Route::post('rechazar', 'RequerimientoController@rechazar')->name('pedidos.rechazar');
                    Route::post('aceptar-todos', 'RequerimientoController@aceptarTodos')->name('pedidos.aceptarTodos');
                    Route::post('rechazar-todos', 'RequerimientoController@rechazarTodos')->name('pedidos.rechazarTodos');
                });

                Route::get('editar/{requerimiento}', 'RequerimientoController@edit')->name('requerimientos.edit');
                Route::put('actualizar/{requerimiento}', 'RequerimientoController@update')->name('requerimientos.update');

                Route::get('logistica', 'RequerimientoController@listaLogistica')->name("pedidos.listaLogistica");
            });
        });

        Route::group(['middleware' => ['type:\App\Empresa']], function () {
            Route::get('presupuestos/cmi', 'PresupuestoController@cmi')->name('presupuesto.cmi');
            Route::get('presupuesto/empresa/{empresaId?}/{mes?}{year?}{acumulado?}', 'PresupuestoController@indexEmpresa')->name('presupuesto.indexEmpresa');
            Route::resource('presupuesto', 'PresupuestoController')->except([
                'index', 'show'
            ]);
            Route::get('usuarios-centro/', 'UserController@indexEmpresa')->name('user.indexEmpresa');
            Route::get('usuarios-centro/create', 'UserController@create')->name('user.create');
            Route::post('usuarios-centro/create', 'UserController@storeCentro')->name('user.store');
            Route::get('usuarios-centro/{usuario}', 'UserController@editCentro')->name('user.editCentro');
            Route::put('usuarios-centro/{usuario}', 'UserController@updateCentro')->name('user.updateCentro');
            Route::delete('usuarios-centro/{usuario}', 'UserController@destroy')->name('user.destroyCentro');
            Route::get('/presupuesto/seleccionar-centro','PresupuestoController@seleccionarCentro') ->name('presupuesto.seleccionarCentro');
            
Route::get('/presupuesto/editar',  'PresupuestoController@editar')
    ->name('presupuesto.editar');

    Route::post('presupuesto/actualizar/{centro}/{year}', 'PresupuestoController@actualizar')
        ->name('presupuesto.actualizar');

            Route::get('validacion', 'ReportController@validaciones')->name('reportes.validaciones');
            Route::post('validacion', 'ReportController@generarValidaciones')->name('reportes.validaciones.generar');


            Route::get(
                'enviados',
                'ReportController@enviadosView'
            )->name('reportes.enviados');
            Route::post(
                'enviados',
                'ReportController@enviados'
            )->name('reportes.enviados.generar');

            Route::get(
                'recibidos',
                'ReportController@recibidosView'
            )->name('reportes.recibidos');
            Route::post(
                'recibidos',
                'ReportController@recibidos'
            )->name('reportes.recibidos.generar');

            Route::get(
                'cierre',
                'ReportController@cierresView'
            )->name('reportes.cierres');
            Route::post(
                'cierre',
                'ReportController@cierres'
            )->name('reportes.cierres.generar');

            Route::get(
                "estado-pago/{guiaDespacho?}",
                "RechazoController@estadoView"
            )->name("rechazo.estado-pago");

            Route::post(
                "estado-pago/{rechazo}/estado",
                "RechazoController@cambiarEstado"
            )->name("rechazo.estado-pago.post");

            Route::post(
                "estado-pago/guardar",
                "RechazoController@guardarEstados"
            )->name("rechazo.guardar-estado");

            Route::get(
                "nota-credito",
                "ReportController@notaCreditoView"
            )->name("reportes.nota-credito");

            Route::post(
                "nota-credito",
                "ReportController@notaCredito"
            )->name("reportes.nota-credito.generar");

            Route::get(
                "carga-empresa",
                "ReportController@cargaEmpresaView"
            )->name("reportes.carga-empresa");

            Route::post(
                "carga-empresa",
                "ReportController@cargaEmpresa"
            )->name("reportes.carga-empresa.generar");
        });

        Route::get('presupuesto/centro/{centroId?}/{mes?}{year?}{acumulado?}', 'PresupuestoController@indexCentro')->name('presupuesto.indexCentro');
    });

    // Rutas de Compass
    Route::group(['prefix' => 'alogis', 'middleware' => ['compass']], function () {

        Route::get('/', 'HomeController@index')->name('compass.home');

        Route::group(['prefix' => 'bidones'], function () {

            Route::resources([
                'proveedores' => 'ProveedorController',
                'bidones' => 'BidonController',
                'entradas' => 'EntradaController',
                'salidas' => 'SalidaController',
                'carga-iniciales' => 'CargaInicialController',
                'ajustes' => 'AjusteController',
                'nota-creditos' => 'NotaCreditoController'
            ]);

            Route::get('/kardex', 'KardexController@show')->name('kardex-show');
            Route::post('/kardex', 'KardexController@filter')->name('kardex');
            Route::get('/reportes/inventario', 'ReporteController@showInventario')->name('inventario');
            Route::post('/reportes/inventario', 'ReporteController@filterInventario')->name('filter-inventario');
            Route::get('/reportes/entrada', 'ReporteController@showEntrada')->name('entrada');
            Route::post('/reportes/entrada', 'ReporteController@filterEntrada')->name('filter-entrada');
            Route::get('/reportes/salida', 'ReporteController@showSalida')->name('salida');
            Route::post('/reportes/salida', 'ReporteController@filterSalida')->name('filter-salida');
            Route::get('/proveedores/producto', 'GetProveedorProductoController')->name('get-productos');
        });

        Route::get("servicio-cliente", "ServicioClienteController@view")
            ->name("servicio-cliente.view");
        Route::post("servicio-cliente", "ServicioClienteController@submit")
            ->name("servicio-cliente.submit");


        Route::get('asignacion-masiva', 'ProgramacionPrecioController@show')->name('productos.asignacionMasivaView');
        Route::get('asignacion-masiva/formato', 'ProgramacionPrecioController@formato')->name('productos.asignacionMasivaFormato');

        Route::post('cargar/precio', 'ProgramacionPrecioController@crearProgramacion')->name('productos.asignacionMasiva');


        Route::post('empresas/{empresa}/productos/show', 'ProductoController@show')->name('empresas.productos.show');
        Route::resource('empresas.productos', 'ProductoController')->shallow();
        Route::post("empresas/productos/formato", "ProductoController@formatoCargaMasiva")->name("productos.formato-carga-masiva");

        Route::resources([
            'holdings' => 'HoldingController',
            'abastecimientos' => 'AbastecimientoController',
            'bodegueros' => 'BodegueroController',
            'horarios' => 'HorarioController'
        ]);

        Route::get('descargar/precio', 'ProgramacionPrecioController@formato')->name('productos.formato');

        Route::get('descargar/producto', 'ProductoController@formatoProductos')->name('productos.formatoProductos');

        Route::get('estado/empresa/{empresa}', 'EmpresaController@habilitarForm')->name('empresas.habilitar.get');
        Route::post('habilitar/empresa/{empresa}', 'EmpresaController@habilitar')->name('empresas.habilitar');

        Route::get('usuarios/{tipo?}', 'UserController@index')->name('usuarios.index');

        Route::delete('usuarios/eliminar/{usuario}', 'UserController@destroy')->name('usuarios.destroy');
        Route::get('asignar-usuarios', 'UserController@usuariosSinAsignar')->name('usuarios.asignar');
        Route::get('asignacion-usuario/{userId}/{tipo}', 'UserController@asignar')->name('usuario.asignar');
        Route::post('asignacion-usuario', 'UserController@asignacion')->name('usuario.asignacion');
        Route::get('users/{usuario}', 'UserController@edit')->name('usuarios.edit');
        Route::put('users/{usuario}', 'UserController@update')->name('usuarios.update');
        Route::delete('users/{usuario}', 'UserController@destroy')->name('usuarios.destroy');

        Route::get('cargar-folios', 'FolioController@create')->name('cargarFolios');
        Route::post('cargar-folios', 'FolioController@store')->name('folios.store');

        Route::post('requerimiento/{requerimiento}/productos/agregar', 'RequerimientoController@agregarProductos')->name('requerimiento.productos.agregar');
        Route::delete('requerimiento/{requerimiento}', 'RequerimientoController@eliminar')->name('requerimiento.eliminar');
        Route::get('reemplazar-producto/{requerimiento}/{producto}', 'RequerimientoController@cambiarProducto')->name('cajas.cambiar');
        Route::put('reemplazar-producto/{requerimiento}', 'RequerimientoController@reemplazar')->name('cajas.reemplazar');
        Route::get('borrar-producto/{requerimiento}/{producto}', 'RequerimientoController@borrarProducto')->name('cajas.borrar');

        Route::group(['prefix' => 'pedidos'], function () {

            Route::get('importar', 'RequerimientoController@cargaMasiva')->name('requerimiento.cargaMasiva');
            Route::post('importar', 'RequerimientoController@importar')->name('requerimientos.importar');

            Route::get('verificar', 'RequerimientoController@verificar')->name('compass.pedidos.verificar');
            Route::post('verificar', 'RequerimientoController@doVerificar')->name('compass.verificar');

            Route::get('armar', 'RequerimientoController@indexCajas')->name('compass.pedidos.cajasIndex');
            Route::get('armar/{requerimiento}', 'RequerimientoController@showCaja')->name('compass.pedidos.show');
            Route::post('armar-caja/{requerimiento}', 'RequerimientoController@armarCaja')->name('compass.pedidos.armarCaja');

            Route::get('despachar', 'RequerimientoController@despacharView')->name('compass.pedidos.despachar');

            Route::get('programar-despacho', 'TransporteController@create')->name('compass.pedidos.programarDespachos');
            Route::post('programar-despacho', 'TransporteController@store')->name('compass.pedidos.programarDespachos.post');

            Route::post('eliminar-despacho/{id}', 'RequerimientoController@eliminarDespacho')->name('compass.eliminarDespacho');
            Route::post('generar-guia/{id}', 'RequerimientoController@generarGuia')->name('compass.generarGuia');
            Route::post('despachar/{id}', 'RequerimientoController@despachar')->name('compass.despachar');

            Route::get('descargar-guia/{requerimiento}', 'RequerimientoController@descargarGuia')->name('descargarGuias');

            Route::post("guias-despacho/generar/{transporte}", "TransporteController@generarGuia")->name('guia-despacho.create');

            Route::get('guias-despacho/{transporte}', 'GuiaDespachoController@index')->name('guia-despacho.index');
            Route::get('guias-despacho/{guiaDespacho}/show', 'GuiaDespachoController@show')->name('guia-despacho.show');
        });

        Route::group(['prefix' => 'reportes'], function () {
            Route::get('productos_cantidad/{year?}', 'ReportController@productosPorCantidad')->name('reportes.productosCantidad');
            Route::get('packs', 'ReportController@packs')->name('reportes.packs');
            Route::post('packs', 'ReportController@generarPack')->name('reportes.packs.generar');

            Route::get('estados-pagos', 'ReportController@estadoPagoView')->name('reportes.estadoPago');
            Route::post('estados-pagos', 'ReportController@estadoPago')->name('reportes.estadoPago.generar');

            Route::get('productos', 'ReportController@rebajaView')->name('reportes.productos');
            Route::post('productos', 'ReportController@rebaja')->name('reportes.productos.generar');

            Route::get('guias_emitidas', 'ReportController@historialView')
                ->name('reportes.guias.view');
            Route::post('guias_emitidas', 'ReportController@historial')
                ->name("reportes.guias.filter");

            Route::get('cartas', 'ReportController@cartaRequerimientos')
                ->name('reportes.carta.view');
            Route::post('cartas', 'ReportController@cartaRequerimientos')
                ->name("reportes.carta.filter");
            Route::post('cartas/{requerimiento}', 'ReportController@cartasRequerimientoGenerate')
                ->name("reportes.carta.download");
        });
    });


    Route::group(["prefix" => "estado_pago"], function () {
        Route::get("general", "EstadoPagoController@cuadroEstadoGeneral")->name("estado_pago_general");
        Route::post("general", "EstadoPagoController@generateEstadoGeneral")->name("generate_estado_general");

        Route::get("{guiaDespacho}/concepto", "EstadoPagoController@concepto")->name("estado_pago_concepto");
        Route::post("{guiaDespacho}/concepto", "EstadoPagoController@enviarActualizacion")->name("estado_pago_actualizado");

        Route::post("{guiaDespacho}/producto", "EstadoPagoController@conceptoStore")->name("estado_pago_concepto_store");
        Route::post("{guiaDespacho}/producto/{producto?}", "EstadoPagoController@generarReclamo")->name("estado_pago_reclamo");
        Route::post("{guiaDespacho}/productos", "EstadoPagoController@conceptoMassiveStore")->name("estado_pago_concepto_masivo");

        Route::get("resumen", "EstadoPagoController@resumen")->name("estado_pago_resumen");
        Route::post("resumen", "EstadoPagoController@generarResumen")->name("estado_pago_generar_resumen");

        Route::post("{guiaDespacho}/liquidado", "EstadoPagoController@marcarGuiaLiquidado")->name("estado_pago_liquidado");

        Route::get("cierre", "EstadoPagoController@cierre")->name("estado_pago_cierre");
        Route::post("cierre", "EstadoPagoController@generarCierre")->name("estado_pago_generar_cierre");

        Route::get("nota-credito/{guiaDespacho}", "EstadoPagoController@notaCredito")->name("estado_pago_nota_credito");

        Route::get("orden-compra", "ControlOrdenCompraCentroController@index")->name("orden_compra_index");
        Route::post("orden-compra", "ControlOrdenCompraCentroController@filtrar")->name("orden_compra_filtrar");
    });

    Route::group(["prefix" => "orden_compra"], function () {
        Route::get("index", "OrdenCompraController@index")->name("orden_compra_index");
        Route::post("index", "OrdenCompraController@filtrar")->name("orden_compra_filtrar");

        Route::get("cierre/{cierre}", "OrdenCompraController@cierre")->name("orden_compra_cierre");

        Route::get("crear/{cierre}", "OrdenCompraController@create")->name("orden_compra_create");
        Route::post("crear/{cierre}", "OrdenCompraController@store")->name("orden_compra_store");
        Route::delete("{ordenCompra}", "OrdenCompraController@destroy")->name("orden_compra_delete");

        Route::get("edit/{ordenCompra}", "OrdenCompraController@edit")->name("orden_compra_edit");
        Route::post("update/{ordenCompra}", "OrdenCompraController@update")->name("orden_compra_update");

        Route::post("exportar/{cierre}", "OrdenCompraController@export")->name("orden_compra_export");
    });

    Route::group(["prefix" => "nota_credito"], function () {
        Route::get("index", "NotaCreditoTributariaController@index")->name("nota_credito_index");
        Route::post("index", "NotaCreditoTributariaController@filtrar")->name("nota_credito_filtrar");

        Route::get("cierre/{cierre}", "NotaCreditoTributariaController@cierre")->name("nota_credito_cierre");

        Route::get("crear/{cierre}", "NotaCreditoTributariaController@create")->name("nota_credito_create");
        Route::post("crear/{cierre}", "NotaCreditoTributariaController@store")->name("nota_credito_store");
        Route::delete("{notaCreditoTributaria}", "NotaCreditoTributariaController@destroy")->name("nota_credito_delete");

        Route::get("edit/{notaCreditoTributaria}", "NotaCreditoTributariaController@edit")->name("nota_credito_edit");
        Route::post("update/{notaCreditoTributaria}", "NotaCreditoTributariaController@update")->name("nota_credito_update");

        Route::post("exportar/{cierre}", "NotaCreditoTributariaController@export")->name("nota_credito_export");
    });

    Route::group(["prefix" => "factura_electronica"], function () {
        Route::get("index", "FacturaElectronicaController@index")->name("factura_electronica_index");
        Route::post("index", "FacturaElectronicaController@filtrar")->name("factura_electronica_filtrar");

        Route::get("cierre/{cierre}", "FacturaElectronicaController@cierre")->name("factura_electronica_cierre");

        Route::get("crear/{cierre}", "FacturaElectronicaController@create")->name("factura_electronica_create");
        Route::post("crear/{cierre}", "FacturaElectronicaController@store")->name("factura_electronica_store");
        Route::delete("{facturaElectronica}", "FacturaElectronicaController@destroy")->name("factura_electronica_delete");

        Route::get("edit/{facturaElectronica}", "FacturaElectronicaController@edit")->name("factura_electronica_edit");
        Route::post("update/{facturaElectronica}", "FacturaElectronicaController@update")->name("factura_electronica_update");

        Route::post("exportar/{cierre}", "FacturaElectronicaController@export")->name("factura_electronica_export");
    });

    Route::group(["prefix" => "conciliacion"], function () {
        Route::get("index", "ConciliacionController@index")->name("conciliacion_index");
        Route::post("index", "ConciliacionController@filtrar")->name("conciliacion_filtrar");

        Route::get("{cierre}", "ConciliacionController@conciliacion")->name("conciliacion_centro");

        Route::post("{cierre}", "ConciliacionController@export")->name("conciliacion_export");
    });

    Route::delete("eliminar-cierre/{cierre}", "DeleteCierre")->name("eliminar_cierre");
});
