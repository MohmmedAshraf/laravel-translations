<?php

use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use App\Exceptions\Handler;

if (class_exists('App\Exceptions\Handler')) {
    class ExtendedHandler extends Handler { }
} else {
    class ExtendedHandler extends BaseHandler { }
}
