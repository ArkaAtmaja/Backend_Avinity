<?php

// routes/api.php
// Tambahkan di dalam group middleware auth (Sanctum/Passport) sesuai setup kamu

use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\VariantController;
use App\Http\Controllers\Owner\IngredientController;
use App\Http\Controllers\Owner\RecipeController;
use Illuminate\Support\Facades\Route;

Route::prefix('owner')->group(function () {

    // ── Inventories ──────────────────────────────
    Route::apiResource('ingredients', IngredientController::class);
    Route::apiResource('recipes', RecipeController::class);

    // ── Menus ─────────────────────────────────────
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);

    // Variants bersarang di bawah product
    Route::apiResource('products.variants', VariantController::class)
        ->shallow();
});

/*
 * Endpoint yang dihasilkan:
 *
 * GET    /api/owner/ingredients
 * POST   /api/owner/ingredients
 * GET    /api/owner/ingredients/{ingredient}
 * PUT    /api/owner/ingredients/{ingredient}
 * DELETE /api/owner/ingredients/{ingredient}
 *
 * GET    /api/owner/recipes
 * POST   /api/owner/recipes
 * GET    /api/owner/recipes/{recipe}
 * PUT    /api/owner/recipes/{recipe}
 * DELETE /api/owner/recipes/{recipe}
 *
 * GET    /api/owner/categories
 * POST   /api/owner/categories
 * GET    /api/owner/categories/{category}
 * PUT    /api/owner/categories/{category}
 * DELETE /api/owner/categories/{category}
 *
 * GET    /api/owner/products
 * POST   /api/owner/products
 * GET    /api/owner/products/{product}
 * PUT    /api/owner/products/{product}
 * DELETE /api/owner/products/{product}
 *
 * GET    /api/owner/products/{product}/variants  (index)
 * POST   /api/owner/products/{product}/variants  (store)
 * GET    /api/owner/variants/{variant}            (show  - shallow)
 * PUT    /api/owner/variants/{variant}            (update - shallow)
 * DELETE /api/owner/variants/{variant}            (destroy - shallow)
 */