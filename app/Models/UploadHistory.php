<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    protected $table = 'upload_histories'; // Confirma que está usando o nome correto da tabela


    protected $fillable = ['i_cod_cnes_fonte', 'upload_date', 'user_id', 'file_name', 'record_count'];
}
