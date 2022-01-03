<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Model\Email_format;

/*
 * Email Format Controller is used to the Show Edit and update the Email Formats.
 */
class Email_formatController extends Controller {
    /*
     * All the Emails fetch and show to the Email Formates view pages.
     */

    public function __construct()
    {
        $this->middleware('permission:email-format-list', ['only' => ['index']]);
        $this->middleware('permission:email-format-edit', ['only' => ['editemail', 'update']]);
    }

    public function index() {
        $eformat = Email_format::all();
        return view('admin.email_format.index', ['eformat' => $eformat]);
    }

    /*
     * This Funciton is getting Email Formate data and view in Edit Email Formate Pages
     */
    public function editemail($id) {
        $email_format = Email_format::where('id', $id)->first();

        $email_array = array(
            'id' => $email_format->id,
            'title' => $email_format->title,
            'variables' => $email_format->variables,
            'subject' => $email_format->subject,
            'emailformat' => $email_format->emailformat,
        );

        return view('admin.email_format.edit', ['email_array' => $email_array]);
    }

    /*
     * All the records update into the database to use this function
     */
    public function update(Request $request) {
        $rules = array(
            'subject'=>'required',
            'emailformat' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('admin.editemail',['id',$request->get('id')])
                            ->withErrors($validator);
        } else {
            $emailupdate = Email_format::where('id', $request->get('id'))->update(['subject'=>$request->get('subject'),'emailformat' => $request->get('emailformat')]);

            if ($emailupdate) {
                return redirect()->route('admin.email_format')
                                ->with('success', 'Record Updated Successfully!');
            } else {
                return redirect()->back()->with("error", "Not Change Any Values!");
            }
        }
    }

}
