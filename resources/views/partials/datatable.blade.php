<table id="datatable" class="table">
  <thead>
    <tr>
      @if (isset($index))
        <th scope="col">#</th>
      @endif
      @foreach ($headers as $header)
        <th scope="col">
          {{ ucfirst($header) }}
        </th>
      @endforeach
      @if (isset($acciones))
        <th scope="col">Acciones</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $item)
      <tr>
        @if (isset($index))
          <th scope="row">{{ ($loop->index + 1) }}</th>
        @endif
        @foreach ($headers as $header)
          <td>
            @if (is_int($item))
              {{ $items[$item][$header] }}
            @else
              {{ $item[$header] }}
            @endif
          </td>
        @endforeach
        @if (isset($acciones))
          <td>
            @foreach ($acciones as $accion)
              @if ($accion['type'] === 'link')
                <a href="{{ route($accion['data'], $item) }}">{{ $accion['label'] }}</a>
              @endif
              @if ($accion['type'] === 'modal-btn')
                <modal-btn-component
                  title="{{$accion['label']}}"
                  :message='{{$accion['data']}}'>Ver {{$accion['label']}}</modal-btn-component>
                @endif
              @endforeach
          </td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>
