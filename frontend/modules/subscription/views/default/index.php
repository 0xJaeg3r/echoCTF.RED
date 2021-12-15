<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\widgets\ListView;

$this->title=Yii::$app->sys->event_name ." Subscriptions";
$this->_url=\yii\helpers\Url::to([null],'https');
$this->registerJsFile("https://js.stripe.com/v3/",['position'=>1]);
$this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.min.js");
//$this->registerJsFile("/js/plugins/sweetalert2.js");

$this->registerCss(file_get_contents(__DIR__."/pricing.css"));
?>
<div class="site-index">
    <div class="body-content">

      <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4 text-primary">Level up your game</h1>
        <p class="lead">Subscriptions help us to keep the platform alive and allows us to focus on developing content.</br>Pick your subscription type and start hacking.</p>
      </div>
<?php
if($mine && $mine->active)
  echo $this->render('_update', ['mine' => $mine,]);
else
  echo $this->render('_create',['mine'=>$mine,'dataProvider'=>$dataProvider]);
?>
    </div>
</div>