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

//до этого обработка данных
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); 
//тут вывод данных
?>
<form method="POST" action="<?echo $sDocPath?>" ENCTYPE="multipart/form-data" name="dataload">
<input type="hidden" name="STEP" value="<?echo $STEP;?>">
<?
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
    $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
    $arFilter = Array("IBLOCK_ID"=>$_POST["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
    while($ob = $res->GetNextElement())
    { 
       echo "<tr>";
       $arFields = $ob->GetFields();
       echo "<td width='10%'><input type='checkbox' name='idel[]' value='".$arFields["ID"]."'></td>";
       echo "<td width='10%'>".$arFields["ID"]."</td>";
       echo "<td width='50%'>".$arFields["NAME"]."</td>";
       echo "<td width='30%'>".$arFields["USER_NAME"]."</td>";
        //echo "(".$arFields["ID"].")".$arFields["NAME"];
      echo "</tr>";
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