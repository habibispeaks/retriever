<?php

//php artisan make:model ReportItem --------------->command for creating models


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportItem extends Model
{
    use HasFactory;

    protected $table = 'reportitem';

    public $timestamps = false;
    protected $fillable = [
        'itemname',
        'category',
        'date',
        'time',
        'area',
        'street',
        'city',
        'file',
        'description',
    ];
}
