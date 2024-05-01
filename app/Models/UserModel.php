<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    protected $table = 'm_warga';
    protected $primaryKey = 'nik';

    protected $fillable = ['level_id','nokk','nama','jenis_kelamin','tempat_lahir','tanggal_lahir','alamat','agama','status','kewarganegaraan','pekerjaan','pendidikan','status_pernikahan'];

    public function level() : BelongsTo
    {
        return $this->belongsTo(LevelModel::class,'level_id','level_id');
    }
}
