<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'nick_name',
        'logo',
        'description',
        'address',
        'email',
        'phone_number',
        'instagram_account_link',
        'tiktok_account_link',
        'whatsapp_account_link',
    ];
}
