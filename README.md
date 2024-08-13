## アプリケーション名
確認テスト：お問合せフォーム

## 環境構築
```
$ git clone XXX

# docker-compose.yml のDBの設定を.envに記入
$ cd src
$ cp .env.example .env
$ vi .env
---
DB_HOST=XXX
DB_DATABASE=XXX
DB_USERNAME=XXX
DB_PASSWORD=XXX
---

$ docker-compose up -d --build
$ docker-compose exec php bash
> composer install
> php artisan key:generate
> php artisan migrate
> php artisan db:seed
> php artisan test

# http://localhost:8080
# にアクセスし、phpMyadminでDBを確認
# http://localhost
# にアクセスし、"The stream or file  could not be opened"というエラーが発生した場合は
$ sudo chmod -R 777 src/*

# ログインが画面が表示されたら、右上の「Register」をクリック
# ユーザを登録し、ログイン画面からログイン
# お問い合わせフォームに入力し、お問い合わせデータを登録
# http://localhost/admin
# にアクセスし、お問い合わせデータを検索、削除
```

## 使用技術(実行環境)
- PHP 7.4.9
- Laravel 8.83.8 
- MySQL 8.0.26

## ER図
```mermaid
erDiagram
categories ||--o{ contacts : "1つのcategoryは0以上のcontactを持つ"
users {
bigint id PK
string name "ユーザー名"
string email "メールアドレス"
string password "パスワード"
timestamp created_at
timestamp deleted_at
}
contacts {
	bigint id PK
	references category_id FK
	string first_name "名"
	string last_name "姓"
	tinyint gender "性別"
	string email "メールアドレス"
	string tell "電話番号"
	string address "住所"
	string building "建物名"
	text detail "お問い合わせ内容"
	timestamp created_at
	timestamp updateed_at
}
categories {
	bigint id PK
	text content "お問い合わせの種類"
	timestamp created_at
	timestamp updateed_at
}
```

## URL
- 開発環境：http://localhost/
- MySQL管理画面：http://localhost:8080/
