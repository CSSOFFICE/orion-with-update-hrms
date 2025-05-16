<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XinSystemSetting extends Model
{
    /**
     * @table string - The table associated with the model.
     * @primaryKey string - primry key column.
     */
    protected $table = 'xin_system_setting';
    protected $primaryKey = 'setting_id';
}
