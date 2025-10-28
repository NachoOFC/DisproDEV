<?php

namespace App\Http\Controllers\Api;

use App\Requerimiento;
use App\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    /**
     * Obtener todos los requerimientos
     */
    public function requerimientos()
    {
        try {
            $requerimientos = Requerimiento::select('id', 'numero', 'descripcion', 'estado_id', 'created_at')
                ->with('estado:id,nombre')
                ->limit(100)
                ->get()
                ->map(function ($req) {
                    return [
                        'id' => $req->id,
                        'numero' => $req->numero,
                        'descripcion' => $req->descripcion,
                        'estado' => $req->estado->nombre ?? 'Desconocido',
                        'fecha' => $req->created_at->format('Y-m-d'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $requerimientos,
                'count' => $requerimientos->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los productos
     */
    public function productos()
    {
        try {
            $productos = Producto::select('id', 'nombre', 'sku', 'precio', 'stock')
                ->limit(100)
                ->get()
                ->map(function ($prod) {
                    return [
                        'id' => $prod->id,
                        'nombre' => $prod->nombre,
                        'sku' => $prod->sku,
                        'precio' => '$' . number_format($prod->precio, 0, ',', '.'),
                        'stock' => $prod->stock,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $productos,
                'count' => $productos->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener reportes (resumen de datos)
     */
    public function reportes()
    {
        try {
            $reportes = [
                [
                    'id' => 1,
                    'titulo' => 'Reporte de Requerimientos - ' . date('M Y'),
                    'fecha' => date('Y-m-d'),
                    'descarga' => 'PDF',
                    'url' => '/api/reportes/requerimientos/pdf'
                ],
                [
                    'id' => 2,
                    'titulo' => 'Análisis de Productos',
                    'fecha' => date('Y-m-d'),
                    'descarga' => 'XLSX',
                    'url' => '/api/reportes/productos/xlsx'
                ],
                [
                    'id' => 3,
                    'titulo' => 'Proyección de Stock',
                    'fecha' => date('Y-m-d'),
                    'descarga' => 'PDF',
                    'url' => '/api/reportes/stock/pdf'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $reportes,
                'count' => count($reportes)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuarios (solo los básicos)
     */
    public function usuarios()
    {
        try {
            $usuarios = \App\User::select('id', 'name', 'email')
                ->limit(50)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'nombre' => $user->name,
                        'email' => $user->email,
                        'rol' => 'Usuario',
                        'estado' => 'Activo',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $usuarios,
                'count' => $usuarios->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
