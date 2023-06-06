import axios, { isAxiosError } from "axios";
import { readFileSync, writeFileSync } from "fs";

type CSV = string
type ResponseData = SuccessData | ErrorData

/** 成功のResponseではdataフィールドにCSVの文字列が入っている */
type SuccessData = {
  data: CSV
} 
/** エラーの場合(Http status codeが500)の場合は、エラーの情報が入っている) */
type ErrorData = 
{
  errorCode: string
  field: string
  message: string
}

// csvファイルを読み込む例です。適宜値を変えてください。
const body = readFileSync(`${__dirname}/../../sample_data/input_1.csv`, { encoding: "utf8" })

export async function convertData(body: string) {
  // 1つ目のAPIを実行
  const middleData = await postSampleA(body);
  if(!middleData) return

  if (middleData.status != 200) {
    // エラーの場合は、回答スプレッドシートへフィールド、エラーコードを記載してください
    const errorData: ErrorData = middleData.data
    // { errorCode: "XXX", field: "age", message: "年齢のエラー"} のような結果が表示される
    console.log(errorData)
    return;
  }
  const lastData = await postSampleB(middleData.data.data);
  if(!lastData) return
  
  if (lastData.status != 200) {
    // エラーの場合は、回答スプレッドシートへフィールド、エラーコードを記載してください
    const errorData: ErrorData = middleData.data
    console.log(errorData)
    return;
  }

  // 中間データと結果データをファイルに保存
  writeFileSync('teamID_apia_output.csv', (middleData.data as SuccessData).data, 'utf-8');
  writeFileSync('teamID_apib_output.csv', (lastData.data as SuccessData).data, 'utf-8');
}

export async function postSampleA(body: string) {
  try {
    const response = await axios.post('http://127.0.0.1:8000/converta', body);

    return response;
  } catch (error) {
    if (isAxiosError(error)) {
      return error.response;
    };

    console.error('不明なエラーです');
  }
};

export async function postSampleB(body: string) {
  try {
    const response = await axios.post('http://127.0.0.1:8000/convertb', body);

    return response;
  } catch (error) {
    if (isAxiosError(error)){
      return error.response;
    }

    console.error('不明なエラーです');
  }
};


convertData(body);
