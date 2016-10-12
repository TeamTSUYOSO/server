# server

### /data/
#### tsuyoso_recipe.csv
テストデータ

### /mapping/
elasticsearch用のマッピングファイル

### /util/
#### insert_data.php
テストデータ(CSV)からelasticsearchにデータを突っ込むためのシェルを生成する

`php insert_data.php > recipe.sh`

`./recipe.sh`

### elasticsearch

ターミナルからの操作例

#### テストデータをDBに突っ込む
curl -XPUT localhost:9200/tsuyoso -d @schema.json
./recipe.sh

#### DB削除
curl -XDELETE localhost:9200/tsuyoso

#### 簡単なクエリを投げるテスト

curl -XGET 'localhost:9200/tsuyoso/recipe/_search?pretty' -d'
{
   "query" : {
        "query_string" : {
            "query" : "name: 唐揚げ"
        }
    }
}'
