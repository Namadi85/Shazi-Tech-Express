<?php
class VtuProvider {
 public function __construct(private array $cfg){}
 private function token(): string { if(!empty($this->cfg['api_key'])) return $this->cfg['api_key']; throw new RuntimeException('VTU API token is not configured.'); }
 private function call(string $method,string $url,array $params=[]):array{
  $headers=['Accept: application/json','Authorization: Bearer '.$this->token()]; $opts=[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>$headers,CURLOPT_TIMEOUT=>45];
  if($method==='POST'){ $headers[]='Content-Type: application/json';$opts[CURLOPT_HTTPHEADER]=$headers;$opts[CURLOPT_POSTFIELDS]=json_encode($params); }
  curl_setopt_array($ch=curl_init($url),$opts);$raw=curl_exec($ch);$err=curl_error($ch);$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
  if($err)throw new RuntimeException('VTU provider connection failed.');$data=json_decode($raw,true);if(!is_array($data)||$code>=400)throw new RuntimeException($data['message']??'VTU provider error.');return $data;
 }
 public function plans(string $network=''):array{ $url=$this->cfg['base_url'].'/api/v2/variations/data';if($network)$url.='?service_id='.rawurlencode(strtolower($network));$r=$this->call('GET',$url);return $r['data']??[]; }
 public function purchaseData(string $network,string $phone,string $plan,string $reference):array{return $this->call('POST',$this->cfg['base_url'].'/api/v2/data',['request_id'=>$reference,'phone'=>$phone,'service_id'=>strtolower($network),'variation_id'=>(string)$plan]);}
 public function purchaseAirtime(string $network,string $phone,float $amount,string $reference):array{return $this->call('POST',$this->cfg['base_url'].'/api/v2/airtime',['request_id'=>$reference,'phone'=>$phone,'service_id'=>strtolower($network),'amount'=>(int)$amount]);}
 public function verifyCustomer(string $service,string $customer,string $variation):array{return $this->call('POST',$this->cfg['base_url'].'/api/v2/verify-customer',['customer_id'=>$customer,'service_id'=>$service,'variation_id'=>$variation]);}
 public function electricity(string $service,string $customer,string $variation,float $amount,string $reference):array{return $this->call('POST',$this->cfg['base_url'].'/api/v2/electricity',['request_id'=>$reference,'customer_id'=>$customer,'service_id'=>$service,'variation_id'=>$variation,'amount'=>(int)$amount]);}
 public function tvPlans():array{$url=$this->cfg['base_url'].'/api/v2/variations/tv';$r=$this->call('GET',$url);return $r['data']??[];}
 public function tv(string $service,string $customer,string $variation,float $amount,string $reference,string $type='renew'):array{return $this->call('POST',$this->cfg['base_url'].'/api/v2/tv',['request_id'=>$reference,'customer_id'=>$customer,'service_id'=>$service,'variation_id'=>(string)$variation,'subscription_type'=>$type]);}
}
