<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BaseModel extends Model
{
    use TimestampsFormatTrait;
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
