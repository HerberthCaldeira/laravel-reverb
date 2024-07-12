<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('my.private.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
