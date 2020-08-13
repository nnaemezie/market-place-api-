<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductCollection;
use App\Model\Product;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $user =  User::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['password']),
        ]);

        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function login(Request $request){
        // vaildates http request
        $validator = Validator::make($request->all(),[
            'phone' => ['required'],
            'password' => ['required'],
        ]);
        if($validator->fails()){
            // report error
            return response()->json(['message'=>$validator->errors()],400);
        }else{
            // checks users credentials
            if(Auth::attempt(['phone'=>$request->phone,'password'=>$request->password])){
                $user = Auth()->user();
                $success = [
                    'token'=>$user->createToken('myapp')->accessToken,
                    'name'=>Auth()->user()->name,
                    'phone'=>Auth()->user()->phone,
                ];
                return response()->json(['response'=>$success],200);
            }else{
                // return login error
                return response()->json(['message'=>'Invalid credentials'],400);
            }
        }
    }

    public function products(){
        $user_id = Auth::id();
        //$products = Product::where('user_id', $user_id)->get();
        $products = ProductCollection::collection(Product::where('user_id', $user_id)->get());
        return response()->json(['response'=>$products],200);
    }

    public function offices(){
        return User::all('name', 'phone');
    }
}
