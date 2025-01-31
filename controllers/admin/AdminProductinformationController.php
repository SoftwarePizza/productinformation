<?php

class AdminProductinformationController extends ModuleAdminController
{
  public function __construct()
  {
    parent::__construct();

    $db = \Db::getInstance();
    $id_lang = (int) Context::getContext()->language->id;

    $this->table = "product_information";
    $this->className = "ProductinformationClass";
    $this->identifier = 'id_product_information';
    $this->_defaultOrderBy = 'id_product_information';
    $this->fields_list = array(
      'id_product_information' => array(
        'title' => $this->trans('ID', [], 'Admin.Global'),
        'align' => 'center',
        'class' => 'fixed-width-xs'
      ),
      'image' => array(
        'title' => $this->trans('Logo', [], 'Admin.Global'),
        'orderby' => false,
        'callback' => 'displayLogo',
        'callback_object' => $this,
        'filter' => false,
        'search' => false,
        'class' => 'fixed-width-xl'
      ),
      'name' => array(
        'title' => $this->trans('Feature Name', [], 'Admin.Global'),
        'orderby' => true,
        'class' => 'fixed-width-xxl'
      ),
      'content' => array(
        'title' => $this->trans('Content', [], 'Admin.Global'),
        'orderby' => true,
        'class' => 'fixed-width-xxl'
      ),
      'value' => array(
        'title' => $this->trans('Value', [], 'Admin.Global'),
        'orderby' => true,
        'class' => 'fixed-width-xxl'
      ),
      'active' => array(
        'title' => $this->trans('Status', [], 'Admin.Global'),
        'align' => 'center',
        'active' => 'status',
        'type' => 'bool',
        'width' => 25,
        'search' => false,
        'orderby' => false,
      ),
    );

    $this->actions = ['edit', 'delete'];

    $this->bulk_actions = array(
      'delete' => array(
        'text'    => 'Delete selected',
        'icon'    => 'icon-trash',
        'confirm' => 'Delete selected items?',
      ),
    );

    $features_query = '
          SELECT ' . _DB_PREFIX_ . 'feature.id_feature, name FROM ' . _DB_PREFIX_ .
      'feature INNER JOIN ' . _DB_PREFIX_ . 'feature_lang ON (' . _DB_PREFIX_ . 'feature.id_feature = '
      . _DB_PREFIX_ . 'feature_lang.id_feature AND ' . _DB_PREFIX_ . 'feature_lang.id_lang = ' . $id_lang . ')';
    $values_query = '
          SELECT ' . _DB_PREFIX_ . 'feature_value.id_feature_value, value FROM ' . _DB_PREFIX_ .
      'feature_value INNER JOIN ' . _DB_PREFIX_ . 'feature_value_lang ON (' . _DB_PREFIX_ . 'feature_value.id_feature_value = '
      . _DB_PREFIX_ . 'feature_value_lang.id_feature_value AND ' . _DB_PREFIX_ . 'feature_value_lang.id_lang = ' . $id_lang . ')';
    $features_selected = $db::getInstance()->ExecuteS($features_query);
    $values_selected = $db::getInstance()->ExecuteS($values_query);

    if ($features_selected === false || $values_query === false) {
      throw new \Exception('Database query failed: ' . Db::getInstance()->getMsgError());
    }

    $features_max = count($features_selected);
    $values_max = count($values_selected);

    $features_options = [];
    $values_options = [];

    for ($i = 0; $i < $features_max; $i++) {
      $features_options[$i] = [
        'id_option' => $features_selected[$i]['name'],
        'name' => $features_selected[$i]['name'],
      ];
    }
    for ($i = 0; $i < $values_max; $i++) {
      $values_options[$i] = [
        'id_option' => $values_selected[$i]['value'],
        'name' => $values_selected[$i]['value'],
      ];
    }

    $this->fields_form = [
      'legend' => [
        'title' => $this->trans('Settings', [], 'Admin.Global'),
      ],
      'input' => [
        [
          'type' => 'select',
          'label' => $this->trans('Feature Name', [], 'Admin.Global'),
          'name' => 'name',
          'size' => 1,
          'required' => true,
          'options' => array(
            'query' => $features_options,
            'id' => 'id_option',
            'name' => 'name'
          ),
        ],
        [
          'type' => 'text',
          'label' => $this->trans('Content', [], 'Admin.Global'),
          'name' => 'content',
          'required' => true
        ],
        [
          'type' => 'select',
          'label' => $this->trans('Value', [], 'Admin.Global'),
          'name' => 'value',
          'size' => 1,
          'disabled' => false,
          'required' => true,
          'options' => array(
            'query' => $values_options,
            'id' => 'id_option',
            'name' => 'name'
          ),
        ],
        [
          'type' => 'file',
          'label' => $this->trans('Picture', [], 'Admin.Global'),
          'name' => 'image',
          'size' => 20,
          'required' => true
        ],
        [
          'type' => 'switch',
          'label' => $this->trans('Status', [], 'Admin.Global'),
          'name' => 'active',
          'is_bool' => true,
          'default_value' => 1,
          'values' => array(
            array(
              'id' => 'active_on',
              'value' => 1,
              'label' => $this->trans('Enabled', [], 'Admin.Global'),
            ),
            array(
              'id' => 'active_off',
              'value' => 0,
              'label' => $this->trans('Disabled', [], 'Admin.Global'),
            )
          )
        ]
      ],
      'submit' => [
        'title' => $this->trans('Save', [], 'Admin.Global'),
        'class' => 'btn btn-default pull-right'
      ]
    ];

    if (isset($_FILES['image'])) {
      // Image storage directory
      $target_dir = _PS_UPLOAD_DIR_ . 'productinformation/';

      // Create custom file name for the uploaded image
      // This is necessary for correct identification of images, deletion, edition & cleanup process
      // $id = isset($_POST['id_product_information']) ? (int)$_POST['id_product_information'] : '0';
      // Id is generated by the database engine, so we can't access it in post data. I would need to make a special database query for this. Not necessary
      $featureName = isset($_POST['name']) ? preg_replace('/[^A-Za-z0-9_-]/', '_', $_POST['name']) : 'FeatureName';
      $featureValue = isset($_POST['value']) ? preg_replace('/[^A-Za-z0-9_-]/', '_', $_POST['value']) : 'FeatureValue';
      $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $customFileName = "{$featureName}_{$featureValue}.{$fileExtension}";

      // Actual file path to upload to
      $target_file = $target_dir . $customFileName;

      $uploadOk = 1;
      // Check if image file is a actual image or fake image
      if (isset($_POST["submit"])) {
        if (getimagesize($_FILES['image']["tmp_name"])) {
          $uploadOk = 1;
        } else {
          $uploadOk = 0;
        }
      }

      // Allow certain file formats
      if (!in_array(strtolower($fileExtension), ['jpg', 'png', 'jpeg', 'gif'])
      ) {
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES['image']["tmp_name"], $target_file)) {
          echo "The file " . basename($_FILES['image']["name"]) . " has been stored under name " . htmlspecialchars($customFileName);
          $_POST['image'] = $customFileName;
        }
      }
    }
    $this->bootstrap = true;
  }

  public function displayLogo($path)
  {
    return '<img width="50px" src="' . _PS_BASE_URL_ . __PS_BASE_URI__ . 'upload/productinformation/' . $path . '">';
  }

  public function processDelete()
  {
      $id = Tools::getValue($this->identifier);
      $object = new ProductinformationClass($id);
  
      if ($object->id && !empty($object->image)) {
          $image_path = _PS_UPLOAD_DIR_ . 'productinformation/' . $object->image;
          if (file_exists($image_path)) {
              unlink($image_path); // Delete the image file
          }
      }
  
      parent::processDelete();
  }

  public function isEnabled($value)
  {
    if ($value) {
      return $this->trans('Yes', [], 'Admin.Global');
    } else {
      return $this->trans('No', [], 'Admin.Global');
    }
  }

  public function setMedia($isNewTheme = false)
  {
    $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/productinformation/views/js/back.js');
    parent::setMedia();
  }
}
