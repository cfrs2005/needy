<?php
/**
 * Created by PhpStorm.
 * User: zhangqingyue01
 * Date: 2018/9/20
 * Time: 20:03
 */
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PayController extends Controller
{

    public $accessToken;


    public function actionRun()
    {
        $list = \app\models\Orders::find()->where('qr_id is not null')->orderBy('order_id desc')->limit(100)->all();
        $this->setToken();
        foreach ($list as $item) {
            if ($this->qrGet($item->qr_id)) {
                $item->trade_status = 1;
                $item->save();
                echo $item->order_id . "\t" . $item->trade_id . "\t" . $item->money . "\t支付成功" . PHP_EOL;
            }
        }
    }


    private function qrGet($qr_id)
    {
        $client = new \Youzan\Open\Client($this->accessToken);
        $method = 'youzan.trades.qr.get';
        $apiVersion = '3.0.0';

        $params = [
            'qr_id' => $qr_id,

        ];
        $response = $client->get($method, $apiVersion, $params);
        if ($response && @$response['response']['qr_trades']) {
            if (!empty($response['response']['qr_trades'])) {
                return $response['response']['qr_trades'][0]['status'] == 'TRADE_RECEIVED';
            }
        }
        return false;
    }


    private function setToken()
    {
        $clientId = Yii::$app->params['client_id'];
        $clientSecret = Yii::$app->params['client_secret'];
        $type = 'self';
        $keys['kdt_id'] = Yii::$app->params['shopid'];;

        $result = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);
        $this->accessToken = $result['access_token'];
    }
}
