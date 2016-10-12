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

