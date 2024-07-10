<?php

namespace App\Http\Middleware;

use Closure;
use Config;

class UpdateDbConfiguration
{
    /**
     * Handle databse configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $config = Config::get('database.connections.mysql');
        // $config['user'] = "test";
        // $config['database'] = "testdb";
        // $config['password'] = "test2123";
        // config()->set('database.connections.mysql', $config);
        // echo "<pre>";
        // print_r(config());
    }
}
