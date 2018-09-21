<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{

    public $accessToken;

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $orders = \app\models\Orders::find()->orderBy('order_id desc')->limit(10)->all();
        $totals = $this->getSitePayLogs();
        return $this->render('index', [
            'orders' => $orders,
            'totals' => $totals,
        ]);
    }


    /**
     * 获取 支付记录信息
     * @return array
     */
    private function getSitePayLogs()
    {

        $today = $this->getTimes();
        $sql = "select trade_status,count(1) as count,sum(money) as money from orders where starttime>" . $today[0] . " and starttime<" . $today[1] . ' group by trade_status';
        $todayRs = \Yii::$app->db->createCommand($sql)->queryAll();
        $todayResult = ['trader_count' => 0, 'trader_money' => 0];
        if ($todayRs) {
            foreach ($todayRs as $item) {
                if ($item['trade_status'] == 1) {
                    $todayResult['trader_money'] = $item['money'];
                } else {
                    $todayResult['trader_count'] += $item['count'];

                }
            }
        }
        $yesterday = $this->getTimes(1);
        $sql = "select trade_status,count(1) as count,sum(money) as money from orders where starttime>" . $yesterday[0] . " and starttime<" . $yesterday[1] . ' group by trade_status';
        $yesterdayRs = \Yii::$app->db->createCommand($sql)->queryAll();
        $yesterdayResult = ['trader_count' => 0, 'trader_money' => 0];
        if ($yesterdayRs) {
            foreach ($yesterdayRs as $item) {
                if ($item['trade_status'] == 1) {
                    $yesterdayResult['trader_money'] = $item['money'];
                } else {
                    $yesterdayResult['trader_count'] += $item['count'];

                }
            }
        }
        $sql = "select trade_status,count(1) as count,sum(money) as money from orders group by trade_status";
        $allRs = \Yii::$app->db->createCommand($sql)->queryAll();
        $allResult = ['trader_count' => 0, 'trader_money' => 0];

        if ($allRs) {
            foreach ($allRs as $item) {
                if ($item['trade_status'] == 1) {
                    $allResult['trader_money'] = $item['money'];
                } else {
                    $allResult['trader_count'] += $item['count'];

                }
            }
        }
        return [$todayResult, $yesterdayResult, $allResult];
    }


    /**
     * 1 昨天 2 前2天  上周 default 今天
     * @param $dateType
     * @return array
     */
    private function getTimes($dateType = 0)
    {
        $now = time();
        switch ($dateType) {
            case "1":
                $time = strtotime('-1 day', $now);
                $beginTime = date('Y-m-d 00:00:00', $time);
                $endTime = date('Y-m-d 23:59:59', $time);
                break;
            case "2":
                $time = strtotime('-2 day', $now);
                $beginTime = date('Y-m-d 00:00:00', $time);
                $endTime = date('Y-m-d 23:59:59', $now);
                break;
            case "3":
                $time = '1' == date('w') ? strtotime('Monday', $now) : strtotime('last Monday', $now);
                $beginTime = date('Y-m-d 00:00:00', $time);
                $endTime = date('Y-m-d 23:59:59', strtotime('Sunday', $now));
                break;
            default:
                $beginTime = date('Y-m-d 00:00:00', $now);
                $endTime = date('Y-m-d 23:59:59', $now);
        }
        return [
            strtotime($beginTime),
            strtotime($endTime),
        ];

    }

    public function actionOrder()
    {

        $data = [
            'code' => 1,
        ];
        $out_trade_no = \Yii::$app->request->post('out_trade_no', 0);
        $money = \Yii::$app->request->post('money', 0);
        $order = new \app\models\Orders();
        $order->trade_id = $out_trade_no;
        $order->money = $money;
        $order->starttime = time();
        $order->trade_status = 0;
        $this->setToken();
        $info = $this->qrCreate($out_trade_no, $money);
        if ($info && @$info['response']) {
            $order->qr_id = $info['response']['qr_id'];
            $order->save();
            $data = [
                'code' => 0,
                'qr_pc' => $info['response']['qr_code'],
            ];
        }
        echo json_encode($data);
        exit();
    }


    /**
     * 创建 二维码
     * @param $name
     * @param $price
     * @return mixed
     */
    private function qrCreate($name, $price)
    {
        $client = new \Youzan\Open\Client($this->accessToken);
        $method = 'youzan.pay.qrcode.create';
        $apiVersion = '3.0.0';

        $params = [
            'qr_name' => "anjing_" . $name,
            'qr_price' => $price*100,
            'qr_type' => 'QR_TYPE_DYNAMIC',
        ];
        $response = $client->get($method, $apiVersion, $params);
        return $response;

    }


    /**
     * 获取授权token
     */
    private function setToken()
    {
        $clientId = \yii::$app->params['client_id'];
        $clientSecret = \yii::$app->params['client_secret'];
        $type = 'self';
        $keys['kdt_id'] = yii::$app->params['shopid'];;

        $result = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);
        $this->accessToken = $result['access_token'];
    }


}
