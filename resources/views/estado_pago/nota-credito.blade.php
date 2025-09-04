<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Nota Credito</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>

<body>
    <div class="container" id="content">
        <div class="row gx-1 justify-content-center">
            <div class="col-6 d-inline-flex flex-column text-center">
                <strong>COMPASS CATERING S.A</strong>
                <span>GIRO: SERVICIOS DE ALIMENTACIÓN</span>
                <span>CASA MATRIZ: AV. LAS CONDES 11,774 PISO 7</span>
                <span>VITACURA- SANTIAGO</span>
            </div>
            <div class="col-2">
                <span class="d-inline-flex flex-column border border-4 border-dark p-3 text-center">
                    <strong>NOTA CREDITO</strong>
                    <strong>PROFORMA</strong>
                    <span>{{ date("d/m/Y")  }}</span>
                </span>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-5 d-inline-flex flex-column">
                <b>CLIENTE</b>
                <b>CENTRO</b>
                <b>GUIA ASOCIADA</b>
                <b>FECHA GUIA</b>
                <b>ORDEN PEDIDO</b>
                <b>FECHA ORDEN PEDIDO</b>
            </div>
            <div class="col-7 d-inline-flex flex-column">
                <span>{{ $guiaDespacho->razon_social_receptor  }}</span>
                <span>{{ $guiaDespacho->nombre_centro  }}</span>
                <span>{{ $guiaDespacho->folio  }}</span>
                <span>{{ $guiaDespacho->fecha  }}</span>
                <span>{{ $guiaDespacho->requerimiento_id  }}</span>
                <span>{{ $guiaDespacho->requerimiento->created_at  }}</span>
            </div>
        </div>
        <div class="row mt-4">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>PRODUCTO</th>
                        <th>PRECIO UNT.</th>
                        <th>CANTIDAD</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->sku  }}</td>
                        <td>{{ $producto->detalle  }}</td>
                        <td>{{ $producto->pivot->precio  }}</td>
                        <td>{{ $producto["cntd"]  }}</td>
                        <td>{{ number_format($producto["subtotal"], 0, ".", "") }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfooter>
                    <tr>
                        <td colspan="3" class="text-right">NETO</td>
                        <td>{{ number_format($neto, 0, ".", "")  }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">IVA</td>
                        <td>{{ number_format($iva, 0, ".", "")  }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">TOTAL</td>
                        <td>{{ number_format($total, 0, ".", "")  }}</td>
                    </tr>
                </tfooter>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        var element = document.getElementById("content");
        html2pdf(element);
    </script>

</body>

</html>