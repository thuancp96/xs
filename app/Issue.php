<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model {

	protected $fillable = ['title', 'description','image','user_id', 'user_name'];

	protected $table = 'issues';

}
