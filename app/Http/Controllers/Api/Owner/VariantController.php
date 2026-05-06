<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index(Product $product): JsonResponse
    {
        return response()->json([
            'data' => $product->variants()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'price_modifier' => 'nullable|numeric',
            'stock'          => 'required|integer|min:0',
            'is_active'      => 'boolean',
        ]);

        $variant = $product->variants()->create($validated);

        return response()->json(['data' => $variant, 'message' => 'Varian berhasil dibuat'], 201);
    }

    public function show(Product $product, Variant $variant): JsonResponse
    {
        abort_if($variant->product_id !== $product->id, 404);

        return response()->json(['data' => $variant]);
    }

    public function update(Request $request, Product $product, Variant $variant): JsonResponse
    {
        abort_if($variant->product_id !== $product->id, 404);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'price_modifier' => 'nullable|numeric',
            'stock'          => 'required|integer|min:0',
            'is_active'      => 'boolean',
        ]);

        $variant->update($validated);

        return response()->json(['data' => $variant, 'message' => 'Varian berhasil diperbarui']);
    }

    public function destroy(Product $product, Variant $variant): JsonResponse
    {
        abort_if($variant->product_id !== $product->id, 404);
        $variant->delete();

        return response()->json(['message' => 'Varian berhasil dihapus']);
    }
}