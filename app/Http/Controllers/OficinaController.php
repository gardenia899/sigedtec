<?php

namespace App\Http\Controllers;

use App\Http\Requests\OficinaRequest;
use App\Models\Office;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OficinaController extends Controller
{
    public function index(): View
    {
        return view('oficinas.index', [
            'oficinas' => Office::withCount('users')
                ->select(['id', 'code', 'name', 'location', 'is_active'])
                ->orderBy('code')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('oficinas.create', [
            'codigoOficina' => $this->siguienteCodigo(),
        ]);
    }

    public function store(OficinaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        DB::transaction(function () use ($data) {
            $data['code'] = $this->reservarCodigo();
            Office::create($data);
        });

        return redirect()->route('oficinas.index')->with('success', 'Oficina registrada correctamente.');
    }

    public function edit(Office $office): View
    {
        return view('oficinas.edit', compact('office'));
    }

    public function update(OficinaRequest $request, Office $office): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');

        $office->update($data);

        return redirect()->route('oficinas.index')->with('success', 'Oficina actualizada correctamente.');
    }

    private function siguienteCodigo(): string
    {
        $sequence = (int) DB::table('office_code_sequences')
            ->where('id', 1)
            ->value('last_number');

        return sprintf('OFI-%06d', max($sequence, $this->maximoCodigo()) + 1);
    }

    private function reservarCodigo(): string
    {
        DB::table('office_code_sequences')->insertOrIgnore([
            'id' => 1,
            'last_number' => 0,
        ]);

        $sequence = DB::table('office_code_sequences')
            ->where('id', 1)
            ->lockForUpdate()
            ->first();

        $next = max((int) $sequence->last_number, $this->maximoCodigo()) + 1;

        DB::table('office_code_sequences')
            ->where('id', 1)
            ->update(['last_number' => $next]);

        return sprintf('OFI-%06d', $next);
    }

    private function maximoCodigo(): int
    {
        return Office::query()
            ->where('code', 'like', 'OFI-%')
            ->pluck('code')
            ->map(static function (string $code): int {
                $suffix = substr($code, 4);

                return ctype_digit($suffix) ? (int) $suffix : 0;
            })
            ->max() ?? 0;
    }

    public function toggle(Office $office): RedirectResponse
    {
        if ($office->is_active && $office->users()->exists()) {
            return redirect()->route('oficinas.index')->with('error', 'No se puede desactivar una oficina con usuarios asignados.');
        }

        $office->update(['is_active' => ! $office->is_active]);

        $mensaje = $office->is_active ? 'Oficina activada correctamente.' : 'Oficina desactivada correctamente.';

        return redirect()->route('oficinas.index')->with('success', $mensaje);
    }
}
