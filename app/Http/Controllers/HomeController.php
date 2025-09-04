<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $userType = $user->userable;
        $userNotifications = $user->notifications()->latest('created_at')->take(3)->get();

        // Obtener las notificaciones
        $notifications = [];
        if ($userNotifications->count() > 0) {
            foreach ($userNotifications as $notification) {
                $data = $notification->data;
                array_push($notifications, \App\Requerimiento::findOrFail($data['requerimiento_id']));
            }
        }

        // Filtrar datos según el centro asignado (si el usuario es de tipo Empresa)
        $requerimientos = collect([]); // Inicializar una colección vacía
        if ($userType instanceof \App\Empresa) {
            $centroAsignado = $user->centro; // Obtener el centro asignado al usuario

            if ($centroAsignado) {
                // Filtrar los requerimientos del centro asignado
                $requerimientos = $centroAsignado->requerimientos()
                    ->where("created_at", ">=", now()->subMonths(3)) // Últimos 3 meses
                    ->get();
            }
        }

        // Cargar la vista correspondiente
        if (($userType instanceof \App\Centro) || ($userType instanceof \App\Empresa) || ($userType instanceof \App\Holding)) {
            return view('cliente.home')->with(compact('notifications', 'requerimientos'));
        } elseif ($userType instanceof \App\CompassRole) {
            return view('compass.home')->with(compact('notifications'));
        } else {
            return view('login');
        }
    }
}
