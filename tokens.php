<?php

function tokensFrom($code)
{
    $patterns = [
        "lamp" => "LAMP at (\d+\s*\,\s*\d+)",
        "andgate" => "ANDGATE at (\d+\s*\,\s*\d+)",
        "switch" => "SWITCH at (\d+\s*\,\s*\d+)",
        "end" => "end",
        "ignore" => "\s+|from|and",
    ];

    $code = trim($code);
    $tokens = [];

    $length = strlen($code);

    while ($length > 0) {
        foreach ($patterns as $type => $pattern) {
            preg_match("/^{$pattern}/", $code, $matches);

            if (count($matches)) {
                $code = substr($code, strlen($matches[0]));

                if ($type === "ignore") {
                    continue;
                }

                array_push($tokens, [
                    "type" => $type,
                    "value" => count($matches) > 1 ? $matches[1] : null,
                ]);
            }
        }

        if ($length === strlen($code)) {
            $extract = substr($code, 0, 25);
            throw new Exception("unrecognised code near '{$extract}...'");
        }

        $length = strlen($code);
    }

    return $tokens;
}

$code = "
    LAMP at 21,26
        from ANDGATE at 20,23
            from SWITCH at 20,20 and SWITCH at 22,20
";

$tokens = tokensFrom($code); // [["type" => "lamp", "value" => "21,26"], ...]
