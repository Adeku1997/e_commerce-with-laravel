<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * show create product form
     *
     */
    public function create(): View
    {
        return view(
            'product.create',
            [
                'categories' => Category::all(),
                'brands' => Brand::all(),
            ]
        );
    }

    /**
     * validate and store products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store( Request $request): RedirectResponse
    {
        $request->validate([
            'subcategory'=> 'required',
            'name' => 'required',
            'brand' => 'required',
            'file' => 'required|mimes:jpg,png,jpeg,webp',
            'price'=>'required',
            'description' => 'required'
        ]);

        $newImageName = time() . '-' .$request->name . '.' . $request->file->extension();
        $request->file->move(public_path('images/productImgs'),$newImageName);

        Product::create([
            'sub_category_id' => $request['subcategory'],
            'name' => $request['name'],
            "brand_id" => $request['brand'],
            'price' =>$request['price'],
            'description' =>$request['description'],
            'file_path' => $newImageName,
        ]);

        return redirect()->route('subcategory.show',$request['subcategory']);
    }
}
