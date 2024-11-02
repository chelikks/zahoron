<?php

function adminPages(){
    $admin_pages=[
        ['Кладбища','storage/uploads/mdi_grave-stone (1).svg',
            [
                ['Список','account.admin.cemetery'],
                ['Импортировать','account.admin.parser.cemetery'],
                // ['Добавить кладбища','account.admin.create.cemetery']
            ],
        ],
        ['Морги','storage/uploads/game-icons_morgue-feet (2).svg',
            [
                ['Список','account.admin.mortuary'],
                ['Импортировать','account.admin.parser.mortuary'],
                // ['Добавить кладбища','account.admin.create.cemetery']
            ],
        ],
        ['Крематории','storage/uploads/emojione-monotone_funeral-urn.svg',
            [
                ['Список','account.admin.crematorium'],
                ['Импортировать','account.admin.parser.crematorium'],
                // ['Добавить кладбища','account.admin.create.cemetery']
            ],
        ],
        ['Колумбарии','storage/uploads/mdi_grave-stone (2).svg',
            [
                ['Список','account.admin.columbarium'],
                ['Импортировать','account.admin.parser.columbarium'],
                // ['Добавить кладбища','account.admin.create.cemetery']
            ],
        ]
       
    ];
    return $admin_pages;
}


?>