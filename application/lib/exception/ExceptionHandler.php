<?php
namespace app\lib\exception;
use think\exception\Handle;
use think\Request;
use think\Log;

class ExceptionHandler extends Handle{
    private $code;
    private $msg;
    private $errorCode;
    //需要返回客户端当前请求的URL路径

    public function render(\Exception $e){
        if($e instanceof BaseException){  //判断错误类型
            //如果是自定义的异常
            $this->code=$e->code;
            $this->msg=$e->msg;
            $this->errorCode=$e->errorCode;
            $this->recordErrorLog($e);//记录日志
        }else{
            if(config('app_debug')){
                return  parent::render($e);
            }else{
                $this->code=500;
                $this->msg='服务器内部错误,不想告诉你';
                $this->errorCode=999;
                $this->recordErrorLog($e);//记录日志
            }
        }
        $request=Request::instance();
        $result=[
            'msg'=>$this->msg,
            'error_code'=>$this->errorCode,
            'request_url'=>$request->url()
        ];
        return json($result,$this->code);
    }
    private function recordErrorLog(\Exception $e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']
        ]);
        Log::record($e->getMessage(),'error');//使用Log的record方法调用日志。
    }
}