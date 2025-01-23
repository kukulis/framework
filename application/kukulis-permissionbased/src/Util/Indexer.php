<?php
// TODO move this to a separate repository as a lib
namespace Kukulis\PermissionBased\Util;
class Indexer
{
    public static function indexBy(array $elements, callable $indexGetter) : array
    {
        $result = [];
        foreach ($elements as $element) {
            $index = call_user_func($indexGetter, $element);
            $result[$index] = $element;
        }

        return $result;
    }

    public static function applyWhenMatch(
        array    $elements,
        array    $sourceElements,
        callable $sourceKeyGetter,
        callable $destinationKeyGetter,
        callable $action
    )  : void
    {
        $indexedSources = self::indexBy($sourceElements, $sourceKeyGetter);
        foreach ($elements as $element) {
            $destinationKey = call_user_func($destinationKeyGetter, $element);
            if (!array_key_exists($destinationKey, $indexedSources)) {
                continue;
            }
            call_user_func($action, $element, $indexedSources[$destinationKey]);
        }
    }
}