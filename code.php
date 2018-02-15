<?php

function codeFromNodes($nodes)
{
    $fragments = [];

    foreach ($nodes as $node) {
        $type = $node["type"];
        $value = $node["value"];

        if ($type === "switch") {
            array_push($fragments, "\$inspector->switchIsOnAt({$value})");
        }

        if ($type === "andgate") {
            $children = $node["children"];
            array_push($fragments, join(" && ", codeFromNodes($children)));
        }

        if ($type === "lamp") {
            $children = $node["children"];
            $conditions = join(" ", codeFromNodes($children));

            array_push($fragments, "
                if ({$conditions}) { 
                    \$inspector->switchLampOnAt({$value});
                }      
            ");
        }
    }

    return $fragments;
}

$nodes = [
    [
        "type" => "lamp",
        "value" => "21,26",
        "children" => [
            [
                "type" => "andgate",
                "value" => "20,23",
                "parent" => "...",
                "children" => [
                    [
                        "type" => "switch",
                        "value" => "20,20",
                        "parent" => "...",
                    ],
                    [
                        "type" => "switch",
                        "value" => "22,20",
                        "parent" => "...",
                    ],
                ],
            ],
        ],
    ]
];

$code = join("\n\n", codeFromNodes($nodes));

print_r($code);
