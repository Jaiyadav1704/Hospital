<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Doctor;

use App\Models\Book;



class HomeController extends Controller
{
    
    public function redirect(){
        if(Auth::id()){
            if(Auth::user()->usertype=='0'){
                $doctor = doctor::all();
                return view('user.home', compact('doctor'));
            }
            else{
                return view('admin.home');
            }
        }

        else{
            return redirect()->back();
        }
    }

    public function index(){

        if(Auth::id()){
            return redirect('home');
        }
        else{
        $doctor = doctor::all();

        return view('user.home', compact('doctor'));
        }
    }

    public function book(Request $request){

        $data = new book;

        $data->name = $request->name;
        $data->doctor = $request->doctor;
        $data->speciality = $request->speciality;
        $data->phone = $request->phone;
        $data->symptoms = $request->symptoms;
        $data->date = $request->date;
        $data->status = "In progress";

        if(Auth::id()){
            $data->user_id =Auth::user()->id;
        }

        $data->save();

        return redirect()->back()->with('message', 'Appointment Request Successfull. We will contact with you soon');

    }

    public function myappointment(){
        if(Auth::id()){

            if(Auth::user()->usertype == 0){
            $userid = Auth::user()->id;
            $appoint = book::where('user_id', $userid)->get();
            return view('user.my_appointment', compact('appoint'));
            }

            else{
                return redirect()->back();
            }
        }

        else{
            return redirect()->back();
        }
    }

    public function cancel_appoint($id){
        $data  = book::find($id);

        $data->delete();

        return redirect()->back();
    }
}
