<?php

namespace App\Http\Controllers;

use App\Http\Resources\IconResource;
use App\Models\Icon;
use Illuminate\Http\Request;
use Validator;

class IconController extends Controller
{
    public function index()
    {
        return IconResource::collection(Icon::all());
    }

    public function store(Request $request)
    {
        $input = $request::all();

        $validasi = Validator::make($input, [
            'title' => 'required|max:255',
            'url' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => FALSE,
                'msg' => $validasi->errors(),
            ], 400);
        }

        if ($request::file('url')->isValid()) {
            $url = $request::file('url');
            $extention = $url->getClientOriginalExtension();
            $namaFoto = "image/icon/" . date('YmdHis') . "." . $extention; // Ubah direktori sesuai kebutuhan Anda
            $upload_path = storage_path('app/public/' . $namaFoto);
            $url->storeAs('public', $namaFoto);

            $input['url'] = $namaFoto;
        }

        if (Icon::create($input)) {
            // respons berhasil
            return response()->json([
                'status' => TRUE,
                'msg' => 'Icon Berhasil Disimpan',
            ], 201);
        } else {
            // respons gagal
            return response()->json([
                'status' => FALSE,
                'msg' => 'Icon Gagal Disimpan',
            ], 400);
        }
    }

    public function show($id)
    {
        $icon = Icon::find($id);

        return response()->json([
            'status' => TRUE,
            'data' => $icon
        ]);
    }

    public function update(Request $request, $id)
    {
        // Search icon by id
        $icon = Icon::find($id);

        // Check if icon available
        if (is_null($icon)) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Icon Not Found'
            ]);
        }

        // Catch all request fields
        $input = $request->all();

        // Default value(s)
        $input['title'] = $input['title'] ?? $icon->title;

        // Validation rules
        $rules = [
            'title' => ['required', 'string']
        ];

        if ($request->hasFile('url')) {
            $rules['url'] = ['image', 'mimes:jpeg,jpg,png', 'max:2048'];

            $url = $request->file('url');
            $extention = $url->getClientOriginalExtension();
            $pathname = 'image/icon/' . date('YmdHis') . "." . $extention;
            $upload_path = storage_path('app/public/' . $pathname);
            $url->storeAs('public', $pathname);

            $input['url'] = $pathname;

            $old_icon = storage_path("app/public/{$icon->title}");

            if (file_exists($old_icon)) {
                unlink($old_icon);
            }
        }

        $validated = Validator::make($input, $rules);

        // validate inputs
        if ($validated->fails()) {
            return response()->json([
                'status' => FALSE,
                'message' => $validated->errors()
            ]);
        }

        $icon->update($input);

        return response()->json([
            'status' => TRUE,
            'message' => "Data berhasil diupdate"
        ]);
    }

    public function destroy($id) 
    {
        $icon = Icon::find($id);

        // Check if icon available
        if (is_null($icon)) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Icon Not Found'
            ]);
        }
        
        $old_icon = storage_path("app/public/{$icon->title}");

        if (file_exists($old_icon)) {
            unlink($old_icon);
        }

        $icon->delete();

        return response()->json([
            'status' => TRUE,
            'message' => "Icon berhasil diupdate"
        ]);
    }
}
