<?php
use yii\helpers\Html;
?>
<div class="leader" >
    <div class="border"></div>
    <div class="leader-wrap">
      <div class="leader-place"><!--<?=$index+1;?>.--></div>
      <div class="leader-ava"><img src="/images/avatars/<?=$model->player->profile->avtr?>"  class="rounded" width="30px"/></div>
      <div class="leader-name" style="width: 100%"><?=$model->player->profile->link?> on <?=Html::a($model->target->name,['/target/default/versus','id'=>$model->target_id,'profile_id'=>$model->player->profile->id]);?></div>
      <div class="leader-score_title" style="width: 50px"><?=number_format($model->timer);?></div>
    </div>
    <div class="leader-bar"><div style="width: 0%" class="bar"></div></div>
    <div class="border"></div>
</div>
