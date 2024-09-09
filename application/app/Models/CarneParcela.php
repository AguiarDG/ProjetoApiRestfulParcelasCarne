<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarneParcela extends Model
{
    use HasFactory;

    protected $table = 'carnes_parcelas';

    protected $primaryKey = 'id_carne_parcela';

    protected $fillable = [
        'id_carne',
        'numero',
        'valor',
        'data_vencimento',
        'entrada'
    ];
}
