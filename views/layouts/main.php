<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */




    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
       
    }

    dmstr\web\AdminLteAsset::register($this);
    app\assets\IoniconsAsset::register($this);
    // dmstr\web\AdminLtePluginAsset::register($this);
  
    
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Yii::$app->params['app_name'] ?></title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/favicon-16x16.png">
	<link rel="manifest" href="site.webmanifest">
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

    <?php if(!Yii::$app->user->isGuest):?>
    <?php
                    $user_details  = Yii::$app->db->createCommand('SELECT * FROM user WHERE id="'.Yii::$app->getUser()->id.'"')->queryOne();
                    $user_designation  = Yii::$app->db->createCommand('SELECT name FROM tbl_designation WHERE id="'.$user_details['designation_id'].'"')->queryOne();
                   
                   
                   ?>


        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset,
                'user_details'=>$user_details,
                'user_designation'=>$user_designation
            ]
        ) ?>
        <?php   $designation = Yii::$app->db->createCommand('SELECT designation_id FROM user WHERE id = '.Yii::$app->user->id.'')->queryOne(); ?>
        
        
        <?php if($designation['designation_id']== 1):?>        
        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset, 
             'user_details'=>$user_details,
            'user_designation'=>$user_designation]
        )
        ?>
        <?php elseif($designation['designation_id']== 5):?> 
            <?= $this->render(
            'left_customer.php',
            ['directoryAsset' => $directoryAsset, 
             'user_details'=>$user_details,
            'user_designation'=>$user_designation]
        )
        ?>
        <?php endif ?>    
        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>
     <?php else:?>
        
    <?php endif ?>



    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>

