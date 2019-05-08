<?php
/**
*	平台管理员数据模型
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Observers\PasswordHashObservers;

class AdminModel extends Authenticatable
{
    use Notifiable;
	
	protected $table = 'admin';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public static function boot()
	{
		parent::boot();

		static::observe(new PasswordHashObservers());
	}
}
