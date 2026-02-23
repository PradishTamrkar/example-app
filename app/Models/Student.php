<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'student_id'; //yo kina //primary key cei protected nei kina vanda kheri it is needed when saving updating reading etc and this should not be accessible to other classes except itself and parent/child class basically internal configs ho yini haru so we better noit make it public because it should be accessed by external class and also not private because it blocks inheritance

    protected $fillable = [     // why fillable //fillable is used for mass assignment ad fillable chei used to assign the only attributes that can be filled like if fillable use garena rey ani is_admin vanne euta colum cha rey tyo fillable use garena vane jun ni user le fill garna sakcha and can make themselves admin
        'student_name',         // fillable and guard
        'student_email',
        'phone_no',
    ];

    // protected $guarded = ['student_id'];
}

//fillable chei used for mass assignment and determine which which attributes are mass fillable
//but guarded chei used to block list of attributes that cannot be mass assigned
//normally hamle save() use garyo vane harek time new object create garirako huncha so yesto case ma mass fill garnu pardeina so guarded can be used but mass fill garnu cha vane hamilai fillable chaincha nei
//use either fillable or guarded per model
//$guarded =[] //means allow everything we will use guared when there are many columns and many of them can be mass fillable and only some cannot be mass fill able so in that case we can make code shorter by using guarded
