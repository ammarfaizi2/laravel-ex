<?php
class Authentication extends Eloquent
{
	 /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'authentications';
	
	protected $primaryKey = 'user_id';
}
?>

