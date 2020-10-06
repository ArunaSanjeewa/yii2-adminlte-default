<p align="center">
    <a href="eimsky.com" target="_blank">
        <img src="https://github.com/harithmadu/solmik_intercool_service_web/blob/master/image/Login_new1.png?raw=true" height="100px">
    </a>
    <h1 align="center">APT Cool Mate</h1>
    <br>
</p>



Please follow these step for setup:  

1. CLONE the project.

2. COPY `vendor\webvimark\module-user-management` directory to `anywhere in your pc` ,we need this lately.

3. RUN 
    ~~~
    composer install
    ~~~
    make sure you are in root path `solmik_intercool_service_web/`

5. Now please replace that (step 2) COPIED directory to `vendor\webvimark\module-user-management`  
6. Edit the file `config/db.php` with real data, for example:

    ```php
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=yii2basic',
        'username' => 'root',
        'password' => '1234',
        'charset' => 'utf8',
    ];
    ```
