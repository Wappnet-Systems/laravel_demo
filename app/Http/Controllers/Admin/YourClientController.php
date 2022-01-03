<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\YourClients;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Common_query;
use App\Lib\CommonTask;
use Image;
use App\Lib\UploadFile;
use DataTables;

class YourClientController extends Controller
{
    public $data;
    public $common_task;
    public $upload_file;

    public function __construct()
    {
        $this->common_task = new CommonTask();
        $this->upload_file = new UploadFile();
        $this->data['module_title'] = "Clients";
        $this->data['module_link'] = "admin.clients";

        $this->middleware('permission:client-list', ['only' => ['index']]);
        $this->middleware('permission:client-add', ['only' => ['add_client','save_client']]);
        $this->middleware('permission:client-edit', ['only' => ['change_client_status','edit_client','update_client']]);
    }

    public function index(){
        $this->data['page_title'] = "Clients";
        return view('admin.your_clients.index', $this->data);
    }

    public function get_client_list(){
        $client = YourClients::get();
        // orderBy('id','DESC')->
        return Datatables::of($client)
                ->editColumn('client_image', function ($client) {
                            if($client->client_image){
                                return $this->upload_file->get_s3_file_path("client_image",$client->client_image);
                            }else{
                                return "";
                            }
                        })
                ->make(true);
    }

    public function add_client(){
        $this->data['page_title'] = "Add Client";
        return view('admin.your_clients.create', $this->data);
    }

    public function save_client(Request $request){
        $rules = array(
            'client_title' => 'required',
            'client_link' => 'required',
            'client_image' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please follow validation rules.');
        }

        $add_data = [
            'client_title' => $request->get('client_title'),
            'client_link' => $request->get('client_link'),
        ];

        if($request->hasFile('client_image')) {
            $add_data['client_image'] = $this->upload_file->upload_s3_file($request, 'client_image',"client_image","150","150");
        }
        if(YourClients::insert($add_data)){
            return redirect()->route('admin.clients')->with('success', 'Client inserted successfully');
        }

        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function change_client_status($id,$status){
        $update_data = [
            'status' => $status,
            'updated_ip' => request()->ip(),
        ];
        if(YourClients::whereId($id)->update($update_data)){
            return redirect()->route('admin.clients')->with('success', 'Status changed successfully');
        }

        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function edit_client($id){
        $this->data['page_title'] = "Edit Client";
        $this->data['client'] = YourClients::whereId($id)->first();
        return view('admin.your_clients.edit', $this->data);
    }

    public function update_client(Request $request){
        $rules = array(
            'client_title' => 'required',
            'client_link' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please follow validation rules.');
        }

        $add_data = [
            'client_title' => $request->get('client_title'),
            'client_link' => $request->get('client_link'),
        ];

        if($request->hasFile('client_image')) {

            if($client_image = YourClients::whereId($request->get('id'))->value('client_image')){
                $response = $this->upload_file->delete_s3_file($client_image);
            }

            $add_data['client_image'] = $this->upload_file->upload_s3_file($request, 'client_image',"client_image","150","150");
        }
        if(YourClients::whereId($request->get('id'))->update($add_data)){
            return redirect()->route('admin.clients')->with('success', 'Client updated successfully');
        }

        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }
}
