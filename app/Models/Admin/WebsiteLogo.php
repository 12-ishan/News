<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions; 


class WebsiteLogo extends Model
{
    protected $table = 'website_logo';

    protected $fillable = [ 'imageId'];

    public function image()
    {
        return $this->belongsTo('App\Models\Admin\Media', 'imageId', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*'])->useLogName('website logo')->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
        // Chain fluent methods for configuration options
       
    }


}