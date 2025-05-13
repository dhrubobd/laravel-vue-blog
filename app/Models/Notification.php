<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    protected $fillable = ['user_id', 'type', 'message', 'read_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
