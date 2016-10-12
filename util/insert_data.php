<?php

/***************************
*
* Google Spread Sheetのレシピデータを
* ElasticSearchで使えるJSON形式にパースしてコマンド化
*
* php insert_data.php > recipe.sh
*
******************************/

$filepath = "../data/tsuyoso_recipe.csv";
$file = new SplFileObject($filepath); 
$file->setFlags(SplFileObject::READ_CSV);

//config
define("COL_RECIPE_ID", 0);
define("COL_INGREDIENT_NUM", 3);
define("COL_INSTRUCTIONS_NUM", 6);

$recipe_datas = array();
$recipe_id;
foreach ($file as $key => $line) {
    //remove header
    if($key == 0){
        continue;
    }
    if(!is_null($line)){
        if($line[COL_RECIPE_ID] != ''){
            //idあり
            $recipe_id = $line[COL_RECIPE_ID];
            $recipe_datas[$recipe_id]["id"] = $line[0];
            $recipe_datas[$recipe_id]["name"] = $line[1];
            $recipe_datas[$recipe_id]["serving_num"] = $line[2];
            $recipe_datas[$recipe_id]["ingredients_genre"] = $line[8];
            $recipe_datas[$recipe_id]["process"] = $line[9];
            $recipe_datas[$recipe_id]["foods_genre"] = $line[10];
            $recipe_datas[$recipe_id]["takes_time"] = $line[11];
            $recipe_datas[$recipe_id]["kitchenware"] = $line[12];
            $recipe_datas[$recipe_id]["calorie"] = $line[13];
            $recipe_datas[$recipe_id]["price"] = $line[14];
        }
        if($line[COL_INGREDIENT_NUM] != ''){
            //材料あり
            $ingredient = array();
            $ingredient["name"] = $line[COL_INGREDIENT_NUM+1];
            $ingredient["quantity"] = $line[COL_INGREDIENT_NUM+2];
            $recipe_datas[$recipe_id]["ingredients"][] = $ingredient;
        }
        if($line[COL_INSTRUCTIONS_NUM] != ''){
            //材料あり
            $instruction = array();
            $instruction["content"] = $line[COL_INSTRUCTIONS_NUM+1];
            $instruction["order"] = $line[COL_INSTRUCTIONS_NUM];
            $recipe_datas[$recipe_id]["instructions"][] = $instruction;
        }
    }
}

foreach ($recipe_datas as $recipe_id => $recipe_data) {
    echo "curl -XPUT 'http://localhost:9200/tsuyoso/recipe/{$recipe_id}' -d '";
    echo " { ";
    echo "\"id\": {$recipe_data["id"]},";
    echo "\"name\": \"{$recipe_data["name"]}\",";
    echo "\"serving_num\": {$recipe_data["serving_num"]},";
    echo "\"ingredients_genre\": \"{$recipe_data["ingredients_genre"]}\",";
    echo "\"process\": \"{$recipe_data["process"]}\",";
    echo "\"foods_genre\": \"{$recipe_data["foods_genre"]}\",";
    echo "\"takes_time\": {$recipe_data["takes_time"]},";
    echo "\"kitchenware\": \"{$recipe_data["kitchenware"]}\",";
    echo "\"calorie\": {$recipe_data["calorie"]},";
    echo "\"price\": {$recipe_data["price"]},";
    //ingredients
    $is_first = true;
    echo "\"ingredients\" : [";
    foreach ($recipe_data["ingredients"] as $ingredient) {
        if(!$is_first){
            echo ", ";
        }
        echo " { ";
        echo "\"name\": \"{$ingredient["name"]}\",";
        echo "\"quantity\": \"{$ingredient["quantity"]}\"";
        echo "} ";
        $is_first = false;
    }
    echo '],';
    //instructions
    $is_first = true;
    echo "\"instructions\" : [";
    foreach ($recipe_data["instructions"] as $instruction) {
        if(!$is_first){
            echo ", ";
        }
        echo " { ";
        echo "\"content\": \"{$instruction["content"]}\",";
        echo "\"order\": \"{$instruction["order"]}\"";
        echo "}";
        $is_first = false;
    }
    echo ']';
    echo "} '". PHP_EOL;
}