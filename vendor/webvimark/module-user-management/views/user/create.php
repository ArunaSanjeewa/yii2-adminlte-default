<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use app\models\Employee;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = UserManagementModule::t('back', 'User creation');
$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">



	<div class="panel panel-default">
		<div class="panel-body">

			<?= $this->render('_form',['model'=>$model]) ?>
		</div>
	</div>

</div>
