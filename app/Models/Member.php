<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'aadhar_no',
        'mobile_number',
        'phone_number',
        'address',
        'city',
        'state',
        'zip_code',
        'weight',
        'height',
        'chest',
        'waist',
        'thigh',
        'arms',
        'fat_percentage',
        'username',
        'password',
        'photo_path',
        'interested_area',
        'source',
        'referred_by',
        'member_type',
        'membership_type',
        'membership_valid_from',
        'membership_valid_to',
        'first_payment_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'membership_valid_from' => 'date',
        'membership_valid_to' => 'date',
        'first_payment_date' => 'date',
    ];
}