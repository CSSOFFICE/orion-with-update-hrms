<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class variation_templates extends Model
{
    use HasFactory;
    protected $table = 'variation_templates';
    protected $fillable = [
        'description',
        'unit',
        'qty',
        'labour',
        'material',
        'misc',
        'wastage_percent',
        'wastage_amount',
        'sc',
        'total',
        'amount',
        'quotation_no',
        'template_id',
        'estimates_id',
        'type',
        'contractor_percent',
        'contractor_amount',
    ];
}
