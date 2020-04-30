<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // app/Message.php

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['message'];

    /**
     * A message belong to a conversation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation() {
        return $this->belongsTo('App\Conversation');
    }

    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}