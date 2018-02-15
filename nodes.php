<?php

function nodesfromTokens($tokens)
{
    $nodes = [];
    $current = null;

    $cursor = 0;
    $length = count($tokens);

    while ($cursor < $length) {
        $token =& $tokens[$cursor];

        // ...these can have children
        if ($token["type"] === "lamp" || $token["type"] === "andgate") {
            if ($current !== null) {
                $token["parent"] =& $current;
                $current["children"][] =& $token;
            } else {
                $nodes[] =& $token;
            }

            $current =& $token;
            $current["children"] = [];
        }
        
        // ...these can't have children
        if ($token["type"] === "switch") {
            $token["parent"] =& $current;
            $current["children"][] =& $token;
        }

        // ...this climbs back up the chain
        if ($token["type"] === "end") {
            $current =& $current["parent"];
        }
        
        $cursor++;
    }

    return $nodes;
}

$tokens = array(
    ["type" => "lamp", "value" => "21,26"],
    ["type" => "andgate", "value" => "20,23"],
    ["type" => "switch", "value" => "20,20"],
    ["type" => "switch", "value" => "22,20"],
    ["type" => "end", "value" => null],
);

$nodes = nodesFromTokens($tokens); // ["type" => "lamp", "value" => "21,26", "children" => ...]
