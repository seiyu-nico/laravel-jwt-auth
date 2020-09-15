# 構築ログ
```
# composer create-project --prefer-dist laravel/laravel auth "6.*"
# composer require laravel/ui --dev 1.*
# npm install && npm run dev
# composer require tymon/jwt-auth
# php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
# php artisan jwt:secret
# php artisan make:seeder UsersTableSeeder
# php artisan migrate
```

# JWT
- 認証つきAPI要求時は Authorization ヘッダにアクセストークンを付与する。
```
-H "Authorization: Bearer (トークン)
```

デフォルトの有効期限は発行から1時間、リフレッシュ有効期限は発行から2週間(この期限内であれば無効になったトークンを使って新しいトークンを取得することができる)。有効期限を変更する場合は以下の設定を .env に追記する。
| 設定 | デフォルト値 | 説明 |
| :--: | :--: | :--: |
| JWT_TTL | 60 (1時間) | トークンの有効期限 (分) |
| JWT_REFRESH_TTL | 20160 (2週間) | トークンのリフレッシュ有効期限 (分) |


リフレッシュをおこなうとすぐに古いトークンは使えなくなる。リフレッシュと並列に古いトークンでアクセスをおこなうと更新直後に認証が通らなくなってしまう。古いトークンをすぐに失効させたくない場合は、猶予期間を .env に設定する。

| 設定 | デフォルト値 | 説明 |
| :--: | :--: | :--: |
| JWT_BLACKLIST_GRACE_PERIOD | 0 (無効) | ブラックリスト猶予期間 (秒) |


## ログイン
- レスポンスで返ってきたaccess_tokenを今後のapiで常にリクエストに含める
```
# curl -H "Accept: application/json" -XPOST -d 'email=test@test.com&password=testtest' https://auth.localhost/api/login 
{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hdXRoLmxvY2FsaG9zdFwvYXBpXC9sb2dpbiIsImlhdCI6MTYwMDE3NTM1OCwiZXhwIjoxNjAwMTc4OTU4LCJuYmYiOjE2MDAxNzUzNTgsImp0aSI6ImxRdk1SekhKaFdQb3hOMnAiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.l_CH8ijCI8JWK8wqYF2CJ6RsyLm32NanFnkB0J-pRfY","token_type":"bearer","expires_in":3600}
```

## アクセストークンをリクエストに含めて送信する
```
# curl -X GET -H "Accept: application/json" -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hdXRoLmxvY2FsaG9zdFwvYXBpXC9sb2dpbiIsImlhdCI6MTYwMDE3NTM1OCwiZXhwIjoxNjAwMTc4OTU4LCJuYmYiOjE2MDAxNzUzNTgsImp0aSI6ImxRdk1SekhKaFdQb3hOMnAiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.l_CH8ijCI8JWK8wqYF2CJ6RsyLm32NanFnkB0J-pRfY" https://auth.localhost/api/user
{"id":1,"name":"test","email":"test@test.com","email_verified_at":null,"created_at":null,"updated_at":null}
```

## アクセストークンの更新
```
# curl -X GET -H "Accept: application/json" -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hdXRoLmxvY2FsaG9zdFwvYXBpXC9sb2dpbiIsImlhdCI6MTYwMDE3NTM1OCwiZXhwIjoxNjAwMTc4OTU4LCJuYmYiOjE2MDAxNzUzNTgsImp0aSI6ImxRdk1SekhKaFdQb3hOMnAiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.l_CH8ijCI8JWK8wqYF2CJ6RsyLm32NanFnkB0J-pRfY" https://auth.localhost/api/refresh
{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hdXRoLmxvY2FsaG9zdFwvYXBpXC9yZWZyZXNoIiwiaWF0IjoxNjAwMTc1MzU4LCJleHAiOjE2MDAxNzkwMTgsIm5iZiI6MTYwMDE3NTQxOCwianRpIjoiUFp2cktWU3JWanU2NURNeSIsInN1YiI6MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.jZybQbQWDLGdHpk1iPusrXwE1GrDI-Ry08kqMlN3h0o","token_type":"bearer","expires_in":3600}
```

## ログアウト
```
# curl -X POST -H "Accept: application/json" -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hdXRoLmxvY2FsaG9zdFwvYXBpXC9yZWZyZXNoIiwiaWF0IjoxNjAwMTc1MzU4LCJleHAiOjE2MDAxNzkwMTgsIm5iZiI6MTYwMDE3NTQxOCwianRpIjoiUFp2cktWU3JWanU2NURNeSIsInN1YiI6MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.jZybQbQWDLGdHpk1iPusrXwE1GrDI-Ry08kqMlN3h0o" https://auth.localhost/api/logout
{"message":"Successfully logged out"}
```