<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Designation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Designations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<div class="customer-view">

<p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <div class="row">
  <div class="col-md-3">

    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-body box-profile">
        <img class="profile-user-img img-responsive img-circle" src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?><?php echo '/../image/designation.png' ?>" height="180px" width="165px" onerror="this.onerror=null;this.src='/solmik_intercool_service_web/web/../profile_pictures/default_profile.jpg'" alt="User profile picture">

        <h3 class="profile-username text-center"><?= Html::encode($this->title) ?></h3>

        <p class="text-muted text-center "></p>

      
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <b>Count</b> <a class="pull-right"><p class="label label-default fontS11" style="font-size:10px;background-color:#30d82c"><?= count($system_user_list)?></p></a>
          </li>
        
          
          
        </ul>

     
                </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

   
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
      
        <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">Users</a></li>
       
      </ul>
      <div class="tab-content">
        
        <div class="tab-pane active" id="timeline">
      
       
              <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tbody><tr>
                  <th style="width: 10px">#</th>
                  <th>Username</th>
                  <th>Full Name</th>
                
                </tr>
             <?php foreach($system_user_list as $technician): ?>
                <tr>
                  <td>1.</td>
                  <td><?= $technician->username ?></td>
                  <td><?= $technician->full_name ?></td>
                 
                </tr>
                <?php endforeach ?>
          
              </tbody></table>
            </div>
            <!-- /.box-body -->
                                      
        </div>                        
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





