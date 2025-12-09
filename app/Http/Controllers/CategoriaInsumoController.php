<?php

namespace App\Http\Controllers;

use App\Models\CategoriaInsumo;
use Illuminate\Http\Request;

class CategoriaInsumoController extends Controller
{
    public function index()
    {
        $restauranteId = $this->restauranteId();
        
        // Garantir que categorias predefinidas existam
        CategoriaInsumo::criarPredefinidas($restauranteId);
        
        $categorias = CategoriaInsumo::where('restaurante_id', $restauranteId)
            ->orderBy('ordem')
            ->orderBy('nome')
            ->get();
        
        return view('categoria_insumos.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $restauranteId = $this->restauranteId();
        
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:100', 'unique:categoria_insumos,nome,NULL,id,restaurante_id,' . $restauranteId],
            'descricao' => ['nullable', 'string'],
        ]);

        $data['restaurante_id'] = $restauranteId;
        $data['predefinida'] = false;
        $data['ordem'] = CategoriaInsumo::where('restaurante_id', $restauranteId)->max('ordem') + 1;

        CategoriaInsumo::create($data);

        return redirect()->route('categoria-insumos.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function destroy(CategoriaInsumo $categoriaInsumo)
    {
        $this->authorizeCategoria($categoriaInsumo);
        
        // Não permitir deletar categorias predefinidas
        if ($categoriaInsumo->predefinida) {
            return redirect()->route('categoria-insumos.index')
                ->with('error', 'Não é possível deletar categorias predefinidas.');
        }
        
        // Verificar se está em uso
        if ($categoriaInsumo->estaEmUso()) {
            return redirect()->route('categoria-insumos.index')
                ->with('error', 'Não é possível deletar uma categoria que está em uso por insumos.');
        }

        $categoriaInsumo->delete();

        return redirect()->route('categoria-insumos.index')->with('success', 'Categoria removida com sucesso.');
    }

    protected function restauranteId(): int
    {
        return (int) session('restaurante_id');
    }

    protected function authorizeCategoria(CategoriaInsumo $categoria): void
    {
        abort_unless($categoria->restaurante_id === $this->restauranteId(), 403);
    }
}
