<?php

use App\Http\Controllers\Controller;
use Kreait\Firebase\Database;

class MiControlador extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function agregarDatos()
    {
        $nuevaData = $this->database->getReference('ruta/en/firebase')
            ->push(['clave' => 'valor']);
        
        return response()->json($nuevaData->getValue());
    }
}
