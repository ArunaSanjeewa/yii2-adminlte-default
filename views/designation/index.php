<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use app\models\Designation;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DesignationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Designations';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="outlet-index">

    <div class="panel panel-default">
            <div class="panel-body">

    <p>
        <?= Html::a('Create Designation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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








