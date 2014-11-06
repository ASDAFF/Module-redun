<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redun/include.php"); // инициализация модуля
//require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redun/prolog.php"); // инициализация модуля
CModule::IncludeModule("iblock");
//--------------------------------------------------------------
if (isset($_POST["STEP"])){
    $STEP=$_POST["STEP"];
} else {
  $STEP=1;
}	
//--------------------------------------------------------------

if (isset($_POST["backButton"])){
  $STEP=$STEP-1;
} else if (isset($_POST["nextButton"])){ 
  $STEP=$_POST["STEP"]+1;
}


if (isset($_POST["Copy"])){
  if (cMainredun::Copy($_POST["IBLOCK_ID"],$_POST["IBLOCK_ID2"],$_POST["idel"])){
    header('Location: '.$APPLICATION->GetCurPage());
  }
}


if ($STEP==3){
    $sTableID = "tbl_rubric"; // ID таблицы
    $oSort = new CAdminSorting($sTableID, "ID", "desc"); // объект сортировки
    $lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка
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
        );
    }


      $cData = new CIBlockElement;
      $arSelect = Array("ID", "NAME");
      $arFilter = Array("IBLOCK_ID"=>$_POST["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
      $rsData = $cData->GetList(Array(), $arFilter, false, Array(), $arSelect);

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
        ));

      while($arRes = $rsData->NavNext(true, "f_")):
  
        // создаем строку. результат - экземпляр класса CAdminListRow
        $row =& $lAdmin->AddRow($f_ID, $arRes); 
        
        // далее настроим отображение значений при просмотре и редаткировании списка
        
        // параметр NAME будет редактироваться как текст, а отображаться ссылкой
        $row->AddInputField("ID");
        $row->AddViewField("NAME", $f_NAME);        
      endwhile;


        $lAdmin->CheckListMode();
      }
    $aTabs = array(
      array("DIV" => "edit1",
            "TAB" => "Инфоблок источник",
            "TITLE"=> "Выбор информационного блока из которого копировать"),
      array("DIV" => "edit2",
            "TAB" => "Инфоблок приемник",
            "TITLE"=> "Выбор информационного блока в который копировать"),
      array("DIV" => "edit3",
            "TAB" => "Элемент",
            "TITLE"=> "Выбор элемента инфоблока для копирования"),
    );
//до этого обработка данных
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); 
//тут вывод данных
?>
<form method="POST" action="<?echo $sDocPath?>" ENCTYPE="multipart/form-data" name="dataload">
<input type="hidden" name="STEP" value="<?echo $STEP;?>">
<?
$tabControl = new CAdminTabControl("tabControl", $aTabs,  false, true);	
$tabControl->Begin();
//-----------------------------------------------------------------------------------------
  $tabControl->BeginNextTab();
    ?>
      <tr>
        <td width="40%">Информационный блок для экспорта:</td>
        <td width="60%">
          <?echo GetIBlockDropDownList($IBLOCK_ID, 'IBLOCK_TYPE_ID', 'IBLOCK_ID', false, 'class="adm-detail-iblock-types"', 'class="adm-detail-iblock-list"');?>
        </td>
      </tr>
    <?
  $tabControl->EndTab();
//-----------------------------------------------------------------------------------------
  $tabControl->BeginNextTab();
    ?>
      <tr>
        <td width="40%">Информационный блок для экспорта:</td>
        <td width="60%">
          <?echo GetIBlockDropDownList($IBLOCK_ID2, 'IBLOCK_TYPE_ID2', 'IBLOCK_ID2', false, 'class="adm-detail-iblock-types"', 'class="adm-detail-iblock-list"');?>
        </td>
      </tr>
    <?
  $tabControl->EndTab();
//-----------------------------------------------------------------------------------------
  $tabControl->BeginNextTab();
   if ($STEP==3){
            $lAdmin->DisplayList();
      }
  $tabControl->EndTab();
//-----------------------------------------------------------------------------------------
?>
<?
// завершение формы - вывод кнопок сохранения изменений
$tabControl->Buttons();
  if ($STEP<2) {?>
    <input type="submit" name="nextButton" value="Далее &gt;&gt;">
  <?} else if ($STEP==2){?>
      <input type="submit" name="backButton" value="&lt;&lt; Назад">
      <input type="submit" name="nextButton" value="Далее &gt;&gt;">
  <?} else if ($STEP==3){?>
      <input type="submit" name="backButton" value="&lt;&lt; Назад">
      <input type="submit" name="Copy" value="Копировать">
  <?}

// завершаем интерфейс закладки
$tabControl->End();
?>
</form>
<script type="text/javaScript">
<!--
BX.ready(function() {
<?if ($STEP < 2):?>
  tabControl.SelectTab("edit1");
  tabControl.DisableTab("edit2");
  tabControl.DisableTab("edit3");
<?elseif ($STEP == 2):?>
  tabControl.SelectTab("edit2");
  tabControl.DisableTab("edit1");
  tabControl.DisableTab("edit3");
<?elseif ($STEP > 2):?>
  tabControl.SelectTab("edit3");
  tabControl.DisableTab("edit1");
  tabControl.DisableTab("edit2");
<?endif;?>
});
//-->
</script>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>