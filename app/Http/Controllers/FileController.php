<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadItem()
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);

        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/items'), $imageName);

        $host = request()->getSchemeAndHttpHost();
        return "$host/images/items/$imageName";
    }

    public function uploadCar()
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/cars'), $imageName);

        $host = request()->getSchemeAndHttpHost();
        return "$host/images/cars/$imageName";
    }
}
