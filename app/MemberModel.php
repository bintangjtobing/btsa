<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    protected $table = 'users';
    protected $fillable = ['nama_lengkap', 'username', 'email', 'password', 'role', 'status', 'remember_token', 'created_at', 'updated_at', 'un_password', 'logIP', 'last_login', 'last_logout', 'jabatan', 'divisi', 'kantor', 'profilephoto', 'facebook', 'instagram'];

    public function getAvatar()
    {
        if (!$this->profilephoto) {
            return asset('file/' . $this->profilephoto);
        }
        return asset('file/default.jpg');
    }
}
