<?php


namespace Annotation;



/**
 * Class Serialize
 *
 * @Annotation
 * @package App\Annotation
 */
final class JsonSerialize implements Annotation
{
    public $name;
    public $hidden = false;
    public $comment;
    public $type ;
    public $format;

    public function __construct($params = null)
    {
        $this->name = $params['name'] ?? null;
        $this->comment = $params['comment'] ?? null;
        $this->type = $params['type'] ??  null;
        $this->format = $params['format'] ?? null;
        $this->hidden = isset($params['hidden']) ?
            !strcasecmp($params['hidden'], 'true') || !empty($params['hidden'])
            : false;
    }

}