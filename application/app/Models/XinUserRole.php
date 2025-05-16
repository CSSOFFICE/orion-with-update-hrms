<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XinUserRole extends Model
{
    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     */
    protected $primaryKey = 'role_id';
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * relatioship business rules:
     *         - the Project can have many Estimates
     *         - the Estimate belongs to one Project
     */
    public function users() {
        return $this->hasMany('App\Models\User', 'role_id', 'role_id');
    }

    /**
     * get array of role resources
     *
     * @return array
     */
    public function getRoleResources()
    {
        return explode(',', $this->role_resources);
    }
}
