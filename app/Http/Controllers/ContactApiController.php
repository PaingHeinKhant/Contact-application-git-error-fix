<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Photo;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::latest("id")
//            ->where("user_id",Auth::id())
            ->paginate(10);
        return response()->json($contacts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        return  Auth::user();

        $request->validate([
            "firstName" => "required|min:4",
            "lastName" => "required|min:3",
            'phone'=>'required|numeric',
            'company'=>'nullable',
            'birthday'=>'nullable',
            'email' => 'nullable',
            "image" => "file|mimes:jpeg,png|max:512",
        ]);

//        return $request;

        if ($request->image !=null){
            $newName = uniqid()."_image.".$request->file('image')->extension();
            $request->file('image')->storeAs("public",$newName);
        }

        $contact = Contact::create([
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            'phone'=>$request->phone,
            'company'=>$request->company,
            'birthday'=>$request->birthday,
            'email' => $request->email,
            'image' => asset(Storage::url($newName)),
            'user_id'=> Auth::id(),
        ]);


        return response()->json($contact);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if (is_null($contact)){
            return response()->json(['message'=>'Contact is not found'],404);
        }
        return $contact;

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

        $request->validate([
            "firstName" => "nullable|min:4",
            "lastName" => "nullable|min:3",
            'phone'=>'nullable|numeric',
            'company'=>'nullable',
            'birthday'=>'nullable',
            'email' => 'nullable',
        ]);

        $contact = Contact::find($id);

        if (is_null($contact)){
            return response()->json(['message'=>'Contact is not found'],404);
        }

        if ($request->has('firstName')){
            $contact->firstName =  $request->firstName;
        }
        if ($request->has('lastName')){
            $contact->lastName =  $request->lastName;
        }
        if ($request->has('company')){
            $contact->company =  $request->company;
        }
        if ($request->has('birthday')){
            $contact->birthday =  $request->birthday;
        }
        if ($request->has('email')){
            $contact->email =  $request->email;
        }
        $contact->update();

        return response()->json($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (is_null($contact)){
            return response()->json(['message'=>'Contact is not found'],404);
        }

        $contact->delete();
        return response()->json(['message'=>'Contact is deleted'],204);
    }

    public function multipleDestroy(Request $request){
//        return "hello";
        $arr = $request->multipleFormCheck;
//        return $arr;
        Contact::destroy(collect($arr));
        return response()->json(['message'=>'multiple delete is success'],204);
    }
}
