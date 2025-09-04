<?php

namespace App\Policies;

use App\Centro;
use App\Empresa;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequerimientoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determina si el Centro puede crear Requerimiento
     *
     * @return void
     */
    public function create(Centro $centro)
    {
        return $centro->empresa()->puedeCrear();
    }

    /**
     * Determina si la Empresa puede validar Requerimientos
     *
     * @return void
     */
    public function validar(Empresa $empresa)
    {
        return $empresa->puedeValidar();;
    }
    
    
}
