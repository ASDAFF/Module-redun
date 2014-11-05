<?
  $aMenu = array(
    "parent_menu" => "global_menu_content", // поместим в раздел "Сервис"
    "sort"        => 100,                    // вес пункта меню
    "url"         => "redun_copy.php",  // ссылка на пункте меню
    "text"        => "Копировать элемент",       // текст пункта меню
    "title"       => "Модуль копирования", // текст всплывающей подсказки
    "icon"        => "form_menu_icon", // малая иконка
    "page_icon"   => "form_page_icon", // большая иконка
    "module_id"   => "redun",
    "items_id"    => "menu_redun"  // идентификатор ветви
  );
  return $aMenu;
?>