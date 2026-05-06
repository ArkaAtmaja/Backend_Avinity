<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ingredients = Ingredient::when(
            $request->search,
            fn ($q) => $q->where('name', 'like', '%' . $request->search . '%')
        )->orderBy('name')->get();

        return response()->json(['data' => $ingredients]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:ingredients,name',
            'unit'          => 'required|string|max:50',
            'stock'         => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'description'   => 'nullable|string',
        ]);

        $ingredient = Ingredient::create($validated);

        return response()->json(['data' => $ingredient, 'message' => 'Bahan baku berhasil dibuat'], 201);
    }

    public function show(Ingredient $ingredient): JsonResponse
    {
        return response()->json(['data' => $ingredient->load('recipes')]);
    }

    public function update(Request $request, Ingredient $ingredient): JsonResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'unit'          => 'required|string|max:50',
            'stock'         => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'description'   => 'nullable|string',
        ]);

        $ingredient->update($validated);

        return response()->json(['data' => $ingredient, 'message' => 'Bahan baku berhasil diperbarui']);
    }

    public function destroy(Ingredient $ingredient): JsonResponse
    {
        if ($ingredient->recipes()->exists()) {
            return response()->json(['message' => 'Bahan baku masih digunakan dalam resep'], 422);
        }

        $ingredient->delete();

        return response()->json(['message' => 'Bahan baku berhasil dihapus']);
    }
}