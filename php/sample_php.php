<?php

# API1を実行する
$inputFileA = '../sample_data/input_1.csv';
$outputFileA = '../id_apia_output.csv';
$url = 'http://localhost:8000/converta';

$inputFileDataA = file_get_contents($inputFileA);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $inputFileDataA);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
$responseA = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);
$jsonData = json_decode($responseA, true);

// APIの結果がエラーの場合はjsonを出力して終了する
if ($httpcode === 400) {
    echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    file_put_contents('output_1.json', json_encode($jsonData, JSON_UNESCAPED_UNICODE));
    exit('API Aの実行に失敗しました。');
}

// CSVとして出力するための処理
$data = $jsonData['data'];
$rows = explode("\n", $data);
$fileA = fopen($outputFileA, 'w');
foreach ($rows as $row) {
    $fields = explode(",", $row);
    fputcsv($fileA, $fields);
}
fclose($fileA);

# API2を実行する
$inputFileB = '../id_apia_output.csv';
$outputFileB = '../id_apib_output.csv';
$url = 'http://localhost:8000/convertb';

$inputFileDataB = file_get_contents($inputFileB);
// 末尾の改行を削除
//$inputFileDataB = rtrim($inputFileDataB, "\n");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $inputFileDataB);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
$responseB = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);
$jsonData = json_decode($responseB, true);

// APIの結果がエラーの場合はjsonを出力して終了する
if ($httpcode === 400) {
    echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    file_put_contents('output_2.json', json_encode($jsonData, JSON_UNESCAPED_UNICODE));
    exit('API Bの実行に失敗しました。');
}

// CSVとして出力するための処理
$data = $jsonData['data'];
$rows = explode("\n", $data);
$fileB = fopen($outputFileB, 'w');
foreach ($rows as $row) {
    $fields = explode(",", $row);
    fputcsv($fileB, $fields);
}
fclose($fileB);

?>
