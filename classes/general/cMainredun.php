<?php
setlocale( LC_NUMERIC, '' );
class cMainredun {
    static $MODULE_ID="redun";

    /**
     * Функция копирования
     * @param $From - откуда $To - куда, $ID
     * @return bool
     */
    static function Copy($From, $To, $ID){
        $result=array();
         foreach ($ID as $key => $value) {
            $res = CIBlockElement::GetByID($value);
                while($ob = $res->GetNextElement()){ 
                     $arFields = $ob->GetFields();                      
                     $arProps = $ob->GetProperties();
                }  
            foreach ($arProps as $key => $value) {
                $arProp[$key]=$value["VALUE"];
            }
            $el = new CIBlockElement;
            $arLoadProductArray = Array(
                "NAME"=> htmlspecialchars_decode($arFields["NAME"]),
                "IBLOCK_ID"=> $To,
                "ACTIVE"=> $arFields["ACTIVE"],
                "DATE_ACTIVE_FROM"=> $arFields["DATE_ACTIVE_FROM"],
                "DATE_ACTIVE_TO"=> $arFields["DATE_ACTIVE_TO"],
                "SORT"=> $arFields["SORT"],
                "PREVIEW_PICTURE"=> CFile::MakeFileArray($arFields["PREVIEW_PICTURE"]),
                "PREVIEW_TEXT"=> htmlspecialchars_decode($arFields["PREVIEW_TEXT"]),
                "PREVIEW_TEXT_TYPE"=> $arFields["PREVIEW_TEXT_TYPE"],
                "DETAIL_PICTURE"=> CFile::MakeFileArray($arFields["DETAIL_PICTURE"]),
                "DETAIL_TEXT"=> htmlspecialchars_decode($arFields["DETAIL_TEXT"]),
                "DETAIL_TEXT_TYPE"=> $arFields["DETAIL_TEXT_TYPE"],
                "SEARCHABLE_CONTENT"=> $arFields["SEARCHABLE_CONTENT"],
                "DATE_CREATE"=> $arFields["DATE_CREATE"],
                "CREATED_BY"=> $arFields["CREATED_BY"],
                "CREATED_USER_NAME"=> $arFields["CREATED_USER_NAME"],
                "TIMESTAMP_X"=> $arFields["TIMESTAMP_X"],
                "MODIFIED_BY"=> $arFields["MODIFIED_BY"],
                "USER_NAME"=> $arFields["USER_NAME"],
                "LANG_DIR"=> $arFields["LANG_DIR"],
                "LIST_PAGE_URL"=> $arFields["LIST_PAGE_URL"],
                "DETAIL_PAGE_URL"=> $arFields["DETAIL_PAGE_URL"],
                "SHOW_COUNTER"=> $arFields["SHOW_COUNTER"],
                "SHOW_COUNTER_START"=> $arFields["SHOW_COUNTER_START"],
                "WF_COMMENTS"=> $arFields["WF_COMMENTS"],
                "WF_STATUS_ID"=> $arFields["WF_STATUS_ID"],
                "LOCK_STATUS"=> $arFields["LOCK_STATUS"],
                "TAGS"=> $arFields["TAGS"],
                "PROPERTY_VALUES"=> $arProp
              );
            
            if($PRODUCT_ID = $el->Add($arLoadProductArray)){
                $result[]="Yes"; 
            }
        }
        return $result;
    }
}