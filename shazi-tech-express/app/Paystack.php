<?php
class Paystack {
 public function __construct(private string $secret, private string $appUrl){}
 private function call(string $method,string $path,array $body=[]):array{
  if(!$this->secret)throw new RuntimeException('Paystack is not configured.');
  $ch=curl_init('https://api.paystack.co'.$path);$headers=['Authorization: Bearer '.$this->secret,'Content-Type: application/json','Cache-Control: no-cache'];
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>$headers,CURLOPT_TIMEOUT=>30]);
  if($method!=='GET'){curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body));}
  $raw=curl_exec($ch);$err=curl_error($ch);$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
  if($err)throw new RuntimeException('Payment provider connection failed.');$data=json_decode($raw,true);
  if(!is_array($data)||$code>=400||empty($data['status']))throw new RuntimeException($data['message']??'Payment provider error.');return $data;
 }
 public function initialize(string $email,float $amount,string $reference):array{
  return $this->call('POST','/transaction/initialize',['email'=>$email,'amount'=>(int)round($amount*100),'currency'=>'NGN','reference'=>$reference,'callback_url'=>$this->appUrl.'/payment-callback.php']);
 }
 public function verify(string $reference):array{return $this->call('GET','/transaction/verify/'.rawurlencode($reference));}
 public function validWebhook(string $raw,string $signature):bool{return $signature && hash_equals(hash_hmac('sha512',$raw,$this->secret),$signature);}
}
