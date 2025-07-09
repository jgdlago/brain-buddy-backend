<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LevelController extends Controller
{
    /**
     * @return Response
     */
    public function list(): Response
    {
        return response(
            Level::all()->list('id', 'name')
        );
    }
}
