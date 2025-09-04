<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     *  
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo = null)
    {
        switch ($tipo) {
        case 'h':
            $users = User::whereHasMorph(
                'userable',
                ['App\Holding'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Holding';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        case 'e':
            $users = User::whereHasMorph(
                'userable',
                ['App\Empresa'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Empresa';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        case 'c':
            $users = User::whereHasMorph(
                'userable',
                ['App\Centro'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Centro';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        case 'r':
            $users = User::whereHasMorph(
                'userable',
                ['App\CompassRole'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Compass';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        default:
            $users = User::all();
            return view('usuario.index')->with(compact('users'));
            break;
        }
    }


    /**
     * Muestra el cuadro con los usuarios sin asignar
     *
     * @return \Illuminate\Http\Response
     */
    public function usuariosSinAsignar()
    {
        $users = User::where('userable_id', null)->get();
        return view('usuario.asignar')->with(compact('users'));
    }

    /**
     * Muestra informacion del usuario y del tipo a asignar
     *
     * @return \Illuminate\Http\Response
     */
    public function asignar($userId, $tipo)
    {
        $user = User::findOrFail($userId);
        switch ($tipo) {
        case 'h':
            $asignacion = \App\Holding::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion'));
            break;
        case 'e':
            $asignacion = \App\Empresa::all();
             $centro = \App\Centro::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion','centro'));
            break;
        case 'c':
            $asignacion = \App\Centro::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion'));
            break;
        case 'r':
            $asignacion = \App\CompassRole::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion'));
            break;
        default:
            Auth::logout();
            break;
        }
    }

    /**
     * Asigna Usuario a un Rol
     *
     * @return \Illuminate\Http\Response
     */
  /**  public function asignacion(Request $request)
    {
        $user = User::findOrFail($request->input('userId'));
        $class = $request->input('asignacionType');
        $asignacion = $class::find($request->input('asignacion'));
        $user->userable_id = $asignacion->id;
        $user->userable_type = get_class($asignacion);

        if ($user->saveOrFail()) {
            
            $msg = [
                'meta' => [
                    'title' => '¡Usuario asignado exitosamente!',
                    'msg' => "El Usuario $user->name fue asignado con exito a".($asignacion->nombre ?? $asignacion->name ?? $asignacion->razon_social)
                ]
            ];

            return redirect()->route('usuarios.index')->with(compact('msg'));
        }

    }
*/
/**
 * Asigna Usuario a un Rol y a un Centro (si se selecciona)
 *
 * @return \Illuminate\Http\Response
 */
public function asignacion(Request $request)
{
    $user = User::findOrFail($request->input('userId'));
    $class = $request->input('asignacionType');
    $asignacion = $class::find($request->input('asignacion'));

    // Asignar el tipo de usuario (Empresa, Centro, etc.)
    $user->userable_id = $asignacion->id;
    $user->userable_type = get_class($asignacion);

    // Guardar el usuario
    if ($user->saveOrFail()) {
        // Asignar los centros seleccionados
        if ($request->has('centro')) {
            $centros = $request->input('centro'); // Obtener los centros seleccionados

            // Eliminar centros asignados previamente (opcional, dependiendo de tu lógica)
            $user->centros()->detach();

            // Asignar los nuevos centros
            foreach ($centros as $centroId) {
                $user->centros()->attach($centroId);
            }
        }

        $msg = [
            'meta' => [
                'title' => '¡Usuario asignado exitosamente!',
                'msg' => "El Usuario $user->name fue asignado con éxito a " . ($asignacion->nombre ?? $asignacion->name ?? $asignacion->razon_social)
            ]
        ];

        return redirect()->route('usuarios.index')->with(compact('msg'));
    } else {
        $msg = [
            'meta' => [
                'title' => 'Error',
                'msg' => 'No se pudo asignar el usuario.'
            ]
        ];

        return redirect()->route('usuarios.index')->with(compact('msg'));
    }
}
    /**
     * Muestra la lista de Librerias de ese Usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function libreriaIndex()
    {
        $requerimientos = Auth::user()->requerimientos()->get();
        return view('requerimiento.libreria_index')->with(compact('requerimientos'));
    }
    

    /**
     * Agrega o Elimina un Requerimiento de la libreria del Usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function libreriaEdit(\App\Requerimiento $requerimiento, Request $request)
    {
        if ($request->input("libreria")) {
            Auth::user()->requerimientos()->save($requerimiento, ['nombre' => $request->input('nombre')]);
            return response()->json([
                "meta" => [
                    "title" => "Agregado a la libreria",
                    "msg" => "El Requerimiento fue agregado a la libreria exitosamente"
                ]
            ]);
        } else {
            Auth::user()->requerimientos()->detach($requerimiento->id);
            return response()->json([
                "meta" => [
                    "title" => "Eliminado de la libreria",
                    "msg" => "El Requerimiento fue eliminado de la libreria exitosamente"
                ]
            ]);
        }
    }

    /**
     * Muestra el listado de usuarios de los centros de esa empresa
     *
     * @return \Illuminate\Http\Response
     */
    public function indexEmpresa()
    {
        $centros = Auth::user()->userable->centros()->get();

        $users = collect([]);
        $centros->map(function($centro) use ($users) {
            $usuarios = $centro->users()->get();
            if ($usuarios->count() > 0) {
                $users->push($usuarios);
            }
        });

        $users = $users->flatten();
        return view('usuario.index_empresa')->with(compact('users'));
    }
    
    
    /**
     * Muestra el formulario de creacion de usuarios para un centro
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centros = Auth::user()->userable->centros()->get();

        return view('usuario.create')->with(compact('centros'));
    }

    /**
     * Crea un nuevo usuario para un centro
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCentro(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));

        $centro = \App\Centro::findOrFail($request->input('centro'));
        $centro->users()->save($user);

        $msg = [
            "meta" => [
                "title" => 'Nuevo Usuario creado exitosamente',
                "msg" => 'El usuario: '.$user->name.' fue creado exitosamente para el centro: '.$centro->nombre
            ]
        ];

        return redirect()->route('user.indexEmpresa')->with(compact('msg'));
    }
    
    /**
     * Muestra el formulario para editar un usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        $user = User::find($user);
        return view('usuario.edit')->with(compact('user'));
    }
    
    /**
     * Actualiza la informacion del Usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function update($user, Request $request)
    {
        $user = User::find($user);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));

        if ($user->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => 'Usuario actualizado exitosamente',
                    "msg" => 'El Usuario '.$user->name.' fue actualizado exitosamente'
                ]
            ];

            return redirect()->route('usuarios.index')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => 'Usuario no pudo ser actualizado',
                    "msg" => 'El Usuario '.$user->name.' no pudo ser actualizado'
                ]
            ];

            return redirect()->route('usuarios.index')->with(compact('msg'));
        }
    }
    
    /**
     * Muestra el formulario para editar un usuario para centros
     *
     * @return \Illuminate\Http\Response
     */
    public function editCentro($user)
    {
        $user = User::find($user);
        $centros = Auth::user()->userable->centros()->get();
        return view('usuario.edit_centro')->with(compact('user', 'centros'));
    }
    
    /**
     * Actualiza la informacion del Usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCentro($user, Request $request)
    {
        $user = User::find($user);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));

        $user->userable()->dissociate();

        $centro = \App\Centro::findOrFail($request->input('centro'));
        $centro->users()->save($user);

        if ($user->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => 'Usuario actualizado exitosamente',
                    "msg" => 'El Usuario '.$user->name.' fue actualizado exitosamente'
                ]
            ];

            return redirect()->route('user.indexEmpresa')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => 'Usuario no pudo ser actualizado',
                    "msg" => 'El Usuario '.$user->name.' no pudo ser actualizado'
                ]
            ];

            return redirect()->route('user.indexEmpresa')->with(compact('msg'));
        }
    }
    
    public function destroy($user)
    {
        $user = \App\User::find($user);
        $user->delete();

        $msg = [
            'meta' => [
                "title" => 'Usuario Eliminado',
                "msg" => 'El usuario fue eliminado exitosamente'
            ]
        ];

        return response()->json($msg);
    }

}
