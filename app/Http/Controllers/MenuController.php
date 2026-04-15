<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        return Menu::all();
    }

    public function store(Request $request)
    {
        return Menu::create($request->all());
    }

    // UPDATE (INI YANG KAMU KURANG)
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'message' => 'Menu tidak ditemukan'
            ], 404);
        }

        $menu->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
        ]);

        return response()->json([
            'message' => 'Berhasil diupdate',
            'data' => $menu
        ]);
    }

    public function destroy($id)
    {
        return Menu::destroy($id);
    }

    public function search(Request $request)
    {
        $q = $request->query('q');

        return Menu::where('nama', 'like', "%$q%")
            ->orWhere('kategori', 'like', "%$q%")
            ->get();
    }
}
