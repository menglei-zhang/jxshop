<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016101800716074",

		//商户私钥
		'merchant_private_key' => "MIIEogIBAAKCAQEA29MUpjV6qMhxTQNDRjjiziljnAeCIdOrasrq2yT7qVhZufv3M4QQ53Cxkba7yKZx0cZaYs3+YvvPiVAr2luzaMFc4RQDhuGsAwn7VdFGJHVpHRJU69gyMiM8uwGghQ5S4/Gy7LupzQQv8I7rBArF2A9uNykBj9Q4fZByQ3kDjtJVnCIUqO6cczvU4P0OxXMUVVWBehCCZ7Ca/VQY5Chs357zabKlVhzYzUP3RfmwnUcnB9G51HhzY6/fFYdL8RlsYJzAWIUEfw8rsXnCqaWJOj8zMOEEibakMbGRo+Hvma0RGCSSgZh3OialB2sNrtQVNniLfVRnqAxCFJYwmQ6GkwIDAQABAoIBABTK734VOufyJb1qHJfs0486nQBqOWXntqQgU3ytD9zhzYLpEWXfDRZHM5Sb20FMdSGpiG0mHPTR6ryLR8qct+iluZpUoUzF1dZnwE1KwT67mFi2ni+8wGq7qIfrzHopc6+58fmweow45Cv16Mb2Geao9vzitRIcuccuvZAiRzLgB1bHv4mDKW2N/iBewmTMll45hR+A7oeczoBF3IJBWIJZTBxoYWSC0zaUdTRB4a7C0pd749qwd6GyZK8ei5rWs0f+vcQJ7uPniyBBmWf7V2VwLT3FNtIu7KKAu9+/a37W8YrycWLe+1cvRlrxP35P8pmv3Axq00Wzgg7/CWpb+GkCgYEA/3f0rJVXLZQiSmJxT/XD/ILYzanMDpVz/MF6D250x9kN/z9Pp5hM9WW7ohuN4tmCeXdZr2uJ1bnUxWTF+vefKxLvIeXjw3CW3xInxTojceTMiO13qWLc8suJJWPr1wB0S49rYRNVtpGCtQzDOI+8IDtxnpjMwS5e9cAFdmeihAUCgYEA3EgkuJhKimMRS3XLrs5nj01Zqw6/WdKtWqssZGQS7S8guxr2bf27FgSOqPgTmIwz9XMgzrY6DV10leP+xxFqzhjw5s8lx3GQlKcN3SiclpQyFwAetT7QKS8YAyuhjRYrFiZ6fZHCzkemT3LjMxca5ISmobiDM/pzNAbhvCixO7cCgYB0aoykLPXqPkwWC6BJaNyj8Fit+AeWScLuVpix+YrcG2yGeapp3DyNw4tqxxW0X5xWj+3dw6qvK5zrSw3xXPA6p8kzcFiHkoIeK17WQLUwTKxsZZKXLQc02U2pLUym0H0uha/QMAhqRwsCSEuaNd3r7krLlCQSkHgTkyqqF3X2pQKBgCa81SBVP76IX3E6vN/30kRkIOGxDTt5jhzEv2DELIPjZskgm7eKCE197ayPO7r25OhEH7/aUekpxyfY2WQejv7BahPc19L0CK3rlseOrcLZOtnKnpvW+PpVGs7r7FQIUvlpON8+M9jcxsVv+b5xULxhC4CjFwecAohisW3KZiiPAoGAbPf4r5E4Ddu832ah9KUqCCOu3GfWkWmp5nXniQAX9daF2N/E85CmLEOYd9ONSm+obiceOjSOAi9iJW7NmT9yxsWU3WVGCi8FtyEvAo5KIt47atdQyyvSl4bIh2/yUgQbXX4r4vzn9EQToahhPPSxotSdPB3R+W1C1fplXYXrOjI=",
		
		//异步通知地址  异步需要外部网络支持
		'notify_url' => "http://localhost/pay/notify_url.php",
		
		//同步跳转
		'return_url' => "http://jxshop.com/Order/returnUrl.html",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDIgHnOn7LLILlKETd6BFRJ0GqgS2Y3mn1wMQmyh9zEyWlz5p1zrahRahbXAfCfSqshSNfqOmAQzSHRVjCqjsAw1jyqrXaPdKBmr90DIpIxmIyKXv4GGAkPyJ/6FTFY99uhpiq0qadD/uSzQsefWo0aTvP/65zi3eof7TcZ32oWpwIDAQAB",
);