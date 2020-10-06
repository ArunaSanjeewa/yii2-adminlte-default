<?php

namespace webvimark\modules\UserManagement\models;

use webvimark\helpers\LittleBigHelper;
use webvimark\helpers\Singleton;
use webvimark\modules\UserManagement\components\AuthHelper;
use webvimark\modules\UserManagement\components\UserIdentity;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use webvimark\modules\UserManagement\models\User;


use webvimark\modules\UserManagement\UserManagementModule;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use app\models\Designation;
use app\models\IcHeadTellers;
use app\models\IcHeadTellersWeekend;
use app\models\IcUsers;
use yii\data\ActiveDataProvider;
use app\models\IcAts;
use app\models\IcAtsWeekend;
use app\models\IcTs;
use app\models\IcAtsAll;

use app\models\Customer;
use app\models\Outlet;


/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $email_confirmed
 * @property string $auth_key
 * @property string $password_hash
 * @property string $confirmation_token
 * @property string $bind_to_ip
 * @property string $registration_ip
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 *  @property integer $roleId
 */
class User extends UserIdentity implements IdentityInterface
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	const STATUS_BANNED = -1;
	public $file;


	/**
	 * @var string
	 */
	public $gridRoleSearch;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @var string
	 */
	public $repeat_password;
        public $Role_id;

	/**
	 * Store result in singleton to prevent multiple db requests with multiple calls
	 *
	 * @param bool $fromSingleton
	 *
	 * @return static
	 */
	public static function getCurrentUser($fromSingleton = true)
	{
		if ( !$fromSingleton )
		{
			return static::findOne(Yii::$app->user->id);
		}

		$user = Singleton::getData('__currentUser');

		if ( !$user )
		{
			$user = static::findOne(Yii::$app->user->id);

			Singleton::setData('__currentUser', $user);
		}

		return $user;
	}

	/**
	 * Assign role to user
	 *
	 * @param int  $userId
	 * @param string $roleName
	 *
	 * @return bool
	 */
	public static function assignRole($userId, $roleName)
	{
		try
		{
			Yii::$app->db->createCommand()
				->insert(Yii::$app->getModule('user-management')->auth_assignment_table, [
					'user_id' => $userId,
					'item_name' => $roleName,
					'created_at' => time(),
				])->execute();

			AuthHelper::invalidatePermissions();

			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	/**
	 * Revoke role from user
	 *
	 * @param int    $userId
	 * @param string $roleName
	 *
	 * @return bool
	 */
	public static function revokeRole($userId, $roleName)
	{
		$result = Yii::$app->db->createCommand()
			->delete(Yii::$app->getModule('user-management')->auth_assignment_table, ['user_id' => $userId, 'item_name' => $roleName])
			->execute() > 0;

		if ( $result )
		{
			AuthHelper::invalidatePermissions();
		}

		return $result;
	}

	/**
	 * @param string|array $roles
	 * @param bool         $superAdminAllowed
	 *
	 * @return bool
	 */
	public static function hasRole($roles, $superAdminAllowed = true)
	{
		if ( $superAdminAllowed AND Yii::$app->user->isSuperadmin )
		{
			return true;
		}
		$roles = (array)$roles;

		AuthHelper::ensurePermissionsUpToDate();

		return array_intersect($roles, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_ROLES,[])) !== [];
	}

	/**
	 * @param string $permission
	 * @param bool   $superAdminAllowed
	 *
	 * @return bool
	 */
	public static function hasPermission($permission, $superAdminAllowed = true)
	{
		if ( $superAdminAllowed AND Yii::$app->user->isSuperadmin )
		{
			return true;
		}

		AuthHelper::ensurePermissionsUpToDate();

		return !(in_array($permission, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_PERMISSIONS,[])));
	}

	public static function hasRestriction($permission)
	{
		AuthHelper::ensurePermissionsUpToDate();

		return in_array($permission, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_PERMISSIONS,[]));
	}

	/**
	 * Useful for Menu widget
	 *
	 * <example>
	 * 	...
	 * 		[ 'label'=>'Some label', 'url'=>['/site/index'], 'visible'=>User::canRoute(['/site/index']) ]
	 * 	...
	 * </example>
	 *
	 * @param string|array $route
	 * @param bool         $superAdminAllowed
	 *
	 * @return bool
	 */
	public static function canRoute($route, $superAdminAllowed = true)
	{
		if ( $superAdminAllowed AND Yii::$app->user->isSuperadmin )
		{
			return true;
		}

		$baseRoute = AuthHelper::unifyRoute($route);

		if ( Route::isFreeAccess($baseRoute) )
		{
			return true;
		}

		AuthHelper::ensurePermissionsUpToDate();

		return Route::isRouteAllowed($baseRoute, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_ROUTES,[]));
	}

	/**
	 * getStatusList
	 * @return array
	 */
	public static function getStatusList()
	{
		return array(
			self::STATUS_ACTIVE   => UserManagementModule::t('back', 'Active'),
			self::STATUS_INACTIVE => UserManagementModule::t('back', 'Inactive'),
			self::STATUS_BANNED   => UserManagementModule::t('back', 'Banned'),
		);
	}

	/**
	 * getStatusValue
	 *
	 * @param string $val
	 *
	 * @return string
	 */
	public static function getStatusValue($val)
	{
		$ar = self::getStatusList();

		return isset( $ar[$val] ) ? $ar[$val] : $val;
	}

	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return Yii::$app->getModule('user-management')->user_table;
	}
	

	/**
	* @inheritdoc
	*/
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
			['username', 'required'],
			
			['username', 'unique'],
			['username', 'trim'],
			['full_name', 'required'],
			 ['file_no','string'],
			['address', 'required'],
			['designation_id', 'required'],
			['first_name', 'required'],
			['last_name', 'required'],
			[['nic'], 'required'],
			['nic', 'unique','on'=>['newUser']],
	
			[['nic'], 'match', 'pattern' => '/^([0-9]{9}[x|X|v|V]|[0-9]{12})$/'],
		
			
			[['status', 'email_confirmed','contact_number','designation_id','outlet_id'], 'integer'],
			[['contact_number'], 'match', 'pattern' => '/^(?:0)?(?:(?P<area>11|21|23|24|25|26|27|31|32|33|34|35|36|37|38|41|45|47|51|52|54|55|57|63|65|66|67|81|91)(?P<land_carrier>0|2|3|4|5|7|9)|7(?P<mobile_carrier>0|1|2|5|6|7|8)\d)\d{6}$/'],
			
		//	['email', 'required'],

			['email', 'email'],
		//	['email','required'],
			['email','unique','on'=>['newUser']],
			['email', 'validateEmailConfirmedUnique','on'=>['newUser']],
			[['profile_log'], 'string', 'max' => 250],
			
			
			['bind_to_ip', 'validateBindToIp'],
			['bind_to_ip', 'trim'],
			['bind_to_ip', 'string', 'max' => 255],

			['password', 'required', 'on'=>['newUser', '']],
			['password', 'string',  'min' => 4,'max' => 10, 'on'=>['newUser', 'changePassword']],
			['password', 'trim', 'on'=>['newUser', 'changePassword']],
			['password', 'match', 'pattern' => Yii::$app->getModule('user-management')->passwordRegexp],

			['repeat_password', 'required', 'on'=>['newUser', 'changePassword']],
			['repeat_password', 'compare', 'compareAttribute'=>'password'],
			[['designation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Designation::className(), 'targetAttribute' => ['designation_id' => 'id']],
			[['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
			[['outlet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Outlet::className(), 'targetAttribute' => ['outlet_id' => 'id']],

		];
	}

	/**
	 * Check that there is no such confirmed E-mail in the system
	 */
	public function validateEmailConfirmedUnique()
	{
		if ( $this->email )
		{
			$exists = User::findOne([
				'email'           => $this->email
				//'email_confirmed' => 1,
			]);

			if ( $exists  )
			{
				$this->addError('email', UserManagementModule::t('front', 'This E-mail already exists'));
			}
		}
	}

	/**
	 * Validate bind_to_ip attr to be in correct format
	 */
	public function validateBindToIp()
	{
		if ( $this->bind_to_ip )
		{
			$ips = explode(',', $this->bind_to_ip);

			foreach ($ips as $ip)
			{
				if ( !filter_var(trim($ip), FILTER_VALIDATE_IP) )
				{
					$this->addError('bind_to_ip', UserManagementModule::t('back', "Wrong format. Enter valid IPs separated by comma"));
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return [
			'id'                 => 'ID',
			'username'           => UserManagementModule::t('back', 'User Name'),
			'full_name'           => UserManagementModule::t('back', 'Full Name'),
			'address'  => UserManagementModule::t('back', 'Address'),
			'nic'           => UserManagementModule::t('back', 'NIC'),
			'file_no'           => UserManagementModule::t('back', 'Personal File No'),
			'superadmin'         => UserManagementModule::t('back', 'Superadmin'),
			'confirmation_token' => UserManagementModule::t('back', 'Confirmation Token'),
			'registration_ip'    => UserManagementModule::t('back', 'Registration IP'),
			'bind_to_ip'         => UserManagementModule::t('back', 'Bind to IP'),
			'status'             => UserManagementModule::t('back', 'Status'),
			'gridRoleSearch'     => UserManagementModule::t('back', 'Roles'),
			'created_at'         => UserManagementModule::t('back', 'Created'),
			'updated_at'         => UserManagementModule::t('back', 'Updated'),
			'password'           => UserManagementModule::t('back', 'Password'),
			'repeat_password'    => UserManagementModule::t('back', 'Repeat password'),
			'email_confirmed'    => UserManagementModule::t('back', 'E-mail confirmed'),
			'email'              => UserManagementModule::t('back', 'E-mail'),
			'Role_id'              => UserManagementModule::t('back', 'Role'),
			'file' => 'Profile Photo',
			'contact_number'=>UserManagementModule::t('back', 'Contact No'),
			'designation_id'=>UserManagementModule::t('back', 'Designation'),
			'customer_id'=>UserManagementModule::t('back', 'Customer'),
			'first_name'=>UserManagementModule::t('back', 'First Name'),
			'last_name'=>UserManagementModule::t('back', 'Last Name'),
			'outlet_id'=>UserManagementModule::t('back', 'Outlet')
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRoles()
	{
		return $this->hasMany(Role::className(), ['name' => 'item_name'])
			->viaTable(Yii::$app->getModule('user-management')->auth_assignment_table, ['user_id'=>'id']);
	}


	/**
	 * Make sure user will not deactivate himself and superadmin could not demote himself
	 * Also don't let non-superadmin edit superadmin
	 *
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
          
		if ( $insert )
		{
			if ( php_sapi_name() != 'cli' )
			{
				$this->registration_ip = LittleBigHelper::getRealIp();
			}
			$this->generateAuthKey();
		}
		else
		{
			// Console doesn't have Yii::$app->user, so we skip it for console
			if ( php_sapi_name() != 'cli' )
			{
				if ( Yii::$app->user->id == $this->id )
				{
					// Make sure user will not deactivate himself
					$this->status = static::STATUS_ACTIVE;

					// Superadmin could not demote himself
					if ( Yii::$app->user->isSuperadmin AND $this->superadmin != 1 )
					{
						$this->superadmin = 1;
					}
				}

				// Don't let non-superadmin edit superadmin
				if ( isset($this->oldAttributes['superadmin']) && !Yii::$app->user->isSuperadmin && $this->oldAttributes['superadmin'] == 1 )
				{
					return false;
				}
			}
		}

		// If password has been set, than create password hash
		if ( $this->password )
		{
			$this->setPassword($this->password);
			$this->setEncryptedPassword($this->password);
			
		}

		return parent::beforeSave($insert);
	}

	/**
	 * Don't let delete yourself and don't let non-superadmin delete superadmin
	 *
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		// Console doesn't have Yii::$app->user, so we skip it for console
		if ( php_sapi_name() != 'cli' )
		{
			// Don't let delete yourself
			if ( Yii::$app->user->id == $this->id )
			{
				return false;
			}

			// Don't let non-superadmin delete superadmin
			if ( !Yii::$app->user->isSuperadmin AND $this->superadmin == 1 )
			{
				return false;
			}
		}

		return parent::beforeDelete();
	}

	/**
	 * @inheritdoc
	 */
    /*
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
	 */

	public static function findIdentityByAccessToken($token, $type = null)
	{

		$access_token = AccessTokens::findOne(['token' => $token]);
		echo json_encode($access_token);
		die;
		if ($access_token) {
			if ($access_token->expires_at < time()) {
				Yii::$app->api->sendFailedResponse('Access token expired');
			}

			return static::findOne(['id' => $access_token->user_id]);
		} else {
			return (false);
		}
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
			'status' => self::STATUS_ACTIVE,
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return bool
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}

		$timestamp = (int)substr($token, strrpos($token, '_') + 1);
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		return $timestamp + $expire >= time();
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}
	public function setEncryptedPassword($password)
	{
		$this->encrypted_password = base64_encode($password);
	}
	public function hashPassword($password)
	{
		return Yii::$app->security->generatePasswordHash($password);
	}


	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}
	public function validateNic()
    {
        if ($model->nic) {
            $exists = $this::findOne([
                'nic' => $model->nic,
                //'email_confirmed' => 1,
            ]);

            if ($exists) {
                $this->addError('nic', UserManagementModule::t('front', 'This nic already exists'));
            }
        }
	}
	public function getDesignation()
    {
        return $this->hasOne(Designation::className(), ['id' => 'designation_id']);
	}
	public function getOutlet()
    {
        return $this->hasOne(Outlet::className(), ['id' => 'outlet_id']);
	}

	public static function getListTec($outlet_idTec)
    {
       
            return array('' => '') + ArrayHelper::map(self::find()->where(['designation_id'=>3])->andWhere(['outlet_id'=>$outlet_idTec])->all(), 'id', 'full_name');
       
    }
	public static function getListHelper()
    {
       
            return array('' => '') + ArrayHelper::map(self::find()->where(['designation_id'=>4])->all(), 'id', 'full_name');
       
    }

	public static function getList() {

     
		$users = User::find()->all();

		
		
	   return array('' => '') + ArrayHelper::map(self::find()->all(),'id','username'); 
		  
	   }


	   //GET Tellers
	   public static function getListSysUsers($customer_id) {

     
		$users = User::find()->where(['designation_id'=>5])->andWhere(['not in','customer_id',$customer_id])->all();
		
	   return array('' => '') + ArrayHelper::map($users,'id','username'); 
		  
	   }
	    //GET Tellers
		public static function getListByDesignation($designation_id,$outlet_id) {

			//print_r($outlet_id);die;

		//	
		//	$users = Yii::$app->db->createCommand('SELECT * FROM user Where  designation_id =' .  $designation_id . ' AND outlet_id NOT in ('.$outlet_id.')  ')->queryAll();
		//	$users = Yii::$app->db->createCommand('SELECT * FROM user Where  designation_id =' .  $designation_id . ' AND outlet_id !='.$outlet_id.' ')->queryAll();;
			$users = User::find()->where(['designation_id'=>$designation_id])->all();

			
		   return array('' => '') + ArrayHelper::map($users,'id','username'); 
			  
		   }
	
}
