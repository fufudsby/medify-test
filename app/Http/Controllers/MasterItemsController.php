<?php

namespace App\Http\Controllers;

use App\Exports\MasterItemsExport;
use App\Models\Category;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin ?? 0;
        $hargamax = $request->hargamax ?? PHP_INT_MAX;

        $data_search = MasterItem::query();

        if (! empty($kode)) {
            $data_search = $data_search->where('kode', $kode);
        }
        if (! empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%'.$nama.'%');
        }

        $data_search = $data_search->where('harga_beli', '>=', $hargamin)->where('harga_beli', '<=', $hargamax);
        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')->orderBy('id')->get();

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
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['categories'] = Category::orderBy('name')->get();

        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('categories')
            ->where('kode', $kode)
            ->firstOrFail();

        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required',
            'harga_beli' => 'required|numeric|min:0',
            'laba' => 'required|numeric|min:0',
            'supplier' => 'required',
            'jenis' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $foto_path = null;

        if ($request->hasFile('foto')) {
            $foto_path = $request->file('foto')->store('master-items', 'public');
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        if ($foto_path !== null) {
            $foto_lama = $data_item->foto;

            $data_item->foto = $foto_path;

            if ($foto_lama) {
                Storage::disk('public')->delete($foto_lama);
            }
        }

        $data_item->save();
        $data_item->categories()->sync($request->categories ?? []);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();

        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);

        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);

        return $array[$random];
    }

    public function export()
    {
        return Excel::download(
            new MasterItemsExport,
            'master-items-'.date('Y-m-d').'.xlsx'
        );
    }
}
