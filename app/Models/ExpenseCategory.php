<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;
    
}
class Category extends Model
{
    protected $table = 'expense_categories';
    protected $fillable = ['gym_id', 'name'];
}
