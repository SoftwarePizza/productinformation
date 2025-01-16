<?php

class ProductinformationAjaxModuleFrontController extends ModuleFrontController
{
  /**
   * @var bool
   */
  public $ssl = true;

  /**
   * @see FrontController::initContent()
   *
   * @return void
   */
    public function initContent()
    {
      $id_lang = Context::getContext()->language->id;
      $db = \Db::getInstance();
      $name = $_GET['name'] ?? "";

    $query = "
SELECT " . _DB_PREFIX_ . "feature.id_feature, " . _DB_PREFIX_ . "feature_lang.name, " . _DB_PREFIX_ . "feature_value.id_feature_value, "
. _DB_PREFIX_ . "feature_value_lang.value FROM " . _DB_PREFIX_ . "feature INNER JOIN "
. _DB_PREFIX_ . "feature_lang ON (" . _DB_PREFIX_ . "feature.id_feature = "
. _DB_PREFIX_ . "feature_lang.id_feature) INNER JOIN " . _DB_PREFIX_ . "feature_value ON (" .
 _DB_PREFIX_ . "feature.id_feature = " . _DB_PREFIX_ . "feature_value.id_feature) INNER JOIN " .
 _DB_PREFIX_ . "feature_value_lang ON (" . _DB_PREFIX_ . "feature_value.id_feature_value = " . _DB_PREFIX_ .
 "feature_value_lang.id_feature_value) WHERE " . _DB_PREFIX_ . "feature_value_lang.id_lang = " . $id_lang . " AND " . _DB_PREFIX_ .
 "feature_lang.id_lang = " . $id_lang . " AND " . _DB_PREFIX_ . "feature_lang.name = '" . $name . "'";

       $selected = $db::getInstance()->ExecuteS($query);

              header('Content-Type: application/json');
              die(json_encode([
                  'selected' => $selected,
              ]));
    }

}
