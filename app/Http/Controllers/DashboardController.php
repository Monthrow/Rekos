<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index(Request $request) {
        $keyword = $request->input('q', '');
        
        if ($keyword !== '') {
            $barangs = Barang::where(function($query) use ($keyword) {
                            $query->where('nama_barang', 'like', "%$keyword%")
                                  ->orWhere('deskripsi', 'like', "%$keyword%");
                        })
                        ->where('status_barang', '!=', 'terjual')
                        ->orderBy('id_barang', 'desc')
                        ->get();
        } else {
            $barangs = Barang::where('status_barang', '!=', 'terjual')
                        ->orderBy('id_barang', 'desc')
                        ->get();
        }

        return view('dashboard', compact('barangs', 'keyword'));
    }
}