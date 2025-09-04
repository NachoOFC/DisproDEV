<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowNotification extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $year = $request->input('year');
        $mes = $request->input('mes');

        $userNotifications = Auth::user()->unreadNotifications()->latest('created_at')->get();

        $notifications = [];
        foreach ($userNotifications as $notification) {
            $data = $notification->data;
            array_push($notifications, \App\Requerimiento::findOrFail($data['requerimiento_id']));
        }

        Auth::user()->unreadNotifications->markAsRead();

        $home = '';
        $menu = '';

        switch (get_class(Auth::user()->userable)) {
            case 'App\CompassRole':
                $home = route('compass.home');
                $menu = 'compass.menu';
                break;
            default:
                $home = route('cliente.home');
                $menu = 'cliente.menu';
                break;
        }

        return view('notifications')->with(compact('notifications', 'home', 'menu'));
    }
}
