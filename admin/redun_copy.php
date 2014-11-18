<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redun/include.php"); // инициализация модуля
//require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redun/prolog.php"); // инициализация модуля
CModule::IncludeModule("iblock");
//--------------------------------------------------------------
if (isset($_GET["STEP"])){
    $STEP=$_GET["STEP"];
} else {
  $STEP=1;
} 
//--------------------------------------------------------------
if (isset($_GET["IBLOCK_ID"])){
  $IBLOCK_ID=$_GET["IBLOCK_ID"];
}

if (isset($_GET["IBLOCK_ID2"])){
  $IBLOCK_ID2=$_GET["IBLOCK_ID2"];
}

if (isset($_GET["backButton"])){
  $STEP=$STEP-1;
} else if (isset($_GET["nextButton"])){ 
  $STEP=$_GET["STEP"]+1;
}


if (isset($_GET["Copy"])){
 $resultt=cMainredun::Copy($_GET["IBLOCK_ID"],$_GET["IBLOCK_ID2"],$_GET["ID"]);
  if (count($resultt)>"0"){
    header('Location: '.$APPLICATION->GetCurPage()."?status=".count($resultt));
  }
}

    $lAdmin=0;
    $sTableID = "tbl_rubric"; // ID таблицы
    $oSort = new CAdminSorting($sTableID, "TIMESTAMP_X", "ASC"); // объект сортировки
    $lAdmin = new CAdminList($sTableID, Array()); // основной объект списка
    // проверку значений фильтра для удобства вынесем в отдельную функцию
    function CheckFilter()
    {
        global $FilterArr, $lAdmin;
        foreach ($FilterArr as $f) global $$f;
        
        /* 
           здесь проверяем значения переменных $find_имя и, в случае возникновения ошибки, 
           вызываем $lAdmin->AddFilterError("текст_ошибки"). 
        */
        
        return count($lAdmin->arFilterErrors) == 0; // если ошибки есть, вернем false;
    }

    // опишем элементы фильтра
    $FilterArr = Array(
        "find_id",
        "find_name",
        "find_time",
        );

    // инициализируем фильтр
    $lAdmin->InitFilter($FilterArr);

    // если все значения фильтра корректны, обработаем его
    if (CheckFilter())
    {
        // создадим массив фильтрации для выборки CRubric::GetList() на основе значений фильтра
        $arFilter = Array(
            "ID"    => $find_id,
            "NAME"   => $find_lid,
            "DATE_CREATE"   => $find_time,
        );
    }


      $cData = new CIBlockElement;
      $arSelect = Array("ID", "NAME", "DATE_CREATE");
      $arFilter = Array("IBLOCK_ID"=>$_GET["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
      $rsData = $cData->GetList(Array("DATE_CREATE"=>"DSC"), $arFilter, false, Array("nPageSize"=>10), $arSelect);

      // преобразуем список в экземпляр класса CAdminResult
      $rsData = new CAdminResult($rsData, $sTableID);

      // аналогично CDBResult инициализируем постраничную навигацию.
      $rsData->NavStart();

      // отправим вывод переключателя страниц в основной объект $lAdmin
      $lAdmin->NavText($rsData->GetNavPrint(GetMessage("rub_nav")));

      $lAdmin->AddHeaders(array(
          array(  "id"    =>"ID",
            "content"  =>"ID",
            "sort"     =>"id",
            "default"  =>true,
          ),
          array(  "id"    =>"NAME",
            "content"  =>"Заголовок",
            "sort"     =>"name",
            "default"  =>true,
          ),          
          array(  "id"    =>"DATE_CREATE",
            "content"  =>"Дата создания",
            "sort"     =>"time",
            "default"  =>true,
          ),
        ));

      while($arRes = $rsData->NavNext(true, "f_")):
  
        // создаем строку. результат - экземпляр класса CAdminListRow
        $row =& $lAdmin->AddRow($f_ID, $arRes); 
        
        // далее настроим отображение значений при просмотре и редаткировании списка
        
        // параметр NAME будет редактироваться как текст, а отображаться ссылкой
        $row->AddInputField("ID");
        $row->AddViewField("NAME", $f_NAME);        
        $row->AddViewField("TIMESTAMP_X", $f_TIMESTAMP_X);        
      endwhile;


        $lAdmin->CheckListMode();
      
//до этого обработка данных
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); 
//тут вывод данных
?>
<div align="center">
<?  if (isset($_GET["status"])){
      echo "<h2 color='green'>Копирование прошло успешно, скопировано ".$_GET["status"]." эл.</h2>";
    } 
  ?>
<form method="GET" action="<?echo $sDocPath?>" ENCTYPE="multipart/form-data" name="dataload">
<input type="hidden" name="STEP" value="<?echo $STEP;?>">
<input type="hidden" name="IBLOCK_ID" value="<?echo $IBLOCK_ID;?>">
<input type="hidden" name="IBLOCK_ID2" value="<?echo $IBLOCK_ID2;?>">
 <? if ($STEP<2) {?>
    <input type="submit" name="nextButton" class="adm-btn-save" value="Далее &gt;&gt;">
  <?} else if ($STEP==2){?>
      <input type="submit" name="backButton" value="&lt;&lt; Назад">
      <input type="submit" name="nextButton" class="adm-btn-save" value="Далее &gt;&gt;">
  <?} else if ($STEP==3){?>
      <input type="submit" name="backButton" value="&lt;&lt; Назад">
      <input type="submit" name="Copy" class="adm-btn-save" value="Копировать">
  <?}
  ?>
  <hr>
    <? if ($STEP<2) {?>
      <div>
        <p width="40%">Информационный блок для экспорта:</p>
        <p width="60%">
          <?echo GetIBlockDropDownList($IBLOCK_ID, 'IBLOCK_TYPE_ID', 'IBLOCK_ID', false, 'class="adm-detail-iblock-types"', 'class="adm-detail-iblock-list"');?>
        </p>
      </div>
      <?} else if ($STEP==2){?>
      <div>
        <p width="40%">Информационный блок для импорта:</p>
        <p width="60%">
          <?echo GetIBlockDropDownList($IBLOCK_ID2, 'IBLOCK_TYPE_ID2', 'IBLOCK_ID2', false, 'class="adm-detail-iblock-types"', 'class="adm-detail-iblock-list"');?>
        </p>
      </div>
         <?} else if ($STEP==3){
            $lAdmin->DisplayList();
      }?>

</form>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>