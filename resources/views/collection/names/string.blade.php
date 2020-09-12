@php
    /** @var $collection */
    /** @var bool $no_quotes */
    /** @var string $delimiter */
    $output = '';
    $number = count($collection);
    $i = 1;
    foreach($collection as $element) {
        if(!$no_quotes) {$output .= '"';}
        $output .= $element->name;
        if(!$no_quotes) {$output .= '"';}
        if($i < $number) {$output .= $delimiter;}
        $i++;
    }
    echo $output
@endphp