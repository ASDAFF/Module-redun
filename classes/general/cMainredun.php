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
        $res = CIBlockElement::GetByID($ID[0]);
            while($ob = $res->GetNextElement()){ 
                 $arFields = $ob->GetFields();                      
                 $arProps = $ob->GetProperties();
            }  

        $el = new CIBlockElement;
        $arLoadProductArray = Array(
            "NAME"=> $arFields["NAME"],
            "IBLOCK_ID"=> $To,
            "ACTIVE"=> $arFields["ACTIVE"],
            "DATE_ACTIVE_FROM"=> $arFields["DATE_ACTIVE_FROM"],
            "DATE_ACTIVE_TO"=> $arFields["DATE_ACTIVE_TO"],
            "SORT"=> $arFields["SORT"],
            "PREVIEW_PICTURE"=> $arFields["PREVIEW_PICTURE"],
            "PREVIEW_TEXT"=> $arFields["PREVIEW_TEXT"],
            "PREVIEW_TEXT_TYPE"=> $arFields["PREVIEW_TEXT_TYPE"],
            "DETAIL_PICTURE"=> $arFields["DETAIL_PICTURE"],
            "DETAIL_TEXT"=> $arFields["DETAIL_TEXT"],
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
            "PROPERTY_VALUES"=> $arProps
          );
        if($PRODUCT_ID = $el->Add($arLoadProductArray))
            return true;
        else
          return false; 
    }
}