<?php namespace webvimark\modules\UserManagement\controllers;

use webvimark\components\AdminDefaultController;
use Yii;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\search\UserSearch;
use yii\web\NotFoundHttpException;
use app\models\Employee;
use app\models\Organization;
use yii\helpers\json;
use app\models\OutletUser;
use app\models\RegionUser;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use yii\helpers\ArrayHelper;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\UploadedFile;
use DateTime;
use DatePeriod;
use DateInterval;
use app\models\SessionUsers;
use app\models\Customer;


/**
 * UserController implements the CRUD actions for User model.
 */

class UserController extends AdminDefaultController
{
	/**
	 * @var User
	 */
	public $modelClass = 'webvimark\modules\UserManagement\models\User';

	/**
	 * @var UserSearch
	 */
	public $modelSearchClass = 'webvimark\modules\UserManagement\models\search\UserSearch';

	/**
	 * @return mixed|string|\yii\web\Response
	 */
	public function actionCreate()
	{	
		
		$model = new User(['scenario'=>'newUser']);

		$model2 = new User();

			  
	   if (Yii::$app->request->isPost) {
		   	$model2->file = UploadedFile::getInstance($model,'file');
		  	
		
		   if (!empty($model2->file)) {
			$exten = $model2->file->extension;
			   if ($model->load(Yii::$app->request->post())) {
				   $model2->file->name = $model->username;                    
				   $model->profile_log = "";                     
				   $createTimeStamp = date('YmdHis');                                  
				   $model2->file->saveAs('../profile_pictures/'.$model->username. '_'.$createTimeStamp.'.'. $exten);
				   $model->profile_log = '../profile_pictures/'.$model->username. '_'.$createTimeStamp.'.'.$exten;                  
  
				   if( $model->save()){				
					//	User::assignRole($model->id, "50");
						Yii::$app->getSession()->setFlash('success','User created successfully');
					return $this->redirect(['view', 'id' => $model->id]);	
				   }
				   
			   }
		   }else{
			   
			   if ($model->load(Yii::$app->request->post())) {
				   
				   $model->profile_log = "";
				   $createTimeStamp = date('YmdHis');				  
				   $model->profile_log = '../profile_pictures/default_profile.jpg';
				   if( $model->save()){
					// $master_version = Yii::$app->db->createCommand('SELECT * FROM tbl_version WHERE tbl_name="tbl_user"')->queryOne();
					// $new_version = $master_version['tbl_version']+1;
					// $increament_master_fare_version = Yii::$app->db->createCommand('UPDATE tbl_version SET tbl_version='.$new_version.' WHERE tbl_name="tbl_user"')->execute();
					
					//	User::assignRole($model->id, "50");
						Yii::$app->getSession()->setFlash('success','User created successfully');
						return $this->redirect(['view', 'id' => $model->id]);	
					}
			   }
		   }
	   }

		return $this->render('create', [
			'model' => $model,
			'model2'=>$model2
			
		]);
	}



	public function actionUpdate($id)
	{
	
		$model = $this->findModel($id);
		
		
		if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if (empty($model->file)) {

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
					
					
                    Yii::$app->getSession()->setFlash('success','User updated successfully');
                    return $this->redirect(['view', 'id' => $model->id]);
                }


            }else{
				$createTimeStamp = date('YmdHis');
            $model->file->saveAs('../profile_pictures/'.$model->username.'_'.$createTimeStamp.'.'.$model->file->extension);

                //save databases
            $model->profile_log = '../profile_pictures/' . $model->username . '_' . $createTimeStamp . '.' . $model->file->extension;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
               Yii::$app->getSession()->setFlash('success','Success');
                return $this->redirect(['view', 'id' => $model->id]);
            }
			}
            

        }
		
		return $this->render('update', [
			'model' => $model,

		]);
	}
	/**
	 * @param int $id User ID
	 *
	 * @throws \yii\web\NotFoundHttpException
	 * @return string
	 */
	public function actionChangePassword($id)
	{

		
		$model = User::findOne($id);
		//print_r($model);die;

		if ( !$model )
		{
			throw new NotFoundHttpException('User not found');
		}

		$model->scenario = 'changePassword';

		if ( $model->load(Yii::$app->request->post()) )
		{
			if(true ){
				$model->save();
				// $master_version = Yii::$app->db->createCommand('SELECT * FROM tbl_version WHERE tbl_name="tbl_user"')->queryOne();
				// 	$new_version = $master_version['tbl_version']+1;
				// 	$increament_master_fare_version = Yii::$app->db->createCommand('UPDATE tbl_version SET tbl_version='.$new_version.' WHERE tbl_name="tbl_user"')->execute();
					
				return $this->redirect(['view',	'id' => $model->id]);
			}else{
				Yii::$app->getSession()->setFlash('danger','Required fields are missing, please update the user and try again');
			}

			

			

			
		}

		return $this->renderIsAjax('changePassword', compact('model'));
	}

	public function actionQuick()
    {
        $id = ($_POST['id']);
        $this->redirect(['view', 'id' => $id]);
	}
	
	public function actionView($id){

		$model = $this->findModel($id);
		return $this->render('view', [
			'model' => $model,
		]);
	}


	
	protected function findModelCus($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
