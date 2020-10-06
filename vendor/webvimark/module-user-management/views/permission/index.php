<?php
use webvimark\extensions\GridBulkActions\GridBulkActions;
use webvimark\extensions\GridPageSize\GridPageSize;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var webvimark\modules\UserManagement\models\rbacDB\search\PermissionSearch $searchModel
 * @var yii\web\View $this
 */
$this->title = UserManagementModule::t('back', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="panel panel-default">
		<div class="panel-body">
	<div class="row">
		
				<div class="col-sm-6">
					 <p style="margin-top:8px;margin-bottom:8px;">
						<?= GhostHtml::a(
						UserManagementModule::t('back', 'Create'),
						['/user-management/permission/create'],
						['class' => 'btn btn-success']
					) ?>
					</p>
				</div>

				
			</div>
	 
				<div class=" text-right">
					<?= GridPageSize::widget(['pjaxId' => 'user-grid-pjax']) ?>
				</div>
		<div class="portlet-body">

		<?php Pjax::begin([
			'id'=>'permission-grid-pjax',
		]) ?>

		<?= GridView::widget([
			'id'=>'permission-grid',
			'dataProvider' => $dataProvider,
			'pager'=>[
				'options'=>['class'=>'pagination pagination-sm'],
				'hideOnSinglePage'=>true,
				'lastPageLabel'=>'>>',
				'firstPageLabel'=>'<<',
			],
			'filterModel' => $searchModel,
			'layout'=>'{items}<div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">{summary}'.GridBulkActions::widget([
						'gridId'=>'permission-grid',
						'actions'=>[Url::to(['bulk-delete'])=>GridBulkActions::t('app', 'Delete'),],
						]).'</div></div>',
			'columns' => [
				['class' => 'yii\grid\SerialColumn', 'options'=>['style'=>'width:10px'] ],

				[
					'attribute'=>'description',
					'value'=>function($model){
							if ( $model->name == Yii::$app->getModule('user-management')->commonPermissionName )
							{
								return Html::a(
									$model->description,
									['view', 'id'=>$model->name],
									['data-pjax'=>0, 'class'=>'label label-primary']
								);
							}
							else
							{
								return Html::a($model->description, ['view', 'id'=>$model->name], ['data-pjax'=>0]);
							}
						},
					'format'=>'raw',
				],
				'name',
				[
					'attribute'=>'group_code',
					'filter'=>ArrayHelper::map(AuthItemGroup::find()->asArray()->all(), 'code', 'name'),
					'value'=>function(Permission $model){
							return $model->group_code ? $model->group->name : '';
						},
				],
				['class' => 'yii\grid\CheckboxColumn', 'options'=>['style'=>'width:10px'] ],
				[
					'class' => 'yii\grid\ActionColumn',
					'contentOptions'=>['style'=>'width:70px; text-align:center;'],
				],
			],
		]); ?>

		<?php Pjax::end() ?>
	</div>
</div>
</div>