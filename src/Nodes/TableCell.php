<?php

namespace Tiptap\Nodes;

use Tiptap\Contracts\Node;

class TableCell extends Node
{
    public static $name = 'table_cell';

    public static function parseHTML($DOMNode)
    {
        return [
            [
                'tag' => 'td',
            ],
        ];
    }

    protected static function getAttrs($node)
    {
        $attrs = [];

        if (isset($node->attrs)) {
            if (isset($node->attrs->colspan)) {
                $attrs['colspan'] = $node->attrs->colspan;
            }

            if (isset($node->attrs->colwidth)) {
                if ($widths = $node->attrs->colwidth) {
                    if (count($widths) === $attrs['colspan']) {
                        $attrs['data-colwidth'] = implode(',', $widths);
                    }
                }
            }

            if (isset($node->attrs->rowspan)) {
                $attrs['rowspan'] = $node->attrs->rowspan;
            }
        }

        return $attrs;
    }

    public static function renderHTML($node)
    {
        return [
            'tag' => 'td',
            'attrs' => self::getAttrs($node),
        ];
    }

    public static function data($DOMNode)
    {
        $data = [
            'type' => self::$name,
        ];

        $attrs = [];

        if ($colspan = $DOMNode->getAttribute('colspan')) {
            $attrs['colspan'] = intval($colspan);
        }

        if ($colwidth = $DOMNode->getAttribute('data-colwidth')) {
            $widths = array_map(function ($w) {
                return intval($w);
            }, explode(',', $colwidth));
            if (count($widths) === $attrs['colspan']) {
                $attrs['colwidth'] = $widths;
            }
        }

        if ($rowspan = $DOMNode->getAttribute('rowspan')) {
            $attrs['rowspan'] = intval($rowspan);
        }

        if (! empty($attrs)) {
            $data['attrs'] = $attrs;
        }

        return $data;
    }
}
