<?php

namespace App\Http\Controllers;

use App\Http\Resources\NavlinkResource;
use App\Models\Navlink;
use App\Http\Requests\StoreNavlinkRequest;
use App\Http\Requests\UpdateNavlinkRequest;
use Illuminate\Http\Request;
use Validator;

class NavlinkController extends Controller
{
    public function index()
    {
        return NavlinkResource::collection(Navlink::all());
    }

    public function store(Request $request)
    {
        $input = $request->all();

        if (is_null($input)) {
            return response()->json([
                'status' => FALSE,
                'message' => 'All fields required!'
            ]);
        }

        $validated = Validator::make($input, [
            'title' => 'required|string|max:10',
            'url' => 'required|string|max:255'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => FALSE,
                'message' => $validated->errors()
            ]);
        }

        if (Navlink::create($input)) {
            // respons berhasil
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully store new Navlink'
            ], 201);
        } else {
            // respons gagal
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed store Navlink'
            ], 400);
        }
    }

    public function show($id)
    {
        $navlink = Navlink::find($id);

        if (is_null($navlink)) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Data not found'
            ]);
        }

        return response()->json([
            'status' => TRUE,
            'data' => $navlink
        ]);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $navlink = Navlink::find($id);

        // default values
        $input['title'] = $input['title'] ?? $navlink->title;
        $input['url'] = $input['url'] ?? $navlink->url;

        if (is_null($input)) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Minimum one field required!'
            ]);
        }

        $validated = Validator::make($input, [
            'title' => 'required|string|max:10',
            'url' => 'required|string|max:255'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => FALSE,
                'message' => $validated->errors()
            ]);
        }

        $navlink->update($input);

        return response()->json([
            'status' => TRUE,
            'message' => 'Successfully update Navlink',
            'data' => $input
        ], 201);
    }

    public function destroy($id) 
    {
        $navlink = Navlink::find($id);

        if (is_null($navlink)) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Navlink not found'
            ]);
        }

        $navlink->delete();

        return response()->json([
            'status' => TRUE,
            'message' => "Successfully deleted Navlink"
        ]);
    }
}
