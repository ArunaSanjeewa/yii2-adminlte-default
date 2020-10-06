<?php

use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\models\Designation;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use app\models\Customer;

?>

<script>
function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah')
                            .attr('src', e.target.result)
                            .width(250)
                            ;
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
function checkNicOK(){
    
    var nic = document.getElementById("id_nic");
    var valueOfNic = nic.value;
    var nicStatus;
    if(valueOfNic.length < 10){
        document.getElementById("id_nic").style.color = "#555";
    }
    else if(valueOfNic.length == 10){
        nicStatus =  /^[0-9]{9}[vVxX]$/.test(valueOfNic);
        (nicStatus)?document.getElementById("id_nic").style.color = "#555":document.getElementById("id_nic").style.color = "#c44569";
    }
    else if(valueOfNic.length == 12){
        nicStatus =  /^[0-9]{12}$/.test(valueOfNic);
        (nicStatus)?document.getElementById("id_nic").style.color = "#555":document.getElementById("id_nic").style.color = "#c44569";
    }
    else if(valueOfNic.length > 12 || valueOfNic.length == 11 ){
        document.getElementById("id_nic").style.color = "#c44569";
        // document.getElementById('error_nic').innerHTML = "Please enter a valid nic";
    }

    else{
       // document.getElementById("id_nic").style.borderColor = "red";
       // document.getElementById('error_nic').innerHTML = "Please enter a valid nic";
    }

return false;

}
</script>
<!-- ======================================================================================================= -->

<style>
form div.required label.control-label:after {

    content: " * ";

    color: red;

}
</style>
<!-- ======================================================================================================= -->

<div class="user-form">

    <?php $form = ActiveForm::begin(['fieldConfig' => ['inputOptions' => ['autocomplete' => 'off']],'id' => 'user', /* 'layout'=>'horizontal' */ 'validateOnBlur' => false]); ?>
    <div class="portlet light ">
        <p style="margin-top:8px;margin-bottom:8px;">
            <?= Html::a('Manage', ['index'], ['class' => 'btn btn-success']) ?>
        </p>
       
        <div class="portlet-body form">
            <form role="form">
                <div class="form-body">
                    <div class="row">
                      
                        <div class="col-md-6" id="getinfo1">

                            <div class="row ">
                                <div class=" col-md-6">

                                    <?= $form->field($model, 'username')->textInput() ?>
                                </div>
                                <div class=" col-md-6">
                                   
                                    <?= $form->field($model, 'nic', ['enableAjaxValidation' => false])->textInput(['onkeyup' => 'checkNicOK()', 'placeholder' => "NIC", 'id' => 'id_nic', 'maxlength' => "12"]) ?>
                                </div>

                            </div>
                            <div class="row">
                                <div class=" col-md-6">
                                <?= $form->field($model,'full_name')->textInput() ?>
                                </div>
                                <div class=" col-md-6">
                                <?= $form->field($model,'address')->textInput() ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col-md-6">
                                <?= $form->field($model,'first_name')->textInput() ?>
                                </div>
                                <div class=" col-md-6">
                                <?= $form->field($model,'last_name')->textInput() ?>
                                </div>
                            </div>
                            

                            <div class="row ">

                                <?php if ($model->isNewRecord): ?>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'password')->passwordInput([ 'maxlength' => 10, 'autocomplete' => 'off']) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'repeat_password')->passwordInput([ 'maxlength' => 10, 'autocomplete' => 'off']) ?>
                                </div>
                                <?php endif; ?>
                            </div>



                        </div>
  
                        <div class="col-md-6">

                            <div class="row">

                            </div>
                            <div class="row ">
                                <?php if (User::hasPermission('editUserEmail')): ?>
                                <div class="col-md-6 ">
                                    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                                </div>

                                <?php endif; ?>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => 10]) ?>
                                </div>
                            </div>
                            <div class="row ">
                             <div class="col-md-6">
                           
                             <?= $form->field($model, 'file_no')->textInput(['maxlength' => 8]) ?>
                             <?= $form->field($model, 'designation_id')->dropDownList(Designation::getList()) ?>

                           
                              
                               
                                </div>
                                <div class="col-md-6">
                         
                           <?= $form->field($model, 'file')->fileInput(['onchange' => 'readURL(this)']) ?>
                            <img src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?><?php echo '/'.$model->profile_log ?>"
                             class="w3-card img-responsive pic-bordered"  
                                        width="234px" height="20px" 
                                       onerror="this.src='<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?><?php echo '../../../profile_pictures/default_profile.jpg' ?>';"
                                        id="blah" src="#" alt="your image"
                                        >
                        </div>  
                            </div>



                        </div>

                    </div>
                    <br>
                    <div class="form-actions right">
                        <?php if ($model->isNewRecord): ?>
                        <?=
                            Html::submitButton(
                                    UserManagementModule::t('back', 'Create'), ['class' => 'btn btn-success']
                            )
                            ?>
                        <?php else: ?>
                        <?=
                            Html::submitButton(
                                     UserManagementModule::t('back', 'Save'), ['class' => 'btn btn-primary']
                            )
                            ?>
                        <?php endif; ?>

                    </div>
            </form>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

</div>


<style>
#getinfo1 {
    border-right: 1px solid gray;

}
</style>

