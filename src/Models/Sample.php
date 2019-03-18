<?php
namespace APP\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class User
 * @package APP\Models
 * @author: Drew D. Lenhart
 */
class Sample extends Model
{
    public $timestamps = false;
    protected $connection = 'sqlite';

    protected $table = 'Sample';
}
