<?php

namespace webvimark\modules\UserManagement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use webvimark\modules\UserManagement\models\User;
use app\models\IcHeadTellers;
use app\models\IcHeadTellersWeekend;
use app\models\IcUsers;
use app\models\IcAts;
use app\models\IcAtsAll;
use app\models\IcTs;
use app\models\IcAtsWeekend;


/**
 * UserSearch represents the model behind the search form about `webvimark\modules\UserManagement\models\User`.
 */
class UserSearch extends User
{
	public function rules()
	{
		return [
			[['id', 'superadmin', 'status', 'created_at', 'updated_at', 'email_confirmed'], 'integer'],
			[['username', 'gridRoleSearch', 'registration_ip', 'email'], 'string'],
		];
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		$query = User::find();
			
			
	
		$query->with(['roles']);
	

		if ( !Yii::$app->user->isSuperadmin )
		{
			$query->where(['superadmin'=>0]);

			//HEAD TELLERS 25
			//filter ther users related to IC for 
			if(User::hasRole('25')){

				$head_teller = IcHeadTellers::find()->select('ic_id')->where(['user_id' =>\Yii::$app->user->identity->id,'status'=>1] )->all();
				$head_teller_weekend = IcHeadTellersWeekend::find()->select('ic_id')->where(['user_id' =>\Yii::$app->user->identity->id,'status'=>1] )->all();
				
				
				
				 if($head_teller){
					$ic_user_list = IcUsers::find()->select('user_id as id')->where(['AND',['in','ic_id',$head_teller],['status'=>1]] )->all();
					 
					$query->andFilterWhere(['in', 'id', $ic_user_list]);
				}
				if($head_teller_weekend){
					$ic_user_list = IcUsers::find()->select('user_id as id')->where(['AND',['in','ic_id',$head_teller_weekend],['status'=>1]] )->all();
					
					$query->andFilterWhere(['in','id', $ic_user_list]);
				}
			   
			}
			//ATS 40
			//filter ther users related to IC for 
			if(User::hasRole('40')){

				$ic_list_ats = IcAts::find()->select('ic_id')->where(['user_id' =>\Yii::$app->user->identity->id,'status'=>1] )->all();          
				$ic_list_ats_weekend = IcAtsWeekend::find()->select('ic_id')->where(['user_id' =>\Yii::$app->user->identity->id,'status'=>1] )->all();
				
				if($ic_list_ats){
					$ic_user_list = IcUsers::find()->select('user_id as id')->where(['AND',['in','ic_id' ,$ic_list_ats],['status'=>1]] )->all();
					$ic_head_tellers_list = IcHeadTellers::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats],['status'=>1]])->all();
					$ic_head_tellers_weekend_list = IcHeadTellersWeekend::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats],['status'=>1]])->all();
				 
					$query->andFilterWhere(['in', 'id', $ic_user_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_weekend_list]);
				}
				if($ic_list_ats_weekend){
					$ic_user_list = IcUsers::find()->select('user_id as id')->where(['AND',['in','ic_id' ,$ic_list_ats_weekend],['status'=>1]] )->all();
					$ic_head_tellers_list = IcHeadTellers::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats_weekend],['status'=>1]])->all();
					$ic_head_tellers_weekend_list = IcHeadTellersWeekend::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats_weekend],['status'=>1]])->all();
				
					$query->andFilterWhere(['in','id', $ic_user_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_weekend_list]);
				}
			   
			}

			//ATS ALL and TS 30
			//filter ther users related to IC for 
			if(User::hasRole('30')){

				$ic_list_ats_all = IcAtsAll::find()->select('ic_id')->where(['user_id' =>\Yii::$app->user->identity->id,'status'=>1] )->all();          
				$ic_list_ts = IcTs::find()->select('ic_id')->where(['user_id' =>\Yii::$app->user->identity->id,'status'=>1] )->all();
				
				if($ic_list_ats_all){
					$ic_user_list = IcUsers::find()->select('user_id as id')->where(['AND',['in','ic_id' ,$ic_list_ats_all],['status'=>1]] )->all();
					$ic_head_tellers_list = IcHeadTellers::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats_all],['status'=>1]])->all();
					$ic_head_tellers_weekend_list = IcHeadTellersWeekend::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats_all],['status'=>1]])->all();
					$ic_ats_list = IcAts::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats_all],['status'=>1]])->all();
					$ic_ats_weekend_list = IcAtsWeekend::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ats_all],['status'=>1]])->all();
					
					$query->andFilterWhere(['in', 'id', $ic_user_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_weekend_list]);
					$query->orFilterWhere(['in', 'id', $ic_ats_list]);
					$query->orFilterWhere(['in', 'id', $ic_ats_weekend_list]);
				}
				if($ic_list_ts){
					$ic_user_list = IcUsers::find()->select('user_id as id')->where(['AND',['in','ic_id' ,$ic_list_ts],['status'=>1]] )->all();
					$ic_head_tellers_list = IcHeadTellers::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ts],['status'=>1]])->all();
					$ic_head_tellers_weekend_list = IcHeadTellersWeekend::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ts],['status'=>1]])->all();
					$ic_ats_list = IcAts::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ts],['status'=>1]])->all();
					$ic_ats_weekend_list = IcAtsWeekend::find()->select('user_id as id')->where(['AND',['in','ic_id',$ic_list_ts],['status'=>1]])->all();
				 
					$query->andFilterWhere(['in', 'id', $ic_user_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_list]);
					$query->orFilterWhere(['in', 'id', $ic_head_tellers_weekend_list]);
					$query->orFilterWhere(['in', 'id', $ic_ats_list]);
					$query->orFilterWhere(['in', 'id', $ic_ats_weekend_list]);
				}
			   
			}
			

		}

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
			],
			'sort'=>[
				'defaultOrder'=>[
					'id'=>SORT_DESC,
				],
			],
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		if ( $this->gridRoleSearch )
		{
			$query->joinWith(['roles']);
		}

		$query->andFilterWhere([
			//'id' => $this->id,
			'superadmin' => $this->superadmin,
			'status' => $this->status,
			Yii::$app->getModule('user-management')->auth_item_table . '.name' => $this->gridRoleSearch,
			'registration_ip' => $this->registration_ip,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'email_confirmed' => $this->email_confirmed,
		]);

        	$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'email', $this->email]);
		
		


		return $dataProvider;
	}
	
	public function searchByDesignation($params)
	{
		$query = User::find()->where(['designation_id'=>$params]);

	//	$query->with(['roles']);

		if ( !Yii::$app->user->isSuperadmin )
		{
			$query->where(['superadmin'=>0]);
		}

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
			],
			'sort'=>[
				'defaultOrder'=>[
					'id'=>SORT_DESC,
				],
			],
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		if ( $this->gridRoleSearch )
		{
			$query->joinWith(['roles']);
		}

		$query->andFilterWhere([
			'id' => $this->id,
			'superadmin' => $this->superadmin,
			'status' => $this->status,
			Yii::$app->getModule('user-management')->auth_item_table . '.name' => $this->gridRoleSearch,
			'registration_ip' => $this->registration_ip,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'email_confirmed' => $this->email_confirmed,
		]);

        	$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'email', $this->email]);

		return $dataProvider;
	}
}
