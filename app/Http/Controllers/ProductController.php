<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products = Product::latest()->paginate(5);
        // return view('products.index',compact('products'))
        //     ->with('i', (request()->input('page', 1) - 1) * 5);

        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
            'files.*' => 'mimes:pdf|max:2000',
        ]);

        
                    
            $dm = Product::create([
                'name' => $request->name,
                'detail' => $request->detail
            ]);

            if ($request->exists('files')) 
            {
                $images = $request->file('files');

                // if (is_array($images) || is_object($images))
                // {
                    foreach($images as $image) 
                    {        
                        $fileName = time().'_'.$image->getClientOriginalName();
                        $filePath = $image->storeAs('uploads', $fileName, 'public');
        
                        $fm = new File([
                            'filename' => $fileName,
                            'path' => '/storage/' . $filePath
                        ]);
                        
                        $dm->file()->save($fm);
                    }
                // }
            }

            return redirect()->route('products.index')->with('success', 'Created successfully!');
        

        // Product::create($request->all());

        // if ($request->exists('files')) 
        //     {
        //         $images = $request->file('files');
    
        //         foreach($images as $image) 
        //         {        
        //             $fileName = time().'_'.$image->getClientOriginalName();
        //             $filePath = $image->storeAs('uploads', $fileName, 'public');
    
        //             $fm = new File([
        //                 'filename' => $fileName,
        //                 'path' => '/storage/' . $filePath
        //             ]);
    
        //             Product::create($request->all())->file()->save($fm);
        //         }
        //     }

        // return redirect()->route('products.index')
        //     ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        $files = DB::table('files')
                 ->select('filename','id', 'path', DB::raw("date_format(created_at, '%Y-%m-%d %r') AS created_at"))
                 ->where('product_id', $product->id)
                 ->get();
                 
        return view('products.show', compact('product', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
