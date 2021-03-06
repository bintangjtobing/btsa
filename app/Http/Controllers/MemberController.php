<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MemberModel;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\messagesDB;

class MemberController extends Controller
{
    public function index()
    {
        $data_member = DB::table('users')
            ->select('users.*')
            ->get();
        return view('member.index', ['data_member' => $data_member]);
    }
    public function online()
    {
        $users = MemberModel::where('id', '!=', Auth::id())->orderBy('nama_lengkap', 'ASC')->get();
        return view('member.online', ['users' => $users]);
    }
    public function getMessage($id)
    {
        // return $id;
        // getting all message for selected user
        // getting those message which is from Auth::id and to = user id
        $my_id = Auth::user()->id;
        $messages = messagesDB::where(function ($query) use ($id, $my_id) {
            $query->where('from_id', $my_id)->where('to_id', $id);
        })->orWhere(function ($query) use ($id, $my_id) {
            $query->where('from_id', $id)->where('to_id', $my_id);
        })->get();
        // return view('member.messages', ['messages' => $messages]);
        dd($my_id);
    }
    public function view($username)
    {
        $member = MemberModel::find($username);
        dd($member);
    }
    public function addnewmember(Request $request)
    {
        $data_member = new \App\MemberModel;
        $data_member->nama_lengkap = $request->nama_lengkap;
        $data_member->username = $request->username;
        $data_member->email = $request->email;
        $data_member->role = $request->role;
        $data_member->status = 'active';
        $data_member->un_password = $request->password;
        $data_member->jabatan = $request->jabatan;
        $data_member->divisi = $request->divisi;
        $data_member->profilephoto = 'default.jpg';
        $data_member->kantor = $request->kantor;
        $data_member->password = Hash::make($request->password);
        $data_member->remember_token = str_random(50);
        $data_member->created_by = auth()->user()->nama_lengkap;
        $data_member->save();

        return redirect('/member')->with('sukses', 'New member data has been succesfully added!');
    }
    public function registered(Request $request)
    {
        $data_member = new \App\MemberModel;
        $data_member->nama_lengkap = $request->nama_lengkap;
        $data_member->username = $request->username;
        $data_member->email = $request->email;
        $data_member->role = $request->role;
        $data_member->status = 'inactive';
        $data_member->un_password = $request->password;
        $data_member->password = Hash::make($request->password);
        $data_member->remember_token = str_random(50);
        $data_member->created_by = 'Guest.';
        $data_member->save();

        return back()->with('sukses', 'Akun anda telah berhasil diajukan. Hubungi pihak managemen IT anda untuk menyetujui ajuan daftar anggota anda. Dan tunggu akan email anda untuk informasi username dan password anda');
    }
    public function delete($id)
    {
        $data_member = MemberModel::find($id);

        if ($data_member) {
            if ($data_member->delete()) {

                DB::statement('ALTER TABLE users AUTO_INCREMENT = ' . (count(MemberModel::all()) + 1) . ';');

                return back()->with('sukses', 'Member has been successfully deleted!');
            }
        }
    }
    public function edit($id)
    {
        $data_member = \App\MemberModel::find($id);
        return view('member.edit', ['data_member' => $data_member]);
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $data_member = \App\MemberModel::find($id);
        $data_member->update($request->all());
        $data_member->un_password = $request->password;
        $data_member->password = Hash::make($request->password);
        $data_member->remember_token = str_random(50);
        $data_member->jabatan = $request->jabatan;
        $data_member->divisi = $request->divisi;
        $data_member->kantor = $request->kantor;
        $data_member->facebook = $request->facebook;
        $data_member->instagram = $request->instagram;
        if ($request->hasFile('profilephoto')) {
            $avatar = $request->file('profilephoto');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $request->file('profilephoto')->move('file/', $filename);
            $data_member->profilephoto = $filename;
        }
        $data_member->updated_by = auth()->user()->nama_lengkap;
        $data_member->created_by = auth()->user()->nama_lengkap;
        $data_member->save();
        return redirect('/dashboard')->with('sukses', 'Member data has been successfully updated!');
    }
}
