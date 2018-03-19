@extends('layouts.default')

@section('title')
	Книги
@stop

@section('subtitle')

@stop

@section('content')

  	<h1>@yield('title')</h1>
    <h2>@yield('subtitle')</h2>

    <ul class="books">
        <?php

            //echo '<pre>'.print_r($books, true).'</pre>';

            $books_list = '';
			$i = 0;

            foreach($books as $book)
            {
            	$books_list .= '<li';
            	if(0 == ($i%3))
            	{
            		$books_list .= ' class="wide_book"';
            	}
				$books_list .= '>';
				$books_list .= '<a href="/base/books/'.$book->id.'">';
                    $books_list .= '<p class="book_name">';
                    	$books_list .= '<strong>'.preg_replace('/ \(.+\)?/i', '', $book->name).'</strong>';
	                    //$books_list .= ', ';
                    	//$books_list .= '('.$book->year.')';
                   	$books_list .= '</p>';
                    $books_list .= '<img src="/data/img/covers/books/'.$covers[$book->id].'.jpg" alt="'.$book->name.' ('.$book->year.')" />';
                    $books_list .= '<p class="book_annotation_short">'.Helpers::words_limit($book->annotation, 35).'</p>';
                $books_list .= '</a>';
                $books_list .= '</li>';
                $i++;
            }

            echo $books_list;

        ?>
    </ul>

    <?php echo $books->links(); ?>

@stop