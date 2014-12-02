<?php
header("Content-type: text/html;charset=utf-8");
class PaymentAction extends Action {
        public function _initialize() {
                Vendor('Alipay.AlipayCore');
                Vendor('Alipay.AlipayNotify');
                Vendor('Alipay.AlipaySubmit');
                Vendor('Alipay.AlipayConfig');
        }

        function alipayto() {


           $kecheng_order = new Model("KechengOrder");
           $order_id = $this->_param("oid");

           $result = $kecheng_order->query("SELECT * FROM kecheng_order WHERE order_id=".$order_id );
            if(empty($result)){
                $msg = mb_convert_encoding("订单不存在","GB2312","UTF-8");
                print "<script language=\"javascript\">alert('$msg');location.href='/Member/Order';</script>";
                exit;
            }
           $data_order = $result[0];
           $alipay_config = aliconfigs();

            //支付类型
            $payment_type = "1";
            //必填，不能修改
            //服务器异步通知页面路径
            $notify_url = "http://www.easytutor.cn/Member/Payment/notify_url";
            //需http://格式的完整路径，不能加?id=123这类自定义参数

            //页面跳转同步通知页面路径
            $return_url = "http://www.easytutor.cn/Member/Payment/return_url";
            //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

            //卖家支付宝帐户
            $seller_email = '315179571@qq.com';
            //必填

            //商户订单号
            $out_trade_no = $data_order['order_sn'];
            //商户网站订单系统中唯一订单号，必填

            //订单名称
            $subject = "伊兔网课程宝";
            //必填

            //付款金额
            $total_fee = $data_order['order_amount'];
            //必填

            //订单描述

            $body = "通过伊兔网订购了课程,总计".$total_fee."元";
            //商品展示地址
            $show_url = "";
            //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html

            //防钓鱼时间戳
            $anti_phishing_key = "";
            //若要使用请调用类文件submit中的query_timestamp函数

            //客户端的IP地址
            $exter_invoke_ip = "";
            //非局域网的外网IP地址，如：221.0.0.1



            $parameter = array(
                "service" => "create_direct_pay_by_user",
                "partner" => trim($alipay_config['partner']),
                "payment_type"	=> $payment_type,
                "notify_url"	=> $notify_url,
                "return_url"	=> $return_url,
                "seller_email"	=> $seller_email,
                "out_trade_no"	=> $out_trade_no,
                "subject"	=> $subject,
                "total_fee"	=> $total_fee,
                "body"	=> $body,
                "show_url"	=> $show_url,
                "anti_phishing_key"	=> $anti_phishing_key,
                "exter_invoke_ip"	=> $exter_invoke_ip,
                "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
            );
            //print_r($parameter);die;
            $alipaySubmit = new AlipaySubmit($alipay_config);
            $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
            echo $html_text;
        }
        public function return_url() {
                $aliapy_config = aliconfigs();
                $alipayNotify = new AlipayNotify($aliapy_config);
                $verify_result = $alipayNotify->verifyReturn();
                //die;
                //var_dump($verify_result );die;
                //if ($verify_result) { //验证成功

                        $out_trade_no = $_GET['out_trade_no']; //获取订单号
                        $trade_no = $_GET['trade_no']; //获取支付宝交易号
                        $total_fee = $_GET['total_fee']; //获取总价格

                        $kecheng_order = new Model("KechengOrder");
                        $data_order = $kecheng_order->where("order_sn='".$out_trade_no."'")->find();
                        $pay_status = $data_order['pay_status'];
						
						$agency = new Model("Agency");
						$data_agency = $agency->where('pkid='.$data_order['agency_id'])->find();
						
                        if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {

                                if ($pay_status == 0) {//未支付
                                    $pay_time = date("Y-m-d H:i:s");
                                    $bespeak_code = rand(100000,999999);
                                    $sql = "UPDATE kecheng_order SET pay_status=1,order_status=1,pay_time='".$pay_time."',bespeak_code='".$bespeak_code."',alipay_code='".$trade_no."' WHERE order_sn='".$out_trade_no."'";
                                    $kecheng_order->query($sql);
                                    
									$postData = array();
									$postData['mobile'] = $data_order['visitor_mobile'];
									$postData['content'] = "【伊兔网】课程验证码：".$data_order['bespeak_code']."，请凭此前往".$data_agency['title']."预约上课，地址在".$data_agency['address']."，电话：".$data_agency['telephone']."。";
									
									import('@.ORG.Util.SmsApi');
									$sms_api = new SmsApi();
									$res = $sms_api->getApiResponse($postData);
									
									$sms_log = new Model("SmsLog");
									$sms_log->create();
									$sms_log->mobile = $postData['mobile'];
									$sms_log->content = $postData['content'];
									$sms_log->result = $res;
									$sms_log->ctime = date("Y-m-d H:i:s");
									$sms_log->add();
			
									//echo $sql;die;
                                    $msg = "支付成功!";
                                    print "<script language=\"javascript\">alert('$msg');location.href='/Member/KechengOrder/index/';</script>";
                                    exit;
                                }

                        } else {
                                //echo "trade_status=" . $_GET['trade_status'];
                                $this->assign('msg','支付失败!');
                        }

                        echo "验证成功<br />";
                        echo "trade_no=" . $trade_no;

                /*} else {


                        $this->assign('msg','验证失败');
                }*/

                redirect("/Member/KechengOrder");
                exit();
                //$this->display();

        }
        public function notify_url() {
                $aliapy_config = aliconfigs();
                $alipayNotify = new AlipayNotify($aliapy_config);
                $verify_result = $alipayNotify->verifyNotify();

                if ($verify_result) { //验证成功

                        $out_trade_no = $_POST['out_trade_no']; //获取订单号
                        $trade_no = $_POST['trade_no']; //获取支付宝交易号
                        $total_fee = $_POST['total_fee']; //获取总价格

                        $kecheng_order = new Model("KechengOrder");
                        $result = $kecheng_order->where("order_sn='.$out_trade_no.'")->select();
                        $pay_status = $result[0]['pay_status'];


                        if ($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') { //交易成功结束
                                $pay_time = date("Y-m-d H:i:s");
                                $kecheng_order->query("UPDATE kecheng_order SET pay_status=1,order_status=1,pay_time='".$pay_time."',alipay_code='".$trade_no."' WHERE order_sn='.$out_trade_no.'");

                                echo "success"; //请不要修改或删除

                                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                        } else {
                                echo "success"; //其他状态判断。普通即时到帐中，其他状态不用判断，直接打印success。

                                //调试用，写文本函数记录程序运行情况是否正常
                                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                        }

                        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                } else {
                        //验证失败
                        echo "fail";

                        //调试用，写文本函数记录程序运行情况是否正常
                        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                }

        }
}
?>