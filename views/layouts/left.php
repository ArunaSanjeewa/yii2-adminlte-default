<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
       


                <img src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/<?php echo $user_details['profile_log']; ?>" 
                onerror="this.onerror=null;this.src='<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?><?php echo '/../profile_pictures/default_profile.jpg' ?>'" 
                alt="user_profile"
                class="img-circle" alt="User Image"/>

            </div>
            <div class="pull-left info">
                <p><?php echo Yii::$app->getUser()->username; ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>

            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->


        <?=dmstr\widgets\Menu::widget(
    [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'MAIN NAVIGATION', 'options' => ['class' => 'header']],
            [
                'label' => 'Dashboard',
                'icon' => 'bullseye',
                'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php',
                

                

            ],
            [
                'label' => 'User Administration',
                'icon' => 'user',
                'url' => '#',
                'items' => [
                    [
                        'label' => 'User',
                        'icon' => 'circle-o',
                        'url' => ['#'],
                        'items' => [
                            ['label' => 'Manage', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/user-management/user'],
                            ['label' => 'Create', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/user-management/user/create'],
                        ],
                    ],
                    [
                        'label' => 'Designation',
                        'icon' => 'circle-o',
                        'url' => ['#'],
                        'items' => [
                            ['label' => 'Manage', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/designation/index'],
                            ['label' => 'Create', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/designation/create'],
                        ],
                    ],
                    [
                        'label' => 'Role',
                        'icon' => 'circle-o',
                        'url' => ['#'],
                        'items' => [
                            ['label' => 'Manage', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/user-management/role'],
                            ['label' => 'Create', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/user-management/role/create'],
                        ],
                    ],
                    [
                        'label' => 'Permission',
                        'icon' => 'circle-o',
                        'url' => ['#'],
                        'items' => [
                            ['label' => 'Manage', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/user-management/permission'],
                            ['label' => 'Create', 'icon' => 'circle-o', 'url' => Yii::$app->getUrlManager()->getBaseUrl() . '/index.php/user-management/permission/create'],
                        ],
                    ],

                ],

            ],
           
            








        ],

    ]
)?>

    </section>

</aside>
