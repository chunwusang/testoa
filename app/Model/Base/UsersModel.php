<?php
/**
*	平台上用户数据模型
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Observers\PasswordHashObservers;
use App\Observers\UsersObservers;
use Rock;

class UsersModel extends Authenticatable
{
    use Notifiable;

	protected $table = 'users';
	
	
	protected $hidden = [
        'password', 'remember_token',
    ];
	
	public static function boot()
	{
		parent::boot();
		static::observe(new PasswordHashObservers());
		static::observe(new UsersObservers());
	}
	
	//设置默认头像
	public function getFaceAttribute($val)
    {
		if(isempt($val)){
			$val = '/images/noface.png';
		}
		return Rock::replaceurl($val);
    }
	
	//设置默认样式
	public function getBootstyleAttribute($val)
    {
		if(isempt($val)){
			$val = config('rock.usersstyle');
		}
		return $val;
    }
	
	/**
	*	创建的单位(1对多)
	*/
	public function createcompany()
	{
		return $this->hasMany(CompanyModel::class, 'uid');
	}
	
	/**
	*	加入的单位(1对多)，返回usera单位用户记录
	*/
	public function joincompany()
	{
		return $this->hasMany(UseraModel::class, 'uid');
	}
}
