<div style="display: none">
    <?php
        // $accountCheque = (new \yii\db\Query())
        //      ->select(['cheque'])
        //      ->from('accounts')
        //      ->where(['account_id' => $model->account_id])
        //      ->one();
        $bank_code = (new \yii\db\Query())
             ->select(['bank_code'])
             ->from('banks')
             ->where(['bank_id' => $model->bank_id])
             ->one();
        $account_number = (new \yii\db\Query())
             ->select(['account_number'])
             ->from('accounts')
             ->where(['account_id' => $model->account_id])
             ->one();
    ?>
</div>

        <div id="payDate" style="margin-top: 15px; text-align: center !important; margin-left: 78%;font-size: 13px;text-transform: uppercase;">
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
         <?php
      if ($model->cheque_type == 'Open') {
        ?>
        <div id="signatures" style="margin-left: 45%;margin-top: 35px">
        <?php
      }else{
        ?>
        <div id="signatures" style="margin-left: 45%;margin-top: 75px">
        <?php
      }
      ?> 
        <?php
        if ($model->cheque_type == 'Open') {
             if (isset($currentApproved[0])) {
            ?>
            <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_one['signature'],['width' => '50px','height' => '40px']); ?>
            </span>
            <?php
        }
        if (isset($currentApproved[1])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_two['signature'],['width' => '50px','height' => '40px']); ?>
                
            </span>
            <?php
        }
        if (isset($currentApproved[2])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_three['signature'],['width' => '50px','height' => '40px']); ?>
        </span>
            <?php
        }
        }
        ?>
    </div>
        <div id="amount" style="margin-left: 70%;text-align: center; font-size: 20px; margin-top: 30px;">
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

          <div id="payee" style="width: 55%; margin-top:30px !important ; font-size: 10px; margin-left: 40px; font-weight: bold;text-transform: uppercase;">
                <?= $model->payee ?>
        </div>
        <div id="wordsOne" style="width: 56%; font-weight: bold; font-size: 10px; margin-left: 100px; margin-top: 20px;display: inline-block;border: 0px solid #ccc;box-sizing: border-box;text-transform: uppercase;">
               <?= $words1; ?>
            </div>
          <div id="wordsTwo" style="width: 62%; font-weight: bold; font-size: 10px; margin-left: 13px;margin-top: 22px;display: inline-block;border: 0px solid #ccc;box-sizing: border-box;text-transform: uppercase;">
               <?= $words2; ?>  
            </div>
            <div id="signatures" style="margin-left: 67%;margin-top: -30px">
        <?php
        if (isset($currentApproved[0])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_one['signature'],['width' => '50px','height' => '40px']); ?>
        </span>
            <?php
        }
        if (isset($currentApproved[1])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_two['signature'],['width' => '50px','height' => '40px']); ?>
        </span>
            <?php
        }
        if (isset($currentApproved[2])) {
            ?>
        <span>
            <?= Html::img(Yii::getAlias('@webroot').'/signs/'.$sign_three['signature'],['width' => '50px','height' => '40px']); ?>
        </span>
            <?php
        }
        ?>
        </div>
        <div id="font" style="margin-top: 46px; margin-left: 0%;margin-right: 0%;background-color: #bfbfbf;height: 70px;">
               <p style="font-family: cheque, 'Helvetica Neue', Arial, Helvetica, sans-serif;color:#000;font-size: 22px;margin-left: 15%;margin-top: 10px"><guard>C</guard><?= $model->cheque_no; ?><guard>A</guard><?= $bank_code['bank_code']; ?><guard>A</guard>11<guard>D</guard><?= $account_number['account_number']; ?><guard>C</guard></p>
            </div>
    </div>