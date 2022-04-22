<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Read all products
        // return Product::orderBy('id', 'desc')->paginate(10);
        return Product::with('users', 'users')->orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Check Admin

        $user = auth()->user();

        if ($user->tokenCan("1")) {
            $request->validate([
                'name' => 'required|min:5',
                'slug' => 'required',
                'price' => 'required',
            ]);

            // กำหนดตัวแปรรับค่าจากฟอร์ม
            $data_product = array(
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'user_id' => $user->id
            );

            // รับไฟล์ภาพเข้ามา
            $image = $request->file('file');

            // ตรวจสอบว่าผู้ใช้มีการอัพโหลดภาพเข้ามาหรือไม่ 
            if(!empty($image)){
                // เปลี่ยนชื่อรูปภาพ
                $file_name = "product_".time().".".$image->getClientOriginalExtension();
                
                // กำหนดขนาดความกว้าง สูง ของภาพ
                $imgWidth = 400;
                $imgHeight = 400;
                $folderUpload = public_path('/images/products/thumbnail');
                $path = $folderUpload."/".$file_name;

                $img = Image::make($image->getRealPath());
                $img->orientate()->fit($imgWidth, $imgHeight, function($constaraint){
                    $constaraint->upsize();
                });
                $img->save($path);

                // อัพโหลดภาพต้นฉบับ เข้า Folder Original
                $destinationPath = public_path('/images/products/original');
                $image->move($destinationPath, $file_name);

                $data_product['image'] = url('/').'/images/products/thumbnail/'.$file_name;

            }else{
                $data_product['image'] = url('/').'/images/products/thumbnail/no_img.jpeg';
            }

            // create data to table product
            return Product::create($data_product);

            // return response( $data_product, 201 );
            // return response($request->all(),201);
            // return Product::create($request->all());
        } else {
            return [
                'status' => 'Permission denide to create.'
            ];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if($user->tokenCan("1")) {

            $request->validate([
                'name' => 'required|min:5',
                'slug' => 'required',
                'price' => 'required',
            ]);

         
            $data_product = array(
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'user_id' => $user->id
            );

            // รับไฟล์ภาพเข้ามา
            $image = $request->file('file');

            // ตรวจสอบว่าผู้ใช้มีการอัพโหลดภาพเข้ามาหรือไม่ 
            if(!empty($image)){
                // เปลี่ยนชื่อรูปภาพ
                $file_name = "product_".time().".".$image->getClientOriginalExtension();
                
                // กำหนดขนาดความกว้าง สูง ของภาพ
                $imgWidth = 400;
                $imgHeight = 400;
                $folderUpload = public_path('/images/products/thumbnail');
                $path = $folderUpload."/".$file_name;

                $img = Image::make($image->getRealPath());
                $img->orientate()->fit($imgWidth, $imgHeight, function($constaraint){
                    $constaraint->upsize();
                });
                $img->save($path);

                // อัพโหลดภาพต้นฉบับ เข้า Folder Original
                $destinationPath = public_path('/images/products/original');
                $image->move($destinationPath, $file_name);

                $data_product['image'] = url('/').'/images/products/thumbnail/'.$file_name;

            }else{
                $data_product['image'] = url('/').'/images/products/thumbnail/no_img.jpeg';
            }

            $product = Product::find($id);
            $product->update($data_product);
            
            return $product;
        }else{
            return [
                'status' => 'Permission denied to change.'
            ];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  string $keyword
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function search($keyword)
     {
        return Product::with('users', 'users')
            ->where('name','like','%'.$keyword.'%')
            ->orderBy('id', 'desc')->paginate(10);  
     }

}
