<?php


namespace Annotation;


use function iyoule\BizSpace\str_empty;

/**
 * Class Serialize
 *
 * @Annotation
 * @package App\Annotation
 */
final class BizJson implements Annotation
{
    /**
     * @var array
     */
    public $field = [];
    public $hidden = false;
    public $type;
    public $format = false;
    public $require;

    public function __construct($params = null)
    {
        $this->setName($params['field'] ?? null);
        $this->type = $params['type'] ?? null;
        $this->format = $params['format'] ?? null;
        $this->setRequire($params['require'] ?? false);
        $this->setHidden($params['hidden'] ?? false);
    }

    /**
     * @param null $name
     */
    private function setName($name): void
    {
        if (is_string($name)) {
            if (strpos($name, ',') !== false) {
                $name = array_map('trim', explode(',', $name));
            } else {
                $name = [trim($name)];
            }
        } else if (!is_array($name)) {
            $name = [];
        }
        $this->field = $name;

    }


    public function setRequire($value)
    {
        $this->require = !str_empty($value);
    }


    public function setHidden($value)
    {
        $this->hidden = !str_empty($value);
    }


}