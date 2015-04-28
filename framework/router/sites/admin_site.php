<?
return array(
    'default_menu' => array('page' => 'teszt'),
    'template_class' => "Admin_Template",
    'component_slots' => array(
        'menu' => '',
        'login' => '',
        'page' => '',
        'messages' => 'uzenetek'
    ),
    'components' => array(
        'teszt' => array(
            'class' => 'Teszt_Komponens',
            'params' => array(),
            'allowed_slots' => array('page')
        ),
        'felhasznalo' => array(
            'class' => 'FelhasznaloKomponens',
            'params' => '',
            'allowed_slots' => array('page')
        ),
        'ugyfel'=> array(
            'class'=>'UgyfelKomponens',
            'params' => '',
            'allowed_slots' => array('page')
        ),
        'penztar' => array(
            'class' => 'PenztarKomponens',
            'params' => '',
            'allowed_slots' => array('page')
        ),
        'szamla' => array(
            'class' => 'SzamlaKomponens',
            'params' => '',
            'allowed_slots' => array('page')
        ),
        'uzenetek' => array(
            'class' => 'Uzenetek_Site_Component',
            'allowed_slots' => array()
        )
    )
);
