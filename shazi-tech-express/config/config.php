<?php
return [
 'db'=>[
  'host'=>getenv('DB_HOST') ?: '127.0.0.1', 'name'=>getenv('DB_NAME') ?: 'shazi_tech_express',
  'user'=>getenv('DB_USER') ?: 'root', 'pass'=>getenv('DB_PASS') ?: '', 'charset'=>'utf8mb4'
 ],
 'app_url'=>rtrim(getenv('APP_URL') ?: 'http://localhost', '/'),
 'paystack'=>[
  'secret_key'=>getenv('PAYSTACK_SECRET_KEY') ?: '',
  'public_key'=>getenv('PAYSTACK_PUBLIC_KEY') ?: ''
 ],
 'vtu'=>[
  'provider'=>getenv('VTU_PROVIDER') ?: 'vtu.ng',
  'base_url'=>rtrim(getenv('VTU_BASE_URL') ?: 'https://vtu.ng/wp-json', '/'),
  'api_key'=>getenv('VTU_API_KEY') ?: '',
  'username'=>getenv('VTU_USERNAME') ?: '',
  'password'=>getenv('VTU_PASSWORD') ?: ''
 ],
];
