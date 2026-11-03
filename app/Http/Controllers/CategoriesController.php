<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return view('categories.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Category::query();

        if (! empty($kode)) {
            $data_search = $data_search->where('kode', $kode);
        }
        if (! empty($nama)) {
            $data_search = $data_search->where('name', 'LIKE', '%'.$nama.'%');
        }

        $data_search = $data_search->select('kode', 'name')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search,
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = Category::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;

        return view('categories.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = Category::with('masterItems')
            ->where('kode', $kode)
            ->firstOrFail();

        return view('categories.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        if ($method == 'new') {
            $data_item = new Category;
            $kode = Category::count('id') + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = Category::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->kode = $kode;
        $data_item->name = $request->nama;

        $data_item->save();

        return redirect('categories');
    }

    public function delete($id)
    {
        Category::find($id)->delete();

        return redirect('categories');
    }

    public function downloadPdf($kode)
    {
        $category = Category::with('masterItems')
            ->where('kode', $kode)
            ->firstOrFail();

        $pdf = Pdf::loadView('categories.single.pdf', [
            'data' => $category,
            'printed_at' => now(),
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('kategori-'.$category->kode.'.pdf');
    }
}
