<?
return array(
    'default_menu' => array('page' => 'penztar'),
    'template_class' => 'Admin_Template',
    'component_slots' => array(
        'menu' => '',
        'login' => '',
        'page' => '',
        'messages' => 'uzenetek'
    ),
    'components' => array(
        'penztar' => array(
            'class' => 'PenztarKomponens',
            'params' => array(),
            'allowed_slots' => array('page')
        ),
        'uzenetek' => array(
            'class' => 'Uzenetek_Site_Component',
            'allowed_slots' => array()
        )
    )
);