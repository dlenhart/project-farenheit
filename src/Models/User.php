<?php
namespace APP\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class User
 * @package APP\Models
 * @author: Drew D. Lenhart
 */
class User extends Model
{
    public $timestamps = true;
    protected $connection = 'sqlite';

    protected $table = 'Users';

    protected $fillable = [
        'email',
        'name',
        'password'
    ];
}
