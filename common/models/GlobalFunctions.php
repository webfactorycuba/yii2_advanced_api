<?php

namespace common\models;

use Yii;
use backend\models\i18n\Language;
use backend\models\settings\Setting;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\validators\EmailValidator;

class GlobalFunctions
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const KEY_ENCRYPT_DECRYPT = 'key2020';

    public static function generateRandomString($length = 32)
    {
        return Yii::$app->security->generateRandomString($length);
    }

    /**
     * @return string
     */
    public static function getNoValueSpan()
    {
        return Html::tag("span", Yii::t("backend", "No definido"), ['class' => 'label label-danger']);
    }

    /**
     * Function for get de current date of server
     * @param string $format Optional
     * @return string
     */
    public static function getCurrentDate($format = null)
    {
        date_default_timezone_set('America/Havana');

        if ($format === null)
            $currentDate = date("Y-m-d H:i:s");
        else
            $currentDate = date($format);

        return $currentDate;
    }

    /**
     * Funcion para cambiar el formato 10-Ene-2012 al formato 2012-01-10 quitando conflicto del mes en español
     *
     * @param string $date
     * @return false|string
     */
    public static function formatDateToSaveInDB($date)
    {
        //picar la fecha que viene en formato 01-Ene-2020
        $explode_date = explode('-', $date);

        //obtener el valor del dia
        $day = $explode_date[0];

        //obtener el valor del mes
        $month = $explode_date[1];

        //obtener el valor del año
        $year = $explode_date[2];

        //comparar los meses problematicos y cambiarlos a ingles
        if (GlobalFunctions::isWordInString('Ene', $month)) {
            $month = 'Jan';
        } elseif (GlobalFunctions::isWordInString('Abr', $month)) {
            $month = 'Apr';
        } elseif (GlobalFunctions::isWordInString('Ago', $month)) {
            $month = 'Aug';
        } elseif (GlobalFunctions::isWordInString('Dic', $month)) {
            $month = 'Dec';
        }

        //devolverla en el formato Y-m-d
        $new_date = "$year-$month-$day";

        return date('Y-m-d', strtotime($new_date));
    }

    /**
     * @param $date
     * @return false|string
     */
    public static function formatDateToShowInSystem($date)
    {
        if (isset($date) && !empty($date)) {
            $date_temp = date('d-M-Y', strtotime($date));
            $explode_date = explode('-', $date_temp);
            $month = $explode_date[1];

            if (Yii::$app->language == 'es') {
                if ($month == 'Jan') {
                    $date_change = str_replace('Jan', 'Ene', $date_temp);
                } elseif ($month == 'Apr') {
                    $date_change = str_replace('Apr', 'Abr', $date_temp);
                } elseif ($month == 'Aug') {
                    $date_change = str_replace('Aug', 'Ago', $date_temp);
                } elseif ($month == 'Dec') {
                    $date_change = str_replace('Dec', 'Dic', $date_temp);
                } else {
                    $date_change = $date_temp;
                }

                return $date_change;
            } else {
                return $date_temp;
            }

        } else {
            return $date;
        }

    }

    /**
     * set a session flash message
     * @param string $class
     * @param string $message
     * @return mixed
     */
    public static function setFlashMessage($class, $message)
    {
        $session = Yii::$app->session;
        $session->setFlash($class, $message);
    }

    /**
     * set a session flash message
     * @param string $class
     * @param string $message
     * @return mixed
     */
    public static function addFlashMessage($class, $message)
    {
        $session = Yii::$app->session;
        $session->addFlash($class, $message);
    }

    /**
     * get flag image url
     * @param string $language
     * @return string
     */
    public static function getFlagByLanguage($language)
    {
        if (GlobalFunctions::isTranslationAvailable()) {
            $path = Url::to('@web/uploads/flags/');
            $languages_actives = Language::getLanguagesActives();
            $url_flag = null;

            foreach ($languages_actives AS $index => $lang_active) {
                $code = $lang_active->code;
                $flag_name = $lang_active->image;

                if ($code === $language)
                    $url_flag = $path . $flag_name;
            }

            return $url_flag;
        } else {
            return null;
        }

    }

    /**
     * Get label with css to status of user
     * @param integer $value
     * @param boolean $show_yes_not
     * @return string
     */
    public static function getStatusValue($value, $show_yes_not = NULL, $style_label_no = 'badge bg-red')
    {
        if ($value === self::STATUS_ACTIVE) {
            if ($show_yes_not === NULL) {
                return Html::tag('span', Yii::t('common', 'Activo'), ['class' => 'badge bg-green']);
            } else {
                return Html::tag('span', Yii::t('common', 'SI'), ['class' => 'badge bg-green']);
            }
        } else {
            if ($show_yes_not === NULL) {
                return Html::tag('span', Yii::t('common', 'Inactivo'), ['class' => $style_label_no]);
            } else {
                return Html::tag('span', Yii::t('common', 'NO'), ['class' => $style_label_no]);
            }
        }

    }

    /**
     * @param $string
     * @param $key
     * @return string
     */
    public static function encrypt($string, $key = GlobalFunctions::KEY_ENCRYPT_DECRYPT)
    {
        $result = '';
        $total = strlen($string);
        for ($i = 0; $i < $total; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    /**
     * @param $string
     * @param $key
     * @return string
     */
    public static function decrypt($string, $key = GlobalFunctions::KEY_ENCRYPT_DECRYPT)
    {
        $result = '';
        $string = base64_decode($string);
        $total = strlen($string);

        for ($i = 0; $i < $total; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }

    /**
     * @param null $user_id
     * @return mixed
     */
    public static function getRol($user_id = NULL)
    {
        if (is_null($user_id))
            $user_id = Yii::$app->user->identity->id;

        if (isset(array_keys(Yii::$app->authManager->getRolesByUser($user_id))[0]))
            return array_keys(Yii::$app->authManager->getRolesByUser($user_id))[0];
        else
            '';
    }

    /**
     * Get roles list.
     * @return Array
     */
    public static function getRolesList()
    {
        $models = Yii::$app->authManager->getRoles();

        $value = (count($models) == 0) ? ['' => ''] : ArrayHelper::map($models, 'name', 'name');

        return $value;
    }

    /**
     * @return bool
     */
    public static function isTranslationAvailable()
    {
        $languages_actives = Language::getLanguagesActives();
        $total_languages_actives = count($languages_actives);
        if ($total_languages_actives > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**  BEGIN - FILE UPLOADS FUNCTIONS  */
    /**
     * @return string
     */
    public static function getNoImageDefaultUrl($avatar = false)
    {
        if ($avatar) {
            $url = Url::to('uploads/avatars/avatar_default.jpg');
        } else {
            $url = Url::to('images/noimage_default.jpg');
        }

        return $url;
    }

    /**
     * Función que dado el nombre de la carpeta y el nombre de del fichero devuelve el path
     *
     * @param string $folder
     * @param string $name
     * @return string
     */
    public static function getFileByNamePath($folder, $name)
    {
        $path = 'uploads/' . $folder . '/' . $name;

        return $path;
    }

    /**
     * Función que dado el nombre de la carpeta y el nombre de del fichero devuelve la URL
     *
     * @param string $folder
     * @param string $name
     * @return string
     */
    public static function getFileUrlByNamePath($folder, $name)
    {
        $url = Url::to('@web/uploads/' . $folder . '/' . $name);

        return $url;
    }

    /**
     * @param $dir
     * @param $file
     * @param array $extensions
     * @param null $name_to_download
     * @return bool
     */
    public static function downloadFile($dir, $file, $extensions = [], $name_to_download = null)
    {
        //Si el directorio existe
        if (is_dir($dir)) {
            //Ruta absoluta del archivo
            $path = $dir . $file;

            //Si el archivo existe
            if (is_file($path)) {
                //Obtener información del archivo
                $file_info = pathinfo($path);
                //Obtener la extensión del archivo
                $extension = $file_info["extension"];

                if ($name_to_download !== null) {
                    $filename = $name_to_download . '.' . $extension;
                } else {
                    $filename = $file;
                }

                if (is_array($extensions)) {
                    //Si el argumento $extensions es un array
                    //Comprobar las extensiones permitidas
                    foreach ($extensions as $e) {
                        //Si la extension es correcta
                        if ($e === $extension) {
                            //Procedemos a descargar el archivo
                            // Definir headers
                            $size = filesize($path);
                            header("Content-Type: application/force-download");
                            header("Content-Disposition: attachment; filename=$filename");
                            header("Content-Transfer-Encoding: binary");
                            header("Content-Length: " . $size);
                            // Descargar archivo
                            readfile($path);
                            //Correcto
                            return true;
                        }
                    }
                }
            }
        }
        //Ha ocurrido un error al descargar el archivo
        return false;
    }

    /**
     * Process to deletion any file by url path
     *
     * @return boolean the status of deletion
     */
    public static function deleteFile($url_path)
    {
        // check if file exists on server
        if (empty($url_path) || !file_exists($url_path)) {
            return false;
        }

        // check if file can be deleted on server
        try{
            if (!unlink($url_path)) {
                return false;
            }
        }catch (\Exception $exception){
            Yii::info("Error deleting image on GlobalFunction: " . $url_path);
            Yii::info($exception->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Función para devolver la configuración del FileInput
     *
     * @return array
     */
    public static function getOptionsFileInput($preview, $extensions=[])
    {
        return [
            'allowedFileExtensions'=>(isset($extensions) && !empty($extensions))? $extensions : GlobalFunctions::getImageFormats(),
            'showPreview' => true,
            'defaultPreviewContent'=> $preview,
            'showUpload'=> false,
            'layoutTemplates'=> [
                'main1'=>  '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}</div>{caption}</div>',
            ],
            'fileActionSettings' => [
                'showDrag' => false,
                'showZoom' => true,
                'showUpload' => false,
                'showDelete' => false,
                'showView' => false,
            ],
        ];

    }

    /**
     * Returns an array to configure a preview for kartik FileInput widget using audio, video, img
     * renders according to it extension, other wise render an icon preview
     * @param $path string relative path to file to render
     * @param $name string Name to show in caption
     * @param $options array using custom options for render html tag for preview
     * @return array
     */
    public static function getConfigFileInputWithPreview($path, $name, $extensions=[], $options=[])
    {
        return self::getOptionsFileInput(self::renderPreviewForm($path, $name, $options), $extensions);
    }

    /**
     * Render a preview for file using audio, video, img in grids or index
     * renders according to it extension, other wise render an icon preview
     * @param $path string relative path to file to render
     * @param $name string Name to show in caption
     * @param $options array using custom options for render html tag for preview
     * @return string
     */
    public static function renderPreviewForIndex($path, $name, $options=[])
    {
        return self::renderPreview($path, $name, true, $options);
    }

    /**
     * Render a preview for kartik FileInput widget using audio, video, img
     * renders according to it extension, other wise render an icon preview
     * @param $path string relative path to file to render
     * @param $name string Name to show in caption
     * @param $options array using custom options for render html tag for preview
     * @return string
     */
    public static function renderPreviewForm($path, $name, $options=[]){
        return self::renderPreview($path, $name, false, $options);
    }

    /**
     * Render a preview for file using audio, video, img
     * renders according to it extension, other wise render an icon preview
     * @param $path string relative path to file to render
     * @param $name string Name to show in caption
     * @param $isForIndex boolean true if is for Index row, false for forms preview
     * @param $options array using custom options for render html tag for preview
     * @return string
     */
    private static function renderPreview($path, $name, $isForIndex=false, $options=[])
    {
        if(isset($name, $path) && !empty($name) && !empty($path))
        {
            $icon = 'fa fa-file-o text-danger';
            $explode = explode('.',$path);
            $ext = end($explode);
            $filename = $name . "." . $ext;

            if(ArrayHelper::isIn($ext, GlobalFunctions::getImageFormats())){
                $localOptions = $isForIndex ? [
                    'width' => '100px',
                    'height' => '100%',
                ] : [];
                if(isset($options) && !empty($options)){
                    $options = array_merge($localOptions, $options);
                }
                $preview = Html::img("/{$path}", array_merge(['class' => 'previewAvatar'],$options));
            } else if(ArrayHelper::isIn($ext, GlobalFunctions::getAudioFormats())){
                $localOptions = [
                    'width' => 320,
                    'height' => 25,
                    'controls' => 'controls'
                ];
                if(isset($options) && !empty($options)){
                    $options = array_merge($localOptions, $options);
                }
                return Html::tag("audio", "<source src='/{$path}' type='audio/{$ext}'/>", $options);
            }
            else if(ArrayHelper::isIn($ext, GlobalFunctions::getVideoFormats())){
                $localOptions = [
                    'width' => 320,
                    'height' => 240,
                    'controls' => 'controls'
                ];
                if(isset($options) && !empty($options)){
                    $options = array_merge($localOptions, $options);
                }
                return Html::tag("video", "<source src='/{$path}' type='video/{$ext}'/>", $options);
            }else {
                //Define icon by extension
                if($ext == 'pdf'){
                    $icon= 'fa fa-file-pdf-o text-danger';
                }
                if(ArrayHelper::isIn($ext, GlobalFunctions::getWordFormats())){
                    $icon= 'fa fa-file-word-o text-primary';
                }
                if(ArrayHelper::isIn($ext, GlobalFunctions::getExcelFormats())){
                    $icon= 'fa fa-file-excel-o text-success';
                }
                if(ArrayHelper::isIn($ext, GlobalFunctions::getPowerPointFormats())){
                    $icon= 'fa fa-file-powerpoint-o text-warning';
                }
                if(ArrayHelper::isIn($ext, GlobalFunctions::getCompressFormats())){
                    $icon= 'fa fa-file-zip-o text-default';
                }
                if(ArrayHelper::isIn($ext, ['apk'])){
                    $icon= 'fa fa-android text-success';
                }

                $preview = '
                <div class="file-preview-frame krajee-default  kv-preview-thumb" data-fileindex="0"  data-template="object" title="'.$filename.'"><div class="kv-file-content">
                <div class="kv-preview-data file-preview-other-frame" style="'. ($isForIndex ? '':'width:213px;height:160px;').'">
                <div class="file-preview-other">
                <span class="file-other-icon"><i class="'.$icon.'" style="'. ($isForIndex ? 'font-size: 9rem;' : '') .'"></i></span>
                </div>
                </div>
                </div><div class="file-thumbnail-footer">
                    <div class="file-footer-caption" title="'.$filename.'">
                        <div class="file-caption-info">'.$filename.'</div>
                    </div>
                </div>
                </div>';
            }

            return $preview;
        }
        else
        {
            return Html::img("/" . self::getNoImageDefaultUrl(),['class'=>'previewAvatar']);
        }
    }

    /**  END - FILE UPLOADS FUNCTIONS  */

    /**
     * Function to get array of letters of alphabet to use in row excel
     *
     * @return array
     */
    public static function getArrayAlphabet()
    {
        $alphabet = [
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
            5 => 'E',
            6 => 'F',
            7 => 'G',
            8 => 'H',
            9 => 'I',
            10 => 'J',
            11 => 'K',
            12 => 'L',
            13 => 'M',
            14 => 'N',
            15 => 'O',
            16 => 'P',
            17 => 'Q',
            18 => 'R',
            19 => 'S',
            20 => 'T',
            21 => 'U',
            22 => 'V',
            23 => 'W',
            24 => 'X',
            25 => 'Y',
            26 => 'Z',
            27 => 'AA',
            28 => 'AB',
            29 => 'AC',
            30 => 'AD',
            31 => 'AE',
            32 => 'AF',
            33 => 'AG',
            34 => 'AH',
            35 => 'AI',
            36 => 'AJ',
            37 => 'AK',
            38 => 'AL',
            39 => 'AM',
            40 => 'AN',
            41 => 'AO',
            42 => 'AP',
            43 => 'AQ',
            44 => 'AR',
            45 => 'AS',
            46 => 'AT',
            47 => 'AU',
            48 => 'AV',
            49 => 'AW',
            50 => 'AX',
            51 => 'AY',
            52 => 'AZ',
        ];

        return $alphabet;
    }

    /**
     * Function to convert a number in format int(111.11) or decimal(111.1111.111,52)
     *
     * @param $number
     * @param int $digits
     * @return string
     */
    public static function formatNumber($number, $digits = 0)
    {
        return number_format($number, $digits, ',', '.');
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $date_to_check
     * @return bool
     */
    public static function checkDateInRange($start_date, $end_date, $date_to_check)
    {
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);
        $date_to_check = strtotime($date_to_check);

        if (($date_to_check >= $start_date) && ($date_to_check <= $end_date)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $date_to_check
     * @return bool
     */
    public static function compareDateWithCurrentDate($date_to_check)
    {
        $current_date = strtotime(date('Y-m-d'));
        $date_to_check = strtotime($date_to_check);

        if ($current_date === $date_to_check) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtener la cantidad de dias trancurridos entre dos fechas o en entre una fecha y la fecha actual
     * @param $start_date
     * @param null $end_date
     * @return mixed
     */
    public static function getDaysPassedBetweenDates($start_date, $end_date = null)
    {
        if ($end_date === null) {
            $second_date = date('Y-m-d');
        } else {
            $second_date = $end_date;
        }

        if (strtotime($start_date) === strtotime($end_date)) {
            return 1;
        } else {
            $date1 = new \DateTime($start_date);
            $date2 = new \DateTime($second_date);

            $interval = $date1->diff($date2);

            return $interval->days;
        }
    }

    /**
     * Función para devolver timeago a partir de timestamp
     *
     * @param $timestamp
     * @return string
     */
    public static function getTimeAgoByTimestamp($timestamp)
    {
        $strTime = array(
            Yii::t('common',"segundo"),
            Yii::t('common',"minuto"),
            Yii::t('common',"hora"),
            Yii::t('common',"día"),
            Yii::t('common',"mes"),
            Yii::t('common',"año"));
        $length = array("60", "60", "24", "30", "12", "10");

        $currentTime = time();

        if ($currentTime >= $timestamp) {
            $diff = time() - $timestamp;

            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);

            $label_time_ago = "</b>" . $diff . " " . $strTime[$i] . "(s)";
            return "<b>" . Yii::t('backend', "Hace {label_time_ago}",['label_time_ago' => $label_time_ago]);
        }

    }

    /**
     * Función para buscar una palabra dentro de una cadena de texto sin diferenciar mayúsculas o minúsculas
     *
     * @param $word_to_search
     * @param $string
     * @return bool
     */
    public static function isWordInString($word_to_search, $string)
    {
        if (stristr($string, $word_to_search) === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Returns an array or a string with all day numbers for a specific month
     * @param bool $asArray true if we need a array with days from month
     * @param bool|int $month number of month to evaluate
     * @return array|string
     */
    public static function getDayLabelsFromMonth($asArray = false, $month = false, $year = false)
    {
        if (!$year) $year = date("Y");
        if (!$month) $month = date("n");

        $totalMonthDays = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);

        $labelsString = "";
        $labelsArray = [];
        for ($i = 1; $i < $totalMonthDays; $i += 1) {
            $labelsString .= "$i,";
            array_push($labelsArray, $i);
        }

        $labelsString .= "$totalMonthDays";
        array_push($labelsArray, $totalMonthDays);

        return $asArray ? $labelsArray : $labelsString;

    }

    /**
     * Returns an array or a string with all day numbers of weeks for a specific month
     * @param bool $asArray true if we need a array with days from month
     * @param bool|int $month number of month to evaluate
     * @return array|string
     */
    public static function getWeeksLabelsFromMonth($asArray = false, $month = false, $year = false)
    {
        if (!$year) $year = date("Y");
        if (!$month) $month = date("n");

        $lastday = date("t", mktime(0, 0, 0, $month, 1, $year));
        $no_of_weeks = 0;
        $count_weeks = 0;
        while ($no_of_weeks < $lastday) {
            $no_of_weeks += 7;
            $count_weeks++;
        }

        $labelsString = "";
        $labelsArray = [];
        for ($i = 1; $i < $count_weeks; $i += 1) {
            $labelsString .= "'" . Yii::t("backend", "Semana") . " $i',";
            array_push($labelsArray, $i);
        }

        $labelsString .= "'" . Yii::t("backend", "Semana") . " $count_weeks'";
        array_push($labelsArray, $count_weeks);

        return $asArray ? $labelsArray : $labelsString;

    }

    public static function getMonthLabel($month = null)
    {
        $months = self::getMonthLabels();
        if (!isset($month) || empty($month) || !is_numeric($month)) {
            $month = date("n");
        }

        if ((int)$month < 12) {
            return $months[(int)$month - 1];
        } else {
            $month = date("n");
            return $months[(int)$month - 1];
        }
    }

    /**
     * @param $cc_mails
     * @return bool
     */
    public static function validateCCMails($cc_mails)
    {
        $explode_cc_mails = explode(';', $cc_mails);

        if (count($explode_cc_mails) > 0) {
            foreach ($explode_cc_mails AS $email) {
                $temp_mail = trim($email);

                $validator = new EmailValidator();

                if (!$validator->validate($temp_mail)) {
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Función para devolver la configuración del Ckeditor
     *
     * @return array
     */
    public static function getToolBarForCkEditor($advanced_toolbar = false)
    {
        if ($advanced_toolbar) {
            return [
                [
                    "name" => "row1",
                    "items" => [
                        "Bold", "Italic", "Underline", "Strike", "-",
                        "Subscript", "Superscript", "RemoveFormat", "-",
                        "TextColor", "BGColor", "-",
                        "NumberedList", "BulletedList", "-",
                        "Outdent", "Indent", "-", "Blockquote", "-",
                        "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "list", "indent", "blocks", "align", "bidi", "-",
                        "Link", "Unlink", "Anchor", "-",
                        "ShowBlocks", "Maximize",
                    ],
                ],
                [
                    "name" => "row2",
                    "items" => [
                        "Table", "HorizontalRule", "SpecialChar", "Iframe", "-",
                        "NewPage", "Print", "Templates", "-",
                        "Cut", "Copy", "Paste", "PasteText", "PasteFromWord", "-",
                        "Undo", "Redo", "-",
                        "Find", "SelectAll", "Format", "Font", "FontSize",
                    ],
                ],
            ];
        } else {
            return [
                [
                    'name' => 'row1',
                    'items' => [
                        'Bold', 'Italic', 'Underline', 'Strike', '-',
                        'Subscript', 'Superscript', 'RemoveFormat', '-',
                        'TextColor', 'BGColor', '-',
                        'NumberedList', 'BulletedList', '-'
                    ],
                ]
            ];
        }
    }

//    /**
//     * Función para devolver la configuración del FileInput
//     *
//     * @return array
//     */
//    public static function getOptionsFileInput($preview)
//    {
//        return [
//            'allowedFileExtensions' => self::getImageFormats(),
//            'defaultPreviewContent' => '<img src="' . $preview . '" class="previewAvatar">',
//            'showUpload' => false,
//            'fileActionSettings' => [
//                'showDrag' => false,
//                'showZoom' => true,
//                'showUpload' => false,
//                'showDelete' => false,
//                'showView' => false,
//            ],
//            'browseLabel' => '',
//            'removeLabel' => '',
//            'layoutTemplates' => [
//                'main1' => '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}{upload}{remove}</div>{caption}</div>',
//            ]
//        ];
//
//    }

    /***********************
     *  Extensions section *
     ***********************/

    public static function getImageFormats()
    {
        return ['jpg', 'jpeg', 'png', 'svg', 'psd', 'tiff', 'gif', 'bmp'];
    }

    public static function getAudioFormats()
    {
        return ['mp3', 'wav', 'ogg', 'wma', 'm4a'];
    }

    public static function getVideoFormats()
    {
        return ['avi', 'mkv', 'mpg', 'mpeg', 'mp4', 'mov', 'webm', 'wmv', 'flv'];
    }

    public static function getDocsFormats()
    {
        return ['txt', 'tex', 'ttf', 'pdf'];
    }

    public static function getWordFormats()
    {
        return ['docx', 'odt', 'doc', 'docm', 'dotx', 'dotm'];
    }

    public static function getExcelFormats()
    {
        return ['xls', 'xlsx', 'xlt', 'xml', 'csv', 'ods'];
    }

    public static function getPowerPointFormats()
    {
        return ['pptx', 'ppt', 'pps', 'ppsx', 'ppsm', 'odp'];
    }

    public static function getCompressFormats()
    {
        return ['rar', 'zip', '7z', '7zip', 'gz', 'gzip', 'tar', 'tar.gz', 'tgz'];
    }

    /**
     * Returns true if $ext is a audio, image or video for render a preview
     * @param $ext string
     * @return bool
     */
    public static function checkExtensionForPreview($ext)
    {
        return ArrayHelper::isIn($ext, self::getImageFormats()) ||
            ArrayHelper::isIn($ext, self::getVideoFormats()) ||
            ArrayHelper::isIn($ext, self::getAudioFormats());
    }

    /**
     * @param int $count
     * @param bool $withLabel
     * @return string
     */
    public static function getFormattedViewsCount($count = 0, $withLabel = false)
    {
        $key = $withLabel ? Yii::t("common", "vista") : "";
        $formatted = "";
        switch ($count) {
            case 0:
                $formatted = $count;
                break;
            case $count > 999999:
                $formatted .= round($count /= 10000, 1);
                $key = "Kb " . $key;
                break;
            case $count > 999:
                $formatted .= round($count /= 1000, 1);
                $key = "K " . $key;
                break;
            default:
                $formatted = isset($count) ? $count : 0;
        }

        return $formatted . " " . $key . ($withLabel && $count !==1 ? "s" : "");
    }

    /**
     * @param int $count
     * @param bool $withLabel
     * @return string
     */
    public static function getFormattedDownsCount($count = 0, $withLabel = false)
    {
        $key = $withLabel ? Yii::t("common", "descarga") : "";
        $formatted = "";
        switch ($count) {
            case 0:
                $formatted = $count;
                break;
            case $count >= 999999:
                $formatted .= round($count /= 1000000, 1);
                $key = "Kb " . $key;
                break;
            case $count >= 999:
                $formatted .= round($count /= 1000, 1);
                $key = "K " . $key;
                break;
            default:
                $formatted = isset($count) ? $count : 0;
        }

        return $formatted . " " . $key . ($withLabel && $count !==1 ? "s" : "");
    }

    public static function getMonthLabels()
    {
        return [
            Yii::t("common", "Enero"),
            Yii::t("common", "Febrero"),
            Yii::t("common", "Marzo"),
            Yii::t("common", "Abril"),
            Yii::t("common", "Mayo"),
            Yii::t("common", "Junio"),
            Yii::t("common", "Julio"),
            Yii::t("common", "Agosto"),
            Yii::t("common", "Septiembre"),
            Yii::t("common", "Octubre"),
            Yii::t("common", "Noviembre"),
            Yii::t("common","Diciembre")
        ];
    }

    /**
     * Returns an array with strings as hexadecimal numbers for represent colors
     * @param $length integer
     * @return array
     */
    public static function generateRandomColors($length)
    {
        $colors =[];

        for($i=0; $i<$length; $i++){
            array_push($colors, self::generateRandomColor());
        }

        return $colors;
    }

    /**
     * @return string hexadecimal number for a color
     */
    public static function generateRandomColor()
    {
        $r = str_pad(dechex(mt_rand(0,255)), 2, '0', STR_PAD_LEFT);
        $g = str_pad(dechex(mt_rand(0,255)), 2, '0', STR_PAD_LEFT);
        $b = str_pad(dechex(mt_rand(0,255)), 2, '0', STR_PAD_LEFT);

        return "#{$r}{$g}{$b}";
    }

    /**
     * Returns an array with all years from custom set to current year
     * @param null|string|int $from
     * @param null|string|int $to
     * @return array
     */
    public static function getYearsRange($asString=false, $from=null, $to=null)
    {
        if(!isset($from) || empty($from)){
            $from = "2020"; //Set Logs system start year
        }

        if(!isset($to) || empty($to)){
            $to = date("Y");
        }

        $range = [];
        $from = (int) $from;
        $to = (int) $to;

        if($from > $to){
            $temp = $from;
            $from = $to;
            $to = $temp;
        }

        while ($from <= $to){
            $year = $from++;
            $range["{$year}"] = $asString ? "{$year}":$year;
        }
        return $range;

    }

    public static function seoMetaTags()
    {
        echo Html::tag('meta', '', ['name' => 'robots', 'content' => 'index,follow']) . PHP_EOL;
        echo Html::tag('meta', '', ['name' => 'keywords', 'content' => Setting::getSeoKeywords()]) . "\n    ";
        echo Html::tag('meta', '', ['name' => 'author', 'content' => 'WebFactory (Cuba)']) . PHP_EOL;
    }

    /* BEGIN CUSTOM GROUP PANEL */
    public static function beginCustomPanel($title,$class = 'box box-primary box-solid') {
        $html = '
            <div class="'.$class.'">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        '.$title.'
                    </h3>
        
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
        ';

        return $html;
    }

    public static function endCustomPanel() {
        $html = '
                 </div>
            <!-- /.box-body -->
        </div>
        ';
        return $html;
    }
    /* END CUSTOM GROUP PANEL */
}