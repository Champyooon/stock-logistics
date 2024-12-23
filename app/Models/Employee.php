<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable =[
        'department_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'date_of_hired',
        'num_cnps',
        'jobtitle',
        'address',
        'telephone',
        'email',
        'service_id'
       ];

       public function department():BelongsTo
   {
     return $this->belongsTo(Department::class);
   }
   public function service():BelongsTo
   {
     return $this->belongsTo(Service::class);
   }
}
