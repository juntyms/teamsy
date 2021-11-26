<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantScope;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function($department) {
            if (session()->has('tenant_id')) {
                $department->tenant_id = session()->get('tenant_id');
            }
        });
    }
}
