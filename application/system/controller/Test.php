<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20 0020
 * Time: 15:28
 */

namespace app\system\controller;


class Test
{
    /**
     * 微信第三方测试
     */
    public function index(){
        if( S( 'component_verify_ticket' ) ){
            $this->auth();
        }else{
            echo '授权域名：'. I( 'server.HTTP_HOST' );
            echo '<br>';
            echo '授权接收：'. I( 'server.HTTP_HOST' ).__MODULE__.'/index/setTicket';
            echo '<br>';
            echo 'TOKEN：'. TOKEN;
            echo '<br>';
            echo '响应接收：'. I( 'server.HTTP_HOST' ).__MODULE__.'/respond/index/appid/$APPID$';
            echo '<br>';
            echo '开发域名：'. I( 'server.HTTP_HOST' );
            echo '<br>';
            echo 'IP白名单：'. $this->getLoc();
        }
    }

    private function getLoc(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://ip.chinaz.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36" );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $html = curl_exec($ch);
        curl_close($ch);
        import('Common.Libs.phpQuery.PQ');
        $obj = \phpQuery::newDocumentHTML($html);
        $res = pq($obj)->find('.fz24')->html();
        return $res;
    }
    private function auth(){
        $authorization_code = I('get.auth_code');
        if( !$authorization_code ){
            $url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.OPEN_APPID.'&pre_auth_code='.$this->getPreAuthCode() .'&redirect_uri='.getUrl();
            exit( "<script>window.location.href='".$url."';</script>");
        }
        //获取授权信息
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='.$this->getComToken();
        $param = array(
            'component_appid' => OPEN_APPID,
            'authorization_code' => $authorization_code
        );
        $res = post($url,$param);
        if(!$res['authorization_info'] ){
            exit( '出错' );
        }
        $authorizer_appid = $res['authorization_info']['authorizer_appid'];
        var_dump( $this->getAppInfo( $authorizer_appid ) );
        exit('授权成功');
    }
    public function setTicket() {
        import('Common.Libs.Weixin.wxBizMsgCrypt','','.php');
        $encodingAesKey = EN_KEY;
        $token = TOKEN;
        $appId = OPEN_APPID;
        $timestamp = I('get.timestamp');
        $nonce = I('get.nonce');
        $msg_sign = I('get.msg_signature');
        $encryptMsg = file_get_contents ( 'php://input' );
        $pc = new \WXBizMsgCrypt (TOKEN, EN_KEY, OPEN_APPID );

        $postArr = xml2arr ( $encryptMsg );
        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf ( $format, $postArr ['Encrypt'] );
        $msg = '';
        $errCode = $pc->decryptMsg ( $msg_sign, $timestamp, $nonce, $from_xml, $msg );
        if ($errCode == 0) {
            $param = xml2arr ( $msg );
            switch ($param ['InfoType']) {
                case 'component_verify_ticket' :
                    $component_verify_ticket = $param ['ComponentVerifyTicket'];
                    S('component_verify_ticket',$component_verify_ticket);
                    break;
                case 'unauthorized' :
                    $status = 2;
                    break;
                case 'authorized' :
                    $status = 1;
                    break;
                case 'updateauthorized' :
                    break;
            }
        }
        exit('success');
    }
    private function getPreAuthCode(){
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' .  $this->getComToken();
        $param['component_appid'] = OPEN_APPID;
        $data = post( $url, $param );
        $pre_auth_code = $data['pre_auth_code'];
        if(!$pre_auth_code){
            S( 'component_access_token', null );
        }
        return $pre_auth_code;
    }

}