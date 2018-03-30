@extends('layouts.default')

@section('title')О сайте@stop

@section('subtitle')FAQ — Часто задаваемые вопросы@stop

@section('content')

    <section class="text-center">
        <h1 class="pt-5">@yield('title')</h1>
        <h2 class="pb-3">@yield('subtitle')</h2>
    </section>

    <div class="row mt-5">
        <div class="col-md-12">
            
            <blockquote class="blockquote">Что это за сайт?</blockquote>
            <div class="mb-5">Бугурт — это система хранения оценок к фильмам, книгам, играм и музыкальным альбомам. Она позволяет составлять коллекции и списки желаемого, и получать рекомендации для просмотра, чтения, игр и прослушивания.</div>

            <blockquote class="blockquote">Почему система так называется?</blockquote>
            <div class="mb-5">
                Исторически «бугурт» — это часть рыцарского турнира, в которой принимают участие две группы сражающихся. В современном переносном значении «бугурт» — это комплекс переживаний, связанных с несовершенством окружающего мира. Бугурт помогает выразить эти переживания с помощью оценок и комментариев.
            </div>

            <blockquote class="blockquote">Здесь можно что-нибудь скачать?</blockquote>
            <div class="mb-5">Только таблицы со своими оценками. Скачиваемого контента — книг, фильмов, игр, альбомов — здесь нет и не будет.</div>

            <blockquote class="blockquote">Есть другие сайты с оценками. Зачем еще один?</blockquote>
            <div class="mb-5">
                На то есть несколько причин. Большинство сайтов с оценками концентрируется только на одной области творчества — фильмах, книгах, играх или музыке. Бугурт содержит четыре крупнейших направления современной культуры, и связи между произведениями и авторами также не ограничены одной областью. Многие системы со временем становятся перегруженными, а Бугурт сконцентрирован на хранении оценок. <!--Кроме того, базы Бугурта находятся в свободном доступе на Github: <a href="https://github.com/lutov/buhurt_database">https://github.com/lutov/buhurt_database</a-->
            </div>

            <blockquote class="blockquote">Почему я не могу найти какое-нибудь произведение?</blockquote>
            <div class="mb-5">
                База сайта наполняется вручную, что, с одной стороны, замедляет темпы, а с другой — позволяет повысить качество базы. Принцип Бугурта - свободная, достоверная и актуальная информация.
                <br/>
                Тем не менее, мы стремимся в первую очередь добавлять достаточно популярные произведения, которые с большой вероятностью будут интересны широкой аудитории. При желании вы можете предложить произведения к добавлению или свою кандидатуру в редакторы сайта по адресу: <a href="mailto:request@buhurt.ru">request@buhurt.ru</a>
            </div>

            <blockquote class="blockquote">Мне нравится этот проект. Можно вас как-то отблагодарить?</blockquote>
            <div class="mb-5">Да, воспользуйтесь формой ниже.</div>

            <iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/donate.xml?account=410013434601073&quickpay=donate&payment-type-choice=on&default-sum=100&targets=%D0%9D%D0%B0+%D0%BF%D0%BE%D0%B4%D0%B4%D0%B5%D1%80%D0%B6%D0%BA%D1%83+%D0%B8+%D1%80%D0%B0%D0%B7%D0%B2%D0%B8%D1%82%D0%B8%D0%B5+%D1%81%D0%B8%D1%81%D1%82%D0%B5%D0%BC%D1%8B&target-visibility=on&project-name=%C2%AB%D0%91%D1%83%D0%B3%D1%83%D1%80%D1%82%C2%BB&project-site=http%3A%2F%2Fwww.free-buhurt.club%2F&button-text=05&successURL=" width="509" height="133"></iframe>

        </div>
    </div>

@stop