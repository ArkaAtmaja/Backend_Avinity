<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(): JsonResponse
    {
        $recipes = Recipe::with(['product', 'ingredients'])->orderBy('name')->get();

        return response()->json(['data' => $recipes]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'                         => 'required|string|max:255',
            'product_id'                   => 'nullable|exists:products,id',
            'description'                  => 'nullable|string',
            'serving_size'                 => 'required|integer|min:1',
            'instructions'                 => 'nullable|string',
            'ingredients'                  => 'nullable|array',
            'ingredients.*.ingredient_id'  => 'required|exists:ingredients,id',
            'ingredients.*.quantity'       => 'required|numeric|min:0.001',
            'ingredients.*.unit'           => 'required|string',
        ]);

        $recipe = Recipe::create([
            'name'         => $validated['name'],
            'product_id'   => $validated['product_id'] ?? null,
            'description'  => $validated['description'] ?? null,
            'serving_size' => $validated['serving_size'],
            'instructions' => $validated['instructions'] ?? null,
        ]);

        if (!empty($validated['ingredients'])) {
            $sync = collect($validated['ingredients'])->mapWithKeys(fn ($item) => [
                $item['ingredient_id'] => [
                    'quantity' => $item['quantity'],
                    'unit'     => $item['unit'],
                ],
            ])->toArray();

            $recipe->ingredients()->sync($sync);
        }

        return response()->json([
            'data'    => $recipe->load(['product', 'ingredients']),
            'message' => 'Resep berhasil dibuat',
        ], 201);
    }

    public function show(Recipe $recipe): JsonResponse
    {
        return response()->json(['data' => $recipe->load(['product', 'ingredients'])]);
    }

    public function update(Request $request, Recipe $recipe): JsonResponse
    {
        $validated = $request->validate([
            'name'                        => 'required|string|max:255',
            'product_id'                  => 'nullable|exists:products,id',
            'description'                 => 'nullable|string',
            'serving_size'                => 'required|integer|min:1',
            'instructions'                => 'nullable|string',
            'ingredients'                 => 'nullable|array',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity'      => 'required|numeric|min:0.001',
            'ingredients.*.unit'          => 'required|string',
        ]);

        $recipe->update([
            'name'         => $validated['name'],
            'product_id'   => $validated['product_id'] ?? null,
            'description'  => $validated['description'] ?? null,
            'serving_size' => $validated['serving_size'],
            'instructions' => $validated['instructions'] ?? null,
        ]);

        if (isset($validated['ingredients'])) {
            $sync = collect($validated['ingredients'])->mapWithKeys(fn ($item) => [
                $item['ingredient_id'] => [
                    'quantity' => $item['quantity'],
                    'unit'     => $item['unit'],
                ],
            ])->toArray();

            $recipe->ingredients()->sync($sync);
        }

        return response()->json([
            'data'    => $recipe->load(['product', 'ingredients']),
            'message' => 'Resep berhasil diperbarui',
        ]);
    }

    public function destroy(Recipe $recipe): JsonResponse
    {
        $recipe->ingredients()->detach();
        $recipe->delete();

        return response()->json(['message' => 'Resep berhasil dihapus']);
    }
}