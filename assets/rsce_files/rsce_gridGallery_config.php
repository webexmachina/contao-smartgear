<?php
return array
(
    'label' => array('Galerie responsive', 'Générez une galerie d\'images personnalisée utilisant une grille responsive'),
    'types' => array('content'),
    'contentCategory' => 'texts',
    'standardFields' => array('cssID'),
    'fields' => array
    (
        'headline' => array
        (
            'inputType' => 'standardField'
            ,'eval' => array('tl_class' => 'w50')
        ),
        'nbCols_default' => array
        (
            'label' => array('Nombre de colonnes', 'Indiquez le nombre de colonnes souhaité (entre 1 et 12)'),
            'inputType' => 'text',
            'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50 clr', 'min'=>1, 'max'=>12, 'mandatory'=>true)
        ),
        'responsive_legend' => array
        (
            'label' => array('Configuration responsive')
            ,'inputType' => 'group'
        ),
        'nbCols_xl' => array
        (
            'label' => array('Nombre de colonnes < 1400px', 'Indiquez le nombre de colonnes souhaité (entre 1 et 12)'),
            'inputType' => 'text',
            'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50 clr', 'min'=>1, 'max'=>12)
        ),
        'nbCols_lg' => array
        (
            'label' => array('Nombre de colonnes < 1200px', 'Indiquez le nombre de colonnes souhaité (entre 1 et 12)'),
            'inputType' => 'text',
            'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50 clr', 'min'=>1, 'max'=>12)
        ),
        'nbCols_md' => array
        (
            'label' => array('Nombre de colonnes < 992px', 'Indiquez le nombre de colonnes souhaité (entre 1 et 12)'),
            'inputType' => 'text',
            'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50 clr', 'min'=>1, 'max'=>12)
        ),
        'nbCols_sm' => array
        (
            'label' => array('Nombre de colonnes < 768px', 'Indiquez le nombre de colonnes souhaité (entre 1 et 12)'),
            'inputType' => 'text',
            'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50 clr', 'min'=>1, 'max'=>12)
        ),
        'nbCols_xs' => array
        (
            'label' => array('Nombre de colonnes < 620px', 'Indiquez le nombre de colonnes souhaité (entre 1 et 12)'),
            'inputType' => 'text',
            'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50 clr', 'min'=>1, 'max'=>12)
        ),
        'nbCols_xxs' => array
        (
            'label' => array('Nombre de colonnes < 520px', 'Indiquez le nombre de colonnes souhaité (entre 1 et 12)'),
            'inputType' => 'text',
            'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50 clr', 'min'=>1, 'max'=>12)
        ),
        'listItems' => array
        (
            'label' => array('Images', 'Editez les images')
            ,'elementLabel' => '%s. image'
            ,'inputType' => 'list'
            ,'fields' => array
            (
                'img_legend' => array
                (
                    'label' => array('Configuration de l\'image')
                    ,'inputType' => 'group'
                ),
                'img_src' => array
                (
                    'label' => array('Image', 'Sélectionnez une image')
                    ,'inputType' => 'fileTree'
                    ,'eval' => array('filesOnly'=>true, 'fieldType'=>'radio', 'extensions'=>Config::get('validImageTypes'),'tl_class'=>'w50','mandatory'=>true)
                ),
                'img_alt' => array
                (
                    'label' => &$GLOBALS['TL_LANG']['tl_content']['alt']
                    ,'inputType' => 'text'
                    ,'eval' => array('tl_class'=>'w50')
                ),
                'link_legend' => array
                (
                    'label' => array('Configuration du lien')
                    ,'inputType' => 'group'
                ),
                'href' => array
                (
                    'label' => &$GLOBALS['TL_LANG']['MSC']['url']
                    ,'inputType' => 'text'
                    ,'eval' => array('rgxp'=>'url', 'tl_class' => 'w50 wizard ')
                    ,'wizard' => array(array('tl_content', 'pagePicker'))
                ),
                'title' => array
                (
                    'label' => &$GLOBALS['TL_LANG']['tl_content']['titleText']
                    ,'inputType' => 'text'
                    ,'eval' => array('tl_class'=>'w50')
                ),
                'target' => array
                (
                    'label' => &$GLOBALS['TL_LANG']['tl_content']['fullsize']
                    ,'inputType' => 'checkbox'
                    ,'eval' => array('tl_class'=>'w50')
                ),
                'misc_legend' => array
                (
                    'label' => array('Configuration avancée')
                    ,'inputType' => 'group'
                ),
                'span_cols' => array
                (
                    'label' => array('Empiétement - colonnes', 'Indiquez le nombre de colonnes sur lequel doit s\'étendre l\'image'),
                    'inputType' => 'text',
                    'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50', 'min'=>1, 'max'=>12)
                ),
                'span_rows' => array
                (
                    'label' => array('Empiétement - lignes', 'Indiquez le nombre de lignes sur lequel doit s\'étendre l\'image'),
                    'inputType' => 'text',
                    'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50', 'min'=>1, 'max'=>12)
                ),
                'classes' => array
                (
                    'label' => array('Classes supplémentaires', 'Indiquez, si souhaité, la ou les classes css à ajouter a l\'image')
                    ,'inputType' => 'text'
                    ,'eval' => array('tl_class'=>'w50 clr')
                )
            )
        ),
    ),
);