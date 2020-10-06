<?php
/**
 * @var yii\widgets\ActiveForm $form
 * @var webvimark\modules\UserManagement\models\rbacDB\Permission $model
 */

use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
	'id'      => 'role-form',
	'layout'=>'horizontal',
	'validateOnBlur' => false,
]) ?>
<div class="portlet light ">
        <p style="margin-top:8px;margin-bottom:8px;">
            <?= Html::a('Manage', ['index'], ['class' => 'btn btn-success']) ?>
        </p>
        
        <div class="portlet-body form">
		<?= $form->field($model, 'description')->textInput(['maxlength' => 255, 'autofocus' => $model->isNewRecord ? true : false]) ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

	<!-- < ?= $form->field($model, 'group_code')->dropDownList(ArrayHelper::map(AuthItemGroup::find()->asArray()->all(), 'code', 'name'), ['prompt' => '']) ?> -->
		</div>
</div>
	
	

	<div class="">
		<div class="col-sm-offset-3 col-sm-9">
			<?php if ( $model->isNewRecord ): ?>
				<?= Html::submitButton(
					 UserManagementModule::t('back', 'Create'),
					['class' => 'btn btn-success']
				) ?>
			<?php else: ?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-ok"></span> ' . UserManagementModule::t('back', 'Save'),
					['class' => 'btn btn-primary']
				) ?>
			<?php endif; ?>
		</div>
	</div>
<?php ActiveForm::end() ?>