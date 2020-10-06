<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use app\models\Designation;

/* @var $this yii\web\View */
/* @var $model app\models\Designation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="designation-form">
<div class="panel panel-default">
		<div class="panel-body">

    <?php $form = ActiveForm::begin(); ?>

  
    <div class="col-md-6">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div> 
    <div class="col-md-6">
    <?= $form->field($model, 'login')->dropDownList(['1'=>'ACTIVE','0'=>'DEACTIVE']) ?>
    </div> 
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'name',
            [
                'class'=>'webvimark\components\StatusColumn',
                'attribute'=>'login',
              
                
              
                'optionsArray'=>[
                    
 
 
                            [Designation::STATUS_ACTIVE, UserManagementModule::t('back', 'ACTIVE'), 'success'],
                            [Designation::STATUS_INACTIVE, UserManagementModule::t('back', 'DEACTIVE'), 'warning'],
                         
                           
                                   
                        ],
               

                    ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>

</div>

