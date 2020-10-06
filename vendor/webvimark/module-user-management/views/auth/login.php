<?php

/**
 * @var $this yii\web\View
 * @var $model webvimark\modules\UserManagement\models\forms\LoginForm
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
body{
   background-color: #f5f5f5;
   color: #ffffff;
}
.panel {
	background-color: #1a2226; 
}

.panel-default > .panel-heading {
	color: #ffffff;
    background-color: #1a2226;
    border-color: #ddd;
}

.login-footer {
    color: #ffffff;
    background-color: #1a2226;
    border-color: #ddd;
}

</style>
<div class="container" id="login-wrapper" >

	<div class="row" >


		<div class="col-md-4 col-md-offset-4 " >
			<div class="panel panel-default" style="margin: 0;padding: 0;">
                <div class="panel-body" style="padding: 0;">
				<br>
                    <div class= "center page-logo " align="center">
                        <a class="brand " href="#">
  
                          

							<img style=" width: 100%; height: 100%;" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/../image/<?= Yii::$app->params['login_image'] ?>" height="172px" alt="logo" > </a>
                    </div>
						<div style="padding:6px">
						<center><b><span style=" text-align: center;font-size:14px;font-weight:600;"><?= Yii::$app->params['app_name'] ?></span></b>	</center>
						</div>
					</div>
				<div class="panel-heading" style="padding:6px; text-align: center;font-size:14px;">
				<center><b><span > <?= Yii::$app->params['company_name'] ?></span></b>	</center>
				</div>
						
				<div class="panel-body login-footer">
					<!-- <br><br> -->
                    <h5  >Please Enter Your Username and Password</h5>
                    <br>
					<?php $form = ActiveForm::begin([
					'id' => 'login-form',
					//'action' =>Url::to(['auth/credentials']),
					'options' => ['autocomplete' => 'off'],
					'validateOnBlur' => false,
					'fieldConfig' => [
						'template' => "{input}\n{error}",
					],
				]) ?>

					<?= $form->field($model, 'username')
					->textInput(['placeholder' => 'Username', 'autocomplete' => 'off','autofocus'=>true]) ?>

					 <?= $form->field($model, 'password')
					->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'autocomplete' => 'off','autofocus'=>true]) ?> 

					<!-- <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? $form->field($model, 'rememberMe')->checkbox(['value' => true]) : '' ?> -->
<br>
					<?= Html::submitButton(
					UserManagementModule::t('front', 'LOGIN'),
					['class' => 'btn btn-block btn-primary','style'=>'display: block;
    width: 25%;
    border-radius: 0;
    float: right;
    color: white;
    background-color: '.Yii::$app->params['login_btn_clr'].';
    border: none;']
				) ?>

					<div class="row registration-block">
						<!-- <div class="col-sm-6">
							<?= GhostHtml::a(
							UserManagementModule::t('front', "Registration"),
							['/user-management/auth/registration']
						) ?>
						</div> -->
						<!-- <div class="col-sm-6 text-right">
							<?= GhostHtml::a(
							UserManagementModule::t('front', "Forgot password ?"),
							['/user-management/auth/password-recovery']
						) ?>
						</div> -->
					</div>




					<?php ActiveForm::end() ?>
				</div>
		
			</div>
			<br>
					<div class="copyright dt-center" style="text-align: center;font-size:11px;color:black">
          <span> <?= Yii::$app->params['copy_right'] ?> </span>
			
        </div>
		</div>
		
	</div>
</div>

<style>
	.load-bar {
  position: relative;
  margin-top: 0px;
  width: 100%;
  height: 3px;
  background-color: <?= Yii::$app->params['login_running_bar_clr_0'] ?>;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
}
.bar:nth-child(1) {
  background-color: <?= Yii::$app->params['login_running_bar_clr_1'] ?>;
  animation: loading 3s linear infinite;
}
.bar:nth-child(2) {
  background-color:<?= Yii::$app->params['login_running_bar_clr_2'] ?>;
  animation: loading 3s linear 1s infinite;
}
.bar:nth-child(3) {
  background-color:<?= Yii::$app->params['login_running_bar_clr_3'] ?>;
  animation: loading 3s linear 2s infinite;
}
@keyframes loading {
    from {left: 50%; width: 0;z-index:100;}
    33.3333% {left: 0; width: 100%;z-index: 10;}
    to {left: 0; width: 100%;}
}
</style>
<?php
$css = <<<CSS
html, body {
	
	
	height: 100%;
	min-height: 100%;
	position: relative;
}
#login-wrapper {
	position: relative;
	top: 5%;
}
#login-wrapper .registration-block {
	margin-top: 0px;
}
CSS;

$this->registerCss($css);
?>