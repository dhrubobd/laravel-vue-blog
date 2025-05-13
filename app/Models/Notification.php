<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use HasFactory, Notifiable;
    
    protected $fillable = ['user_id', 'type', 'message', 'read_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
