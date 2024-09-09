<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carne extends Model
{
    use HasFactory;

    protected $table = 'carnes';

    protected $primaryKey = 'id_carne';

    protected $fillable = [
        'valor_total',
        'qtd_parcelas',
        'data_primeiro_vencimento',
        'periodicidade'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function carneparcelas()
    {
        return $this->belongsToMany(CarneParcela::class, 'carne_parcela', 'id_carne', 'id_carne_parcela');
    }

    protected static function boot()
    {
        parent::boot();

        Carne::deleting(function ($carne) {
            $carne->carneparcelas()->detach();
        });
    }
}
