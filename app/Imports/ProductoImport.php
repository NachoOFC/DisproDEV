<?php

namespace App\Imports;

use App\Empresa;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductoImport implements ToCollection, WithHeadingRow
{
    use SkipsErrors, SkipsFailures;
    public $err;
    private $empresa;

    public function __construct(Empresa $empresa)
    {
        $this->err = collect([]);
        $this->empresa = $empresa;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $sku = null;
            $detalle = null;
            $costo = null;
            $venta = null;
            $desde = null;
            $hasta = null;
            $familia = null;
            $marca = null;
            $formato = null;
            $reemplazo = false;

            if (isset($row["sku"])) {
                $sku = str_pad($row["sku"], 11, "0", STR_PAD_LEFT);
            } else {
                $this->err->push($row);
                continue;
            }

            if (isset($row["detalle"])) {
                $detalle = $row["detalle"];
            } else {
                $this->err->push($row);
                continue;
            }

            if (isset($row["costo"])) {
                $costo = str_replace(",", "", $row["costo"]);
                $costo = floatval($costo);
            } else {
                $this->err->push($row);
                continue;
            }

            if (isset($row["venta"])) {
                $venta = str_replace(",", "", $row["venta"]);
                $venta = floatval($venta);
            } else {
                $this->err->push($row);
                continue;
            }

            if (isset($row["desde"])) {
                $desde = DateTime::createFromFormat("j-n-Y", $row["desde"]);

                if (!$desde) {
                    $this->err->push($row);
                    continue;
                }

                $desde = $desde->format("Y-m-d");
            } else {
                $this->err->push($row);
                continue;
            }

            if (isset($row["hasta"])) {
                $hasta = DateTime::createFromFormat("j-n-Y", $row["hasta"]);

                if (!$hasta) {
                    $this->err->push($row);
                    continue;
                }

                $hasta = $hasta->format("Y-m-d");
            } else {
                $this->err->push($row);
                continue;
            }

            if (isset($row["marca"])) {
                $marca = $row["marca"];
            }

            if (isset($row["familia"])) {
                $familia = $row["familia"];
            }

            if (isset($row["reemplazo"]) && $row["reemplazo"]) {
                $reemplazo = true;
            }

            if (isset($row["formato"])) {
                $formato = $row["formato"];
            }
            
            

            $productoNuevo = $this->empresa->productos()->create([
                "sku" => $sku,
                "detalle" => $detalle,
                "costo" => $costo,
                "venta" => $venta,
                "desde" => $desde,
                "hasta" => $hasta,
                "marca" => $marca,
                "familia" => $familia,
                "reemplazo" => $reemplazo,
                "formato" => $formato
            ]);
        }
    }
}
