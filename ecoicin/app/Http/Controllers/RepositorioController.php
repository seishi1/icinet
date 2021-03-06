<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\Documento;
use App\EstadisticasDocumento;
use App\TipoProyecto;
use Storage;

class RepositorioController extends Controller
{
    /**
     * Muestra todos los proyectos que se encuentran en el repositorio
     * @return View con los proyectos
     */
    public function index()
    {
        $proyectos = Proyecto::all();
        $tipo_proyecto = TipoProyecto::all();
        $cont = 2;

        return view('repositorio', compact('proyectos', 'tipo_proyecto', 'cont'));
    }

    /**
     * Muestra todos los proyectos que se encuentran en el repositorio
     * @return View con los proyectos
     */
    public function producto()
    {
        $proyectos = Proyecto::all();
        $tipo_proyecto = TipoProyecto::all();
        $cont = 2;

        return view('producto', compact('proyectos', 'tipo_proyecto', 'cont'));
    }

    /**
     * Muestra todos los proyectos que se encuentran en el repositorio
     * @return View con los proyectos
     */
    public function documento()
    {
        $proyectos = Proyecto::all();
        $tipo_proyecto = TipoProyecto::all();
        $cont = 2;

        return view('documento', compact('proyectos', 'tipo_proyecto', 'cont'));
    }

