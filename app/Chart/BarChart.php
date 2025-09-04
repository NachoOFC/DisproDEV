<?php

namespace App\Chart;
use Carbon\Carbon;

class BarChart
{
    public $data;
    /**
     *  
     */
    public function __construct($userType)
    {
        $centros = collect([]);
        switch (get_class($userType)) {
        case 'App\Centro':
            $centros->push($userType);
            break;
        case 'App\Empresa':
            $centrosEmpresa = $userType->centros()->get();
            foreach ($centrosEmpresa as $centro) {
                $centros->push($centro);
            }
            break;
        }
        $requerimientos = $centros->map(function ($item) {
            return [
                'centro' => $item->nombre,
                'data' => $item->requerimientos()->whereBetween('created_at', [Carbon::create(date("Y")), Carbon::create(date("Y"), 12, 31)])
                               ->orderBy('created_at')
                               ->get()
                               ->groupBy(function ($val) {
                                   return Carbon::parse($val->created_at)->format('m');
                               })
                           ];
        });
        $this->data = collect(
            $requerimientos->map( function($item, $index) {
                return [
                    'label' => $item['centro'],
                    'backgroundColor' => '#8'.$index * 3 .'f',
                    'data' => [
                        (empty($item['data'][1])) ? 0 : count($item['data'][1]),
                        (empty($item['data'][2])) ? 0 : count($item['data'][2]),
                        (empty($item['data'][3])) ? 0 : count($item['data'][3]),
                        (empty($item['data'][4])) ? 0 : count($item['data'][4]),
                        (empty($item['data'][5])) ? 0 : count($item['data'][5]),
                        (empty($item['data'][6])) ? 0 : count($item['data'][6]),
                        (empty($item['data'][7])) ? 0 : count($item['data'][7]),
                        (empty($item['data'][8])) ? 0 : count($item['data'][8]),
                        (empty($item['data'][9])) ? 0 : count($item['data'][9]),
                        (empty($item['data'][10])) ? 0 : count($item['data'][10]),
                        (empty($item['data'][11])) ? 0 : count($item['data'][11]),
                        (empty($item['data'][12])) ? 0 : count($item['data'][12])
                    ]
                ];
            })
        );
    }

    /**
     * returns Data as JSON
     *
     * @return JSON data
     */
    public function toJson()
    {
        return json_encode($this->data);
    }
    
}
