<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Catalogue;
use App\Model\CatalogueImages;
use App\Lib\UploadFile;
use Illuminate\Support\Facades\Validator;

class CatalogueController extends Controller
{
    public $data;
    public $upload_file;

    public function __construct()
    {
        $this->data['module_title'] = "Catalogue";
        $this->data['module_link'] = "admin.catalogue";
        $this->upload_file = new UploadFile();

        $this->middleware('permission:admin-catalogue-list', ['only' => ['index']]);
        $this->middleware('permission:admin-catalogue-add', ['only' => ['add_catalogue', 'save_catelogue']]);
        $this->middleware('permission:admin-catalogue-edit', ['only' => ['edit_catalogue', 'change_catalogue_status']]);
        $this->middleware('permission:admin-catalogue-delete', ['only' => ['delete_catalogue']]);
    }

    public function index(){
        $this->data['page_title'] = "Catalogue";
        $this->data['catalogue'] = Catalogue::with(['get_catelogue_images'])->get();
        // dd($this->data);
        return view('admin.catalogue.index', $this->data);
    }

    public function add_catalogue(){
        $this->data['page_title'] = "Add Catalogue";
        return view('admin.catalogue.create', $this->data);
    }

    public function save_catelogue(Request $request){
        $rules = array(
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'images' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please follow validation rules.');
        }
        if($catelogue_id = Catalogue::create($request->all())){
            if ($request->hasFile('images')) {
                $catelogue_img = [];
                foreach ($request->file('images') as $key => $value) {
                    $catelogue_img['catalogue_id'] = $catelogue_id->id;
                    // $catelogue_img['image'] = $this->upload_file->upload_image($value,'public/catelogue_images');

                    $catelogue_img['image'] = $this->upload_file->upload_s3_file_multiple($value, 'catelogue_images',"600","300");
                    CatalogueImages::insert($catelogue_img);
                }
            }
            return redirect()->route('admin.catalogue')->with('success', 'Catalogue inserted successfully');
        }
        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function edit_catalogue($id){
        // $this->middleware('permission:admin-catalogue-edit');
        $this->data['page_title'] = "Edit Catalogue";
        $this->data['catalogue'] = Catalogue::find($id);
        return view('admin.catalogue.edit', $this->data);
    }

    public function update_catelogue(Request $request){
        $catelogue_id = $request->get('id');
        $catelogue = Catalogue::find($catelogue_id);

        $catelogue->name = $request->get('name');
        $catelogue->description = $request->get('description');
        $catelogue->amount = $request->get('amount');

        if($catelogue->save()){
            if ($request->hasFile('images')) {
                $this->remove_catalogue_images($catelogue_id);
                $catelogue_img = [];
                foreach ($request->file('images') as $key => $value) {
                    $catelogue_img['catalogue_id'] = $catelogue_id;
                    // $catelogue_img['image'] = $this->upload_file->upload_image($value, 'public/catelogue_images');

                    $catelogue_img['image'] = $this->upload_file->upload_s3_file_multiple($value, 'catelogue_images');
                    CatalogueImages::insert($catelogue_img);
                }
            }
            return redirect()->route('admin.catalogue')->with('success', 'Catalogue inserted successfully');
        }
        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function change_catalogue_status($id,$status){
        if(Catalogue::whereId($id)->update(['status'=>$status])){
            return redirect()->route('admin.catalogue')->with('success', 'Status updated successfully');
        }
        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function delete_catalogue($id){
        $this->remove_catalogue_images($id);
        if(Catalogue::whereId($id)->delete()){
            return redirect()->route('admin.catalogue')->with('success', 'Data deleted successfully');
        }
        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function remove_catalogue_images($id){
        $images = CatalogueImages::where('catalogue_id', $id)->get()->toArray();
        if(CatalogueImages::where('catalogue_id', $id)->delete()){
            if(count($images)){
                foreach ($images as $key => $value) {
                    // $this->upload_file->delete_file_folder($value['image']);
                    $response = $this->upload_file->delete_s3_file($value['image']);
                }
            }
        }
    }
}
