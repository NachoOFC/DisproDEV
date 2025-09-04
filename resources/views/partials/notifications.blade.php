@if (count($notifications) > 0)
    @foreach ($notifications as $notification)
        <div
            class="alert
            @if($notification->estado === 'EN BODEGA' || $notification->estado === 'PROCESAMIENTO')
                alert-info
            @elseif($notification->estado === 'RECHAZADO')
                alert-danger
            @else
                alert-primary
            @endif
            alert-dismissible fade show"
            role="alert">
            El Requerimiento con nombre:
            <b>{{$notification->nombre}}</b>,
            fue actualizado al estado:
            <b>{{$notification->estado}}</b>,
            a la fecha de: <b>{{$notification->updated_at}}</b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@else
    <div class="alert alert-secondary">No tienes notificaciones nuevas</div>
@endif
