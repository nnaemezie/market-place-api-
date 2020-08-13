<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Model\Product;
use App\User;
use Illuminate\Support\Facades\Storage;
use Validator;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductCollection::collection(Product::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string', 'max:255'],
            'photo' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $file = $request->file('photo');
        $photo_url = "photo".uniqid().md5(now()).'.'.$file->getClientOriginalExtension();
        $file->move(public_path("product_image"),$photo_url);
        $request['photo'] = $photo_url;

        Product::create([
            'user_id' => Auth::id(),
            'type' => $request['type'],
            'name' => $request['name'],
            'desc' => $request['desc'],
            'photo' => $photo_url,
        ]);

        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductCollection($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        $rules = [
            'type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string', 'max:255'],
            'photo' => ['sometimes'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        if ($request->hasFile('photo')){

            $file = $request->file('photo');
            $photo_url = "photo".uniqid().md5(now()).'.'.$file->getClientOriginalExtension();
            $file->move(public_path("product_image"),$photo_url);
            $request['photo'] = $photo_url;

            Product::find($product)->update([
                'user_id' => Auth::id(),
                'type' => $request['type'],
                'name' => $request['name'],
                'desc' => $request['desc'],
                'photo' => $photo_url,
            ]);
        }else{
            Product::find($product)->update([
                'user_id' => Auth::id(),
                'type' => $request['type'],
                'name' => $request['name'],
                'desc' => $request['desc'],
            ]);
        }

        return response()->json(['message'=>"Item updated successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($photo = Product::findOrFail($id)) {
            if (Product::destroy($id)){
                $file = 'product_image/'.$photo->photo;
                File::delete($file);
                return response()->json(['message' => "Item deleted successfully"], 200);
            }
        }
        return response()->json(['message' => "Deleting item failed"], 400);
    }
}
