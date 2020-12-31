<div class="cheque-view" style="background-color: #fff;border: 1px solid white;padding-top: 5px;padding-bottom: 20px;padding-left: 10px;">
            
        <div id="payDate" style="margin-top: 0px; text-align: center !important; margin-left: 82%;font-size: 14px;text-transform: uppercase;background-color: #fff;">
           <?= $words = app\components\Converter::convertDate($model->pay_date); ?>
        </div>
        <?php
        use yii\helpers\Html;
        $currentApproved = rtrim($model->signatories_approved,'</SIGNS>');
        $currentApproved = ltrim($currentApproved,'<SIGNS>');
        $currentApproved = explode("PIMS",$currentApproved);
        //signatory three
        if (isset($currentApproved[0])) {
        $sign_one = (new \yii\db\Query())
             ->select(['signature'])
             ->from('users')
             ->where(['id' => $currentApproved[0]])
             ->one();
         }
        //signatory one
             if (isset($currentApproved[1])) {
        $sign_two = (new \yii\db\Query())
             ->select(['signature'])
             ->from('users')
             ->where(['id' => $currentApproved[1]])
             ->one();
         }
        //signatory two
             if (isset($currentApproved[2])) {
        $sign_three = (new \yii\db\Query())
             ->select(['signature'])
             ->from('users')
             ->where(['id' => $currentApproved[2]])
             ->one();
             }
        ?>
        <div id="signatures" style="margin-left: 40%;margin-top:35px">
        <?php
        if ($model->cheque_type == 'Open') {
             if (isset($currentApproved[0])) {
            ?>
            <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_one['signature'],['width' => '45px','height' => '40px']); ?>
            </span>
            <?php
        }
        if (isset($currentApproved[1])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_two['signature'],['width' => '45px','height' => '40px']); ?>
                
            </span>
            <?php
        }
        if (isset($currentApproved[2])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_three['signature'],['width' => '45px','height' => '40px']); ?>
        </span>
            <?php
        }
        }
        ?>
    </div>
    <?php
        if ($model->cheque_type == 'Closed') {
             ?>
             <div style="margin-left: 40%;margin-top:40px"></div>
            <?php
        }
        ?>
        <div id="amount" style="margin-left: 78%;text-align: center; font-size: 20px; margin-top: 27px;background-color: #fff;">
                    *<?= number_format($model->amount,2) ?>*
        </div>
        <div style="display: none">
        <?= $words = app\components\Converter::convertNumber($date = $model->amount);
            $words=str_word_count($words,1);
            $words1='';$words2='';$len=0;
            foreach ($words as $word) {
                $len = strlen($word) + $len;
                if($len <= 31){
                    $words1 = $words1.' '.$word;
                    $len = $len + 1;
                }else{
                    $words2 = $words2.' '.$word;
                    $len = $len + 1;
                }
            }
            ?>
          </div>

          <div id="payee" style="width: 65.3%; margin-top:862px !important ; font-size: 12px; margin-left: -10px; font-weight: bold;text-transform: uppercase;background-color: #fff;">
                     <?= $model->payee ?>
        </div>
        <div id="wordsOne" style="width: 56%; font-weight: bold; font-size: 12px; margin-left: 40px; margin-top: 25px;display: inline-block;border: 0px solid #ccc;box-sizing: border-box;text-transform: uppercase;background-color: #fff;">
                        <?= $words1; ?>
            </div>
          <div id="wordsTwo" style="width: 62%; font-weight: bold; font-size: 12px; margin-left: -10px;margin-top: 18px;display: inline-block;border: 0px solid #ccc;box-sizing: border-box;text-transform: uppercase;background-color: #fff;">
                      <?= $words2; ?>  
            </div>
            <div id="signatures" style="margin-left: 70%;margin-top: -25px">
        <?php
        if (isset($currentApproved[0])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_one['signature'],['width' => '45px','height' => '40px']); ?>
        </span>
            <?php
        }
        if (isset($currentApproved[1])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_two['signature'],['width' => '45px','height' => '40px']); ?>
        </span>
            <?php
        }
        if (isset($currentApproved[2])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_three['signature'],['width' => '45px','height' => '40px']); ?>
        </span>
            <?php
        }
        ?>
        </div>
    </div>