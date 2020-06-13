# Buhurt Public API

Публичный интерфейс https://buhurt.ru предоставляет доступ к поиску и основным сущностям сайта.

Базовый адрес API: https://buhurt.ru/api

Доступ к публичному интерфейсу предоставляется только для чтения и не тебует авторизации.

Текущая версия API: v0.1.0

**TODO**

* Списочные методы
* Выборки по вторичным сущностям

**Содержание**

1. [Поиск](#search)
1. [Книги](#books)
1. [Фильмы](#films)
1. [Игры](#games)
1. [Альбомы](#albums)

## Search

Поиск по подстроке в полях name и alt_name основных сущностей. Возвращает до трёх результатов по каждой найденной сущности. Поиск НЕ морфологический.

* Path: /search?query=query
* Method: GET
* Variables: _string_ query
* Response format: JSON

### Search Request

`https://buhurt.ru/api/search/?query=sword`

### Search Response

<details><summary>Example</summary>

```
{
  "status": "OK",
  "count": 5,
  "data": {
    "books": {
      "2024": {
        "id": 2024,
        "name": "Перси Джексон и меч Аида",
        "alt_name": [
          "Percy Jackson and the Sword of Hades"
        ]
      },
      "3482": {
        "id": 3482,
        "name": "Меч Рассвета",
        "alt_name": [
          "The Sword of the Dawn",
          "Меч зари",
          "Шпага зари"
        ]
      },
      "4058": {
        "id": 4058,
        "name": "Мечи Ланкмара",
        "alt_name": [
          "The Swords of Lankhmar"
        ]
      }
    },
    "films": {
      "588": {
        "id": 588,
        "name": "Меч в камне",
        "alt_name": [
          "The Sword in the Stone"
        ]
      },
      "687": {
        "id": 687,
        "name": "Меч чужака",
        "alt_name": [
          "Sword of the Stranger",
          "Stranger: Mukou Hadan"
        ]
      },
      "1664": {
        "id": 1664,
        "name": "Пароль «Рыба-меч»",
        "alt_name": [
          "Swordfish"
        ]
      }
    },
    "games": {
      "391": {
        "id": 391,
        "name": "Sword of the Berserk: Guts' Rage",
        "alt_name": [
          "Berserk Millennium Falcon Arc: Chapter of the Flowers of Oblivion"
        ]
      },
      "1135": {
        "id": 1135,
        "name": "Sword With Sauce",
        "alt_name": [
          ""
        ]
      },
      "1248": {
        "id": 1248,
        "name": "Broken Sword 5: The Serpent’s Curse",
        "alt_name": [
          ""
        ]
      }
    },
    "albums": {
      "1134": {
        "id": 1134,
        "name": "The Chronicles of the Black Sword    ",
        "alt_name": null
      },
      "1225": {
        "id": 1225,
        "name": "The Broadsword and the Beast",
        "alt_name": null
      }
    },
    "bands": {
      "184": {
        "id": 184,
        "name": "Sword Coast",
        "alt_name": null
      }
    }
  },
  "url": "https://buhurt.ru/api/search?query=sword",
  "errors": null
}
```

</details>

## Books

Выборки книг.

### Book

Выбор конкретной книги по id.

* Path: /books/{id}
* Method: GET
* Variables: _int_ id
* Response format: JSON

#### Book Request

`https://buhurt.ru/api/books/3482`

#### Book Response

<details><summary>Example</summary>

```
{
  "id": 3482,
  "name": "Меч Рассвета",
  "alt_name": [
    "The Sword of the Dawn",
    "Меч зари",
    "Шпага зари"
  ],
  "description": "Искусные полководцы с чёрными душами кровью и железом установили власть над континентом, и вот уже последний уцелевший город Европы, Афины, охвачен огнём. Удалось спасти только замок Камарг, вовремя переместив его в другое измерение. Но когда в окрестностях замка был обнаружен гранбретанский лазутчик, стало понятно, что и здесь безопасность горстки выживших под большим вопросом. Чтобы окончательно отрезать путь к Камаргу, Хоукмун и д&rsquo;Аверк возвращаются в поверженный мир.",
  "year": 1968,
  "verified": 1,
  "created_at": "2014-06-01 10:00:00",
  "updated_at": "2020-06-13 14:28:54",
  "cover": "/data/img/covers/books/3482.webp?hash=41f7e8020bd9c487a009dfcfbdca7261",
  "rating": {
    "average": 8,
    "count": 1
  },
  "simple_relations": [
    {
      "id": 12400,
      "relation_id": 2,
      "relation": "Приквел",
      "element_type": "Book",
      "element_id": 6718
    },
    {
      "id": 12410,
      "relation_id": 2,
      "relation": "Приквел",
      "element_type": "Book",
      "element_id": 7916
    },
    {
      "id": 12419,
      "relation_id": 1,
      "relation": "Сиквел",
      "element_type": "Book",
      "element_id": 6282
    },
    {
      "id": 12421,
      "relation_id": 1,
      "relation": "Сиквел",
      "element_type": "Book",
      "element_id": 7772
    },
    {
      "id": 12423,
      "relation_id": 1,
      "relation": "Сиквел",
      "element_type": "Book",
      "element_id": 8260
    },
    {
      "id": 12425,
      "relation_id": 1,
      "relation": "Сиквел",
      "element_type": "Book",
      "element_id": 7271
    }
  ],
  "genres": [
    {
      "id": 7,
      "name": "Фантастика и фэнтези"
    }
  ],
  "collections": [],
  "writers": [
    {
      "id": 5023,
      "name": "Майкл Муркок"
    }
  ],
  "books_publishers": [
    {
      "id": 2,
      "name": "Эксмо"
    }
  ],
  "rates": [
    {
      "id": 413,
      "user_id": 1,
      "rate": 8
    }
  ]
}
```

</details>

## Films

Выборки фильмов.

### Film

Выбор конкретного фильма по id.

* Path: /films/{id}
* Method: GET
* Variables: _int_ id
* Response format: JSON

#### Film Request

`https://buhurt.ru/api/films/1664`

#### Film Response

<details><summary>Example</summary>

```
{
  "id": 1664,
  "name": "Пароль «Рыба-меч»",
  "alt_name": [
    "Swordfish"
  ],
  "description": "Гэбриэл Шир является одним из самых опасных шпионов в мире. В молодости он работал на ЦРУ, а теперь стал гениальным преступником. Теперь Гэбриэл хочет украсть миллиард долларов из не совсем легальных фондов, принадлежащих правительству США. Чтобы осуществить свой темный замысел, Гэбриэл нанимает талантливого хакера по имени Стенли Джобсон, отсидевшего срок за проникновение в компьютерную сеть ФБР.\r\n\r\nОставшийся без гроша за душой, Стенли принимает предложение Гэбриэла, и оказывается в самой гуще событий, которые не обещают ничего хорошего. Взявшись на реализацию плана, Стэнли внезапно осознает, что ему уготована роль пешки в большой опасной игре.",
  "year": 2001,
  "length": 99,
  "verified": 1,
  "created_at": "2014-06-01 10:00:00",
  "updated_at": "2020-06-13 14:27:12",
  "cover": "/data/img/covers/films/1664.webp?hash=d4f48140e738130f8804fdbf5e9c6cbc",
  "rating": {
    "average": 5,
    "count": 2
  },
  "simple_relations": [],
  "genres": [
    {
      "id": 20,
      "name": "Экшены"
    },
    {
      "id": 30,
      "name": "Криминал"
    },
    {
      "id": 45,
      "name": "Триллеры"
    }
  ],
  "collections": [
    {
      "id": 21,
      "name": "Хакеры"
    },
    {
      "id": 43,
      "name": "Ограбления"
    }
  ],
  "screenwriters": [
    {
      "id": 7287,
      "name": "Скип Вудс"
    }
  ],
  "directors": [
    {
      "id": 3157,
      "name": "Доминик Сена"
    }
  ],
  "producers": [
    {
      "id": 2896,
      "name": "Джонатан Д. Крэйн"
    },
    {
      "id": 3000,
      "name": "Джоэл Силвер"
    },
    {
      "id": 10392,
      "name": "Брюс Берман"
    }
  ],
  "actors": [
    {
      "id": 10304,
      "name": "Джон Траволта"
    },
    {
      "id": 17181,
      "name": "Хью Джекман"
    },
    {
      "id": 16395,
      "name": "Холли Берри"
    },
    {
      "id": 12199,
      "name": "Дон Чидл"
    },
    {
      "id": 7571,
      "name": "Сэм Шепард"
    },
    {
      "id": 11562,
      "name": "Винни Джонс"
    }
  ],
  "countries": [
    {
      "id": 1,
      "name": "Австралия"
    },
    {
      "id": 20,
      "name": "США"
    }
  ],
  "rates": [
    {
      "id": 1681,
      "user_id": 1,
      "rate": 6
    },
    {
      "id": 5513,
      "user_id": 305,
      "rate": 4
    }
  ]
}
```

</details>

## Games

Выборки игр.

### Game

Выбор конкретной игры по id.

* Path: /games/{id}
* Method: GET
* Variables: _int_ id
* Response format: JSON

#### Game Request

`https://buhurt.ru/api/games/391`

#### Game Response

<details><summary>Example</summary>

```
{
  "id": 391,
  "name": "Sword of the Berserk: Guts' Rage",
  "alt_name": [
    "Berserk Millennium Falcon Arc: Chapter of the Flowers of Oblivion"
  ],
  "description": "Действие игры разворачивается в промежутке между событиями 22 и 23 частей манги, когда Гатс и Пак покинули Эльфхельм вместе с Каской, но до того, как они встретились с Фарнезой, Серпико и Исидро.",
  "year": 1999,
  "verified": 0,
  "created_at": "2019-06-10 15:09:26",
  "updated_at": "2020-06-13 14:34:02",
  "cover": "/data/img/covers/games/391.webp?hash=2d11455646f8bb6a676d61021a2c5a35",
  "rating": {
    "average": 0,
    "count": 0
  },
  "simple_relations": [
    {
      "id": 9321,
      "relation_id": 5,
      "relation": "Спин-офф",
      "element_type": "Book",
      "element_id": 17897
    }
  ],
  "genres": [
    {
      "id": 65,
      "name": "Экшен"
    },
    {
      "id": 59,
      "name": "Квесты"
    }
  ],
  "collections": [],
  "platforms": [
    {
      "id": 29,
      "name": "Sega Dreamcast",
      "pivot": {
        "game_id": 391,
        "platform_id": 29
      }
    }
  ],
  "developers": [
    {
      "id": 906,
      "name": "Yuke's Media Creations"
    }
  ],
  "games_publishers": [
    {
      "id": 11,
      "name": "Eidos Interactive"
    }
  ],
  "rates": []
}
```

</details>

## Albums

Выборки альбомов.

### Album

Выбор конкретного альбома по id.

* Path: /albums/{id}
* Method: GET
* Variables: _int_ id
* Response format: JSON

#### Album Request

`https://buhurt.ru/api/albums/518`

#### Album Response

<details><summary>Example</summary>

```
{
  "id": 518,
  "name": "Стена",
  "year": 1994,
  "verified": 1,
  "created_at": "2014-06-01 10:00:00",
  "updated_at": "2020-06-13 14:27:30",
  "cover": "/data/img/covers/albums/518.webp?hash=552fdb2a7b353556cdb939c68d30e54b",
  "rating": {
    "average": 9,
    "count": 1
  },
  "simple_relations": [],
  "bands": [
    {
      "id": 306,
      "name": "Чёрный Обелиск"
    }
  ],
  "tracks": [
    {
      "id": 11627,
      "name": "Стена (настоящая)"
    },
    {
      "id": 11628,
      "name": "Дом желтого сна"
    },
    {
      "id": 11629,
      "name": "Цезарь"
    },
    {
      "id": 11630,
      "name": "Меч"
    },
    {
      "id": 11631,
      "name": "Серый святой (в рок'н'ролле)"
    },
    {
      "id": 11632,
      "name": "Игрок (акустический вариант)"
    },
    {
      "id": 11633,
      "name": "Болезнь"
    },
    {
      "id": 11634,
      "name": "Черный Обелиск"
    },
    {
      "id": 11635,
      "name": "Стена (акустический вариант)"
    }
  ],
  "rates": [
    {
      "id": 8135,
      "user_id": 1,
      "rate": 9
    }
  ]
}
```

</details>
