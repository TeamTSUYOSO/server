# server

主にelasticsearchの設定関係

## elasticsearch

ターミナルからの操作例

#### テストデータをDBに突っ込む
```sh
$ curl -XPUT localhost:9200/tsuyoso -d @schema.json
./recipe.sh
```

#### DB削除
```sh
$ curl -XDELETE localhost:9200/tsuyoso
```

#### 簡単なクエリを投げるテスト

```sh
$ curl -XGET 'localhost:9200/tsuyoso/recipe/_search?pretty' -d'
{
   "query" : {
        "query_string" : {
            "query" : "name: 唐揚げ"
        }
    }
}'
```

## 構造
### /data/
#### tsuyoso_recipe.csv
GoogleDocsで集めてたテストデータ

### /mapping/
elasticsearch用のマッピングファイル

### /util/
#### insert_data.php
テストデータ(CSV)からelasticsearchにデータを突っ込むためのシェルを生成

```sh
php insert_data.php > recipe.sh
./recipe.sh
```

