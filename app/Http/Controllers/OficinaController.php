<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Contracts\View\View;

/**
 * Módulo 2 — Catálogo de oficinas (SDD §14-M2).
 * Fase 1: listado base; CRUD completo en la Fase 2.
 */
class OficinaController extends Controller
{
    public function index(): View
    {
        return view('oficinas.index', [
            'oficinas' => Office::withCount('users')->orderBy('code')->get(),
        ]);
    }
}