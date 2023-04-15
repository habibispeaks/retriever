<?php

//php artisan make:model ReportItem --table=reportitem  --------------->command for creating models


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadItem extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $table = 'uploaditem';


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
