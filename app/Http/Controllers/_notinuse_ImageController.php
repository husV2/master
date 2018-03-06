<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('webadmin');
    }
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
     * Stores an image to a folder *specified in the request (TODO)*. Validates for size and mime types.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            
        $validator = Validator::make($request->all(), [
                'pic' => 'required | mimes:jpeg,jpg,png,gif,bmp | max:1000'
        ]);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
            //return "Not the correct type";
        }
        
        $name = str_random(10) . '.' . $request->file('pic')->getClientOriginalExtension();
        $request->file('pic')->move(
              storage_path('app') . '/images/'.$request->dir, $name
        );

        // This can be used to clear to folder for testing purposes:
        // 
        //  
//        $files = scandir(storage_path('app') . '/uploads');
//        
//        foreach($files as $file)
//        {
//            if($file !== "." && $file !== '..')
//            {
//                unlink(storage_path('app') . '/uploads/'.$file);
//            }
//        }

        
        return "No errors";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