    /**
     * Lista los proyectos en una tabla para ejecutar acciones o agregar nuevos
     * @return View con todos los proyectos
     */
    public function listarProyectos(){
      $proyectos = Proyecto::all();
      $tipo_proyecto = TipoProyecto::all();
      $cont = 2;

      return view('proyectos', compact('proyectos', 'tipo_proyecto', 'cont'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     // NO SE HA PROBADO ESTA FUNCION DEL CONTROLADOR!!!!
    public function store(Request $request)
    {
          $proyecto = new Proyecto;
          $proyecto->nombre_proyecto = $request->nombre_proyecto;
          $proyecto->autores_proyecto = $request->autores_proyecto;
          $proyecto->tipo_proyecto_id = $request->tipo_proyecto;
          $proyecto->fecha_proyecto = $request->fecha_proyecto;
          $proyecto->url_proyecto = $request->url_proyecto;
          $rs_proyecto = $proyecto->save();

          if($rs_proyecto){

            $informe = new Documento;
            $informe->nombre_documento = $proyecto->nombre_proyecto.'_informe';
            $informe->ruta_documento = $this->almacenarDocumento($request->informe_proyecto, $informe->nombre_documento, 'informe');
            $informe->proyecto_id = $proyecto->id;
            $rs_informe = $informe->save();
            $informe_id = $informe->id;

            if ($rs_informe) {
              $estadistica_informe = new EstadisticasDocumento;
              $estadistica_informe->num_visitas = 0;
              $estadistica_informe->num_descargas = 0;
              $estadistica_informe->documento_id = $informe_id;
              $rs_est_informe = $estadistica_informe->save();
            }

            $presentacion = new Documento;
            $presentacion->nombre_documento = $proyecto->nombre_proyecto.'_presentacion';
            $presentacion->ruta_documento = $this->almacenarDocumento($request->presentacion_proyecto, $presentacion->nombre_documento, 'presentacion');
            $presentacion->proyecto_id = $proyecto->id;
            $rs_presentacion = $presentacion->save();
            $presentacion_id = $presentacion->id;

            if ($rs_presentacion) {
              $estadistica_presentacion = new EstadisticasDocumento;
              $estadistica_presentacion->num_visitas = 0;
              $estadistica_presentacion->num_descargas = 0;
              $estadistica_presentacion->documento_id = $presentacion_id;
              $rs_est_presentacion = $estadistica_presentacion->save();
            }
          }

          if($rs_proyecto /*&& $rs_informe && $rs_est_informe && $rs_presentacion && $rs_est_presentacion*/){
            //dd($rs_proyecto);
            return back()->with('mensaje', 'Se ha ingresado correctamente el proyecto');
          }

          return back()->with('mensaje', 'Ha ocurrido un error al ingresar el proyecto');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Obtiene el proyecto por medio de su id con todos sus atributos correspondientes
     * @param  Int $proyecto id
     * @return Proyecto   correspondiente al id
     */
    public function edit($proyecto)
    {
      $proyecto = Proyecto::where('id_proyecto', $proyecto)->first();

      //$informe = Documento::where('nombre_documento', 'like', '%informe%')->get()->where('proyecto_id', $proyecto->id_proyecto)->first()->id_documento;

      //$presentacion = Documento::where('nombre_documento', 'like', '%presentacion%')->get()->where('proyecto_id', $proyecto->id_proyecto)->first()->id_documento;

      //$informe = $this->verDocumento($informe->id_documento);

      //$presentacion = $this->verDocumento($presentacion->id_documento);

      return compact('proyecto');
    }

    /**
     * Actualiza los atributos del proyecto en la base de datos.
     *
     * @param  Request  $request  con los datos a editar
     * @return Response  vuelve a la pagina anterior
     */
    public function update(Request $request)
    {
      $proyecto = $request->id_proyecto;
      $nombre_proyecto = $request->nombre_proyecto;
      $autores_proyecto = $request->autores_proyecto;
      $tipo_proyecto_id = $request->tipo_proyecto;
      $fecha_proyecto = $request->fecha_proyecto;
      $parm_proyecto = compact('nombre_proyecto', 'autores_proyecto', 'tipo_proyecto_id', 'fecha_proyecto');
      $rs = Proyecto::where('id_proyecto', $proyecto)->update($parm_proyecto);

      if(isset($request->informe_proyecto)){
        $informe = Documento::where('nombre_documento', 'like', '%informe%')->get()->where('proyecto_id', $proyecto)->first();
        if (Storage::exists($informe->ruta_documento)) {
            Storage::delete($informe->ruta_documento);
        }

        $ruta_documento = $this->almacenarDocumento($request->informe_proyecto, $nombre_proyecto.'_informe', 'informe');

        $nombre_documento = $nombre_proyecto.'_informe';
        $parm_informe = compact('nombre_documento', 'ruta_documento');

        $rs = Documento::where('id_documento', $informe->id_documento)->update($parm_informe);
      }

      if(isset($request->presentacion_proyecto)){

        $presentacion = Documento::where('nombre_documento', 'like', '%presentacion%')->get()->where('proyecto_id', $proyecto)->first();
        if (Storage::exists($presentacion->ruta_documento)) {
            Storage::delete($presentacion->ruta_documento);
        }

        $ruta_documento = $this->almacenarDocumento($request->presentacion_proyecto, $nombre_proyecto.'_presentacion', 'presentacion');

        $nombre_documento = $nombre_proyecto.'_presentacion';
        $parm_presentacion = compact('nombre_documento', 'ruta_documento');

        $rs = Documento::where('id_documento', $presentacion->id_documento)->update($parm_presentacion);
      }

      if ($rs) {
        return back();
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
      $proyecto = $request->id_proyecto;
      $informe = Documento::where('nombre_documento', 'like', '%informe%')->get()->where('proyecto_id', $proyecto)->first();
      if (Storage::exists($informe->ruta_documento)) {
        Storage::delete($informe->ruta_documento);
      }

      //$rs = Documento::where('id_documento', $informe->id_documento)->delete();

      $presentacion = Documento::where('nombre_documento', 'like', '%presentacion%')->get()->where('proyecto_id', $proyecto)->first();
      if (Storage::exists($presentacion->ruta_documento)) {
        Storage::delete($presentacion->ruta_documento);
      }
      $rs = Proyecto::where('id_proyecto', $proyecto)->delete();


      //$rs = Documento::where('id_documento', $presentacion->id_documento)->delete();

      return $rs;
    }

    protected function almacenarDocumento($documento, $nombre_documento, $tipo){
      return $documento->storeAs("uploads_".$tipo, $nombre_documento.".pdf");
    }

    public function verDocumento($documento){
      $ruta_documento = Documento::where('id_documento', $documento)->first()->ruta_documento;
      return Storage::response($ruta_documento);
    }

    public function descargarDocumento($documento){
      $ruta_documento = Documento::where('id_documento', $documento)->first()->ruta_documento;
      return Storage::download($ruta_documento);
    }
}
