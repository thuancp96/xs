<?php namespace App;

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Bean object based on Collection
 */
class CustomerType_Game extends Model
{
    protected $table = 'customer_type_game';

    // public function findOrFail()
    // {
    //     if (App::runningInConsole()) {
    //         Log::info("DB::reconnect");
    //         DB::reconnect();
    //     }

    //     return parent::__call('findOrFail', func_get_args());
    // }
}
