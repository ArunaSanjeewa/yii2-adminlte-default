<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\helpers\BaseUrl;
use yii\helpers\Url;

use app\models\Outlet;


use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use app\models\Territory;
use app\models\Customer;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = $model->username;

$this->title = 'User Profile';
$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


          <!-- Main content -->
    <section class="">
    <div class="row">
  <div class="col-md-8">
  <p style="margin-top:8px;margin-bottom:8px;">
          

          
          <?= GhostHtml::a(UserManagementModule::t('back', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
         
         
          <?php if(Yii::$app->user->isSuperadmin):?>
          <?= GhostHtml::a(UserManagementModule::t('back', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
          <?= GhostHtml::a(
      UserManagementModule::t('back', 'Roles and permissions'),
      ['/user-management/user-permission/set', 'id'=>$model->id],
      ['class' => 'btn btn-sm btn-default']
  ) ?>
   
  <?php endif;?>  
  <?= GhostHtml::a(UserManagementModule::t('back', 'Change Password'), ['auth/change-own-password', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger']) ?>

          <!-- <?= GhostHtml::a(UserManagementModule::t('back', 'Delete'), ['delete', 'id' => $model->id], [
      'class' => 'btn btn-sm btn-danger pull-right',
      'data' => [
      'confirm' => UserManagementModule::t('back', 'Are you sure you want to delete this user?'),
      'method' => 'post',
      ],
  ]) 
  
  ?> -->

      </p>
            </div>
            <div class=" col-md-4 ">
            <div class=" ">

<?php $data = ArrayHelper::map($model::find()->all(), 'id', 'username'); ?>

<?php $data = $model::getList(); ?>
<?php yii\widgets\Pjax::begin([]) ?>
<?php echo Select2::widget([

'model' => $model,
'attribute' => 'username',
'data' => $data,
'size' => Select2::SMALL,
'options' => ['placeholder' => 'Search for username ...', 'class' => 'small'],
'pluginOptions' => [
    'allowClear' => true
],
'pluginEvents' => [
    'select2:select' => "function (e) {        
            var id = e.params.data.id;                                    
            console.log(id);
            myFunction(id);                           
            }"
]
]);

?>
<script>
//Parse the drop down selected item to controller 
function myFunction(id) {

    $.ajax({
        url: '<?php echo Url::to(['/./user-management/user/quick']); ?>',

        type: 'post',
        dataType: 'json',
        data: {
            "id": id
        },
        success: function(data) {
            alert(data.id);

        }
    });
}
</script>
<?php yii\widgets\Pjax::end() ?>

</div>
    </div>   
            </div>
<div class="row">
  <div class="col-md-3">

    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-body box-profile">
        <img class="profile-user-img img-responsive img-circle" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?><?php echo '/'. $model->profile_log ?>"
                                                    class="img-responsive w3-card pic-bordered" height="180px"
                                                    width="165px"
                                                    onerror="this.onerror=null;this.src='<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?><?php echo '/../profile_pictures/default_profile.jpg' ?>'" 
                                                    alt="User profile picture">

        <h3 class="profile-username text-center"><?= Html::encode($model->full_name) ?></h3>

        <p class="text-muted text-center "><?= Html::encode($model->username) ?></p>

      
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <b>Status</b> <a class="pull-right"><?php
if ($model->status == 0) {
echo Html::tag('p', 'DEACTIVE', ['class' => 'label label-danger fontS11','style'=>'font-size:10px']);
} elseif ($model->status == 1) {
echo Html::tag('p', 'ACTIVE', ['class' => 'label label-default fontS11','style'=>'font-size:10px;background-color:#30d82c']);
};
?></a>
          </li>
          <li class="list-group-item">
            <b>Designation</b> <a class="pull-right"><?= $model->designation->name ?></a>
          </li>
          <li class="list-group-item">
         
            <b>Role</b> 
            <?php foreach($model->roles as $r ):?>
              <a class="pull-right"><?= ($r->description); ?></a><br>
            <?php  endforeach?>
           
          </li>
        </ul>

     
        <?= Html::a(
                                    '<b>Sign out</b>',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-primary btn-block']
                                ) ?>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

    
    <!-- /.box -->
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#activity" data-toggle="tab">Details</a></li>
        <!-- <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
        <li><a href="#settings" data-toggle="tab">More</a></li> -->
      </ul>
      <div class="tab-content">
        <div class="active tab-pane" id="activity">
         
                                        <?= DetailView::widget([
			'model'      => $model,
			'attributes' => [
				'id',
				[
					'attribute'=>'status',
					'value'=>User::getStatusValue($model->status),
				],
				'username',
                'full_name',
                'nic',
                'address',
                'contact_number',
                'file_no',
				[
					'attribute'=>'email',
					'value'=>$model->email,
					'format'=>'email',
					'visible'=>User::hasPermission('viewUserEmail'),
				],
				[
					'attribute'=>'email_confirmed',
					'value'=>$model->email_confirmed,
					'format'=>'boolean',
					'visible'=>User::hasPermission('viewUserEmail'),
				],
			
				[
					'label'=>UserManagementModule::t('back', 'Roles'),
					'value'=>implode('<br>', ArrayHelper::map(Role::getUserRoles($model->id), 'name', 'description')),
					'visible'=>User::hasPermission('viewUserRoles'),
					'format'=>'raw',
				],
				// [
				// 	'attribute'=>'bind_to_ip',
				// 	'visible'=>User::hasPermission('bindUserToIp'),
				// ],
				// array(
				// 	'attribute'=>'registration_ip',
				// 	'value'=>Html::a($model->registration_ip, "http://ipinfo.io/" . $model->registration_ip, ["target"=>"_blank"]),
				// 	'format'=>'raw',
				// 	'visible'=>User::hasPermission('viewRegistrationIp'),
				// ),
				'created_at:datetime',
				'updated_at:datetime',
			],
		]) ?>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="timeline">
          <!-- The timeline -->
          <ul class="timeline timeline-inverse">
            <!-- timeline time label -->
            <li class="time-label">
                  <span class="bg-red">
                    10 Feb. 2014
                  </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-envelope bg-blue"></i>

              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                <div class="timeline-body">
                  Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                  weebly ning heekya handango imeem plugg dopplr jibjab, movity
                  jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                  quora plaxo ideeli hulu weebly balihoo...
                </div>
                <div class="timeline-footer">
                  <a class="btn btn-primary btn-xs">Read more</a>
                  <a class="btn btn-danger btn-xs">Delete</a>
                </div>
              </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-user bg-aqua"></i>

              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                </h3>
              </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-comments bg-yellow"></i>

              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                <div class="timeline-body">
                  Take me to your leader!
                  Switzerland is small and neutral!
                  We are more like Germany, ambitious and misunderstood!
                </div>
                <div class="timeline-footer">
                  <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                </div>
              </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline time label -->
            <li class="time-label">
                  <span class="bg-green">
                    3 Jan. 2014
                  </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-camera bg-purple"></i>

              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                <div class="timeline-body">
                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                  <img src="http://placehold.it/150x100" alt="..." class="margin">
                </div>
              </div>
            </li>
            <!-- END timeline item -->
            <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
          </ul>
        </div>
        <!-- /.tab-pane -->

        <div class="tab-pane" id="settings">
           
           
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- /.nav-tabs-custom -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->

</section>
<!-- /.content -->